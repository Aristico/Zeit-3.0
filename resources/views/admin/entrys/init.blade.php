@extends('layouts.time')

@section('content')

    <div class="col-sm-6">
        <h1>Benutzereinstellungen</h1>

        <p>Es ist fast gschafft. Bitte teilen Sie mir noch mit, wie viele Überstunden Sie zum Start haben.</p>

        <!-- bei Action den Controller und die Methode eintrage z.B. UserController@Create -->
        {!! Form::open(['action'=>['EntryController@initSet', $id], 'method' => 'POST']) !!}
            {{csrf_field()}}
            <div class="form-group">
               {!! Form::label('balance', 'Aktuelle Überstunden:') !!}
               {!! Form::number('balance', null , ['title'=>'balance', 'class'=>'form-control'] ) !!}
            </div>
            <div class="form-group">
               {!! Form::submit('Speichern', ['class'=>'btn btn-primary'] ) !!}
            </div>
        {!! Form::close() !!}
    </div>

@endsection