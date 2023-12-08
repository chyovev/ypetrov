@extends('public.works.layout')

@section('work-content')

    <h1 class="stickable" id="title">{!! $poem->title !!}</h1>
    <div id="dedication" @class(['none' => !$poem->dedication])>{!! nl2br($poem->dedication) !!}</div>
    <div id="body">{{ $poem->text }}</div>

@stop