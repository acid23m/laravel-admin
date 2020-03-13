<div class="toast {{ $bg_color }} mx-2 my-1" role="alert" aria-live="assertive" aria-atomic="true" data-delay="6000"
     style="min-width: 230px;">
    <div class="toast-header">
        @if($icon)
            <i class="fa fa-{{ $icon }} mr-1"></i>
        @endif

        <strong class="mr-auto">
            @if($title)
                {{ $title }}
            @endif
        </strong>

        <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="{{ __('Close') }}">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>

    <div class="toast-body {{ $text_color }}">
        {{ $slot }}
    </div>
</div>
