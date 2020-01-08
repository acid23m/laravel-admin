@extends('admin::layouts.pages')

@push('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.scheduled-tasks.index') }}">{{ __('Scheduled Tasks') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.scheduled-tasks.show', $model) }}">{{ $model->name }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Edit') }}</li>
@endpush

@section('title', __('Scheduled Tasks') . ': ' . __('Edit') . ' ' . $model->name)

@section('content')

    @include('admin::scheduled_tasks._form', [
        'method' => 'put',
        'route' => route('admin.scheduled-tasks.update', $model),
        'submit' => [
            'type' => 'primary',
            'label' => __('Save'),
        ]
    ])

@endsection
