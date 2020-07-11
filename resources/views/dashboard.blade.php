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
                <!-- If User Role Admin -->
                <h2 class="pageheader-title">Área de Administrador </h2>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- end pageheader  -->
    <!-- ============================================================== -->
    <div class="ecommerce-widget">
        <?php
        //                    if ($auth->hasRole(Role::ADMIN)) {
        //                        $totalNutritionists = $con->query(sprintf("SELECT count(*) as total FROM users where roles_mask=%s", Role::COLLABORATOR))->fetch(PDO::FETCH_ASSOC);
        //                        $activeNutritionists = $con->query(sprintf("SELECT count(*) as active FROM users where roles_mask=%s AND status=%s",\Delight\Auth\Role::COLLABORATOR,\Delight\Auth\Status::NORMAL))->fetch(PDO::FETCH_ASSOC);
        //                    }
        //
        //                    if ($auth->hasRole(\Delight\Auth\Role::ADMIN)) {
        //                        $totalPatients = $con->query(sprintf("SELECT count(*) AS total FROM users where roles_mask=%s",Role::CONSUMER))->fetch(PDO::FETCH_ASSOC);
        //                        $activePatients = $con->query(sprintf("SELECT count(*) AS active FROM users where status=%s and roles_mask=%s",Status::NORMAL,Role::CONSUMER))->fetch(PDO::FETCH_ASSOC);
        //                    } else {
        //                        $totalPatients = $con->query("SELECT count(*) AS total FROM users WHERE nutritionistId=" . $auth->getUserId())->fetch(PDO::FETCH_ASSOC);
        //                        $activePatients = $con->query(sprintf("SELECT count(*) AS active FROM users WHERE status=%s AND nutritionistId=%s", Status::NORMAL, $auth->getUserId()))->fetch(PDO::FETCH_ASSOC);
        //                    }
        //
        //                    $nrAppointments = $con->query("SELECT count(*) AS total FROM appointment")->fetch(PDO::FETCH_ASSOC);
        ?>

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

                @if (Auth::user()->hasRole('Personal_trainer'))
                    <h1></h1>
                @else
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
                @endif
        </div>
    </div>
    </div>
</div>

@endsection
