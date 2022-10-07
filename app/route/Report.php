<?php

use App\Controller\Reports\MedicineInformationController;
use App\Controller\Reports\StockReceivingController;
use App\Controller\Reports\StockDepensingController;
use App\Controller\Reports\StockExpirationController;
use App\Controller\Reports\StockInventoryController;

if(strtolower($request->action) == "mi-all") {
    $medicineInformation = new MedicineInformationController;
    echo json_encode(
        $medicineInformation->all($request)
    );
    die();
}
else if(strtolower($request->action) == "mi-filter") {
    $medicineInformation = new MedicineInformationController;
    echo json_encode(
        $medicineInformation->filter($request)
    );
    die();
}
else if(strtolower($request->action) == "sr-all") {
    $stockReceiving= new StockReceivingController;
    echo json_encode(
        $stockReceiving->all($request)
    );
    die();
}
else if(strtolower($request->action) == "sr-filter") {
    $stockReceiving= new StockReceivingController;
    echo json_encode(
        $stockReceiving->filter($request)
    );
    die();
}
else if(strtolower($request->action) == "sd-all") {
    $stockDepensing= new StockDepensingController;
    echo json_encode(
        $stockDepensing->all($request)
    );
    die();
}
else if(strtolower($request->action) == "sd-filter") {
    $stockDepensing= new StockDepensingController;
    echo json_encode(
        $stockDepensing->filter($request)
    );
    die();
}
else if(strtolower($request->action) == "se-all") {
    $stockExpiration= new StockExpirationController;
    echo json_encode(
        $stockExpiration->all($request)
    );
    die();
}
else if(strtolower($request->action) == "se-filter") {
    $stockExpiration= new StockExpirationController;
    echo json_encode(
        $stockExpiration->filter($request)
    );
    die();
}
else if(strtolower($request->action) == "si-all") {
    $stockInventory= new StockInventoryController;
    echo json_encode(
        $stockInventory->all($request)
    );
    die();
}
else if(strtolower($request->action) == "si-filter") {
    $stockInventory= new StockInventoryController;
    echo json_encode(
        $stockInventory->filter($request)
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