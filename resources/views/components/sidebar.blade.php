<aside class="sidebar" id="sidebar">

    <div class="logo sidebar-logo">
        <div class="logo-box"></div>
        <h1>MARKETHING</h1>

        <button class="sidebar-close" id="sidebarClose" type="button">×</button>
    </div>

    <nav class="sidebar-menu">

        <a href="{{ url('/agency/dashboard') }}" class="sidebar-link" data-route="/agency/dashboard">
            Dashboard
        </a>

        <a href="{{ url('/agency/clients') }}" class="sidebar-link" data-route="/agency/clients">
            Clients
        </a>

        <a href="{{ url('/agency/campaigns/create') }}" class="sidebar-link" data-route="/agency/campaigns/create">
            Create Campaign
        </a>

        <a href="{{ url('/agency/campaigns/show') }}" class="sidebar-link" data-route="/agency/campaigns/show">
            Campaign Output
        </a>

        <a href="{{ url('/admin/dashboard') }}" class="sidebar-link" data-route="/admin/dashboard">
            Admin
        </a>

        <a href="{{ url('/admin/prompts') }}" class="sidebar-link" data-route="/admin/prompts">
            Prompt Editor
        </a>

        <a href="{{ url('/admin/settings') }}" class="sidebar-link" data-route="/admin/settings">
            Settings
        </a>

    </nav>

    <div class="sidebar-plan">
        <span>Basic Plan</span>
        <p>10 clients · 50 AI assists/day</p>
    </div>

</aside>

<div class="sidebar-overlay" id="sidebarOverlay"></div>