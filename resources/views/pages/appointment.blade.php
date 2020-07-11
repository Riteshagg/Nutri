@extends('layouts.app')

@section('content')

    <div class="container-fluid  dashboard-content">

        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="page-header">
                    <h2 class="pageheader-title">Gerir Consultas</h2>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <div class="card-header d-flex">
                        <h4 class="card-header-title">Listagem de Consultas</h4>

                    </div>
                    <div class="card-body">
                        @include('layouts.message')
                        @if(Auth::user()->hasRole('Admin'))
                            <div class="form-group row" id="patientNutritionistAppDropdownRow">
                                <label class="col-12 col-sm-3 col-form-label text-sm-right">Selecionar
                                    Nutricionista</label>
                                <div class="col-12 col-sm-8 col-lg-6">
                                    @php
                                        $sel = !(isset($_GET) && isset($_GET['nid'])) ? "selected" : "";
                                    @endphp

                                    <select class="form-control" name="nutritionist" id="nutritionistSelect">
                                        <option value="" disabled {{ $sel }}> Selecionar Nutricionista</option>
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
                                        url.searchParams.set("nid", event.target.value);
                                        url.searchParams.delete("id");
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
                                    <?php $sel = !(isset($_GET) && isset($_GET['id'])) ? "selected" : ""; ?>
                                    <select class="form-control" name="patient" id="patientSelect">
                                        <option value="" disabled {{ $sel }} > Selecionar Cliente</option>
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
                                    <th>Massa Muscular (Kg)</th>
                                    <th>Massa Gorda (Kg)</th>
                                    <th>Total Água</th>
                                    <th>Gordura Visceral (g/cm²)</th>
                                    <th>Rácio Cintura/Anca</th>
                                    <th>Download PDF 1</th>
                                    <th>Download PDF 2</th>
                                </tr>
                                </thead>

                                <tbody id="appointmentTableBody">

                                @foreach($appointmentlist as $appointment)
                                    @php $attach  = json_decode($appointment->attachments,true) @endphp
                                    <tr data-appointment-id='{{ $appointment->id }}' onclick='editAppointment({{ $appointment->id }})' style='cursor: pointer' >
                                        <td>{{ $appointment->date }}</td>
                                        <td>{{ $appointment->weight }}</td>
                                        <td>{{ $appointment->muscleMass }}</td>
                                        <td>{{ $appointment->fatMass }}</td>
                                        <td>{{ $appointment->totalWater }}</td>
                                        <td>{{ $appointment->visceralFat }}</td>
                                        <td>{{ $appointment->hipWaistRatio }}</td>
                                 <td><a href = '{{ route('download',$attach['attach1']) }}' download="{{$attach['attach1']}}">{{$appointment->attachments ? 'Download':""}}</a></td>
                                    <td><a href = '{{ route('download',$attach['attach2']) }}' download="{{$attach['attach2']}}">{{$appointment->attachments ? 'Download':""}}</a></td>
                                    </tr>

                                @endforeach

                                </tbody>
                                <tfoot>
                                <tr>
                                    <th>Data</th>
                                    <th>Peso (Kg)</th>
                                    <th>Massa Muscular (Kg)</th>
                                    <th>Massa Gorda (Kg)</th>
                                    <th>Total Água</th>
                                    <th>Gordura Visceral (g/cm²)</th>
                                    <th>Rácio Cintura/Anca</th>
                                    <th>Download PDF 1</th>
                                    <th>Download PDF 2</th>
                                </tr>
                                </tfoot>
                            </table>

                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if ((isset($_GET) && isset($_GET['uid']) && isset($_GET['nid'])) || (isset($_GET) && isset($_GET['uid']) && Auth::user()->hasRole('Nutritionist') ))
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <h5 class="card-header">Adicionar Consulta</h5>
                    <div class="card-body">
                        @php
                            if (Auth::user()->hasRole('Admin')) {
                                $qs = "?" . http_build_query(["uid" => trim($_GET['uid']), "nid" => trim($_GET['nid'])]);
                            } else {
                                $qs = "?" . http_build_query(["uid" => trim($_GET['uid'])]);
                            }
                        @endphp

                        <form action="{{ route('appointments_store') }}{{$qs}}" method="post" enctype="multipart/form-data" data-parsley-validate id="form">
                            @csrf

                            <div class="form-group row">
                                <label class="col-12 col-sm-3 col-form-label text-sm-right">Data da Consulta</label>
                                <div class="col-12 col-sm-8 col-lg-6">
                                    <input name="appDate" required class="form-control" dateformat="d M y" type="date"/>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-12 col-sm-3 col-form-label text-sm-right">Peso (Kg)</label>
                                <div class="col-12 col-sm-8 col-lg-6">
                                    <input type="text" required name="weight" placeholder="Introduza o Peso (Kg)" class="form-control" data-parsley-pattern="[0-9]*([.,]?[0-9]*)">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-12 col-sm-3 col-form-label text-sm-right">Massa Muscular (Kg)</label>
                                <div class="col-12 col-sm-8 col-lg-6">
                                    <input type="text" required name="mm" placeholder="Introduza a Massa Muscular em Kg" class="form-control" data-parsley-pattern="[0-9]*([.,]?[0-9]*)">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-12 col-sm-3 col-form-label text-sm-right">Massa Gorda (Kg)</label>
                                <div class="col-12 col-sm-8 col-lg-6">
                                    <input type="text" required name="fm" placeholder="Introduza a Massa Gorda em Kg" class="form-control" data-parsley-pattern="[0-9]*([.,]?[0-9]*)">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-12 col-sm-3 col-form-label text-sm-right">Total de Água (Kg)</label>
                                <div class="col-12 col-sm-8 col-lg-6">
                                    <input type="text" required name="tw" placeholder="Introduza o Total de Água" class="form-control" data-parsley-pattern="[0-9]*([.,]?[0-9]*)">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-12 col-sm-3 col-form-label text-sm-right">Gordura Visceral (g/cm²)</label>
                                <div class="col-12 col-sm-8 col-lg-6">
                                    <input type="text" required name="vf" placeholder="Introduza a Gordura Visceral" class="form-control" data-parsley-pattern="[0-9]*([.,]?[0-9]*)">
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="col-12 col-sm-3 col-form-label text-sm-right"><abbr title="Em decimal">Rácio Cintura/Anca</abbr></label>
                                <div class="col-12 col-sm-8 col-lg-6">
                                    <input type="text" required name="hw" placeholder="Introduza o Rácio Cintura/Anca" class="form-control" data-parsley-pattern="[0-9]*([.,]?[0-9]*)">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-12 col-sm-3 col-form-label text-sm-right">Upload PDF 1</label>
                                <div class="col-12 col-sm-8 col-lg-6">
                                <input type="file" class="form-control-file" accept="application/pdf"  required name="attachments" id="attachments" data-parsley-pattern="([a-zA-Z0-9\s_\\.\-:])+(.pdf)$">

                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-12 col-sm-3 col-form-label text-sm-right">Upload PDF 2</label>
                                <div class="col-12 col-sm-8 col-lg-6">
                                    <input type="file" class="form-control-file" accept="application/pdf"  required name="attachments1" id="attachments1" data-parsley-pattern="([a-zA-Z0-9\s_\\.\-:])+(.pdf)$">

                                </div>
                            </div>

                            <div class="form-group row text-right">
                                <div class="col col-sm-10 col-lg-9 offset-sm-1 offset-lg-0">
                                    <button type="submit" class="btn btn-space btn-primary">Gravar</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div id="appointmentChart">
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="card border-4 border-top border-top-primary" style="padding: 10px  ">
                        <div id="chartWeight"></div>
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
                        <div id="chartvf"></div>
                    </div>
                </div>

                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                    <div class="card border-4 border-top border-top-primary" style="padding: 10px  ">
                        <div id="charthw"></div>
                    </div>
                </div>
            </div>
        </div>
        @endif

    </div>



    <div class="modal" id="appointmentModal" tabindex="-1" role="dialog" aria-labelledby="smallmodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="smallmodalLabel">Editar Consulta</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <form id = 'update-ajex' action="{{ route('updateappointment') }}" method="post" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" required="" id="sAppId1" name="sAppId1" class="form-control">
                            <div class="form-group row">
                                <label class="col-12 col-sm-3 col-form-label text-sm-right">Id Consulta: </label>
                                <div class="col-12 col-sm-10 col-lg-8">
                                    <input type="text" required="" id="sAppId" name="sAppId" class="form-control" disabled>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-12 col-sm-3 col-form-label text-sm-right">Data da consulta</label>
                                <div class="col-12 col-sm-10 col-lg-8">
                                    <input id="sAppDate" class="form-control" name="sAppDate" dateformat="d M y" type="date"/>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="col-12 col-sm-3 col-form-label text-sm-right">Peso</label>
                                <div class="col-12 col-sm-10 col-lg-8">
                                    <input type="number" required="" id="sAppWeight" name="sAppWeight" class="form-control">
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="col-12 col-sm-3 col-form-label text-sm-right">Massa Muscular</label>
                                <div class="col-12 col-sm-10 col-lg-8">
                                    <input type="number" required="" id="sAppmm" name="sAppmm" class="form-control">
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="col-12 col-sm-3 col-form-label text-sm-right">Massa Gorda</label>
                                <div class="col-12 col-sm-10 col-lg-8">
                                    <input type="number" required="" id="sAppfm" name="sAppfm" class="form-control">
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="col-12 col-sm-3 col-form-label text-sm-right">Total Água</label>
                                <div class="col-12 col-sm-10 col-lg-8">
                                    <input type="number" required="" id="sApptw" name="sApptw" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-12 col-sm-3 col-form-label text-sm-right">Gordura Visceral</label>
                                <div class="col-12 col-sm-10 col-lg-8">
                                    <input type="number" required="" id="sAppvf" name="sAppvf" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-12 col-sm-3 col-form-label text-sm-right">Racio Anca/Cintura </label>
                                <div class="col-12 col-sm-10 col-lg-8">
                                    <input type="number" required="" id="sApphw" name="sApphw" class="form-control">
                                </div>
                            </div>
                                <div class="form-group row">
                                    <label class="col-12 col-sm-3 col-form-label text-sm-right">Upload PDF 1</label>
                                    <div class="col-12 col-sm-8 col-lg-6">
                                        <input type="file" class="form-control-file" accept="application/pdf"   name="mattach" id="mattach" data-parsley-pattern="([a-zA-Z0-9\s_\\.\-:])+(.pdf)$">
                                        <div id = 'pdfexist1'></div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-12 col-sm-3 col-form-label text-sm-right">Upload PDF 2</label>
                                    <div class="col-12 col-sm-8 col-lg-6">
                                        <input type="file" class="form-control-file" accept="application/pdf"   name="mattach2" id="mattach2" data-parsley-pattern="([a-zA-Z0-9\s_\\.\-:])+(.pdf)$">
                                        <div id = 'pdfexist2'></div>
                                    </div>
                                </div>

                            <div class="form-group row text-right">
                                <div class="col col-sm-2 col-lg-3 ">
                                    <button class="btn btn-space btn-danger" onclick="deleteAppointment()">Eliminar</button>
                                </div>

                                <div class="col col-sm-10 col-lg-8 offset-sm-1 offset-lg-0">
                                    <button type="button" class="btn btn-space btn-success" onclick="submitbtn()">Actualizar</button>

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
    <script src="{!! asset('js/appointment.js') !!}"></script>
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/series-label.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    @php
        $json = [];
        foreach($appointmentlist as $appointment){
          array_push($json, array(
            "a_id"   => $appointment->id,
            "weight" => $appointment->weight,
            "mm"     => $appointment->muscleMass,
            "fm"     => $appointment->fatMass,
            "tw"     => $appointment->totalWater,
            "vf"     => $appointment->visceralFat,
            "hw"     => $appointment->hipWaistRatio,
            "date"   => $appointment->date,
          ));
        }

        $json = json_encode($json);

    @endphp

    <script>
        // parse json as it was before
        window.chartData =  <?= $json ?>;

        var weight = [];
        var date = [];
        var average = [];
        var mm = [];
        var fm = [];
        var tw = [];
        var vf = [];
        var hw = [];
        var sum = 0;
        for (var i = 0; i < window.chartData.length; i++) {
            weight.push(Number(window.chartData[i].weight));
            mm.push(Number(window.chartData[i].mm));
            fm.push(Number(window.chartData[i].fm));
            tw.push(Number(window.chartData[i].tw));
            vf.push(Number(window.chartData[i].vf));
            hw.push(Number(window.chartData[i].hw));
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
        highchart("chartWeight", "Variação do Peso", "Peso (Kg)", "Data da consulta", "Peso", weight, "Média", average, date);
        highchart("chartfm", "Variação da Massa Gorda", "Massa Gorda (Kg)", "Data da Consulta", "Gordura", fm, "Média", average, date);
        highchart("chartmm", "Variação da Massa Muscular", "Massa Muscular (Kg)", "Data da Consulta", "Músculo", mm, "Média", average, date);
        highchart("charttw", "Variação do Total de Água", "Total Água", "Data da Consulta", "Total Água", tw, "Média", average, date);
        highchart("chartvf", "Variação da Gordura Visceral", "Gordura Visceral (g/cm²)", "Data da Consulta", "Gordura Visceral", vf, "Média", average, date);
        highchart("charthw", "Variação do Rácio Cintura/Anca", "Valores", "Data da Consulta", "Racio Cintura/Anca", hw, "Média", average, date);

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
