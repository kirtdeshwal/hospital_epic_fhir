<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function getPatients(Request $request) {
        $patients = Patient::latest();

        if($request->has('datetimes') && $request->datetimes != '') {
            [$startDateTime, $endDateTime] = explode(' - ', $request->datetimes);

            $start = Carbon::createFromFormat('Y/m/d h:i A', $startDateTime);
            $end = Carbon::createFromFormat('Y/m/d h:i A', $endDateTime);
            $patients = $patients->whereBetween('consultation_date', [$start, $end]);
        }

        if($request->has('search_patient') && $request->search_patient != '') {
            $key = $request->search_patient;
            $patients = $patients->where(function($query) use($key) {
                $query->where('first_name', 'LIKE', '%'.$key.'%')
                ->orWhere('last_name', 'LIKE', '%'.$key.'%');
            });
        }

        if($request->has('search_group') && $request->search_group != '') {
            $patients = $patients->where('group', $request->search_group);
        }

        $patients = $patients->orderBy('id', 'desc')->get();

        return response()->json([
            'patients' => $patients
        ]);
    }

    public function getEpicPatient($patient_id) {
        $epicPatient = Epic::getPatientDetails($patient_id);
        
        return response()->json([
            'patient_details' => $epicPatient
        ]);
    }

    public function patientProcedures($patient_id) {
        $patientProcedures = Epic::getPatientProcedures($patient_id);
        if(isset($patientProcedures['entry'])) {
            $procedure = $patientProcedures['entry'][0];
        } else {
            $procedure = null;
        }

        return response()->json([
            'patient_id' => $patient_id,
            'patient_procedures' => $procedure
        ]);
    }

    public function getProcedure($procedure_id) {
        $procedure = Epic::getProcedure($procedure_id);

        return response()->json([
            'procedure' => $procedure
        ]);
    }
}
