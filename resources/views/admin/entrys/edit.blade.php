@extends('layouts.time')

@section('content')

    <div class="col-sm-6">
        <h1>Eintrag Bearbeiten</h1>

        <p class="alert alert-info">Sie Bearbeiten hier den Eintrag vom <b>{{$entry->dateCarbon()->format('d.m.Y')}}</b>. Die reguläre Arbeitszeit
           beträgt {{$entry->regular_hours}} Stunden und aktuell sind {{$entry->regular_hours}} Stunden vermerkt. Daraus ergibt sich
           eine Abweichung von {{$entry->overtime}} und ein Überstunden-Saldo von {{$entry->balance}}.</p>

        <!-- bei Action den Controller und die Methode eintrage z.B. UserController@Create -->
        {!! Form::model($entry, ['action'=>['EntryController@update', $entry->id], 'method' => 'PUT']) !!}
            {{csrf_field()}}
            
            <div class="form-group">
               {!! Form::label('begin', 'Anfang') !!}
               {!! Form::time('begin', $entry->beginCarbon()->format('H:i'), ['title'=>'begin', 'class'=>'form-control'] ) !!}
            </div>
            
            <div class="form-group">
               {!! Form::label('end', 'Ende') !!}
               {!! Form::time('end', $entry->endCarbon()->format('H:i'), ['title'=>'end', 'class'=>'form-control'] ) !!}
            </div>

            <div class="form-group">
               {!! Form::label('break', 'Pause') !!}
               {!! Form::number('break', null, ['title'=>'break', 'class'=>'form-control'] ) !!}
            </div>
            
            <div class="form-group">
               {!! Form::label('comment', 'Kommentar:') !!}
               {!! Form::text('comment', null, ['title'=>'comment', 'class'=>'form-control'] ) !!}   
            </div>
            
            <button class="btn btn-primary" name="command" value="save">Speichern</button>
            <button class="btn btn-danger" name="command" value="delete">Eintrag Löschen</button>

        {!! Form::close() !!}
    </div>

@endsection