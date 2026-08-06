@extends('agent.layouts.app')

@section('title', 'Create Package - Tour Raja Agent')

@section('content')
    <x-package-form 
        :is-admin="false"
        :package="null"
        action="{{ route('agent.packages.store') }}"
        method="POST"
        :themes="$themes ?? []"
        :holiday-types="$holidayTypes ?? []"
        :transits="$transits ?? []"
    />
@endsection