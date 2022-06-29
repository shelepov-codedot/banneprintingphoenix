<?php
header('Content-Type: text/html; charset=utf-8');
require_once('../../../wp-config.php');

$patch = dirname(__FILE__);

$link = new mysqli("localhost", DB_USER, DB_PASSWORD, DB_NAME);

if ($link == false){
    print("Ошибка: Невозможно подключиться к MySQL " . mysqli_connect_error());

    die;
}

$sql = 'INSERT INTO '.$table_prefix.'woocommerce_tax_rates(tax_rate_country, tax_rate_state, tax_rate, tax_rate_name, tax_rate_priority, tax_rate_compound, tax_rate_shipping, tax_rate_order, tax_rate_class) VALUES ';
$sql_location = 'INSERT INTO '.$table_prefix.'woocommerce_tax_rate_locations(location_code, tax_rate_id, location_type) VALUES ';

//$stateInsert = '';
foreach (glob($patch."/TAXRATES_ZIP*.csv") as $csvFile) {

    $row = 0;
    if (($handle = fopen(str_replace($patch.'/', '', $csvFile), "r")) !== FALSE) {
        $sumStateRate = 0;
        $sumEstimatedSpecialRate = 0;
        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if ($row == 0) {
                $row++;
                continue;
            }

//            if ($stateInsert != $data[0]) {

//                $tax_rate_country   = 'US';
//                $tax_rate_state     = $data[0];
//                $tax_rate           = ($data[3]+$data[7])*100;
//                $tax_rate_name      = $link->real_escape_string($data[0].' State Tax '.$tax_rate.'%');
//                $tax_rate_priority  = 2;
//                $tax_rate_compound  = 1;
//                $tax_rate_shipping  = 1;
//                $tax_rate_order     = 0;
//                $tax_rate_class     = '';
//                $link->query($sql."('$tax_rate_country', '$tax_rate_state', '$tax_rate', '$tax_rate_name', $tax_rate_priority, $tax_rate_compound, $tax_rate_shipping, $tax_rate_order, '$tax_rate_class')");

//                $stateInsert = $data[0];
//            }

            $tax_rate_country   = 'US';
            $tax_rate_state     = $data[0];
            $tax_rate           = $data[4]*100;
            $tax_rate_name      = $link->real_escape_string(' State Tax Rate ('.$tax_rate.'%)');
            $tax_rate_priority  = 1;
            $tax_rate_compound  = 1;
            $tax_rate_shipping  = 1;
            $tax_rate_order     = 1;
            $tax_rate_class     = '';
            $link->query($sql."('$tax_rate_country', '$tax_rate_state', '$tax_rate', '$tax_rate_name', $tax_rate_priority, $tax_rate_compound, $tax_rate_shipping, $tax_rate_order, '$tax_rate_class')");

            $tax_location_code = $data[1];
            $tax_rate_id = $link->insert_id;
            $location_type = 'postcode';
            $link->query($sql_location."('$tax_location_code', '$tax_rate_id', '$location_type')");

            $row++;
        }
        fclose($handle);
    }
}
?>