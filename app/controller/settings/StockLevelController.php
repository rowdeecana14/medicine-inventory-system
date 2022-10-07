<?php

namespace App\Controller\Settings;
use App\Model\Settings\StockLevelModel;
use App\Model\LogModel;
use App\Controller\BaseController;
use App\Helper\Helper;

class StockLevelController extends BaseController {

    public $auth = [];

    public function __construct() {
        $this->auth = json_decode(auth_user());
    }

    public function all() {
        $model = new StockLevelModel;

        $show_fields = [ 
            'stock_levels.id as sl_id', 'stock_levels.quantity', 'stock_levels.level', 'stock_levels.updated_at',
            "concat(updator.first_name,' ', substring(updator.middle_name, 1, 1), '. ', updator.last_name, ' ', updator.suffix) as updator_name",
        ];
        $join_tables = [
            [ "LEFT", "users as updator_user", "stock_levels.updated_by", "updator_user.id"],
            [ "LEFT", "health_officials as updator", "updator_user.health_official_id", "updator.id"], 
        ];
        $wheres = [];
        $result = [];
        $levels = $model->selects($show_fields, $join_tables, $wheres);

        foreach($levels as $index => $level) {
            $badge =  "";
            $action = '
                <button type="button" class="btn btn-icon btn-round btn-warning btn-edit" data-id="'.$level['sl_id'].'" data-toggle="tooltip" data-placement="top" title="Edit record">
                    <i class="fas fa-edit"></i>
                </button>
            ';

            if( $level['level'] == "Low") {
                $badge =  "danger";
            }
            else if( $level['level'] == "Moderate") {
                $badge = "warning";
            }
            else {
                $badge = "info";
            }
            
            array_push($result, [
                'index' => $index + 1,
                'quantity' => $level['quantity'],
                'level' => '<span class="badge badge-'.$badge.'">'.strtoupper($level['level']).'</span>',
                'updated_by' => $level['updator_name'],
                'updated_at' => Helper::humanDate('M d, Y h:i A', $level['updated_at']),
                'action' => $action
            ]);
        }

        return [
            "success" => true,
            "message" => "success",
            "data" => $result
        ];
    }

    public function show($request) {
        $level_model = new StockLevelModel;
        $show_fields = [ 
            'stock_levels.id as sl_id', 'stock_levels.quantity', 'stock_levels.level', 'stock_levels.updated_at',
        ];
        $wheres = [[ 'table' => 'stock_levels', 'key' => 'id', 'value' => $request->id ]];
        $level = $level_model->select($show_fields, [],  $wheres);
        $label = "";

        if( $level['level'] == "Low") {
            $label = "0 - ".$level['quantity'];
        }
        else if( $level['level'] == "Moderate") {
            // LOW LEVEL 
            $wheres = [[ 'table' => 'stock_levels', 'key' => 'id', 'value' => 1]];
            $low_level = $level_model->select($show_fields, [],  $wheres);
            $label = ($low_level['quantity'] + 1)." - ".$level['quantity'];
        }
        else {
            $wheres = [[ 'table' => 'stock_levels', 'key' => 'id', 'value' => 2]];
            $moderate_level = $level_model->select($show_fields, [],  $wheres);
            $label = ($moderate_level['quantity'] + 1)." - ".$level['quantity'];
        }

        // LOW LEVEL 
        $wheres = [[ 'table' => 'stock_levels', 'key' => 'id', 'value' => 1]];
        $low_level = $level_model->select($show_fields, [],  $wheres);

       
        // HIGH LEVEL 
        $wheres = [[ 'table' => 'stock_levels', 'key' => 'id', 'value' => 2]];
        $high_level = $level_model->select($show_fields, [],  $wheres);

        $log = new LogModel;
        $log->store([
            'requests' => json_encode($request),
            'ip' => Helper::getUserIP(),
            'module_id' => $level_model->module,
            'action_id' => $level_model->action_read,
            'record_id' => $request->id,
            'user_id' => $this->auth->id
        ]);

        return [
            "success" => true,
            "message" => "success",
            "data" => [
                "id" => $level['sl_id'],
                "level" =>  $level['level'],
                "quantity" =>  $level['quantity'],
                "label" => $label,
            ] 
        ];
    }

    public function update($request) {
        $level_model = new StockLevelModel;

        if($request->level == "Low") {
            $show_fields = [ 'stock_levels.quantity'];
            $wheres = [[ 'table' => 'stock_levels', 'key' => 'id', 'value' => 2 ]];
            $moderate_level = $level_model->select($show_fields, [],  $wheres);
    
            if((int) $moderate_level['quantity'] <=  (int) $request->quantity) {
                return [
                    "success" => false,
                    "message" => "The quantity must be lower than ".$moderate_level['quantity']. ' (Moderate level).'
                ];
            }   
        }
        else if($request->level == "Moderate") {
            $show_fields = [ 'stock_levels.quantity'];
            $wheres = [[ 'table' => 'stock_levels', 'key' => 'id', 'value' => 3 ]];
            $high_level = $level_model->select($show_fields, [],  $wheres);

            $show_fields = [ 'stock_levels.quantity'];
            $wheres = [[ 'table' => 'stock_levels', 'key' => 'id', 'value' => 1 ]];
            $low_level = $level_model->select($show_fields, [],  $wheres);
    
            if((int) $high_level['quantity'] <=  (int) $request->quantity) {
                return [
                    "success" => false,
                    "message" => "The quantity must be lower than ".$high_level['quantity']. ' (High level).'
                ];
            }
            if((int) $low_level['quantity'] >=  (int) $request->quantity) {
                return [
                    "success" => false,
                    "message" => "The quantity must be greater than ".$low_level['quantity']. ' (Low level).'
                ];
            }   
        }
        else if($request->level == "High") {
            $show_fields = [ 'stock_levels.quantity'];
            $wheres = [[ 'table' => 'stock_levels', 'key' => 'id', 'value' => 2 ]];
            $moderate_level = $level_model->select($show_fields, [],  $wheres);
    
            if((int) $moderate_level['quantity'] >=  (int) $request->quantity) {
                return [
                    "success" => false,
                    "message" => "The quantity must be greater than ".$moderate_level['quantity']. ' (Moderate level).'
                ];
            }   
        }

        $data = [
            'id' => $request->id,
            'quantity' => $request->quantity,
            'updated_by' => $this->auth->id,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        if($level_model->update($data)) {
            $log = new LogModel;
            $log->store([
                'requests' => json_encode($request),
                'ip' => Helper::getUserIP(),
                'module_id' => $level_model->module,
                'action_id' => $level_model->action_update,
                'record_id' => $request->id,
                'user_id' => $this->auth->id
            ]);

            return [
                "success" => true,
                "message" => "success",
            ];
        }

        return [
            "success" => false,
            "message" => "error"
        ];
    }

    public function reportFilter($request) {
        $model = new StockLevelModel;
        $data = isset($request->q) ? [ 'level' => $request->q ] : [];
        $fields = ['quantity as id', 'level as text'];
        $levels = $model->search($fields, [], $data);
        array_unshift($levels, ['id' => 'All' , 'text' => 'All']);

        $wheres = [[ 'table' => 'stock_levels', 'key' => 'id', 'value' => 3]];
        $high_level = $model->select(['quantity'], [],  $wheres);
        $high_level = isset($high_level['quantity']) ? $high_level['quantity'] : 0;

        array_push($levels, ['id' =>  $high_level + 1, 'text' => 'Excelent']);

        return [
            "success" => true,
            "message" => "success",
            "data" => $levels
        ];
    }

}
?>