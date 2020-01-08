@extends('admin::layouts.main')

@section('body')
    <nav class="navbar navbar-expand navbar-dark bg-dark shadow">
        <a class="sidebar-toggle mr-3" href="#"><i class="fa fa-bars"></i></a>
        <a class="navbar-brand mr-5" href="{{ url('/') }}">
            {{ config('app.name') }}
        </a>

        @include('admin::nav.top')
    </nav>

    <div class="d-flex">
        @include('admin::nav.side')

        <main class="content p-4">
            {{--flash--}}
            @if(session()->has('success'))
                @push('toasts')
                    @toast(['type' => 'success', 'icon' => 'check', 'title' => __('Done')])
                    {{ session()->get('success') }}
                    @endtoast
                @endpush
            @endif
            @if(session()->has('error'))
                @push('toasts')
                    @toast(['type' => 'error', 'icon' => 'bug', 'title' => __('Error')])
                    {{ session()->get('error') }}
                    @endtoast
                @endpush
            @endif

            {{--toasts--}}
            <section class="d-flex justify-content-end" style="position: relative;">
                <div class="position-absolute" aria-live="polite" aria-atomic="true" style="z-index: 1000;">
                    @stack('toasts')
                </div>
            </section>

            <h2 class="mb-4">@yield('title')</h2>

            @include('admin::nav.breadcrumbs')

            <div class="card mb-4">
                <div class="card-body">
                    @yield('content')
                </div>
            </div>
        </main>
    </div>
@endsection
