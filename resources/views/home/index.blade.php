@extends('admin::layouts.pages')

@section('title', __('Administrative Panel'))

@section('content')

    <div class="row mb-4">
        <div class="col-12 col-md-6">
            <!-- User -->
            @include('admin::home._user')
            <!-- /User -->
        </div>

        <div class="col-12 col-md-6">
            <!-- User note -->
            @include('admin::home._note')
            <!-- /User note -->
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-md-6">
            <!-- Server -->
            @include('admin::home._server')
            <!-- /Server -->
        </div>

        <div class="col-12 col-md-6">
            <!-- Service -->
            @include('admin::home._services')
            <!-- /Service -->
        </div>
    </div>

    @if (auth('admin')->user()->can(\SP\Admin\Security\Role::SUPER))
        <div class="row mt-3">
            <div class="col-12">
                <!-- Log -->
                @include('admin::home._log')
                <!-- /Log -->
            </div>
        </div>
    @endif

@endsection
