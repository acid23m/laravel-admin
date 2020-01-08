@extends('admin::layouts.pages')

@push('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.scheduled-tasks.index') }}">{{ __('Scheduled Tasks') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Create') }}</li>
@endpush

@section('title', __('Scheduled Tasks') . ': ' . __('Create'))

@section('content')

    @include('admin::scheduled_tasks._form', [
        'method' => 'post',
        'route' => route('admin.scheduled-tasks.store'),
        'submit' => [
            'type' => 'success',
            'label' => __('Create'),
        ]
    ])

@endsection
