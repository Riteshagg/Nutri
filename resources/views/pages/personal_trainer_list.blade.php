@extends('layouts.app')
@section('content')

    <div class="container-fluid  dashboard-content">

        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="page-header">
                    <h2 class="pageheader-title">Ver Personal Trainer</h2>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                @include("layouts.message")
                <div class="card">
                    <div class="card-body">

                        <div class="form-group">
                            <table class="display table table-hover table-striped" style="width:100%">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>Telefone</th>
                                    <th>Data de Nascimento</th>
                                    <th>Email</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($user_list as $users=>$user)
                                    <tr data-patient-id='{{ $user->id }}' onclick='editPersonalTrainer({{ $user->id }})' style="cursor: pointer">
                                        <td>{{ ++$i }}</td>
                                        <td>{{$user->name}}</td>
                                        <td>{{$user->phone}}</td>
                                        <td>{{$user->dob}}</td>
                                        <td>{{$user->email}}</td>
                                    </tr>
                                @endforeach

                                </tbody>
                                <tfoot>
                                </tfoot>
                            </table>
                            {!! $user_list->render() !!}
                        </div>

                    </div>
                </div>
            </div>
        </div>




        <div class="modal" id="trainerModal" tabindex="-1" role="dialog" aria-labelledby="smallmodalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="smallmodalLabel">Editar personal trainer</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">


                        <div class="row">
                            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                                <form method="post" action="{{ route('trainer_update') }}"  id="client-form">
                                    @csrf
                                    <div class="form-group row">
                                        <label class="col-12 col-sm-3 col-form-label text-sm-right">Id: </label>
                                        <div class="col-12 col-sm-10 col-lg-8">
                                            <input type="hidden" name="id" id="mnutriIdhidden">
                                            <input type="text" required="" name="ids" id="mtrainerId" class="form-control" disabled>

                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-12 col-sm-3 col-form-label text-sm-right">Nome</label>
                                        <div class="col-12 col-sm-10 col-lg-8">
                                            <input type="text" required="" name="name" id="mpName" placeholder="Introduza o nome do Nutricionista" class="form-control">
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-12 col-sm-3 col-form-label text-sm-right">Telefone</label>
                                        <div class="col-12 col-sm-10 col-lg-8">
                                            <input type="number" required="" name="phone" id="mpPhone" placeholder="Introduza o Telefone" class="form-control">
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-12 col-sm-3 col-form-label text-sm-right">Date of Birth</label>
                                        <div class="col-12 col-sm-10 col-lg-8">
                                            <input id="mpDate" name="dob" class="form-control" dateformat="d M y" type="date"/>
                                            <span class="mpDate_span" id="mpDate_span" style="pointer-events: none;"></span>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-12 col-sm-3 col-form-label text-sm-right">Email</label>
                                        <div class="col-12 col-sm-10 col-lg-8">
                                            <input type="text" name="email" required="" id="mpEmail" placeholder="Intoduza o email" class="form-control">
                                        </div>
                                    </div>



                                    <div class="form-group row text-right">
                                        <div class="col col-sm-2 col-lg-3 ">
                                            <a href = 'trainer_delete/' id = 'mpdelete' class="btn btn-space btn-danger">Eliminar</a>
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




    </div>

@endsection
@section('script')

    <script src="{!! asset('js/personalTrainer.js') !!}"></script>

@endsection

