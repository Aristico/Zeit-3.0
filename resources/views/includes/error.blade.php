@if(count($errors->get($field)) > 0)
    <div class="alert alert-danger">
        {{$errors->first($field)}}
    </div>
@endif