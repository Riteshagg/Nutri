@extends('layouts.app')

@section('content')

    <div class="dashboard-ecommerce">
        <div class="container-fluid dashboard-content ">

    <!-- ============================================================== -->
    <!-- pageheader  -->
    <!-- ============================================================== -->
    <div class="row">
        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                @if (Auth::user()->hasRole('Admin'))
                <h2 class="pageheader-title">Área de Administrador </h2>
                @elseif(Auth::user()->hasRole('Nutritionist'))
                <h2 class="pageheader-title">Área de Nutricionista</h2>
                @endif
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- end pageheader  -->
    <!-- ============================================================== -->
    <div class="ecommerce-widget">
        <div class="row">
            @if (Auth::user()->hasRole('Admin'))
            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12">
                <div class="card border-4 border-top border-top-primary">
                    <div class="card-body">
                        <h5 class="text-muted">Nutricionistas</h5>
                        <div class="metric-value d-inline-block">
                            <h1 class="mb-1">
                                {{ $nutritionist['active'] .'/'. $nutritionist['total']}}
                            </h1>
                        </div>

                    </div>
                </div>
            </div>
            @endif

            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12">
                <div class="card border-4 border-top border-top-primary">
                    <div class="card-body">
                        <h5 class="text-muted">Clientes</h5>
                        <div class="metric-value d-inline-block">
                            @if (Auth::user()->hasRole('Admin'))
                            <h1 class="mb-1">
                                {{ $patient['active'] .'/'. $patient['total']}}
                            </h1>
                            @else
                            <h1 class="mb-1">
                                {{ $patient['active']}}
                            </h1>
                            @endif
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 col-12">
                <div class="card border-4 border-top border-top-primary">
                    <div class="card-body">
                        <h5 class="text-muted">Consultas</h5>
                        <div class="metric-value d-inline-block">
                            <h1 class="mb-1">
                                {{ $appointment['active'] }}
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
</div>

@endsection
