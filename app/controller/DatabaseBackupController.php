<?php

namespace App\Controller;
use App\Model\DatabaseBackupModel;
use App\Model\LogModel;
use App\Controller\BaseController;
use App\Helper\Helper;

class DatabaseBackupController extends BaseController {

    public $auth = [];

    public function __construct() {
        $this->auth = json_decode(auth_user());
    }

    public function all() {
        $databaseBackupModel = new DatabaseBackupModel();
        $show_fields = [ 
            'database_backups.file_size', 'database_backups.file_name', 'database_backups.created_at', 'database_backups.id as backup_id', 
            "concat(creator.first_name,' ', substring(creator.middle_name, 1, 1), '. ', creator.last_name, ' ', creator.suffix) as creator_name",
        ];
        $join_tables = [
            [ "LEFT", "users as creator_user", "database_backups.created_by", "creator_user.id"],
            [ "LEFT", "health_officials as creator", "creator_user.health_official_id", "creator.id"],
        ];

        $backup_results = $databaseBackupModel->selects($show_fields, $join_tables);
        $backups = [];

        foreach($backup_results as $index => $result) {
            array_push($backups, [
                'index' => $index + 1,
                'file_name' => $result['file_name'],
                'file_size' => $result['file_size'],
                'created_at' =>  Helper::humanDate('M d, Y h:i A', $result['created_at']),
                'created_by' => $result['creator_name'],
                'action' => '
                    <button type="button" class="btn btn-icon btn-round btn-secondary btn-download" data-file="'. $result['file_name'].'" data-id="'.$result['backup_id'].'" data-toggle="tooltip" data-placement="top" title="Download backup">
                        <i class="fas fa-download"></i>
                    </button>
                    <button type="button" class="btn btn-icon btn-round btn-danger btn-delete" data-file="'. $result['file_name'].'" data-id="'.$result['backup_id'].'" data-toggle="tooltip" data-placement="top" title="Delete backup">
                        <i class="fas fa-trash-alt"></i>
                    </button>
                '
            ]);
        }

        return [
            "success" => true,
            "message" => "success",
            "data" => $backups
        ];
    }

    public function backup($request) {
        $file_name = strtolower(app_code()) . '_backup_' . date('d-m-Y') . '_' . time() . '.sql';
        $path =  '../database/backups/';

       $databaseBackupModel = new DatabaseBackupModel();
       $backup = $databaseBackupModel->backup($file_name, $path);
       $file_size = $databaseBackupModel->getFileSize($file_name);

       if(!$backup) {
            return [
                "success" => true,
                "message" => "success"
            ];
       }
     
        $backup_id =  $databaseBackupModel->lastInsertId([
            "file_name" => $file_name . '.zip',
            "file_size" => $file_size,
            "created_by" => $this->auth->id, 
        ]);

        $log = new LogModel;
        $log->store([
            'requests' => json_encode($request),
            'ip' => Helper::getUserIP(),
            'module_id' => $databaseBackupModel->module,
            'action_id' => $databaseBackupModel->action_add,
            'record_id' => $backup_id,
            'user_id' => $this->auth->id
        ]);


        return [
            "success" => true,
            "message" => "success"
        ];
    }

    public function remove($request) {
        $databaseBackupModel = new DatabaseBackupModel;
        $data = [
            'id' => $request->id,
            'deleted_by' =>  $this->auth->id,
            'deleted_at' => date('Y-m-d H:i:s')
        ];

        $path = '../database/backups/'.$request->file_name;

        if (file_exists($path)) {
            unlink($path);
        }

        if($databaseBackupModel->remove($data)) {
            
            $log = new LogModel;
            $log->store([
                'requests' => json_encode($request),
                'ip' => Helper::getUserIP(),
                'module_id' => $databaseBackupModel->module,
                'action_id' => $databaseBackupModel->action_delete,
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

    public function download($request) {
        $databaseBackupModel = new DatabaseBackupModel;

        $log = new LogModel;
        $log->store([
            'requests' => json_encode($request),
            'ip' => Helper::getUserIP(),
            'module_id' => $databaseBackupModel->module,
            'action_id' => $databaseBackupModel->action_download,
            'record_id' => $request->id,
            'user_id' => $this->auth->id
        ]);

        return [
            "success" => true,
            "message" => "success",
            "data" => [
                "path" => app_backup_path().$request->file_name
            ]
        ];
    }
}
?>