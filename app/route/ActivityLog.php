<?php

use App\Controller\LogController;

if(strtolower($request->action) == "all") {
    $log = new LogController;
    echo json_encode(
        $log->all($request)
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