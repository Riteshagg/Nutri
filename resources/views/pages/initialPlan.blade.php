@extends('layouts.app')

@section('content')

    <div class="container-fluid  dashboard-content">

        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="page-header">
                    <h2 class="pageheader-title">Initial training Plan</h2>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <div class="card-header d-flex">
                        <h4 class="card-header-title">Listagem de Initial training Plan</h4>

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
                            <table id="initialPlanWholeTable" class="display table table-hover table-striped"
                                   style="width:100%">
                                <thead>
                                <tr>
                                    <th>Objective </th>
                                    <th>Exercise </th>
                                    <th>Intensity </th>
                                    <th>Volume</th>
                                    <th>Series </th>
                                    <th>Repetitions</th>
                                    <th>Rest time</th>
                                    <th>Antropemtric data</th>
                                    <th>Goal </th>
                                    <th>Time Frame </th>
                                    <th>Motivation</th>
                                </tr>
                                </thead>

                                <tbody id="appointmentTableBody">

                                @foreach($initialTrainingList as $initialTraining)
                                    @php
                                        $initialPlanData = json_decode($initialTraining->planData,true);
                                    $initialObservations = json_decode($initialTraining->observations,true);

                                    @endphp
                                    <tr data-appointment-id='{{ $initialTraining->id }}'  onclick='editInitialPlan({{ $initialTraining->id }})' style='cursor: pointer' >
                                        <td>{{ $initialPlanData['objective'] }}</td>
                                        <td>{{ $initialPlanData['exercise'] }}</td>
                                        <td>{{ $initialPlanData['intensity'] }}</td>
                                        <td>{{ $initialPlanData['volume']}}</td>
                                        <td>{{ $initialPlanData['series'] }}</td>
                                        <td>{{ $initialPlanData['repetitions'] }}</td>
                                        <td>{{ $initialPlanData['rest_time'] }}</td>
                                        <td>{{ $initialObservations['dntropemtric_data'] }}</td>
                                        <td>{{ $initialObservations['goal'] }}</td>
                                        <td>{{ $initialObservations['time_frame'] }}</td>
                                        <td>{{ $initialObservations['motivation'] }}</td>
                                    </tr>

                                @endforeach

                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>Objective </th>
                                    <th>Exercise </th>
                                    <th>Intensity </th>
                                    <th>Volume</th>
                                    <th>Series </th>
                                    <th>Repetitions</th>
                                    <th>Rest time</th>
                                    <th>Antropemtric data</th>
                                    <th>Goal </th>
                                    <th>Time Frame </th>
                                    <th>Motivation</th>
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
                        <h5 class="card-header">Adicionar Initial Plan</h5>
                        <div class="card-body">
                            @php
                                if (Auth::user()->hasRole('Admin')) {
                                    $qs = "?" . http_build_query(["uid" => trim($_GET['uid']), "nid" => trim($_GET['nid'])]);
                                } else {
                                    $qs = "?" . http_build_query(["uid" => trim($_GET['uid'])]);
                                }
                            @endphp


{{--                            --}}
                            @if(count($initialTrainingList)==0)
                            <form action="{{ route('initial_plan_store') }}{{$qs}}" method="post" enctype="multipart/form-data" data-parsley-validate id="form">
                                @csrf
                             <div id="cloneform" class="shadow-none p-3 mb-5 bg-light rounded">
                            <p class="border-bottom text-capitalize font-weight-bolder" style="color: #0E6655">Plan data:</p>
                                 <div class="row">
                                     <div class="col-3">
                                         <div class="form-group row">
                                             <label class="col-12 col-sm-3 col-form-label text-sm-right">Objective</label>
                                             <div class="col-12 col-sm-8 col-lg-6">
                                                 <input name="objective[]" required placeholder="Objective" class="form-control" dateformat="d M y" type="text"/>
                                             </div>
                                         </div>
                                     </div>
                                     <div class="col-3">
                                         <div class="form-group row">
                                             <label class="col-12 col-sm-3 col-form-label text-sm-right">Exercise</label>
                                             <div class="col-12 col-sm-8 col-lg-6">
                                                 <input type="text" required name="exercise[]" placeholder="Exercise" class="form-control">
                                             </div>
                                         </div>
                                     </div>
                                     <div class="col-3">
                                         <div class="form-group row">
                                             <label class="col-12 col-sm-3 col-form-label text-sm-right">Intensity</label>
                                             <div class="col-12 col-sm-8 col-lg-6">
                                                 <input type="number" required name="intensity[]" placeholder="Intensity" class="form-control">
                                             </div>
                                         </div>
                                     </div>
                                     <div class="col-3">
                                         <div class="form-group row">
                                             <label class="col-12 col-sm-3 col-form-label text-sm-right">Volume</label>
                                             <div class="col-12 col-sm-8 col-lg-6">
                                                 <input type="text" required name="volume[]" placeholder="Volume" class="form-control" >
                                             </div>
                                         </div>
                                     </div>
                                 </div>
                                <div class="row">
                                    <div class="col-3">
                                        <div class="form-group row">
                                            <label class="col-12 col-sm-3 col-form-label text-sm-right">Series </label>
                                            <div class="col-12 col-sm-8 col-lg-6">
                                                <input type="number" required name="series[]" placeholder="Series " class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group row">
                                            <label class="col-12 col-sm-3 col-form-label text-sm-right">Repetitions </label>
                                            <div class="col-12 col-sm-8 col-lg-6">
                                                <input type="number" required name="repetitions[]" placeholder="Repetitions" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-3">
                                        <div class="form-group row">
                                            <label class="col-12 col-sm-3 col-form-label text-sm-right">Rest time</label>
                                            <div class="col-12 col-sm-8 col-lg-6">
                                                <input type="number" required name="rest_time[]" placeholder="Rest time" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                             </div>
                                <p class="border-bottom text-capitalize font-weight-bolder" style="color: #0E6655">Observations:</p>

                                 <div class="row">
                                     <div class="col-4">
                                         <div class="form-group row">
                                             <label class="col-12 col-sm-3 col-form-label text-sm-right">Antropemtric data</label>
                                             <div class="col-12 col-sm-8 col-lg-6">
                                                 <input type="text" class="form-control" placeholder="Antropemtric data" required name="dntropemtric_data">

                                             </div>
                                         </div>
                                     </div>
                                     <div class="col-4">
                                         <div class="form-group row">
                                             <label class="col-12 col-sm-2 col-form-label text-sm-right">Goal </label>
                                             <div class="col-12 col-sm-8 col-lg-6">
                                                 <input type="text" required name="goal" placeholder="Goal" class="form-control">
                                             </div>
                                         </div>
                                     </div>
                                     <div class="col-4">
                                         <div class="form-group row">
                                             <label class="col-12 col-sm-2 col-form-label text-sm-right">Time frame</label>
                                             <div class="col-12 col-sm-8 col-lg-6">
                                                 <input type="text" required name="time_frame" placeholder="Time frame" class="form-control">
                                             </div>
                                         </div>
                                     </div>
                                     <div class="col-3">
                                         <div class="form-group row">
                                             <label class="col-12 col-sm-3 col-form-label text-sm-right">Motivation </label>
                                             <div class="col-12 col-sm-8 col-lg-6">
                                                 <input type="text" required name="motivation" placeholder="Motivation" class="form-control">
                                             </div>
                                         </div>
                                     </div>
                                 </div>




                                <div class="row">
                                    <div class="col-6">
                                        <div class="form-group row text-left">
                                            <div class="col col-sm-6 col-lg-9 offset-sm-1 offset-lg-0">
                                                <button type="button" class="btn btn-outline-primary" onclick="add()">Add form</button>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="form-group row text-right">
                                            <div class="col col-sm-6 col-lg-9 offset-sm-1 offset-lg-0">
                                                <button type="submit" class="btn btn-space btn-primary">Gravar</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>



                            </form>
                                @endif
                        </div>
                    </div>
                </div>
            </div>

        @endif

    </div>



    <div class="modal" id="initialModal" tabindex="-1" role="dialog" aria-labelledby="smallmodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="smallmodalLabel">Editar initial training</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <form id = 'update-ajex' action="{{ route('update_initial_plan') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <p class="border-bottom text-capitalize font-weight-bolder" style="color: #0E6655">Plan data:</p>

                                <div class="form-group row">

                                    <div class="col-12 col-sm-8 col-lg-6">
                                        <input name="id" id = "id" required placeholder="Objective" class="form-control" dateformat="d M y" type="hidden"/>
                                    </div>
                                </div>

                                        <div class="form-group row">
                                            <label class="col-12 col-sm-3 col-form-label text-sm-right">Objective</label>
                                            <div class="col-12 col-sm-8 col-lg-6">
                                                <input name="objective" id="objective" required placeholder="Objective" class="form-control" dateformat="d M y" type="text"/>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-12 col-sm-3 col-form-label text-sm-right">Exercise</label>
                                            <div class="col-12 col-sm-8 col-lg-6">
                                                <input type="text" required name="exercise" id="exercise" placeholder="Exercise" class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-12 col-sm-3 col-form-label text-sm-right">Intensity</label>
                                            <div class="col-12 col-sm-8 col-lg-6">
                                                <input type="number" required name="intensity" id="intensity" placeholder="Intensity" class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-12 col-sm-3 col-form-label text-sm-right">Volume</label>
                                            <div class="col-12 col-sm-8 col-lg-6">
                                                <input type="text" required name="volume" id="volume" placeholder="Volume" class="form-control" >
                                            </div>
                                        </div>




                                        <div class="form-group row">
                                            <label class="col-12 col-sm-3 col-form-label text-sm-right">Series </label>
                                            <div class="col-12 col-sm-8 col-lg-6">
                                                <input type="number" required name="series" id="series" placeholder="Series " class="form-control">
                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <label class="col-12 col-sm-3 col-form-label text-sm-right">Repetitions </label>
                                            <div class="col-12 col-sm-8 col-lg-6">
                                                <input type="number" required name="repetitions" id="repetitions" placeholder="Repetitions" class="form-control">
                                            </div>
                                        </div>


                                        <div class="form-group row">
                                            <label class="col-12 col-sm-3 col-form-label text-sm-right">Rest time</label>
                                            <div class="col-12 col-sm-8 col-lg-6">
                                                <input type="number" required name="rest_time" id="rest_time" placeholder="Rest time" class="form-control">
                                            </div>
                                        </div>


                                <p class="border-bottom text-capitalize font-weight-bolder" style="color: #0E6655">Observations:</p>

                                        <div class="form-group row">
                                            <label class="col-12 col-sm-3 col-form-label text-sm-right">Antropemtric data</label>
                                            <div class="col-12 col-sm-8 col-lg-6">
                                                <input type="text" class="form-control" id="dntropemtric_data" placeholder="Antropemtric data" required name="dntropemtric_data">

                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-12 col-sm-3 col-form-label text-sm-right">Goal </label>
                                            <div class="col-12 col-sm-8 col-lg-6">
                                                <input type="text" required name="goal" id="goal" placeholder="Goal" class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-12 col-sm-3 col-form-label text-sm-right">Time frame</label>
                                            <div class="col-12 col-sm-8 col-lg-6">
                                                <input type="text" required name="time_frame" id="time_frame" placeholder="Time frame" class="form-control">
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label class="col-12 col-sm-3 col-form-label text-sm-right">Motivation </label>
                                            <div class="col-12 col-sm-8 col-lg-6">
                                                <input type="text" required name="motivation" id="motivation" placeholder="Motivation" class="form-control">
                                            </div>
                                        </div>




                                <div class="form-group row text-right">
                                    <div class="col col-sm-2 col-lg-3 ">
                                        <button class="btn btn-space btn-danger" onclick="deleteInitialPlan()">Eliminar</button>
                                    </div>

                                    <div class="col col-sm-10 col-lg-8 offset-sm-1 offset-lg-0">
                                        <button type="submit" class="btn btn-space btn-success">Actualizar</button>

                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div><!--Modal Body-->
            </div>
        </div>

    </div>




@endsection

@section('script')
    <script src="{!! asset('js/trainerPlan.js') !!}"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/series-label.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>


    <script>

        function add(){
            var newel =   $("#cloneform:last").clone();
            // Add after last <div class='input-form'>
            $(newel).insertAfter("#cloneform:last");
        }



        $(document).ready(function () {
            $.fn.dataTable.moment('DD/MM/YYY');
            $('#initialPlanWholeTable').DataTable({
                //orderCellsTop: true,
                fixedHeader: true,
                autoFill: true,
            });
        });
    </script>


@endsection
