<?php
    session_start();
    define("APP_BASE_URL", "http://localhost/mis");
    define("APP_CODE", "MIS");
    define("APP_VERSION", "1.0");
    define("APP_NAME", "medicine-inventory-system");
    define("APP_TITLE", "Medicine Inventory System");
    define("APP_API_URL", "/app/route/WebRoute.php");

    function isAuthenticate() {
        if(isset($_SESSION[app_code().'_AUTH_USER'])) {
            header('Location: '.base_url().'/views/dashboard/');
        }
    }

    function notAuthenticate() {
        if(!isset($_SESSION[app_code().'_AUTH_USER'])) {
            header('Location: '.base_url());
        }
    }
    
    function csrf() {
        $token = "";
        $vowels = 'aeou';
        $consonants = "bdghjmnpqrstvz";
        $number = '1234567890';

        for($i = 0; $i <= 100; $i++) {
            $token .= $consonants[rand() % strlen($consonants)];
            $token .= $vowels[rand() % strlen($vowels)];
            $token .= $number[rand() % strlen($number)];

            $token .= $consonants[rand() % strlen(strtoupper($consonants))];
            $token .= $vowels[rand() % strlen(strtoupper($vowels))];
            $token .= $number[rand() % strlen($number)];
        }

        $_SESSION[app_code().'_TOKEN'] = $token;

        return $token;
    }   

    function auth_user() {
        if(!isset($_SESSION[app_code().'_AUTH_USER']) && empty($_SESSION[app_code().'_AUTH_USER'])) {
            return '[]';
        }

        return json_encode($_SESSION[app_code().'_AUTH_USER']);
    }

    function token() {
        if(!isset($_SESSION[app_code().'_TOKEN']) && empty($_SESSION[app_code().'TOKEN'])) {
            return '';
        }
		
        return $_SESSION[app_code().'_TOKEN'];
	}

    function loginCount() {
        if(isset($_SESSION[app_code().'_LOGIN_COUNT']) ) {
            $count = $_SESSION[app_code().'_LOGIN_COUNT'];
            $_SESSION[app_code().'_LOGIN_DATE'] = date('Y-m-d');
            $_SESSION[app_code().'_LOGIN_COUNT'] = 2;

            return $count;
        }

        $_SESSION[app_code().'_LOGIN_DATE'] = date('Y-m-d');
        $_SESSION[app_code().'_LOGIN_COUNT'] = 2;
		
        return 1;
    }

    function base_url() {
        return constant("APP_BASE_URL"); 
    }

    function api_url() {
        return constant("APP_BASE_URL") . constant("APP_API_URL"); 
    }

    function image_url() {
        return constant("APP_BASE_URL") .'/public/assets/img/config/'; 
    }

    function upload_url() {
        return $_SERVER["DOCUMENT_ROOT"]."/".constant("APP_NAME")."/public/assets/img/uploaded/"; 
    }

    function app_name() {
        return constant("APP_NAME");
    }

    function app_title() {
        return constant("APP_TITLE");
    }

    function app_code() {
        return constant("APP_CODE");
    }

    function app_version() {
        return constant("APP_VERSION");
    }

    function app_config_path() {
        return base_url() . '/public/assets/img/config/';
    }

    function app_uploaded_document_root($folder) {
        return $_SERVER["DOCUMENT_ROOT"]."/".constant("APP_NAME")."/public/assets/img/uploaded/".$folder."/"; 
    }

    function app_uploaded_path($folder) {
        return base_url() . '/public/assets/img/uploaded/'.$folder.'/';
    }

    function app_backup_root() {
        return $_SERVER["DOCUMENT_ROOT"]."/".constant("APP_NAME")."/app/database/backups/";
    }

    function app_backup_path() {
        return base_url() . '/app/database/backups/';
    }

?>