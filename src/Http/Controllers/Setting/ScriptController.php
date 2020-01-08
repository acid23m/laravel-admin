<?php
declare(strict_types=1);

namespace SP\Admin\Http\Controllers\Setting;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use SP\Admin\Http\Controllers\AdminController as Controller;
use SP\Admin\Models\Repositories\SettingScriptRepository;
use SP\Admin\Models\SettingScript;

/**
 * Class ScriptController.
 *
 * @package SP\Admin\Http\Controllers\Setting
 */
final class ScriptController extends Controller
{
    /**
     * ScriptController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->authorizeResource(SettingScript::class);
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
     * @param SettingScriptRepository $repository
     * @return View
     */
    public function show(SettingScriptRepository $repository): View
    {
        return view('admin::settings.scripts.show', [
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
        return view('admin::settings.scripts.edit', [
            'model' => $this->getModel(),
        ]);
    }

    /**
     * Updates the specified resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function update(Request $request): RedirectResponse
    {
        $scripts = $this->getModel();

        $data = $request->validate([
            $scripts::HEAD => 'nullable|string',
            $scripts::BOTTOM => 'nullable|string',
        ]);

        try {
            $scripts->set($scripts::HEAD, $data[$scripts::HEAD]);
            $scripts->set($scripts::BOTTOM, $data[$scripts::BOTTOM]);
        } catch (\Throwable $e) {
            report($e);

            return back()->with('error', trans('Error updating.'));
        }

        return redirect()
            ->route('admin.settings.scripts.show')
            ->with('success', trans('The Record has been updated.'));
    }

    /**
     * @return SettingScript
     */
    protected function getModel(): SettingScript
    {
        return new SettingScript;
    }

}
