@extends('layouts.app')

@section('content')

    <div class="container-fluid  dashboard-content">
        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="page-header">
                    <h2 class="pageheader-title">Add personal trainer</h2>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">

                    <div class="card-body">
                        @include('layouts.message')
                        <form action="{{ route('personal_trainer_save') }}" method="post">
                            @csrf
                            <div class="form-group row">
                                <label class="col-12 col-sm-3 col-form-label text-sm-right">Nome</label>
                                <div class="col-12 col-sm-8 col-lg-6">
                                    <input type="text" required name="name" id="name" placeholder="Introduza o Nome" class="form-control" value="{{ old('name') }}" >
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-12 col-sm-3 col-form-label text-sm-right">Telefone</label>
                                <div class="col-12 col-sm-8 col-lg-6">
                                    <input type="number" required name="phone" id="phone" placeholder="Introduza o Telefone" class="form-control" maxlength="9" >
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-12 col-sm-3 col-form-label text-sm-right">Data Nascimento</label>
                                <div class="col-12 col-sm-8 col-lg-6">
                                    <input id="dob" required name="dob" class="form-control" dateformat="d M Y" type="date" />
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-12 col-sm-3 col-form-label text-sm-right">Email</label>
                                <div class="col-12 col-sm-8 col-lg-6">
                                    <input type="email" required name="email" id="email" placeholder="Introduza o Email" class="form-control" >
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
    </div>

@endsection

