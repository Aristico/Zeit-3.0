@extends('layouts.time')

@section('content')

    <h1>Benutzer anlegen</h1>

    <!-- bei Action den Controller und die Methode eintrage z.B. UserController@Create -->
    {!! Form::open(['action'=>'UserController@store', 'method' => 'post']) !!}
        {{csrf_field()}}
        <div class="form-group">
           {!! Form::label('firstname', 'Vorname') !!}
           {!! Form::text('firstname', null, ['title'=>'firstname', 'class'=>'form-control'] ) !!}
        </div>
        <div class="form-group">
           {!! Form::label('name', 'Nachname') !!}
           {!! Form::text('name', null, ['title'=>'name', 'class'=>'form-control'] ) !!}
        </div>
        <div class="form-group">
           {!! Form::label('email', 'E-Mail') !!}
           {!! Form::email('email', null, ['title'=>'email', 'class'=>'form-control'] ) !!}
        </div>
        <div class="form-group">
           {!! Form::label('password', 'Passwort') !!}
           {!! Form::password('password', ['title'=>'password', 'class'=>'form-control'] ) !!}
        </div>

        @include('includes.message')

        <div class="form-group">
           {!! Form::submit('Anlegen', ['class'=>'btn btn-primary'] ) !!}
        </div>
    {!! Form::close() !!}

@endsection