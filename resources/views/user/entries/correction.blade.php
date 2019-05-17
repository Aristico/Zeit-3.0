@extends('layouts.time')

@section('content')

   <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Überstunden auszahlen</div>

                <div class="card-body">

                    <p>Bitte tregen Sie ein, wie viele Stunden sie auszahlen lassen wollen.</p>
                    @include('includes.errors')


                    <!-- bei Action den Controller und die Methode eintrage z.B. UserController@Create -->
                    {!! Form::model($entry, ['action'=>['EntryController@setCorrection', $entry->id], 'method' => 'PUT']) !!}
                        {{csrf_field()}}
                        <div class="form-group">
                           {!! Form::label('correction', 'Anzahl Überstunden:') !!}
                           {!! Form::number('correction', $entry->correction , ['title'=>'correction', 'class'=>'form-control', 'step'=>0.25] ) !!}
                        </div>

                        @include('includes.error', ['field'=>'balance'])

                        <div class="form-group">
                           {!! Form::submit('Speichern', ['class'=>'btn btn-primary'] ) !!}
                        </div>

                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>

@endsection
