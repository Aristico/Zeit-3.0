@extends('layouts.time')

@section('content')


   <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Arbeitszeiten bearbeiten</div>

                <div class="card-body">
                <schedule-form :schedule="{{ $schedule }}"></schedule-form>
                </div>
            </div>
        </div>
    </div>
@endsection
