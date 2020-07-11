

<div class="nav-left-sidebar sidebar-dark">
    <div class="menu-list">
        <nav class="navbar navbar-expand-lg navbar-light">
            <a class="d-xl-none d-lg-none" href="#">Dashboard</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav flex-column">
                    <li class="nav-divider">
                        Menu
                    </li>
                    <li class="nav-item " id="dashboardMenu">
                        <a class="nav-link " href="{{ URL('dashboard') }}">
                            <i class="fa fa-fw fa-user-circle"></i>Dashboard</a>
                    </li>

                    @if(Auth::user()->hasRole('Admin'))
                    <li class="nav-item" id="nutritionistMenu">
                        <a class="nav-link" href="{{ URL('nutritionist-add') }}">
                            <i class="fa fa-fw fa-briefcase-medical"></i>Adicionar Nutricionista</a>
                    </li>
                    <li class="nav-item" id="nutritionistListMenu">
                        <a class="nav-link" href="{{ URL('nutritionist-list') }}">
                            <i class="fa fa-fw fa-file-medical-alt"></i>Ver Nutricionistas</a>
                    </li>
                        <li class="nav-item" id="trainerMenu">
                            <a class="nav-link" href="{{ URL('personal-trainer-add') }}">
                                <i class="fa fa-fw fa-briefcase-medical"></i>Add personal trainer</a>
                        </li>
                        <li class="nav-item" id="trainerListMenu">
                            <a class="nav-link" href="{{ URL('personal-trainer-list') }}">
                                <i class="fa fa-fw fa-file-medical-alt"></i>Trainer List</a>
                        </li>
                    @endif


                    @if(Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Personal_trainer') )
                        <li class="nav-item " id="patientMenu">
                            <a class="nav-link" href="{{ URl('trainer-patients-add') }}"><i class="fa fa-fw fa-user-plus"></i>Trainer Adicionar Patients</a>
                        </li>
{{--                        <li class="nav-item " id="initialTrainingPlanMenu">--}}
{{--                            <a class="nav-link" href="{{ URL('initial-training-Plan') }}"><i class="fa fa-fw fa-briefcase-medical"></i>Initial training Plan</a>--}}
{{--                        </li>--}}
                        <li class="nav-item " id="initialTrainingPlanMenu">
                            <a class="nav-link" href="{{ URL('ongoing-training-Plan') }}"><i class="fa fa-fw fa-briefcase-medical"></i>On Going training Plan</a>
                        </li>
                    @endif
                    @if(Auth::user()->hasRole('Personal_trainer') )
                    <li class="nav-item " id="patientListMenu">
                        <a class="nav-link " href="{{ URl('trainer-patients-list') }}"><i class="fa fa-fw fa-th-list"></i>Ver Trainer Patients</a>
                    </li>
                    @endif
                    @if(Auth::user()->hasRole('Admin') || Auth::user()->hasRole('Nutritionist') )
                    <li class="nav-item " id="patientMenu">
                        <a class="nav-link" href="{{ URl('patients-add') }}"><i class="fa fa-fw fa-user-plus"></i>Adicionar Cliente</a>
                    </li>

                    <li class="nav-item " id="patientListMenu">
                        <a class="nav-link " href="{{ URl('patients') }}"><i class="fa fa-fw fa-th-list"></i>Ver Clientes</a>
                    </li>

                    <li class="nav-item " id="appointmentMenu">
                        <a class="nav-link" href="{{ URL('appointments') }}"><i class="fa fa-fw fa-briefcase-medical"></i>Gerir
                            Consultas</a>
                    </li>
                    <li class="nav-item " id="onlineAppointmentMenu">
                        <a class="nav-link" href="{{ url('online-appointments') }}"><i class="fa fa-fw fa-briefcase-medical"></i>Gerir
                            Consultas Online</a>
                    </li>

                    @endif
                    <br/><br/><br/><br/><br/><br/>
                </ul>
            </div>
        </nav>
    </div>
</div>
