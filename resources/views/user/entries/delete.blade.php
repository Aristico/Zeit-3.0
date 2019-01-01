@extends('layouts.time')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Eintrag löschen</div>

                <div class="card-body">

                    <h1>Eintrag löschen</h1>

                    <p>Wollen Sie den Eintrag von {{$entry->dateCarbon()->format('d.m.Y')}} wirklich löschen</p>

                    <!-- bei Action den Controller und die Methode eintrage z.B. UserController@Create -->
                    {!! Form::open(['action'=>['EntryController@destroy', $entry->id], 'method' => 'DELETE']) !!}
                        {{csrf_field()}}
                        <input type="hidden" name="year" value="{{$entry->dateCarbon()->format('Y')}}">
                        <input type="hidden" name="month" value="{{$entry->dateCarbon()->format('m')}}">
                        <button class="btn btn-danger" name="command" value="yes">Ja</button>
                        <button class="btn btn-primary" name="command" value="no">Nein</button>
                    {!! Form::close() !!}

                </div>
            </div>
        </div>
    </div>


@endsection