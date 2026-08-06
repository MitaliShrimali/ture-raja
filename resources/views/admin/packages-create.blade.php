@extends('layouts.admin')

@section('title', 'Add New Package - Tour Raja Admin')

@section('content')
    <x-package-form 
        :is-admin="true"
        action="{{ url('/admin/packages/store') }}"
        method="POST"
        :themes="$themes ?? []"
        :holiday-types="$holidayTypes ?? []"
        :transits="$transits ?? []"
        :agents="$agents ?? []"
    />
@endsection