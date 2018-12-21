@extends('layouts.time')

@section('content')

    <h1>Zeitplan Bearbeiten</h1>
    <br>
    <!-- bei Action den Controller und die Methode eintrage z.B. UserController@Create -->
    {!! Form::open(['action'=>['ScheduleController@update'], 'method' => 'put']) !!}
        {{csrf_field()}}
        <div class="row">
            <p class="col-sm-4 offset-2">Anfang</p>
            <p class="col-sm-4">Ende</p>
            <p class="col-sm-2">Pause</p>
        </div>
        @If(count($schedule) > 0)
            @foreach($schedule as $single_day)
                <div class="row">
                    <div class="col-sm-2">
                        <p>{{$days[$single_day->day]}}</p>
                    </div>
                    <input type="hidden" name="day[{{$single_day->day}}][day]" value="{{$single_day->day}}">
                    <input type="hidden" name="day[{{$single_day->day}}][user_id]" value="{{$single_day->user_id}}">
                    <input type="hidden" name="day[{{$single_day->day}}][version]" value="{{$single_day->version}}">

                    <div class="form-group col-sm-4">
                       {!! Form::label('day[' . $single_day->day . '][begin]', 'Beginn:', ['class'=>'sr-only']) !!}
                       {!! Form::time('day[' . $single_day->day . '][begin]', $single_day->begin, ['title'=>'begin', 'class'=>'form-control'] ) !!}
                    </div>
                    <div class="form-group  col-sm-4">
                       {!! Form::label('day[' . $single_day->day . '][end]', 'Ende:', ['class'=>'sr-only']) !!}
                       {!! Form::time('day[' . $single_day->day . '][end]', $single_day->end, ['title'=>'end', 'class'=>'form-control'] ) !!}
                    </div>
                    <div class="form-group col-sm-2">
                       {!! Form::label('day[' . $single_day->day . '][break]', 'Pause', ['class'=>'sr-only']) !!}
                       {!! Form::number('day[' . $single_day->day . '][break]', $single_day->break, ['title'=>'break', 'class'=>'form-control'] ) !!}
                    </div>
                </div>
            @endforeach
        @endif

        @include('includes.message')

        @if(session('success'))
           <button class="btn btn-primary" value="true" name="ready">Speichern</button>
           <button class="btn btn-secondary" value="false" name="ready">Ändern</button>
        @else
           <button class="btn btn-primary" value="false" name="ready">Ändern</button>
        @endif
    {!! Form::close() !!}

@endsection