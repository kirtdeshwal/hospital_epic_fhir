<x-app-layout>
    <x-slot name="header">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Dashboard') }}
                </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="row justify-content-between">
                        <div class="col-auto">Patient Details</div>
                        <div class="col-auto"><a href="{{ route('patients.procedures', $epicPatient['id']['@attributes']['value']) }}" class="btn btn-primary">Patient Procedures</a></div>
                    </div>
                </div>
                <div class="p-4">
                <div class="row">
                    <div class="form-group col-md-6">
                        <label>Patient FHIR ID</label>
                        <input type="text" class="form-control" disabled value="{{ $epicPatient['id']['@attributes']['value'] }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Gender</label>
                        <input type="text" class="form-control" disabled value="{{ $epicPatient['gender']['@attributes']['value'] }}">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="form-group col-md-6">
                        <label>Race</label>
                        <input type="text" class="form-control" disabled value="{{ $epicPatient['extension'][2]['extension'][0]['valueCoding']['display']['@attributes']['value'] }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Name</label>
                        <input type="text" class="form-control" disabled value="{{ $epicPatient['name'][0]['text']['@attributes']['value'] }}">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="form-group col-md-6">
                        <label>Phone</label>
                        <input type="text" class="form-control" disabled @if(isset($epicPatient['telecom'][0]['value'])) value="{{ $epicPatient['telecom'][0]['value']['@attributes']['value'] }}" @else value="{{ $epicPatient['telecom']['value']['@attributes']['value'] }}" @endif>
                    </div>
                    <div class="form-group col-md-6">
                        <label>Date of Birth</label>
                        <input type="text" class="form-control" disabled value="{{ $epicPatient['birthDate']['@attributes']['value'] }}">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="form-group col-md-6">
                        <label>Address 1</label>
                        <input type="text" class="form-control" disabled value="{{ $epicPatient['address'][0]['line'][0]['@attributes']['value'] }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Address 2</label>
                        <input type="text" class="form-control" disabled @if(isset($epicPatient['address'][0]['line'][1])) value="{{ $epicPatient['address'][0]['line'][1]['@attributes']['value'] }}" @endif>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="form-group col-md-6">
                        <label>City</label>
                        <input type="text" class="form-control" disabled value="{{ $epicPatient['address'][0]['city']['@attributes']['value'] }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label>State</label>
                        <input type="text" class="form-control" disabled value="{{ $epicPatient['address'][0]['state']['@attributes']['value'] }}">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="form-group col-md-6">
                        <label>Country</label>
                        <input type="text" class="form-control" disabled value="{{ $epicPatient['address'][0]['country']['@attributes']['value'] }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Zipcode</label>
                        <input type="text" class="form-control" disabled value="{{ $epicPatient['address'][0]['postalCode']['@attributes']['value'] }}">
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="form-group col-md-6">
                        <label>Marital Status</label>
                        <input type="text" class="form-control" disabled value="{{ $epicPatient['maritalStatus']['text']['@attributes']['value'] }}">
                    </div>
                    <div class="form-group col-md-6">
                        <label>Managing Organization</label>
                        <input type="text" class="form-control" disabled value="{{ $epicPatient['managingOrganization']['display']['@attributes']['value'] }}">
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

