@extends('layouts.time')

@section('content')

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Elle bisherigen Einträge</div>

                <div class="card-body">

                    <table class="table">
                        <thead>
                            <tr>
                                <th>Datum</th>
                                <th>Arbeitszeit</th>
                                <th>Andwesend</th>
                                <th>Abweichung</th>
                                <th>Saldo</th>
                                <th>Bemerkung</th>
                                <th>Aktionen</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($entries as $entry)
                                <tr class="
                                    @if($entry->comment == 'no Entry')
                                        {{'alert alert-warning'}}
                                    @endif
                                ">
                                    <td>{{$entry->dateCarbon()->format('d.m.Y')}}</td>
                                    @if($entry->comment != 'no Entry')
                                        <td>{{$entry->regular_hours}}</td>
                                        <td>{{$entry->actual_hours}}</td>
                                        <td>{{$entry->overtime}}</td>
                                        <td>{{$entry->balance}}</td>
                                        <td>{{str_limit($entry->comment, 30)}}</td>
                                    @else
                                        <td></td><td></td><td></td><td></td><td></td>
                                    @endif
                                    <td>@if($entry->comment != 'no Entry')
                                            <a href="{{route('entries.edit', $entry->id)}}">Bearbeiten</a>
                                        @else
                                            <a href="{{route('entries.create', ['date'=>$entry->date])}}">Erstellen</a>
                                        @endif</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


@endsection