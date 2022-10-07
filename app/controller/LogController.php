<?php

namespace App\Controller;
use App\Model\LogModel;
use App\Helper\Helper;

class LogController extends BaseController {

    public function all() {
        
        $model = new LogModel;
        $logs = $model->logs();
        $result = [];

        foreach($logs as $index => $log) {
            $action  = '';
            $badges = [
                1 => 'badge-primary',
                2 => 'badge-warning',
                3 => 'badge-danger',
                4 => 'badge-info',
                5 => 'badge-secondary',
                6 => 'badge-default',
                7 => 'badge-default',
                8 => 'badge-default',
                9 => 'badge-default',
            ];

            $url =  Helper::uploadedHealthOfficialImage($log['image']);
            $health_official = $log['h_first_name'];
            $action_badge = '<span class="badge '.$badges[$log['action_id']].'">'.strtoupper($log['action']).'</span>';

            if(in_array($log['action_id'], [6, 7])) {
                $action = $action_badge.' '.substr(strtolower($log['module']), 0, -1);
            }
            else {
                if($log['module_id'] == 2) {
                    $action = $action_badge. ' category record, id: '.$log['record_id'];
                }
                else if($log['module_id'] == 5) {
                    $action = $action_badge. ' person disability record, id: '.$log['record_id'];
                }
                else if($log['module_id'] == 11) {
                    $action = $action_badge. ' civil status record, id: '.$log['record_id'];
                }
                else if($log['module_id'] == 16) {
                    $action = $action_badge. ' stocks receiving record, id: '.$log['record_id'];
                }
                else if($log['module_id'] == 17) {
                    $action = $action_badge. ' stocks dispencing record, id: '.$log['record_id'];
                }
                else if($log['module_id'] == 18) {
                    $action = $action_badge. ' stocks expiration record, id: '.$log['record_id'];
                }
                else {
                    $action =$action_badge.' '.substr(strtolower($log['module']), 0, -1).' record, id: '.$log['record_id'];
                }
            }
            
            array_push($result, [
                'index' => $index + 1,
                'image' =>  '
                    <div class="avatar ">
                    <img src="'.$url.'" alt="'.$health_official.'" class="avatar-img rounded-circle">
                    </div>',
                'name' => $health_official,
                'role' => $log['position'],
                'module' => ucwords($log['module']),
                'action' => $action,
                'datetime' =>  date('M d, Y h:i A', strtotime($log['datetime'])),
            ]);
        }

        return [
            "success" => true,
            "message" => "success",
            "data" => [
                'logs' => $result,
                'year' => date('Y')
            ]
        ];
    }
}
?>