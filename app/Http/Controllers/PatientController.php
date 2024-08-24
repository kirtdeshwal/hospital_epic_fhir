<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\EpicConnector;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xls;
use PhpOffice\PhpSpreadsheet\IOFactory;

use App\Helpers\Epic;
use Carbon\Carbon;

class PatientController extends Controller
{
    public function index(Request $request) {

        // $token ="eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJhdWQiOiJ1cm46b2lkOmZoaXIiLCJjbGllbnRfaWQiOiIxNTJhYmE3YS1hZTE0LTQ5M2MtYmEyZi0wYjJmNDA0NTNmNjMiLCJlcGljLmVjaSI6InVybjplcGljOk9wZW4uRXBpYy1jdXJyZW50IiwiZXBpYy5tZXRhZGF0YSI6InhlTm9qLTVsRmNpNlBQSG84dFBrbFEyNW1lVzJmb1JJU1VQWVVodHI4S3ZBU0VBMWE0TGhiVG1mYUlVeUNRLThsdEFnMUlfMDlETXhxeTU0Xy1GMnVEaVItZTI5OXpON1Q0YVZmY3lLa29qYTBEUDZHLThmeU95cm1HSXdiNGtNIiwiZXBpYy50b2tlbnR5cGUiOiJhY2Nlc3MiLCJleHAiOjE3MjM5MTAyODYsImlhdCI6MTcyMzkwNjY4NiwiaXNzIjoidXJuOm9pZDpmaGlyIiwianRpIjoiY2U2YmZiODItZWFiNC00NDE0LWFkNTgtZjFlY2ExZTJjNDk0IiwibmJmIjoxNzIzOTA2Njg2LCJzdWIiOiJlNmF3Ni1SSnVLTzJtYnFqbGVLdmdWUTMifQ.dlnzAgWKkLxbryfHbG2S6eYAm4p8EZv8eU66b_eOT8wV0To-tRF9o4fKm2SPUoN-x-Yrcqv_jUgqGxr0Od2kQnYPJF5TfICeaZYaXiHmr6JGvVj4dPHHd7FEjU_1A_d3iGK4l-gSrUk8YI2Qh8BFs3NoIPAWnsk_WAY2I5d39OpPJMigstlYogAY8wTCLnlT40fgh-C9FRPzFXb4nq_wAGQb7AcqhnwXwoUj87XNlyG-0q9HeHEkeII0Asia2cAlgTs_DREr38n1Eh-us8tmH-zKJYHRxrsqVox29b2dLeGBiJ7yZaOXq-5c8KFLvuSmXA94KrAjVECYp4QIPR_kAw";
        // $refresh_token = "eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJhdWQiOiJ1cm46b2lkOmZoaXIiLCJjbGllbnRfaWQiOiIxNTJhYmE3YS1hZTE0LTQ5M2MtYmEyZi0wYjJmNDA0NTNmNjMiLCJlcGljLmVjaSI6InVybjplcGljOk9wZW4uRXBpYy1jdXJyZW50IiwiZXBpYy5tZXRhZGF0YSI6IkZVbkw2ZnQyZlRhODBqTkFrT3RMWS1hR2RLMkRycDFoQm53NEhyalNQU1dCeWdZY2EzU0xHRGZ6RnVqTk40VGIwMnZpSjdTOFYwal9FNW1qcHpVdkFLVmxkaEkzbXRKcmVKcllNS3l5WmJnSnV2cXhBVVRrdVctekhFWi1sV2NOIiwiZXBpYy50b2tlbnR5cGUiOiJyZWZyZXNoIiwiaWF0IjoxNzIzOTAyMDgyLCJpc3MiOiJ1cm46b2lkOmZoaXIiLCJqdGkiOiI3OTI4MzQ0Mi00MjU1LTQwZjEtOGM5Ny0wZmQxNTE4OTk4ZjMiLCJuYmYiOjE3MjM5MDIwODIsInN1YiI6ImU2YXc2LVJKdUtPMm1icWpsZUt2Z1ZRMyJ9.Ez4WsdugmGY-EsbuQbt8sGLFnx0vyRgQQzbBOzWqKV5d-2R5WRTe3vG_U6KeBF7QGEpHsn7VcDjvFwOu0sa2JsLpAmIkkxVkE74IPB-Ag_1sUIerFTZL74QH1DpZ3_GdY22T2JhoSkNIRyyyXAUBUfwVEsDAKJ_9xz9EzrVGq5dDqT-UQGu5DUIe3eFq2LFqWsWOB_iXleg8jkEDUsvcg68jwSiXlSw9DeEcw1Ejn1mIFUIM3-Yp6SOZyPrIm1w8zPhmjqzswmkvwhlmq63tRsRt1UO5-95iq3wJ3lLI5WiI9fa4l8Pls6T_yh9CwGION7klWKCkWasNcjuiplo7eQ";
        // $epic = EpicConnector::where('user_id', auth()->user()->id)->first();
        // $epic->access_token = $token;
        // $epic->refresh_token = $refresh_token;
        // $epic->expires_on = date('Y-m-d H:i:s', strtotime('+55 minutes'));
        // $epic->save();
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

        $epicConnector = EpicConnector::where('user_id', auth()->user()->id)->first();
        
        if($epicConnector) {
            if($epicConnector->expires_on > date('Y-m-d H:i:s')) {
                $epic_connected = 1;
            } else {
                $epic_connected = 0;
            }
        } else {
            $epic_connected = 0;
        }

        return view('patients.index', compact('patients', 'epic_connected'));
    }

    public function upload_patients(Request $request) {
        $the_file = $request->file('patient_list');
        $spreadsheet = IOFactory::load($the_file->getRealPath());
        $sheet        = $spreadsheet->getActiveSheet();
        $row_limit    = $sheet->getHighestDataRow();
        $column_limit = $sheet->getHighestDataColumn();
        $row_range    = range( 2, $row_limit );
        $column_range = range( 'A', $column_limit );
        $startcount = 2;

        foreach ( $row_range as $index =>$row ) {
            $data_row = array();
            foreach(range('A', 'D') as $v){
                    
                array_push($data_row,$sheet->getCell( $v . $row )->getValue() );
                
            }

            if($data_row[0] == '')
            {
                break;
            }

            if($data_row[3] == 1) {
                $group = 'A';
            }
            if($data_row[3] == 2) {
                $group = 'B';
            }
            if($data_row[3] == 3) {
                $group = 'C';
            }
            if($data_row[3] == 4) {
                $group = 'D';
            }
            if($data_row[3] == 5) {
                $group = 'E';
            }
            if($data_row[3] == 6) {
                $group = 'F';
            }

            Patient::create([
                'first_name' => $data_row[0],
                'last_name' => $data_row[1],
                'dob' => date('Y-m-d', strtotime($data_row[2])),
                'group' => $group,
            ]);
        }

        return redirect(route('patients.index'));
    }

    public function epic_oauth(Request $request) {
        if($request->has('code')) {
            $response = Epic::getAccessToken($request->code);
            if(isset($response['access_token'])){
                $epic_user = EpicConnector::where('user_id', auth()->user()->id)->first();
                if($epic_user) {
                    $epic_user->access_token = $response['access_token']; 
                    $epic_user->refresh_token = $response['refresh_token'];
                    $epic_user->expires_on = date('Y-m-d H:i:s', strtotime('+30 minutes'));
                    $epic_user->save();
                } else {
                    EpicConnector::create([
                        'user_id' => auth()->user()->id,
                        'access_token' => $response['access_token'],
                        'refresh_token' => $response['refresh_token'],
                        'expires_on' => date('Y-m-d H:i:s', strtotime('+30 minutes'))
                    ]);
                }
            }

        }
        return redirect(route('patients.index'));
    }

    public function create_epic_oauth() {
        $epicDetails = EpicConnector::where('user_id', auth()->user()->id)->first();

        if($epicDetails) {
            Epic::updateAccessToken($epicDetails);
            return redirect(route('patients.index'));
        } else {
            $base_url = "https://fhir.epic.com/interconnect-fhir-oauth/oauth2/authorize";
            $params = [
                'response_type' => 'code',
                // 'redirect_uri' => 'https://ebd9-2409-40d1-3-480a-1449-88f2-a6cd-3cfe.ngrok-free.app',
                'redirect_uri' => 'http://127.0.0.1:8000',
                'scope' => 'Openid Connect id_tokens',
                'state' => 'xyz',
                // 'client_id' => '85fa0b7e-2e7f-4aed-8540-770a15b86874'
                'client_id' => '152aba7a-ae14-493c-ba2f-0b2f40453f63'
            ];
            $query_string = http_build_query($params);
            $full_url = $base_url . '?' . $query_string;
            return redirect($full_url);
        }
    }

    public function getEpicPatient($id) {
        $epicPatient = Epic::getPatientDetails($id);
        
        return view('patients.epic_patient', compact('epicPatient'));
    }

    public function patientProcedures($id) {
        $patientProcedures = Epic::getPatientProcedures($id);
        if(isset($patientProcedures['entry'])) {
            $procedure = $patientProcedures['entry'][0];
        } else {
            $procedure = null;
        }
        $patient_id = $id;  
        return view('patients.epic_patient_procedures', compact('procedure' , 'patient_id'));
    }

    public function getProcedure($id) {
        $procedure = Epic::getProcedure($id);
        dd($procedure);
    }
}
