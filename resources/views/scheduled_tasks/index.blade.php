@extends('admin::layouts.pages')

@push('breadcrumbs')
    <li class="breadcrumb-item active" aria-current="page">{{ __('Scheduled Tasks') }}</li>
@endpush

@section('title', __('Scheduled Tasks'))

@section('content')
    <a class="btn btn-success mb-2" href="{{ route('admin.scheduled-tasks.create') }}">{{ __('Create') }}</a>

    @modelGrid($modelgrid_config)
@endsection
