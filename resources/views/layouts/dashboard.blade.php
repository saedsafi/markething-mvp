@extends('layouts.app')

@section('content')

<div class="dashboard-layout">

    @include('components.sidebar')

    <main class="main-content">

        @include('components.topbar')

        @yield('dashboard-content')
        

    </main>

</div>

@include('components.confirm-modal')

@endsection