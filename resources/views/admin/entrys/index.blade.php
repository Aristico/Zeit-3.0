@extends('layouts.time')

@section('content')

    <table class="table">
        <thead>
            <tr>
                <th>Datum</th>
                <th>Anfang</th>
                <th>Ende</th>
                <th>Pause</th>
                <th>Arbeitszeit (Regulär)</th>
                <th>Abweichung</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>
            @foreach($entries as $entry)
                <tr>
                    <td>{{$entry->date}}</td>
                    <td>{{$entry->begin}}  </td>
                    <td>{{$entry->end}}</td>
                    <td>{{$entry->break}}</td>
                    <td>{{$entry->actual_hours}} ({{$entry->regular_hours}})</td>
                    <td>{{$entry->overtime}}</td>
                    <td>{{$entry->balance}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

@endsection