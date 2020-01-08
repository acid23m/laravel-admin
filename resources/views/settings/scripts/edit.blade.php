@extends('admin::layouts.pages')

@push('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.settings.scripts.show') }}">{{ __('User Scripts') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Edit') }}</li>
@endpush

@section('title', __('User Scripts') . ': ' . __('Edit'))

@section('content')

    <p class="text-muted">
        {{ __('You can add an optional third-party code, such as a counter, metric, social buttons, the widget information ...') }}
    </p>

    <div class="alert alert-warning text-uppercase" role="alert">
        {{ __('Be careful with scripts provided by third parties. The code entered here may affect the performance of the application and create a security risk!') }}
    </div>

    @include('admin::settings.scripts._form', [
        'method' => 'put',
        'route' => route('admin.settings.scripts.update'),
        'submit' => [
            'type' => 'primary',
            'label' => __('Save'),
        ]
    ])

@endsection
