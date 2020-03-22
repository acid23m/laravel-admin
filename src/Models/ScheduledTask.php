<?php
declare(strict_types=1);

namespace SP\Admin\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Kyslik\ColumnSortable\Sortable;
use SP\Admin\UseCases\Databases\ScheduledTask as ScheduledTaskDatabase;

/**
 * Scheduled tasks.
 *
 * @property int $id
 * @property string $name
 * @property string $min
 * @property string $hour
 * @property string $day
 * @property string $month
 * @property string $week_day
 * @property string $command
 * @property string $out_file
 * @property int $file_write_method
 * @property string $report_email
 * @property bool $report_only_error
 * @property bool $active
 * @property string|Carbon $created_at
 * @property string|Carbon $updated_at
 *
 * @package SP\Admin\Models
 */
final class ScheduledTask extends Model
{
    use Sortable;

    public const REWRITE_FILE = 0;
    public const APPEND_TO_FILE = 1;

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'min' => '*',
        'hour' => '*',
        'day' => '*',
        'month' => '*',
        'week_day' => '*',
        'file_write_method' => self::REWRITE_FILE,
    ];
    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = ScheduledTaskDatabase::DB_CONNECTION;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'min',
        'hour',
        'day',
        'month',
        'week_day',
        'command',
        'out_file',
        'file_write_method',
        'report_email',
        'report_only_error',
        'active',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'report_only_error' => 'boolean',
        'active' => 'boolean',
    ];
    /**
     * The attributes that should be sortable.
     *
     * @var array
     */
    public array $sortable = [
        'name',
        'active',
    ];

    /**
     * {@inheritDoc}
     */
    public static function attributeLabels(): array
    {
        return [
            'name' => trans('Title'),
            'min' => trans('Minute'),
            'hour' => trans('Hour'),
            'day' => trans('Day'),
            'month' => trans('Month'),
            'week_day' => trans('Day of Week'),
            'command' => trans('Command'),
            'out_file' => trans('Output to file'),
            'file_write_method' => trans('File writing method'),
            'report_email' => trans('E-mail the output'),
            'report_only_error' => trans('E-mail only on failure'),
            'active' => trans('Is Active'),
            'created_at' => trans('Creation Date'),
            'updated_at' => trans('Modification Date'),
        ];
    }

    /**
     * Filters query.
     *
     * @param Builder $query
     * @param array $params Uri parameters
     * @return Builder
     */
    public function scopeFilter(Builder $query, array $params = []): Builder
    {
        $params = \array_map('trim', $params);

        if (isset($params['name']) && filled($params['name'])) {
            $query->where('name', 'like', "%{$params['name']}%");
        }

        if (isset($params['active']) && filled($params['active'])) {
            $is_active = $params['active'] === 'true';
            $query->where('active', '=', $is_active);
        }

        return $query;
    }

}
