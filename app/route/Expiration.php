<?php

use App\Controller\Medicines\ExpirationController;

if(strtolower($request->action) == "all") {
    $expiration = new ExpirationController;
    echo json_encode(
        $expiration->all()
    );
    die();
}
else if(strtolower($request->action) == "profile") {
    $expiration = new ExpirationController;
    echo json_encode(
        $expiration->profile($request)
    );
    die();
}
else if(strtolower($request->action) == "show") {
    $expiration = new ExpirationController;
    echo json_encode(
        $expiration->edit($request)
    );
    die();
}
else if(strtolower($request->action) == "store-expiration") {
    $expiration = new ExpirationController;
    echo json_encode(
        $expiration->storeExpiration($request)
    );
    die();
}
else if(strtolower($request->action) == "show-expiration") {
    $expiration = new ExpirationController;
    echo json_encode(
        $expiration->showExpiration($request)
    );
    die();
}
else if(strtolower($request->action) == "update-expiration") {
    $expiration = new ExpirationController;
    echo json_encode(
        $expiration->updateExpiration($request)
    );
    die();
}
else if(strtolower($request->action) == "remove-expiration") {
    $expiration = new ExpirationController;
    echo json_encode(
        $expiration->removeExpiration($request)
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