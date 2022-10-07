<?php

namespace App\Controller\Medicines;
use App\Model\Medicines\MedicineModel;
use App\Model\Medicines\StockExpirationModel;
use App\Model\LogModel;
use App\Controller\BaseController;
use App\Helper\Helper;

class ExpirationController extends BaseController {

    public $auth = [];

    public function __construct() {
        $this->auth = json_decode(auth_user());
    }

    public function all() {
        $medicineModel = new MedicineModel;
        $show_fields = [ 
            'image', 'name', 'description', 'status', 'category_id', 'type_id', 
        ];
        $show_fields = Helper::appendTable('medicines', $show_fields);
        $show_fields = array_merge($show_fields, [
            'medicines.id as medicine_id', 'categories.name as category', 'types.name as type',
            "(SELECT SUM(sim.quantity) FROM stockin_medicines AS sim WHERE sim.medicine_id=medicines.id ) as stockin",
            "(SELECT SUM(som.quantity) FROM stockout_medicines AS som WHERE som.medicine_id=medicines.id ) as stockout",
            "(SELECT SUM(ss.quantity) FROM stock_expiries AS ss WHERE  DATEDIFF(ss.expired_at, CURRENT_DATE) <= 0 AND ss.medicine_id=medicines.id) as expired"
        ]);
        $join_tables = [
            [ "LEFT", "categories", "medicines.category_id", "categories.id"],
            [ "LEFT", "types", "medicines.type_id", "types.id"],
        ];

        $medicines = $medicineModel->selects($show_fields, $join_tables);
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
                'medicine' => $medicine['name'],
                'description' => $medicine['description'],
                'expired' => (int) $medicine['expired'],
                'available' => ((int) $medicine['stockin'] - (int)  $medicine['stockout']) - (int) $medicine['expired'],
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

            'creator.suffix as creator_suffix', 'creator.first_name as creator_first_name', 
            'creator.middle_name as creator_middle_name', 'creator.last_name as creator_last_name',
            'updator.suffix as updator_suffix', 'updator.first_name as updator_first_name',
            'updator.middle_name as updator_middle_name', 'updator.last_name as updator_last_name'
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
            'received' =>  (int)  $medicine['stockin'],
            'dispenced' => (int)  $medicine['stockout'],
            'expired' => (int) $medicine['expired'],
        ]);

        // GET LIST OF EXPIRATIONS
        $stockExpirationModel = new StockExpirationModel;
        $show_fields = [ 'stock_expiries.quantity', 'stock_expiries.expired_at', 'DATEDIFF(stock_expiries.expired_at, CURRENT_DATE) as days'];
        $join_tables = [
            [ "LEFT", "medicines AS m", "stock_expiries.medicine_id", "m.id"],
        ];
        $where_fields = [[ 'table' => 'stock_expiries', 'key' => 'medicine_id', 'value' => $request->id ]];
        $order_fields = [[ 'table' => 'stock_expiries', 'key' => 'expired_at', 'value' => 'asc']];
        $results = $stockExpirationModel->selectsAdvanced($show_fields, $join_tables, $where_fields, $order_fields);
        $expiries = [];

        foreach($results as $index => $expiration) {
            $days = $expiration['days'] <= 0 
            ?  '<span class="badge badge-default">EXPIRED</span>'
            :  '<span class="badge badge-danger">'.$expiration['days'].' DAY(S)</span>';

            array_push($expiries, [
                'index' => $index + 1,
                'quantity' => $expiration['quantity'],
                'expired_at' =>  Helper::humanDate('M d, Y', $expiration['expired_at']),
                'days' => $days
            ]);
        };

        $logModel = new LogModel;
        $logModel->store([
            'requests' => json_encode($request),
            'ip' => Helper::getUserIP(),
            'module_id' => $stockExpirationModel->module,
            'action_id' => $stockExpirationModel->action_read,
            'record_id' => $request->id,
            'user_id' => $this->auth->id
        ]);

        return [
            "success" => true,
            "message" => "success",
            "data" => [
                "medicine" => $medicine,
                "expiries" => $expiries
            ]
        ];
    }

    public function edit($request) {
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

            'creator.suffix as creator_suffix', 'creator.first_name as creator_first_name', 
            'creator.middle_name as creator_middle_name', 'creator.last_name as creator_last_name',
            'updator.suffix as updator_suffix', 'updator.first_name as updator_first_name',
            'updator.middle_name as updator_middle_name', 'updator.last_name as updator_last_name'
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
            'received' =>  (int)  $medicine['stockin'],
            'dispenced' => (int)  $medicine['stockout'],
            'expired' => (int) $medicine['expired'],
        ]);

        // GET LIST OF EXPIRATIONS
        $stockExpirationModel = new StockExpirationModel;
        $show_fields = [ 'stock_expiries.quantity', 'stock_expiries.expired_at', 'stock_expiries.id', 'DATEDIFF(stock_expiries.expired_at, CURRENT_DATE) as days'];
        $join_tables = [
            [ "LEFT", "medicines AS m", "stock_expiries.medicine_id", "m.id"],
        ];
        $where_fields = [[ 'table' => 'stock_expiries', 'key' => 'medicine_id', 'value' => $request->id ]];
        $order_fields = [[ 'table' => 'stock_expiries', 'key' => 'expired_at', 'value' => 'asc']];
        $results = $stockExpirationModel->selectsAdvanced($show_fields, $join_tables, $where_fields, $order_fields);
        $expiries = [];

        foreach($results as $index => $expiration) {

            $days = $expiration['days'] <= 0 
                ?  '<span class="badge badge-default">EXPIRED</span>'
                :  '<span class="badge badge-danger">'.$expiration['days'].' DAY(S)</span>';
                
            array_push($expiries, [
                'index' => $index + 1,
                'quantity' => $expiration['quantity'],
                'expired_at' =>  Helper::humanDate('M d, Y', $expiration['expired_at']),
                'days' => $days,
                'action' => ' 
                    <button type="button" class="btn btn-icon btn-round btn-warning btn-edit-expiration" data-id="'.$expiration['id'].'" data-toggle="tooltip" data-placement="top" title="Edit record">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-icon btn-round btn-danger btn-delete-expiration" data-id="'.$expiration['id'].'" data-toggle="tooltip" data-placement="top" title="Delete record">
                        <i class="fas fa-trash-alt"></i>
                    </button>'
            ]);
        };

        $logModel = new LogModel;
        $logModel->store([
            'requests' => json_encode($request),
            'ip' => Helper::getUserIP(),
            'module_id' => $stockExpirationModel->module,
            'action_id' => $stockExpirationModel->action_read,
            'record_id' => $request->id,
            'user_id' => $this->auth->id
        ]);

        return [
            "success" => true,
            "message" => "success",
            "data" => [
                "medicine" => $medicine,
                "expiries" => $expiries
            ]
        ];
    }
    
    public function storeExpiration($request) {
        $stockExpirationModel = new StockExpirationModel;

        $medicine = $stockExpirationModel->getAvailableStock($request->id);
        $available = ((int) $medicine['stockin'] - (int)  $medicine['stockout']) - (int) $medicine['expired'];

        if($available < (int) $request->quantity) {
            return [
                "success" => false,
                "validation" => false,
                "message" => "The avaialble stock only is ".$available.".",
            ];
        }

        $expired = $stockExpirationModel->getCountExpiredDate(Helper::dateParser($request->expired_at), $request->id);
        $total_expired = (int) $expired['total'];

        if($total_expired > 0) {
            return [
                "success" => false,
                "validation" => false,
                "data" => $expired,
                "message" => "This date " . Helper::humanDate('M d, Y', $request->expired_at) ." is already set.",
            ];
        }

        $data = Helper::allowOnly((array) $request, [ 'expired_at', 'quantity']);
        $data = array_merge($data, [
            "expired_at" => Helper::dateParser($data['expired_at']),
            "medicine_id" => $request->id,
            "created_by" => $this->auth->id, 
        ]);
        $expiration_id =  $stockExpirationModel->lastInsertId($data);

        $logModel = new LogModel;
        $logModel->store([
            'requests' => json_encode($request),
            'ip' => Helper::getUserIP(),
            'module_id' => $stockExpirationModel->module,
            'action_id' => $stockExpirationModel->action_add,
            'record_id' => $expiration_id,
            'user_id' => $this->auth->id
        ]);

        return [
            "success" => true,
            "message" => "success"
        ];
    }

    public function showExpiration($request) {
        $stockExpirationModel = new StockExpirationModel;
        $expiration = $stockExpirationModel->show(['id' => $request->id]);
        $expiration['expired_at'] = Helper::dateParserShow($expiration['expired_at']);

        $logModel = new LogModel;
        $logModel->store([
            'requests' => json_encode($request),
            'ip' => Helper::getUserIP(),
            'module_id' => $stockExpirationModel->module,
            'action_id' => $stockExpirationModel->action_read,
            'record_id' => $request->id,
            'user_id' => $this->auth->id
        ]);

        return [
            "success" => true,
            "message" => "success",
            "data" => $expiration
        ];
    }

    public function updateExpiration($request) {
        $stockExpirationModel = new StockExpirationModel;

        $expiration = $stockExpirationModel->getOneExpiration($request->id);
        $quantity = (int) $expiration['quantity'];

        $medicine = $stockExpirationModel->getAvailableStock($request->medicine_id);
        $available = ((int) $medicine['stockin'] - (int)  $medicine['stockout']) - (int) $medicine['expired'];

        if($quantity < $request->quantity) {
            if($available < (int) $request->quantity) {
                return [
                    "success" => false,
                    "validation" => false,
                    "message" => "The avaialble stock only is ".$available.".",
                    "data" => $medicine
    
                ];
            }
        }

        $expired = $stockExpirationModel->getCountExpiredDateUpdate(Helper::dateParser($request->expired_at), $request->medicine_id, $request->id);
        $total_expired = (int) $expired['total'];

        if($total_expired > 0) {
            return [
                "success" => false,
                "validation" => false,
                "data" => $expired,
                "message" => "This date " . Helper::humanDate('M d, Y', $request->expired_at) ." is already set.",
            ];
        }


        $data = Helper::allowOnly((array) $request, ['id', 'expired_at', 'quantity']);
        $data = array_merge($data, [
            "expired_at" => Helper::dateParser($data['expired_at']),
            "updated_by" => $this->auth->id, 
            "updated_at" => date('Y-m-d H:i:s'),
        ]);

        if($stockExpirationModel->update($data)) {
            $log = new LogModel;
            $log->store([
                'requests' => json_encode($request),
                'ip' => Helper::getUserIP(),
                'module_id' => $stockExpirationModel->module,
                'action_id' => $stockExpirationModel->action_update,
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

    public function removeExpiration($request) {
        $stockExpirationModel = new StockExpirationModel;
        $data = [
            'id' => $request->id,
            'deleted_by' => $this->auth->id,
            'deleted_at' => date('Y-m-d H:i:s')
        ];

        if($stockExpirationModel->remove($data)) {
            
            $logModel = new LogModel;
            $logModel->store([
                'requests' => json_encode($request),
                'ip' => Helper::getUserIP(),
                'module_id' => $stockExpirationModel->module,
                'action_id' => $stockExpirationModel->action_delete,
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