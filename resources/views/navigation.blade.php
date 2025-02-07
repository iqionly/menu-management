<ul class="nav nav-pills flex-column mb-auto" style="--bs-scroll-height: 100px;">
    @foreach ($data_menu as $menu)
    <li class="nav-item">
        <a href="{{ menu_management_route($menu['route']) }}" class="nav-link {{ $routeActive == $menu['route'] && !empty($menu['route']) ? 'active' : '' }}" aria-current="page">
            <svg class="bi pe-none me-2" width="16" height="16">
                <use xlink:href="#home"></use>
            </svg>
            {{ $menu['name'] }}
        </a>
    </li>
    @endforeach
</ul>