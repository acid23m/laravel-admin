@if(!isset($type))
    @php $type = 'default'; @endphp
@endif

@switch($type)
    @case('success')
        @php
        $bg_color = 'bg-success';
        $text_color = 'text-light';
        @endphp
        @break
    @case('error')
        @php
        $bg_color = 'bg-danger';
        $text_color = 'text-light';
        @endphp
        @break
    @case('info')
        @php
        $bg_color = 'bg-info';
        $text_color = 'text-light';
        @endphp
        @break
    @case('default')
        @php
        $bg_color = '';
        $text_color = '';
        @endphp
        @break
    @default
        @php
        $bg_color = '';
        $text_color = '';
        @endphp
@endswitch

<div class="toast {{ $bg_color }} mx-2 my-1" role="alert" aria-live="assertive" aria-atomic="true" data-delay="6000"
     style="min-width: 230px;">
    <div class="toast-header">
        @isset($icon)
            <i class="fa fa-{{ $icon }} mr-1"></i>
        @endisset

        <strong class="mr-auto">
            @isset($title)
                {{ $title }}
            @endisset
        </strong>

        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="{{ __('Close') }}">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="toast-body {{ $text_color }}">
        {{ $slot }}
    </div>
</div>
