@extends('layouts.app')
@section('content')


    <div class="container-fluid  dashboard-content">

        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="page-header">
                    <h2 class="pageheader-title">Ver Clientes</h2>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <div class="card-body">
                        @include("layouts.message")


                        @if(Auth::user()->hasRole('Admin'))
                            <form action="{{route('filter_trainer_nutri')}}" method="post" class="selectTrainerOrNutritionist">
                                @csrf
                            <div class="form-group row" id="patientNutritionistAppDropdownRow">
                                <label class="col-12 col-sm-3 col-form-label text-sm-right">Selecionar</label>
                                <div class="col-12 col-sm-8 col-lg-6">


                                    <select class="form-control" name="selectTrainerOrNutritionist" id="nutritionistSelect">
                                        <option value="" > Selecionar</option>
                                       <option value="trainer">Personal Trainer</option>
                                       <option value="nutritionist">Nutritionist</option>
                                    </select>
                                </div>

                            </div>
                            </form>
                            <script>
                                $(function() {
                                    $('#nutritionistSelect').change(function() {
                                       $('.selectTrainerOrNutritionist').submit();
                                    });
                                });
                            </script>



                        @if(!empty($filter))
                            <?php
                                if($filter =='trainer'){
                                    echo "<h3>Personal Trainer Client</h3>";
                                    $id = 'patientTrainerDropdown';
                                    $delete = "<a href = 'trainer-patient-delete/' id = 'mpdelete' class='btn btn-space btn-danger'>Eliminar</a>";
                                         $action = "trainer-patients-update";
                                }else{
                                    echo "<h3>Nutritionist Client</h3>";
                                    $id = 'patientNutritionistDropdown';
                                    $delete = " <a href = 'patient_delete/' id = 'mpdelete' class='btn btn-space btn-danger'>Eliminar</a>";
                                    $action = "patients-update";
                                }

                                ?>

                            <div class="form-group">
                                <table class="display table table-hover table-striped" id="data-table" style="width:100%">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nome</th>
                                        <th>Telefone</th>
                                        <th>Data de Nascimento</th>
                                        <th>Email</th>
                                        @if(Auth::user()->hasRole('Admin'))
                                            <th>Activo</th>
                                        @endif
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @foreach ($data as $key => $patient)

                                        <tr data-patient-id='{{ $patient->user->id }}' onclick='editPatient({{ $patient->user->id }})' style="cursor: pointer">
                                            <td>{{ ++$i }}</td>
                                            <td>{{$patient->user->name}}</td>
                                            <td>{{$patient->user->phone}}</td>
                                            <td>{{$patient->user->dob}}</td>
                                            <td>{{$patient->user->email}}</td>
                                            @if(Auth::user()->hasRole('Admin'))
                                                <th>{{ ($patient->user->status == 1)? "Activo" : "Inactivo" }}</th>
                                            @endif
                                        </tr>
                                    @endforeach

                                    </tbody>
                                    <tfoot>
                                    </tfoot>
                                </table>

{{--                                {!! $data->render() !!}--}}
                            </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal" id="patientModal" tabindex="-1" role="dialog" aria-labelledby="smallmodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="smallmodalLabel">Editar Patients</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">


                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <form method="post" action="{{($filter=='trainer')? route('trainer_patient_update'):route('patient_update')}}"  id="client-form">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-12 col-sm-3 col-form-label text-sm-right">Id Cliente: </label>
                                    <div class="col-12 col-sm-10 col-lg-8">
                                        <input type="hidden" name="id" id="mpatientIdhidden">
                                        <input type="text" required="" name="ids" id="mpatientId" class="form-control" disabled>

                                    </div>
                                </div>

                                @if (count($selectUserToAssign))
                                    <?php $name =  $filter=='trainer'? 'trainerSelect': 'nutritionistSelect'?>
                                    <div class="form-group row">
                                        <label class="col-12 col-sm-3 col-form-label text-sm-right">Selecione o <?php echo $filter?></label>
                                        <div class="col-12 col-sm-8 col-lg-6">
                                            <select class="form-control" id="<?=$id?>" name="<?php echo $name ?>" required>
                                                <option value="">Selecionar Trainer</option>

                                                @foreach ($selectUserToAssign as $key=>$AssignUser)
                                                    <option value="{{ $AssignUser->id }}" >{{ $AssignUser->name  }}</option>;
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                @else
                                    {{ Form::hidden("$name", Auth::id() ) }}
                                @endif

                                <div class="form-group row">
                                    <label class="col-12 col-sm-3 col-form-label text-sm-right">Nome do Cliente</label>
                                    <div class="col-12 col-sm-10 col-lg-8">
                                        <input type="text" required="" name="name" id="mpName" placeholder="Introduza o nome do Cliente" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-12 col-sm-3 col-form-label text-sm-right">Telefone</label>
                                    <div class="col-12 col-sm-10 col-lg-8">
                                        <input type="number" required="" name="phone" id="mpPhone" placeholder="Introduza o Telefone" class="form-control">
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label class="col-12 col-sm-3 col-form-label text-sm-right">Data de nascimento</label>
                                    <div class="col-12 col-sm-10 col-lg-8">
                                        <input id="mpDate" name="dob" class="form-control" dateformat="d M y" type="date"/>
                                        <span class="mpDate_span" id="mpDate_span" style="pointer-events: none;"></span>
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label class="col-12 col-sm-3 col-form-label text-sm-right">Email</label>
                                    <div class="col-12 col-sm-10 col-lg-8">
                                        <input type="email"  name="email" required="" id="mpEmail" placeholder="Intoduza o email" class="form-control">
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label class="col-12 col-sm-3 col-form-label text-sm-right">Activo</label>
                                    <div class="col-12 col-sm-10 col-lg-8">
                                        <select name="status" id="pActive" class="form-control" required>
                                            <option value="0">Inactivo</option>
                                            <option value="1">Activo</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row text-right">
                                    <div class="col col-sm-2 col-lg-3 ">
                                       <?=$delete?>
                                        {{--                                    <button class="btn btn-space btn-danger" onclick="patientDelete()">Eliminar</button>--}}
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
@endif

     @endif





                        @if(Auth::user()->hasRole('Nutritionist'))
                        <div class="form-group">
                            <table class="display table table-hover table-striped" id="data-table" style="width:100%">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Telefone</th>
                                    <th>Data de Nascimento</th>
                                    <th>Email</th>
                                    @if(Auth::user()->hasRole('Admin'))
                                    <th>Activo</th>
                                    @endif
                                </tr>
                                </thead>
                                    <tbody>
                                    @foreach ($data as $key => $patient)

                                        <tr data-patient-id='{{ $patient->user->id }}' onclick='editPatient({{ $patient->user->id }})' style="cursor: pointer">
                                            <td>{{ ++$i }}</td>
                                            <td>{{$patient->user->name}}</td>
                                            <td>{{$patient->user->phone}}</td>
                                            <td>{{$patient->user->dob}}</td>
                                            <td>{{$patient->user->email}}</td>
                                            @if(Auth::user()->hasRole('Admin'))
                                                <th>{{ ($patient->user->status == 1)? "Activo" : "Inactivo" }}</th>
                                            @endif
                                        </tr>
                                    @endforeach

                                    </tbody>
                                <tfoot>
                                </tfoot>
                            </table>
                            {!! $data->render() !!}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="modal" id="patientModal" tabindex="-1" role="dialog" aria-labelledby="smallmodalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="smallmodalLabel">Editar Cliente</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">


                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                        <form method="post" action="{{ route('patient_update') }}"  id="client-form">
                            @csrf
                            <div class="form-group row">
                                <label class="col-12 col-sm-3 col-form-label text-sm-right">Id Cliente: </label>
                                <div class="col-12 col-sm-10 col-lg-8">
                                    <input type="hidden" name="id" id="mpatientIdhidden">
                                    <input type="text" required="" name="ids" id="mpatientId" class="form-control" disabled>

                                </div>
                            </div>

                            @if (count($nutritionists))
                                <div class="form-group row">
                                    <label class="col-12 col-sm-3 col-form-label text-sm-right">Selecione o Nutricionista</label>
                                    <div class="col-12 col-sm-8 col-lg-6">
                                        <select class="form-control" id="patientNutritionistDropdown" name="nutritionistSelect" required>
                                            <option value="">Selecionar Nutricionista</option>

                                            @foreach ($nutritionists as $key=>$nutritionist)
                                                <option value="{{ $nutritionist->id }}" >{{ $nutritionist->name  }}</option>;
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                            @else
                                {{ Form::hidden( 'nutritionistSelect', Auth::id() ) }}
                            @endif

                            <div class="form-group row">
                                <label class="col-12 col-sm-3 col-form-label text-sm-right">Nome do Cliente</label>
                                <div class="col-12 col-sm-10 col-lg-8">
                                    <input type="text" required="" name="name" id="mpName" placeholder="Introduza o nome do Cliente" class="form-control">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-12 col-sm-3 col-form-label text-sm-right">Telefone</label>
                                <div class="col-12 col-sm-10 col-lg-8">
                                    <input type="number" required="" name="phone" id="mpPhone" placeholder="Introduza o Telefone" class="form-control">
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="col-12 col-sm-3 col-form-label text-sm-right">Data de nascimento</label>
                                <div class="col-12 col-sm-10 col-lg-8">
                                    <input id="mpDate" name="dob" class="form-control" dateformat="d M y" type="date"/>
                                    <span class="mpDate_span" id="mpDate_span" style="pointer-events: none;"></span>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="col-12 col-sm-3 col-form-label text-sm-right">Email</label>
                                <div class="col-12 col-sm-10 col-lg-8">
                                    <input type="email"  name="email" required="" id="mpEmail" placeholder="Intoduza o email" class="form-control">
                                </div>
                            </div>


                            <div class="form-group row">
                                <label class="col-12 col-sm-3 col-form-label text-sm-right">Activo</label>
                                <div class="col-12 col-sm-10 col-lg-8">
                                    <select name="status" id="pActive" class="form-control" required>
                                        <option value="0">Inactivo</option>
                                        <option value="1">Activo</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row text-right">
                                <div class="col col-sm-2 col-lg-3 ">
                                    <a href = 'patient_delete/' id = 'mpdelete' class='btn btn-space btn-danger'>Eliminar</a>
{{--                                    <button class="btn btn-space btn-danger" onclick="patientDelete()">Eliminar</button>--}}
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
    @endif
    <?php
        if($filter=='trainer'){
            echo "<script src='js/trainerPatient.js'></script>";
        }else{
            echo " <script src='js/patient.js'></script>";
        }



    ?>
@endsection

@section('script')

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.js"></script>

    <script>
        $.extend( $.fn.dataTable.defaults, {
            searching: false,
            ordering:  false,
        } );
        $(document).ready( function () {
            $('#data-table').DataTable();
        } );
    </script>


@endsection
