@php $user = auth('admin')->user() @endphp

<h5>
    <i class="fa fa-user mr-1"></i>
    {{ __('User') }}
</h5>

<hr>

<ul class="list-unstyled">
    <li>
        <strong>{{ __('Nickname') }}</strong>:
        <span class="badge badge-primary">{{ $user->username }}</span>
    </li>

    <li>
        <strong>{{ __('Role') }}</strong>:
        {{ \SP\Admin\Security\Role::getList(true, true)[$user->role] }}
    </li>

    <li>
        <strong>Email</strong>:
        {{ html()->mailto($user->emailo, $user->email) }}
    </li>

    <li>
        <strong>{{ __('Visit Date') }}</strong>:
        {{ \SP\Admin\Helpers\Formatter::isoToLocalDateTime($user->accessed_at) }}
    </li>

    <li>
        <strong>IP</strong>:
        {{ $user->ip }}
    </li>
</ul>
