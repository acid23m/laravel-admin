<?php
declare(strict_types=1);

namespace SP\Admin\Http\Controllers\Setting;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use SP\Admin\Contracts\Setting\AbstractBasic;
use SP\Admin\Contracts\Setting\AbstractBasicRepository;
use SP\Admin\Contracts\Setting\AbstractBasicRequest;
use SP\Admin\Events\Setting\BasicSaved;
use SP\Admin\Http\Controllers\AdminController as Controller;
use SP\Admin\Models\SettingBasic;

/**
 * CRUD for Basic Settings.
 *
 * @package SP\Admin\Http\Controllers\Setting
 */
final class BasicController extends Controller
{
    /**
     * BasicSettingController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->authorizeResource(SettingBasic::class);
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
     * @param AbstractBasicRepository $repository
     * @return View
     */
    public function show(AbstractBasicRepository $repository): View
    {
        return view('admin::settings.basic.show', [
            'modeldetails_config' => $repository->modelDetailsConfig(),
        ]);
    }

    /**
     * Shows the form for editing the specified resource.
     *
     * @param AbstractBasicRepository $repository
     * @return View
     */
    public function edit(AbstractBasicRepository $repository): View
    {
        $model = $this->getModel();

        return view('admin::settings.basic.edit', [
            'model' => $model,
            'app_logo_url' => $repository->appLogoUrlOriginal(),
            'tz_list' => $repository->timezoneListForSelector(),
            'mail_encrypt_list' => $repository->mailEncryptionListForSelector(),
        ]);
    }

    /**
     * Updates the specified resource in storage.
     *
     * @param AbstractBasicRequest $request
     * @return RedirectResponse
     */
    public function update(AbstractBasicRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $setting = $this->getModel();
        $setting_old = $setting->getAll();

        try {
            $setting->setAll($data);
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', trans('Error updating.'));
        }

        event(new BasicSaved($setting_old, $setting));

        return redirect()
            ->route('admin.settings.basic.show')
            ->with('success', trans('The Record has been updated.'));
    }

    /**
     * @return AbstractBasic
     */
    protected function getModel(): AbstractBasic
    {
        return resolve(AbstractBasic::class);
    }

}
