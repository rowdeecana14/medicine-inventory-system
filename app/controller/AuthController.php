<?php

namespace App\Controller;
use App\Model\LogModel;
use App\Model\Settings\UserModel;
use App\Helper\Helper;

class AuthController extends BaseController {

    public function login($request) {
        $user_model = new UserModel();
        $user = $user_model->login($request->username);

        if(!$user) {
            return [
                "success" => false,
                "message" => "Username not exist.",
            ];
        }
        
        if($user['deleted_at'] != null) {
            return [
                "success" => false,
                "message" => "Account deleted.",
            ];
        }

        if($user['status'] != 'Active') {
            return [
                "success" => false,
                "message" => "Account deactivated.",
            ];
        }
        
        if(!password_verify($request->password, $user['password'])){
            return [
                "success" => false,
                "message" => "Incorrect password.",
            ];
        }

        $_SESSION[app_code().'_AUTH_USER'] =[
            'id' => $user['id'],
            'username' => $user['username'],
            'image' => Helper::uploadedHealthOfficialImage($user['image']),
            'name' =>  $user['first_name']. " ".$user['middle_name'][0].". ".$user['last_name'],
            'fname' => ucwords($user['first_name']),
            'position' => $user['position']
        ];

        $_SESSION[app_code().'_LOGIN_DATE'] = date('Y-m-d');
        $_SESSION[app_code().'_LOGIN_COUNT'] = 1;

        $log = new LogModel;
        $log->store([
            'requests' => json_encode($request),
            'ip' => Helper::getUserIP(),
            'module_id' => $user_model->module,
            'action_id' => $user_model->action_login,
            'record_id' => 0,
            'user_id' => $user['id']
        ]);

        return [
            "success" => true,
            "message" => "Login",
        ];
    }

    public function logout($request) {
        $auth = json_decode(auth_user());
        
        unset($_SESSION[app_code()."_AUTH_USER"]); 
        unset($_SESSION[app_code()."_LOGIN_DATE"]);
        unset($_SESSION[app_code()."_LOGIN_COUNT"]);

        $user_model = new UserModel();
        $log = new LogModel;
        $log->store([
            'requests' => json_encode($request),
            'ip' => Helper::getUserIP(),
            'module_id' => $user_model->module,
            'action_id' => $user_model->action_logout,
            'record_id' => 0,
            'user_id' => $auth->id
        ]);

        return [
            "success" => true,
            "message" => "Logout",
            "data" => $auth->id
        ];
    }
}
?>