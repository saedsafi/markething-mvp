<aside class="sidebar" id="sidebar">

    <a
    href="{{ auth()->user()->isFounder() ? route('admin.dashboard') : route('agency.dashboard') }}"
    class="brand-logo"
>
    <div class="logo-box"></div>

    <img
        src="{{ asset('images/logo.svg') }}"
        alt="MARKETHING"
        class="brand-text"
    >
</a>

    <nav class="sidebar-menu">

        @if(request()->is('admin*'))

            <a href="{{ url('/admin/dashboard') }}" class="sidebar-link" data-route="/admin/dashboard">
                Admin Dashboard
            </a>

            <a href="{{ url('/admin/prompts') }}" class="sidebar-link" data-route="/admin/prompts">
                Prompt Editor
            </a>

            <a href="{{ url('/admin/campaigns') }}" class="sidebar-link" data-route="/admin/campaigns">
                Campaigns
            </a>

            <a href="{{ url('/admin/logs') }}" class="sidebar-link" data-route="/admin/logs">
                LLM Logs
            </a>
            <a href="{{ url('/admin/usage') }}" class="sidebar-link" data-route="/admin/usage">
                Usage & Cost
            </a>
            <a href="{{ url('/admin/settings') }}" class="sidebar-link" data-route="/admin/settings">
                Settings
            </a>


        @else

            <a href="{{ url('/agency/dashboard') }}" class="sidebar-link" data-route="/agency/dashboard">
                Dashboard
            </a>

            <a href="{{ url('/agency/clients') }}" class="sidebar-link" data-route="/agency/clients">
                Clients
            </a>

            <a href="{{ url('/agency/campaigns/create') }}" class="sidebar-link" data-route="/agency/campaigns/create"  data-exact="true">
                Create Campaign
            </a>

            <a href="{{ route('agency.campaigns.index') }}" class="sidebar-link" data-route="/agency/campaigns"  data-exact="true">
                Campaigns
            </a>

        @endif

    </nav>
<!--
    <div class="sidebar-plan">
        @if(request()->is('admin*'))
            <span>Founder Admin</span>
            <p>Admin-only tools and configuration.</p>
        @else
            <span>Client Limit</span>
            <p>Limit is set per agency by the founder.</p>
        @endif
    </div>
-->
</aside>

<div class="sidebar-overlay" id="sidebarOverlay"></div>