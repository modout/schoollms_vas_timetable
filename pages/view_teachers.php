<?php

$server_ip = $_SERVER["SERVER_ADDR"] == '154.0.172.207' ? 'timetable.schoollms.net': $_SERVER["SERVER_ADDR"];

if (isset($_GET['school_id'])){
    $school_id = $_GET['school_id'];
} else {
    $school_id = 1;
}

$year = date('Y');
$q = "SELECT * FROM schoollms_schema_userdata_school_year WHERE year_label = '$year'";
$data->execSQL($q);
$row = $data->getRow();
$year_id = $row->year_id;

$q = "SELECT * FROM schoollms_schema_userdata_schools WHERE school_id = $school_id";
$data->execSQL($q);
$row = $data->getRow();
$school_acronym = $row->school_acronym;
$school_db = $school_acronym."lms_dev_vas_timetable";

$q = "SELECT DISTINCT * FROM schoollms_schema_userdata_access_profile WHERE school_id = $school_id AND type_id = 4 AND NOT access_id = '' AND user_id IN (SELECT user_id FROM schoollms_schema_userdata_teacher_schooldetails WHERE year_id = $year_id) ORDER BY surname ASC";

$result = $data->exec_sql($q, "array");

$list_teachers = "<table border=1>";
$row_max = 15;
$row_count = 1;
foreach($result as $rkey=>$row){
    $user_id = $row["user_id"];
    $q = "SELECT photo FROM schoollms_schema_userdata_user_photo WHERE user_id = $user_id";
    //$q_data = array ($user_id);
    //$user_query = $link->prepare($q);
    //$user_query->execute($q_data);
    //$user_result = mysql_query($q, $link);
    //$photo_num = count($user_query->fetchAll());
    $data->execSQL($q);
    
    if ($data->numrows > 0){
        //$user_row2 = mysql_fetch_object($user_result);
        //$photo = "<img src='../../../timetable/0.9.0/api/process.php?action=GETIMAGE&user_id=$user_id&database=$school_db' height='75%' width='75%' />";
        $photo = "<img src='http://timetable.schoollms.net/api/process.php?action=GETIMAGE&user_id=$user_id&database=$school_db' height='75%' width='75%' />";
    } else {
        $photo = "<img src='http://dtracker.schoollms.net/sites/default/files/pictures/silhouette.jpg' height='70%' width='70%' />";
    }

    $name = $row['name'];
    $surname = $row['surname'];
    $url = "http://timetable.schoollms.net/viewtimetable.php?school_id=$school_id&user_type=4&user_id=$user_id&year_id=$year_id&id=22";
    if ($row_count == $row_max){
        $list_teachers .= "</tr>";
        $row_count = 1;
    }
    
    if ($row_count == 1){
        $list_teachers .= "<tr><td><center><a href='$url' target='_blank'> $photo </a><br><b>$name $surname</b></center></td>";
    } else {
        $list_teachers .= "<td><center><a href='$url' target='_blank'> $photo </a><br><b>$name $surname</b></center></td>";
    }
    
    $row_count++;
}
$list_teachers .= "</table>";

?>
<div title="School Teachers" style="padding:10px">
    <?php echo $list_teachers; ?>
</div>
