@extends('admin::layouts.pages')

@push('breadcrumbs')
    <li class="breadcrumb-item active" aria-current="page">{{ __('Analytics Services') }}</li>
@endpush

@section('title', __('Analytics Services'))

@section('content')

    <div class="d-flex mb-3">
        <a class="btn btn-primary mr-2" href="{{ route('admin.settings.analytics.edit') }}">{{ __('Edit') }}</a>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <tbody>
            @foreach($modeldetails_config as $detail)
                <tr>
                    <td>{{ $detail['label'] }}</td>
                    <td>{{ $detail['value'] }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

@endsection
