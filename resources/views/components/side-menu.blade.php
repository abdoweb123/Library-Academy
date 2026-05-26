@can($can)
    <li class="slide {{ arrRouteActive($routes) }}">
        <a class="side-menu__item {{ arrRouteActive($routes, 'active') }}" data-toggle="slide" href="#">
            <i class="{{ $icon }} m-2"></i>
            <span class="side-menu__label">{{ trns($label) }}</span>
            <i class="angle fa fa-angle-right"></i>
        </a>
        <ul class="slide-menu">

            @php
                // هل في أي child route مطابق للرابط الحالي؟
                $isChildActive = collect($children)->contains(function($child) {
                    return !empty($child['param']) && url()->current() === route($child['route'], $child['param']);
                });

                // هل الرابط الحالي هو فعلاً رابط view_all؟
                $isMainRouteActive = url()->current() === route($mainRoute);
            @endphp

            @if ($showViewAll ?? true)
                <li>
                    <a href="{{ route($mainRoute) }}" class="slide-item"
                       style="color: {{ $isMainRouteActive && !$isChildActive ? '#0478ed' : '' }}">
                        <i class="fa fa-eye m-2"></i> {{ trns('view_all') }}
                    </a>
                </li>
            @endif

            @foreach ($children as $child)
                <li>
                    <a href="{{ route($child['route'], $child['param'] ?? null) }}" class="slide-item"
                            style="color: {{ url()->current() === route($child['route'], $child['param'] ?? []) ? '#0478ed' : '' }}">
                        <i class="fa fa-eye m-2"></i> {{ trns($child['label']) }}
                    </a>
                </li>
            @endforeach
        </ul>
    </li>
@endcan
