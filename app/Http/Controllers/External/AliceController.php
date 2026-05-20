<?php

namespace App\Http\Controllers\External;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Services\AliceService;
use Illuminate\Support\Facades\Cache;
use App\Events\AliceEvent;
use Illuminate\Support\Facades\Log;


class AliceController extends Controller
{
    public function index(Request $request, $id)
    {
        $checkin_id = $id;
        $action = $request->input('action', null);

     /*   $backend_token = Cache::remember('backend_token', 3600, function () {
            $res_login = AliceService::login();
            $login_token = $res_login['token'];

            $res_back = AliceService::backendToken($login_token);
            $backend_token = $res_back['token'];
            return $backend_token;
        });*/

        $login_token = AliceService::loginTokenCache();
        $backend_token = AliceService::backendTokenCache();

        if($action == 'reset'){
            Cache::forget('alice_user_id_' . $id);
            Cache::forget('alice_user_token_' . $id);
            Cache::forget('alice_user_document_' . $id.'_idcard');
            Cache::forget('alice_user_document_' . $id.'_driverlicense');
            Cache::forget('alice_user_document_' . $id.'_healthinsurancecard');
            Cache::forget('alice_user_document_' . $id.'_passport');
            Cache::forget('alice_user_document_' . $id.'_residencepermit');
        }

        $user_id = AliceService::userIdCache($id);
        $user_token = AliceService::userTokenCache($user_id);




       /* $user_token = Cache::remember('alice_user_token_' . $id, 3600, function () use ($login_token, $backend_token){

            $res_user = AliceService::userCreate($backend_token);

            $user_id = $res_user['user_id'];


            $res_user_token = AliceService::userToken($login_token, $user_id);

            $user_token = $res_user_token['token'];
            return $user_token;
        });*/

        $langs = ['es' => 'Español', 'en' => 'Ingles'];

        $countries = [
            'ESP' => 'España',
            'FRA' => 'Francia',
            'ITA' => 'Italia',
            'COL' => 'Colombia',
            'USA' => 'Estado Unidos de America'
        ];

        $documents_type = [
            'idcard' => 'Documento de Identificación',
            'driverlicense' => 'Licencia de Conducción',
            'healthinsurancecard' => 'Tarjeta de Seguro de Salud',
            'passport' => 'Pasaporte',
            'residencepermit' => 'Permiso de Residencia'
        ];


        return view('external.alice.index', compact('checkin_id','user_token', 'langs', 'countries', 'documents_type'));
    }

    public function events(Request $request, $id)
    {
        $data = $request->all();

        $user_id = AliceService::userIdCache($id);
        //Log::info('user_id ' . $user_id);
        $user_token = AliceService::userTokenCache($user_id);

        //Log::info('user_token');
        //Log::info($user_token);

        $documentCreateID = Cache::remember('alice_user_document_' . $id .'_'. $request->document_type , 3600, function () use ($user_token, $request) {
            return AliceService::documentCreate($user_token, $request->document_type, $request->country);
        });
        //Log::info($documentCreateID);

        $data = collect([
            'emisor_id' => $request->emisor_id,
            'receptor_id' => $request->receptor_id,
            'sesion_id' => $request->sesion_id,
            'action' => $request->action,
            'user_token' => $user_token,
            'language' => $request->language,
            'country' => $request->country,
            'document_type' => $request->document_type,
            'side' => $request->side,
            'document_id' => $documentCreateID['document_id'],
            'checkin_id' => $id
        ]);
        //dd($data);
        event(new AliceEvent($data));

    }

    public function status(Request $request, $id)
    {
        $checkin_id = $id;

        $res = AliceService::userReport($checkin_id);

        if(!empty($res['report'])){
            foreach($res['report']['documents'] as $doc_key => $doc_row ){
                if(isset($res['report']['documents'][$doc_key]['sides']['front']['media']['cropped_document']['href'])){

                    $base64Image = AliceService::media(
                        $checkin_id,
                        $res['report']['documents'][$doc_key]['sides']['front']['media']['cropped_document']['href'],
                        $res['report']['documents'][$doc_key]['sides']['front']['media']['cropped_document']['extension']
                    );
                    $res['report']['documents'][$doc_key]['sides']['front']['base64Image'] = $base64Image;
                }
                if(isset($res['report']['documents'][$doc_key]['sides']['back']['media']['cropped_document']['href'])){

                    $base64Image = AliceService::media(
                        $checkin_id,
                        $res['report']['documents'][$doc_key]['sides']['back']['media']['cropped_document']['href'],
                        $res['report']['documents'][$doc_key]['sides']['back']['media']['cropped_document']['extension']
                    );
                    $res['report']['documents'][$doc_key]['sides']['back']['base64Image'] = $base64Image;
                }
            }
        }

        $report = $res['report'];
        //dump($report);
        return view('external.alice.status', compact('checkin_id','report'));
        //dump($res);
    }

    public function login()
    {

        $res = AliceService::login();

        $login_token = $res['token'];


        $res_back = AliceService::backendToken($login_token);

        $backend_token = $res_back['token'];


        $res_user = AliceService::user($backend_token);

        $user_id = $res_user['user_id'];


        $res_user_token = AliceService::userToken($login_token, $user_id);

        $user_token = $res_user_token['token'];


    }

    // methodo de prueba
    /*public function front()
    {
        $res = AliceService::login();
        //dump($res);
        $login_token = $res['token'];
        //dump('login_token: ' . $login_token);

        $res_back = AliceService::backendToken($login_token);
        //dump($res_back);
        $backend_token = $res_back['token'];
        //dump('backend_token: ' . $backend_token);

        $res_user = AliceService::user($backend_token);
        //dump($res_user);
        $user_id = $res_user['user_id'];
        //dump('user_id: ' . $user_id);

        $res_user_token = AliceService::userToken($login_token, $user_id);
        //dump($res_user_token);
        $user_token = $res_user_token['token'];
        //dump('user_token: ' . $user_token);
        return view('alice/index', compact('user_token'));
    }*/
}
