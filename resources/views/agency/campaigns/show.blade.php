@extends('layouts.dashboard')

@section('title', 'Campaign Output - MARKETHING')

@section('page-title', 'Summer Launch')
@section('page-subtitle', 'Review, edit, copy, and save generated campaign posts.')

@section('user-name', 'Nova Marketing')
@section('user-role', 'Agency Account')

@section('dashboard-content')

<div class="campaign-output-page">

    <div class="campaign-output-hero">

        <div>
            <span class="hero-badge">Generated Campaign</span>

            <h2>Summer Launch</h2>

            <p>
                Campaign generated for Bloom Café using the Young Professional persona.
                Review each post, edit content inline, copy post content, and save changes.
            </p>
        </div>

        <div class="hero-actions">
            <button class="btn btn-secondary" type="button" data-agency-dashboard>
                Back To Dashboard
            </button>

            <button class="btn btn-primary" type="button">
                Export Campaign
            </button>
        </div>

    </div>

    <div class="campaign-meta-grid">

        <div class="campaign-meta-card">
            <span>Client</span>
            <strong>Bloom Café</strong>
        </div>

        <div class="campaign-meta-card">
            <span>Persona</span>
            <strong>Young Professional</strong>
        </div>

        <div class="campaign-meta-card">
            <span>Date Range</span>
            <strong>May 10 → May 23</strong>
        </div>

        <div class="campaign-meta-card">
            <span>Total Posts</span>
            <strong>6</strong>
        </div>

    </div>

    <div class="campaign-output-layout">

        <main class="post-list">

            <x-post-card
                number="1"
                title="Post 1 of 6 — Morning Ritual"
                date="May 10"
                channel="Instagram"
                media="Reel"
                summary="A warm morning coffee post inviting followers to start their day at Bloom Café."
                caption="Start your morning with the kind of coffee that makes the whole day feel softer. Visit Bloom Café for fresh espresso, cozy corners, and your favorite first sip."
                hashtags="#BloomCafe #CoffeeTime #RamallahCafe #MorningCoffee"
                creative="A short reel showing espresso pouring into a glass cup, sunlight hitting the table, and a customer opening a laptop beside fresh dessert."
            />

            <x-post-card
                number="2"
                title="Post 2 of 6 — Dessert Moment"
                date="May 12"
                channel="Facebook"
                media="Image"
                summary="A sweet afternoon post promoting handmade desserts and calm café breaks."
                caption="Your afternoon deserves something sweet. Pair your favorite coffee with our handmade desserts and enjoy a calm break at Bloom Café."
                hashtags="#DessertLovers #CafeLife #BloomCafe"
                creative="A warm close-up image of cake beside a cappuccino on a wooden table with soft natural light."
                edited="true"
            />

            <x-post-card
                number="3"
                title="Post 3 of 6 — Friends Gathering"
                date="May 15"
                channel="Instagram"
                media="Carousel"
                summary="A social post encouraging friend groups to gather, share coffee, and enjoy the atmosphere."
                caption="Good coffee tastes even better with good company. Bring your friends, choose your favorite corner, and enjoy the Bloom Café experience."
                hashtags="#CafeFriends #CoffeeAndConversation #BloomCafe"
                creative="Carousel showing friends laughing, drinks on the table, cozy seating, and dessert sharing."
            />

            <x-post-card
                number="4"
                title="Post 4 of 6 — Work From Café"
                date="May 18"
                channel="Facebook"
                media="Image"
                summary="A productivity-focused post positioning Bloom Café as a calm place to work."
                caption="Need a calm place to focus? Bloom Café gives you the coffee, comfort, and atmosphere you need to get things done."
                hashtags="#WorkFromCafe #RemoteWork #BloomCafe"
                creative="Photo of a laptop, notebook, iced coffee, and headphones on a café table."
            />

            <x-post-card
                number="5"
                title="Post 5 of 6 — Seasonal Drink"
                date="May 20"
                channel="Instagram"
                media="Reel"
                summary="A promotional reel introducing a seasonal drink and encouraging followers to try it."
                caption="A seasonal favorite is waiting for you. Come try our limited-time drink and make your coffee break feel special."
                hashtags="#SeasonalDrink #CafeReel #BloomCafe"
                creative="Reel showing the drink being prepared, topped, served, and enjoyed by a customer."
            />

            <x-post-card
                number="6"
                title="Post 6 of 6 — Weekend Invitation"
                date="May 23"
                channel="Facebook"
                media="Image"
                summary="A weekend invitation post encouraging followers to slow down and visit the café."
                caption="This weekend, slow down and treat yourself to your favorite coffee, fresh dessert, and a cozy seat at Bloom Café."
                hashtags="#WeekendCafe #CoffeeBreak #BloomCafe"
                creative="Lifestyle image of a relaxed weekend table setup with two drinks, dessert, and soft background blur."
            />

        </main>

        <aside class="campaign-output-side">

            <div class="table-card">
                <h2 class="section-title">Campaign Summary</h2>

                <div class="summary-list">
                    <div>
                        <span>Objective</span>
                        <strong>Awareness</strong>
                    </div>

                    <div>
                        <span>Channels</span>
                        <strong>Instagram + Facebook</strong>
                    </div>

                    <div>
                        <span>Prompt Version</span>
                        <strong>Master v1.0</strong>
                    </div>

                    <div>
                        <span>Status</span>
                        <strong>Generated</strong>
                    </div>
                </div>
            </div>

            <div class="table-card">
                <h2 class="section-title">Post Rules</h2>

                <div class="checklist">
                    <div class="checklist-item done">
                        <span>✓</span>
                        One post per day per channel by total
                    </div>

                    <div class="checklist-item done">
                        <span>✓</span>
                        All posts inside date range
                    </div>

                    <div class="checklist-item done">
                        <span>✓</span>
                        Total posts match request
                    </div>
                </div>
            </div>

            <div class="table-card">
                <h2 class="section-title">Quick Actions</h2>

                <div class="shortcut-list">
                    <button class="shortcut-card" type="button">
                        <span>⧉</span>
                        Copy All Posts
                    </button>

                    <button class="shortcut-card" type="button">
                        <span>↓</span>
                        Download Content
                    </button>

                    <button class="shortcut-card" type="button" data-create-campaign>
                        <span>✦</span>
                        New Campaign
                    </button>
                </div>
            </div>

        </aside>

    </div>

</div>

<x-toast
    id="appToast"
    title="Saved"
    message="Your changes were saved successfully."
/>

@endsection