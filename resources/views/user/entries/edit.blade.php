@extends('layouts.time')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Eintrag bearbeiten</div>

                <div class="card-body">

                    <p class="alert alert-info">Sie Bearbeiten hier den Eintrag vom <b>{{$entry->dateCarbon()->format('d.m.Y')}}</b>. Die reguläre Arbeitszeit
                       beträgt {{number_format($entry->regular_hours, 2, ',', '')}} Stunden und aktuell sind {{number_format($entry->actual_hours, 2, ',', '')}} Stunden vermerkt. Daraus ergibt sich
                       eine Abweichung von {{number_format($entry->overtime, 2, ',', '')}} und ein Überstunden-Saldo von {{number_format($entry->balance, 2, ',', '')}} Stunden.</p>

                    <!-- bei Action den Controller und die Methode eintrage z.B. UserController@Create -->
                    {!! Form::model($entry, ['action'=>['EntryController@update', $entry->id], 'method' => 'PUT']) !!}
                        {{csrf_field()}}

                        <input type="hidden" name="month" value="{{$entry->dateCarbon()->format('m')}}">
                        <input type="hidden" name="year" value="{{$entry->dateCarbon()->format('Y')}}">

                        <div class="form-group">
                           {!! Form::label('begin', 'Anfang') !!}
                           {!! Form::time('begin', $entry->beginCarbon()->format('H:i'), ['title'=>'begin', 'class'=>'form-control', $entry->isEditable ? '' : 'readonly'] ) !!}
                        </div>
                        @include('includes.error', ['field'=>'begin'])

                        <div class="form-group">
                           {!! Form::label('end', 'Ende') !!}
                           {!! Form::time('end', $entry->endCarbon()->format('H:i'), ['title'=>'end', 'class'=>'form-control', $entry->isEditable ? '' : 'readonly'] ) !!}
                        </div>
                        @include('includes.error', ['field'=>'end'])

                        <div class="form-group">
                           {!! Form::label('break', 'Pause') !!}
                           {!! Form::number('break', null, ['title'=>'break', 'class'=>'form-control', $entry->isEditable ? '' : 'readonly'] ) !!}
                        </div>
                        @include('includes.error', ['field'=>'break'])

                        <div class="form-group">
                           {!! Form::label('comment', 'Kommentar:') !!}
                           {!! Form::text('comment', null, ['title'=>'comment', 'class'=>'form-control', $entry->isEditable ? '' : 'readonly'] ) !!}
                        </div>
                        @include('includes.errors')
                        @if($entry->isEditable)
                            <button class="btn btn-primary" name="command" value="save">Speichern</button>
                            <button class="btn btn-danger" name="command" value="delete">Eintrag Löschen</button>
                        @endif
                    {!! Form::close() !!}

                    @if(!$entry->isEditable)
                        <a class="btn btn-primary" href="{{route('entries.index.month', ['year'=>$entry->dateCarbon()->format('Y'), 'month'=>$entry->dateCarbon()->format('n')])}}">OK</a>
                    @endif
                </div>
            </div>
        </div>
    </div>



@endsection
