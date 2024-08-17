<x-app-layout>
    <x-slot name="header">
        <div class="row justify-content-between">
            <div class="col-auto">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Dashboard') }}
                </h2>
            </div>
            <div class="col-auto">
                @if($epic_connected)
                <button class="btn btn-success"><i>epic</i> FHIR Connected</button>
                @else
                <a href="{{ url('/patients/get-epic-access-token') }}" class="btn btn-danger">Connect To <i>epic</i> FHIR</a>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="row justify-content-between">
                        <div class="col-auto">Patients</div>
                        <div class="col-auto row">
                            <div class="col-auto"><button data-bs-toggle="modal" data-bs-target="#uploadModal" class="btn btn-secondary">Upload</button></div>
                            <div class="col-auto">
                                <form action="" method="GET">
                                    <div class="row">
                                        <div class="col-auto"><input type="text" name="search_patient" value="{{ Request::get('search_patient') }}" class="form-control" placeholder="Search Patient"></div>
                                        <div class="col-auto"><input type="text" name="search_group" value="{{ Request::get('search_group') }}" class="form-control" placeholder="Search Group"></div>
                                        <div class="col-auto"><button type="submit" class="btn btn-primary">Search</button></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Date of Birth</th>
                                <th>Group</th>
                                <th>Epic ID</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($patients as $patient)
                            <tr>
                                <td>{{ $patient->first_name }}</td>
                                <td>{{ $patient->last_name }}</td>
                                <td>{{ date('m-d-Y', strtotime($patient->dob)) }}</td>
                                <td>{{ $patient->group }}</td>
                                <td>
                                    {{ $patient->epic_fhir_id }} 
                                    @if($patient->epic_fhir_id)
                                    <a href="{{ route('patients.epic-patient', $patient->epic_fhir_id) }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                    </svg></a>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="uploadModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Upload Patients CSV</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('patients.upload') }}" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    @csrf 
                    <div class="form-control">
                        <label>Select Patient List</label>
                        <input type="file" name="patient_list" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
                </form>
            </div>
        </div>
    </div>

</x-app-layout>

