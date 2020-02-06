<?php
declare(strict_types=1);

namespace SP\Admin\Widgets\ModelGrid;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\{Collection, Model};
use Illuminate\Pagination\{AbstractPaginator, LengthAwarePaginator, Paginator};
use Illuminate\Support\Str;
use Kyslik\ColumnSortable\SortableLink;
use SP\Admin\Widgets\ModelGrid\Columns\{Column, ModelColumn};

/**
 * Displays table filled with model\'s collection data.
 *
 * @package SP\Admin\Widgets\ModelGrid
 */
class ModelGrid implements Htmlable
{
    protected const FILTER_INPUT_CLASS = 'js-modelgrid-filter-input';

    /**
     * @var string Id for table tag
     */
    private string $id;
    /**
     * @var string Classname of the collection items
     */
    private string $model_class;
    /**
     * @var Collection|AbstractPaginator
     */
    private $collection;
    /**
     * @var array|Column[] Columns should be showed in the table
     */
    private array $columns;

    /**
     * DataGrid constructor.
     *
     * @param array $config
     * @throws \Throwable
     */
    public function __construct(array $config)
    {
        // create id for table tag
        $this->id = 'modelgrid-' . Str::random(8);

        // checks model class
        throw_if(
            !isset($config['model_class']) || blank($config['model_class']),
            \LogicException::class,
            'The "model_class" parameter is not configured.'
        );

        $this->model_class = $config['model_class'];

        // checks data provider
        throw_if(
            !isset($config['collection']),
            \LogicException::class,
            'The "collection" parameter is not configured.'
        );
        throw_if(
            !($config['collection'] instanceof Collection) && !($config['collection'] instanceof AbstractPaginator),
            \LogicException::class,
            'The collection must be instance of \Illuminate\Database\Eloquent\Collection or \Illuminate\Pagination\Paginator or \Illuminate\Pagination\LengthAwarePaginator.'
        );

        $this->collection = $config['collection'];

        // checks columns
        throw_if(
            !isset($config['columns']) || !\is_array($config['columns']),
            \LogicException::class,
            'The "columns" parameter is not configured.'
        );

        $this->columns = $this->normalizeColumns($config['columns']);
    }

    /**
     * Renders table and scripts.
     *
     * @return string HTML
     * @throws \Throwable
     */
    public function render(): string
    {
        return $this->tableJs()
            . $this->summary()
            . $this->table()
            . $this->pagination();
    }

    /**
     * Fills columns array with column objects.
     *
     * @param array $config_columns
     * @return array
     */
    protected function normalizeColumns(array &$config_columns): array
    {
        $columns = [];

        /**
         * Creates column object from array and initiates it.
         *
         * @param array $column
         * @return Column
         */
        $columnFromArray = static function (array &$column): Column {
            if (isset($column['attribute']) && filled($column['attribute'])) {
                $c = resolve(ModelColumn::class);
                $c->setAttribute($column['attribute']);
                $c->setFilter($column['filter'] ?? true);
            } else {
                $c = resolve(Column::class);
            }
            $c->setLabel($column['label'] ?? null);
            $c->setValue($column['value'] ?? null);
            $c->setCellWidth($column['cell_width'] ?? null);
            $c->setCellClass($column['cell_class'] ?? null);

            return $c;
        };

        /**
         * Creates column object from classname and initiates it.
         *
         * @param string $column
         * @return Column
         */
        $columnFromClassName = static function (string &$column): Column {
            $c = resolve($column);
            throw_if(
                !($c instanceof Column),
                \LogicException::class,
                'The column must be instance of \SP\Admin\Widgets\ModelGrid\Columns\Column class.'
            );
            $c->boot();

            return $c;
        };

        /**
         * Initiates column object.
         *
         * @param Column $column
         * @return Column
         */
        $columnFromInstance = static function (Column &$column): Column {
            $c = clone $column;
            $c->boot();

            return $c;
        };

        foreach ($config_columns as $index => &$column) {
            if (\is_array($column)) {
                $c = $columnFromArray($column);
            } elseif (\is_string($column)) {
                $c = $columnFromClassName($column);
            } elseif ($column instanceof Column) {
                $c = $columnFromInstance($column);
            } else {
                throw new \LogicException("The column ($index) must be array, classname or instance of \SP\Admin\Widgets\ModelGrid\Columns\Column class.");
            }

            $columns[] = $c;
        }

        return $columns;
    }

    /**
     * Renders label for table header.
     *
     * @param Column $column Current column definition
     * @return string
     */
    protected function columnLabel(Column $column): string
    {
        $label = $column->getLabel();
        $grid = $this;

        if ($label === null) {
            $label = rescue(static function () use (&$column, &$grid): string {
                $model_class = $grid->model_class;

                return $model_class::getAttributeLabel($column->getAttribute()); // checks if model uses ModelLabels trait
            }, '', false);
        }

        // sort link
        $label = rescue(static function () use ($label, &$column, &$grid): string {
            /** @var Model $item */
            $item = new $grid->model_class;

            return \in_array($column->getAttribute(), $item->sortable, true)
                ? SortableLink::render([$column->getAttribute(), $label])
                : $label;
        }, $label, false);

        return $label;
    }

    /**
     * Renders filter input.
     *
     * @param Column $column Current column definition
     * @return string|null
     * @throws \Throwable
     */
    protected function columnFilter(Column $column): ?string
    {
        // skip
        if (!($column instanceof ModelColumn)) {
            return null;
        }

        $filter = $column->getFilter();
        $filter_input_class = self::FILTER_INPUT_CLASS;

        // user defined
        if ($filter instanceof \Closure) {
            return $filter($filter_input_class);
        }

        // input.text
        if ($filter === true) {
            $input_name = $column->getAttribute();
            $input_value = request()->query($column->getAttribute(), '');

            return '<input class="form-control form-control-sm ' . $filter_input_class . '" type="text" name="' . $input_name . '" value="' . $input_value . '">';
        }

        // select
        if (\is_array($filter)) {
            $uri_param = request()->query($column->getAttribute(), '');
            $select = '<select class="custom-select custom-select-sm ' . $filter_input_class . '" name="' . $column->getAttribute() . '">';

            // checks empty option
            if (\array_key_first($filter) !== '') {
                $selected = $uri_param === '' ? 'selected' : '';
                $select .= '<option value="" ' . $selected . '></option>';
            }

            foreach ($filter as $opt_key => $opt_val) {
                $selected = (string)$uri_param === (string)$opt_key ? 'selected' : '';
                $select .= '<option value="' . $opt_key . '" ' . $selected . '>';
                $select .= $opt_val;
                $select .= '</option>';
            }
            $select .= '</select>';

            return $select;
        }

        return null;
    }

    /**
     * Renders value for the table cell.
     *
     * @param Model $item Current item of the collection
     * @param Column $column Current column definition
     * @param int $index Zero-based number of the current column
     * @return string
     * @throws \Throwable
     */
    protected function cellValue(Model $item, Column $column, int $index): string
    {
        if ($column instanceof ModelColumn && $column->getValue() === null) {
            $attribute = $column->getAttribute();
            try {
                return (string)$item->$attribute;
            } catch (\Throwable $e) {
                throw new \InvalidArgumentException("The attribute $attribute is not found in the Model.");
            }
        }

        if ($column->getValue() instanceof \Closure) {
            return $column->getValue()($item, $index);
        }

        return (string)$column->getValue();
    }

    /**
     * Table with data.
     *
     * @return string HTML
     * @throws \Throwable
     */
    protected function table(): string
    {
        $html = '<div class="table-responsive">';
        $html .= '<table class="table table-bordered table-hover" id="' . $this->id . '">';

        // header
        $html .= '<thead>';

        // labels
        $html .= '<tr>';
        foreach ($this->columns as &$column) {
            $label = $this->columnLabel($column);
            $html .= <<<LABEL
<th scope="col">
    <div class="d-flex justify-content-between align-items-center">
        $label
    </div>
</th>
LABEL;
        }
        unset($column, $label);
        $html .= '</tr>';
        // /labels


        // filters
        $html .= '<tr>';
        foreach ($this->columns as &$column) {
            $input = $this->columnFilter($column);
            $html .= <<<LABEL
<td>
    <div class="form-group m-0">
        $input
    </div>
</td>
LABEL;
        }
        unset($column, $input);
        $html .= '</tr>';
        // /filters

        $html .= '</thead>';
        // /header


        // body
        $html .= '<tbody>';

        if ($this->collection->isEmpty()) { // no data
            $html .= '<tr>';
            $html .= '<td colspan="' . \count($this->columns) . '">';
            $html .= trans('No data');
            $html .= '</td>';
            $html .= '</tr>';
        } else { // with data
            $index = 0;
            foreach ($this->collection as &$item) {
                $html .= '<tr>';
                foreach ($this->columns as $column) {
                    $cell_width = $column->getCellWidth() !== null
                        ? 'style="width:' . $column->getCellWidth() . 'px;"'
                        : '';
                    $cell_class = $column->getCellClass() !== null
                        ? 'class="' . $column->getCellClass() . '"'
                        : '';

                    $html .= "<td $cell_class $cell_width>";
                    $html .= $this->cellValue($item, $column, $index);
                    $html .= '</td>';
                }
                $html .= '</tr>';
                $index ++;
            }
            unset($item, $cell_width, $cell_class);
        }

        $html .= '</tbody>';
        // /body

        $html .= '</table>';
        $html .= '</div>';

        return $html;
    }

    /**
     * Pagination links.
     *
     * @return string
     */
    protected function pagination(): string
    {
        if (!($this->collection instanceof AbstractPaginator)) {
            return '';
        }

        $pagination_links = $this->collection
            ->appends(
                request()->except('page')
            )
            ->links();

        return <<<PAGER
<div class="d-flex justify-content-end">
    $pagination_links
</div>
PAGER;
    }

    /**
     * Information block.
     *
     * @return string
     */
    protected function summary(): string
    {
        $info = '';
        if ($this->collection instanceof Collection) {
            $info = trans('Total') . ' ' . $this->collection->count();
        } elseif ($this->collection instanceof LengthAwarePaginator) {
            $info = $this->collection->firstItem() . ' - ' . $this->collection->lastItem() . ' / ' . $this->collection->total();
        } elseif ($this->collection instanceof Paginator) {
            $info = $this->collection->firstItem() . ' - ' . $this->collection->lastItem();
        }

        $reset_link = '<a class="text-decoration-none" href="' . request()->url() . '" title="' . trans('Reset') . '">';
        $reset_link .= '<i class="fa fa-sync-alt"></i>';
        $reset_link .= '</a>';

        return <<<SUM
<div class="d-flex justify-content-end mb-2">
    <div class="mr-3"><small>$info</small></div>
    <div class="mr-1">$reset_link</div>
</div>
SUM;
    }

    /**
     * Scripts for table.
     *
     * @return string
     */
    protected function tableJs(): string
    {
        $filter_input_class = self::FILTER_INPUT_CLASS;

        return <<<JS
<script>
window.deferredCallbacks.modelGrid = function (w, d) {
    'use strict';

    let dataTable = d.getElementById('{$this->id}');
    let filterInputs = dataTable.querySelectorAll('.$filter_input_class');
    
    const applyFilters = function (e) {
        //e.preventDefault();
        
        dataTable.insertAdjacentHTML('beforebegin', '<form id="filter-{$this->id}" method="get" action=""></form>');
        
        let filterForm = d.getElementById('filter-{$this->id}');
        
        filterForm.appendChild(dataTable);
        filterForm.submit();
    };

    filterInputs.forEach(function (filterInput) {
        filterInput.addEventListener('change', applyFilters, false);
    });
};
</script>
JS;
    }

    /**
     * Gets content as a string of HTML.
     *
     * @return string
     * @throws \Throwable
     */
    public function toHtml(): string
    {
        return $this->render();
    }

    /**
     * @return string
     * @throws \Throwable
     */
    public function __toString()
    {
        return $this->render();
    }

}
