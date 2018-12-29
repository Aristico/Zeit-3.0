@extends('layouts.time')

@section('content')

 <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Benutzer löschen</div>

                <div class="card-body">


                    <p class="alert-danger alert">Wollen Sie den Eintrag von {{$user->firstname}} {{$user->name}} wirklich löschen</p>

                    <!-- bei Action den Controller und die Methode eintrage z.B. UserController@Create -->
                    {!! Form::open(['action'=>['UserController@destroy', $user->id], 'method' => 'DELETE']) !!}
                        {{csrf_field()}}
                        <button class="btn btn-danger" name="command" value="yes">Ja</button>
                        <button class="btn btn-primary" name="command" value="no">Nein</button>
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>

@endsection