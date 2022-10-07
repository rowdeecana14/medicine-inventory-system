<?php

use App\Controller\Settings\BloodTypeController;

if(strtolower($request->action) == "all") {
    $blood_type = new BloodTypeController;
    echo json_encode(
        $blood_type->all()
    );
    die();
}
else if(strtolower($request->action) == "select2") {
    $blood_type = new BloodTypeController;
    echo json_encode(
        $blood_type->select2($request)
    );
    die();
}
else if(strtolower($request->action) == "store") {
    $blood_type = new BloodTypeController;
    echo json_encode(
        $blood_type->store($request)
    );
    die();
}
else if(strtolower($request->action) == "show") {
    $blood_type = new BloodTypeController;
    echo json_encode(
        $blood_type->show($request)
    );
    die();
}
else if(strtolower($request->action) == "update") {
    $blood_type = new BloodTypeController;
    echo json_encode(
        $blood_type->update($request)
    );
    die();
}
else if(strtolower($request->action) == "remove") {
    $blood_type = new BloodTypeController;
    echo json_encode(
        $blood_type->remove($request)
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