<?php

use App\Controller\Medicines\MedicineController;

if(strtolower($request->action) == "all") {
    $medicine = new MedicineController;
    echo json_encode(
        $medicine->all()
    );
    die();
}
else if(strtolower($request->action) == "select2") {

    $medicine = new MedicineController;
    echo json_encode(
        $medicine->select2($request)
    );
    die();
}
else if(strtolower($request->action) == "report-filter") {

    $medicine = new MedicineController;
    echo json_encode(
        $medicine->reportFilter($request)
    );
    die();
}
else if(strtolower($request->action) == "store") {

    $medicine = new MedicineController;
    echo json_encode(
        $medicine->store($request)
    );
    die();
}
else if(strtolower($request->action) == "show") {
    $medicine = new MedicineController;
    echo json_encode(
        $medicine->show($request)
    );
    die();
}
else if(strtolower($request->action) == "profile") {
    $medicine = new MedicineController;
    echo json_encode(
        $medicine->profile($request)
    );
    die();
}
else if(strtolower($request->action) == "update") {
    $medicine = new MedicineController;
    echo json_encode(
        $medicine->update($request)
    );
    die();
}
else if(strtolower($request->action) == "remove") {
    $medicine = new MedicineController;
    echo json_encode(
        $medicine->remove($request)
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