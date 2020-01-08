@extends('admin::layouts.pages')

@push('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.settings.basic.show') }}">{{ __('Basic settings') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Edit') }}</li>
@endpush

@section('title', __('Basic settings') . ': ' . __('Edit'))

@section('content')

    @include('admin::settings.basic._form', [
        'method' => 'put',
        'route' => route('admin.settings.basic.update'),
        'submit' => [
            'type' => 'primary',
            'label' => __('Save'),
        ]
    ])

@endsection
