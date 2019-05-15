@extends('layouts.time')

@section('content')

       <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Arbeitszeiten eintragen</div>

                <div class="card-body">

                    <!-- bei Action den Controller und die Methode eintrage z.B. UserController@Create -->
                    {!! Form::open(['action'=>'ScheduleController@store', 'method' => 'post']) !!}
                        {{csrf_field()}}

                <schedule-form :schedule="{{ $schedule }}" :userid="{{ $id }}"></schedule-form>
                    {!! Form::close() !!}
                    <!-- bei Action den Controller und die Methode eintrage z.B. UserController@Create -->
                </div>
            </div>
        </div>
    </div>


<!--



-->

@endsection

