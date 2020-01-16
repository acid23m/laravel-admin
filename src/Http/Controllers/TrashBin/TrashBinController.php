<?php
declare(strict_types=1);

namespace SP\Admin\Http\Controllers\TrashBin;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\{Collection, LazyCollection};
use Illuminate\View\View;
use SP\Admin\Http\Controllers\AdminController as Controller;
use SP\Admin\Models\Repositories\TrashBinRepository;
use SP\Admin\Security\Role;

/**
 * Trash bin functionality.
 *
 * @package SP\Admin\Http\Controllers\TrashBin
 */
final class TrashBinController extends Controller
{
    /**
     * Displays a listing of the trashed resources.
     *
     * @param TrashBinRepository $repository
     * @return View
     */
    public function index(TrashBinRepository $repository): View
    {
        return view('admin::trash_bin.index', [
            'items' => $repository->tableDataForModels(),
        ]);
    }

    /**
     * Permanently deletes all trashed resources.
     *
     * @param TrashBinRepository $repository
     * @return RedirectResponse
     * @throws \Throwable
     */
    public function clear(TrashBinRepository $repository): RedirectResponse
    {
        throw_if(
            auth('admin')->user()->cant(Role::ADMIN),
            AuthorizationException::class
        );

        /** @var Collection|LazyCollection $trashed_items */
        foreach ($repository->getTrashedItems() as $trashed_items) {
            /** @var Model $model */
            foreach ($trashed_items as $model) {
                $model->forceDelete();
            }
        }

        return redirect()
            ->route('admin.trash-bin.index')
            ->with('success', trans('The Records has been deleted.'));
    }

}
