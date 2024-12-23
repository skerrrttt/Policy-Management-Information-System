@php
$containerNav = $containerNav ?? 'container-fluid';
$navbarDetached = ($navbarDetached ?? '');
$user = Auth::user();


// $user = Auth::user();
// $firstname = $user->firstname ?? 'Guest';
// $role = $user->roles()->first()->name ?? 'User';
@endphp

<!-- Navbar -->
@if(isset($navbarDetached) && $navbarDetached == 'navbar-detached')
<nav class="layout-navbar {{$containerNav}} navbar navbar-expand-xl {{$navbarDetached}} align-items-center bg-navbar-theme" id="layout-navbar">
  @endif
  @if(isset($navbarDetached) && $navbarDetached == '')
  <nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="{{$containerNav}}">
      @endif

      <!--  Brand demo (display only for navbar-full and hide on below xl) -->
      @if(isset($navbarFull))
      <div class="navbar-brand app-brand demo d-none d-xl-flex py-0 me-4">
        <a href="{{url('/')}}" class="app-brand-link gap-2">
          <span class="app-brand-logo demo">@include('_partials.macros',["width"=>25,"withbg"=>'var(--bs-primary)'])</span>
          <span class="app-brand-text demo menu-text fw-bold">{{config('variables.templateName')}}</span>
        </a>
      </div>
      @endif

      <!-- ! Not required for layout-without-menu -->
      @if(!isset($navbarHideToggle))
      <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0{{ isset($menuHorizontal) ? ' d-xl-none ' : '' }} {{ isset($contentNavbar) ?' d-xl-none ' : '' }}">
        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
          <i class="bx bx-menu bx-sm"></i>
        </a>
      </div>
      @endif

      <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
        <ul class="navbar-nav flex-row align-items-center ms-auto">
            <!-- Display Campus Name -->
           


          {{-- <!-- Place this tag where you want the button to render. -->
          <li class="nav-item lh-1 me-3">
            <a class="github-button" href="https://github.com/themeselection/sneat-html-laravel-admin-template-free" data-icon="octicon-star" data-size="large" data-show-count="true" aria-label="Star themeselection/sneat-html-laravel-admin-template-free on GitHub">Star</a>
          </li> --}}


          <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-2">
            <a class="nav-link dropdown-toggle hide-arrow show" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="true">
              <span class="position-relative">
                <i class="bx bx-bell bx-sm"></i>
                <span class="badge rounded-pill bg-danger badge-dot badge-notifications border"></span>
              </span>
            </a>
           
              
                 
                  
                 

          <!-- User -->
          <li class="nav-item dropdown-user dropdown">
            <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
              <div class="avatar avatar-online">
                @if($user && $user->image)
                  <img src="{{ asset('storage/app/public/' . $user->image) }}" alt="Profile Image" class="w-px-40 h-auto rounded-circle">
                @else
                  <img src="{{ asset('assets/img/avatars/user.png') }}" alt="" class="w-px-40 h-auto rounded-circle">
                @endif
              </div>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li>
                <a class="dropdown-item" href="#">
                  <div class="d-flex">
                    <div class="avatar avatar-online">
                      @if($user && $user->image)
                        <img src="{{ asset('storage/app/public/' . $user->image) }}" alt="Profile Image" class="w-px-40 h-auto rounded-circle">
                      @else
                        <img src="{{ asset('assets/img/avatars/user.png') }}" alt="" class="w-px-40 h-auto rounded-circle">
                      @endif
                    </div>
                    <div class="flex-grow-1 ms-3">
                      @if($user)
                        <span class="fw-medium d-block">{{ $user->first_name }} {{ $user->last_name }}</span>
                        <small class="text-muted">
                          @php
                            $roles = [];
                            if ($user->hasRole('academic_council_membership')) $roles[] = 'Academic Council Member';
                            if ($user->hasRole('admin_council_membership')) $roles[] = 'Admin Council Member';
                            if ($user->hasRole('local_secretary')) $roles[] = 'Local Secretary';
                            if ($user->hasRole('board_sec')) $roles[] = 'Board Secretary';
                            if ($user->hasRole('university_secretary')) $roles[] = 'University Secretary';
                            echo implode(', ', $roles);
                          @endphp
                        </small>
                      @endif
                    </div>
                  </div>
                </a>
              </li>
              <li>
                <div class="dropdown-divider"></div>
              </li>
              <li>
                <!-- Logout -->
                <a class="dropdown-item" href="{{ route('logout') }}" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                  <i class="bx bx-power-off me-2"></i>
                  <span class="align-middle">Log Out</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  @csrf
                </form>
              </li>
            </ul>
          </li>
          <!--/ User -->
        </ul>
      </div>

      @if(!isset($navbarDetached))
    </div>
    @endif
  </nav>
  <!-- / Navbar -->
