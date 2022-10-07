<?php

use App\Controller\DashboardController;

if(strtolower($request->action) == "widgets") {
    $dashboard = new DashboardController;
    echo json_encode(
        $dashboard->widgets()
    );
    die();
}
else if(strtolower($request->action) == "listings") {
    $dashboard = new DashboardController;
    echo json_encode(
        $dashboard->listings()
    );
    die();
}
else if(strtolower($request->action) == "pie-graphs") {
    $dashboard = new DashboardController;
    echo json_encode(
        $dashboard->pieGraph()
    );
    die();
}
else if(strtolower($request->action) == "line-graphs") {
    $dashboard = new DashboardController;
    echo json_encode(
        $dashboard->lineGraphs()
    );
    die();
}
?>