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

class PatientController extends Controller
{
    public function index(Request $request) {
        $patients = Patient::latest();

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
        $base_url = "https://fhir.epic.com/interconnect-fhir-oauth/oauth2/authorize";
        $params = [
            'response_type' => 'code',
            'redirect_uri' => 'http://127.0.0.1:8000',
            'scope' => 'Openid Connect id_tokens',
            'state' => 'xyz',
            'client_id' => '152aba7a-ae14-493c-ba2f-0b2f40453f63'
        ];
        $query_string = http_build_query($params);
        $full_url = $base_url . '?' . $query_string;
        return redirect($full_url);
    }

    public function getEpicPatient($id) {
        $epicPatient = Epic::getPatientDetails();
        return view('patients.epic_patient', compact('epicPatient'));
    }
}
