<?php

namespace App\Controller\Medicines;
use App\Model\Medicines\MedicineModel;
use App\Model\LogModel;
use App\Controller\BaseController;
use App\Helper\Helper;
use App\Model\PatientModel;

class MedicineController extends BaseController {

    public $auth = [];

    public function __construct() {
        $this->auth = json_decode(auth_user());
    }

    public function all() {
        $model = new MedicineModel;
        $show_fields = [ 
            'image', 'name', 'description', 'status', 'category_id', 'type_id', 
        ];
        $show_fields = Helper::appendTable('medicines', $show_fields);
        $show_fields = array_merge($show_fields, [
            'medicines.id as medicine_id', 'categories.name as category', 'types.name as type'
        ]);
        $join_tables = [
            [ "LEFT", "categories", "medicines.category_id", "categories.id"],
            [ "LEFT", "types", "medicines.type_id", "types.id"],
        ];

        $medicines = $model->selects($show_fields, $join_tables);
        $result = [];

        foreach($medicines as $index => $medicine) {
            $status_badge =  $medicine['status'] == "Active" ? "secondary" : "default";
            $avatar_status = $medicine['status'] == "Active" ? "avatar-online" : "avatar-offline";
            $url =  Helper::uploadedMedicineImage($medicine['image']);

            array_push($result, [
                'index' => $index + 1,
                'image' => '
                    <div class="avatar '.$avatar_status.'">
                        <img src="'.$url.'" alt="'.$medicine['name'].'" class="avatar-img">
                    </div>
                ',
                'name' => $medicine['name'],
                'description' => $medicine['description'],
                'category_id' => $medicine['category'],
                'type_id' => $medicine['type'],
                'status' => '<span class="badge badge-'.$status_badge.'">'.strtoupper($medicine['status']).'</span>',
                'action' => '
                    <button type="button" class="btn btn-icon btn-round btn-info btn-show"  data-id="'.$medicine['medicine_id'].'" data-toggle="tooltip" data-placement="top" title="View record">
                        <i class="fas fa-search"></i>
                    </button>
                    <button type="button" class="btn btn-icon btn-round btn-warning btn-edit" data-id="'.$medicine['medicine_id'].'" data-toggle="tooltip" data-placement="top" title="Edit record">
                        <i class="fas fa-edit"></i>
                    </button>
                '
            ]);
        }

        return [
            "success" => true,
            "message" => "success",
            "data" => $result
        ];
    }
    
    public function select2($request) {
        $medicines = new MedicineModel;
        $data = isset($request->q) ? [ 'name' => $request->q, 'description' => $request->q ] : [];
        $fields = [
            'id', 'name as title', 'name as text', 'substring(description, 1, 50) as description', 'image',
            "(SELECT SUM(sim.quantity) FROM stockin_medicines AS sim WHERE sim.medicine_id=medicines.id ) as stockin",
            "(SELECT SUM(som.quantity) FROM stockout_medicines AS som WHERE som.medicine_id=medicines.id ) as stockout",
            "(SELECT SUM(ss.quantity) FROM stock_expiries AS ss WHERE  DATEDIFF(ss.expired_at, CURRENT_DATE) <= 0 AND ss.medicine_id=medicines.id AND ss.deleted_at IS NULL) as expired"
        ];
        $medicines = $medicines->search($fields, [], $data);

        return [
            "success" => true,
            "message" => "success",
            "data" => $medicines
        ];
    }


    public function reportFilter($request) {
        $medicine = new MedicineModel;
        $data = isset($request->q) ? [ 'name' => $request->q, 'description' => $request->q ] : [];

        $fields = [
            'id', 'name as title', 'name as text', 'substring(description, 1, 50) as description', 'image',
            "(SELECT SUM(sim.quantity) FROM stockin_medicines AS sim WHERE sim.medicine_id=medicines.id ) as stockin",
            "(SELECT SUM(som.quantity) FROM stockout_medicines AS som WHERE som.medicine_id=medicines.id ) as stockout",
            "(SELECT SUM(ss.quantity) FROM stock_expiries AS ss WHERE  DATEDIFF(ss.expired_at, CURRENT_DATE) <= 0 AND ss.medicine_id=medicines.id AND ss.deleted_at IS NULL) as expired"
        ];
       
        $medicines = $medicine->searchAdvanced($fields, [], $data);

        array_unshift($medicines, ['id' => 'All' , 'text' => 'All']);

        return [
            "success" => true,
            "message" => "success",
            "data" => $medicines
        ];
    }

    public function store($request) {
        $path = Helper::uploadedMedicinePath();
        $file = $request->image_to_upload != '' ? Helper::uploadImage($request->image_to_upload, $path) : '';
        $image = $request->image_to_upload != ''  ? $file : null;

        $medicineModel = new MedicineModel;
        $data = Helper::allowOnly((array) $request, ['image', 'name', 'description', 'category_id', 'type_id']);
        $data = array_merge($data, [
            "created_by" => $this->auth->id, 
            "image" =>  $image,
        ]);
        $medicine_id =  $medicineModel->lastInsertId($data);

        $log = new LogModel;
        $log->store([
            'requests' => json_encode($request),
            'ip' => Helper::getUserIP(),
            'module_id' => $medicineModel->module,
            'action_id' => $medicineModel->action_add,
            'record_id' => $medicine_id,
            'user_id' => $this->auth->id
        ]);

        return [
            "success" => true,
            "message" => "success"
        ];
    }

    public function show($request) {
        $medicineModel = new MedicineModel;
        $show_fields = [ 
            'image', 'name', 'description', 'status', 'category_id', 'type_id', 
        ];
        $show_fields = Helper::appendTable('medicines', $show_fields);
        $show_fields = array_merge($show_fields, [
            'medicines.id as medicine_id', 'categories.name as category', 'types.name as type'
        ]);
        $join_tables = [
            [ "LEFT", "categories", "medicines.category_id", "categories.id"],
            [ "LEFT", "types", "medicines.type_id", "types.id"],
        ];
        $wheres = [[ 'table' => 'medicines', 'key' => 'id', 'value' => $request->id ]];
        $medicine = $medicineModel->select($show_fields, $join_tables,  $wheres);

        $medicine = array_merge($medicine, [
            'id' =>  $medicine['medicine_id'],
            'image_profile' => Helper::uploadedMedicineImage($medicine['image']),
            'category_id' => [
                "id" => $medicine['category_id'],
                "text" => $medicine['category'],
            ],
            'type_id' => [
                "id" => $medicine['type_id'],
                "text" => $medicine['type'],
            ],
        ]);

        $log = new LogModel;
        $log->store([
            'requests' => json_encode($request),
            'ip' => Helper::getUserIP(),
            'module_id' => $medicineModel->module,
            'action_id' => $medicineModel->action_read,
            'record_id' => $request->id,
            'user_id' => $this->auth->id
        ]);

        return [
            "success" => true,
            "message" => "success",
            "data" => $medicine
        ];
    }

    public function profile($request) {
        $medicineModel = new MedicineModel;
        $show_fields = [ 
            'image', 'name as names', 'description', 'status', 'category_id', 'type_id', 
            'created_at', 'updated_at', 'updated_by', 'created_by', 
        ];
        $show_fields = Helper::appendTable('medicines', $show_fields);
        $show_fields = array_merge($show_fields, [
            'medicines.id as medicine_id', 'categories.name as category', 'types.name as type',

            "(SELECT SUM(sim.quantity) FROM stockin_medicines AS sim WHERE sim.medicine_id=medicines.id ) as stockin",
            "(SELECT SUM(som.quantity) FROM stockout_medicines AS som WHERE som.medicine_id=medicines.id ) as stockout",
            "(SELECT SUM(ss.quantity) FROM stock_expiries AS ss WHERE  DATEDIFF(ss.expired_at, CURRENT_DATE) <= 0 AND ss.medicine_id=medicines.id AND ss.deleted_at IS NULL) as expired",

            "concat(creator.first_name,' ', substring(creator.middle_name, 1, 1), '. ', creator.last_name, ' ', creator.suffix) as creator_name",
            "concat(updator.first_name,' ', substring(updator.middle_name, 1, 1), '. ', updator.last_name, ' ', updator.suffix) as updator_name",
        ]);
        $join_tables = [
            [ "LEFT", "categories", "medicines.category_id", "categories.id"],
            [ "LEFT", "types", "medicines.type_id", "types.id"],

            [ "LEFT", "users as creator_user", "medicines.created_by", "creator_user.id"],
            [ "LEFT", "health_officials as creator", "creator_user.health_official_id", "creator.id"],
            [ "LEFT", "users as updator_user", "medicines.updated_by", "updator_user.id"],
            [ "LEFT", "health_officials as updator", "updator_user.health_official_id", "updator.id"],
        ];
        $wheres = [[ 'table' => 'medicines', 'key' => 'id', 'value' => $request->id ]];
        $medicine = $medicineModel->select($show_fields, $join_tables,  $wheres);

        $medicine = array_merge($medicine, [
            'id' =>  $medicine['medicine_id'],
            'image_profile' => Helper::uploadedMedicineImage($medicine['image']),
            'available' =>  ((int) $medicine['stockin'] - (int)  $medicine['stockout']) - (int) $medicine['expired'],
            'recieved' =>  (int)  $medicine['stockin'],
            'dispenced' => (int)  $medicine['stockout'],
            'expired' => (int) $medicine['expired'],
            'created_by' => $medicine['creator_name'],
            'created_at' => Helper::humanDate('M d, Y h:i A', $medicine['created_at']),
            'updated_by' => $medicine['updator_name'],
            'updated_at' =>  Helper::humanDate('M d, Y h:i A', $medicine['updated_at']),
        ]);

        // GET ALL STOCK TRANSACTIONS
        $transactions_model = new PatientModel;
        $results = $transactions_model->transactions($request->id);
        $transactions = [];

        foreach($results as $index => $transaction) {
            $quantity = $transaction['type'] == "stockin" ? $transaction['sit_quantity'] : $transaction['sot_quantity'];
            $health_official =  $transaction['type'] == "stockin" ? $transaction['sit_health_official'] : $transaction['sot_health_official'];
            $person =  $transaction['type'] == "stockin" ? $transaction['sit_delivery'] : $transaction['sot_patient'];

            $date =  $transaction['type'] == "stockin" 
                ? Helper::humanDate('M d, Y', $transaction['sit_received_at'])
                : Helper::humanDate('M d, Y', $transaction['sot_dispenced_at']);
            $type = $transaction['type'] == "stockin" 
                ? '<span class="badge badge-secondary"><i class="fas fa-arrow-circle-left pr-1"></i> RECEIVED</span>'
                : '<span class="badge badge-default"><i class="fas fa-arrow-circle-right pr-1"></i> DISPENCED</span>';

            array_push($transactions, [
                'index' => $index + 1,
                'transaction_no' => app_code().'-'.str_pad($transaction['transaction_no'], 6, "0", STR_PAD_LEFT),
                'quantity' => $quantity,
                'health_official' => $health_official,
                'person' => $person,
                'date' => $date,
                'type' => $type,
            ]);
        };

        $log = new LogModel;
        $log->store([
            'requests' => json_encode($request),
            'ip' => Helper::getUserIP(),
            'module_id' => $medicineModel->module,
            'action_id' => $medicineModel->action_read,
            'record_id' => $request->id,
            'user_id' => $this->auth->id
        ]);

        return [
            "success" => true,
            "message" => "success",
            "data" => [
                "medicine" => $medicine,
                "transactions" => $transactions
            ]
        ];
    }

    public function update($request) {
        $path = Helper::uploadedMedicinePath();
        $file = $request->image_to_upload != '' ? Helper::uploadImage($request->image_to_upload, $path) : '';

        $medicineModel = new MedicineModel;
        $data = Helper::allowOnly((array) $request, ['id', 'name', 'description', 'category_id', 'type_id', 'status']);
        $data = array_merge($data, [
            "updated_by" => $this->auth->id,
            "updated_at" => date('Y-m-d H:i:s'),
        ]);

        if($request->image_to_upload != '') {
            $data['image'] = $file;
        }
        else {
            unset($data['image']);
        }

        if($medicineModel->update($data)) {
            $log = new LogModel;
            $log->store([
                'requests' => json_encode($request),
                'ip' => Helper::getUserIP(),
                'module_id' => $medicineModel->module,
                'action_id' => $medicineModel->action_update,
                'record_id' => $request->id,
                'user_id' => $this->auth->id
            ]);

            return [
                "success" => true,
                "message" => "success"
            ];
        }

        return [
            "success" => false,
            "message" => "error"
        ];
    }

    public function remove($request) {
        $medicineModel = new MedicineModel;
        $data = [
            'id' => $request->id,
            'status' => 'Inactive',
            'deleted_by' => $this->auth->id,
            'deleted_at' => date('Y-m-d H:i:s')
        ];

        if($medicineModel->remove($data)) {
            
            $log = new LogModel;
            $log->store([
                'requests' => json_encode($request),
                'ip' => Helper::getUserIP(),
                'module_id' => $medicineModel->module,
                'action_id' => $medicineModel->action_delete,
                'record_id' => $request->id,
                'user_id' => $this->auth->id
            ]);

            return [
                "success" => true,
                "message" => "success"
            ];
        }

        return [
            "success" => false,
            "message" => "error"
        ];
    }
}
?>