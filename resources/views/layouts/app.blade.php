<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('layouts.partials.header')
<body>
<div id="app">

    @include('layouts.partials.navbar')
    @include('layouts.partials.sidebar')
    <main class="py-4">
        <div class="dashboard-wrapper">
            @yield('content')

            @include('layouts.partials.script')

            @yield('script')

            @include('layouts.partials.footer')

        </div>
    </main>
</div>
</body>
</html>
