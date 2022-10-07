<?php

namespace App\Controller;
use App\Controller\BaseController;
use App\Model\Settings\UserModel;
use App\Model\Settings\StockLevelModel;
use App\Model\HealthOfficialModel;
use App\Model\Medicines\MedicineModel;
use App\Model\PatientModel;
use App\Helper\Helper;

class DashboardController extends BaseController {

    public function widgets() {
        $model = new UserModel;
        $wheres = [[ 'table' => 'users', 'key' => 'status', 'value' => 'Active' ]];
        $users_count = $model->rowsCount([], $wheres);

        $model = new HealthOfficialModel;
        $wheres = [[ 'table' => 'health_officials', 'key' => 'status', 'value' => 'Active' ]];
        $health_officials_count = $model->rowsCount([], $wheres);

        $model = new PatientModel;
        $wheres = [[ 'table' => 'patients', 'key' => 'status', 'value' => 'Active' ]];
        $patients_count = $model->rowsCount([], $wheres);

        $medicine_model = new MedicineModel;
        $inventories = $medicine_model->count();

        return [
            'data' => [
                'users' => $users_count,
                'health_officials' =>  $health_officials_count,
                'patients' =>  $patients_count,
                'inventories' => $inventories,
            ]
        ];
    }

    public function listings() {
        $model = new StockLevelModel;
        $wheres = [[ 'table' => 'stock_levels', 'key' => 'id', 'value' => 1]];
        $low_level = $model->select(['quantity'], [],  $wheres);
        $low_level = isset($low_level['quantity']) ? $low_level['quantity'] : 0;

        $medicine_model = new MedicineModel;
        $low_stocks = $medicine_model->lowStocks($low_level);

        foreach($low_stocks as $index => $medicine) {
            $low_stocks[$index]['image'] = Helper::uploadedMedicineImage($medicine['image']);
        }

        $expiring_stocks = $medicine_model->expiringStocks();

        foreach($expiring_stocks as $index => $medicine) {
            $expiring_stocks[$index]['image'] = Helper::uploadedMedicineImage($medicine['image']);
            $expiring_stocks[$index]['expired_at'] = Helper::humanDate('M d, Y', $medicine['expired_at']);
        }

        return [
            'data' => [
                'low_stocks' => $low_stocks,
                'expiring_stocks' => $expiring_stocks
            ]
        ];
    }

    public function pieGraph() {
        $color_list = array_values(Helper::colorLists());
        $medicine_model = new MedicineModel;

        $availables = $medicine_model->stockByCategories();
        $avail_labels = [];
        $avail_values = [];
        $avail_colors = [];

        $expiries = $medicine_model->expiredByCategories();
        $exp_labels = [];
        $exp_values = [];
        $exp_colors = [];
    
        foreach($availables as $index => $category) {
            array_push($avail_colors, $color_list[$index]);
            array_push($avail_labels, strtoupper($category['category']));
            array_push($avail_values, $category['quantity']);
        }

        foreach($expiries as $index => $category) {
            array_push($exp_colors, $color_list[$index]);
            array_push($exp_labels, strtoupper($category['category']));
            array_push($exp_values, $category['quantity']);
        }

        return [
            'data' => [
                'available_stocks' => [
                    'colors' => $avail_colors,
                    'labels' => $avail_labels,
                    'values' => $avail_values,
                ],
                'expired_stocks' => [
                    'colors' => $exp_colors,
                    'labels' => $exp_labels,
                    'values' => $exp_values,
                ]
            ]
        ];
    }
   
    public function lineGraphs() {
        $medicine_model = new MedicineModel;
        $month_list = ['01', '02', '03', '04', '05', '06', '07','08', '09', '10', '11', '12'];
        $monthly_received = [];
        $monthly_dispensed = [];
        $monthly_expired = [];

        foreach($month_list as $month) {
            array_push($monthly_received, $medicine_model->monthlyReceived($month));
            
            if($month == date('m')) {
                break;
            }
        }

        foreach($month_list as $month) {
            array_push($monthly_dispensed, $medicine_model->monthlyDespensed($month));
            
            if($month == date('m')) {
                break;
            }
        }

        foreach($month_list as $month) {
            array_push($monthly_expired, $medicine_model->monthlyExpired($month));
            
            if($month == date('m')) {
                break;
            }
        }

        return [
            'data' => [
                'monthly_received' =>  $monthly_received,
                'monthly_dispensed' =>  $monthly_dispensed,
                'monthly_expired' =>  $monthly_expired,
            ]
        ];
    }
}
?>