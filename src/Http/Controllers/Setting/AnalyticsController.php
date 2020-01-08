<?php
declare(strict_types=1);

namespace SP\Admin\Http\Controllers\Setting;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use SP\Admin\Http\Controllers\AdminController as Controller;
use SP\Admin\Http\Requests\Setting\UpdateAnalytics;
use SP\Admin\Models\Repositories\SettingAnalyticsRepository;
use SP\Admin\Models\SettingAnalytics;

/**
 * Class AnalyticsController.
 *
 * @package SP\Admin\Http\Controllers\Setting
 */
final class AnalyticsController extends Controller
{
    /**
     * AnalyticsController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->authorizeResource(SettingAnalytics::class);
    }

    /**
     * Gets the list of resource methods which do not have model parameters.
     *
     * @return array
     */
    protected function resourceMethodsWithoutModels(): array
    {
        return ['show', 'edit', 'update'];
    }

    /**
     * Displays the specified resource.
     *
     * @param SettingAnalyticsRepository $repository
     * @return View
     */
    public function show(SettingAnalyticsRepository $repository): View
    {
        return view('admin::settings.analytics.show', [
            'modeldetails_config' => $repository->modelDetailsConfig($this->getModel()),
        ]);
    }

    /**
     * Shows the form for editing the specified resource.
     *
     * @return View
     */
    public function edit(): View
    {
        return view('admin::settings.analytics.edit', [
            'model' => $this->getModel(),
        ]);
    }

    /**
     * Updates the specified resource in storage.
     *
     * @param UpdateAnalytics $request
     * @return RedirectResponse
     */
    public function update(UpdateAnalytics $request): RedirectResponse
    {
        $data = $request->validated();

        $setting = $this->getModel();

        try {
            $setting->setAll($data);
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', trans('Error updating.'));
        }

        return redirect()
            ->route('admin.settings.analytics.show')
            ->with('success', trans('The Record has been updated.'));
    }

    /**
     * @return SettingAnalytics
     */
    protected function getModel(): SettingAnalytics
    {
        return resolve(SettingAnalytics::class);
    }

}
