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
                    Patient Procedures @if($procedure) - {{ $procedure['resource']['Procedure']['subject']['display']['@attributes']['value'] }} @endif - ({{ $patient_id }})
                </div>
                <div class="p-4">
                    @if($procedure)
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Procedure Name</th>
                                <th>Procedure ID</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ $procedure['resource']['Procedure']['category']['text']['@attributes']['value'] }}</td>
                                <td>{{ $procedure['resource']['Procedure']['id']['@attributes']['value'] }} 
                                    <a href="{{ route('patients.procedure', $procedure['resource']['Procedure']['id']['@attributes']['value']) }}">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                        </svg>
                                    </a>
                                </td>
                                <td>{{ $procedure['resource']['Procedure']['performedDateTime']['@attributes']['value'] }}</td>
                                <td>{{ $procedure['resource']['Procedure']['status']['@attributes']['value'] }}</td>
                            </tr>
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

