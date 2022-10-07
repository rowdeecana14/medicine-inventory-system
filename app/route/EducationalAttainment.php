<?php

use App\Controller\Settings\EducationalAttainmentController;

if(strtolower($request->action) == "all") {
    $educational_attainment = new EducationalAttainmentController;
    echo json_encode(
        $educational_attainment->all()
    );
    die();
}
else if(strtolower($request->action) == "select2") {

    $educational_attainment = new EducationalAttainmentController;
    echo json_encode(
        $educational_attainment->select2($request)
    );
    die();
}
else if(strtolower($request->action) == "store") {

    $educational_attainment = new EducationalAttainmentController;
    echo json_encode(
        $educational_attainment->store($request)
    );
    die();
}
else if(strtolower($request->action) == "show") {
    $educational_attainment = new EducationalAttainmentController;
    echo json_encode(
        $educational_attainment->show($request)
    );
    die();
}
else if(strtolower($request->action) == "update") {
    $educational_attainment = new EducationalAttainmentController;
    echo json_encode(
        $educational_attainment->update($request)
    );
    die();
}
else if(strtolower($request->action) == "remove") {
    $educational_attainment = new EducationalAttainmentController;
    echo json_encode(
        $educational_attainment->remove($request)
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