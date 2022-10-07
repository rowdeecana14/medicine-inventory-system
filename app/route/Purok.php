<?php

use App\Controller\Settings\PurokController;

if(strtolower($request->action) == "all") {
    $purok = new PurokController;
    echo json_encode(
        $purok->all()
    );
    die();
}
else if(strtolower($request->action) == "select2") {

    $purok = new PurokController;
    echo json_encode(
        $purok->select2($request)
    );
    die();
}
else if(strtolower($request->action) == "store") {
    $purok = new PurokController;
    echo json_encode(
        $purok->store($request)
    );
    die();
}
else if(strtolower($request->action) == "show") {
    $purok = new PurokController;
    echo json_encode(
        $purok->show($request)
    );
    die();
}
else if(strtolower($request->action) == "update") {
    $purok = new PurokController;
    echo json_encode(
        $purok->update($request)
    );
    die();
}
else if(strtolower($request->action) == "remove") {
    $purok = new PurokController;
    echo json_encode(
        $purok->remove($request)
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