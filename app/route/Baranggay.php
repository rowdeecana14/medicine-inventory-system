<?php

use App\Controller\Settings\BaranggayController;

if(strtolower($request->action) == "all") {
    $baranggay = new BaranggayController;
    echo json_encode(
        $baranggay->all()
    );
    die();
}
else if(strtolower($request->action) == "select2") {

    $baranggay = new BaranggayController;
    echo json_encode(
        $baranggay->select2($request)
    );
    die();
}
else if(strtolower($request->action) == "store") {

    $baranggay = new BaranggayController;
    echo json_encode(
        $baranggay->store($request)
    );
    die();
}
else if(strtolower($request->action) == "show") {
    $baranggay = new BaranggayController;
    echo json_encode(
        $baranggay->show($request)
    );
    die();
}
else if(strtolower($request->action) == "update") {
    $baranggay = new BaranggayController;
    echo json_encode(
        $baranggay->update($request)
    );
    die();
}
else if(strtolower($request->action) == "remove") {
    $baranggay = new BaranggayController;
    echo json_encode(
        $baranggay->remove($request)
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