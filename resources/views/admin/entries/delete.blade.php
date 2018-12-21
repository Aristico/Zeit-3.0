@extends('layouts.time')

@section('content')

    <div class="col-sm-6">
        <h1>Eintrag löschen</h1>

        <p>Wollen Sie den Eintrag von {{$entry->dateCarbon()->format('d.m.Y')}} wirklich löschen</p>

        <!-- bei Action den Controller und die Methode eintrage z.B. UserController@Create -->
        {!! Form::open(['action'=>['EntryController@destroy', $entry->id], 'method' => 'DELETE']) !!}
            {{csrf_field()}}
            <button class="btn btn-danger" name="command" value="yes">Ja</button>
            <button class="btn btn-primary" name="command" value="no">Nein</button>
        {!! Form::close() !!}
    </div>

@endsection