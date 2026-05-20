<?php

namespace App\Services;


use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;


class AliceService
{

    public static function loginToken()
    {
        
        $url = env('ALICE_API_URL');;

        $apiKey = env('ALICE_API_KEY');

        $response = Http::withHeaders([
            'apikey' => $apiKey,
        ])->get($url . '/onboarding/login_token');

        Log::info('call loginToken');
        $data = $response->json();
        if(!empty($data['token'])){
            Log::info('loginToken:' . $data['token']);
            return $data['token'];
        }else{            
            Cache::forget('login_token');            
            Log::error('error LoginToken:');
            Log::error($data);
            return false;
        }

    }

    public static function loginTokenCache()
    {
        $login_token = Cache::remember('login_token', 3600, function () {

            $login_token = self::loginToken();
            return $login_token;
        });
        if (!$login_token) {
            Cache::forget('login_token');
            return false;
        }
        return $login_token;
    }



    public static function backendToken($login_token)
    {
        
        $url = env('ALICE_API_URL');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $login_token,
        ])->get($url . '/onboarding/backend_token');

        $data = $response->json();
        Log::info('call backendToken');

        if(!empty($data['token'])){
            Log::info('backendToken:' . $data['token']);            
            return $data['token'];
        }else{                        
            Cache::forget('backend_token');
            Log::error('error backendToken:');
            Log::error($data);
            return false;
        }
        
    }

    public static function backendTokenCache()
    {

        $login_token = self::loginTokenCache();

        $backend_token = Cache::remember('backend_token', 3600, function () use ($login_token) {

            $backend_token = self::backendToken($login_token);
            return $backend_token;
        });

        if (!$backend_token) {            
            //Cache::forget('login_token');
            Cache::forget('backend_token');
            return false;
        }
        return $backend_token;
    }

    public static function userCreate($backend_token)
    {
        
        $url = env('ALICE_API_URL');

        /*$info =  [
            'email' => 'oscar.huertas@neotech360.com',
        ];*/
        $email = 'oscar.huertas@neotech360.com';

        /*$response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $backend_token,
        ])->attach(
            'email', $email
        )->post($url. '/onboarding/user');*/

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $backend_token,
        ])->post($url . '/onboarding/user');

        Log::info('call userCreate');
        $data = $response->json();
        if(!empty($data['user_id'])){
            Log::info('userCreate:');
            Log::info($data);
            return $data;
        }else{
            Log::error('error userCreate');
            Log::error($data);
            return false;
        }
        
        
        return $data;
    }

    public static function userIdCache($id)
    {

        $user_id = Cache::remember('alice_user_id_' . $id, 3600, function () {

            $backend_token = self::backendTokenCache();

            $res_user = AliceService::userCreate($backend_token);
            return $res_user['user_id'] ?? false;
        });
        if (empty($user_id)) {
            Log::info('limpia alice_user_id_'.$id);
            Cache::forget('alice_user_id_' . $id);
            return false;
        }
        return $user_id;
    }


    public static function userToken($login_token, $user_id)
    {
        Log::info('call userToken');
        

        $url = env('ALICE_API_URL');

        $url = $url . '/onboarding/user_token/' . $user_id;

        $response = Http::withHeaders([
            'accept' => 'application/json',
            // 'Cache-Control' => 'use-cache',
            'Authorization' => 'Bearer ' . $login_token,
        ])->get($url);

        $data = $response->json();

        if(!empty($data['token'])){
            Log::info('userToken: '. $data['token']);            
            return $data['token'];
        }else{            
                        
            Log::error('error userToken:');
            Log::error($data);
            return false;
        }
        
        
        //return $data;
    }

    public static function userTokenCache($user_id)
    {

        $user_token = Cache::remember('alice_user_token_' . $user_id, 3600, function () use ($user_id) {
            Log::info('call alice_user_token_'.$user_id);
            $login_token = self::loginTokenCache();
            
            $user_token = AliceService::userToken($login_token, $user_id);
            
            return $user_token;
        });

        if (empty($user_token)) {
            Log::info('limpia alice_user_token_'.$user_id);
            Cache::forget('alice_user_token_' . $user_id);
            return false;
        }

        return $user_token;
    }


    public static function documents($backend_token)
    {

        $url = env('ALICE_API_URL');

        $url = $url . '/onboarding/documents/supported/';
        
        $response = Http::withHeaders([
           // 'accept' => 'application/json',
            // 'Cache-Control' => 'use-cache',
            'Authorization' => 'Bearer ' . $backend_token,
        ])->get($url);

        $data = $response->json();
        return $data;
    }

    public static function documentCreate($user_token, $document_type, $country)
    {

        $url = env('ALICE_API_URL');

        $url = $url . '/onboarding/user/document';

        $response = Http::withHeaders([
            //'accept' => 'application/json',
            // 'Cache-Control' => 'use-cache',
            'Authorization' => 'Bearer ' . $user_token,
            'Content-Type' => 'multipart/form-data'
        ])->asForm()->post($url, [
            'type' => strtolower($document_type),
            'issuing_country' => $country,
        ]);
        $data = $response->json();
        return $data;
    }

    public static function backendTokenUser($user_id)
    {
        
        //$backend_token = self::backendTokenCache();
        //$user_id = AliceService::userIdCache($id);
        Log::info('user_id: ' . $user_id);
        
        $login_token = self::loginTokenCache();
        Log::info('login_token: ' . $login_token);

        $url = env('ALICE_API_URL');

        $url = $url . '/onboarding/backend_token/'.$user_id;

        $response = Http::withHeaders([
            'accept' => 'application/json',
            // 'Cache-Control' => 'use-cache',
            'Authorization' => 'Bearer ' . $login_token,
            'Content-Type' => 'multipart/form-data'
        ])->get($url);

        $data = $response->json();

        if(!empty($data['token'])){
            Log::info('backendTokenUser: '. $data['token']);            
            return $data['token'];
        }else{                         
            Log::error('error backendTokenUser:');
            Log::error($data);
            return false;
        }
        return $data;
    }

    public static function backendTokenUserCache($user_id)
    {

        $backend_token_user = Cache::remember('alice_backend_token_user_' . $user_id, 3600, function () use ($user_id) {
            
            $backend_token_user = self::backendTokenUser($user_id);           
                        
            return $backend_token_user;
        });

        if (empty($backend_token_user)) {
            Log::info('limpia alice_backend_token_user_'.$user_id);
            Cache::forget('alice_backend_token_user_' . $user_id);
            return false;
        }

        return $backend_token_user;
    }

    public static function userReport($checkin_id)
    {

        $user_id = self::userIdCache($checkin_id);
        $backend_token = self::backendTokenUserCache($user_id);

        $url = env('ALICE_API_URL');

        $url = $url . '/onboarding/user/report';

        $response = Http::withHeaders([
            'accept' => 'application/json',
            // 'Cache-Control' => 'use-cache',
            'Authorization' => 'Bearer ' . $backend_token,
            'Content-Type' => 'multipart/form-data'
        ])->get($url);

        $data = $response->json();
        Log::info('userReport:');
        Log::info($data);
        return $data;
    }

    public static function media($checkin_id, $media, $extension)
    {

        $user_id = self::userIdCache($checkin_id);
        $backend_token = self::backendTokenUserCache($user_id);

        //$url = env('ALICE_API_URL');

        //$url = $url . '/onboarding/media/'.$media.'/download';
        $url = $media;

        $response = Http::withHeaders([
            'accept' => 'application/json',
            // 'Cache-Control' => 'use-cache',
            'Authorization' => 'Bearer ' . $backend_token,
            'Content-Type' => 'multipart/form-data'
        ])->get($url);

        Log::info('Status: ' . $response->status());
        //$data = $response->json();
        if ($response->successful()) {
            $imageContent = $response->body(); // Obtén los datos binarios de la imagen
            $base64Image = 'data:image/'.$extension.';base64,' . base64_encode($imageContent);
            return  $base64Image;
        }else{
            Log::error('error al generar imagen');
            return false;
        }
        
    }
}
