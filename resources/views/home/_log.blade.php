<?php /** @var \Illuminate\Pagination\AbstractPaginator $log_paginator */ ?>

<h5>
    <i class="fa fa-bug mr-1"></i>
    {{ __('Log') }}
</h5>

<hr>

@php
    $info = '';
    if ($log_paginator instanceof \Illuminate\Pagination\LengthAwarePaginator) {
        $info = $log_paginator->firstItem() . ' - ' . $log_paginator->lastItem() . ' / ' . $log_paginator->total();
    } elseif ($log_paginator instanceof \Illuminate\Pagination\Paginator) {
        $info = $log_paginator->firstItem() . ' - ' . $log_paginator->lastItem();
    }
@endphp

<div class="d-flex justify-content-between align-items-end mb-2">
    <a class="btn btn-danger mr-2" href="{{ route('admin.clear-log') }}" data-method="delete"
       data-confirm="{{ __('Are you sure?') }}">
        {{ __('Delete') }}
    </a>
    <small>{!! $info !!}</small>
</div>

<div class="table-responsive">
    <table class="table">
        <thead class="thead-light">
        <tr>
            <th scope="col">#</th>
            <th scope="col">{{ __('Datetime') }}</th>
            <th scope="col">{{ __('Level') }}</th>
            <th scope="col">{{ __('Message') }}</th>
            <th scope="col">{{ __('Context') }}</th>
            <th scope="col">{{ __('Extra') }}</th>
        </tr>
        </thead>
        <tbody>
        @forelse($log_paginator as $log_row)
            <tr>
                <td>{{ $log_row['index'] }}</td>
                <td>{{ $log_row['datetime'] }}</td>
                <td>{{ $log_row['level'] }}</td>
                <td>{{ $log_row['message'] }}</td>
                <td style="font-size: 0.9rem">
                    {!! \nl2br(
                        \substr_replace($log_row['context'], PHP_EOL, \strpos($log_row['context'], '[stacktrace]'), 0)
                    ) !!}
                </td>
                <td style="font-size: 0.9rem">
                    {!! \nl2br($log_row['extra']) !!}
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6">{{ __('No data') }}</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="d-flex justify-content-end">
    {!! $log_paginator->withPath(request()->url())->links() !!}
</div>
