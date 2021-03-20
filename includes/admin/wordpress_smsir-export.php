<?php

/**
 * Export Page
 * 
 * PHP version 5.6.x | 7.x | 8.x
 * 
 * @category  PLugins
 * @package   Wordpress
 * @author    Pejman Kheyri <pejmankheyri@gmail.com>
 * @copyright 2021 All rights reserved.
 */

require '../../../../../wp-load.php';

if(!is_super_admin() )
    wp_die(__('Access denied!', 'wordpress_smsir'));

$type = $_POST['export-file-type'];

if ($type) {
    global $wpdb, $table_prefix;
    include '../classes/php-export-data.class.php';

    $file_name = date('Y-m-d_H-i');
    $result = $wpdb->get_results("SELECT `ID`,`date`,`name`,`mobile`,`status`,`group_ID` FROM {$table_prefix}smsir_subscribes");

    switch($type) {
    case 'excel':
        $exporter = new ExportDataExcel('browser', "{$file_name}.xls");
        break;
    case 'xml':
        $exporter = new ExportDataExcel('browser', "{$file_name}.xml");
        break;
    }

    $exporter->initialize();

    foreach ( $result[0] as $key => $col ) { 
        $columns[] = $key; 
    }
    $exporter->addRow($columns);

    foreach ($result as $row) {
        $exporter->addRow($row);
    }

    $exporter->finalize();

} else {
    wp_die(__('Please select the desired items.', 'wordpress_smsir'), false, array('back_link' => true));
}
?>