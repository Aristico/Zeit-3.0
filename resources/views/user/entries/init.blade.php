@extends('layouts.time')

@section('content')

   <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Überstundensaldo eintragen</div>

                <div class="card-body">

                    <p>Es ist fast gschafft. Bitte teilen Sie mir noch mit, wie viele Überstunden Sie zum Start haben.</p>

                    <!-- bei Action den Controller und die Methode eintrage z.B. UserController@Create -->
                    {!! Form::open(['action'=>['EntryController@initSet', $id], 'method' => 'POST']) !!}
                        {{csrf_field()}}
                        <div class="form-group">
                           {!! Form::label('balance', 'Aktuelle Überstunden:') !!}
                           {!! Form::number('balance', 0 , ['title'=>'balance', 'class'=>'form-control', 'step'=>0.25] ) !!}
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