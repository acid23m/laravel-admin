<?php
declare(strict_types=1);

namespace SP\Admin\Http\Controllers\ScheduledTask;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use SP\Admin\Http\Controllers\AdminController as Controller;
use SP\Admin\Http\Requests\ScheduledTaskRequest;
use SP\Admin\Models\Repositories\ScheduledTaskRepository;
use SP\Admin\Models\ScheduledTask;

/**
 * CRUD for Scheduled Tasks.
 *
 * @package SP\Admin\Http\Controllers\ScheduledTask
 */
final class ScheduledTaskController extends Controller
{
    /**
     * ScheduledTaskController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->authorizeResource(ScheduledTask::class, 'scheduled_task');
    }

    /**
     * Displays a listing of the resource.
     *
     * @param ScheduledTaskRepository $repository
     * @return View
     */
    public function index(ScheduledTaskRepository $repository): View
    {
        return view('admin::scheduled_tasks.index', [
            'modelgrid_config' => $repository->modelGridConfig(),
        ]);
    }

    /**
     * Shows the form for creating a new resource.
     *
     * @param ScheduledTaskRepository $repository
     * @return View
     */
    public function create(ScheduledTaskRepository $repository): View
    {
        return view('admin::scheduled_tasks.create', [
            'model' => new ScheduledTask,
            'method_list' => $repository->getFileMethodsForSelect(),
        ]);
    }

    /**
     * Stores a newly created resource in storage.
     *
     * @param ScheduledTaskRequest $request
     * @return RedirectResponse
     */
    public function store(ScheduledTaskRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $scheduled_tasks = ScheduledTask::create($data);

        if (!$scheduled_tasks) {
            return back()->with('error', trans('Error creating.'));
        }

        return redirect()
            ->route('admin.scheduled-tasks.show', $scheduled_tasks)
            ->with('success', trans('The Record has been created.'));
    }

    /**
     * Displays the specified resource.
     *
     * @param ScheduledTaskRepository $repository
     * @param ScheduledTask $scheduled_task
     * @return View
     */
    public function show(ScheduledTaskRepository $repository, ScheduledTask $scheduled_task): View
    {
        return view('admin::scheduled_tasks.show', [
            'model' => $scheduled_task,
            'modeldetails_config' => $repository->modelDetailsConfig($scheduled_task),
        ]);
    }

    /**
     * Shows the form for editing the specified resource.
     *
     * @param ScheduledTaskRepository $repository
     * @param ScheduledTask $scheduled_task
     * @return View
     */
    public function edit(ScheduledTaskRepository $repository, ScheduledTask $scheduled_task): View
    {
        return view('admin::scheduled_tasks.edit', [
            'model' => $scheduled_task,
            'method_list' => $repository->getFileMethodsForSelect(),
        ]);
    }

    /**
     * Updates the specified resource in storage.
     *
     * @param ScheduledTaskRequest $request
     * @param ScheduledTask $scheduled_task
     * @return RedirectResponse
     */
    public function update(ScheduledTaskRequest $request, ScheduledTask $scheduled_task): RedirectResponse
    {
        $data = $request->validated();

        if (!$scheduled_task->fill($data)->save()) {
            return back()->with('error', trans('Error updating.'));
        }

        return redirect()
            ->route('admin.scheduled-tasks.show', $scheduled_task)
            ->with('success', trans('The Record has been updated.'));
    }

    /**
     * Removes the specified resource from storage.
     *
     * @param ScheduledTask $scheduled_task
     * @return RedirectResponse
     * @throws \Exception
     */
    public function destroy(ScheduledTask $scheduled_task): RedirectResponse
    {
        $scheduled_task->delete();

        return redirect()
            ->route('admin.scheduled-tasks.index')
            ->with('success', trans('The Record has been deleted.'));
    }

}
