@extends('layouts.admin')

@section('title', 'Edit Package - Tour Raja Admin')

@section('content')
    <x-package-form 
        :is-admin="true"
        :package="$pkg"
        action="{{ url('/admin/packages/update') }}"
        method="POST"
        :themes="$themes ?? []"
        :holiday-types="$holidayTypes ?? []"
        :transits="$transits ?? []"
        :agents="$agents ?? []"
    />
@endsection