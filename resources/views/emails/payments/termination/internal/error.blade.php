@php
    /**
    * @var $user App\User
    */
@endphp

@component('mail::message')
    <p>username: {{ $user->nickname }}</p>
    <p>id: {{ $user->id }}</p>
    <p>contract termination date: {{ $endDate->format("d.m.Y") }} </p>
    <p>reason: {{ $reason }}</p>
    <br>
    <br>
    <p><b>ERROR: "{{ $message }}"</b></p>
@endcomponent
