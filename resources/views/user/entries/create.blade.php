@extends('layouts.time')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Eintrag erstellen</div>

                <div class="card-body">

                    <!-- bei Action den Controller und die Methode eintrage z.B. UserController@Create -->
                    {!! Form::open(['action'=>['EntryController@store'], 'method' => 'POST']) !!}
                        {{csrf_field()}}

                        <input type="hidden" name="user_id" value="{{$schedule->user_id}}">
                        <input type="hidden" name="regular_hours" value="{{$schedule->regularHours()}}">
                        <input type="hidden" name="schedule_version" value="{{$schedule->version}}">

                        <input type="hidden" name="year" value="{{\Carbon\Carbon::parse($date)->format('Y')}}">
                        <input type="hidden" name="month" value="{{\Carbon\Carbon::parse($date)->format('m')}}">

                        <div class="form-group">
                           {!! Form::label('date', 'Datum') !!}
                           {!! Form::date('date', $date, ['title'=>'date', 'class'=>'form-control', 'readonly'] ) !!}
                        </div>

                        <div class="form-group">
                           {!! Form::label('begin', 'Anfang') !!}
                           {!! Form::time('begin', $schedule->begin, ['title'=>'begin', 'class'=>'form-control'] ) !!}
                        </div>

                        <div class="form-group">
                           {!! Form::label('end', 'Ende') !!}
                           {!! Form::time('end', $schedule->end, ['title'=>'end', 'class'=>'form-control'] ) !!}
                        </div>

                        <div class="form-group">
                           {!! Form::label('break', 'Pause') !!}
                           {!! Form::number('break', $schedule->break, ['title'=>'break', 'class'=>'form-control'] ) !!}
                        </div>

                        <div class="form-group">
                           {!! Form::label('comment', 'Kommentar:') !!}
                           {!! Form::text('comment', null, ['title'=>'comment', 'class'=>'form-control'] ) !!}
                        </div>

                        <button class="btn btn-primary">Speichern</button>

                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>

@endsection