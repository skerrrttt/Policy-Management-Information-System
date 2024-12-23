<aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
  <div class="app-brand demo">
    <a href="{{ url('/') }}" class="app-brand-link">
      <span class="app-brand-logo demo">
        @include('_partials.macros', ["width" => 25, "withbg" => 'var(--bs-primary)'])
      </span>
      <span class="app-brand-text demo menu-text fw-bold ms-2">PolMIS</span>
    </a>
    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
      <i class="bx bx-chevron-left bx-sm align-middle"></i>
    </a>
  </div>
  <div class="menu-inner-shadow"></div>
  <ul class="menu-inner py-1">
    @foreach ($menuData[0]->menu as $menu)
      @php
        $showMenu = true; // Default: show the menu
        $user = Auth::user();

        // Check if the menu has a roles field
        if (isset($menu->roles) && count($menu->roles) > 0) {
          $showMenu = collect($menu->roles)->contains(fn($role) => $user->hasRole($role));
        }
      @endphp

      @if ($showMenu)
        <li class="menu-item {{ $activeClass ?? '' }}">
          <a href="{{ isset($menu->url) ? url($menu->url) : 'javascript:void(0);' }}" class="menu-link">
            @isset($menu->icon)
              <i class="{{ $menu->icon }}"></i>
            @endisset
            <div>{{ isset($menu->name) ? __($menu->name) : '' }}</div>
          </a>
        </li>
      @endif
    @endforeach
  </ul>
</aside>
