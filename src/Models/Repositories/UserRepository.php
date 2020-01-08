<?php
declare(strict_types=1);

namespace SP\Admin\Models\Repositories;

use Illuminate\Contracts\Auth\Factory as Auth;
use Illuminate\Http\Request;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Support\Str;
use SP\Admin\Helpers\Formatter;
use SP\Admin\Models\User;
use SP\Admin\Security\Role;
use SP\Admin\Widgets\ModelDetails\Rows\ActiveRow;
use SP\Admin\Widgets\ModelDetails\Rows\CreatedAtRow;
use SP\Admin\Widgets\ModelDetails\Rows\UpdatedAtRow;
use SP\Admin\Widgets\ModelGrid\Columns\{ActionColumn, ActiveColumn, CreatedAtColumn, IndexColumn};

/**
 * Class UserRepository.
 *
 * @package SP\Admin\Models\Repositories
 */
final class UserRepository
{
    private const DEFAULT_PER_PAGE = 20;

    /**
     * @var Request
     */
    private Request $request;
    /**
     * @var Auth
     */
    private Auth $auth;

    /**
     * UserRepository constructor.
     *
     * @param Request $request
     * @param Auth $auth
     */
    public function __construct(Request $request, Auth $auth)
    {
        $this->request = $request;
        $this->auth = $auth;
    }

    /**
     * Users collection - filtered, sorted and paginated.
     *
     * @return AbstractPaginator
     */
    public function getUsersForModelGrid(): AbstractPaginator
    {
        $params = $this->request->query();

        $per_page = $params['per_page'] ?? self::DEFAULT_PER_PAGE;

        return $this->auth->guard('admin')->user()->can(Role::SUPER)
            ? User::filter($params)->sortable()->paginate($per_page)
            : User::limited()->filter($params)->sortable()->paginate($per_page);
    }

    /**
     * Config for modelGrid widget.
     *
     * @return array
     */
    public function modelGridConfig(): array
    {
        $auth = $this->auth;

        return [
            'model_class' => User::class,
            'collection' => $this->getUsersForModelGrid(),
            'columns' => [
                IndexColumn::class,
                [
                    'attribute' => 'username',
                    'value' => static function (User $model) use ($auth): string {
                        $username = $model->username;

                        if ($auth->guard('admin')->user()->id === $model->id) {
                            return "<strong>$username</strong>";
                        }

                        return $username;
                    },
                ],
                [
                    'attribute' => 'email',
                    'value' => fn (User $model): string => html()->mailto($model->email, $model->email)->toHtml(),
                ],
                [
                    'attribute' => 'role',
                    'filter' => value(static function (): array {
                        $roles = [];
                        foreach (Role::getList(false) as $role) {
                            $roles[$role] = Str::title($role);
                        }

                        return $roles;
                    }),
                    'value' => fn (User $model): string => html()->span(Str::title($model->role))
                        ->class('badge badge-light')
                        ->toHtml(),
                ],
                ActiveColumn::class,
                CreatedAtColumn::class,
                new ActionColumn([
                    'view' => fn (User $model): string => route('admin.users.show', $model),
                    'edit' => fn (User $model): string => route('admin.users.edit', $model),
                    'delete' => static function (User $model) use ($auth): ?string {
                        if ($auth->guard('admin')->user()->id === $model->id) {
                            return null;
                        }

                        return route('admin.users.destroy', $model);
                    },
                ]),
            ],
        ];
    }

    /**
     * Config for modelDetails widget.
     *
     * @param User $model
     * @return array
     */
    public function modelDetailsConfig(User $model): array
    {
        return [
            'model' => $model,
            'attributes' => [
                ['attribute' => 'id'],
                [
                    'attribute' => 'username',
                    'value' => "<strong>{$model->username}</strong>",
                ],
                [
                    'attribute' => 'email',
                    'value' => static function (User $model): string {
                        $value = '<a href="mailto:' . $model->email . '">';
                        $value .= $model->email;
                        $value .= '</span>';

                        return $value;
                    },
                ],
                [
                    'attribute' => 'role',
                    'value' => static function (User $model): string {
                        $value = Str::title($model->role);
                        $value .= ' [';
                        $value .= Role::getList(true, true)[$model->role];
                        $value .= ']';

                        return $value;
                    },
                ],
                [
                    'attribute' => 'note',
                    'value' => \nl2br((string)$model->note),
                ],
                ActiveRow::class,
                CreatedAtRow::class,
                UpdatedAtRow::class,
                [
                    'attribute' => 'accessed_at',
                    'value' => Formatter::isoToLocalDateTime($model->accessed_at),
                ],
                ['attribute' => 'ip'],
            ],
        ];
    }

}
