@extends('layouts.time')

@section('content')

    <h1>Benutzer anlegen</h1>
    <br>
    <!-- bei Action den Controller und die Methode eintrage z.B. UserController@Create -->
    {!! Form::open(['action'=>'ScheduleController@store', 'method' => 'post']) !!}
        {{csrf_field()}}
        <div class="row">
            <p class="col-sm-4 offset-2">Anfang</p>
            <p class="col-sm-4">Ende</p>
            <p class="col-sm-2">Pause</p>
        </div>
        @If(count($defaults) > 0)
            @foreach($defaults as $default)
                <div class="row">
                    <div class="col-sm-2">
                        <p>{{$days[$default->day]}}</p>
                    </div>
                    <input type="hidden" name="day[{{$default->day}}][day]" value="{{$default->day}}">
                    <input type="hidden" name="day[{{$default->day}}][user_id]" value="{{isset($userid) ? $userid : '0'}}">

                    <div class="form-group col-sm-4">
                       {!! Form::label('day[' . $default->day . '][begin]', 'Beginn:', ['class'=>'sr-only']) !!}
                       {!! Form::time('day[' . $default->day . '][begin]', $default->begin, ['title'=>'begin', 'class'=>'form-control'] ) !!}
                    </div>
                    <div class="form-group  col-sm-4">
                       {!! Form::label('day[' . $default->day . '][end]', 'Ende:', ['class'=>'sr-only']) !!}
                       {!! Form::time('day[' . $default->day . '][end]', $default->end, ['title'=>'end', 'class'=>'form-control'] ) !!}
                    </div>
                    <div class="form-group col-sm-2">
                       {!! Form::label('day[' . $default->day . '][break]', 'Pause', ['class'=>'sr-only']) !!}
                       {!! Form::number('day[' . $default->day . '][break]', $default->break, ['title'=>'break', 'class'=>'form-control'] ) !!}
                    </div>
                </div>
            @endforeach
        @endif

        <div class="form-group">
           {!! Form::submit('Anlegen', ['class'=>'btn btn-primary'] ) !!}
        </div>
    {!! Form::close() !!}

@endsection