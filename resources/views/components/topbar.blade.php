<header class="topbar">

    <div class="topbar-left">

        <button class="mobile-menu-btn" id="menuToggle" type="button">
            ☰
        </button>

        <div>
            <x-breadcrumbs :items="$breadcrumbs ?? []" />

            <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
            <p class="page-subtitle">@yield('page-subtitle', 'Welcome back to MARKETHING')</p>
        </div>

    </div>

    <div class="topbar-actions">

        <div class="profile-wrapper">

            <button class="profile-box profile-button" type="button" data-toggle-profile>
                <div class="avatar"></div>

                <div>
                    <strong>@yield('user-name', 'Nova Marketing')</strong>
                    <p>@yield('user-role', 'Agency Account')</p>
                </div>
            </button>

            <div class="dropdown-panel profile-dropdown" id="profileDropdown">

                <a href="{{ url('/change-password') }}">Account Settings</a>
                <a href="{{ url('/agency/dashboard') }}">Dashboard</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                
                    <button type="submit" class="dropdown-button danger-link">
                        Sign Out
                    </button>
                </form>
            </div>

        </div>

    </div>

</header>