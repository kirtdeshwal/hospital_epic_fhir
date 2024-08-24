<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;
use App\Models\EpicConnector;

class Epic {
    public static function getAccessToken($code) {
        // $sandbox_client_id = "85fa0b7e-2e7f-4aed-8540-770a15b86874";
        $sandbox_client_id = "152aba7a-ae14-493c-ba2f-0b2f40453f63";
        // $sandbox_client_secret = "cUOl5aCaodTL/+Q09WkrPLBNeR10H4pieV/hem5TMsa7w1lZ2a9qGN791Xn0XwXm9F73vSLi/kWF9mimlQlcZA==";
        $sandbox_client_secret = "o/dtB2WKOkL0JqRkyVzwsWL8tL+E8G5ehtIUwSSoEE0dLW9+bwxireaIdSnI4kKCwIlT3YqaCwXzX21ytQyTmQ==";
        $redirect_uri = "http://127.0.0.1:8000";
        // $redirect_uri = "https://ebd9-2409-40d1-3-480a-1449-88f2-a6cd-3cfe.ngrok-free.app";

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://fhir.epic.com/interconnect-fhir-oauth/oauth2/token',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => 'grant_type=authorization_code&code='.$code.'&redirect_uri='.$redirect_uri.'&client_id='.$sandbox_client_id.'&client_secret='.$sandbox_client_secret,
        CURLOPT_HTTPHEADER => array(
            'Content-Type: application/x-www-form-urlencoded',
            'Cookie: EpicPersistenceCookie=!FT+h+1/kNf3nlLDJPAXLMgv89DqeonSB7uNbqHpTKE1SxygKvFoL1+ClKhbP/vbvzsn55fFn7uhiw9w='
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        $response = json_decode($response);

        return $response;
    }

    public static function getPatientDetails($id) {
        $epic_details = EpicConnector::where('user_id', auth()->user()->id)->first();

        $response = Http::withOptions([
            'verify' => false, // Disable SSL certificate verification
        ])->withHeaders([
            'Authorization' => 'Bearer ' . $epic_details->access_token
        ])->get('https://fhir.epic.com/interconnect-fhir-oauth/api/FHIR/R4/Patient/' . $id);

        $responseBody = $response->body();
        $xmlObject = simplexml_load_string($responseBody);
        $jsonString = json_encode($xmlObject);
        $phpArray = json_decode($jsonString, true);
        return $phpArray;
    }

    public static function getProcedureDetails($id) {
        $epic_details = EpicConnector::where('user_id', auth()->user()->id)->first();

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://fhir.epic.com/interconnect-fhir-oauth/api/FHIR/R4/Procedure/'.$id,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer '.$epic_details->access_token,
            'Cookie: EpicPersistenceCookie=!FT+h+1/kNf3nlLDJPAXLMgv89DqeonSB7uNbqHpTKE1SxygKvFoL1+ClKhbP/vbvzsn55fFn7uhiw9w='
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $response = json_decode($response);
        dd($response);
        return $response;
    }

    public static function updateAccessToken($request) {
        $sandbox_client_id = "152aba7a-ae14-493c-ba2f-0b2f40453f63";
        $sandbox_client_secret = "o/dtB2WKOkL0JqRkyVzwsWL8tL+E8G5ehtIUwSSoEE0dLW9+bwxireaIdSnI4kKCwIlT3YqaCwXzX21ytQyTmQ==";
        // dd($request);
        $response = Http::withHeaders([
            'Accept' => 'application/x-www-form-urlencoded',
        ])->post('https://fhir.epic.com/interconnect-fhir-oauth/oauth2/token', [
            'grant_type' => 'refresh_token',
            'refresh_token' => $request->refresh_token,
            'client_id' => $sandbox_client_id,
            'client_secret' => $sandbox_client_secret
        ]);

        dd($response);
    }

    public static function getPatientProcedures($id) {
        $epic_details = EpicConnector::where('user_id', auth()->user()->id)->first();

        $response = Http::withOptions([
            'verify' => false, // Disable SSL certificate verification
        ])->withHeaders([
            'Authorization' => 'Bearer ' . $epic_details->access_token
        ])->get('https://fhir.epic.com/interconnect-fhir-oauth/api/FHIR/R4/Procedure?patient=' . $id);

        $responseBody = $response->body();
        $xmlObject = simplexml_load_string($responseBody);
        $jsonString = json_encode($xmlObject);
        $phpArray = json_decode($jsonString, true);
        return $phpArray;
    } 

    public static function getProcedure($id) {
        $epic_details = EpicConnector::where('user_id', auth()->user()->id)->first();

        $response = Http::withOptions([
            'verify' => false, // Disable SSL certificate verification
        ])->withHeaders([
            'Authorization' => 'Bearer ' . $epic_details->access_token
        ])->get('https://fhir.epic.com/interconnect-fhir-oauth/api/FHIR/R4/Procedure/' . $id);

        $responseBody = $response->body();
        $xmlObject = simplexml_load_string($responseBody);
        $jsonString = json_encode($xmlObject);
        $phpArray = json_decode($jsonString, true);
        return $phpArray;
    }
}