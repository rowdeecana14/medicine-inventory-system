<?php

use App\Controller\HealthOfficialController;

if(strtolower($request->action) == "all") {
    $health_official = new HealthOfficialController;
    echo json_encode(
        $health_official->all()
    );
    die();
}
else if(strtolower($request->action) == "report-filter") {

    $health_official = new HealthOfficialController;
    echo json_encode(
        $health_official->reportFilter($request)
    );
    die();
}
else if(strtolower($request->action) == "select2") {

    $health_official = new HealthOfficialController;
    echo json_encode(
        $health_official->select2($request)
    );
    die();
}
else if(strtolower($request->action) == "store") {

    $health_official = new HealthOfficialController;
    echo json_encode(
        $health_official->store($request)
    );
    die();
}
else if(strtolower($request->action) == "show") {
    $health_official = new HealthOfficialController;
    echo json_encode(
        $health_official->show($request)
    );
    die();
}
else if(strtolower($request->action) == "stockin-transaction") {
    $health_official = new HealthOfficialController;
    echo json_encode(
        $health_official->showStockinTransaction($request)
    );
    die();
}
else if(strtolower($request->action) == "stockout-transaction") {
    $health_official = new HealthOfficialController;
    echo json_encode(
        $health_official->showStockoutTransaction($request)
    );
    die();
}
else if(strtolower($request->action) == "profile") {
    $health_official = new HealthOfficialController;
    echo json_encode(
        $health_official->profile($request)
    );
    die();
}
else if(strtolower($request->action) == "update") {
    $health_official = new HealthOfficialController;
    echo json_encode(
        $health_official->update($request)
    );
    die();
}
else if(strtolower($request->action) == "remove") {
    $health_official = new HealthOfficialController;
    echo json_encode(
        $health_official->remove($request)
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