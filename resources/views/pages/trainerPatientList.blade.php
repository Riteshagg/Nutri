@extends('layouts.app')
@section('content')

    <div class="container-fluid  dashboard-content">

        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="page-header">
                    <h2 class="pageheader-title">Ver Trainer Patients</h2>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <div class="card-body">
                        @include("layouts.message")
                        <div class="form-group">
                            <table class="display table table-hover table-striped" style="width:100%">
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
                    <h5 class="modal-title" id="smallmodalLabel">Editar Patients</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">


                    <div class="row">
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                            <form method="post" action="{{ route('trainer_patient_update') }}"  id="client-form">
                                @csrf
                                <div class="form-group row">
                                    <label class="col-12 col-sm-3 col-form-label text-sm-right">Id Cliente: </label>
                                    <div class="col-12 col-sm-10 col-lg-8">
                                        <input type="hidden" name="id" id="mpatientIdhidden">
                                        <input type="text" required="" name="ids" id="mpatientId" class="form-control" disabled>

                                    </div>
                                </div>

                                @if (count($trainers) !=0)
                                    <div class="form-group row">
                                        <label class="col-12 col-sm-3 col-form-label text-sm-right">Selecione o Trainer</label>
                                        <div class="col-12 col-sm-8 col-lg-6">
                                            <select class="form-control" id="patientTrainerDropdown" name="trainerSelect" required>
                                                <option value="">Selecionar Trainer</option>

                                                @foreach ($trainers as $key=>$trainer)
                                                    <option value="{{ $trainer->id }}" >{{ $trainer->name  }}</option>;
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                @else
                                    {{ Form::hidden( 'trainerSelect', Auth::id() ) }}
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
                                        <a href = 'trainer-patient-delete/' id = 'mpdelete' class="btn btn-space btn-danger">Eliminar</a>
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

@endsection

@section('script')

    <script type="text/javascript" src="https://cdn.datatables.net/v/bs4/dt-1.10.18/datatables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/fixedheader/3.1.5/js/dataTables.fixedHeader.min.js"></script>
    <script>
        dataTablePatient();
    </script>
    <script src="{!! asset('js/trainerPatient.js') !!}"></script>

@endsection
