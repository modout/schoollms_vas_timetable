<?php

if(function_exists("mysql_connect") )
{
	include("data_db.inc");
}
else{
	include("data_db_mysqli.inc");
}

/*$data = new data();
$data->username = "root";
$data->password = "12_s5ydw3ll1979";
$data->host = "localhost";
$data->db = "mehluko";
*/

$data = new data();
$data->username = "root";
$data->password = "$0W3t0";
$data->host = "localhost";
$data->db = "schoollms_dev_vas_timetable";
//$data->db = "sphslms_dev_vas_timetable";

$data1 = new data();
$data1->username = "root";
$data1->password = "$0W3t0";
$data1->host = "localhost";
$data1->db = "schoollms_dev_vas_timetable";
//$data1->db = "sphslms_dev_vas_timetable";

$data2 = new data();
$data2->username = "root";
$data2->password = "$0W3t0";
$data2->host = "localhost";
//$data2->db = "schoollms_dev_teach";
$data2->db = "sphslms_dev_teach";

$imagedb = new data();
$imagedb->username = "root";
$imagedb->password = "$0W3t0";
$imagedb->host = "localhost";
//$imagedb->db = "schoollms_dev_vas_track";
$imagedb->db = "sphslms_dev_vas_track";
?>
