<header class="topbar">

    <div class="topbar-left">

        <button
            class="mobile-menu-btn"
            id="menuToggle"
            type="button"
        >
            ☰
        </button>

        <div>

            <x-breadcrumbs :items="$breadcrumbs ?? []" />

            <h1 class="page-title">
                @yield('page-title', 'Dashboard')
            </h1>

            <p class="page-subtitle">
                @yield(
                    'page-subtitle',
                    'Welcome back to MARKETHING'
                )
            </p>

        </div>

    </div>

    <div class="topbar-actions">

        @php

            $user = auth()->user();

            $isFounder =
                $user &&
                $user->role === 'founder';

            $dashboardUrl =
                $isFounder
                    ? url('/admin/dashboard')
                    : url('/agency/dashboard');

            $settingsUrl =
                $isFounder
                    ? url('/admin/settings')
                    : url('/agency/settings');

            $displayRole =
                $isFounder
                    ? 'Founder Admin'
                    : 'Agency Account';

        @endphp

        <div class="profile-wrapper">

            <button
                class="profile-box profile-button"
                type="button"
                data-toggle-profile
            >

                <div class="avatar"></div>

                <div class="profile-meta">

                    <strong>
                        {{ $user->name ?? 'MARKETHING User' }}
                    </strong>

                    <p>
                        {{ $displayRole }}
                    </p>

                </div>

            </button>

            <div
                class="dropdown-panel profile-dropdown"
                id="profileDropdown"
            >

                <a href="{{ $dashboardUrl }}">
                    Dashboard
                </a>

                <a href="{{ $settingsUrl }}">
                    Account Settings
                </a>

                <form
                    method="POST"
                    action="{{ route('logout') }}"
                >
                    @csrf

                    <button
                        type="submit"
                        class="dropdown-button danger-link"
                    >
                        Sign Out
                    </button>

                </form>

            </div>

        </div>

    </div>

</header>