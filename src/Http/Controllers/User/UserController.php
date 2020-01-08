<?php
declare(strict_types=1);

namespace SP\Admin\Http\Controllers\User;

use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use SP\Admin\Http\Controllers\AdminController as Controller;
use SP\Admin\Http\Requests\User\{StoreUser, UpdateUser};
use SP\Admin\Models\Repositories\UserRepository;
use SP\Admin\Models\User;

/**
 * Class UserController.
 *
 * @package SP\Admin\Http\Controllers\User
 */
final class UserController extends Controller
{
    /**
     * UserController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Displays a listing of the resource.
     *
     * @param UserRepository $repository
     * @return View
     */
    public function index(UserRepository $repository): View
    {
        return view('admin::users.index', [
            'modelgrid_config' => $repository->modelGridConfig(),
        ]);
    }

    /**
     * Shows the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return view('admin::users.create', [
            'model' => new User,
        ]);
    }

    /**
     * Stores a newly created resource in storage.
     *
     * @param StoreUser $request
     * @param Hasher $hasher
     * @return RedirectResponse
     */
    public function store(StoreUser $request, Hasher $hasher): RedirectResponse
    {
        $data = $request->validated();

        $user = new User;
        $user->password_form = $data['password_form'];

        if (!$user->fill($data)->save()) {
            return back()->with('error', trans('Error creating.'));
        }

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', trans('The Record has been created.'));
    }

    /**
     * Displays the specified resource.
     *
     * @param UserRepository $repository
     * @param User $user
     * @return View
     */
    public function show(UserRepository $repository, User $user): View
    {
        return view('admin::users.show', [
            'model' => $user,
            'modeldetails_config' => $repository->modelDetailsConfig($user),
        ]);
    }

    /**
     * Shows the form for editing the specified resource.
     *
     * @param User $user
     * @return View
     */
    public function edit(User $user): View
    {
        return view('admin::users.edit', [
            'model' => $user,
        ]);
    }

    /**
     * Updates the specified resource in storage.
     *
     * @param UpdateUser $request
     * @param User $user
     * @return RedirectResponse
     */
    public function update(UpdateUser $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        $user->password_form = $data['password_form'];

        if (!$user->fill($data)->save()) {
            return back()->with('error', trans('Error updating.'));
        }

        return redirect()
            ->route('admin.users.show', $user)
            ->with('success', trans('The Record has been updated.'));
    }

    /**
     * Removes the specified resource from storage.
     *
     * @param User $user
     * @return RedirectResponse
     * @throws \Exception
     */
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', trans('The Record has been deleted.'));
    }

}
