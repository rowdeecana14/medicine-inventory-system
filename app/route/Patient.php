<?php

use App\Controller\PatientController;

if(strtolower($request->action) == "all") {
    $patient = new PatientController;
    echo json_encode(
        $patient->all()
    );
    die();
}
else if(strtolower($request->action) == "select2") {

    $patient = new PatientController;
    echo json_encode(
        $patient->select2($request)
    );
    die();
}
else if(strtolower($request->action) == "report-filter") {

    $patient = new PatientController;
    echo json_encode(
        $patient->reportFilter($request)
    );
    die();
}
else if(strtolower($request->action) == "store") {

    $patient = new PatientController;
    echo json_encode(
        $patient->store($request)
    );
    die();
}
else if(strtolower($request->action) == "show") {
    $patient = new PatientController;
    echo json_encode(
        $patient->show($request)
    );
    die();
}
else if(strtolower($request->action) == "profile") {
    $patient = new PatientController;
    echo json_encode(
        $patient->profile($request)
    );
    die();
}
else if(strtolower($request->action) == "transaction") {
    $patient = new PatientController;
    echo json_encode(
        $patient->showTransactionDetials($request)
    );
    die();
}
else if(strtolower($request->action) == "update") {
    $patient = new PatientController;
    echo json_encode(
        $patient->update($request)
    );
    die();
}
else if(strtolower($request->action) == "remove") {
    $patient = new PatientController;
    echo json_encode(
        $patient->remove($request)
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