<?php

use App\Controller\Settings\OccupationController;

if(strtolower($request->action) == "all") {
    $occupation = new OccupationController;
    echo json_encode(
        $occupation->all()
    );
    die();
}
else if(strtolower($request->action) == "select2") {

    $occupation = new OccupationController;
    echo json_encode(
        $occupation->select2($request)
    );
    die();
}
else if(strtolower($request->action) == "store") {

    $occupation = new OccupationController;
    echo json_encode(
        $occupation->store($request)
    );
    die();
}
else if(strtolower($request->action) == "show") {
    $occupation = new OccupationController;
    echo json_encode(
        $occupation->show($request)
    );
    die();
}
else if(strtolower($request->action) == "update") {
    $occupation = new OccupationController;
    echo json_encode(
        $occupation->update($request)
    );
    die();
}
else if(strtolower($request->action) == "remove") {
    $occupation = new OccupationController;
    echo json_encode(
        $occupation->remove($request)
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