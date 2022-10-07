<?php

use App\Controller\AuthController;

if(strtolower($request->action) == "login") {
    $user = new AuthController;
    echo json_encode(
        $user->login($request)
    );
    die();
}
else if(strtolower($request->action) == "logout") {
    $user = new AuthController;
    echo json_encode(
        $user->logout($request)
    );
    die();
}
else {
    http_response_code(401);
    echo json_encode([
        "success" => false,
        "message" => "Unauthorized user, action not found"
    ]);
    die();
}
?>