<h5>
    <i class="fa fa-plug mr-1"></i>
    {{ __('Maintenance') }}
</h5>

<hr>

<ul class="nav nav-tabs" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" href="#st1" data-toggle="tab">{{ __('Cache') }}</a>
    </li>

    <li class="nav-item">
        <a class="nav-link" href="#st2" data-toggle="tab">{{ __('Sitemap') }}</a>
    </li>

    @if (auth('admin')->user()->can(\SP\Admin\Security\Role::ADMIN))
        <li class="nav-item">
            <a class="nav-link" href="#st3" data-toggle="tab">{{ __('Database') }}</a>
        </li>
    @endif
</ul>

<div class="tab-content">
    <div class="tab-pane fade show active" id="st1">
        <p class="mt-3">
            {{ __('Not all of the cached content on the server is automatically updated. To update, you can manually reset the cache.') }}
        </p>
        <a href="{{ route('admin.clear-cache') }}" class="btn btn-block btn-success" data-method="post">
            {{ __('Clear Cache') }}
        </a>
    </div>

    <div class="tab-pane fade" id="st2">
        <p class="mt-3">
            {{ __('sitemap.xml file helps indexing site pages by web crawlers.') }}
        </p>
        <a href="{{ route('admin.create-sitemap') }}" class="btn btn-block btn-success" data-method="post">
            {{ __('Generate Sitemap') }}
        </a>
    </div>

    @if (auth('admin')->user()->can(\SP\Admin\Security\Role::ADMIN))
        <div class="tab-pane fade" id="st3">
            <p class="mt-3">
                {{ __('Direct interaction with the database is not recommended. Use the editor only if you know what you are doing!') }}
            </p>
            @php
                $db_creds = [];
                /** @var \PDO $pdo */
                $pdo = app('db')->connection()->getPdo();
                $db_conn = config('database.default');

                $db_creds[] = 'Driver: <span class="text-muted">' . $pdo->getAttribute(\PDO::ATTR_DRIVER_NAME) . '</span>';
                $db_creds[] = 'Server: <span class="text-muted">db:5432</span>';
                $db_creds[] = 'User name: <span class="text-muted">' . config("database.connections.$db_conn.username") . '</span>';
                $db_creds[] = 'Password: <span class="text-muted">' . config("database.connections.$db_conn.password") . '</span>';
                $db_creds[] = 'DB: <span class="text-muted">' . config("database.connections.$db_conn.database") . '</span>';
            @endphp
            <ul>
                @foreach($db_creds as $db_cred)
                    <li>{!! $db_cred !!}</li>
                @endforeach
            </ul>
            <a href="{{ url('adminer/index.php') }}" class="btn btn-block btn-success" target="_blank">
                {{ __('Database Editor') }}
            </a>
        </div>
    @endif
</div>
