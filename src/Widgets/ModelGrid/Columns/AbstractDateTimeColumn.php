<?php
declare(strict_types=1);

namespace SP\Admin\Widgets\ModelGrid\Columns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use SP\Admin\Helpers\Formatter;

/**
 * Base class for columns with dates and times.
 *
 * @package SP\Admin\Widgets\ModelGrid\Columns
 */
abstract class AbstractDateTimeColumn extends ModelColumn
{
    public const DATETIME_RANGE_SEPARATOR = ' : ';

    /**
     * @var string
     */
    protected string $input_id;

    /**
     * CreatedAtColumn constructor.
     */
    public function __construct()
    {
        $this->input_id = 'drp_' . Str::random(8);
    }

    /**
     * {@inheritDoc}
     * @throws \Throwable
     */
    public function boot(): void
    {
        // attribute
        $attribute = $this->getAttribute();

        // filter
        $input_id = $this->input_id;
        $plugin_js = $this->filterJs();

        $this->setFilter(static function (string $filter_input_class) use ($attribute, $input_id, $plugin_js): string {
            $input_name = $attribute;
            $input_value = request()->query($attribute, '');

            $input = '<input class="form-control form-control-sm ' . $filter_input_class . '" id="' . $input_id . '" type="text" name="' . $input_name . '" value="' . $input_value . '">';

            return $input . $plugin_js;
        });

        // value
        $this->setValue(fn(Model $item) => Formatter::isoToLocalDateTime($item->$attribute));
    }

    /**
     * Initializes plugin.
     *
     * @return string
     */
    protected function filterJs(): string
    {
        $id = $func_name = $this->input_id;

        $separator = self::DATETIME_RANGE_SEPARATOR;

        $translate_apply = trans('Apply');
        $translate_cancel = trans('Cancel');
        $translate_from = trans('From');
        $translate_to = trans('To');
        $translate_clear = trans('Clear');

        return <<<JS
<script>
window.deferredCallbacks.$func_name = function (w, d, $) {
    'use strict';

    w.moment.locale(
        w.adminLang
    );
    
    const format = 'YYYY-MM-DD';
    const separator = '$separator';

    \$('#$id').daterangepicker({
        autoUpdateInput: false,
        locale: {
            format,
            separator,
            applyLabel: '$translate_apply',
            //cancelLabel: '$translate_cancel',
            fromLabel: '$translate_from',
            toLabel: '$translate_to',
            cancelLabel: '$translate_clear'
        }
    });
    
    \$('#$id').on('apply.daterangepicker', function(ev, picker) {
        let input = \$(this);
        
        input.val(
            picker.startDate.format(format) + separator + picker.endDate.format(format)
        );
        d.getElementById('$id').dispatchEvent(new Event('change'));
    });

    \$('#$id').on('cancel.daterangepicker', function(ev, picker) {
        let input = \$(this);
        
        input.val('');
        d.getElementById('$id').dispatchEvent(new Event('change'));
    });
};
</script>
JS;
    }

}
