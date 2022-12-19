<?php

namespace App\Controller;
use App\Model\PatientModel;
use App\Model\LogModel;
use App\Controller\BaseController;
use App\Helper\Helper;
use App\Model\Medicines\StockoutTransactionModel;
use App\Model\Medicines\StockoutMedicineModel;

class PatientController extends BaseController {

    public $auth = [];

    public function __construct() {
        $this->auth = json_decode(auth_user());
    }

    public function all() {
        $model = new PatientModel;
        $show_fields = [ 
            'image', 'first_name', 'middle_name', 'last_name', 'national_id', 'birth_date', 
            'philhealth_member', 'person_disability_id', 'status'
        ];
        $show_fields = Helper::appendTable('patients', $show_fields);
        $show_fields[] = 'patients.id as patient_id';
        $show_fields[] = 'genders.name as gender';
        $show_fields[] = 'person_disabilities.name as person_disability';
        $join_tables = [
            [ "LEFT", "genders", "patients.gender_id", "genders.id"],
            [ "LEFT", "person_disabilities", "patients.person_disability_id", "person_disabilities.id"],
        ];

        $patients = $model->selects($show_fields, $join_tables);
        $result = [];

        foreach($patients as $index => $patient) {
            $status_badge =  $patient['status'] == "Active" ? "secondary" : "default";
            $philhealth_member_status_badge =  $patient['philhealth_member'] == "Yes" ? "secondary" : "default";
            $pwd_status =  strtolower($patient['person_disability']) == "none" ? "default" : "secondary";
            $pwd = strtolower($patient['person_disability']) == "none" ? "NO" : "YES";

            $name = $patient['first_name'] . ' '.$patient['middle_name'][0].'. '.$patient['last_name'];
            $avatar_status = $patient['status'] == "Active" ? "avatar-online" : "avatar-offline";
            $url =  Helper::uploadedPatientImage($patient['image']);

            array_push($result, [
                'index' => $index + 1,
                'image' => '
                    <div class="avatar '.$avatar_status.'">
                        <img src="'.$url.'" alt="'.$name.'" class="avatar-img rounded-circle">
                    </div>
                ',
                'name' => $name,
                'national_id' => $patient['national_id'],
                'age' =>  Helper::age($patient['birth_date']),
                'gender' => $patient['gender'],
                'philhealth_member' => '<span class="badge badge-'.$philhealth_member_status_badge.'">'.strtoupper($patient['philhealth_member']).'</span>',
                'pwd' => '<span class="badge badge-'.$pwd_status.'">'.$pwd.'</span>',
                'status' => '<span class="badge badge-'.$status_badge.'">'.strtoupper($patient['status']).'</span>',
                'action' => '
                    <button type="button" class="btn btn-icon btn-round btn-info btn-show"  data-id="'.$patient['patient_id'].'" data-toggle="tooltip" data-placement="top" title="View record">
                        <i class="fas fa-search"></i>
                    </button>
                    <button type="button" class="btn btn-icon btn-round btn-warning btn-edit" data-id="'.$patient['patient_id'].'" data-toggle="tooltip" data-placement="top" title="Edit record">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-icon btn-round btn-danger btn-delete" data-id="'.$patient['patient_id'].'" data-toggle="tooltip" data-placement="top" title="Delete record">
                        <i class="fas fa-trash-alt"></i>
                    </button>'
            ]);
        }

        return [
            "success" => true,
            "message" => "success",
            "data" => $result
        ];
    }
    
    public function select2($request) {
        $patient = new PatientModel;
        $data = isset($request->q) ? [
            [ 
                "column" => "concat(patients.first_name,' ', substring(patients.middle_name, 1, 1), '. ', patients.last_name)" ,
                "parameter" => "name",
                "value" => $request->q
            ],
            [ 
                "column" =>  "concat(patients.street_building_house, ', ', puroks.name, ', ', baranggays.name)" ,
                "parameter" => "address",
                "value" => $request->q
            ],
            [ 
                "column" =>  "genders.name" ,
                "parameter" => "gender",
                "value" => $request->q
            ]
        ] : [];

        $fields = [
            'patients.id', 'image', 'genders.name as gender',
            "concat(patients.street_building_house, ', ', puroks.name, ', ', baranggays.name) as description",
            "concat(patients.first_name,' ', substring(patients.middle_name, 1, 1), '. ', patients.last_name) as title", 
            "concat(patients.first_name,' ', substring(patients.middle_name, 1, 1), '. ', patients.last_name) as text", 
        ];
        $fields = array_merge($fields, [
            'baranggays.name as baranggay',  'puroks.name as purok'
        ]);
        $join_tables = [
            [ "LEFT", "baranggays", "patients.baranggay_id", "baranggays.id"],
            [ "LEFT", "puroks", "patients.purok_id", "puroks.id"],
            [ "LEFT", "genders", "patients.gender_id", "genders.id"],
        ];
        $patient = $patient->searchAdvanced($fields, $join_tables, $data);

        return [
            "success" => true,
            "message" => "success",
            "data" => $patient
        ];


        $patients = new PatientModel;
        $data = isset($request->q) ? [ 'first_name' => $request->q, 'middle_name' => $request->q , 'last_name' => $request->q  ] : [];
        $fields = ['id', "concat(first_name,' ', substring(middle_name, 1, 1), '. ', last_name) as text"];
        $patients = $patients->search($fields, [], $data);

        return [
            "success" => true,
            "message" => "success",
            "data" => $patients
        ];
    }

    public function reportFilter($request) {
        $patient = new PatientModel;
        $data = isset($request->q) ? [
            [ 
                "column" => "concat(patients.first_name,' ', substring(patients.middle_name, 1, 1), '. ', patients.last_name)" ,
                "parameter" => "name",
                "value" => $request->q
            ],
            [ 
                "column" =>  "concat(patients.street_building_house, ', ', puroks.name, ', ', baranggays.name)" ,
                "parameter" => "address",
                "value" => $request->q
            ],
            [ 
                "column" =>  "genders.name" ,
                "parameter" => "gender",
                "value" => $request->q
            ]
        ] : [];

        $fields = [
            'patients.id', 'image', 'genders.name as gender',
            "concat(patients.street_building_house, ', ', puroks.name, ', ', baranggays.name) as description",
            "concat(patients.first_name,' ', substring(patients.middle_name, 1, 1), '. ', patients.last_name) as title", 
            "concat(patients.first_name,' ', substring(patients.middle_name, 1, 1), '. ', patients.last_name) as text", 
        ];
        $fields = array_merge($fields, [
            'baranggays.name as baranggay',  'puroks.name as purok'
        ]);
        $join_tables = [
            [ "LEFT", "baranggays", "patients.baranggay_id", "baranggays.id"],
            [ "LEFT", "puroks", "patients.purok_id", "puroks.id"],
            [ "LEFT", "genders", "patients.gender_id", "genders.id"],
        ];
        $patients = $patient->searchAdvanced($fields, $join_tables, $data);

        array_unshift($patients, ['id' => 'All' , 'text' => 'All']);

        return [
            "success" => true,
            "message" => "success",
            "data" => $patients
        ];
    }

    public function store($request) {
        $file = '';
        $patientModel = new PatientModel;
        $fullname = strtolower($request->first_name." ".$request->middle_name." ".$request->last_name);
        
        $data = Helper::allowOnly((array) $request, [
            'first_name', 'middle_name', 'last_name', 'national_id', 'philhealth_member', 'person_disability_id',
            'gender_id', 'birth_date', 'position_id', 'civil_status_id', 'citizenship_id', 'weight',
            'height', 'blood_type_id', 'educational_attainment_id', 'educational_attainment_id', 'email', 'contact_no',
            'baranggay_id', 'purok_id', 'street_building_house'
        ]);

        if(!$patientModel->isUniqueName($fullname)) {
            return [
                "success" => false,
                "message" => "Patient name is already exist."
            ];
        }

        if($request->image_to_upload != '') {
            $path = Helper::uploadedPatientPath();
            $file = Helper::uploadImage($request->image_to_upload, $path);
        }
        
        $patient_id =  $patientModel->lastInsertId(array_merge($data, [
            'birth_date' =>  Helper::dateParser($data['birth_date']),
            'created_by' => $this->auth->id,
            'image' => isset($request->image_to_upload) & $request->image_to_upload != null ? $file : null
        ]));

        $log = new LogModel;
        $log->store([
            'requests' => json_encode($request),
            'ip' => Helper::getUserIP(),
            'module_id' => $patientModel->module,
            'action_id' => $patientModel->action_add,
            'record_id' => $patient_id,
            'user_id' => $this->auth->id
        ]);

        return [
            "success" => true,
            "message" => "success"
        ];
    }

    public function show($request) {
        $patientModel = new PatientModel;
        $show_fields = [ 
            'image', 'first_name', 'middle_name', 'last_name', 'national_id', 'philhealth_member', 'birth_date',
            'email', 'contact_no',  'street_building_house', 'weight', 'height', 'status',
            'civil_status_id', 'person_disability_id', 'gender_id', 'position_id', 'citizenship_id',
            'baranggay_id', 'purok_id', 'blood_type_id', 'educational_attainment_id'
        ];

        $show_fields = Helper::appendTable('patients', $show_fields);
        $show_fields[] = 'patients.id as patient_id';
        $show_fields[] = 'civil_statuses.name as civil_status';
        $show_fields[] = 'person_disabilities.name as person_disability';
        $show_fields[] = 'genders.name as gender';
        $show_fields[] = 'occupations.name as position';
        $show_fields[] = 'citizenships.name as citizenship';
        $show_fields[] = 'baranggays.name as baranggay';
        $show_fields[] = 'puroks.name as purok';
        $show_fields[] = 'blood_types.name as blood_type';
        $show_fields[] = 'educational_attainments.name as educational_attainment';

        $join_tables = [
            [ "LEFT", "civil_statuses", "patients.civil_status_id", "civil_statuses.id"],
            [ "LEFT", "person_disabilities", "patients.person_disability_id", "person_disabilities.id"],
            [ "LEFT", "genders", "patients.gender_id", "genders.id"],
            [ "LEFT", "occupations", "patients.position_id", "occupations.id"],
            [ "LEFT", "citizenships", "patients.citizenship_id", "citizenships.id"],
            [ "LEFT", "baranggays", "patients.baranggay_id", "baranggays.id"],
            [ "LEFT", "puroks", "patients.purok_id", "puroks.id"],
            [ "LEFT", "blood_types", "patients.blood_type_id", "blood_types.id"],
            [ "LEFT", "educational_attainments", "patients.educational_attainment_id", "educational_attainments.id"],
        ];
        $wheres = [[ 'table' => 'patients', 'key' => 'id', 'value' => $request->id ]];
        $patient = $patientModel->select($show_fields, $join_tables,  $wheres);

        $patient['id'] = $patient['patient_id'];
        $patient['birth_date'] = Helper::dateParserShow($patient['birth_date']);
        $patient['image_profile']  =  Helper::uploadedPatientImage($patient['image']);
        $patient['civil_status_id'] = [
            "id" => $patient['civil_status_id'],
            "text" => $patient['civil_status'],
        ];
        $patient['person_disability_id'] = [
            "id" => $patient['person_disability_id'],
            "text" => $patient['person_disability'],
        ];
        $patient['gender_id'] = [
            "id" => $patient['gender_id'],
            "text" => $patient['gender'],
        ];
        $patient['position_id'] = [
            "id" => $patient['position_id'],
            "text" => $patient['position'],
        ];
        $patient['citizenship_id'] = [
            "id" => $patient['citizenship_id'],
            "text" => $patient['citizenship'],
        ];
        $patient['baranggay_id'] = [
            "id" => $patient['baranggay_id'],
            "text" => $patient['baranggay'],
        ];
        $patient['purok_id'] = [
            "id" => $patient['purok_id'],
            "text" => $patient['purok'],
        ];
        $patient['blood_type_id'] = [
            "id" => $patient['blood_type_id'],
            "text" => $patient['blood_type'],
        ];
        $patient['educational_attainment_id'] = [
            "id" => $patient['educational_attainment_id'],
            "text" => $patient['educational_attainment'],
        ];

        $log = new LogModel;
        $log->store([
            'requests' => json_encode($request),
            'ip' => Helper::getUserIP(),
            'module_id' => $patientModel->module,
            'action_id' => $patientModel->action_read,
            'record_id' => $request->id ,
            'user_id' => $this->auth->id
        ]);

        return [
            "success" => true,
            "message" => "success",
            "data" => $patient
        ];
    }

    public function profile($request) {
        $patientModel = new PatientModel;
        $show_fields = [ 
            'image', 'first_name', 'middle_name', 'last_name', 'national_id', 'philhealth_member', 'birth_date',
            'email', 'contact_no',  'street_building_house', 'weight', 'height', 'status',
            'civil_status_id', 'person_disability_id', 'gender_id', 'position_id', 'citizenship_id',
            'baranggay_id', 'purok_id', 'blood_type_id', 'educational_attainment_id',
            'created_at', 'updated_at', 'updated_by', 'created_by',
        ];
        $show_fields = Helper::appendTable('patients', $show_fields);
        $show_fields = array_merge($show_fields, [
            'patients.id as patient_id', 'civil_statuses.name as civil_status', 'person_disabilities.name as person_disability',
            'genders.name as gender', 'occupations.name as position',  'citizenships.name as citizenship', 'baranggays.name as baranggay',
            'puroks.name as purok', 'blood_types.name as blood_type', 'educational_attainments.name as educational_attainment',
            'blood_types.name as blood_type',  'educational_attainments.name as educational_attainment', 
           
            "concat(creator.first_name,' ', substring(creator.middle_name, 1, 1), '. ', creator.last_name, ' ', creator.suffix) as creator_name",
            "concat(updator.first_name,' ', substring(updator.middle_name, 1, 1), '. ', updator.last_name, ' ', updator.suffix) as updator_name",
        ]);

        $join_tables = [
            [ "LEFT", "civil_statuses", "patients.civil_status_id", "civil_statuses.id"],
            [ "LEFT", "person_disabilities", "patients.person_disability_id", "person_disabilities.id"],
            [ "LEFT", "genders", "patients.gender_id", "genders.id"],
            [ "LEFT", "occupations", "patients.position_id", "occupations.id"],
            [ "LEFT", "citizenships", "patients.citizenship_id", "citizenships.id"],
            [ "LEFT", "baranggays", "patients.baranggay_id", "baranggays.id"],
            [ "LEFT", "puroks", "patients.purok_id", "puroks.id"],
            [ "LEFT", "blood_types", "patients.blood_type_id", "blood_types.id"],
            [ "LEFT", "educational_attainments", "patients.educational_attainment_id", "educational_attainments.id"],

            [ "LEFT", "users as creator_user", "patients.created_by", "creator_user.id"],
            [ "LEFT", "health_officials as creator", "creator_user.health_official_id", "creator.id"],
            [ "LEFT", "users as updator_user", "patients.updated_by", "updator_user.id"],
            [ "LEFT", "health_officials as updator", "updator_user.health_official_id", "updator.id"], 
        ];
        $wheres = [[ 'table' => 'patients', 'key' => 'id', 'value' => $request->id ]];
        $patient = $patientModel->select($show_fields, $join_tables,  $wheres);

        $patient = array_merge($patient, [
           'id' =>  $patient['patient_id'],
           'age' =>  Helper::age($patient['birth_date']),
           'image_profile' =>  Helper::uploadedPatientImage($patient['image']),
           'address' =>  $patient['street_building_house'].', '.$patient['purok']. ', '.$patient['baranggay'],
           'fullname' => $patient['first_name'] . ' '.$patient['middle_name'].' '.$patient['last_name'],
            'created_by' => $patient['creator_name'],
            'created_at' => Helper::humanDate('M d, Y h:i A', $patient['created_at']),
            'updated_by' => $patient['updator_name'],
            'updated_at' =>  Helper::humanDate('M d, Y h:i A', $patient['updated_at']),
        ]);

        // GET LIST STOCKOUT TRANSACTION BY PATIENT ID
        $stockout_transaction_model = new StockoutTransactionModel;
        $show_fields = [ 'remarks', 'dispenced_at'];
        $show_fields = Helper::appendTable('stockout_transactions', $show_fields);
        $show_fields = array_merge($show_fields, [
            "stockout_transactions.id sot_id", "st.transaction_no",
            "concat(ho.first_name,' ', substring(ho.middle_name, 1, 1), '. ', ho.last_name) as receiver",
            "(SELECT COUNT(som.id) FROM stockout_medicines AS som WHERE som.stockout_transaction_id=stockout_transactions.id ) as items",
        ]);
        $join_tables = [
            [ "LEFT", "stock_transactions AS st", "stockout_transactions.id", "st.stock_transaction_id"],
            [ "LEFT", "health_officials as ho", "stockout_transactions.health_official_id", "ho.id"],
        ];

        $where_fields = [[ 'table' => 'stockout_transactions', 'key' => 'patient_id', 'value' => $request->id ]];
        $order_fields = [[ 'table' => 'stockout_transactions', 'key' => 'dispenced_at', 'value' => 'desc']];

        $results = $stockout_transaction_model->selectsAdvanced($show_fields, $join_tables, $where_fields, $order_fields);
        $transactions = [];

        foreach($results as $index => $medicine) {
            array_push($transactions, [
                'index' => $index + 1,
                'transaction_no' => app_code().'-'.str_pad($medicine['transaction_no'], 6, "0", STR_PAD_LEFT),
                'items' => $medicine['items'],
                'receiver' => $medicine['receiver'],
                'remarks' => $medicine['remarks'],
                'dispenced_at' => Helper::humanDate('M d, Y', $medicine['dispenced_at']),
                'action' => '
                    <button type="button" class="btn btn-icon btn-round btn-info btn-show-modal-medicines"  data-id="'.$medicine['sot_id'].'" data-toggle="tooltip" data-placement="top" title="View record">
                        <i class="fas fa-search"></i>
                    </button>
                  '
            ]);
        };

        // GET LIST STOCKOUT MEDICINE BY PATIENT ID
        $stockout_medicine_model = new StockoutMedicineModel;
        $show_fields = [ 'quantity', 'dosage'];
        $show_fields = Helper::appendTable('stockout_medicines', $show_fields);
        $show_fields = array_merge($show_fields, [
            "m.image", "m.name", "m.description", "st.transaction_no", "sot.dispenced_at",
            "concat(ho.first_name,' ', substring(ho.middle_name, 1, 1), '. ', ho.last_name) as dispencer"
        ]);
        $join_tables = [
            [ "LEFT", "stockout_transactions AS sot", "stockout_medicines.stockout_transaction_id", "sot.id"],
            [ "LEFT", "stock_transactions AS st", "sot.id", "st.stock_transaction_id"],
            [ "LEFT", "medicines AS m", "stockout_medicines.medicine_id", "m.id"],
            [ "LEFT", "health_officials as ho", "sot.health_official_id", "ho.id"],
        ];

        $where_fields = [[ 'table' => 'sot', 'key' => 'patient_id', 'value' => $request->id ]];
        $order_fields = [[ 'table' => 'sot', 'key' => 'dispenced_at', 'value' => 'desc']];

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
                'transaction_no' => app_code().'-'.str_pad($medicine['transaction_no'], 6, "0", STR_PAD_LEFT),
                'name' => $medicine['name'],
                'description' => $medicine['description'],
                'quantity' => $medicine['quantity'],
                'dosage' => $medicine['dosage'],
                'dispencer' => $medicine['dispencer'],
                'dispenced_at' => Helper::humanDate('M d, Y', $medicine['dispenced_at'])
            ]);
        };

        $log = new LogModel;
        $log->store([
            'requests' => json_encode($request),
            'ip' => Helper::getUserIP(),
            'module_id' => $patientModel->module,
            'action_id' => $patientModel->action_read,
            'record_id' => $request->id ,
            'user_id' => $this->auth->id
        ]);

        return [
            "success" => true,
            "message" => "success",
            "data" => [
                'patient' => $patient, 
                'transactions' => $transactions,
                'medicines' => $medicines
            ]
        ];
    }

    public function showTransactionDetials($request) {
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

    public function update($request) {
        $file = '';
        $patientModel = new PatientModel;

        $data = Helper::allowOnly((array) $request, [
            'first_name', 'middle_name', 'last_name', 'national_id', 'philhealth_member', 'person_disability_id', 
            'gender_id', 'birth_date', 'position_id', 'civil_status_id', 'citizenship_id', 'weight',
            'height', 'blood_type_id', 'educational_attainment_id', 'educational_attainment_id', 'email', 'contact_no',
            'baranggay_id', 'purok_id', 'street_building_house', 'id'
        ]);
        $data = array_merge($data, [
            'birth_date' => Helper::dateParser($data['birth_date']),
            'updated_by' => $this->auth->id,
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $fullname = strtolower($request->first_name." ".$request->middle_name." ".$request->last_name);

        if(!$patientModel->isUniqueName($fullname, $request->id)) {
            return [
                "success" => false,
                "message" => "Patient name is already exist."
            ];
        }

        if($request->image_to_upload != '') {
            $path = Helper::uploadedPatientPath();
            $file = Helper::uploadImage($request->image_to_upload, $path);
        }

        if($request->image_to_upload != '') {
            $data['image'] = $file;
        }
        else {
            unset($data['image']);
        }

        if($patientModel->update($data)) {

            $log = new LogModel;
            $log->store([
                'requests' => json_encode($request),
                'ip' => Helper::getUserIP(),
                'module_id' => $patientModel->module,
                'action_id' => $patientModel->action_update,
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
        $patientModel = new PatientModel;
        $data = [
            'id' => $request->id,
            'status' => 'Inactive',
            'deleted_by' => $this->auth->id,
            'deleted_at' => date('Y-m-d H:i:s')
        ];

        if($patientModel->remove($data)) {
            
            $log = new LogModel;
            $log->store([
                'requests' => json_encode($request),
                'ip' => Helper::getUserIP(),
                'module_id' => $patientModel->module,
                'action_id' => $patientModel->action_delete,
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