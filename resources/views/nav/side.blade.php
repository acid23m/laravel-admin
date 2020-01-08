@php $current_route_name = request()->route()->getName() @endphp

<div class="sidebar sidebar-dark bg-dark pt-3">
    <ul class="list-unstyled">
        <li class="{{ $current_route_name === 'admin.home' ? 'active' : '' }}">
            <a href="{{ route('admin.home') }}">
                <i class="fa fa-fw fa-home mr-1"></i>
                {{ __('Home') }}
            </a>
        </li>
    </ul>

    {{--<ul class="list-unstyled mt-3">
        <li>
            <a href="#">
                <i class="fab fa-fw fa-affiliatetheme mr-1"></i>
                Menu Item
            </a>
        </li>
        <li>
            <a data-toggle="collapse" href="#sm_expand_1">
                <i class="fas fa-fw fa-archive mr-1"></i>
                Expandable Menu Item
            </a>
            <ul class="list-unstyled collapse" id="sm_expand_1">
                <li><a href="#">Submenu Item</a></li>
                <li><a href="#">Submenu Item</a></li>
            </ul>
        </li>
        <li><a href="#"><i class="fas fa-fw fa-asterisk mr-1"></i> Menu Item</a></li>
        <li><a href="#"><i class="fas fa-fw fa-baseball-ball mr-1"></i> Menu Item</a></li>
    </ul>--}}
</div>
