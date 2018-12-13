@extends('layouts.time');

@section('content')

@include('includes.message')


<h3>Links für die Zeiterfassung:</h3>
<table class="table">
    <tbody>
        <tr>
            <td>Ankommen</td>
            <td><a target="_blank" href="{{'http://' . $_SERVER['SERVER_NAME'] . '/entries/' . Auth::user()->identifier . '/enter'}}">{{'http://' . $_SERVER['SERVER_NAME'] . '/entries/' . Auth::user()->identifier . '/enter'}}</a> </td>
        </tr>
        <tr>
            <td>Verlassen</td>
            <td><a target="_blank" href="{{'http://' . $_SERVER['SERVER_NAME'] . '/entries/' . Auth::user()->identifier . '/leave'}}">{{'http://' . $_SERVER['SERVER_NAME'] . '/entries/' . Auth::user()->identifier . '/leave'}}</a> </td>
        </tr>
    </tbody>
</table>


@endsection