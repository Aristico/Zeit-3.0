@extends('layouts.time')

@section('content')

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
                                        <td>{{number_format($entry->regular_hours, 2, ',', '')}}</td>
                                        <td>{{number_format($entry->actual_hours, 2, ',', '')}}</td>
                                        <td>{{number_format($entry->overtime, 2, ',', '')}}</td>
                                        <td>{{number_format($entry->balance, 2, ',', '')}}</td>
                                        <td>{{str_limit($entry->comment, 30)}}</td>
                                    @else
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    @endif
                                    <td>
                                        @if($isEditable)
                                            @if($entry->comment != 'no Entry')
                                                <a href="{{route('entries.edit', $entry->id)}}">Bearbeiten</a>
                                            @else
                                                <a href="{{route('entries.create', ['date'=>$entry->date])}}">Erstellen</a>
                                            @endif
                                        @else
                                            @if($entry->comment != 'no Entry')
                                                <a href="{{route('entries.edit', $entry->id)}}">Anzeigen</a>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>

@endsection
