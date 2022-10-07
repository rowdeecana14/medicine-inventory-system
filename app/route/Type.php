<?php
use App\Controller\Settings\TypeController;

if(strtolower($request->action) == "all") {
    $type = new TypeController;
    echo json_encode(
        $type->all()
    );
    die();
}
else if(strtolower($request->action) == "select2") {

    $type = new TypeController;
    echo json_encode(
        $type->select2($request)
    );
    die();
}
else if(strtolower($request->action) == "report-filter") {

    $type = new TypeController;
    echo json_encode(
        $type->reportFilter($request)
    );
    die();
}
else if(strtolower($request->action) == "store") {

    $type = new TypeController;
    echo json_encode(
        $type->store($request)
    );
    die();
}
else if(strtolower($request->action) == "show") {
    $type = new TypeController;
    echo json_encode(
        $type->show($request)
    );
    die();
}
else if(strtolower($request->action) == "update") {
    $type = new TypeController;
    echo json_encode(
        $type->update($request)
    );
    die();
}
else if(strtolower($request->action) == "remove") {
    $type = new TypeController;
    echo json_encode(
        $type->remove($request)
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