<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Dashboard - Nutri4Solutions</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{!! asset('assets/vendor/fonts/circular-std/style.css') !!}" rel="stylesheet">
    <link rel="stylesheet" href="{!! asset('assets/libs/css/style.css') !!}">
    <link rel="stylesheet" href="{!! asset('assets/vendor/fonts/fontawesome/css/fontawesome-all.css') !!}">
    <link rel="stylesheet" href="{!! asset('assets/vendor/datepicker/tempusdominus-bootstrap-4.css') !!}"/>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css"/>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.5/chosen.jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.5/chosen.min.css"/>

    <link rel="stylesheet" href="{!! asset('assets/libs/css/fstdropdown.css') !!}">


    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.css"/>

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/fixedheader/3.1.5/css/fixedHeader.dataTables.min.css"/>
    <style>
        thead input {
            width: 100%;
        }
      
        table thead, table > thead > tr > th {
            background-color: #1f8677;
            color: white !important;

        }
        .nav_ul{
            -webkit-overflow-scrolling: auto;
        }
        select {
            color: #71748d;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-position: 98% 52%;
            background-size: 12px;
            background-repeat: no-repeat;
            background-image: url('img/down-arrow.svg');
            padding: 5px 15px;
            border: 1px solid #d2d2e4;
            border-radius: 2px;
        }
    </style>

</head>
