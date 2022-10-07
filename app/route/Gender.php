<?php

use App\Controller\Settings\GenderController;

if(strtolower($request->action) == "all") {
    $gender = new GenderController;
    echo json_encode(
        $gender->all()
    );
    die();
}
else if(strtolower($request->action) == "select2") {

    $gender = new GenderController;
    echo json_encode(
        $gender->select2($request)
    );
    die();
}
else if(strtolower($request->action) == "store") {

    $gender = new GenderController;
    echo json_encode(
        $gender->store($request)
    );
    die();
}
else if(strtolower($request->action) == "show") {
    $gender = new GenderController;
    echo json_encode(
        $gender->show($request)
    );
    die();
}
else if(strtolower($request->action) == "update") {
    $gender = new GenderController;
    echo json_encode(
        $gender->update($request)
    );
    die();
}
else if(strtolower($request->action) == "remove") {
    $gender = new GenderController;
    echo json_encode(
        $gender->remove($request)
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