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

        <div class="global-search">
            <input type="text" placeholder="Search..." data-global-search>
            <span>⌕</span>
        </div>

        <div class="notification-wrapper">

            <button class="notification-btn" type="button" data-toggle-notifications>
                🔔
                <span></span>
            </button>

            <div class="dropdown-panel notification-dropdown" id="notificationDropdown">

                <div class="dropdown-header">
                    <strong>Notifications</strong>
                    <small>3 new</small>
                </div>

                <div class="notification-item">
                    <div>✦</div>
                    <p>
                        <strong>Campaign generated</strong>
                        <span>Summer Launch is ready for review.</span>
                    </p>
                </div>

                <div class="notification-item">
                    <div>◎</div>
                    <p>
                        <strong>AI assist used</strong>
                        <span>Brand personality answer drafted.</span>
                    </p>
                </div>

                <div class="notification-item">
                    <div>!</div>
                    <p>
                        <strong>Usage limit reminder</strong>
                        <span>18 of 50 AI assists used today.</span>
                    </p>
                </div>

            </div>

        </div>

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
                <a href="{{ url('/login') }}" class="danger-link">Sign Out</a>

            </div>

        </div>

    </div>

</header>