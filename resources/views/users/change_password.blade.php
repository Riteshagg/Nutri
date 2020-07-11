@extends('layouts.app')
@section('content')

    <div class="container-fluid  dashboard-content">

        <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="page-header">
                    <h2 class="pageheader-title">Definições</h2>

                </div>
            </div>
        </div>

        <div class="row">

            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="card">
                    <div class="card-body">
                        @include('layouts.message')
                        <form id="changeSettingsForm" data-parsley-validate method="post" action="{{ route('update_password') }}">
                            @csrf
                            <div class="form-group row">
                                <label class="col-12 col-sm-3 col-form-label text-sm-right">Password Antiga</label>
                                <div class="col-12 col-sm-8 col-lg-6">
                                    <input type="password" required id="oldPassword" name="oldPassword" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-12 col-sm-3 col-form-label text-sm-right">Password Nova</label>
                                <div class="col-12 col-sm-8 col-lg-6">
                                    <input type="password" required id="newPassword" name="newPassword" class="form-control">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-12 col-sm-3 col-form-label text-sm-right">Password Nova Redigitar</label>
                                <div class="col-12 col-sm-8 col-lg-6">
                                    <input type="password" required id="confirmpassword" name="confirmpassword" class="form-control">
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