@extends('layouts.app')

@section('content')

    <div class="container-fluid  dashboard-content">

    <div class="row">

        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="page-header">
                <h2 class="pageheader-title">Área de Cliente</h2>

            </div>
        </div>
    </div>
        <div class="row">
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12" id="pw_info" style="display:none;">

                <div class="card" style="padding: 10px 0 0 10px  ">
                    <div class="form-group row">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right">Password Antiga</label>
                        <div class="col-12 col-sm-8 col-lg-8">
                            <input type="password" required="" id="old_password" placeholder="Password"
                                   class="form-control">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right">Password Nova</label>
                        <div class="col-12 col-sm-8 col-lg-8">
                            <input type="password" required="" id="new_password" placeholder="Password"
                                   class="form-control">
                        </div>
                    </div>


                    <div class="form-group row">
                        <label class="col-12 col-sm-3 col-form-label text-sm-right">Confirmar Password</label>
                        <div class="col-12 col-sm-9 col-lg-8">
                            <input type="password" required="" id="confirm_password" placeholder="Password"
                                   class="form-control">
                        </div>

                        <div class="form-group row  text-right mx-auto">
                            <div class="col col-sm-10 col-lg-9 offset-sm-1 offset-lg-0 ">
                                <button type="button" onclick="pwChangeSubmit()"
                                        class="btn btn-space btn-primary">Alterar
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <ul class="nav nav-tabs " id="myTab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active show" id="personal-trainer-tab" data-toggle="tab" href="#personal-trainer" role="tab" >Personal Trainer</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="nutritionist-tab" data-toggle="tab" href="#nutritionist" role="tab" >Nutritionist</a>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade active show" id="personal-trainer" role="tabpanel">
    <div id="patientData">
        <div class="row ">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" id="nut_info">

                <div class="card" style="padding: 10px 0 0 10px  ">
                    <h3>Cliente: <strong><span id="patientName">{{ $user_details->name }}</span></strong></h3>
                    <h3>O seu personal Trainer: <strong><span id="nutritionistName">{{ $trainerDetails !=''? $trainerDetails->name:'' }}</span></strong></h3>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">

                    <h5 class="card-header">As Suas Consultas</h5>
                    <div class="card-body">

                        <div class="tab-regular">
                            <ul class="nav nav-tabs " id="myTab" role="tablist">

                                <li class="nav-item active show">
                                    <a class="nav-link" id="oongoing-tab" data-toggle="tab" href="#ongoing" role="tab" >OnGoing Plan</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade active show" id="ongoing" role="tabpanel">
                                    <h3>On Going Plan</h3>


                                    <table id="onlineAppointmentWholeTable" class="display table table-hover table-striped" style="width:100%">
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
                                        @php

                                            $out = "";
                                            foreach($ongoingPlan as $key => $Plan) {

                                                $weight = (json_decode($Plan->anthropometricData)->body_weight) ? json_decode($Plan->anthropometricData)->body_weight : "";
                                                $chest = (json_decode($Plan->anthropometricData)->body_chest) ? json_decode($Plan->anthropometricData)->body_chest : "";
                                                $waist = (json_decode($Plan->anthropometricData)->body_waist) ? json_decode($Plan->anthropometricData)->body_waist : "";
                                                $waistHip = (json_decode($Plan->anthropometricData)->body_waist_hip) ? json_decode($Plan->anthropometricData)->body_waist_hip : "";
                                                $hip = (json_decode($Plan->anthropometricData)->body_hip) ? json_decode($Plan->anthropometricData)->body_hip : "";

                                                $observations = ($Plan->observations != "") ? "<i class=\"fas fa-file\"></i>" : "";
                                                $download = $Plan->attachment ? 'Download PDF':"";
                                                $out .= "<tr data-appointment-id='$Plan->id' onclick='editOnlineAppointment($Plan->id)' style='cursor: pointer'>
                                                <td>$Plan->date</td>
                                                <td>$weight</td>
                                                <td>$chest</td>
                                                <td>$waist</td>
                                                <td>$waistHip</td>
                                                <td>$hip</td>
                                                 <td><a href = 'trainer-download-online/$Plan->attachment'>$download</a></td>

                                               </tr>";
                                            }
                                            echo $out;
                                        @endphp

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

                                </div>

                            </div>









                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
            </div>

        <!-----------------------------REST PAGE PATIENT  graphs _________________________-->




            <div class="tab-pane fade " id="nutritionist" role="tabpanel">
        <div id="patientData">
            <div class="row ">
                <!-- div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <button class="btn btn-primary pull-right" style="float:right" onclick="pwChange()">Alterar Password
                    </button>
                </div -->

                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" id="nut_info">

                    <div class="card" style="padding: 10px 0 0 10px  ">
                        <h3>Cliente: <strong><span id="patientName">{{ $user_details->name }}</span></strong></h3>
                        <h3>O seu Nutricionista: <strong><span id="nutritionistName">{{ $nutritionistDetails !=''? $nutritionistDetails->name:'' }}</span></strong></h3>
                    </div>
                </div>

            </div>

            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="card">

                        <h5 class="card-header">As Suas Consultas</h5>
                        <div class="card-body">

                            <div class="tab-regular">
                                <ul class="nav nav-tabs " id="myTab" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active show" id="presenciais-tab" data-toggle="tab" href="#presenciais" role="tab" >Consultas Presenciais</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="online-tab" data-toggle="tab" href="#online" role="tab" >Consultas Online</a>
                                    </li>

                                </ul>
                                <div class="tab-content" id="myTabContent">
                                    <div class="tab-pane fade active show" id="presenciais" role="tabpanel">
                                        <h3>Consultas Presenciais</h3>
                                        <table id="patientPageWholeTable" class="display table table-hover table-striped" style="width:100%">

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

                                            @php
                                                $out = "";
                                                foreach($appointments as $key => $appointment) {
                                                $attach  = json_decode($appointment->attachments,true);
                                                $attach1 = $attach['attach1'];
                                                $attach2 = $attach['attach2'];
                                                    $download = $appointment->attachments ? 'Download':"";
                                                    $out .= "<tr data-appointment-id='$appointment->id'>
                                                    <td>$appointment->date</td>
                                                    <td>$appointment->weight</td>
                                                    <td>$appointment->muscleMass</td>
                                                    <td>$appointment->fatMass</td>
                                                    <td>$appointment->totalWater</td>
                                                    <td>$appointment->visceralFat</td>
                                                    <td>$appointment->hipWaistRatio</td>
                                                    <td><a href = 'download/$attach1'>$download</a></td>
                                                    <td><a href = 'download/$attach2'>$download</a></td>
                                                   </tr>";
                                                }
                                                echo $out;
                                            @endphp

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
                                            </tr>
                                            </tfoot>
                                        </table>
                                        <br>
                                        <br>

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
                                    <div class="tab-pane fade" id="online" role="tabpanel">
                                        <h3>Consultas Online</h3>


                                        <table id="onlineAppointmentWholeTable" class="display table table-hover table-striped" style="width:100%">
                                            <thead>
                                            <tr>
                                                <th>Data</th>
                                                <th>Peso (Kg)</th>
                                                <th>PP (cm)</th>
                                                <th>PC1 (cm)</th>
                                                <th>PC2 (cm)</th>
                                                <th>PA (cm)</th>
                                                <th>Obs</th>
                                            </tr>
                                            </thead>

                                            <tbody id="appointmentTableBody">
                                            @foreach($onlineappointments as $appointment)
                                            @php


                                                    $weight = (json_decode($appointment->antropometrics)->body_weight) ? json_decode($appointment->antropometrics)->body_weight : "";
                                                    $chest = (json_decode($appointment->antropometrics)->body_chest) ? json_decode($appointment->antropometrics)->body_chest : "";
                                                    $waist = (json_decode($appointment->antropometrics)->body_waist) ? json_decode($appointment->antropometrics)->body_waist : "";
                                                    $waistHip = (json_decode($appointment->antropometrics)->body_waist_hip) ? json_decode($appointment->antropometrics)->body_waist_hip : "";
                                                    $hip = (json_decode($appointment->antropometrics)->body_hip) ? json_decode($appointment->antropometrics)->body_hip : "";

                                                    $observations = ($appointment->observations != "") ? "<i class=\"fas fa-file\"></i>" : "";
                                                     $download = $appointment->attachments ? $observations:"";
                                             @endphp
                                                    <tr data-appointment-id='$appointment->id' onclick='editOnlineAppointment($appointment->id)' style='cursor: pointer'>
                                                    <td>{{$appointment->date}}</td>
                                                    <td>{{$weight}}</td>
                                                    <td>{{$chest}}</td>
                                                    <td>{{$waist}}</td>
                                                    <td>{{$waistHip}}</td>
                                                    <td>{{$hip}}</td>
                                                    <td>  <span onclick="download('{{$appointment->observations}}','text.txt')">{!!  $observations !!}</span></td>

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
                                                <th>Obs</th>
                                            </tr>
                                            </tfoot>
                                        </table>

                                    </div>









                                </div>
                            </div>


                        </div>
                    </div>
                </div>
            </div>


            <!-----------------------------REST PAGE PATIENT  graphs _________________________-->


        </div>
            </div>
        </div><!-- patient data-->
</div>

@endsection

@section('script')
    <script type="application/javascript">
        var con = '<?php echo session()->has('success')?>';
        if(con){
            var success = '<?= session('success')?>';
            alert("Notice! "+success);
        }


    </script>

    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="https://code.highcharts.com/modules/series-label.js"></script>
    <script src="https://code.highcharts.com/modules/exporting.js"></script>
    <script src="https://code.highcharts.com/modules/export-data.js"></script>
    @php
        $json = [];
        foreach($appointments as $appointment){
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

    <script>
        // parse json as it was before
        window.chartData = <?= $json ?>;

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

        function barchart(date, weight, mm, fm, tw, vf, hw) {
            Highcharts.chart('chartbar', {
                //chart: {type: 'column'},
                title: {text: 'Somatório'},
                credits: {enabled: false},
                xAxis: {
                    categories: date,
                    crosshair: true,
                    title: {text: 'Data da Consulta'}
                },
                yAxis: {
                    min: 0, title: {text: 'Valores'}
                },
                tooltip: {
                    headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                    pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                        '<td style="padding:0"><b>{point.y:.1f} </b></td></tr>',
                    footerFormat: '</table>',
                    shared: true,
                    useHTML: true
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [{name: 'Peso', data: weight},
                    {name: 'Massa Muscular ', data: mm},
                    {name: 'Massa Gorda', data: fm},
                    {name: 'Total Água', data: tw},
                    {name: 'Gordura Visceral', data: vf},
                    {name: 'Rácio Cintura/Anca', data: hw}
                ]
            });
        }

        //build Charts
        highchart("chartWeight", "Variação do Peso", "Peso (Kg)", "Data da consulta", "Peso", weight, "Média", average, date);
        highchart("chartfm", "Variação da Massa Gorda", "Massa Gorda (Kg)", "Data da Consulta", "Gordura", fm, "Média", average, date);
        highchart("chartmm", "Variação da Massa Muscular", "Massa Muscular (Kg)", "Data da Consulta", "Músculo", mm, "Média", average, date);
        highchart("charttw", "Variação do Total de Água", "Total Água", "Data da Consulta", "Total Água", tw, "Média", average, date);
        highchart("chartvf", "Variação da Gordura Visceral", "Gordura Visceral (Kg)", "Data da Consulta", "Gordura Visceral", vf, "Média", average, date);
        highchart("charthw", "Variação do Rácio Cintura/Anca", "Valores", "Data da Consulta", "Racio Cintura/Anca", hw, "Média", average, date);
        //bar chart
        //barchart(date, weight, mm, fm, tw, vf, hw);
    </script>

@endsection
