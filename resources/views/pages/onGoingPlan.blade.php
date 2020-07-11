@extends('layouts.app')

@section('content')

    <div class="container-fluid  dashboard-content">

        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="page-header">
                    <h2 class="pageheader-title">On Going training Plan</h2>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <div class="card-header d-flex">
                        <h4 class="card-header-title">Listagem de On Going training Plan</h4>

                    </div>
                    <div class="card-body">
                        @include('layouts.message')
                        @if(Auth::user()->hasRole('Admin'))
                            <div class="form-group row" id="patientTrainerAppDropdownRow">
                                <label class="col-12 col-sm-3 col-form-label text-sm-right">Selecionar
                                    Trainer</label>
                                <div class="col-12 col-sm-8 col-lg-6">
                                    @php
                                        $sel = !(isset($_GET) && isset($_GET['nid'])) ? "selected" : "";
                                    @endphp

                                    <select class="form-control" name="trainer" id="trainerSelect">
                                        <option value="" disabled {{ $sel }}> Selecionar Trainer</option>
                                        @foreach ($trainers as $key=>$trainer)
                                            @php
                                                $selected = ((isset($_GET['nid'])) && $_GET['nid'] == $trainer->id )? "selected" : "";
                                            @endphp
                                            <option value="{{ $trainer->id }}" {{ $selected }}>{{ $trainer->name  }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <script>
                                    $("#trainerSelect").on('change', function (event) {
                                        let url = new URL(window.location.href);
                                        url.searchParams.set("nid", event.target.value);
                                        url.searchParams.delete("id");
                                        window.location.href = url.href
                                    })
                                </script>
                            </div>
                        @endif

                        @if(Auth::user()->hasRole('Personal_trainer') || (count($patients)) )
                            <div class="form-group row">
                                <label class="col-12 col-sm-3 col-form-label text-sm-right">Selecionar
                                    Patient</label>
                                <div class="col-12 col-sm-8 col-lg-6">
                                    <?php $sel = !(isset($_GET) && isset($_GET['id'])) ? "selected" : ""; ?>
                                    <select class="form-control" name="patient" id="patientSelect">
                                        <option value="" disabled {{ $sel }} > Selecionar Patients</option>
                                        @foreach ($patients as $key => $patient_array)
                                            @php
                                                $selected = ((isset($_GET['uid'])) && $_GET['uid'] == $patient_array->user->id )? "selected" : "";
                                            @endphp
                                            <option value="{{ $patient_array->user->id }}" {{ $selected }}>{{ $patient_array->user->name  }}</option>

                                        @endforeach
                                    </select>
                                    <script>
                                        $("#patientSelect").on('change', function (event) {
                                            let url = new URL(window.location.href);
                                            url.searchParams.set("uid", event.target.value)
                                            window.location.href = url.href
                                        })
                                    </script>
                                </div>
                            </div>
                        @endif

                        @if (isset($_GET) && isset($_GET['uid']))

                            <table id="appointmentWholeTable" class="display table table-hover table-striped"
                                   style="width:100%">
                                <thead>
                                <tr>
                                    <th>Data</th>
                                    <th>Peso (Kg)</th>
                                    <th>PP (cm)</th>
                                    <th>PC1 (cm)</th>
                                    <th>PC2 (cm)</th>
                                    <th>PA (cm)</th>
                                    <th>Download PDF</th>
                                </tr>
                                </thead>

                                <tbody id="appointmentTableBody">

                                @foreach($ongoing_training_plan as $appointment)
                                    @php

                                        $weight = (isset(json_decode($appointment->anthropometricData)->body_weight)) ? json_decode($appointment->anthropometricData)->body_weight : "";
                                        $chest = (isset(json_decode($appointment->anthropometricData)->body_chest)) ? json_decode($appointment->anthropometricData)->body_chest : "";
                                        $waist = (isset(json_decode($appointment->anthropometricData)->body_waist)) ? json_decode($appointment->anthropometricData)->body_waist : "";
                                        $waistHip = (isset(json_decode($appointment->anthropometricData)->body_waist_hip)) ? json_decode($appointment->anthropometricData)->body_waist_hip : "";
                                        $hip = (isset(json_decode($appointment->anthropometricData)->body_hip)) ? json_decode($appointment->anthropometricData)->body_hip : "";

                                      $observations = ($appointment->observations != "") ? "<i class=\"fas fa-file\"></i>" : "";
                                                 $download = $appointment->attachment ? "Download":"";
                                    @endphp

                                    <tr  data-appointment-id='{{ $appointment->id }}'
                                         onclick='editOnGoingPlan({{ $appointment->id }})' style='cursor: pointer'>
                                        <td>{{ $appointment->date }}</td>
                                        <td>{{ $weight }}</td>
                                        <td>{{ $chest }}</td>
                                        <td>{{ $waist }}</td>
                                        <td>{{ $waistHip }}</td>
                                        <td>{{ $hip }}</td>
                                        {{--                                    <td><a href = 'download-online/{{$appointment->id}}'>{!! $download !!}</a></td>--}}
                                        <td><a href = '{{ route('trainerDownloadOnline',$appointment->attachment) }}' download="{{$appointment->attachment}}">{!! $download !!}</a></td>

                                    </tr>

                                @endforeach

                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>Data</th>
                                    <th>Peso (Kg)</th>
                                    <th>PP (cm)</th>
                                    <th>PC1 (cm)</th>
                                    <th>PC2 (cm)</th>
                                    <th>PA (cm)</th>
                                    <th>Download PDF</th>
                                </tr>
                                </tfoot>
                            </table>

                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if ((isset($_GET) && isset($_GET['uid']) && isset($_GET['nid'])) || (isset($_GET) && isset($_GET['uid']) && Auth::user()->hasRole('Personal_trainer') ))
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="card">
                        <h5 class="card-header">Adicionar On Going Plan</h5>
                        <div class="card-body">
                            @php
                                $getId = $queries !=""?$queries->id:"";
                            @endphp
                            <button class="btn btn-primary btn-sm float-right" id="loadPreviousAppointmentBtn" onclick="loadPreviousAppointment(<?=$getId ?>)">Carregar consulta anterior</button>

                            @php
                                if (Auth::user()->hasRole('Admin')) {
                                    $qs = "?" . http_build_query(["uid" => trim($_GET['uid']), "nid" => trim($_GET['nid'])]);
                                } else {
                                    $qs = "?" . http_build_query(["uid" => trim($_GET['uid'])]);
                                }
                            @endphp

                                <form action="{{ route('ongoing_plan_store') }}{{$qs}}" method="post" enctype="multipart/form-data" name="addAppointmentForm" id="addOnlineAppointmentForm" data-parsley-validate id="form">
                                    @csrf
                                    <div class="form-group row">
                                        <div class="col-12 col-sm-8 col-lg-6">
                                            <input name="nid" class="form-control" value="<?php if(isset($_GET['nid'])) {echo $_GET['nid'];}else{echo \Illuminate\Support\Facades\Auth::user()->id; }  ?>" type="hidden" id="nid"/>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-12 col-sm-8 col-lg-6">
                                            <input name="uid" class="form-control" value="<?=$_GET['uid']?>" type="hidden" id="uid"/>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-12 col-sm-3 col-form-label text-sm-right">Data da Consulta</label>
                                        <div class="col-12 col-sm-8 col-lg-6">
                                            <input name="appDate" required class="form-control" dateformat="d M y"
                                                   type="date" id="date"/>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                                                        data-target="#dadosAntropometricos">Dados Antropométricos
                                                </button>
                                            </h2>
                                        </div>
                                        <div id="dadosAntropometricos" class="collapse show">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12 form-inline">
                                                        <div class="col-3 text-right"><label>Altura</label></div>
                                                        <div class="col-9">
                                                            <input type="text" placeholder="Altura" class="form-control"
                                                                   name="body_height" id="body_height"
                                                                   data-form-section="antropometrics"> cm
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-12 form-inline">
                                                        <div class="col-3 text-right"><label>Peso</label></div>
                                                        <div class="col-9">
                                                            <input type="text" placeholder="Peso" class="form-control"
                                                                   name="body_weight" id="body_weight"
                                                                   data-form-section="antropometrics"> Kg
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-12 form-inline">
                                                        <div class="col-3 text-right"><label>IMC</label></div>
                                                        <div class="col-9">
                                                            <input type="text" placeholder="IMC" class="form-control"
                                                                   name="body_bmi" id="body_bmi"
                                                                   data-form-section="antropometrics"> Kg/m<sup>2</sup>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-12 form-inline">
                                                        <div class="col-3 text-right"><label>% Massa Gorda</label></div>
                                                        <div class="col-9">
                                                            <input type="text" placeholder="% Massa Gorda"
                                                                   class="form-control" name="body_fat_mass"
                                                                   id="body_fat_mass" data-form-section="antropometrics"> %
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-12 form-inline">
                                                        <div class="col-3 text-right"><label>
                                                                <abr title="Perímetro do Peito">PP</abr>
                                                            </label></div>
                                                        <div class="col-9">
                                                            <input type="text" placeholder="Perímetro Peito"
                                                                   class="form-control" name="body_chest" id="body_chest"
                                                                   data-form-section="antropometrics"> cm
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-12 form-inline">
                                                        <div class="col-3 text-right"><label>
                                                                <abr title="Perímetro da Cintura">PC1</abr>
                                                            </label></div>
                                                        <div class="col-9">
                                                            <input type="text" placeholder="Perímetro Cintura"
                                                                   class="form-control" name="body_waist" id="body_waist"
                                                                   data-form-section="antropometrics"> cm
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-12 form-inline">
                                                        <div class="col-3 text-right"><label>
                                                                <abr title="Perímetro da Cintura e Anca">PC2</abr>
                                                            </label></div>
                                                        <div class="col-9">
                                                            <input type="text" placeholder="Perímetro Cintura e Anca"
                                                                   class="form-control" name="body_waist_hip"
                                                                   id="body_waist_hip" data-form-section="antropometrics">
                                                            cm
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-12 form-inline">
                                                        <div class="col-3 text-right"><label>
                                                                <abr title="Perímetro da Anca">PA</abr>
                                                            </label></div>
                                                        <div class="col-9">
                                                            <input type="text" placeholder="Perímetro Anca"
                                                                   class="form-control" name="body_hip" id="body_hip"
                                                                   data-form-section="antropometrics"> cm
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>

                                    <div class="accordion" id="dadosExtra">
                                        <div class="card">
                                            <div class="card-header" id="headingOne">
                                                <h2 class="mb-0">
                                                    <button class="btn btn-link collapsed" type="button"
                                                            data-toggle="collapse" data-target="#objectivos">Objectivos
                                                    </button>
                                                </h2>
                                            </div>
                                            <div id="objectivos" class="collapse show">
                                                <div class="card-body">
                                                    <div class="form-group row">
                                                        <div class="col-3"></div>
                                                        <div class="col-9">
                                                            <div>
                                                                <label class="px-3"><input type="checkbox" name="tone"
                                                                                           id="tone"
                                                                                           data-form-section="objectives">Tonificação</label>
                                                                <label class="px-3"><input type="checkbox"
                                                                                           name="lifestyle_health"
                                                                                           id="lifestyle_health"
                                                                                           data-form-section="objectives">Saúde/Qualidade
                                                                    de Vida</label>
                                                                <label class="px-3"><input type="checkbox"
                                                                                           name="muscle_mass"
                                                                                           id="muscle_mass"
                                                                                           data-form-section="objectives">Massa
                                                                    Muscular</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-3 text-right">
                                                            <label>Peso</label>
                                                        </div>
                                                        <div class="col-9">
                                                            <div class="form-group row pt-0">
                                                                <div class="col-6">
                                                                    <select class="form-control" name="weight_target"
                                                                            id="weight_target"
                                                                            data-form-section="objectives">
                                                                        <option value="loose">Perder</option>
                                                                        <option value="keep">Manter</option>
                                                                        <option value="gain">Ganhar</option>
                                                                    </select>
                                                                </div>
                                                                <div class="col-6 px-0">
                                                                    <label><input type="text" placeholder="Kg"
                                                                                  class="form-control"
                                                                                  name="weight_target_kg"
                                                                                  id="weight_target_kg"
                                                                                  data-form-section="objectives"></label>
                                                                </div>
                                                            </div>

                                                            <div class="form-group row">
                                                                <div class="col-3 d-flex align-items-center">
                                                                    <label>Peso Máximo</label>
                                                                </div>
                                                                <div class="col-9">
                                                                    <div class="form-row">
                                                                        <div class="col-3">
                                                                            <input type="text" placeholder="Kg"
                                                                                   class="form-control" name="weight_max"
                                                                                   id="weight_max"
                                                                                   data-form-section="objectives">
                                                                        </div>
                                                                        <div class="col-9 form-inline">
                                                                            <label>Quando&nbsp;&nbsp;<input type="date"
                                                                                                            class="form-control"
                                                                                                            name="weight_max_date"
                                                                                                            id="weight_max_date"
                                                                                                            data-form-section="objectives"></label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group row">
                                                                <div class="col-3 d-flex align-items-center">
                                                                    <label>Peso Mínimo</label>
                                                                </div>
                                                                <div class="col-9">
                                                                    <div class="form-row">
                                                                        <div class="col-3">
                                                                            <input type="text" placeholder="Kg"
                                                                                   class="form-control" name="weight_min"
                                                                                   id="weight_min"
                                                                                   data-form-section="objectives">
                                                                        </div>
                                                                        <div class="col-9 form-inline">
                                                                            <label>Quando&nbsp;&nbsp;<input type="date"
                                                                                                            class="form-control"
                                                                                                            name="weight_min_date"
                                                                                                            id="weight_min_date"
                                                                                                            data-form-section="objectives"></label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="form-group row">
                                                                <div class="col-3 d-flex align-items-center">
                                                                    <label>Peso Desejado</label>
                                                                </div>
                                                                <div class="col-9">
                                                                    <div class="form-row">
                                                                        <div class="col-3">
                                                                            <input type="text" placeholder="Kg"
                                                                                   class="form-control"
                                                                                   name="weight_desired" id="weight_desired"
                                                                                   data-form-section="objectives">
                                                                        </div>
                                                                        <div class="col-9 form-inline">
                                                                            <label>Quando&nbsp;&nbsp;<input type="date"
                                                                                                            class="form-control"
                                                                                                            name="weight_desired_date"
                                                                                                            id="weight_desired_date"
                                                                                                            data-form-section="objectives"></label>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <textarea type="text" placeholder="Historia de peso"
                                                                      class="form-control" name="weight_history"
                                                                      id="weight_history"
                                                                      data-form-section="objectives"></textarea>

                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-3 text-right"><label>Frequência de treino</label>
                                                        </div>
                                                        <div class="col-9">
                                                            <div class="row no-gutters form-inline">
                                                                <div class="col-6">
                                                                    <input type="text" placeholder="vezes"
                                                                           class="form-control" name="exercise_freq"
                                                                           id="excercise_freq"
                                                                           data-form-section="objectives"> /semana
                                                                </div>
                                                                <div class="col-6">Horario
                                                                    <input type="time" class="form-control"
                                                                           placeholder="HH:MM" name="exercise_time"
                                                                           id="exercise_time"
                                                                           data-form-section="objectives">
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                </div>
                                            </div>
                                        </div>

                                    </div>




                                    <div class="card">
                                        <div class="card-header">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                                                        data-target="#Motivaca">Motivaca
                                                </button>
                                            </h2>
                                        </div>
                                        <div id="Motivaca" class="collapse show">
                                            <div class="card-body">
                                            <textarea class="form-control" placeholder="Motivaca" name="Motivaca"
                                                      id="motivation" rows="10" required></textarea>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="card">
                                        <div class="card-header">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                                                        data-target="#espacoTemporal">Espaco Temporal
                                                </button>
                                            </h2>
                                        </div>
                                        <div id="espacoTemporal" class="collapse show">
                                            <div class="card-body">
                                            <textarea class="form-control" placeholder="espacoTemporal" name="espacoTemporal"
                                                      id="espacoTemporal" rows="10" required></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                                                        data-target="#alteracaoAoTreino">alteracao Ao Treino
                                                </button>
                                            </h2>
                                        </div>
                                        <div id="alteracaoAoTreino" class="collapse show">
                                            <div class="card-body">
                                            <textarea class="form-control" placeholder="alteracaoAoTreino" name="alteracaoAoTreino"
                                                      id="alteracaoAoTreino" rows="10" required></textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="card">
                                        <div class="card-header">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                                                        data-target="#adaptacoes">adaptacoes adquiridas
                                                </button>
                                            </h2>
                                        </div>
                                        <div id="adaptacoes" class="collapse show">
                                            <div class="card-body">
                                            <textarea class="form-control" placeholder="adaptacoes" name="adaptacoes"
                                                      id="adaptacoes" rows="10" required></textarea>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="card" id ="mainTrainingPlan">
                                        <div class="card-header">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                                                        data-target="#PlanTrainer">Plan de Treino
                                                </button>
                                            </h2>
                                        </div>
                                        <div id="PlanTrainer" class="collapse show">
                                            <div class="card-body">
                                                <table id="appointmentWholeTable" class="display table table-hover table-striped"
                                                       style="width:100%">
                                                    <thead>
                                                    <tr>
                                                        <th>Ordem</th>
                                                        <th>Exercicio</th>
                                                        <th>intensidade</th>
                                                        <th>Series (cm)</th>
                                                        <th>Repeticoes</th>
                                                        <th>Descanso (Min)</th>
                                                    </tr>
                                                    </thead>

                                                    <tbody id="appointmentTableBody1">
                                                    <tr id="cloneform" >
                                                        <td><input type="text" id = 'Ordem' name="Ordem[]" class="border border-light"/></td>
                                                        <td><input type="text" id ='Exercicio' name="Exercicio[]" class="border border-light"/></td>
                                                        <td><input type="text" id="intensidade" name="intensidade[]" class="border border-light"/></td>
                                                        <td><input type="text" id="Series" name="Series[]" class="border border-light"/></td>
                                                        <td><input type="text" id="Repeticoes" name="Repeticoes[]" class="border border-light"/></td>
                                                        <td><input type="text" id="Descanso" name="Descanso[]" class="border border-light"/></td>
                                                    </tr>

                                                    </tbody>
                                                </table>
                                                <span id="addbutton" style="cursor: pointer" class="btn btn-primary btn-sm float-right" onclick="add()">ADD ROW</span>
                                            </div>
                                        </div>
                                    </div>





                                    <div class="card">
                                        <div class="card-header">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                                                        data-target="#uploadpdf">Upload PDF
                                                </button>
                                            </h2>
                                        </div>
                                        <div id="uploadpdf" class="collapse show">
                                            <div class="card-body">

                                                <input type="file" class="form-control-file" accept="application/pdf"  required name="attachment" id="attachment" data-parsley-pattern="([a-zA-Z0-9\s_\\.\-:])+(.pdf)$">
                                                <div id="fileText" class="text-secondary"></div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-group row text-right" id="submitButton">
                                        <div class="col-11">
                                            <button type="submit" class="btn btn-space btn-primary">Gravar</button>
                                        </div>
                                    </div>



                                </form>

                        </div>
                    </div>
                </div>
            </div>

        @endif



        <div class="modal"  id="onGoingModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal_smallmodalLabel">Editar Consulta</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" style=" overflow-y: auto;">
                        <div class="row">
                            <div class="col-12">
                                <div id="modalFormBody">

                                </div>

                                {{--                            <div class="form-group row text-right">--}}
                                {{--                                <div class="col col-sm-2 col-lg-3 ">--}}
                                {{--                                    <button class="btn btn-space btn-danger" onclick="deleteOnlineAppointment()">Eliminar</button>--}}
                                {{--                                </div>--}}

                                {{--                                <div class="col col-sm-10 col-lg-8 offset-sm-1 offset-lg-0">--}}
                                {{--                                    <button type="submit"  class="btn btn-space btn-success">Actualizar</button>--}}

                                {{--                                </div>--}}
                                {{--                            </div>--}}

                            </div>
                        </div>

                    </div><!--Modal Body-->
                </div>
            </div>

        </div>

    </div>


@endsection

@section('script')
    <script src="{!! asset('js/trainerOnGoingPlan.js') !!}"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/series-label.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>


    <script>

        var i=0;
        function add(){
            i++;
            if(i<=20) {
                var newel = $("#cloneform:last").clone();
                // Add after last <div class='input-form'>
                $(newel).insertAfter("#cloneform:last");
            }
        }



        $(document).ready(function () {
            $.fn.dataTable.moment('DD/MM/YYY');
            $('#onGoingPlanWholeTable').DataTable({
                //orderCellsTop: true,
                fixedHeader: true,
                autoFill: true,
            });
        });
    </script>


@endsection

