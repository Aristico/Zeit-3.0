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
                <tr>
                    <td>{{$entry->dateCarbon()->format('d.m.Y')}}</td>
                    <td>{{$entry->beginCarbon()->format('H:i') . ' - ' . $entry->endCarbon()->format('H:i') . ' Uhr'}}  </td>
                    <td>{{$entry->break}}</td>
                    <td>{{$entry->actual_hours}} ({{$entry->regular_hours}})</td>
                    <td>{{$entry->overtime}}</td>
                    <td>{{$entry->balance}}</td>
                    <td><a href="{{route('entries.edit', $entry->id)}}">Bearbeiten</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection