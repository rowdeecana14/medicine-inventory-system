<?php

namespace App\Controller;
use App\Model\HealthOfficialModel;
use App\Model\LogModel;
use App\Model\Medicines\StockTransactionModel;
use App\Model\Medicines\StockoutMedicineModel;
use App\Model\Medicines\StockinMedicineModel;
use App\Controller\BaseController;
use App\Helper\Helper;

class HealthOfficialController extends BaseController {
   
    public $auth = [];

    public function __construct() {
        $this->auth = json_decode(auth_user());
    }

    public function all() {
        $healthOfficialModel = new HealthOfficialModel;
        $show_fields = [ 
            'image', 'first_name', 'middle_name', 'last_name', 'position_id', 'gender_id', 'contact_no', 'status', 'suffix'
        ];
        $show_fields = Helper::appendTable('health_officials', $show_fields);
        $show_fields[] = 'health_officials.id as health_official_id';
        $show_fields[] = 'occupations.name as position';
        $show_fields[] = 'genders.name as gender';
        $join_tables = [
            [ "LEFT", "occupations", "health_officials.position_id", "occupations.id"],
            [ "LEFT", "genders", "health_officials.gender_id", "genders.id"],
        ];

        $health_officials = $healthOfficialModel->selects($show_fields, $join_tables);
        $result = [];

        foreach($health_officials as $index => $health_official) {

            $badge =  ($healthOfficialModel->admin_id == $health_official['health_official_id']) ? "primary" : (($health_official['status'] == "Active") ?  'secondary' : "default");

            $suffix = ($health_official['suffix'] == '' || $health_official['suffix'] == null) ? '' : ', '.$health_official['suffix'];
            $name = $health_official['first_name'] . ' '.$health_official['middle_name'][0].'. '.$health_official['last_name'].' '.$suffix;
            $avatar_status = $health_official['status'] == ($healthOfficialModel->admin_id == $health_official['health_official_id']) ? "avatar-away" : (($health_official['status'] == "Active") ?  'avatar-online' : "avatar-offline"); 
            $url =  Helper::uploadedHealthOfficialImage($health_official['image']);

            $actions = '<span class="badge badge-primary">DEFAULT USER</span>';
            
            if($healthOfficialModel->admin_id !== $health_official['health_official_id']) {
                $actions = '
                    <button type="button" class="btn btn-icon btn-round btn-info btn-show"  data-id="'.$health_official['health_official_id'].'" data-toggle="tooltip" data-placement="top" title="View record">
                        <i class="fas fa-search"></i>
                    </button>
                    <button type="button" class="btn btn-icon btn-round btn-warning btn-edit" data-id="'.$health_official['health_official_id'].'" data-toggle="tooltip" data-placement="top" title="Edit record">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-icon btn-round btn-danger btn-delete" data-id="'.$health_official['health_official_id'].'" data-toggle="tooltip" data-placement="top" title="Delete record">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                ';
            }
                
            array_push($result, [
                'index' => $index + 1,
                'image' => '
                    <div class="avatar '.$avatar_status.'">
                        <img src="'.$url.'" alt="'.$name.'" class="avatar-img rounded-circle">
                    </div>
                ',
                'name' => $name,
                'position' => $health_official['position'],
                'gender' => $health_official['gender'],
                'contact_no' => $health_official['contact_no'],
                'status' => '<span class="badge badge-'.$badge.'">'.strtoupper($health_official['status']).'</span>',
                'action' => $actions
            ]);
        }

        return [
            "success" => true,
            "message" => "success",
            "data" => $result
        ];
    }

    public function select2($request) {
        $health_official = new HealthOfficialModel;
        $data = isset($request->q) ? [
            [ 
                "column" => "concat(health_officials.first_name,' ', substring(health_officials.middle_name, 1, 1), '. ', health_officials.last_name)" ,
                "parameter" => "name",
                "value" => $request->q
            ],
            [ 
                "column" =>  "concat(health_officials.street_building_house, ', ', puroks.name, ', ', baranggays.name)" ,
                "parameter" => "address",
                "value" => $request->q
            ],
            [ 
                "column" =>  "occupations.name" ,
                "parameter" => "occupation",
                "value" => $request->q
            ]
        ] : [];

        $fields = [
            'health_officials.id', 'image', 'genders.name as gender', 'occupations.name as occupation',
            "concat(health_officials.street_building_house, ', ', puroks.name, ', ', baranggays.name) as description",
            "concat(health_officials.first_name,' ', substring(health_officials.middle_name, 1, 1), '. ', health_officials.last_name) as title", 
            "concat(health_officials.first_name,' ', substring(health_officials.middle_name, 1, 1), '. ', health_officials.last_name) as text", 
        ];
        $fields = array_merge($fields, [
            'baranggays.name as baranggay',  'puroks.name as purok'
        ]);
        $join_tables = [
            [ "LEFT", "baranggays", "health_officials.baranggay_id", "baranggays.id"],
            [ "LEFT", "puroks", "health_officials.purok_id", "puroks.id"],
            [ "LEFT", "genders", "health_officials.gender_id", "genders.id"],
            [ "LEFT", "occupations", "health_officials.position_id", "occupations.id"],
        ];
        $health_official = $health_official->searchAdvanced($fields, $join_tables, $data);

        return [
            "success" => true,
            "message" => "success",
            "data" => $health_official
        ];
    }

    public function reportFilter($request) {
        $health_official = new HealthOfficialModel;
        $data = isset($request->q) ? [
            [ 
                "column" => "concat(health_officials.first_name,' ', substring(health_officials.middle_name, 1, 1), '. ', health_officials.last_name)" ,
                "parameter" => "name",
                "value" => $request->q
            ],
            [ 
                "column" =>  "concat(health_officials.street_building_house, ', ', puroks.name, ', ', baranggays.name)" ,
                "parameter" => "address",
                "value" => $request->q
            ],
            [ 
                "column" =>  "occupations.name" ,
                "parameter" => "occupation",
                "value" => $request->q
            ]
        ] : [];

        $fields = [
            'health_officials.id', 'image', 'genders.name as gender', 'occupations.name as occupation',
            "concat(health_officials.street_building_house, ', ', puroks.name, ', ', baranggays.name) as description",
            "concat(health_officials.first_name,' ', substring(health_officials.middle_name, 1, 1), '. ', health_officials.last_name) as title", 
            "concat(health_officials.first_name,' ', substring(health_officials.middle_name, 1, 1), '. ', health_officials.last_name) as text", 
        ];
        $fields = array_merge($fields, [
            'baranggays.name as baranggay',  'puroks.name as purok'
        ]);
        $join_tables = [
            [ "LEFT", "baranggays", "health_officials.baranggay_id", "baranggays.id"],
            [ "LEFT", "puroks", "health_officials.purok_id", "puroks.id"],
            [ "LEFT", "genders", "health_officials.gender_id", "genders.id"],
            [ "LEFT", "occupations", "health_officials.position_id", "occupations.id"],
        ];
        $health_officials = $health_official->searchAdvanced($fields, $join_tables, $data);

        array_unshift($health_officials, ['id' => 'All' , 'text' => 'All']);

        return [
            "success" => true,
            "message" => "success",
            "data" => $health_officials
        ];
    }
    
    public function store($request) {
        $file = '';

        if($request->image_to_upload != '') {
            $path = Helper::uploadedHealthOfficialPath();
            $file = Helper::uploadImage($request->image_to_upload, $path);
        }
        else {
            // $file = $request->gender_id === 1
        }
        $healthOfficialModel = new HealthOfficialModel;
        $data = Helper::unsets((array) $request, ['module', 'action', 'csrf_token', 'profileimg', 'image', 'image_to_upload']);
        $data['birth_date'] = Helper::dateParser($data['birth_date']); 
        $data['created_by'] = $this->auth->id;
        $data['image'] = isset($request->image_to_upload) & $request->image_to_upload != null ? $file : null;
        $health_official_id =  $healthOfficialModel->lastInsertId($data);

        $log = new LogModel;
        $log->store([
            'requests' => json_encode($request),
            'ip' => Helper::getUserIP(),
            'module_id' => $healthOfficialModel->module,
            'action_id' => $healthOfficialModel->action_add,
            'record_id' => $health_official_id,
            'user_id' => $this->auth->id
        ]);

        return [
            "success" => true,
            "message" => "success"
        ];
    }

    public function show($request) {
        $healthOfficialModel = new HealthOfficialModel;
        $show_fields = [ 
            'image', 'first_name', 'middle_name', 'last_name', 'position_id', 'gender_id', 'contact_no', 'license_no',
            'civil_status_id', 'purok_id', 'baranggay_id', 'status', 'email', 'birth_date', 'suffix', 'street_building_house'
        ];
        $show_fields = Helper::appendTable('health_officials', $show_fields);
        $show_fields[] = 'health_officials.id as health_official_id';
        $show_fields[] = 'occupations.name as position';
        $show_fields[] = 'genders.name as gender';
        $show_fields[] = 'civil_statuses.name as civil_status';
        $show_fields[] = 'baranggays.name as baranggay';
        $show_fields[] = 'puroks.name as purok';

        $join_tables = [
            [ "LEFT", "occupations", "health_officials.position_id", "occupations.id"],
            [ "LEFT", "genders", "health_officials.gender_id", "genders.id"],
            [ "LEFT", "civil_statuses", "health_officials.civil_status_id", "civil_statuses.id"],
            [ "LEFT", "baranggays", "health_officials.baranggay_id", "baranggays.id"],
            [ "LEFT", "puroks", "health_officials.purok_id", "puroks.id"],
        ];
        $wheres = [[ 'table' => 'health_officials', 'key' => 'id', 'value' => $request->id ]];
        $health_official = $healthOfficialModel->select($show_fields, $join_tables,  $wheres);

        $health_official['id'] = $health_official['health_official_id'];
        $health_official['birth_date'] = date('m/d/Y', strtotime($health_official['birth_date']));
        $health_official['image_profile']  =  Helper::uploadedHealthOfficialImage($health_official['image']);
        $health_official['position_id'] = [
            "id" => $health_official['position_id'],
            "text" => $health_official['position'],
        ];
        $health_official['gender_id'] = [
            "id" => $health_official['gender_id'],
            "text" => $health_official['gender'],
        ];
        $health_official['civil_status_id'] = [
            "id" => $health_official['civil_status_id'],
            "text" => $health_official['civil_status'],
        ];
        $health_official['purok_id'] = [
            "id" => $health_official['purok_id'],
            "text" => $health_official['purok'],
        ];
        $health_official['baranggay_id'] = [
            "id" => $health_official['baranggay_id'],
            "text" => $health_official['baranggay'],
        ];

        $log = new LogModel;
        $log->store([
            'requests' => json_encode($request),
            'ip' => Helper::getUserIP(),
            'module_id' => $healthOfficialModel->module,
            'action_id' => $healthOfficialModel->action_read,
            'record_id' => $request->id,
            'user_id' => $this->auth->id
        ]);

        return [
            "success" => true,
            "message" => "success",
            "data" => $health_official
        ];
    }

    public function showStockoutTransaction($request) {
        $stockout_medicine_model = new StockoutMedicineModel;
        $show_fields = [ 'quantity', 'dosage'];
        $show_fields = Helper::appendTable('stockout_medicines', $show_fields);
        $show_fields = array_merge($show_fields, [
            "m.image", "m.name", "m.description", 
        ]);
        $join_tables = [
            [ "LEFT", "medicines AS m", "stockout_medicines.medicine_id", "m.id"],
        ];

        $where_fields = [[ 'table' => 'stockout_medicines', 'key' => 'stockout_transaction_id', 'value' => $request->id ]];
        $order_fields = [[ 'table' => 'm', 'key' => 'name', 'value' => 'asc']];
        $results = $stockout_medicine_model->selectsAdvanced($show_fields, $join_tables, $where_fields, $order_fields);
        $medicines = [];

        foreach($results as $index => $medicine) {
            $url = Helper::uploadedMedicineImage($medicine['image']);

            array_push($medicines, [
                'index' => $index + 1,
                'image' => '
                    <div class="avatar">
                        <img src="'.$url.'" alt="'.$medicine['name'].'" class="avatar-img">
                    </div>
                ',
                'name' => $medicine['name'],
                'description' => $medicine['description'],
                'quantity' => $medicine['quantity'],
                'dosage' => $medicine['dosage'],
            ]);
        };

        return [
            "success" => true,
            "message" => "success",
            "data" => $medicines
        ];
    }

    public function showStockinTransaction($request) {
        $stockin_medicine_model = new StockinMedicineModel;
        $show_fields = [ 'quantity', ];
        $show_fields = Helper::appendTable('stockin_medicines', $show_fields);
        $show_fields = array_merge($show_fields, [
            "m.image", "m.name", "m.description", 
        ]);
        $join_tables = [
            [ "LEFT", "medicines AS m", "stockin_medicines.medicine_id", "m.id"],
        ];

        $where_fields = [[ 'table' => 'stockin_medicines', 'key' => 'stockin_transaction_id', 'value' => $request->id ]];
        $order_fields = [[ 'table' => 'm', 'key' => 'name', 'value' => 'asc']];
        $results = $stockin_medicine_model->selectsAdvanced($show_fields, $join_tables, $where_fields, $order_fields);
        $medicines = [];

        foreach($results as $index => $medicine) {
            $url = Helper::uploadedMedicineImage($medicine['image']);

            array_push($medicines, [
                'index' => $index + 1,
                'image' => '
                    <div class="avatar">
                        <img src="'.$url.'" alt="'.$medicine['name'].'" class="avatar-img">
                    </div>
                ',
                'name' => $medicine['name'],
                'description' => $medicine['description'],
                'quantity' => $medicine['quantity'],
            ]);
        };

        return [
            "success" => true,
            "message" => "success",
            "data" => $medicines
        ];
    }

    public function profile($request) {
        $healthOfficialModel = new HealthOfficialModel;
        $show_fields = [ 
            'image', 'first_name', 'middle_name', 'last_name', 'position_id', 'gender_id', 'contact_no', 'civil_status_id', 'purok_id', 'street_building_house',
             'baranggay_id', 'status', 'email', 'birth_date', 'created_at', 'updated_at', 'updated_by', 'created_by', 'license_no'
        ];
        $show_fields = Helper::appendTable('health_officials', $show_fields);
        $show_fields = array_merge($show_fields, [
            'health_officials.id as health_official_id', 'TIMESTAMPDIFF(YEAR, health_officials.birth_date, CURDATE()) as age',
            'occupations.name as position', 'genders.name as gender', 'civil_statuses.name as civil_status', 'baranggays.name as baranggay', 'puroks.name as purok', 
            "concat(creator.first_name,' ', substring(creator.middle_name, 1, 1), '. ', creator.last_name, ' ', creator.suffix) as creator_name",
            "concat(updator.first_name,' ', substring(updator.middle_name, 1, 1), '. ', updator.last_name, ' ', updator.suffix) as updator_name",
        ]);
        $join_tables = [
            [ "LEFT", "occupations", "health_officials.position_id", "occupations.id"],
            [ "LEFT", "genders", "health_officials.gender_id", "genders.id"],
            [ "LEFT", "civil_statuses", "health_officials.civil_status_id", "civil_statuses.id"],
            [ "LEFT", "baranggays", "health_officials.baranggay_id", "baranggays.id"],
            [ "LEFT", "puroks", "health_officials.purok_id", "puroks.id"],

            [ "LEFT", "users as creator_user", "health_officials.created_by", "creator_user.id"],
            [ "LEFT", "health_officials as creator", "creator_user.health_official_id", "creator.id"],
            [ "LEFT", "users as updator_user", "health_officials.updated_by", "updator_user.id"],
            [ "LEFT", "health_officials as updator", "updator_user.health_official_id", "updator.id"],
        ];
        $wheres = [[ 'table' => 'health_officials', 'key' => 'id', 'value' => $request->id ]];
        $health_official = $healthOfficialModel->select($show_fields, $join_tables,  $wheres);

        $health_official = array_merge($health_official, [
            'birth_date' => Helper::humanDate('M d, Y', $health_official['birth_date']),
            'created_at' => Helper::humanDate('M d, Y', $health_official['created_at']),
            'updated_at' => Helper::humanDate('M d, Y', $health_official['updated_at']),
            'image_profile' => Helper::uploadedHealthOfficialImage($health_official['image']),
            'address' => $health_official['street_building_house'].', '.$health_official['purok']. ', '.$health_official['baranggay'],
            'fullname' => $health_official['first_name'] . ' '.$health_official['middle_name'].' '.$health_official['last_name'],
            'created_by' => $health_official['creator_name'],
            'updated_by' => $health_official['updator_name'],
        ]);

        // GET ALL STOCK TRANSACTIONS
        $transactions_model = new HealthOfficialModel;
        $results = $transactions_model->transactions($request->id);
        $transactions = [];

        foreach($results as $index => $transaction) {
            $items = $transaction['type'] == "stockin" ? $transaction['stockin'] : $transaction['stockout'];
            $person =  $transaction['type'] == "stockin" ? $transaction['sit_delivery'] : $transaction['sot_patient'];
            $date =  $transaction['type'] == "stockin" 
                ? Helper::humanDate('M d, Y', $transaction['sit_received_at'])
                : Helper::humanDate('M d, Y', $transaction['sot_dispenced_at']);
            $type = $transaction['type'] == "stockin" 
                ? '<span class="badge badge-secondary"><i class="fas fa-arrow-circle-left pr-1"></i> RECEIVED</span>'
                : '<span class="badge badge-default"><i class="fas fa-arrow-circle-right pr-1"></i> DISPENSED</span>';
            $action = '
                <button type="button" class="btn btn-icon btn-round btn-info btn-show-modal-medicines"  
                    data-id="'.$transaction['stock_transaction_id'].'" 
                    data-type="'.$transaction['type'].'" 
                    data-toggle="tooltip" data-placement="top" title="View record"
                >
                    <i class="fas fa-search"></i>
                </button>
            ';

            array_push($transactions, [
                'index' => $index + 1,
                'transaction_no' => app_code().'-'.str_pad($transaction['transaction_no'], 6, "0", STR_PAD_LEFT),
                'items' => $items,
                'person' => $person,
                'date' => $date,
                'type' => $type,
                'action' => $action,
            ]);
        };

        $log = new LogModel;
        $log->store([
            'requests' => json_encode($request),
            'ip' => Helper::getUserIP(),
            'module_id' => $healthOfficialModel->module,
            'action_id' => $healthOfficialModel->action_read,
            'record_id' => $request->id,
            'user_id' => $this->auth->id
        ]);
    
        return [
            "success" => true,
            "message" => "success",
            "data" => [
                "health_official" => $health_official,
                "transactions" => $transactions
            ],
        ];
    }

    public function update($request) {
        $file = '';

        if($request->image_to_upload != '') {
            $path = Helper::uploadedHealthOfficialPath();
            $file = Helper::uploadImage($request->image_to_upload, $path);
        }

        $healthOfficialModel = new HealthOfficialModel;
        $data = Helper::unsets((array) $request,  ['module', 'action', 'csrf_token', 'profileimg', 'image', 'image_to_upload']);
        $data['birth_date'] = Helper::dateParser($data['birth_date']); 
        $data['updated_by'] = $this->auth->id;
        $data['updated_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');

        if($request->image_to_upload != '') {
            $data['image'] = $file;
        }
        else {
            unset($data['image']);
        }

        if($healthOfficialModel->update($data)) {

            $log = new LogModel;
            $log->store([
                'requests' => json_encode($request),
                'ip' => Helper::getUserIP(),
                'module_id' => $healthOfficialModel->module,
                'action_id' => $healthOfficialModel->action_update,
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
        date_default_timezone_set('Asia/Manila');
        $healthOfficialModel = new HealthOfficialModel;
        $data = [
            'id' => $request->id,
            'status' => 'Inactive',
            'deleted_by' => $this->auth->id,
            'deleted_at' => date('Y-m-d H:i:s')
        ];

        if($healthOfficialModel->remove($data)) {

            $log = new LogModel;
            $log->store([
                'requests' => json_encode($request),
                'ip' => Helper::getUserIP(),
                'module_id' => $healthOfficialModel->module,
                'action_id' => $healthOfficialModel->action_delete,
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