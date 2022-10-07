<?php

use App\Controller\Medicines\TransactionController;

if(strtolower($request->action) == "sr-filter") {

    $transactions = new TransactionController;
    echo json_encode(
        $transactions->stockReceivingFilter($request)
    );
    die();
}
else if(strtolower($request->action) == "sd-filter") {

    $transactions = new TransactionController;
    echo json_encode(
        $transactions->stockDispencingFilter($request)
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