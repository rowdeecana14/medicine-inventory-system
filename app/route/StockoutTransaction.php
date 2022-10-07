<?php

use App\Controller\Medicines\StockoutTransactionController;

if(strtolower($request->action) == "all") {
    $stocks_out = new StockoutTransactionController;
    echo json_encode(
        $stocks_out->all($request)
    );
    die();
}
else if(strtolower($request->action) == "store") {
    $stocks_out = new StockoutTransactionController;
    echo json_encode(
        $stocks_out->store($request)
    );
    die();
}
else if(strtolower($request->action) == "profile") {
    $stocks_out = new StockoutTransactionController;
    echo json_encode(
        $stocks_out->profile($request)
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