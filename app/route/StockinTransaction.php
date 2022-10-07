<?php

use App\Controller\Medicines\StockinTransactionController;

if(strtolower($request->action) == "all") {
    $stocks_in = new StockinTransactionController;
    echo json_encode(
        $stocks_in->all($request)
    );
    die();
}
else if(strtolower($request->action) == "store") {
    $stocks_in = new StockinTransactionController;
    echo json_encode(
        $stocks_in->store($request)
    );
    die();
}
else if(strtolower($request->action) == "profile") {
    $stocks_in = new StockinTransactionController;
    echo json_encode(
        $stocks_in->profile($request)
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