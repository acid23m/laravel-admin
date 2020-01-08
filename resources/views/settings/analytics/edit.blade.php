@extends('admin::layouts.pages')

@push('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.settings.analytics.show') }}">{{ __('Analytics Services') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Edit') }}</li>
@endpush

@section('title', __('Analytics Services') . ': ' . __('Edit'))

@section('content')

    @include('admin::settings.analytics._form', [
        'method' => 'put',
        'route' => route('admin.settings.analytics.update'),
        'submit' => [
            'type' => 'primary',
            'label' => __('Save'),
        ]
    ])

@endsection
