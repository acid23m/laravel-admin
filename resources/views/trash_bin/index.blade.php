@extends('admin::layouts.pages')

@push('breadcrumbs')
    <li class="breadcrumb-item active" aria-current="page">{{ __('Trash bin') }}</li>
@endpush

@section('title', __('Trash bin'))

@section('content')
    <a class="btn btn-danger mb-2" href="{{ route('admin.trash-bin.clear') }}" data-method="delete"
       data-confirm="{{ __('Are you sure?') }}">
        {{ __('Clear') }}
    </a>

    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <td>#</td>
                <td>{{ __('Group') }}</td>
                <td>{{ __('Element') }}</td>
                <td>{{ __('Deletion Date') }}</td>
                <td style="width: 100px;"></td>
            </tr>
            </thead>

            <tbody>
            @foreach ($items as $item)
                <tr>
                    <td>{{ $loop->index + 1 }}</td>
                    <td>{{ $item['group_name'] }}</td>
                    <td>{{ $item['label'] }}</td>
                    <td>{{ $item['deleted_at'] }}</td>
                    <td>
                        @foreach($item['links'] as $link)
                            {!! $link !!}
                        @endforeach
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
