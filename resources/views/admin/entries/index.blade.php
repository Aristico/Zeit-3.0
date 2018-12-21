@extends('layouts.time')

@section('content')

    <table class="table">
        <thead>
            <tr>
                <th>Datum</th>
                <th>Arbeitszeit</th>
                <th>Pause</th>
                <th>Dauer (Regulär)</th>
                <th>Abweichung</th>
                <th>Saldo</th>
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
                    <td>@if($entry->comment != 'no Entry'){{$entry->beginCarbon()->format('H:i') . ' - ' . $entry->endCarbon()->format('H:i') . ' Uhr'}}@endif</td>
                    <td>@if($entry->comment != 'no Entry'){{$entry->break}}@endif</td>
                    <td>@if($entry->comment != 'no Entry'){{$entry->actual_hours}} ({{$entry->regular_hours}})@endif</td>
                    <td>@if($entry->comment != 'no Entry'){{$entry->overtime}}@endif</td>
                    <td>@if($entry->comment != 'no Entry'){{$entry->balance}}@endif</td>
                    <td>@if($entry->comment != 'no Entry')
                            <a href="{{route('entries.edit', $entry->id)}}">Bearbeiten</a>
                        @else
                            <a href="{{route('entries.create', ['date'=>$entry->date])}}">Erstellen</a>
                        @endif</td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection