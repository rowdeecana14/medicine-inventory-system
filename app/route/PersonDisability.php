<?php

use App\Controller\Settings\PersonDisabllityController;

if(strtolower($request->action) == "all") {
    $person_disability = new PersonDisabllityController;
    echo json_encode(
        $person_disability->all()
    );
    die();
}
else if(strtolower($request->action) == "select2") {

    $person_disability = new PersonDisabllityController;
    echo json_encode(
        $person_disability->select2($request)
    );
    die();
}
else if(strtolower($request->action) == "store") {

    $person_disability = new PersonDisabllityController;
    echo json_encode(
        $person_disability->store($request)
    );
    die();
}
else if(strtolower($request->action) == "show") {
    $person_disability = new PersonDisabllityController;
    echo json_encode(
        $person_disability->show($request)
    );
    die();
}
else if(strtolower($request->action) == "update") {
    $person_disability = new PersonDisabllityController;
    echo json_encode(
        $person_disability->update($request)
    );
    die();
}
else if(strtolower($request->action) == "remove") {
    $person_disability = new PersonDisabllityController;
    echo json_encode(
        $person_disability->remove($request)
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