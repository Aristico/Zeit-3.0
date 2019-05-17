@extends('layouts.time')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Folgende Salden haben sind für die letzten Monate gespeichert</div>

                <div class="card-body">

                    <table class="table">
                        <thead>
                            <tr>
                                <th>Monat</th>
                                <th>Saldo</th>
                                <th>Aktion</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($entries as $entry)
                                <tr>
                                    <td>{{$monthes[$entry->dateCarbon()->format('n')]}} {{$entry->dateCarbon()->format('Y')}} </td>
                                    <td>{{number_format($entry->balance, 2, ',', '')}}</td>
                                    <td>
                                        <a href="{{route('entries.index.month', ['year'=>$entry->dateCarbon()->format('Y'), 'month'=>$entry->dateCarbon()->format('n')])}}">Anzeigen</a>
                                        @if($entry->isCorrectable)
                                            <a href="{{route('entries.correction.add', $entry->id)}}">
                                                @if($entry->correction != null)
                                                    || Auszahlung Ändern
                                                @else ($entry->correction = null)
                                                    || Überstunden Auszahlen</a>
                                                @endif
                                            </td>
                                        @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>


@endsection
