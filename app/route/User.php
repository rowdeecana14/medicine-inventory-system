<?php

use App\Controller\Settings\UserController;

if(strtolower($request->action) == "all") {
    $user = new UserController;
    echo json_encode(
        $user->all()
    );
    die();
}
else if(strtolower($request->action) == "select2") {
    $user = new UserController;
    echo json_encode(
        $user->select2($request)
    );
    die();
}
else if(strtolower($request->action) == "store") {
    $user = new UserController;
    echo json_encode(
        $user->store($request)
    );
    die();
}
else if(strtolower($request->action) == "show") {
    $user = new UserController;
    echo json_encode(
        $user->show($request)
    );
    die();
}
else if(strtolower($request->action) == "update") {
    $user = new UserController;
    echo json_encode(
        $user->update($request)
    );
    die();
}
else if(strtolower($request->action) == "remove") {
    $user = new UserController;
    echo json_encode(
        $user->remove($request)
    );
    die();
}
else if(strtolower($request->action) == "update_profile") {
    $user = new UserController;
    echo json_encode(
        $user->updateProfile($request)
    );
    die();
}
else if(strtolower($request->action) == "update_account") {
    $user = new UserController;
    echo json_encode(
        $user->updateAccount($request)
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