@if(session('success_message'))
    <div class="alert alert-success">
        {{session('success_message')}}
    </div>
@endif
@if(session('info_message'))
    <div class="alert alert-info">
        {{session('info_message')}}
    </div>
@endif
@if(session('error_message'))
    <div class="alert alert-danger">
        {{session('error_message')}}
    </div>
@endif