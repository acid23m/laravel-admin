<h5>
    <i class="fa fa-desktop mr-1"></i>
    {{ __('Technical Details') }}
</h5>

<hr>

@php \ob_start() @endphp

<ul class="list-unstyled">
    <li>
        <strong>IP</strong>:
        {{ cache()->remember('external_server_ip', 3600, static function () {
            $ch = \curl_init('https://api.ipify.org');
            \curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            \curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
            \curl_setopt($ch, CURLOPT_TIMEOUT, 5);
            $external_ip = \curl_exec($ch);
            \curl_close($ch);
            unset($ch);
            if ($external_ip) {
                return $external_ip;
            }

            return null;
        }) }}
    </li>

    <li>
        <strong>{{ __('Host') }}</strong>:
        {{ request()->getHost() }}
    </li>

    <li>
        <strong>{{ __('OS') }}</strong>:
        {{ PHP_OS }}
    </li>

    <li>
        <strong>{{ __('Server') }}</strong>:
        {{ $_SERVER['SERVER_SOFTWARE'] . ' ' . PHP_SAPI }}
    </li>

    <li>
        <strong>PHP</strong>:
        <?= PHP_VERSION ?>
        @if (auth('admin')->user()->can(\SP\Admin\Security\Role::ADMIN))
            <a href="{{ route('admin.phpinfo') }}">
                <span class="badge badge-secondary">php info</span>
            </a>
        @endif
    </li>

    <li>
        @php
            /** @var \PDO $pdo */
            $pdo = app('db')->connection()->getPdo();
            $main_db_driver_name = $pdo->getAttribute(\PDO::ATTR_DRIVER_NAME);
        @endphp
        <strong>{{ \strtoupper($main_db_driver_name) }}</strong>:
        {{ $pdo->getAttribute(\PDO::ATTR_SERVER_VERSION) }}
    </li>

    @php
        /** @var \PDO $pdo */
        $pdo = app('db')->connection('admin_users')->getPdo();
        $admin_users_driver_name = $pdo->getAttribute(\PDO::ATTR_DRIVER_NAME)
    @endphp
    @if ($main_db_driver_name !== $admin_users_driver_name)
        <li>
            <strong>{{ \strtoupper($admin_users_driver_name) }}</strong>:
            {{ $pdo->getAttribute(\PDO::ATTR_SERVER_VERSION) }}
        </li>
    @endif

    @if (config('cache.default') === 'redis')
        @php
            $redis = app('redis')->connection('cache');
            $info = $redis->info('server');
            $redis_version = $info['Server']['redis_version'];
        @endphp
        <li>
            <strong>REDIS</strong>:
            {{ $redis_version }}
        </li>
    @endif

    <li>
        <strong>LARAVEL</strong>:
        {{ $app::VERSION }}
    </li>
    <li>
        <strong>Admin Panel</strong>:
        {{ ADMIN_PACKAGE_VERSION }}
    </li>
</ul>

<span>--</span>

@php
    $cpu_model = '';
    $cpu_count = 0;

    $cpu = \file('/proc/cpuinfo');

    if ($cpu !== false) {
        foreach ($cpu as &$info) {
            if (\Illuminate\Support\Str::startsWith($info, 'model name') && empty($cpu_model)) {
                $info = \trim($info);
                $info = \str_replace(["\n", "\t"], '', $info);
                $info = \ltrim($info, 'model name');
                $cpu_model = $info;
            }

            if (\Illuminate\Support\Str::startsWith($info, 'processor')) {
                $cpu_count ++;
            }
        }
        unset($info, $cpu);
    }
@endphp

<ul class="list-unstyled">
    <li>
        <strong>{{ __('Processor') }}</strong>
        {{ $cpu_model }}
    </li>
    <li>
        <strong>{{ __('CPU Cores') }}</strong>:
        {{ $cpu_count }}
    </li>
</ul>

<div class="row">
    <div class="col-12 col-md-6">
        @php
            $mem_total = 0;
            $mem_free = 0;
            $mem_used = 0;

            $memory = \file('/proc/meminfo');
            if ($memory !== false) {
                foreach ($memory as &$info) {
                    if (\Illuminate\Support\Str::startsWith($info, 'MemTotal')) {
                        [$key, $val] = \explode(':', $info);
                        $mem_total = (int) \trim($val) * 1024;
                    }

                    if (\Illuminate\Support\Str::startsWith($info, 'MemAvailable')) {
                        [$key, $val] = \explode(':', $info);
                        $mem_free = (int) \trim($val) * 1024;
                    }
                }
                unset($info, $memory);

                $mem_used = $mem_total - $mem_free;
            }

            $mem_total_percent = 100;
            $mem_free_percent = \ceil($mem_total_percent * $mem_free / $mem_total);
            $mem_used_percent = $mem_total_percent - $mem_free_percent;
        @endphp

        <strong>{{ __('Memory') }}</strong>:

        <ul class="list-unstyled mt-2">
            <li class="mb-2">
                {{ __('Available') }}:
                {{ \SP\Admin\Helpers\Formatter::byteSize($mem_free) }}
                <div class="progress progress-micro">
                    <div class="progress-bar bg-success" style="width:{{ $mem_free_percent }}%;"></div>
                </div>
            </li>
            <li class="mb-2">
                {{ __('Used') }}:
                {{ \SP\Admin\Helpers\Formatter::byteSize($mem_used) }}
                <div class="progress progress-micro">
                    <div class="progress-bar bg-danger" style="width:{{ $mem_used_percent }}%;"></div>
                </div>
            </li>
            <li>
                {{ __('Total') }}:
                {{ \SP\Admin\Helpers\Formatter::byteSize($mem_total) }}
            </li>
        </ul>
    </div>

    <div class="col-12 col-md-6">
        @php
            $disk_total = 0;
            $disk_free = 0;
            $disk_used = 0;

            $ds = \disk_total_space('/');
            if ($ds !== false) {
                $disk_total = $ds;
            }

            $df = \disk_free_space('/');
            if ($df !== false) {
                $disk_free = $df;
            }

            $disk_used = $disk_total - $disk_free;

            $disk_total_percent = 100;
            $disk_free_percent = \ceil($disk_total_percent * $disk_free / $disk_total);
            $disk_used_percent = $disk_total_percent - $disk_free_percent;
        @endphp

        <strong>{{ __('Disk') }}</strong>:

        <ul class="list-unstyled mt-2">
            <li class="mb-2">
                {{ __('Available') }}:
                {{ \SP\Admin\Helpers\Formatter::byteSize($disk_free) }}
                <div class="progress progress-micro">
                    <div class="progress-bar bg-success" style="width:{{ $disk_free_percent }}%;"></div>
                </div>
            </li>
            <li class="mb-2">
                {{ __('Used') }}:
                {{ \SP\Admin\Helpers\Formatter::byteSize($disk_used) }}
                <div class="progress progress-micro">
                    <div class="progress-bar bg-danger" style="width:{{ $disk_used_percent }}%;"></div>
                </div>
            </li>
            <li>
                {{ __('Total') }}:
                {{ \SP\Admin\Helpers\Formatter::byteSize($disk_total) }}
            </li>
        </ul>
    </div>
</div>

@php $server_data = \ob_get_clean() @endphp
{!! cache()->remember('server_info', 60, fn () => $server_data) !!}
