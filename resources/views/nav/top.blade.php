<div class="navbar-collapse collapse">
    <ul class="navbar-nav ml-auto">
        {{--<li class="nav-item mr-3">
            <a class="nav-link" href="#">
                <i class="fa fa-bell mr-1"></i> 3
            </a>
        </li>--}}

        <li class="nav-item dropdown mr-3">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" id="dd_options">
                <i class="fa fa-cog mr-1"></i>
            </a>
            <div aria-labelledby="dd_options" class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item d-flex justify-content-between align-items-center"
                   href="{{ route('admin.settings.basic.show') }}">
                    {{ __('Basic settings') }}
                    <i class="fa fa-wrench ml-4"></i>
                </a>

                <div class="dropdown-divider"></div>

                <a class="dropdown-item d-flex justify-content-between align-items-center"
                   href="{{ route('admin.settings.analytics.show') }}">
                    {{ __('Analytics Services') }}
                    <i class="fa fa-chart-bar ml-4"></i>
                </a>

                <a class="dropdown-item d-flex justify-content-between align-items-center"
                   href="{{ route('admin.settings.scripts.show') }}">
                    {{ __('User Scripts') }}
                    <i class="fa fa-code ml-4"></i>
                </a>

                <div class="dropdown-divider"></div>

                <a class="dropdown-item d-flex justify-content-between align-items-center"
                   href="{{ route('admin.scheduled-tasks.index') }}">
                    {{ __('Scheduled Tasks') }}
                    <i class="fa fa-tasks ml-4"></i>
                </a>

                <a class="dropdown-item d-flex justify-content-between align-items-center"
                   href="{{ route('admin.trash-bin.index') }}">
                    {{ __('Trash bin') }}
                    <span class="ml-4">
                        @php $trash_bin_count = trash_bin_count() @endphp
                        @if($trash_bin_count > 0)
                            <span class="badge badge-warning mr-1">{{ $trash_bin_count }}</span>
                        @endif
                        <i class="fa fa-trash-alt"></i>
                    </span>
                </a>
            </div>
        </li>

        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" id="dd_user">
                <i class="fa fa-user-circle mr-1"></i>
                {{ auth('admin')->user()->username }}
            </a>
            <div aria-labelledby="dd_user" class="dropdown-menu dropdown-menu-right">
                <a class="dropdown-item d-flex justify-content-between align-items-center"
                   href="{{ route('admin.users.show', auth('admin')->user()) }}">
                    {{ __('Profile') }}
                    <i class="fa fa-user ml-4"></i>
                </a>

                <a class="dropdown-item d-flex justify-content-between align-items-center"
                   href="{{ route('admin.users.index') }}">
                    {{ __('Users') }}
                    <i class="fa fa-users ml-4"></i>
                </a>

                <div class="dropdown-divider"></div>

                <a class="dropdown-item d-flex justify-content-between align-items-center"
                   href="{{ route('admin.logout') }}" data-method="post" data-confirm="{{ __('Are you sure?') }}">
                    {{ __('Logout') }}
                    <i class="fa fa-sign-out-alt ml-4"></i>
                </a>
            </div>
        </li>
    </ul>
</div>
