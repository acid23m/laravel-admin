@extends('admin::layouts.pages')

@push('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">{{ __('Users') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.users.show', $model) }}">{{ $model->username }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Edit') }}</li>
@endpush

@section('title', __('Users') . ': ' . __('Edit') . ' ' . $model->username)

@section('content')

    @include('admin::users._form', [
        'method' => 'put',
        'route' => route('admin.users.update', $model),
        'submit' => [
            'type' => 'primary',
            'label' => __('Save'),
        ],
        'password_required' => false,
    ])

@endsection
