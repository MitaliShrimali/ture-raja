@extends('agent.layouts.app')

@section('title', 'Edit Package - Tour Raja Agent')

@section('content')
    <x-package-form 
        :is-admin="false"
        :package="$pkg"
        action="{{ route('agent.packages.update') }}"
        method="POST"
        :themes="$themes ?? []"
        :holiday-types="$holidayTypes ?? []"
        :transits="$transits ?? []"
    />
@endsection