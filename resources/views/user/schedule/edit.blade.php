@extends('layouts.time')

@section('content')


   <div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">Arbeitszeiten bearbeiten</div>

            <div class="card-body">

                    <!-- bei Action den Controller und die Methode eintrage z.B. UserController@Create -->
                    {!! Form::open(['action'=>['ScheduleController@update'], 'method' => 'put']) !!}
                        {{csrf_field()}}
                        <div class="row">
                            <p class="col-sm-4 offset-2">Anfang</p>
                            <p class="col-sm-4">Ende</p>
                            <p class="col-sm-2">Pause</p>
                        </div>

                        @If(count($schedule) > 0)
                            <schedule-inputs v-for="(day, key) in {{ $schedule }}" :singleday="day" :key="key"></schedule-inputs>
                        @endif

                        @include('includes.message')

                        @if(session('success'))
                           <button class="btn btn-primary" value="true" name="ready">Speichern</button>
                           <button class="btn btn-secondary" value="false" name="ready">Ändern</button>
                        @else
                           <button class="btn btn-primary" value="false" name="ready">Ändern</button>
                        @endif
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>
@endsection
