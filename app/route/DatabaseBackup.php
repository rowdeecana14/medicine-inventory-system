<?php

use App\Controller\DatabaseBackupController;

if(strtolower($request->action) == "all") {
    $database = new DatabaseBackupController;
    echo json_encode(
        $database->all($request)
    );
    die();
}
else if(strtolower($request->action) == "backup") {
    $database = new DatabaseBackupController;
    echo json_encode(
        $database->backup($request)
    );
    die();
}
else if(strtolower($request->action) == "remove") {
    $database = new DatabaseBackupController;
    echo json_encode(
        $database->remove($request)
    );
    die();
}
else if(strtolower($request->action) == "download") {
    $database = new DatabaseBackupController;
    echo json_encode(
        $database->download($request)
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