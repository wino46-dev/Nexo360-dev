<?php

namespace App\Services;

use App\Models\ControlError;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Exception;


class Sh360OcrService
{
    public $hotel_id = null;
    public $api_url = null;
    public $api_user = null;
    public $api_password = null;

    public function __construct($hotel_id)
    {
        $this->hotel_id = $hotel_id;

        $this->api_url = env('SH360_ORC_API_URL');
        $this->api_user = env('SH360_ORC_API_USER');
        $this->api_password = env('SH360_ORC_API_PASSWORD');
    }

    public function login()
    {
        Log::info('sh360Ocr Login');
        $response = Http::withHeaders([
            'accept' => 'application/json',
        ])->post($this->api_url . '/login', [
            'username' => $this->api_user,
            'password' => $this->api_password
        ]);

        $data = $response->json();

        return $data;
    }

    public function callApi($method, $url, $data = null)
    {
        /*$token = Cache::remember('sh360_token_' . $this->hotel_id, 300, function () {
            $login = $this->login();
            return $login['access_token'] ?? null;
        });*/
        $login = $this->login();
        $token = $login['access_token'] ?? null;
        Log::info('token');
        Log::info($token);
        //$token = '123';

        if ($method == 'get') {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token
            ])->get($this->api_url . $url);
        } elseif ($method == 'post') {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token
            ])->post($this->api_url . $url, $data);
        } elseif ($method == 'patch') {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token
            ])->patch($this->api_url . $url, $data);
        } elseif ($method == 'put') {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $token
            ])->patch($this->api_url . $url, $data);
        } else {
            echo 'metodo no definido';
            die;
        }

        $statusCode = $response->status();
        $res = $response->json();

        if ($statusCode == '200') {
            return $res;
        } elseif ($statusCode == '400') {

            Cache::forget('sh360_token_' . $this->hotel_id);

            $res = $response->json();
            $res['error'] = 'error';
            $res['message'] = $res['description'];

            ControlError::create([
                'origen' => 'Sh360OrcService',
                'tipo' => $statusCode,
                'mensaje' => ($res['name'] ?? 'callApi Error') . ' - ' . ($res['description'] ?? '-'),
                'descripcion' => json_encode($response->json()),
                'establecimiento_id' => $this->hotel_id ?? null
            ]);

            return $res;
        } else {
            Cache::forget('sh360_token_' . $this->hotel_id);
            
            ControlError::create([
                'origen' => 'Sh360OrcService',
                'tipo' => $statusCode,
                'mensaje' => ($res['name'] ?? 'callApi Error'),
                'descripcion' => json_encode($response->body()),
                'establecimiento_id' => $this->hotel_id ?? null
            ]);
            throw new Exception($statusCode . ' ' . ($res['name'] ?? 'callApi Error'));
        }
    }

    public function getData($data)
    {
        $photo1 = '';
        $photo2 = '';

        if (!empty($data[0])) {
            if (!empty($data[0]->respuesta_texto)) {
                $partes = explode(',', $data[0]->respuesta_texto);
                $photo1 = $partes[1];
            }
        }
        if (!empty($data[1])) {
            if (!empty($data[1]->respuesta_texto)) {
                $partes = explode(',', $data[1]->respuesta_texto);
                $photo2 = $partes[1];
            }
        }
        $new_data = [
            'model' => 'SH360',
            'files' => [
                $photo1,
                $photo2
            ]
        ];
        
        $res = $this->callApi('post', '/process-documents', $new_data);
        if (!empty($res['result_formated'])) {

            /* Ajuste documento para españa*/ 
            if (
                isset($res['result_formated']['tipo_documento']) &&
                $res['result_formated']['pais_documento'] == 'ESP'
            ) {
                $res['result_formated']['tipo_documento'] = 6;
            }
        }        
        Log::info($res);        
        return $res;
    }
}
