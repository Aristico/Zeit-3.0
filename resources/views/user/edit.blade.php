@extends('layouts.time')

@section('content')

 <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Benutzer bearbeiten</div>

                <div class="card-body">

                    <!-- bei Action den Controller und die Methode eintrage z.B. UserController@Create -->
                    {!! Form::model($user, ['action'=>['UserController@update', $user->id], 'method' => 'put']) !!}
                        {{csrf_field()}}
                        <div class="form-group">
                           {!! Form::label('firstname', 'Vorname') !!}
                           {!! Form::text('firstname', null, ['title'=>'firstname', 'class'=>'form-control'] ) !!}
                        </div>
                        @include('includes.error', ['field'=>'firstname'])

                        <div class="form-group">
                           {!! Form::label('name', 'Nachname') !!}
                           {!! Form::text('name', null, ['title'=>'name', 'class'=>'form-control'] ) !!}
                        </div>
                        @include('includes.error', ['field'=>'name'])

                        <div class="form-group">
                           {!! Form::label('email', 'E-Mail') !!}
                           {!! Form::email('email', null, ['title'=>'email', 'class'=>'form-control'] ) !!}
                        </div>
                        @include('includes.error', ['field'=>'email'])

                        <div class="form-group">
                           {!! Form::label('password', 'Passwort') !!}
                           {!! Form::password('password', ['title'=>'password', 'class'=>'form-control'] ) !!}
                        </div>
                        @include('includes.error', ['field'=>'password'])

                        <button class="btn btn-primary" name="command" value="save">Speichern</button>
                        <button class="btn btn-danger" name="command" value="delete">Löschen</button>
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>


@endsection