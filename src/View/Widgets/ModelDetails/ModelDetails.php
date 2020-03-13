<?php
declare(strict_types=1);

namespace SP\Admin\View\Widgets\ModelDetails;

use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use SP\Admin\View\Widgets\ModelDetails\Rows\{ModelRow, Row};

/**
 * Displays model details.
 *
 * @package SP\Admin\View\Widgets\ModelDetails
 */
class ModelDetails implements Htmlable
{
    /**
     * @var Model|null
     */
    private ?Model $model;
    /**
     * @var array|Row[] Rows should be showed in the table
     */
    private array $attributes;

    /**
     * ModelDetails constructor.
     *
     * @param array $config
     * @throws \Throwable
     */
    public function __construct(array $config)
    {
        // checks model
        if (isset($config['model'])) {
            $this->model = $config['model'];
        }

        // checks attributes
        throw_if(
            !isset($config['attributes']) || !\is_array($config['attributes']),
            \LogicException::class,
            'The "attributes" parameter is not configured.'
        );

        $this->attributes = $this->normalizeRows($config['attributes']);
    }

    /**
     * Renders details.
     *
     * @return string Html
     * @throws \Throwable
     */
    public function render(): string
    {
        return $this->table();
    }

    /**
     * Fills attributes array with  attribute objects.
     *
     * @param array $config_attributes
     * @return array
     * @throws \LogicException
     */
    protected function normalizeRows(array &$config_attributes): array
    {
        $rows = [];

        /**
         * Creates row object from array and initiates it.
         *
         * @param array $row
         * @return Row
         */
        $rowFromArray = static function (array &$row): Row {
            if (isset($row['attribute']) && filled($row['attribute'])) {
                $r = resolve(ModelRow::class);
                $r->setAttribute($row['attribute']);
            } else {
                $r = resolve(Row::class);
            }
            $r->setLabel($row['label'] ?? null);
            $r->setValue($row['value'] ?? null);

            return $r;
        };

        /**
         * Creates row object from classname and initiates it.
         *
         * @param string $row
         * @return Row
         */
        $rowFromClassname = static function (string &$row): Row {
            $r = resolve($row);
            throw_if(
                !($r instanceof Row),
                \LogicException::class,
                'The row must be instance of \SP\Admin\View\Widgets\ModelDetails\Rows\Row class.'
            );
            $r->boot();

            return $r;
        };

        /**
         * Initiates row object.
         *
         * @param Row $row
         * @return Row
         */
        $rowFromInstance = static function (Row &$row): Row {
            $r = clone $row;
            $r->boot();

            return $r;
        };

        foreach ($config_attributes as $index => $row) {
            if (\is_array($row)) {
                $r = $rowFromArray($row);
            } elseif (\is_string($row)) {
                $r = $rowFromClassname($row);
            } elseif ($row instanceof Row) {
                $r = $rowFromInstance($row);
            } else {
                throw new \LogicException("The row ($index) must be array, classname or instance of \SP\Admin\View\Widgets\ModelDetails\Rows\Row class.");
            }

            $rows[] = $r;
        }

        return $rows;
    }

    /**
     * Renders label of the row.
     *
     * @param Row $row Current row definition
     * @return string
     */
    protected function rowLabel(Row $row): string
    {
        $label = $row->getLabel();
        $grid = $this;

        if ($label === null) {
            $label = rescue(static function () use (&$row, &$grid): string {
                /** @var Model $model */
                $model = $grid->model;

                return $model::getAttributeLabel($row->getAttribute()); // checks if model uses ModelLabels trait
            }, '', false);
        }

        return $label;
    }

    /**
     * Renders value of the row.
     *
     * @param Model $item
     * @param Row $row
     * @return string
     * @throws \Throwable
     */
    protected function rowValue(Model $item, Row $row): string
    {
        if ($row instanceof ModelRow && $row->getValue() === null) {
            $attribute = $row->getAttribute();
            try {
                return (string)$item->$attribute;
            } catch (\Throwable $e) {
                throw new \InvalidArgumentException("The attribute $attribute is not found in the Model.");
            }
        }

        if ($row->getValue() instanceof \Closure) {
            return $row->getValue()($item);
        }

        return (string)$row->getValue();
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
        $html .= '<table class="table table-bordered table-striped">';

        // body
        $html .= '<tbody>';

        foreach ($this->attributes as &$row) {
            $html .= '<tr>';

            $html .= '<td>' . $this->rowLabel($row) . '</td>';
            $html .= '<td>' . $this->rowValue($this->model, $row);

            $html .= '</tr>';
        }
        unset($row);

        $html .= '</tbody>';
        // /body

        $html .= '</table>';
        $html .= '</div>';

        return $html;
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
