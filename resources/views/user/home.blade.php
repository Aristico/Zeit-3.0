@extends('layouts.time');

@section('content')

@include('includes.message')

 <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Links für die Zeiterfassung:</div>

                <div class="card-body">

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

                </div>
            </div>
        </div>
    </div>

@endsection