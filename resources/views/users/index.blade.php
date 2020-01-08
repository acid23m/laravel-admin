@extends('admin::layouts.pages')

@push('breadcrumbs')
    <li class="breadcrumb-item active" aria-current="page">{{ __('Users') }}</li>
@endpush

@section('title', __('Users'))

@section('content')
    <a class="btn btn-success mb-2" href="{{ route('admin.users.create') }}">{{ __('Create') }}</a>

    @modelGrid($modelgrid_config)
@endsection
