<?php
declare(strict_types=1);

namespace SP\Admin\Models;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;
use SP\Admin\Traits\ModelLabels;
use SP\Admin\Traits\ModelScopes;

/**
 * Base Model class.
 *
 * @package SP\Admin\Models
 */
class Model extends BaseModel
{
    use ModelLabels;
    use ModelScopes;

    /**
     * The storage format of the model's date columns.
     *
     * @var string
     */
    protected $dateFormat = STANDARD_FORMAT__DATETIME;

    /**
     * {@inheritDoc}
     */
    public function freshTimestamp(): Carbon
    {
        // always use UTC
        return Carbon::now('UTC');
    }

    /**
     * {@inheritDoc}
     */
    protected function asDateTime($value): Carbon
    {
        // always use UTC

        if ($value instanceof CarbonInterface) {
            return Date::instance($value);
        }

        if ($value instanceof \DateTimeInterface) {
            return Date::parse(
                $value->format('Y-m-d H:i:s.u'), $value->getTimezone()
            );
        }

        if (is_numeric($value)) {
            return Date::createFromTimestamp($value, 'UTC');
        }

        if ($this->isStandardDateFormat($value)) {
            return Date::instance(Carbon::createFromFormat('Y-m-d', $value, 'UTC')->startOfDay());
        }

        $format = $this->getDateFormat();

        if (version_compare(PHP_VERSION, '7.3.0-dev', '<')) {
            $format = str_replace('.v', '.u', $format);
        }

        return Date::createFromFormat($format, $value, 'UTC');
    }

    /**
     * {@inheritDoc}
     */
    public function fromDateTime($value): ?string
    {
        // always use UTC
        return empty($value) ? $value : $this->asDateTime($value)->timezone('UTC')->format(
            $this->getDateFormat()
        );
    }

}
