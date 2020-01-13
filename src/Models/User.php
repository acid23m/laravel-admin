<?php
declare(strict_types=1);

namespace SP\Admin\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Kyslik\ColumnSortable\Sortable;
use SP\Admin\Events\User\CreatingUser;
use SP\Admin\Events\User\SavingUser;
use SP\Admin\Notifications\ResetPassword as ResetPasswordNotification;
use SP\Admin\Security\Role;
use SP\Admin\Widgets\ModelGrid\Columns\CreatedAtColumn;

/**
 * User for Administrative panel.
 *
 * @property string $id
 * @property string $username
 * @property string $email
 * @property string|Carbon $email_verified_at
 * @property string $password
 * @property string $role
 * @property string $note
 * @property bool $active
 * @property string $ip
 * @property string|Carbon $accessed_at
 * @property string $remember_token
 * @property string|Carbon $created_at
 * @property string|Carbon $updated_at
 *
 * @package SP\Admin\Models
 */
final class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword, MustVerifyEmail;

    use Notifiable;
    use Sortable;

    /**
     * @var string|null
     */
    public ?string $password_form = null;

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * The connection name for the model.
     *
     * @var string|null
     */
    protected $connection = 'admin_users';
    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'email',
        'role',
        'note',
        'active',
    ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'email_verified_at',
        'accessed_at',
    ];
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'active' => 'boolean',
        'email_verified_at' => 'timestamp',
    ];
    /**
     * The attributes that should be sortable.
     *
     * @var array
     */
    public array $sortable = [
        'username',
        'email',
        'role',
        'active',
        'created_at',
    ];

    /**
     * The event map for the model.
     *
     * Allows for object-based events for native Eloquent events.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'saving' => SavingUser::class,
        'creating' => CreatingUser::class,
    ];

    /**
     * {@inheritDoc}
     */
    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * {@inheritDoc}
     */
    public static function attributeLabels(): array
    {
        return [
            'username' => trans('Nickname'),
            'password' => trans('Password'),
            'role' => trans('Role'),
            'note' => trans('Note'),
            'active' => trans('Is Active'),
            'created_at' => trans('Creation Date'),
            'updated_at' => trans('Modification Date'),
            'accessed_at' => trans('Visit Date'),
        ];
    }

    /**
     * Filters superusers.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeLimited(Builder $query): Builder
    {
        return $query->where('role', '<>', Role::SUPER);
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
        if (isset($params['username']) && filled($params['username'])) {
            $query->where('username', 'like', "%{$params['username']}%");
        }

        if (isset($params['email']) && filled($params['email'])) {
            $query->where('email', 'like', "%{$params['email']}%");
        }

        if (isset($params['role']) && filled($params['role'])) {
            $query->where('role', '=', $params['role']);
        }

        if (isset($params['active']) && filled($params['active'])) {
            $is_active = $params['active'] === 'true';
            $query->where('active', '=', $is_active);
        }

        /** @var string $timezone */
        $timezone = config('app.timezone', 'UTC');
        $time_start = '00:00:00';
        $time_end = '23:59:59';

        if (isset($params['created_at']) && filled($params['created_at'])) {
            if (\strpos($params['created_at'], CreatedAtColumn::DATETIME_RANGE_SEPARATOR) !== false) {
                [$date_start, $date_end] = \explode(CreatedAtColumn::DATETIME_RANGE_SEPARATOR, $params['created_at']);
                $date_start = Carbon::parse("$date_start $time_start", $timezone)
                    ->timezone('UTC')
                    ->format(STANDARD_FORMAT__DATETIME);
                $date_end = Carbon::parse("$date_end $time_end", $timezone)
                    ->timezone('UTC')
                    ->format(STANDARD_FORMAT__DATETIME);
                $query->whereBetween('created_at', [$date_start, $date_end]);
            } else {
                $query->whereDate('created_at', $params['created_at']);
            }
        }

        return $query;
    }

}
