@extends('admin::layouts.pages')

@push('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.scheduled-tasks.index') }}">{{ __('Scheduled Tasks') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ $model->name }}</li>
@endpush

@section('title', $model->name)

@section('content')

    <div class="d-flex mb-3">
        <a class="btn btn-primary mr-2" href="{{ route('admin.scheduled-tasks.edit', $model) }}">{{ __('Edit') }}</a>

        @if(auth('admin')->user()->id !== $model->id)
            <a class="btn btn-danger mr-2" href="{{ route('admin.scheduled-tasks.destroy', $model) }}" data-method="delete"
               data-confirm="{{ __('Are you sure?') }}">
                {{ __('Delete') }}
            </a>
        @endif
    </div>

    @modelDetails($modeldetails_config)

@endsection
