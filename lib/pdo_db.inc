<?php

//if(function_exists("mysql_connect") )
//{
//	include("data_db.inc");
//}
//else{
//	include("data_db_mysqli.inc");
//}

/*$data = new data();
$data->username = "root";
$data->password = "12_s5ydw3ll1979";
$data->host = "localhost";
$data->db = "mehluko";
*/

$school_db = "sphslms_dev_vas_timetable";
$dsn = "mysql:host=localhost;dbname=$school_db;charset=utf8";
$opt = [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            //PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

$data = new PDO ($dsn, 'root', '$0W3t0');
//$data = new data();
//$data->username = "root";
//$data->password = "$0W3t0";
//$data->host = "localhost";
//$data->db = "sphslms_dev_vas_timetable";

//$data1 = new data();
//$data1->username = "root";
//$data1->password = "$0W3t0";
//$data1->host = "localhost";
//$data1->db = "sphslms_dev_vas_timetable";

$school_db = "sphslms_dev_teach";
$dsn = "mysql:host=localhost;dbname=$school_db;charset=utf8";
$opt = [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            //PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

$data2 = new PDO ($dsn, 'root', '$0W3t0');

//$data2 = new data();
//$data2->username = "root";
//$data2->password = "$0W3t0";
//$data2->host = "localhost";
//$data2->db = "sphslms_dev_teach";

$school_db = "sphslms_dev_vas_track";
$dsn = "mysql:host=localhost;dbname=$school_db;charset=utf8";
$opt = [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            //PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

$imagedb = new PDO ($dsn, 'root', '$0W3t0');

//$imagedb = new data();
//$imagedb->username = "root";
//$imagedb->password = "$0W3t0";
//$imagedb->host = "localhost";
//$imagedb->db = "sphslms_dev_vas_track";
?>
