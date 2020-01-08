@extends('admin::layouts.pages')

@push('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">{{ __('Users') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Create') }}</li>
@endpush

@section('title', __('Users') . ': ' . __('Create'))

@section('content')

    @include('admin::users._form', [
        'method' => 'post',
        'route' => route('admin.users.store'),
        'submit' => [
            'type' => 'success',
            'label' => __('Create'),
        ],
        'password_required' => true,
    ])

@endsection
