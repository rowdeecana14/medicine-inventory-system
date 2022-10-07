<?php

use App\Controller\Medicines\InventoryController;

if(strtolower($request->action) == "all") {
    $inventory = new InventoryController;
    echo json_encode(
        $inventory->all()
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