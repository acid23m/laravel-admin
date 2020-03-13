<?php
declare(strict_types=1);

namespace SP\Admin\Models\Repositories;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Http\Request;
use Illuminate\Pagination\AbstractPaginator;
use SP\Admin\Models\ScheduledTask;
use SP\Admin\View\Widgets\ModelDetails\Rows\{ActiveRow, CreatedAtRow, UpdatedAtRow};
use SP\Admin\View\Widgets\ModelGrid\Columns\{ActionColumn, ActiveColumn, IndexColumn};

/**
 * Scheduled tasks repository.
 *
 * @package SP\Admin\Models\Repositories
 */
final class ScheduledTaskRepository
{
    private const DEFAULT_PER_PAGE = 20;

    /**
     * @var Request
     */
    private Request $request;

    /**
     * ScheduledTaskRepository constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Paginated tasks collection.
     *
     * @return AbstractPaginator
     */
    public function getTasksForModelGrid(): AbstractPaginator
    {
        $params = $this->request->query();

        $per_page = $params['per_page'] ?? self::DEFAULT_PER_PAGE;

        return ScheduledTask::filter($params)->sortable()->paginate($per_page);
    }

    /**
     * Config for modelGrid widget.
     *
     * @return array
     */
    public function modelGridConfig(): array
    {
        return [
            'model_class' => ScheduledTask::class,
            'collection' => $this->getTasksForModelGrid(),
            'columns' => [
                IndexColumn::class,
                ['attribute' => 'name'],
                [
                    'attribute' => 'min',
                    'filter' => false,
                ],
                [
                    'attribute' => 'hour',
                    'filter' => false,
                ],
                [
                    'attribute' => 'day',
                    'filter' => false,
                ],
                [
                    'attribute' => 'month',
                    'filter' => false,
                ],
                [
                    'attribute' => 'week_day',
                    'filter' => false,
                ],
                ActiveColumn::class,
                new ActionColumn([
                    'view' => fn(ScheduledTask $model): string => route('admin.scheduled-tasks.show', $model),
                    'edit' => fn(ScheduledTask $model): string => route('admin.scheduled-tasks.edit', $model),
                    'delete' => fn(ScheduledTask $model): string => route('admin.scheduled-tasks.destroy', $model),
                ]),
            ],
        ];
    }

    /**
     * Config for modelDetails widget.
     *
     * @param ScheduledTask $model
     * @return array
     */
    public function modelDetailsConfig(ScheduledTask $model): array
    {
        return [
            'model' => $model,
            'attributes' => [
                ['attribute' => 'id'],
                [
                    'attribute' => 'name',
                    'value' => "<strong>{$model->name}</strong>",
                ],
                ['attribute' => 'min'],
                ['attribute' => 'hour'],
                ['attribute' => 'day'],
                ['attribute' => 'month'],
                ['attribute' => 'week_day'],
                [
                    'attribute' => 'command',
                    'value' => "<code>{$model->command}</code>",
                ],
                ActiveRow::class,
                CreatedAtRow::class,
                UpdatedAtRow::class,
            ],
        ];
    }

    /**
     * Fills the scheduler with tasks.
     *
     * @static
     * @param Schedule $schedule
     * @throws \LogicException
     */
    public static function registerTasks(Schedule $schedule): void
    {
        $tasks = ScheduledTask::active()->get();

        /** @var ScheduledTask $task */
        foreach ($tasks as $task) {
            $name = $task->name;
            $command = $task->command;
            $cron = "$task->min $task->hour $task->day $task->month $task->week_day";
            $out_file = $task->out_file;
            $file_write_method = $task->file_write_method;
            $report_email = $task->report_email;
            $report_only_error = $task->report_only_error;

            $t = $schedule->exec($command)->cron($cron)->description($name);

            if ($out_file !== null) {
                switch ($file_write_method) {
                    case ScheduledTask::REWRITE_FILE:
                        $t->sendOutputTo($out_file);
                        break;
                    case ScheduledTask::APPEND_TO_FILE:
                        $t->appendOutputTo($out_file);
                        break;
                }
            }

            if ($report_email !== null) {
                if ($report_only_error) {
                    $t->emailOutputOnFailure($report_email);
                } else {
                    $t->emailOutputTo($report_email);
                }
            }
        }
    }

    /**
     * List of writing methods for selectbox.
     *
     * @return array
     */
    public function getFileMethodsForSelect(): array
    {
        return [
            ScheduledTask::REWRITE_FILE => trans('Rewrite'),
            ScheduledTask::APPEND_TO_FILE => trans('Append'),
        ];
    }

}
