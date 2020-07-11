@extends('layouts.app')

@section('content')

    <div class="container-fluid  dashboard-content">

        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="page-header">
                    <h2 class="pageheader-title">Gerir Consultas Online</h2>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <div class="card-header d-flex">
                        <h4 class="card-header-title">Listagem de Consultas Online</h4>

                    </div>
                    <div class="card-body">
                        @include('layouts.message')
                        @if(Auth::user()->hasRole('Admin'))
                            <div class="form-group row" id="patientNutritionistAppDropdownRow">
                                <label class="col-12 col-sm-3 col-form-label text-sm-right">Selecionar
                                    Nutricionista</label>
                                <div class="col-12 col-sm-8 col-lg-6">

                                    <select class="form-control" name="nutritionist" id="nutritionistSelect"
                                            onchange="">
                                        <option value=""> Selecionar Nutricionista</option>
                                        @foreach ($nutritionists as $key=>$nutritionist)
                                            @php
                                                $selected = ((isset($_GET['nid'])) && $_GET['nid'] == $nutritionist->id )? "selected" : "";
                                            @endphp
                                            <option value="{{ $nutritionist->id }}" {{ $selected }}>{{ $nutritionist->name  }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <script>
                                    $("#nutritionistSelect").on('change', function (event) {
                                        let url = new URL(window.location.href);
                                        url.searchParams.set("nid", event.target.value)
                                        window.location.href = url.href
                                    })
                                </script>

                            </div>
                        @endif



                        @if(Auth::user()->hasRole('Nutritionist') || (count($patients)) )
                            <div class="form-group row">
                                <label class="col-12 col-sm-3 col-form-label text-sm-right">Selecionar
                                    Cliente</label>
                                <div class="col-12 col-sm-8 col-lg-6">
                                    <select class="form-control" name="patient" id="patientSelect">
                                        <option value=""> Selecionar Cliente</option>
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
                                <th>Obs</th>
                            </tr>
                            </thead>

                            <tbody id="appointmentTableBody">

                            @foreach($onappointment as $appointment)
                                @php

                                    $weight = (isset(json_decode($appointment->antropometrics)->body_weight)) ? json_decode($appointment->antropometrics)->body_weight : "";
                                    $chest = (isset(json_decode($appointment->antropometrics)->body_chest)) ? json_decode($appointment->antropometrics)->body_chest : "";
                                    $waist = (isset(json_decode($appointment->antropometrics)->body_waist)) ? json_decode($appointment->antropometrics)->body_waist : "";
                                    $waistHip = (isset(json_decode($appointment->antropometrics)->body_waist_hip)) ? json_decode($appointment->antropometrics)->body_waist_hip : "";
                                    $hip = (isset(json_decode($appointment->antropometrics)->body_hip)) ? json_decode($appointment->antropometrics)->body_hip : "";

                                  $observations = ($appointment->observations != "") ? "<i class=\"fas fa-file\"></i>" : "";
                                             $download = $appointment->attachments ? "Download":"";
                                @endphp

                                <tr  data-appointment-id='{{ $appointment->id }}'
                                     onclick='editOnlineAppointment({{ $appointment->id }})' style='cursor: pointer'>
                                    <td>{{ $appointment->date }}</td>
                                    <td>{{ $weight }}</td>
                                    <td>{{ $chest }}</td>
                                    <td>{{ $waist }}</td>
                                    <td>{{ $waistHip }}</td>
                                    <td>{{ $hip }}</td>
{{--                                    <td><a href = 'download-online/{{$appointment->id}}'>{!! $download !!}</a></td>--}}
                                    <td><a href = '{{ route('downloadOnline',$appointment->attachments) }}' download="{{$appointment->attachments}}">{!! $download !!}</a></td>

                                    <td>
                                        <span onclick="download('{{$appointment->observations}}','text.txt')">{!!  $observations !!}</span>
                                    </td>
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
                                <th>Obs</th>
                            </tr>
                            </tfoot>
                        </table>

                        @endif


                    </div>
                </div>
            </div>
        </div>

        @if ((isset($_GET) && isset($_GET['uid']) && isset($_GET['nid'])) || (isset($_GET) && isset($_GET['uid']) && (Auth::user()->hasRole('Nutritionist')) ) )
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="card">
                        <div class="card-header d-flex">
                            <h5 class="card-header-title">Adicionar Consulta</h5>
                        </div>

                        <div class="card-body">
                            @php
                            $getId = $queries !=""?$queries->id:"";
                            @endphp
                            <button class="btn btn-primary btn-sm float-right" id="loadPreviousAppointmentBtn" onclick="loadPreviousAppointment(<?=$getId ?>)">Carregar consulta anterior</button>

                        <?php
                            if (Auth::user()->hasRole('Admin')) {
                                $qs = "?" . http_build_query(["uid" => trim($_GET['uid']), "nid" => trim($_GET['nid'])]);
                            } else {
                                $qs = "?" . http_build_query(["uid" => trim($_GET['uid'])]);
                            }

                            ?>
                            <form action="{{ route('add_online_appointment') }}" method="post" enctype="multipart/form-data"
                                  data-parsley-validate name="addAppointmentForm" id="addOnlineAppointmentForm">
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
                                               type="date" id="appDate"/>
                                    </div>
                                </div>

                                <!-- online -->
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
                                    <div class="card">
                                        <div class="card-header" id="headingTwo">
                                            <h2 class="mb-0">
                                                <button class="btn btn-link collapsed" type="button"
                                                        data-toggle="collapse" data-target="#historicoClinico">Historico
                                                    clinico
                                                </button>
                                            </h2>
                                        </div>
                                        <div id="historicoClinico" class="collapse show">
                                            <div class="card-body">
                                                <!--clinic history start-->
                                                <div class="form-group row">
                                                    <div class="col-3 text-right">
                                                        <label class="form-check-label" for="glicemia">Glicemia</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="text" name="glicemia_detail" id="glicemia_detail"
                                                               placeholder="Detalhe" class="form-control"
                                                               data-form-section="clinic_history">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-3 text-right">
                                                        <label class="form-check-label"
                                                               for="colesterol">Colesterol</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="text" name="colesterol_detail"
                                                               id="colesterol_detail" placeholder="Detalhe"
                                                               class="form-control" data-form-section="clinic_history">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-3 text-right">
                                                        <label class="form-check-label"
                                                               for="trigli">Triglicerídeos</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="text" name="trigli_detail" id="trigli_detail"
                                                               placeholder="Detalhe" class="form-control"
                                                               data-form-section="clinic_history">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-3 text-right">
                                                        <label class="form-check-label" for="uric_acid">Ácido
                                                            Úrico</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="text" name="uric_acid_detail" id="uric_acid_detail"
                                                               placeholder="Detalhe" class="form-control"
                                                               data-form-section="clinic_history">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-3 text-right">
                                                        <label class="form-check-label" for="f_intestinal">Função
                                                            Intestinal/Digestiva</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="text" name="f_intestinal_detail"
                                                               id="f_intestinal_detail" placeholder="Detalhe"
                                                               class="form-control" data-form-section="clinic_history">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-3 text-right">
                                                        <label class="form-check-label" for="f_alergies">Alergias/Intolerâncias
                                                            Alimentares</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="text" name="f_alergies_detail"
                                                               id="f_alergies_detail" placeholder="Detalhe"
                                                               class="form-control" data-form-section="clinic_history">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-3 text-right">
                                                        <label class="form-check-label" for="history_personal">Antecedentes
                                                            Pessoais</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="text" name="history_personal_detail"
                                                               id="history_personal_detail" placeholder="Detalhe"
                                                               class="form-control" data-form-section="clinic_history">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-3 text-right">
                                                        <label class="form-check-label" for="history_family">Antecedentes
                                                            Familiares</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="text" name="history_family_detail"
                                                               id="history_family_detail" class="form-control"
                                                               placeholder="Detalhe" data-form-section="clinic_history">
                                                    </div>
                                                </div>
                                                <div class="form-group row">
                                                    <div class="col-3 text-right">
                                                        <label class="form-check-label"
                                                               for="medication">Medicação</label>
                                                    </div>
                                                    <div class="col-9">
                                                        <input type="text" placeholder="Qual?" name="medication_detail"
                                                               id="medication_detail" class="form-control"
                                                               data-form-section="clinic_history">
                                                    </div>
                                                </div>
                                                <!--clinic history end-->
                                            </div>

                                        </div>
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
{{--                                                            food diary--}}
                                <div class="card">
                                    <div class="card-header">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                                                    data-target="#diarioAlimentar">Diário Alimentar
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="diarioAlimentar" class="collapse show">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="form-group row">
                                                        <label class="col-12 col-sm-3 col-form-label text-sm-right">Wakeup time</label>
                                                        <div class="col-12 col-sm-8 col-lg-6">
                                                            <input name="Wakeup_time" required class="form-control"
                                                                   type="time" id="Wakeup_time"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="form-group row">
                                                        <label class="col-12 col-sm-3 col-form-label text-sm-right">Bed time</label>
                                                        <div class="col-12 col-sm-8 col-lg-6">
                                                            <input name="bed_time" required class="form-control"
                                                                   type="time" id="bed_time"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-12">
                                                    <p class="border-bottom">Breakfast:</p>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-4">
                                                    <div class="form-group row">
                                                        <label class="col-12 col-sm-3 col-form-label text-sm-right">Time</label>
                                                        <div class="col-12 col-sm-8 col-lg-6">
                                                            <input name="breakfast_time" required class="form-control"
                                                                   type="time" id="breakfast_time"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-group row">
                                                        <label class="col-12 col-sm-3 col-form-label text-sm-right">Description </label>
                                                        <div class="col-12 col-sm-8 col-lg-6">
                                                            <textarea class="form-control" placeholder="Observações" name="breakfast_Description"
                                                                      id="breakfast_Description" rows="2"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-group row">
                                                        <label class="col-12 col-sm-3 col-form-label text-sm-right">Place</label>
                                                        <div class="col-12 col-sm-8 col-lg-6">
                                                            <input name="breakfast_place" required class="form-control"
                                                                   type="text" id="breakfast_place"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="row">
                                                <div class="col-12">
                                                    <p class="border-bottom">Morning Snack 1:</p>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-4">
                                                    <div class="form-group row">
                                                        <label class="col-12 col-sm-3 col-form-label text-sm-right">Time</label>
                                                        <div class="col-12 col-sm-8 col-lg-6">
                                                            <input name="MorningS1_time" required class="form-control"
                                                                   type="time" id="MorningS1_time"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-group row">
                                                        <label class="col-12 col-sm-3 col-form-label text-sm-right">Description </label>
                                                        <div class="col-12 col-sm-8 col-lg-6">
                                                            <textarea class="form-control" placeholder="Observações" name="MorningS1_Description"
                                                                      id="MorningS1_Description" rows="2"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-group row">
                                                        <label class="col-12 col-sm-3 col-form-label text-sm-right">Place</label>
                                                        <div class="col-12 col-sm-8 col-lg-6">
                                                            <input name="MorningS1_place" required class="form-control"
                                                                   type="text" id="MorningS1_place"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>



                                            <div class="row">
                                                <div class="col-12">
                                                    <p class="border-bottom">Morning Snack 2:</p>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-4">
                                                    <div class="form-group row">
                                                        <label class="col-12 col-sm-3 col-form-label text-sm-right">Time</label>
                                                        <div class="col-12 col-sm-8 col-lg-6">
                                                            <input name="MorningS2_time" required class="form-control"
                                                                   type="time" id="MorningS2_time"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-group row">
                                                        <label class="col-12 col-sm-3 col-form-label text-sm-right">Description </label>
                                                        <div class="col-12 col-sm-8 col-lg-6">
                                                            <textarea class="form-control" placeholder="Observações" name="MorningS2_Description"
                                                                      id="MorningS2_Description" rows="2"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-group row">
                                                        <label class="col-12 col-sm-3 col-form-label text-sm-right">Place</label>
                                                        <div class="col-12 col-sm-8 col-lg-6">
                                                            <input name="MorningS2_place" required class="form-control"
                                                                   type="text" id="MorningS2_place"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>







                                            <div class="row">
                                                <div class="col-12">
                                                    <p class="border-bottom">Lunch:</p>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-4">
                                                    <div class="form-group row">
                                                        <label class="col-12 col-sm-3 col-form-label text-sm-right">Time</label>
                                                        <div class="col-12 col-sm-8 col-lg-6">
                                                            <input name="lunch_time" required class="form-control"
                                                                   type="time" id="lunch_time"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-group row">
                                                        <label class="col-12 col-sm-3 col-form-label text-sm-right">Description </label>
                                                        <div class="col-12 col-sm-8 col-lg-6">
                                                            <textarea class="form-control" placeholder="Observações" name="lunch_Description"
                                                                      id="lunch_Description" rows="2"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-group row">
                                                        <label class="col-12 col-sm-3 col-form-label text-sm-right">Place</label>
                                                        <div class="col-12 col-sm-8 col-lg-6">
                                                            <input name="lunch_place" required class="form-control"
                                                                   type="text" id="lunch_place"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="row">
                                                <div class="col-12">
                                                    <p class="border-bottom">Afternoon Snack 1:</p>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-4">
                                                    <div class="form-group row">
                                                        <label class="col-12 col-sm-3 col-form-label text-sm-right">Time</label>
                                                        <div class="col-12 col-sm-8 col-lg-6">
                                                            <input name="AfternoonS1_time" required class="form-control"
                                                                   type="time" id="AfternoonS1_time"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-group row">
                                                        <label class="col-12 col-sm-3 col-form-label text-sm-right">Description </label>
                                                        <div class="col-12 col-sm-8 col-lg-6">
                                                            <textarea class="form-control" placeholder="Observações" name="AfternoonS1_Description"
                                                                      id="AfternoonS1_Description" rows="2"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-group row">
                                                        <label class="col-12 col-sm-3 col-form-label text-sm-right">Place</label>
                                                        <div class="col-12 col-sm-8 col-lg-6">
                                                            <input name="AfternoonS1_place" required class="form-control"
                                                                   type="text" id="AfternoonS1_place"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>



                                            <div class="row">
                                                <div class="col-12">
                                                    <p class="border-bottom">Afternoon Snack 2:</p>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-4">
                                                    <div class="form-group row">
                                                        <label class="col-12 col-sm-3 col-form-label text-sm-right">Time</label>
                                                        <div class="col-12 col-sm-8 col-lg-6">
                                                            <input name="AfternoonS2_time" required class="form-control"
                                                                   type="time" id="AfternoonS2_time"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-group row">
                                                        <label class="col-12 col-sm-3 col-form-label text-sm-right">Description </label>
                                                        <div class="col-12 col-sm-8 col-lg-6">
                                                            <textarea class="form-control" placeholder="Observações" name="AfternoonS2_Description"
                                                                      id="AfternoonS2_Description" rows="2"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-group row">
                                                        <label class="col-12 col-sm-3 col-form-label text-sm-right">Place</label>
                                                        <div class="col-12 col-sm-8 col-lg-6">
                                                            <input name="AfternoonS2_place" required class="form-control"
                                                                   type="text" id="AfternoonS2_place"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="row">
                                                <div class="col-12">
                                                    <p class="border-bottom">Dinner:</p>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-4">
                                                    <div class="form-group row">
                                                        <label class="col-12 col-sm-3 col-form-label text-sm-right">Time</label>
                                                        <div class="col-12 col-sm-8 col-lg-6">
                                                            <input name="dinner_time" required class="form-control"
                                                                   type="time" id="dinner_time"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-group row">
                                                        <label class="col-12 col-sm-3 col-form-label text-sm-right">Description </label>
                                                        <div class="col-12 col-sm-8 col-lg-6">
                                                            <textarea class="form-control" placeholder="Observações" name="dinner_Description"
                                                                      id="dinner_Description" rows="2"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-group row">
                                                        <label class="col-12 col-sm-3 col-form-label text-sm-right">Place</label>
                                                        <div class="col-12 col-sm-8 col-lg-6">
                                                            <input name="dinner_place" required class="form-control"
                                                                   type="text" id="dinner_place"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="row">
                                                <div class="col-12">
                                                    <p class="border-bottom">Supper:</p>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-4">
                                                    <div class="form-group row">
                                                        <label class="col-12 col-sm-3 col-form-label text-sm-right">Time</label>
                                                        <div class="col-12 col-sm-8 col-lg-6">
                                                            <input name="supper_time" required class="form-control"
                                                                   type="time" id="supper_time"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-group row">
                                                        <label class="col-12 col-sm-3 col-form-label text-sm-right">Description </label>
                                                        <div class="col-12 col-sm-8 col-lg-6">
                                                            <textarea class="form-control" placeholder="Observações" name="supper_Description"
                                                                      id="supper_Description" rows="2"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-4">
                                                    <div class="form-group row">
                                                        <label class="col-12 col-sm-3 col-form-label text-sm-right">Place</label>
                                                        <div class="col-12 col-sm-8 col-lg-6">
                                                            <input name="supper_place" required class="form-control"
                                                                   type="text" id="supper_place"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>




                                            <div class="row">
                                                <div class="col-12">
                                                    <p class="border-bottom">Weekend:</p>
                                                </div>
                                            </div>

                                            <div class="row">

                                                <div class="col-6">
                                                    <div class="form-group row">
                                                        <label class="col-12 col-sm-3 col-form-label text-sm-right">Description </label>
                                                        <div class="col-12 col-sm-8 col-lg-6">
                                                            <textarea class="form-control" placeholder="Observações" name="weekend_Description"
                                                                      id="weekend_Description" rows="2"></textarea>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>




                                        </div>
                                    </div>
                                </div>
{{--                                                            food diary end--------------------------}}

                                <div class="card">
                                    <div class="card-header">
                                        <h2 class="mb-0">
                                            <button class="btn btn-link collapsed" type="button" data-toggle="collapse"
                                                    data-target="#observacoes">Observações
                                            </button>
                                        </h2>
                                    </div>
                                    <div id="observacoes" class="collapse show">
                                        <div class="card-body">
                                            <textarea class="form-control" placeholder="Observações" name="observations"
                                                      id="observations" rows="10" required></textarea>
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

                                            <input type="file" class="form-control-file" accept="application/pdf"  required name="attachments" id="attachments" data-parsley-pattern="([a-zA-Z0-9\s_\\.\-:])+(.pdf)$">
                                                <div id="fileText" class="text-secondary"></div>
                                        </div>
                                    </div>
                                </div>


                        </div>
                        <!-- /online -->

                        <div class="form-group row text-right">
                            <div class="col-11">
                                <button type="submit" class="btn btn-space btn-primary">Gravar</button>
                            </div>
                        </div>
                        </form>
                    </div>
                </div>
            </div>



            <div id="appointmentChart">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="card border-4 border-top border-top-primary" style="padding: 10px  ">
                            <div id="chartHeight"></div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="card border-4 border-top border-top-primary" style="padding: 10px  ">
                            <div id="chartWeight"></div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="card border-4 border-top border-top-primary" style="padding: 10px  ">
                            <div id="chartImc"></div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="card border-4 border-top border-top-primary" style="padding: 10px  ">
                            <div id="chartFatmass"></div>
                        </div>
                    </div>
                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="card border-4 border-top border-top-primary" style="padding: 10px  ">
                            <div id="chartmm"></div>
                        </div>
                    </div>

                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="card border-4 border-top border-top-primary" style="padding: 10px  ">
                            <div id="chartfm"></div>
                        </div>
                    </div>

                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="card border-4 border-top border-top-primary" style="padding: 10px  ">
                            <div id="charttw"></div>
                        </div>
                    </div>


                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <div class="card border-4 border-top border-top-primary" style="padding: 10px  ">
                            <div id="chartPa"></div>
                        </div>
                    </div>

                </div>
            </div>







        @endif


    </div>


    <div class="modal" id="onlineAppointmentModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal_smallmodalLabel">Editar Consulta</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
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


@endsection

@section('script')




        <script type="application/javascript">
            function download(text, filename){
                var blob = new Blob([text], {type: "text/plain"});
                var url = window.URL.createObjectURL(blob);
                var a = document.createElement("a");
                a.href = url;
                a.download = filename;
                a.click();
            }


        </script>
    <script src="{!! asset('js/onlineappointment.js') !!}"></script>
        <script src="{!! asset('js/inputNumberValid.js') !!}"></script>
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/modules/series-label.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <script src="https://code.highcharts.com/modules/export-data.js"></script>
        {{--        {"body_height":"161","body_weight":"62","body_bmi":"","body_fat_mass":"","body_chest":"","body_waist":"","body_waist_hip":"","body_hip":""}--}}

        @php


            $json = [];
            $array =[];
         foreach($onappointment as $antropometrics){

               array_push( $array ,json_decode($antropometrics->antropometrics,true));
            }

         $json = json_encode($array);


        @endphp


        <script>
            // parse json as it was before
            window.chartData =  <?= $json ?>;

            var body_height = [];
            var date = [];
            var average = [];
            var body_weight = [];
            var body_bmi = [];
            var body_fat_mass = [];
            var body_chest = [];
            var body_waist = [];
            var body_waist_hip = [];
            var body_hip = [];
            var sum = 0;
            for (var i = 0; i < window.chartData.length; i++) {
                body_height.push(Number(window.chartData[i].body_height));
                body_weight.push(Number(window.chartData[i].body_weight));
                body_bmi.push(Number(window.chartData[i].body_bmi));
                body_fat_mass.push(Number(window.chartData[i].body_fat_mass));
                body_chest.push(Number(window.chartData[i].body_chest));
                body_waist.push(Number(window.chartData[i].body_waist));
                body_waist_hip.push(Number(window.chartData[i].body_waist_hip));
                body_hip.push(Number(window.chartData[i].body_hip));
                date.push(window.chartData[i].date);
            }

            function highchart(id, title, yText, xText, yName1, yData1, yName2, yData2, xData) {
                Highcharts.chart(id, {
                    title: {text: title},
                    yAxis: {title: {text: yText}},
                    xAxis: {categories: xData, title: {text: xText}},
                    credits: {enabled: false},
                    series: [
                        {name: yName1, data: yData1, color: '#1f8677'}
                    ],
                    responsive: {
                        rules: [{condition: {maxWidth: 500},}]
                    }
                });
            }


            //build Charts
            highchart("chartHeight", "Variação do Altura", "Altura (cm)", "Data da consulta", "Altura", body_height, "Média", average, date);
            highchart("chartWeight", "Variação do Peso", "Peso (Kg)", "Data da consulta", "Peso", body_weight, "Média", average, date);
            highchart("chartImc", "Variação da IMC (Kg/m2)", "IMC (Kg/m2)", "Data da Consulta", "IMC (Kg/m2)", body_bmi, "Média", average, date);
            highchart("chartFatmass", "Variação da % Massa Gorda (%)", "% Massa Gorda", "Data da Consulta", "% Massa Gorda", body_fat_mass, "Média", average, date);
            highchart("chartfm", "Variação da PP (cm)", "PP (cm)", "Data da Consulta", "PP (cm)", body_chest, "Média", average, date);
            highchart("chartmm", "Variação da PC1", "PC1", "Data da Consulta", "PC1", body_waist, "Média", average, date);
            highchart("charttw", "Variação do PC2", "PC2", "Data da Consulta", "PC2", body_waist_hip, "Média", average, date);
            highchart("chartPa", "Variação do PA", "PA", "Data da Consulta", "PA", body_hip, "Média", average, date);


            $(document).ready(function () {
                $.fn.dataTable.moment('DD/MM/YYY');
                $('#appointmentWholeTable').DataTable({
                    //orderCellsTop: true,
                    fixedHeader: true,
                    autoFill: true,
                });
            });
        </script>




@endsection
