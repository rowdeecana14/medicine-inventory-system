<?php
namespace App\Model;

use App\Helper\Helper;
use App\Model\BaseModel;
use App\Model\Settings\GenderModel;
use App\Model\Settings\PersonDisabilityModel;

class PatientModel extends BaseModel {  
  private static $table = 'patients';
  private static $order_by = [ "patients.first_name", "asc"];
  private static $fillable = [];

  public $module = 13;
  public $action_add = 1;
  public $action_update = 2;
  public $action_delete = 3;
  public $action_read = 4;

  public function getFillable() {
      return self::$fillable;
   }

   public function getTable() {
       return self::$table;
   }

   public function getOrderBy() {
       return self::$order_by;
   }

  public function transactions($id) {
      $this->sql = "
          SELECT 
          st.transaction_no, st.type,
          sim.quantity AS sit_quantity, sim.medicine_id as sit_medicine_id, sit.remarks as sit_remarks, sit.received_at as sit_received_at, sit.delivery_person as sit_delivery, 
          CONCAT(sit_ho.first_name,' ', SUBSTRING(sit_ho.middle_name, 1,1), '. ', sit_ho.last_name) as sit_health_official,
          
          som.quantity as sot_quantity, som.medicine_id as sit_medicine_id, sot.remarks as sot_remarks, sot.dispenced_at as sot_dispenced_at,
          CONCAT(sot_p.first_name,' ', SUBSTRING(sot_p.middle_name, 1,1), '. ', sot_p.last_name) as sot_patient,
          CONCAT(sot_ho.first_name,' ', SUBSTRING(sot_ho.middle_name, 1,1), '. ', sot_ho.last_name) as sot_health_official
          FROM stock_transactions AS st
          LEFT JOIN stockin_transactions AS sit ON st.stock_transaction_id=sit.id
          LEFT JOIN stockin_medicines AS sim ON sit.id=sim.stockin_transaction_id
          LEFT JOIN health_officials AS sit_ho ON sit.health_official_id=sit_ho.id
          
          LEFT JOIN stockout_transactions AS sot ON st.stock_transaction_id=sot.id
          LEFT JOIN stockout_medicines AS som ON sot.id=som.stockout_transaction_id
          LEFT JOIN patients AS sot_p ON sot.patient_id=sot_p.id
          LEFT JOIN health_officials AS sot_ho ON sot.health_official_id=sot_ho.id
          WHERE sim.medicine_id=:sit_m_id OR som.medicine_id=:sot_m_id
        ";
      $this->statement  = $this->connection()->prepare($this->sql);
      $this->statement->bindValue(':sit_m_id', $id);
      $this->statement->bindValue(':sot_m_id', $id);
      $this->statement->execute();

      return $this->statement ->fetchAll();
  }

  public function isUniqueName($fullname = "", $id="") {
    if($id != '') {
      $sql = "SELECT *  FROM ".self::$table." 
        WHERE CONCAT(LOWER(first_name),' ', LOWER(middle_name), ' ', LOWER(last_name))=:fullname 
        AND id !=:id AND deleted_by IS NULL
      ";
      $statement = self::connection()->prepare($sql);
      $statement->bindValue(':fullname', $fullname);
      $statement->bindValue(':id', $id);
    }
    else {
      $sql = "SELECT *  FROM ".self::$table." WHERE CONCAT(LOWER(first_name),' ', LOWER(middle_name), ' ', LOWER(last_name))=:fullname  AND deleted_by IS NULL";
      $statement = self::connection()->prepare($sql);
      $statement->bindValue(':fullname', $fullname);
    }
    
    $statement->execute();

    return ($statement->rowCount() == 0) ? true : false;
  }
}

?>