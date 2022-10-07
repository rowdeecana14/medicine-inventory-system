<?php

use App\Controller\Settings\CivilStatusController;

if(strtolower($request->action) == "all") {
    $civil_status = new CivilStatusController;
    echo json_encode(
        $civil_status->all()
    );
    die();
}
else if(strtolower($request->action) == "select2") {

    $civil_status = new CivilStatusController;
    echo json_encode(
        $civil_status->select2($request)
    );
    die();
}
else if(strtolower($request->action) == "store") {

    $civil_status = new CivilStatusController;
    echo json_encode(
        $civil_status->store($request)
    );
    die();
}
else if(strtolower($request->action) == "show") {
    $civil_status = new CivilStatusController;
    echo json_encode(
        $civil_status->show($request)
    );
    die();
}
else if(strtolower($request->action) == "update") {
    $civil_status = new CivilStatusController;
    echo json_encode(
        $civil_status->update($request)
    );
    die();
}
else if(strtolower($request->action) == "remove") {
    $civil_status = new CivilStatusController;
    echo json_encode(
        $civil_status->remove($request)
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