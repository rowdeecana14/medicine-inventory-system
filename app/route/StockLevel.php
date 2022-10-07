<?php

use App\Controller\Settings\StockLevelController;

if(strtolower($request->action) == "all") {
    $level = new StockLevelController;
    echo json_encode(
        $level->all()
    );
    die();
}
else if(strtolower($request->action) == "show") {
    $level = new StockLevelController;
    echo json_encode(
        $level->show($request)
    );
    die();
}
else if(strtolower($request->action) == "update") {
    $level = new StockLevelController;
    echo json_encode(
        $level->update($request)
    );
    die();
}
else if(strtolower($request->action) == "report-filter") {

    $level = new StockLevelController;
    echo json_encode(
        $level->reportFilter($request)
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