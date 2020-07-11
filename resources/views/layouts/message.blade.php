@if(Session::has('success'))
    <div class="alert alert-success alert-dismissible fade show">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>{{ Session::get('success') }}</strong>
    </div>

@endif

@if ($message = Session::get('error'))
    <div class="alert alert-danger alert-outline alert-dismissible fade show" role="alert">
        {{ $message }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true" class="la la-close"></span>
        </button>
    </div>
@endif


@if($errors->all())
    <div class="alert alert-danger alert-outline alert-dismissible fade show" role="alert">
        @foreach($errors->all() as $error)
            {{ $error }} <br/>
        @endforeach
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true" class="la la-close"></span>
        </button>
    </div>
@endif
