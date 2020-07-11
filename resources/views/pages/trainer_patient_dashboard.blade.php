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

    <div id="patientData">
        <div class="row ">
            <!-- div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <button class="btn btn-primary pull-right" style="float:right" onclick="pwChange()">Alterar Password
                </button>
            </div -->

            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12" id="nut_info">

                <div class="card" style="padding: 10px 0 0 10px  ">
                    <h3>Cliente: <strong><span id="patientName">{{ $user_details->name }}</span></strong></h3>
                    <h3>O seu personal Trainer: <strong><span id="nutritionistName">{{ $nutritionistDetails->name  }}</span></strong></h3>
                </div>
            </div>
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

        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">

                    <h5 class="card-header">As Suas Consultas</h5>
                    <div class="card-body">

                        <div class="tab-regular">
                            <ul class="nav nav-tabs " id="myTab" role="tablist">

                                <li class="nav-item">
                                    <a class="nav-link" id="online-tab" data-toggle="tab" href="#online" role="tab" >Consultas Online</a>
                                </li>
                            </ul>
                            <div class="tab-content" id="myTabContent">

                                <div class="tab-pane  fade active show" id="online" role="tabpanel">
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
                                            <th>Download PDF</th>
                                        </tr>
                                        </thead>

                                        <tbody id="appointmentTableBody">
                                        @php

                                        $out = "";
                                        foreach($onlineappointments as $key => $appointment) {

                                        $weight = (json_decode($appointment->anthropometricData)->body_weight) ? json_decode($appointment->anthropometricData)->body_weight : "";
                                        $chest = (json_decode($appointment->anthropometricData)->body_chest) ? json_decode($appointment->anthropometricData)->body_chest : "";
                                        $waist = (json_decode($appointment->anthropometricData)->body_waist) ? json_decode($appointment->anthropometricData)->body_waist : "";
                                        $waistHip = (json_decode($appointment->anthropometricData)->body_waist_hip) ? json_decode($appointment->anthropometricData)->body_waist_hip : "";
                                        $hip = (json_decode($appointment->anthropometricData)->body_hip) ? json_decode($appointment->anthropometricData)->body_hip : "";

                                        $observations = ($appointment->observations != "") ? "<i class=\"fas fa-file\"></i>" : "";
                                        $download = $appointment->attachment ? 'Download PDF':"";
                                        $out .= "<tr data-appointment-id='$appointment->id' onclick='editOnGoingPlan($appointment->id)' style='cursor: pointer'>
                                            <td>$appointment->date</td>
                                            <td>$weight</td>
                                            <td>$chest</td>
                                            <td>$waist</td>
                                            <td>$waistHip</td>
                                            <td>$hip</td>
                                            <td><a href = 'trainer-download-online/$appointment->attachment'>$download</a></td>

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


        <!-----------------------------REST PAGE PATIENT  graphs _________________________-->



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


@endsection
