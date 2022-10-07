<?php

use App\Controller\Settings\CategoryController;

if(strtolower($request->action) == "all") {
    $category = new CategoryController;
    echo json_encode(
        $category->all()
    );
    die();
}
else if(strtolower($request->action) == "select2") {

    $category = new CategoryController;
    echo json_encode(
        $category->select2($request)
    );
    die();
}
else if(strtolower($request->action) == "report-filter") {

    $category = new CategoryController;
    echo json_encode(
        $category->reportFilter($request)
    );
    die();
}
else if(strtolower($request->action) == "store") {

    $category = new CategoryController;
    echo json_encode(
        $category->store($request)
    );
    die();
}
else if(strtolower($request->action) == "show") {
    $category = new CategoryController;
    echo json_encode(
        $category->show($request)
    );
    die();
}
else if(strtolower($request->action) == "update") {
    $category = new CategoryController;
    echo json_encode(
        $category->update($request)
    );
    die();
}
else if(strtolower($request->action) == "remove") {
    $category = new CategoryController;
    echo json_encode(
        $category->remove($request)
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