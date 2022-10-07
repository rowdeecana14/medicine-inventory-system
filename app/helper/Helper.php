<?php
namespace App\Helper;

class Helper {
    public static function unsets($data, $fields) {
        foreach($fields as $field) {
            unset($data[$field]);
        }

        return $data;
    }

    public static function allowOnly($request, $fields) {
        $data = [];

        foreach($request as $key => $value) {
            if(in_array($key, $fields)) {
                $data[$key] = $value;
            }
        }

        return $data;
    }

    public static function humanDate($format, $data) {
        return $data != NULL ? date($format, strtotime($data)) : '';
    }

    public static function dateParser($date) {
        $list = explode('/', $date);
        return $list[2].'-'.$list[0].'-'.$list[1];
    }

    public static function dateParserShow($date) {
        $list = explode('-', $date);
        return $list[1].'/'.$list[2].'/'.$list[0];
    }

    public static function dateParserProfile($date) {
        $months = array(
            '01' => 'Jan.',
            '02' =>   'Feb.',
            '03' =>   'March',
            '04' =>   'Apr',
            '05' =>   'May',
            '06' =>    'Jun.',
            '07' =>    'Jul.',
            '08' =>    'Aug.',
            '09' =>    'Sep.',
            '10' =>    'Oct.',
            '11' =>    'Nov.',
            '12' =>    'Dec.',
        );
        $list = explode('-', $date);
        return $months[$list[1]].' '.$list[2].', '.$list[0];
    }

    public static function uploadedHealthOfficialImage($image) {
       
        if($image != NULL && $image != '') {
            return app_uploaded_path('health-official').$image;
        }

        return app_config_path() . 'health-official.png';
    }

    public static function uploadedHealthOfficialPath() {
        return app_uploaded_document_root('health-official');
    }

    public static function uploadedPatientImage($image) {
       
        if($image != NULL && $image != '') {
            return app_uploaded_path('patient').$image;
        }

        return app_config_path() . 'patient.png';
    }

    public static function uploadedPatientPath() {
        return app_uploaded_document_root('patient');
    }

    public static function uploadedMedicineImage($image) {
       
        if($image != NULL && $image != '') {
            return app_uploaded_path('medicine').$image;
        }

        return app_config_path() . 'medicine.jpg';
    }

    public static function uploadedMedicinePath() {
        return app_uploaded_document_root('medicine');
    }

    public static function configImage($image) {
       
        if($image != NULL && $image != '') {
            return base_url() . '/public/assets/img/config/'.$image;
        }

        return base_url() . '/public/assets/img/config/default.png';
    }

    public static function appendTable($table, $fields) {
        $result = [];

        foreach($fields as $field) {
            array_push($result, $table.".".$field);
        }

        return $result;
    }

    public static function age($birthdate) {
        //calculate years of age (input string: YYYY-MM-DD)
        list($year, $month, $day) = explode("-", $birthdate);
    
        $year_diff  = date("Y") - $year;
        $month_diff = date("m") - $month;
        $day_diff   = date("d") - $day;
    
        // if we are any month before the birthdate: year - 1 
        // OR if we are in the month of birth but on a day 
        // before the actual birth day: year - 1
        if ( ($month_diff < 0 ) || ($month_diff === 0 && $day_diff < 0)) {
            $year_diff--;   
        }
    
        return $year_diff;
    }

    public static function getUserIP() {
        $ipaddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_X_CLUSTER_CLIENT_IP']))
            $ipaddress = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipaddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipaddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    public static function uploadImage($image, $path) {
        $image = str_replace('data:image/jpeg;base64,', '', $image);
        $image = str_replace('data:image/png;base64,', '', $image);

        $image = str_replace(' ', '+', $image);
        $data = base64_decode($image);
        $image_name = date('Y-m-d').'-'.str_replace(':', '-', date('h:i:s')).'-'.uniqid() . '.png';
        $file = $path.$image_name;

        file_put_contents($file, $data);

        return $image_name;
    }

    public static function colorLists() {
        return array (
            'blueviolet' => '#8a2be2',
            'brown' => '#a52a2a',
            'burlywood' => '#deb887',
            'cadetblue' => '#5f9ea0',
            'chartreuse' => '#7fff00',
            'chocolate' => '#d2691e',
            'coral' => '#ff7f50',
            'cornflowerblue' => '#6495ed',
            'cornsilk' => '#fff8dc',
            'crimson' => '#dc143c',
            'cyan' => '#00ffff',
            'darkblue' => '#00008b',
            'darkcyan' => '#008b8b',
            'darkgoldenrod' => '#b8860b',
            'darkgray' => '#a9a9a9',
            'darkgreen' => '#006400',
            'darkgrey' => '#a9a9a9',
            'darkkhaki' => '#bdb76b',
            'darkmagenta' => '#8b008b',
            'darkolivegreen' => '#556b2f',
            'darkorange' => '#ff8c00',
            'darkorchid' => '#9932cc',
            'darkred' => '#8b0000',
            'darksalmon' => '#e9967a',
            'darkseagreen' => '#8fbc8f',
            'darkslateblue' => '#483d8b',
            'darkslategray' => '#2f4f4f',
            'darkslategrey' => '#2f4f4f',
            'darkturquoise' => '#00ced1',
            'darkviolet' => '#9400d3',
            'deeppink' => '#ff1493',
            'deepskyblue' => '#00bfff',
            'dimgray' => '#696969',
            'dimgrey' => '#696969',
            'dodgerblue' => '#1e90ff',
            'firebrick' => '#b22222',
            'floralwhite' => '#fffaf0',
            'forestgreen' => '#228b22',
            'fuchsia' => '#ff00ff',
            'gainsboro' => '#dcdcdc',
            'ghostwhite' => '#f8f8ff',
            'goldenrod' => '#daa520',
            'gold' => '#ffd700',
            'gray' => '#808080',
            'green' => '#008000',
            'greenyellow' => '#adff2f',
            'grey' => '#808080',
            'honeydew' => '#f0fff0',
            'hotpink' => '#ff69b4',
            'indianred' => '#cd5c5c',
            'indigo' => '#4b0082',
            'ivory' => '#fffff0',
            'khaki' => '#f0e68c',
            'lavenderblush' => '#fff0f5',
            'lavender' => '#e6e6fa',
            'lawngreen' => '#7cfc00',
            'lemonchiffon' => '#fffacd',
            'lightblue' => '#add8e6',
            'lightcoral' => '#f08080',
            'lightcyan' => '#e0ffff',
            'lightgoldenrodyellow' => '#fafad2',
            'lightgray' => '#d3d3d3',
            'lightgreen' => '#90ee90',
            'lightgrey' => '#d3d3d3',
            'lightpink' => '#ffb6c1',
            'lightsalmon' => '#ffa07a',
            'lightseagreen' => '#20b2aa',
            'lightskyblue' => '#87cefa',
            'lightslategray' => '#778899',
            'lightslategrey' => '#778899',
            'lightsteelblue' => '#b0c4de',
            'lightyellow' => '#ffffe0',
            'lime' => '#00ff00',
            'limegreen' => '#32cd32',
            'linen' => '#faf0e6',
            'magenta' => '#ff00ff',
            'maroon' => '#800000',
            'mediumaquamarine' => '#66cdaa',
            'mediumblue' => '#0000cd',
            'mediumorchid' => '#ba55d3',
            'mediumpurple' => '#9370db',
            'mediumseagreen' => '#3cb371',
            'mediumslateblue' => '#7b68ee',
            'mediumspringgreen' => '#00fa9a',
            'mediumturquoise' => '#48d1cc',
            'mediumvioletred' => '#c71585',
            'midnightblue' => '#191970',
            'mintcream' => '#f5fffa',
            'mistyrose' => '#ffe4e1',
            'moccasin' => '#ffe4b5',
            'navajowhite' => '#ffdead',
            'navy' => '#000080',
            'oldlace' => '#fdf5e6',
            'olive' => '#808000',
            'olivedrab' => '#6b8e23',
            'orange' => '#ffa500',
            'orangered' => '#ff4500',
            'orchid' => '#da70d6',
            'palegoldenrod' => '#eee8aa',
            'palegreen' => '#98fb98',
            'paleturquoise' => '#afeeee',
            'palevioletred' => '#db7093',
            'papayawhip' => '#ffefd5',
            'peachpuff' => '#ffdab9',
            'peru' => '#cd853f',
            'pink' => '#ffc0cb',
            'plum' => '#dda0dd',
            'powderblue' => '#b0e0e6',
            'purple' => '#800080',
            'rebeccapurple' => '#663399',
            'red' => '#ff0000',
            'rosybrown' => '#bc8f8f',
            'royalblue' => '#4169e1',
            'saddlebrown' => '#8b4513',
            'salmon' => '#fa8072',
            'sandybrown' => '#f4a460',
            'seagreen' => '#2e8b57',
            'seashell' => '#fff5ee',
            'sienna' => '#a0522d',
            'silver' => '#c0c0c0',
            'skyblue' => '#87ceeb',
            'slateblue' => '#6a5acd',
            'slategray' => '#708090',
            'slategrey' => '#708090',
            'snow' => '#fffafa',
            'springgreen' => '#00ff7f',
            'steelblue' => '#4682b4',
            'tan' => '#d2b48c',
            'teal' => '#008080',
            'thistle' => '#d8bfd8',
            'tomato' => '#ff6347',
            'turquoise' => '#40e0d0',
            'violet' => '#ee82ee',
            'wheat' => '#f5deb3',
            'white' => '#ffffff',
            'whitesmoke' => '#f5f5f5',
            'yellow' => '#ffff00',
            'yellowgreen' => '#9acd32',
        );
    }
}

?>