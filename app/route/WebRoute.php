<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
date_default_timezone_set('Asia/Manila');

include_once('../template/BaseTemplate.php');
include_once('../helper/Helper.php');

// INCLUDE MODELS
include_once('../model/BaseModel.php');
include_once('../model/PatientModel.php');
include_once('../model/HealthOfficialModel.php');
include_once('../model/medicines/MedicineModel.php');
include_once('../model/medicines/StockTransactionModel.php');
include_once('../model/medicines/StockinTransactionModel.php');
include_once('../model/medicines/StockinMedicineModel.php');
include_once('../model/medicines/StockoutTransactionModel.php');
include_once('../model/medicines/StockoutMedicineModel.php');
include_once('../model/medicines/StockExpirationModel.php');
include_once('../model/DatabaseBackupModel.php');
include_once('../model/LogModel.php');

include_once('../model/settings/UserModel.php');
include_once('../model/settings/StockLevelModel.php');
include_once('../model/settings/CategoryModel.php');
include_once('../model/settings/TypeModel.php');
include_once('../model/settings/BaranggayModel.php');
include_once('../model/settings/PurokModel.php');
include_once('../model/settings/CitizenshipModel.php');
include_once('../model/settings/CivilStatusModel.php');
include_once('../model/settings/OccupationModel.php');
include_once('../model/settings/GenderModel.php');
include_once('../model/settings/PersonDisabilityModel.php');
include_once('../model/settings/EducationalAttainmentModel.php');
include_once('../model/settings/BloodTypeModel.php');

// INCLUDE CONTROLLERS
include_once('../controller/BaseController.php');
include_once('../controller/AuthController.php');
include_once('../controller/DashboardController.php');
include_once('../controller/LogController.php');

include_once('../controller/PatientController.php');
include_once('../controller/HealthOfficialController.php');
include_once('../controller/medicines/MedicineController.php');
include_once('../controller/medicines/StockinTransactionController.php');
include_once('../controller/medicines/StockoutTransactionController.php');
include_once('../controller/medicines/ExpirationController.php');
include_once('../controller/medicines/InventoryController.php');
include_once('../controller/medicines/TransactionController.php');
include_once('../controller/DatabaseBackupController.php');

include_once('../controller/reports/MedicineInformationController.php');+
include_once('../controller/reports/StockReceivingController.php');
include_once('../controller/reports/StockDepensingController.php');
include_once('../controller/reports/StockExpirationController.php');
include_once('../controller/reports/StockInventoryController.php');

include_once('../controller/settings/UserController.php');
include_once('../controller/settings/StockLevelController.php');
include_once('../controller/settings/CategoryController.php');
include_once('../controller/settings/TypeController.php');
include_once('../controller/settings/BaranggayController.php');
include_once('../controller/settings/PurokController.php');
include_once('../controller/settings/CitizenshipController.php');
include_once('../controller/settings/CivilStatusController.php');
include_once('../controller/settings/OccupationController.php');
include_once('../controller/settings/GenderController.php');
include_once('../controller/settings/PersonDisabilityController.php');
include_once('../controller/settings/EducationalAttainmentController.php');
include_once('../controller/settings/BloodTypeController.php');

$content = trim(file_get_contents("php://input"));
$request = json_decode($content);
$route = strtolower($request->module);

$routes = [
    "auth" => "./Auth.php",
    "dashboard" => "./Dashboard.php",
    "patients" => "./Patient.php",
    "health_officials" => "./HealthOfficial.php",

    "medicines" => "./Medicine.php",
    "stockin" => "./StockinTransaction.php",
    "stockout" => "./StockoutTransaction.php",
    "expirations" => "./Expiration.php",
    "inventory" => "./Inventory.php",
    "transactions" => "./Transaction.php",

    "reports" => "./Report.php",
    "database_backups" => "./DatabaseBackup.php",
    "activity_logs" => "./ActivityLog.php",
    
    "users" => "./User.php",
    "stock_levels" => "./StockLevel.php",
    "categories" => "./Category.php",
    "types" => "./Type.php",
    "baranggays" => "./Baranggay.php",
    "puroks" => "./Purok.php",
    "citizenships" => "./Citizenship.php",
    "civil_statuses" => "./CivilStatus.php",
    "occupations" => "./Occupation.php",
    "genders" => "./Gender.php",
    "relationships" => "./Relationship.php",
    "person_disabilities" => "./PersonDisability.php",
    "educational_attainments" => "./EducationalAttainment.php",
    "blood_types" => "./BloodType.php",
];

if(trim($_SERVER["CONTENT_TYPE"]) != "application/json") {

    http_response_code(404);
    echo json_encode([
        "success" => false,
        "message" => "Content Type json header not found"
    ]);
    exit();
}

if(!isset($request->module)) {
    http_response_code(404);
    echo json_encode([
        "success" => false,
        "message" => "Module not found in payload"
    ]);
    exit();
}

if(!isset($_SESSION[app_code().'_AUTH_USER']) && $request->module !== 'auth')  {
    http_response_code(401);
    echo json_encode([
        "success" => false,
        "message" => "Unauthorized user, login first."
    ]);
    exit();
}
// if($request->csrf_token != token()) {
//     http_response_code(401);
//     echo json_encode([
//         "success" => false,
//         "session_token" =>  $_SESSION[app_code().'_TOKEN'],
//         "request_token" => $request->csrf_token,
//         "message" => "Unauthorized user, wrong token"
//     ]);
//     exit();
// }

if (!array_key_exists($route, $routes)) {
    http_response_code(404);
    echo json_encode([
        "success" => false,
        "message" => "Module not found"
    ]);
}

if (trim($_SERVER["CONTENT_TYPE"]) == "application/json" &&  isset($request->module) && array_key_exists($route, $routes) ) {
    include($routes[$route]);
}
?>