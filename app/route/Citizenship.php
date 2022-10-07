<?php

use App\Controller\Settings\CitizenshipController;

if(strtolower($request->action) == "all") {
    $complaint_status = new CitizenshipController;
    echo json_encode(
        $complaint_status->all()
    );
    die();
}
else if(strtolower($request->action) == "select2") {
    $complaint_status = new CitizenshipController;
    echo json_encode(
        $complaint_status->select2($request)
    );
    die();
}
else if(strtolower($request->action) == "store") {
    $complaint_status = new CitizenshipController;
    echo json_encode(
        $complaint_status->store($request)
    );
    die();
}
else if(strtolower($request->action) == "show") {
    $complaint_status = new CitizenshipController;
    echo json_encode(
        $complaint_status->show($request)
    );
    die();
}
else if(strtolower($request->action) == "update") {
    $complaint_status = new CitizenshipController;
    echo json_encode(
        $complaint_status->update($request)
    );
    die();
}
else if(strtolower($request->action) == "remove") {
    $complaint_status = new CitizenshipController;
    echo json_encode(
        $complaint_status->remove($request)
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