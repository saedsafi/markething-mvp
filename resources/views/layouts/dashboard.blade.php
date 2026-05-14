@extends('layouts.app')

@section('content')

<div class="dashboard-layout">

    @include('components.sidebar')

    <main class="main-content">

        @include('components.topbar')

        <div class="dashboard-main">

            <div class="dashboard-content-wrapper">
        
                @yield('dashboard-content')
        
            </div>
        
        </div>        

    </main>

</div>

@include('components.confirm-modal')

@endsection