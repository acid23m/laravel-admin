@php $current_route_name = request()->route()->getName() @endphp

@if($current_route_name !== 'admin.home')
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a class="text-decoration-none" href="{{ route('admin.home') }}">
                    {{ __('Home') }}
                </a>
            </li>
            @stack('breadcrumbs')
        </ol>
    </nav>
@endif
