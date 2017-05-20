<?php 
global $data;
include("db.inc");
include("util.php");
//include("config_mysql.php");


//include('data_db_mysqli.inc');

// old mysql extension (it has been deprecated as of PHP 5.5.0 and will be removed in the future)

// define database host (99% chance you won't need to change this value)
$db_host = 'localhost';

// define database name, user name and password
$db_name = 'school_lms_dev_support';
$db_user = 'root';
//$db_pwd  = '12_s5ydw3ll1979';
$db_pwd  = '$0W3t0';
// reset record set to null ($rs is used in timetable function)
$rs = null;
$timetable_items = null;
$user_id = 1;
$timetable_id = 0;
$periods = array();
$period_labels = array();
// open database connection and select database
//$link = mysql_connect('localhost', 'root', '');
//$db_conn = mysqli_connect($db_host, $db_user, $db_pwd,$db_name);
//mysqli_select_db($db_name, $db_conn);

// function executes SQL statement and returns result set as Array
function sqlQuery($sql) {
    global $db_conn;
	global $data;
	$resultSet = Array();
    // execute query	
    /*$db_result = mysql_query($sql, $db_conn);
    // if db_result is null then trigger error
    if ($db_result === null) {
        trigger_error(mysql_errno() . ": " . mysql_error() . "\n");
        exit();
    }
    // prepare result array
   
    // if resulted array isn't true and that is in case of select statement then open loop
    // (insert / delete / update statement will return true on success) 
    if ($db_result !== true) {
        // loop through fetched rows and prepare result set
        while ($row = mysql_fetch_array($db_result, MYSQL_NUM)) {
            // first column of the fetched row $row[0] is used for array key
            // it could be more elements in one table cell
            $resultSet[$row[0]][] = $row;
        }
    }*/
	//echo "<br/> $sql <br/><br/>";
	$mysql_num = "";
	if(function_exists("mysql_connect") )
	{
		$mysql_num = MYSQL_NUM;
	}
	else{
		$mysql_num = MYSQLI_NUM;
	}
	if(strpos(strtoupper($sql),"INSERT") > -1 or strpos(strtoupper($sql),"UPDATE") > -1 or strpos(strtoupper($sql),"DELETE") > -1)
	{
		$data->execNonSql($sql);
	}
	else{
		$data->execSQL($sql);
		while($row = $data->getRow("array",$mysql_num))
		{
			$resultSet[$row[0]][] = $row;
		}
	}
	
    // return result set
    return $resultSet;
}


function sqlEscapeString($string){
    global $data;
	return $data->sqlEscapeString($string);
    //return mysqli_real_escape_string($db_conn, $string);
}

// commit transaction
function sqlCommit() {
	global $db_conn;
	mysqli_query($db_conn,'commit' );
	mysqli_close($db_conn);
}

function navigation_menu ($school_id, $startdate=null,$today=null){
    
    if (!is_null($startdate)){
        if (!is_null($today)){
            $today = get_today($startdate, $today);
        } else {
            $today = date('D:d M:m Y H:i:s');
            $today = get_today($startdate, $today);
        }
    } else {
        $today = date('D:d M:m Y H:i:s');
    }
    
   $ip = $_SERVER['SERVER_ADDR'];
    $port = $_SERVER['SERVER_PORT'];
    if($port != 80)
    {
            $ip = "$ip:$port";
    }
    $protocol = strtolower(explode("/",$_SERVER['SERVER_PROTOCOL'])[0]);
    
    $menu = "<table><tr><td><a href='$protocol://$ip/vas/timetable/?startdate=-1&today=$today'><img src='./images/arrow_left_22x22.png'></a></td>";                                        
    $menu .= "<td><button style='background-color:transparent; border-color:transparent;' id='btnMenu' name='btnMenu'  onClick='openMenu()'><img src='./images/menu2_26x22.png'></button></td>";
    $menu .= "<td><a href='$protocol://$ip/vas/timetable/?startdate=1&today=$today'> <img src='./images/arrow_right_22x22.png'> </a></td></tr></table>";

    return $menu;
}

//Prepare School Time Table View for user;
function prepareTableView($user_id, $time_table_id,$user_type, $year_id, $school_id){
    //echo "Time table ID : $time_table_id <br/>";
    global $timetable_id;
    
    
    //if ($year_id == null){
         $today = date('D:d M:m Y H:i:s');
        
        //echo "TODAY $today\n";
        $day_tokens = explode(" ", $today);

        //$q = "SELECT  FROM "
        $year = $day_tokens[2];
        
        $q = "SELECT year_id FROM schoollms_schema_userdata_school_year WHERE year_label = '$year'";
        
        $result = sqlQuery($q);
        
        foreach ($result as $year_data){
            $year_id = $year_data[0][0];
            break;
        }    
        
        
    //}
    
    //Get User Details For Table Retrieval
    switch ($user_type){
        
        case 2:
            $q = "SELECT timetabl_id FROM schoollms_schema_userdata_learner_timetable WHERE user_id = $user_id AND year_id = $year_id";
            break;
        
        case 4:
            $q = "SELECT timetabl_id FROM schoollms_schema_userdata_teacher_timetable WHERE user_id = $user_id AND year_id = $year_id";
            break;
        
        case 5:
        
        case 6:
            $q = "SELECT timetabl_id FROM schoollms_schema_userdata_school_timetable_items WHERE timetabl_id = $time_table_id";
            break;
    }
    
    //echo "<br/>Q $q <br/>";
   
    //Get USER TIMETABLE ID
    $timetable_result = sqlQuery($q);
	
    $item_count = count($timetable_result);
    if ($item_count > 0){
        foreach ($timetable_result as $items) {
            $timetable_id = $items[0][0];
 
            switch($user_type){
                
                case 2://CHECK IF LEARNER TIMETABLE EMPTY
                    $q = "SELECT * FROM schoollms_schema_userdata_school_timetable_items WHERE timetabl_id = $timetable_id";
                    if (count(sqlQuery($q)) == 0){
                        //echo "BUILD $timetable_id <br>";
                        $timetable_id = build_learner_timetable($user_id, $year_id, $timetable_id);
                    } else {
                        //echo "UPDATE TIMETABLE $timetable_id <br>";
                        $timetable_id = update_learner_timetable($user_id, $year_id, $timetable_id);
                    }
                    
                    break;
                
                case 4://CHECK IF TEACHER TIMETABLE EMPTY
                    break;
            }
            
            break;
        }
    } else {
        switch ($user_type){
        
            case 2:
                $timetable_id = build_learner_timetable($user_id, $year_id, 0);
                break;
            
            case 4:
                //echo "BUILD TEACHER TITMETBAE <br>";
				
                $timetable_id = build_teacher_timetable($user_id, $year_id);
                break;
            
            case 5:
            
            case 6:
                $timetable_id = build_class_timetable($time_table_id, $year_id);
                break;
        }
    }
	
    $result = sqlQuery("delete from schoollms_schema_userdata_school_timetable_view where timetabl_id = $timetable_id");
    //echo "TIMETABLE $timetable_id \n";
    //IF FALSE Get User Time Table According to Today
    $today = date('D:d M:m Y H:i:s');
    
    //Get TimeDays From Today
    $days = days($today, $school_id);
    
    //REMOVE PREVIOUS TIMETABLE LOAD IF FIRST DATE IS DIFFERENT TO TODAY
    
    //FOR EACH DAY LOAD USER TIMETABLE
    $row = 1;
    //echo "<hr/>";
	//var_dump($days);
	//echo "<hr/>";
    $sql = "select l.* 
		from schoollms_schema_userdata_school_timetable_period_labels l
		join schoollms_schema_userdata_school_timetable_period p on p.period_label_id = l.period_label_id and l.school_id = p.school_id
		where l.school_id = $school_id 
		and week_day_id = 1
		order by UNIX_TIMESTAMP(concat('2016-01-01',' ',period_start))  asc";
    //$period_labels = sqlQuery("select * from schoollms_schema_userdata_school_timetable_period_labels where school_id = $school_id order by period_label_id ASC");
    $period_labels = sqlQuery($sql);

    
    //echo "P SQL $sql <br>";
    
    //CHECK IF SCHOOL HAS REGISTRATION PERIOD
    $q = "select * from schoollms_schema_userdata_timetable_settings WHERE school_id = $school_id";
    $settings = sqlQuery($q);
     
    $has_registration = FALSE;
    foreach ($settings as $s_items){
      $settings_tokens = explode("|", $s_items[0][1]);
      $register_tokens = explode ("=", $settings_tokens[11]);
      if ($register_tokens[1] == 'Yes'){
        $has_registration = TRUE;
      }
      break;
    }
    
    foreach ($days as $day) {
        
        $timetable_day = $day['timetable_day'];
        $timetable_day_date = $day['timetable_day_date'];
        
        $tokens = explode(" ", $timetable_day_date);
        
        $year = $tokens[2];
        $month_items = explode(":", $tokens[1]);
        $day_items = explode(":", $tokens[0]);
        $clock_items = explode(":", $tokens[3]);
        
        $date_stamp = "$day_items[1]-$month_items[1]-$year";
        
        $temp_period_labels = $period_labels; 
        $col = 1;
        foreach ($temp_period_labels as $period_label) {
    
       
            $period_label_id = $period_label[0][0];
            //$start = $period[0][3];
            //$label_id = $period[0][5];
            $label = $period_label[0][2];//sqlQuery("select period_label from schoollms_schema_userdata_school_timetable_period_labels where period_label_id = $label_id");
    //        foreach ($period_label as $period_label_data) {
    //            $label = $period_label_data[0][0];
    //            break;
    //        }
    //        $end = $period[0][4];

            //echo "PID $period_label_id PL $label <br>";
            if ($label == "Registration" && !$has_registration){
                //echo "SKIPPED REGISTRATION $period_label_id $label <br>";
                continue;
            }

            if ($label == "Break"){
               $sql = "insert into schoollms_schema_userdata_school_timetable_view (timetabl_id, day_date, tbl_row, tbl_col, slot_code, slot_details) values ($timetable_id,'$date_stamp',$row,$period_label_id,'s-0',' ')";
                //echo "SQL $sql <br />";
                $result = sqlQuery($sql);
                continue;
            }
        //GET DAY ITEMS
        //switch ($user_type){
        
            //case 2:
            $sql = "select distinct timetabl_id, day_id, period_label_id, grade_id, class_id, 
    room_id, subject_id, teacher_id, substitude_id from schoollms_schema_userdata_school_timetable_items where timetabl_id = $timetable_id and day_id =  $timetable_day and period_label_id = $period_label_id";
                  //  break;

                //case 4:
                //    $sql = "select * from schoollms_schema_userdata_school_timetable_items where teacher_id = $user_id and day_id =  $timetable_day order by period_label_id asc";
              //      break;

            //}

            //echo "$label P SQL $sql <br>";
            
            $timetable_items = sqlQuery($sql);

            $col = 1;
            $item_count = count($timetable_items);
            //echo "TTITEMS = $item_count SQL $sql <br> date_stamp : $date_stamp  --- <br/>";

            $slot_details = "";
            //echo "<hr/> num : ".count($timetable_items);
            foreach ($timetable_items as $items) {

                foreach ($items as  $item){
                    //echo "<hr/>num : ".count($items);
                                    //var_dump($item);
                    $item_count = count($items);

                    //echo "ITEMS = $item_count<br>";

                    $period_label_id = $item[2];
                    $grade_id = $item[3];
                    $class_id = $item[4];
                    $room_id = $item[5];
                    $subject_id = $item[6];
                    $teacher_id = $item[7];
                    $substitute_id = $item[8];

                                    //echo "<br/>$period_label_id | $grade_id  | $class_id | $room_id | $subject_id | $teacher_id | $substitute_id | $user_type";
                    //echo "USER TYPE $user_type <br>";

                    switch ($user_type) {
                        case 1:
                        case 2:
                        case 3:
                        case 5:
                        case 6:
                            //echo "I GET HERE <br>";
                            $slot_details = items($timetable_id, $timetable_day, $date_stamp, $period_label_id, $grade_id, $class_id, $room_id, $subject_id, $teacher_id, $substitute_id, $user_type, $user_id); 
                            break;

                        case 4:
                            //echo "if ($teacher_id == $user_id) <br>";
                                                    //alert("$teacher_id == $user_id ---  timetable_id|$timetable_id, timetable_day|$timetable_day, date_stamp|$date_stamp, period_label_id|$period_label_id, grade_id|$grade_id");
                            if ($teacher_id == $user_id){
                                                            //echo "  before items >>> ";
                                $slot_details = items($timetable_id, $timetable_day, $date_stamp, $period_label_id, $grade_id, $class_id, $room_id, $subject_id, $teacher_id, $substitute_id, $user_type, $user_id); 
                                                            //echo "  << after items ";
                            } else {
                                $slot_details = "";
                            }
                            break;

                        default:
                            break;
                    }

                    //echo "SLOT $slot_details <br />";

                    //echo "TT $timetable_id,D $timetable_day,P $period_id,R $room_id,S $subject_id,T $teacher_id,SUB $substitute_id <br>";
                                    //echo "$slot_details <br/>";
                    //$slot_details = sqlEscapeString($slot_details);
                    //echo "AFTER ESCAPE $slot_details <br/>";

                    //$pos = "$row"."_$period_id"
                    $sql = "insert into schoollms_schema_userdata_school_timetable_view (timetabl_id, day_date, tbl_row, tbl_col, slot_code, slot_details) values ($timetable_id,'$date_stamp',$row,$period_label_id,'s-$subject_id','$slot_details')";
                    //echo "$period_label_id $label SQL $sql <br />";
                    $result = sqlQuery($sql);

                    //echo "RESULT $result <br>";
                }
            }
        }
        
        $row++;
    }
	
    //echo "return $timetable_id<br/>";
    return $timetable_id;
}



function build_class_timetable($timetable_id, $year_id){

    $q = "SELECT timetable_type_item_id FROM schoollms_schema_userdata_school_timetable WHERE timetabl_id = $timetable_id AND timetable_type_id = 3";
    
    $result = sqlQuery($q);
    
    $seen_timetable_id = 0;
    foreach ($result as $items) {
        $class_id = $items[0][0];
        
        $q = "SELECT * FROM schoollms_schema_userdata_school_timetable_items WHERE class_id = $class_id ORDER BY timetabl_id, day_id, period_label_id ASC";
        
        $result2 = sqlQuery($q);
        
        foreach ($result2 as $items2){
            
            $timetabl_id = $items2[0][0];
            
            if ($seen_timetable_id == 0){
                $q = "SELECT * FROM schoollms_schema_userdata_school_timetable_items WHERE timetabl_id = $timetabl_id ORDER BY day_id, period_label_id ASC";

                $result3 = sqlQuery($q);
                
                $count_rows = count($result3[$timetabl_id]);
                if ($count_rows !== 80){
                    continue;
                } else {
                    $seen_timetable_id = $timetabl_id;
                    $result2 = $result3[$seen_timetable_id];
           
                    break;
                }
            }
            
        }
            
        if ($seen_timetable_id !== 0){
            foreach ($result2 as $items2){

                $day_id = $items2[1];
                $period_label_id = $items2[2];
                $grade_id = $items2[3];
                //$class_id = $items2[$key][4];
                $room_id = $items2[5];
                $subject_id = $items2[6];
                $teacher_id = $items2[7];
                $substitute_id = $items2[8];

                $q = "SELECT * FROM schoollms_schema_userdata_school_timetable_items WHERE timetabl_id = $timetable_id AND day_id = $day_id AND period_label_id = $period_label_id";

                $result3 = sqlQuery($q);

                $count_result = count($result3[$timetable_id]);

                if ($count_result > 0){

                } else {
                    $q = "insert into schoollms_schema_userdata_school_timetable_items values ($timetable_id, $day_id, $period_label_id, $grade_id, $class_id, $room_id, $subject_id, $teacher_id, $substitute_id)";
					//echo "$q <br/>";
                    $result3 = sqlQuery($q);
                }
              
            }
            break;
        }
    }
    
    return $timetable_id;
}

function update_learner_timetable($user_id, $year_id, $timetable_id){
    
    $q = "SELECT * FROM schoollms_schema_userdata_learner_schooldetails WHERE user_id = $user_id AND year_id = $year_id";
    
    $result = sqlQuery($q);
    
    foreach ($result as $items) {
        $class_id = $items[0][3];
        
        //echo "CLASS $class_id <br>";
        
        $q = "SELECT * FROM schoollms_schema_userdata_school_timetable WHERE timetable_type_id IN (3, 8) AND timetable_type_item_id = $class_id";
        
        $result2 = sqlQuery($q);
        if (count($result2) > 0){
            foreach ($result2 as $items2) {
                $temp_timetable_id = $items2[0][0];
                break;
            }
            
           
            $q = "SELECT * FROM schoollms_schema_userdata_school_timetable_items WHERE timetabl_id = $temp_timetable_id";
         
            //echo "SELECT ITEMS $temp_timetable_id <br>";
            
            $result2 = sqlQuery($q);
            
            $count = count($result2);
            //echo "COUNT $count";
            
            foreach ($result2 as $items2) {
                foreach ($items2 as $row){
                    $day_id = $row[1];
                    $period_label_id = $row[2];
                    $grade_id = $row[3];
                    $class_id = $row[4];
                    $room_id = $row[5];
                    $subject_id = $row[6];
                    $teacher_id = $row[7];
                    $last_update = $row[9];
                    
                    $q = "SELECT * FROM schoollms_schema_userdata_school_timetable_items WHERE timetabl_id = $timetable_id AND day_id = $day_id AND period_label_id = $period_label_id AND grade_id = $grade_id AND class_id = $class_id AND room_id = $room_id AND subject_id = $subject_id AND teacher_id = $teacher_id AND substitude_id = $substitude_id AND last_changed = '$last_update'";

                    $result3 = sqlQuery($q);
                    
                    if (count($result3) == 0){
                        
                        if (count(sqlQuery("SELECT * FROM schoollms_schema_userdata_school_timetable_items WHERE timetabl_id = $timetable_id AND day_id = $day_id AND period_label_id = $period_label_id")) > 0){
                            $q = "UPDATE schoollms_schema_userdata_school_timetable_items SET grade_id = $grade_id, class_id = $class_id, room_id = $room_id, subject_id = $subject_id , teacher_id = $teacher_id, substitude_id = $substitude_id, last_changed = '$last_update' WHERE timetabl_id = $timetable_id AND day_id = $day_id AND period_label_id = $period_label_id";
                        } else {
                            $q = "INSERT INTO schoollms_schema_userdata_school_timetable_items VALUES ($timetable_id, $day_id, $period_label_id, $grade_id, $class_id, $room_id, $subject_id, $teacher_id, 0, '$last_update')";
                            
                        }
                        sqlQuery($q);
                    }
                }
            }
        }
    }
    
    return $timetable_id;
}

function build_learner_timetable($user_id, $year_id, $timetable_id){

    //GET LEARNER ACCESS DETAILS
    $q = "SELECT * FROM schoollms_schema_userdata_access_profile WHERE user_id = $user_id";
    $result = sqlQuery($q);
    foreach ($result as $items) {
        $school_id = $items[0][1];
        $name = $items[0][4];
        $surname = $items[0][5];
        break;
    }
   
    //echo "TID $timetable_id SID $school_id NAME $name SURNAME $surname <br>";
    
    //CHECK IF LEARNER TIMETABL_ID EXISTS
    if ($timetable_id == 0){
        $q = "SELECT * FROM schoollms_schema_userdata_school_timetable WHERE timetable_type_id = 1 AND timetable_type_item_id = $user_id";
        
        $result = sqlQuery($q);
        
        if (count($result) == 0){
            $q = "INSERT INTO schoollms_schema_userdata_school_timetable VALUES (NULL, $school_id, 1, $user_id, '$surname $name', NULL)";
            sqlQuery($q);
            
            $q = "SELECT * FROM schoollms_schema_userdata_school_timetable WHERE timetable_type_id = 1 AND timetable_type_item_id = $user_id";

            $result = sqlQuery($q);
            foreach ($result as $items) {
                $timetable_id = $items[0][0];
                break;
            }
            
        } else {
            foreach ($result as $items) {
                $timetable_id = $items[0][0];
                break;
            }
        }
        $q = "INSERT INTO schoollms_schema_userdata_learner_timetable VALUES ($user_id, $timetable_id, $year_id, 'new', NULL)";
        sqlQuery($q);
    }
    
    $q = "SELECT * FROM schoollms_schema_userdata_learner_schooldetails WHERE user_id = $user_id AND year_id = $year_id";
    
    $result = sqlQuery($q);
    
    foreach ($result as $items) {
        $class_id = $items[0][3];
        
        //echo "CLASS $class_id <br>";
        
        $q = "SELECT * FROM schoollms_schema_userdata_school_timetable WHERE timetable_type_id IN (3, 8) AND timetable_type_item_id = $class_id";
        
        $result2 = sqlQuery($q);
        if (count($result2) > 0){
            foreach ($result2 as $items2) {
                $temp_timetable_id = $items2[0][0];
                break;
            }
            
           
            $q = "SELECT * FROM schoollms_schema_userdata_school_timetable_items WHERE timetabl_id = $temp_timetable_id";
         
            //echo "SELECT ITEMS $temp_timetable_id <br>";
            
            $result2 = sqlQuery($q);
            
            $count = count($result2);
            //echo "COUNT $count";
            
            foreach ($result2 as $items2) {
                foreach ($items2 as $row){
                    $day_id = $row[1];
                    $period_label_id = $row[2];
                    $grade_id = $row[3];
                    $class_id = $row[4];
                    $room_id = $row[5];
                    $subject_id = $row[6];
                    $teacher_id = $row[7];
                    $last_update = $row[9];

                    $q = "INSERT INTO schoollms_schema_userdata_school_timetable_items VALUES ($timetable_id, $day_id, $period_label_id, $grade_id, $class_id, $room_id, $subject_id, $teacher_id, 0, '$last_update')";

                    //echo "INSERT $q <br>";

                    sqlQuery($q);     
                }
            }
        }
    }
    
    return $timetable_id;
}

function build_teacher_timetable($user_id, $year_id){

    //EVERY PERSON ON ACCESS PROFILE MUST EXIST ONCE - IF THEY MOVE SCHOOL CHANGE SCHOOL_ID
    $q = "SELECT school_id, name, surname FROM schoollms_schema_userdata_access_profile WHERE user_id = $user_id";
    
    //echo "Q $q <br>\n";
    
    $result = sqlQuery($q);
    
    foreach ($result as $items) {
        $school_id = $items[0][0];
        $name = $items[0][1];
        $surname = $items[0][2];
        //$surname = $items[0][1];
        break;
    } 
    
    
   
    $q = "INSERT INTO schoollms_schema_userdata_school_timetable (school_id,timetable_type_id,timetable_type_item_id,timetable_label) VALUES ( $school_id, 2, $user_id,'$name $surname');";

    //echo "Q $q <br>\n";

    $result = sqlQuery($q);
    
    $q = "SELECT timetabl_id FROM schoollms_schema_userdata_school_timetable WHERE timetable_type_id = 2 AND timetable_type_item_id = $user_id AND timetable_label = '$name $surname'";

    //echo "Q $q <br>\n";
    
    $result = sqlQuery($q);
    
    foreach ($result as $items) {
        $timetable_id = $items[0][0];
        break;
    }

    //echo "TIMETABLE ID $timetable_id <br>";
    
    $q  = "insert into schoollms_schema_userdata_teacher_timetable(user_id,timetabl_id,year_id,timetable_log) values ($user_id, $timetable_id, $year_id, 'NEW')";

    //echo "Q $q <br>\n";

    $result = sqlQuery($q);

    $q = "select * from schoollms_schema_userdata_school_timetable_items where teacher_id = $user_id order by day_id, period_label_id asc";

    //echo "Q $q <br>\n";

    $timetable_items = sqlQuery($q);

    foreach ($timetable_items as $items) {

        foreach ($items as  $item){

            $item_count = count($items);

            //echo "ITEMS = $item_count<br>";
            $day_id = $item[1];
            $period_label_id = $item[2];
            $grade_id = $item[3];
            $class_id = $item[4];
            $room_id = $item[5];
            $subject_id = $item[6];
            //$teacher_id = $item[7];
            $substitute_id = $item[8];

            $q = "SELECT * FROM schoollms_schema_userdata_teacher_schooldetails WHERE user_id = $user_id AND school_id = $school_id AND grade_id = $grade_id AND class_id = $class_id AND subject_id = $subject_id AND year_id = $year_id";
    
            $schooldetails = sqlQuery($q);

            if (count($schooldetails) == 0){
                //WORK ON TABLE TO KEEP TRACK OF USER YEAR ON ACCESS PROFILE
               $q = "INSERT INTO schoollms_schema_userdata_teacher_schooldetails (user_id, school_id,grade_id, class_id, subject_id, year_id) VALUE ($user_id, $school_id,$grade_id, $class_id, $subject_id, $year_id)"; 
            
               $result = sqlQuery($q);
            }
            
            $q = "SELECT * FROM schoollms_schema_userdata_school_timetable_items WHERE timetabl_id = $timetable_id AND day_id = $day_id AND period_label_id = $period_label_id AND grade_id =  $grade_id AND class_id = $class_id AND room_id = $room_id AND subject_id = $subject_id AND teacher_id = $user_id";
            
            $result = sqlQuery($q);
            
            if (count($result) == 0){
                $q = "insert into schoollms_schema_userdata_school_timetable_items (timetabl_id, day_id, period_label_id, grade_id, class_id, room_id, subject_id, teacher_id, substitude_id) values ($timetable_id, $day_id, $period_label_id, $grade_id, $class_id, $room_id, $subject_id, $user_id, $substitute_id)";

                //echo "Q $q <br>\n";

                $result = sqlQuery($q);
            }
        }
    }
//    } else {
//        $timetable_id = 0;
//    }
    
    return $timetable_id;
}


function print_days($time_table_id = 22, $user_type, $user_id, $year_id, $school_id, $startdate=null, $today=null){
    
    $slots = array ();
    //echo "FIRST TODAY $today STARTDATE $startdate<br>";
    if (!is_null($startdate)){
        if (!is_null($today)){
            $today = get_today($startdate, $today,$school_id);
        } else {
            $today = date('D:d M:m Y H:i:s');
            $today = get_today($startdate,$today,$school_id);
        }
    } else {
        $today = date('D:d M:m Y H:i:s');
    }

    //Get TimeDays From Today
    $days = days($today, $school_id);
    //var_dump($days);
    //REMOVE PREVIOUS TIMETABLE LOAD IF FIRST DATE IS DIFFERENT TO TODAY
    
    //FOR EACH DAY LOAD USER TIMETABLE
    foreach ($days as $key=>$day) {
        
        $timetable_day = $day['timetable_day'];
        $timetable_day_date = $day['timetable_day_date'];
        $tokens = explode(" ", $timetable_day_date);
        
        $year = $tokens[2];
        $month_items = explode(":", $tokens[1]);
        $day_items = explode(":", $tokens[0]);
        $clock_items = explode(":", $tokens[3]);
        $day_string = "<b>Day $timetable_day</b><br>$day_items[0]<br>$day_items[1]-$month_items[0]-$year";
        //GET DAY
        print " <tr><td class='mark dark'><div class='tableFirstCol'> $day_string </div></td></tr>";
        //GET DAY SLOTS
        $slots[$key] = print_day($day_string, $key+1,$time_table_id, $user_type, $user_id, $year_id, $school_id);
    }
    
    return $slots;
}

// print subjects
function subjects() {
	// returned array is compound of nested arrays
	$subjects = sqlQuery('select subject_id, subject_title from schoollms_schema_userdata_school_subjects order by subject_title');
	// print_r($subjects);
	foreach ($subjects as $subject) {
		$id   = $subject[0][0];
		$name = $subject[0][1];
		print "<tr><td class=\"dark\"><div id=\"$id\" class=\"redips-drag redips-clone $id\">$name</div><input id=\"b_$id\" class=\"$id\"type=\"button\" value=\"\" onclick=\"report('$id')\" title=\"Show only $name\"/></td></tr>\n";
	}
}

function periods ($school_id, $startdate=null,$today=null) {
    
    //$periods = sqlQuery('select *  from schoollms_schema_userdata_school_timetable_period order by period_id');
    //echo "SCHOOL_ID $school_id";
    $sql = "select l.* 
		from schoollms_schema_userdata_school_timetable_period_labels l
		join schoollms_schema_userdata_school_timetable_period p on p.period_label_id = l.period_label_id and l.school_id = p.school_id
		where l.school_id = $school_id 
		and week_day_id = 1
		order by UNIX_TIMESTAMP(concat('2016-01-01',' ',period_start))  asc";
    //$period_labels = sqlQuery("select * from schoollms_schema_userdata_school_timetable_period_labels where school_id = $school_id order by period_label_id ASC");
	$period_labels = sqlQuery($sql);
    
     //CHECK IF SCHOOL HAS REGISTRATION PERIOD
     $q = "select * from schoollms_schema_userdata_timetable_settings WHERE school_id = $school_id";
     $settings = sqlQuery($q);
     
     $has_registration = FALSE;
     foreach ($settings as $s_items){
	  $settings_tokens = explode("|", $s_items[0][1]);
	  $register_tokens = explode ("=", $settings_tokens[11]);
	  if ($register_tokens[1] == 'Yes'){
	    $has_registration = TRUE;
	  }
	  break;
     }
    //var_dump($period_labels);
    
    foreach ($period_labels as $period_label) {
    
       
        $id = $period_label[0][0];
        //$start = $period[0][3];
        //$label_id = $period[0][5];
        $label = $period_label[0][2];//sqlQuery("select period_label from schoollms_schema_userdata_school_timetable_period_labels where period_label_id = $label_id");
//        foreach ($period_label as $period_label_data) {
//            $label = $period_label_data[0][0];
//            break;
//        }
//        $end = $period[0][4];
        
        if ($label == "Registration" && !$has_registration){
	    continue;
        }
        
        print "<td class=\"redips-mark dark\"><div class='tableHeader'><b>$label </b></div></td>";
        //print "<td class=\"redips-mark dark\"><b>$label </b><br> $start-$end </td>";
    }
    
}

function days($today, $school_id){
    
    //Get Today Items
    $today_items = explode(" ", $today);
    //echo "today Items : $today_items";
    $year = $today_items[2];
    $month_items = explode(":", $today_items[1]);
    $day_items = explode(":", $today_items[0]);
    $clock_items = explode(":", $today_items[3]);
    
    //Get Year Start Date
    $year_start_date = sqlQuery("select start_time from schoollms_schema_userdata_school_year_calendar where calendar_type = 1 and school_id = $school_id and year_id in (select year_id from schoollms_schema_userdata_school_year where year_label = '$year')");
    foreach ($year_start_date as $start_date) {
        $start = $start_date[0][0];
    }
    
    $year_start_date_items = explode("|", $start);
    
    
    //Fix Date Format to YYYY-MM-DD
    //Find Todays Timetable Day
    $today_token = "$year-$month_items[1]-$day_items[1]";
    $start_date_token = "$year_start_date_items[0]-$year_start_date_items[1]-$year_start_date_items[2]";
    
    //echo "<br> T $today_token S $start_date_token";
    //Count days between start date and today
    $num_days = getSchoolDays($start_date_token, $today_token);
    //$num_days = removeHolidays($num_days,)
    //Get School Days
    $timetable_days = sqlQuery("select * from schoollms_schema_userdata_school_timetable_days where school_id = $school_id");
    
    $num_timetable_days = count($timetable_days);
    
    //echo "NUM TABLE DAYS $num_timetable_days";
    
    $today_day = getTodayTimeTableDay($num_days, $num_timetable_days);
    
    //echo "TODAY $today DAY $today_day <br>";
    
    //Get Days with Date  - From today to the last timetable day
    return getDays($today, $today_day, $num_timetable_days);
    
}

function getSchoolDays($start_date_token, $today_token){
    //Remove weekends and Holidays from $date_1 to $date_2
    $count_days = 1;
    $new_num_days = 0;
    $last_day = $start_date_token;
    
    //Get Num Days
    $num_days = dateDifference($start_date_token, $today_token, '%a');
    
    //echo "NUM DAYS $num_days";
    
    //echo "<br> START DAY $last_day";
    //Weekends
    $weekends = array ("Sat", "Sun");
    
    while ($count_days <= $num_days){
        $next_day =  add_date($last_day,1,0,0);
        $next_day_tokens = explode(" ", $next_day);
        
        //echo "<br> NEXT DAY $next_day";
        
        $year = $next_day_tokens[2];
        $month_items = explode(":", $next_day_tokens[1]);
        $day_items = explode(":", $next_day_tokens[0]);
        $clock_items = explode(":", $next_day_tokens[3]);
    
        //Check Weekends
        if (in_array($day_items[0], $weekends)){
            //Do Nothing
        } elseif (isHoliday($next_day)) {//Check Holidays
            //Do Nothing
        } else {
            $new_num_days++;
        }

        $next_day = "$year-$month_items[1]-$day_items[1]";
        $last_day = $next_day;
        $count_days++;
    }
    
    //echo "<br> NUM SCHOOL DAYS $new_num_days";
    
    return $new_num_days;
    
    
}


function isHoliday($day){

    $result = FALSE;
    $day_tokens = explode(" ", $day);
  
    $year = $day_tokens[2];
    $month_items = explode(":", $day_tokens[1]);
    $day_items = explode(":", $day_tokens[0]);
    $clock_items = explode(":", $day_tokens[3]);
    
    $day_test_token = "$year|$month_items[1]|$day_items[1]|00|00|00";
    
    //Get Holidays
    $year_holidays = sqlQuery("select start_time from schoollms_schema_userdata_school_year_calendar where calendar_type = 6 and year_id in (select year_id from schoollms_schema_userdata_school_year where year_label = '$year')");
    //$year_start_date_items = explode("|", $year_start_date[0][0][0]);
    foreach ($year_holidays as $holidays) {
        $holiday = $holidays[0][0];
        
        //echo "IF HOLIDAY $holiday = DAY TEST $day_test_token <br>";
        
        if (strcmp($holiday, $day_test_token) == 0){
            $result = TRUE;
            break;
        }
    }
    
    return $result;
}


function getDays($today, $today_day, $num_timetable_days){
     
    //Weekends
    $weekends = array ("Sat", "Sun");
    
    $days = array ();
    $day_store = array ();
    $day_store['timetable_day'] = $today_day;
    $day_store['timetable_day_date'] = $today;
    array_push($days, $day_store);
    
    //Get Today Items
    $today_items = explode(" ", $today);
    
    $year = $today_items[2];
    $month_items = explode(":", $today_items[1]);
    $day_items = explode(":", $today_items[0]);
    $clock_items = explode(":", $today_items[3]);
    
    $today_token = "$year-$month_items[1]-$day_items[1]";
    $last_day = $today_token;
    
    $next_day_day = $today_day + 1;
    
    //echo "TODAY $today DAY $today_day <br>";
    $count_days = 1;
    while ($count_days <= $num_timetable_days){
        
        $next_day =  add_date($last_day,1,0,0);
        $next_day_tokens = explode(" ", $next_day);
        
        $year = $next_day_tokens[2];
        $month_items = explode(":", $next_day_tokens[1]);
        $day_items = explode(":", $next_day_tokens[0]);
        $clock_items = explode(":", $next_day_tokens[3]);
    
        //Check Weekends
        if (in_array($day_items[0], $weekends)){
            //Do Nothing
        } elseif (isHoliday($next_day)) {//Check Holidays
            //Do Nothing
        } else {
            if ($next_day_day > $num_timetable_days){
                $next_day_day = 1;
            }
    
            //echo "DATE $next_day DAY $next_day_day COUNT $count_days <br>";
            
            $day_store['timetable_day'] = $next_day_day;
            $day_store['timetable_day_date'] = $next_day;
            array_push($days, $day_store);
            $next_day_day++;
            $count_days++;
        }
        
        
        $next_day = "$year-$month_items[1]-$day_items[1]";
        $last_day = $next_day;
        
    }
 
    //print_r($days);
    
    return $days;
}

function add_date($givendate,$day=0,$mth=0,$yr=0) {
    $cd = strtotime($givendate);
    $newdate = date('D:d M:m Y H:i:s', mktime(date('h',$cd),
    date('i',$cd), date('s',$cd), date('m',$cd)+$mth,
    date('d',$cd)+$day, date('Y',$cd)+$yr));
    return $newdate;
}

function getTodayTimeTableDay($num_days, $num_timetable_days){
    
    if ($num_days > $num_timetable_days){
        $today_day = $num_days - $num_timetable_days;
        while ($today_day > $num_timetable_days){
            $today_day = $today_day - $num_timetable_days;
        }
    } else {
        $today_day = $num_timetable_days - $num_days;
    }
    
    if ($today_day == 0){
        $today_day = 1;
    }
    
    return $today_day;
}

function dateDifference($date_1 , $date_2 , $differenceFormat = '%a' )
{
    $datetime1 = date_create($date_1);
    $datetime2 = date_create($date_2);
    
    $interval = date_diff($datetime1, $datetime2);
    
    return $interval->format($differenceFormat);
    
}

function items($timetable_id, $day_id, $date_stamp, $period_label_id, $grade_id, $class_id, $room_id, $subject_id, $teacher_id, $substitute_id, $user_type, $user_id){
    
    global $timetable_items;
	global $data;
	global $data1;

    //Get TimeTable Item Fields (IF NOT initialized)
    /*if ($timetable_items === null) {

        $sql = "select * from schoollms_schema_userdata_school_timetable_items_form";
        $timetable_items = sqlQuery($sql);
    }*/
    
	$sql = "select * from schoollms_schema_userdata_school_timetable_items_form";
	//echo "$sql;";
	$data->execSQL($sql);
    //$count = count($timetable_items);
    
    //echo "COUNT ITEMS $count <br>";
    //Get TimeTable Details
	$field = "";
	while($row = $data->getRow()){
    //foreach ($timetable_items as $items) {
        
        $field = $row->field;
        //$field_data_link = $items[0][8];
        $field_references = explode("#", $row->field_data_link);
        //echo "<br/>$row->field_data_link<br/>";        
        $tablename = $field_references[0];
        $select_field = $field_references[1];
        $display_field = $field_references[2];
       // echo "$field<br/>";
        switch ($field) {
            case 'timetabl_id':
                
                break;

            case 'day_id':


                break;
            
            case 'period_label_id':
            
                break;
            
            
            case 'room_id':
				
					
					$sql = "select $display_field from $tablename where $select_field = '$room_id'";
					//echo "<br/>$sql<br/>";
					$data1->execSQL($sql);
					if($row1 = $data1->getRow())
					{
						$room = $row1->$display_field;
					}
					/*$result = sqlQuery($sql);
					foreach ($result as $value) {
						$room = $value[0][0];
					}*/
					
					break;
				
           
             case 'class_id':
                $sql = "select $display_field from $tablename where $select_field = '$class_id'";
				//echo "<br/>$sql<br/>";
				//echo "<br/>$sql<br/>";
				$data1->execSQL($sql);
				if($row1 = $data1->getRow())
				{
					$class = $row1->$display_field;
				}
                /*$result = sqlQuery($sql);
                foreach ($result as $value) {
                    $class = $value[0][0];
                }*/
                break;
                
             case 'grade_id':
                $sql = "select $display_field from $tablename where $select_field = '$grade_id'";
				//echo "<br/>$sql<br/>";
				$data1->execSQL($sql);
				if($row1 = $data1->getRow())
				{
					$grade = $row1->$display_field;
				}
                /*$result = sqlQuery($sql);
                foreach ($result as $value) {
                    $grade = $value[0][0];
                }*/
                break;
                
            case 'subject_id':
                $sql = "select $display_field from $tablename where $select_field = '$subject_id'";
				//echo "<br/>$sql<br/>";
				$data1->execSQL($sql);
				if($row1 = $data1->getRow())
				{
					$subject = $row1->$display_field;
				}
                /*$result = sqlQuery($sql);
                foreach ($result as $value) {
                    $subject = $value[0][0];
                }*/

                break;
            
            case 'teacher_id':
                $sql = "select * from $tablename where $select_field = '$teacher_id'";
				//echo "<br/>$sql --- <br/>";
				$data1->execSQL($sql);
				if($row1 = $data1->getRow())
				{
					$teacher = "$row1->name $row1->surname";
				}
                /*$result = sqlQuery($sql);
                foreach ($result as $value) {
                    $name = $value[0][4];
                    $surname = $value[0][5];
                    $teacher = "$name $surname";
                    break;
                }*/
                

                //Get Teacher Details
                break;
            
            case 'substitude_id':
                switch ($user_type) {
                    case 1://STAFF
                    case 2://LEARN
                    case 3://PARENT
                    case 5://MANAGE
                    case 6://SUPPORT 
                        $sql = "select $display_field from $tablename where $select_field = '$substitute_id'";
						//echo "<br/>$sql<br/>";
						$data1->execSQL($sql);
						if($row1 = $data1->getRow())
						{
							$substitute = $row1->$display_field;
						}
                        /*$result = sqlQuery($sql);
                        foreach ($result as $value) {
                            $substitute = $value[0][0];
                        }*/
                        break;
                        
                    case 4://TEACHER
                        $sql = "SELECT class_label FROM schoollms_schema_userdata_school_classes WHERE class_id IN (SELECT class_id FROM schoollms_schema_userdata_school_timetable_items WHERE timetabl_id = $timetable_id AND day_id = $day_id AND period_label_id = $period_label_id AND teacher_id = $teacher_id)";
                       // echo "<br/>$sql<br/>";
						$data1->execSQL($sql);
						if($row1 = $data1->getRow())
						{
							$substitute = $row1->class_label;
						}
						/*$result = sqlQuery($sql);
                        foreach ($result as $value) {
                            $substitute = $value[0][0];
                        }*/
                        //echo "CLASS $substitute <br>";
                        break;
                }
                break;
            
            default:
                break;
        }
    }
	//echo "<br/>EXIT FIELD : $field <br/>";
    
    switch ($user_type) {
        case 2:
            
        case 4:
		//echo "From here <br/>";
            $links = get_timetable_slot_links($timetable_id, $date_stamp, $day_id, $period_label_id, $class_id, $user_type, $user_id);
            break;
        
        case 5:
            
        case 6:
            $links = "";
			//$links = get_timetable_slot_links($timetable_id, $date_stamp, $day_id, $period_label_id, $class_id, $user_type, $user_id);
            break;
        
        default:
            break;
    }
    $links = urlencode($links);
	if(!isset($substitute))
	{
		$substitute = $class;
	}
    //echo "<b>$subject</b><br>$teacher<br>ROOM $room --<br>$substitute<br>$links";
    return @"<b>$subject</b><br>$teacher<br>$room<br>$substitute <br>~ $links";
   
    
}

//get time table links
function get_timetable_slot_links($timetable_id, $date_stamp, $day_id, $period_label_id, $class_id, $user_type, $user_id){
    
    $links = "";
    //echo "$timetable_id, $date_stamp, $day_id, $period_label_id, $class_id, $user_type, $user_id <br/>";
    $today = date('D:d M:m Y H:i:s');
    
    $today_items = explode(" ", $today);
    
    $year = $today_items[2];
    $month_items = explode(":", $today_items[1]);
    $day_items = explode(":", $today_items[0]);
    $clock_items = explode(":", $today_items[3]);
    
    $today_token1 = "$day_items[1]-$month_items[0]-$year";
    
    $today_token2 = "$day_items[1]-$month_items[1]-$year";
   
     if($user_type == 4) { 
        $today_token2 = $date_stamp; 
     } 
    //echo "if ($date_stamp == $today_token2){<br>"; 
    //if (strcmp($date_stamp,$today_token2) == 0){ 
       $q = "SELECT lesson_url,lesson_title,subject_id FROM schoollms_schema_userdata_school_timetable_subject_lessons WHERE day_id = $day_id AND period_label_id = $period_label_id AND class_id = $class_id AND date_format(str_to_date(lesson_date,'%d-%b-%Y'),'%d-%m-%Y') = '$today_token2'";
    
    //echo "if ($date_stamp == $today_token2){<br>";
    
    //if (strcmp($date_stamp,$today_token2) == 0){
        
        
//        $q = "SELECT lesson_url,lesson_title FROM schoollms_schema_userdata_school_timetable_subject_lessons WHERE day_id = $day_id AND period_label_id = $period_label_id AND lesson_date = '$today_token1'";
		//alert($q);

        $result = sqlQuery($q);

        if (count($result) > 0){
            $links .= "<b><i> LESSON(S):</i></b> <br>";
            $lessons = array ();
            foreach ($result as $value) {
                $lesson_title = $value[0][1];
                $lesson_url = $value[0][0];
				$subject_id = $value[0][2];
                $lessons["$lesson_url"] = "$lesson_title-|!~#-$subject_id";
                //$teacher = "$name $surname";
            }

            //echo "LESSON TITLE $lesson_title URL $lesson_url <br>";
            //Get USER DETAILS
            $q = "SELECT name, surname FROM schoollms_schema_userdata_access_profile WHERE user_id = $user_id";

            $result = sqlQuery($q);

            foreach ($result as $items) {
                $name = $items[0][0];
                $surname = $items[0][1];
                break;
            }
			
			$ip = $_SERVER['SERVER_ADDR'];
			$port = $_SERVER['SERVER_PORT'];
			if($port != 80)
			{
				$ip = "$ip:$port";
			}
			$protocol = strtolower(explode("/",$_SERVER['SERVER_PROTOCOL'])[0]);
			

            switch ($user_type) {
                case 2:
                    $q = "SELECT timetable_label FROM schoollms_schema_userdata_school_timetable WHERE timetabl_id = $timetable_id";
                    $result = sqlQuery($q);

                    foreach ($result as $items) {
                        $timetable_label = $items[0][0];
                        //$surname = $items[0][1];
                        break;
                    }
                    
                    //if ($timetable_label !== "$surname $name"){
                        $username = $timetable_label;
                   // }
                        
                    $passwd = "learn123";
                    //$url = "http://127.0.0.1:8081/learn/local_timetable_schoollms_link.php";
                    //$url = "http://192.168.43.52/mhslms/learndev/local_timetable_schoollms_link.php";
                    $url = "$protocol://$ip/learn/local_timetable_schoollms_link.php";
					foreach ($lessons as $lesson_url => $lesson_title) {
						$lesson_title = explode("-|!~#-",$lesson_title);
                        //$pars = "action=open_link&q=$lesson_url&username=$username&passwd=$passwd";
                        $pars = "action=open_link&q=$lesson_url&username=$username&passwd=$passwd&date=$date_stamp&day=$day_id&period_label_id=$period_label_id&user_type=$user_type&user_id=$user_id&lesson_title=$lesson_title[0]";
                        $links .= "<a href='$url?$pars' target='_blank'> $lesson_title[0] </a> <br><br>";
                    }
                    

                    break;

                case 4:
                    $passwd = "teach123";
                    //$url = "http://127.0.0.1:8081/teach/local_timetable_schoollms_link.php";
                    //$url = "http://192.168.43.52/mhslms/teachdev/local_timetable_schoollms_link.php";
 		   $url = "$protocol://$ip/teach/local_timetable_schoollms_link.php";
                    $username="$name $surname";                    
                    foreach ($lessons as $lesson_url => $lesson_title) {
						$lesson_title = explode("-|!~#-",$lesson_title);
                        //$pars = "action=open_link&q=$lesson_url&username=$username&passwd=$passwd";
                        $pars = "action=open_link&q=$lesson_url&username=$username&passwd=$passwd&date=$date_stamp&day=$day_id&period_label_id=$period_label_id&user_type=$user_type&user_id=$user_id&lesson_title=$lesson_title[0]";
                        $links .= "<a href=\" javascript:openLink('$url?$pars','$user_id','$user_type','Access Lesson','$lesson_title[0],$period_label_id,$day_id,$date_stamp,$lesson_url,$class_id,$lesson_title[1]');\" > $lesson_title[0] </a> &nbsp;--&nbsp; <a href=\"javascript:removelesson('$lesson_title[0]','$period_label_id','$day_id','$date_stamp','$lesson_url','$class_id','$lesson_title[1]')\">Unpublish</a> <br><br>";
                    }
                    //$pars = "action=open_link&q=$lesson_url&username=$name $surname&passwd=$passwd";

                    break;
                default:
					break;
            }
            $links .= "<b><i> RESOURCE LIBRARY:</i> </b> <br> <a href='http://172.16.0.3/#content_Resource~~~Library' target='_blank'> TYB </a> <br><br>";
            return $links;
        } else {
            return $links;
        }
//    } else {
//        return "";
//    }
//    $contents = do_post_request($url,$pars);
//    //echo "RESPONSE $contents PARA $pars<br>\n";
//    $remote_response = json_decode($contents, TRUE);
    
}

// create timetable row
function print_day($day, $row,$time_table_id,$user_type, $user_id, $year_id, $school_id) {
	global $rs;
        global $data;
        global $timetable_id;
        //echo "SCHOOL id = $school_id<br/>";
	// if $rs is null than query database (this should be only first time)
	//var_dump($rs);
	if ($rs == null) {
		//echo "<br/>Then it is here<br/>";
                $time_table_id = prepareTableView($user_id,$time_table_id,$user_type, $year_id, $school_id);
		//echo "<br/>$time_table_id<br/>";
                
                
//                $dir = "/var/www/html/school-lms/prod/prod/main/home";
//                $base_url = "http://www.schoollms.net";
//                chdir($dir);
//                //$base_url = 'http://url/to/drupal/root';
//                require_once './includes/bootstrap.inc';
//                define('DRUPAL_ROOT', getcwd());
//                drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
//
//                global $user;
                
		// first column of the query is used as key in returned array
		$sql = "select concat(tbl_row,'_',tbl_col) as pos, timetabl_id, slot_code, slot_details
						from schoollms_schema_userdata_school_timetable_view
						where timetabl_id = $time_table_id";
		
		//echo "$sql<br/>";
		
		$rs = sqlQuery($sql);
		/*echo "select concat(tbl_row,'_',tbl_col) as pos, timetabl_id, slot_code, slot_details
						from schoollms_schema_userdata_school_timetable_view
						where timetabl_id = $time_table_id <br/>";	*/			
		//unset($rs);				
	}
	
        //GET PERIOD TIMES PER WEEKDAY
        //$day_string = "<b>Day $timetable_day</b><br>$day_items[0]<br>$day_items[1]-$month_items[0]-$year";
		//echo "<br/>I tell you the truf $day_string<br/>";
        $day_tokens = explode("<br>", $day);
        $weekday = $day_tokens[1];
        //$period_labels = sqlQuery("select *  from schoollms_schema_userdata_school_timetable_period_labels WHERE school_id = $school_id order by period_label_id");
        $period_labels = array();
        $period_label_titles = array();
        $sql = "select l.* 
            from schoollms_schema_userdata_school_timetable_period_labels l
            join schoollms_schema_userdata_school_timetable_period p on p.period_label_id = l.period_label_id and l.school_id = p.school_id
            where l.school_id = $school_id 
            and week_day_id = 1
            order by UNIX_TIMESTAMP(concat('2016-01-01',' ',period_start))  asc";
        $data->execSQL($sql);
        while($row1 = $data->getRow())
        {
                $period_labels[] = $row1->period_label_id;
                $period_label_titles [] = $row1->period_label;
        }

        $weekday_tokens = sqlQuery("SELECT * FROM schoollms_schema_userdata_school_timetable_weekdays WHERE week_day_label LIKE '%$weekday%' ");
        //print_r($weekday_tokens);
        $index = 0;
        foreach($weekday_tokens as $key=>$value)
        {
                $index = $key;
        }
		
        $week_day_id = $weekday_tokens[$index][0][0];
        
        $periods = sqlQuery("select l.* 
            from schoollms_schema_userdata_school_timetable_period_labels l
            join schoollms_schema_userdata_school_timetable_period p on p.period_label_id = l.period_label_id and l.school_id = p.school_id
            where l.school_id = $school_id 
            and week_day_id = 1
            order by UNIX_TIMESTAMP(concat('2016-01-01',' ',period_start))  asc");
	
        //CHECK IF SCHOOL HAS REGISTRATION PERIOD
        $q = "select * from schoollms_schema_userdata_timetable_settings WHERE school_id = $school_id";
        $settings = sqlQuery($q);

        $has_registration = FALSE;
        foreach ($settings as $s_items){
          $settings_tokens = explode("|", $s_items[0][1]);
          $register_tokens = explode ("=", $settings_tokens[11]);
          if ($register_tokens[1] == 'Yes'){
            $has_registration = TRUE;
          }
          break;
        }
        
	$return_slots = "";
        if ($key == 1){
            $return_slots .= "<tr id='firstTr' height='75px'>";
        } else {
            $return_slots .= "<tr height='75px'>";
        }
//	print '<tr>';
//	print '<td class="mark dark">' . $day . '</td>';
	// column loop starts from 1 because column 0 is for hours
	$result = "";
	for ($col=1; $col < count($period_labels); $col++) {
		// create table cell
		$return_slots .= '<td><div style="width:150px; max-height:75px; overflow:auto">';
		// prepare position key in the same way as the array key looks
		$pos = $row . '_' . $col;
		// if content for the current position exists
		//echo "pos $pos <br/>";
		//var_dump($rs);
		if (array_key_exists($pos, $rs)) {
			// prepare elements for defined position (it could be more than one element per table cell)
			$elements = $rs[$pos];
			//var_dump($elements);
			//echo "Ingenile <hr/>";
		
			// open loop for each element in table cell
			for ($i=0; $i < count($elements); $i++) {
				// id of DIV element will start with sub_id and followed with 'b' (because cloned elements on the page have 'c') and with tbl_id
				// this way content from the database will not be in collision with new content dragged from the left table and each id stays unique
				$id = $elements[$i][2] . 'b' . $elements[$i][1];
				$name = $elements[$i][3];
				$param = str_replace("\r","",$elements[$i][3]);
				$datas = explode("<br>",$param);
				$result="";
                                $display = "";
				$dname = explode("~",$name);
				$name = $dname[0].urldecode($dname[1]);
				//var_dump($elements[$i][3] );
				//echo "$id <hr/>";
				$removeSlot = "";
				
				
				
				if(count($datas) == 4 )
				{
					$result.= "<table border=1 id=tblpopup name=tblpopup>";
					//$result.= "<tr><td>Subject</td><td>$data[0]</td></tr>";
					$result.= "<tr><td id=subjectColumn name=subjectColumn>Subject</td><td>:SUBJECT</td></tr>";
                                        
                                        switch ($user_type){
                                            case 1:
                                            case 2:
                                            case 3:
                                            case 5:
                                            case 6:
                                                $result.= "<tr><td id=teacherColumn name=teacherColumn>Teacher</td><td>$datas[1]</td></tr>";
                                                $result.= "<tr><td id=classroomColumn name=classroomColumn>Classroom</td><td>$datas[2]</td></tr>";
                                                $result.= "<tr><td id=subsColumn name=subsColumn>Substitutes</td><td>$datas[3]</td></tr>";
												$removeSlot = "<div class=\"drag $class\"> <a href=\"javascript:removeslot()\">Clear this  Slot</a></div>";											
												
                                                break;
                                            
                                            case 4:
                                                $result.= "<tr><td id=classroomColumn name=classroomColumn>Classroom</td><td>$datas[2]</td></tr>";
                                                $result.= "<tr><td id=subsClassColumn name=subsClassColumn>Class</td><td>$datas[3]</td></tr>";
                                                break;
                                            
                                            
                                        }
					
					
					$result.= "<tr><td id=dayColumn name=dayColumn>Day</td><td>$day</td></tr>";
					//echo $i;
					//var_dump($periods[1][0][1]);
					$start = $periods[$col][0][3];
					$end = $periods[$col][0][4];
					$result.= "<tr><td>Period Time</td><td>$start - $end </td></tr>";
					$result.= "</table>";
                                        //echo "RESULT $result <br> USER_TYPE $user_type <br>";
				}
                                
				$class = $elements[$i][2];
				$theperiod = $period_labels[$col ][0][2];
				
				//var_dump($class);
				$start= "";
				$end = "";
				foreach($periods as $key=>$value)
				{
					//var_dump($periods);
					//echo "<br/>";
					$start = $periods[$key][0][3];
					$end = $periods[$key][0][4];   
					
					break;					
				}
				
				//echo "$theperiod";
				//$start = $periods[$col][0][4];
				//$end = $periods[$col][0][5];
                                
				//$class = substr($id, 0, 2); // class name is only first 2 letters from ID
				//print "<div id=\"$id\" class=\"drag $class\"  onclick=\"viewTimeTableSlot('$result','$datas[0]')\">$name</div>";
				$dd = explode("CLASS",$name);
				$num = count($dd);
				//var_dump($dd) ;
				$class1 = "NOCLASS";
				if(count($dd) ==2)
				{
					//$class1 = "CLASS ".str_replace("<br>","",trim($dd[1]));
					$class1 = str_replace("<br>","",trim($dd[1]));
					$class1 = str_replace("\n","",$class1 );
					$class1 = str_replace("\r","",$class1 );
                                        
				}
                //echo strlen ($class1);
				$dclass = "";
				for($i = 0;$i < strlen ($class1);$i++)
				{
					if ($class1[$i] == '<'){
						break;
					}
                                        
					if(!empty(trim($class1[$i])))
					{
						//echo $class1[$i]."<br/>";
						$dclass .=$class1[$i];
					}
				}
				
				//$name = str_replace("<br>","",trim($name));
				//echo "$name <br/>";
				$name = str_replace("\n","",$name );
				$name = str_replace("\r","",$name );
				//$dclass_tokens = explode("<", $dclass1);
				//$dclass = $dclass_tokens[0];
				//echo "$class1 <br>\n";
				//$onclick = "viewTimeTableSlot('$result','$datas[0]','$class1','$start-$end~$day')";
				//onclick=\"viewTimeTableSlot('$result','$datas[0]','$class1','$start-$end~$day')\"
				//echo "$onclick  <br/>";
				//echo "into endtehrswehhbfdsmhfj$class1"."gsdhjgfhsdmjfhdsjhfmdshgfhdsgfnhsgdfhngsdnfhgsdnfhvdsnbvfbsndbvfnsdbvfnsdbvfnsdbvfnsdbvfnbsdvfndsbvfndsvbn <br />";
				//alert("viewTimeTableSlot");
				//echo $name;
                                //$dname = explode("<>LESSON",$name);
				if($user_type == 6)
				{
					$theDayInfo = explode("</b>", str_replace("<b>","",$day));
					$theDayInfo[1] = " - ".$theDayInfo[1];
					$date = explode("br",$theDayInfo[1]);
					$subject = str_replace("<b>","",$datas[0]);
					$removeSlot = "<div class=\"drag $class\"> <a href=\"javascript:removeslot('$theDayInfo[0]','$theperiod','$time_table_id','$school_id','$date[2]','$subject','CLASS $dclass')\">Clear this  Slot</a></div>";			
				}
				
				$dclass = str_replace("<br>","",trim($dclass));
				$dclass = str_replace("<br/>","",trim($dclass));
				$dclass = str_replace("\n","",$dclass );
			    $dclass = str_replace("\r","",$dclass );
				
				/*$dname[0] = str_replace("<br>","",trim($dname[0]));
				$dname[0] = str_replace("\n","",$dname[0] );
			    $dname[0] = str_replace("\r","",$dname[0] );
				
				$datas[0] = str_replace("<br>","",trim($datas[0]));
				$datas[0] = str_replace("\n","",$datas[0] );
			    $datas[0] = str_replace("\r","",$datas[0] );*/
				
				$coursename = $dname[0];
				$dname[0] = str_replace("<b>","<b style='font-weight:bold'>",$dname[0]);
				
				$return_slots .= "<div id=\"$id\" class=\"drag $class\" onClick=\"viewTimeTableSlot('$coursename','$datas[0]','CLASS $dclass','$start-$end~$day~$theperiod~$time_table_id~$user_type')\" >$dname[0]</div>
				$removeSlot
				<div class=\"drag $class\">".urldecode($dname[1])."</div>";
			}
		}
		// close table cell
		$return_slots .= '</div></td>';
	}
	$return_slots .= "</tr>\n";
        
        return $return_slots;
}

// create timetable row
function timetable($hour, $row) {
	global $rs;
	// if $rs is null than query database (this should be only first time)
	if ($rs === null) {
		// first column of the query is used as key in returned array
		$rs = sqlQuery("select concat(t.tbl_row,'_',t.tbl_col) as pos, t.tbl_id, t.sub_id, s.sub_name
						from redips_timetable t, redips_subject s
						where t.sub_id = s.sub_id");
	}
	print '<tr>';
	print '<td class="mark dark">' . $hour . '</td>';
	// column loop starts from 1 because column 0 is for hours
	for ($col=1; $col <= 8; $col++) {
		// create table cell
		print '<td>';
		// prepare position key in the same way as the array key looks
		$pos = $row . '_' . $col;
		// if content for the current position exists
		if (array_key_exists($pos, $rs)) {
			// prepare elements for defined position (it could be more than one element per table cell)
			$elements = $rs[$pos];
			// open loop for each element in table cell
			for ($i=0; $i < count($elements); $i++) {
				// id of DIV element will start with sub_id and followed with 'b' (because cloned elements on the page have 'c') and with tbl_id
				// this way content from the database will not be in collision with new content dragged from the left table and each id stays unique
				$id = $elements[$i][2] . 'b' . $elements[$i][1];
				$name = $elements[$i][3];
				$class = substr($id, 0, 2); // class name is only first 2 letters from ID
				print "<div id=\"$id\" class=\"drag $class\">$name</div>";
			}
		}
		// close table cell
		print '</td>';
	}
	print "</tr>\n";
}


function timetable_settings_save($school_id, $settings, $type){

    switch ($type) {
        
        case 'timetable_slot':
		//echo "WE ARE HERE <br/>";
            $user_id = 0;
            $timetable_slot_tokens = explode("|", $settings);
            //var_dump($timetable_slot_tokens);
            foreach ($timetable_slot_tokens as $key => $token) {
		//		echo "$$token <br/>";
                $token_item = explode("=", $token);
                switch ($token_item[0]) {
                    case 'subject_id':
                        $subject_id = $token_item[1];
                        break;

                    case 'teacher_id':
                        $teacher_id = $token_item[1];
                        break;
        
                    case 'grade_id':
                        $grade_id = $token_item[1];
						//alert($grade_id );
                        break;
                    
                    case 'period_label_id':
                        $period_label_id = $token_item[1];
                        break;
                    
                    case 'period_start':
                        $period_start = $token_item[1];
                        break;
                    
                    case 'period_end':
                        $period_end = $token_item[1];
                        break;
                    
                    case 'school_id':
                        $school_id = $token_item[1];
                        break;
                    
                    case 'timetable_id':
                        $timetable_id = $token_item[1];
                        
                        $q = "SELECT timetable_type_item_id FROM schoollms_schema_userdata_school_timetable WHERE school_id = $school_id AND timetabl_id = $timetable_id AND timetable_type_id IN (3, 8)";
                        $class_result = sqlQuery($q);
                        
                        //echo "QTF $q <br>";
                        if (!empty($class_result)) {
                            foreach ($class_result as $class_data) {
                                $class_id = $class_data[0][0];
                                break;
                            }
                        } else { 
                            $class_id = 0;
                        }
                        //alert("CLASS ID $class_id <br>");
                        break;
                    
                    case 'day_id':
                        $day_id = $token_item[1];
                        break;
                    
                    case 'year_id':
                        $year_id = $token_item[1];
                        break;
                    
                    case 'room_id':
                        $room_id = $token_item[1];
                        break;
                    
                    case 'timetable_user_id':
                        $user_id = $token_item[1];
                        break;
                    
                    default:
                        break;
                }
            }
            //alert("user ID : $user_id");
            if ($user_id != 0){
				//alert("ek se");
                //GET USER ROLE
                $q = "SELECT * FROM schoollms_schema_userdata_access_profile WHERE user_id = $user_id";
                $user_result = sqlQuery($q);
                
                if (!empty($user_result)){
                    foreach ($user_result as $user_data) {
                        $user_type = $user_data[0][0];
                        break;
                    }
                    
                    switch ($user_type) {
                        case 2:
                            //GET LEARNER NAMES
                            $q = "SELECT * FROM schoollms_schema_userdata_access_profile WHERE user_id = $user_id AND school_id = $school_id AND type_id = 2";
                            $learner_details = sqlQuery($q);
                            foreach ($learner_details as $learner_timedata) {
                                $name = $learner_timedata[0][4];
                                $surname = $learner_timedata[0][5];
                                break;
                            }

                            //echo "LEARNER USER ID $user_id NAME $name SURNAME $surname <br>";
                            //CHECK IF LEARNER HAS TIMETABLE
                            $q = "SELECT * FROM schoollms_schema_userdata_learner_timetable WHERE user_id = $user_id AND year_id = $year_id";
                            $learner_timetable = sqlQuery($q);

                            if (!empty($learner_timetable)){
                                foreach ($learner_timetable as $learner_timedata) {
                                    $learner_timetable_id = $learner_timedata[0][1];
                                    break;
                                }
                            } else {
                                $q = "SELECT * FROM schoollms_schema_userdata_school_timetable WHERE school_id = $school_id AND timetable_type_item_id = $user_id AND timetable_type_id = 1";
                                $learner_result = sqlQuery($q);

                                if (!empty($learner_result)) {
                                    foreach ($learner_result as $learner_data) {
                                        $learner_timetable_id = $learner_data[0][1];
                                        break;
                                    }
                                } else { 
                                    $q = "INSERT INTO schoollms_schema_userdata_school_timetable (school_id,timetable_type_id,timetable_type_item_id,timetable_label) VALUES ( $school_id, 1, $user_id, '$surname $name')";
									echo "INSERT SQL = $q <br/>";
                                    $learner_result = sqlQuery($q);

                                    $q = "SELECT * FROM schoollms_schema_userdata_school_timetable WHERE school_id = $school_id AND timetable_type_item_id = $user_id AND timetable_type_id = 1";
                                    $learner_result = sqlQuery($q);
                                    foreach ($learner_result as $learner_data) {
                                        $learner_timetable_id = $learner_data[0][1];
                                        break;
                                    }
                                }

                                //INSERT NEW LEARNER TIMETABLE
                                $q = "INSERT INTO schoollms_schema_userdata_learner_timetable (user_id,timetabl_id,year_id,timetabl_log) VALUES ($user_id, $learner_timetable_id, $year_id, 'NEW')";
                                $learner_result = sqlQuery($q);


                            }

                            //echo "LEARNER TIMETABLE ID $learner_timetable_id <br>";
                            //UPDATE LEARNER TIMETABLE SETTINGS
                            $sql = "SELECT * FROM schoollms_schema_userdata_school_timetable_items WHERE timetabl_id = $learner_timetable_id AND day_id = $day_id AND period_label_id = $period_label_id";
                            $timetable_settings = sqlQuery($sql);

                            if (!empty($timetable_settings)) {
                                $sql = "UPDATE schoollms_schema_userdata_school_timetable_items SET grade_id = $grade_id, class_id = $class_id ,room_id = $room_id , subject_id = $subject_id,teacher_id = $teacher_id , substitude_id = 0 WHERE timetabl_id = $learner_timetable_id AND day_id = $day_id AND period_label_id = $period_label_id";
                            } else {
                                $sql = "INSERT INTO schoollms_schema_userdata_school_timetable_items (timetabl_id,day_id, period_label_id, grade_id, class_id,room_id, subject_id,teacher_id,substitude_id) VALUES ($learner_timetable_id, $day_id, $period_label_id, $grade_id, $class_id, $room_id, $subject_id, $teacher_id, 0)";
                            }

                            //echo "LAST Q $sql <br>";

                            $timetable_settings = sqlQuery($sql);
                            
                            timetable_update_user_school_details($user_id, $user_type,$learner_timetable_id, $school_id, $grade_id, $class_id, $subject_id, $year_id);
                            break;
                                                
                        case 4:
                            $teacher_id = $user_id;
                            $q = "SELECT * FROM schoollms_schema_userdata_access_profile WHERE user_id = $teacher_id AND school_id = $school_id AND type_id = 4";
                            $teacher_details = sqlQuery($q);
                            foreach ($teacher_details as $teacher_timedata) {
                                $name = $teacher_timedata[0][4];
                                $surname = $teacher_timedata[0][5];
                                break;
                            }

                            //echo "TEACHER USER ID $teacher_id NAME $name SURNAME $surname <br>";
                            //CHECK IF TEACHER HAS TIMETABLE
                            $q = "SELECT * FROM schoollms_schema_userdata_teacher_timetable WHERE user_id = $teacher_id AND year_id = $year_id";
                            $teacher_timetable = sqlQuery($q);

                            if (!empty($teacher_timetable)){
                                foreach ($teacher_timetable as $teacher_timedata) {
                                    $teacher_timetable_id = $teacher_timedata[0][1];
                                    break;
                                }
                            } else {
                                $q = "SELECT * FROM schoollms_schema_userdata_school_timetable WHERE school_id = $school_id AND timetable_type_item_id = $teacher_id AND timetable_type_id = 2";
                                $teacher_result = sqlQuery($q);

                                if (!empty($teacher_result)) {
                                    foreach ($teacher_result as $teacher_data) {
                                        $teacher_timetable_id = $teacher_data[0][1];
                                        break;
                                    }
                                } else { 
                                    $q = "INSERT INTO schoollms_schema_userdata_school_timetable VALUES (NULL, $school_id, 2, $teacher_id, '$surname $name')";
                                    $teacher_result = sqlQuery($q);

                                    $q = "SELECT * FROM schoollms_schema_userdata_school_timetable WHERE school_id = $school_id AND timetable_type_item_id = $teacher_id AND timetable_type_id = 2";
                                    $teacher_result = sqlQuery($q);
                                    foreach ($teacher_result as $teacher_data) {
                                        $teacher_timetable_id = $teacher_data[0][1];
                                        break;
                                    }
                                }

                                //INSERT NEW TEACHER TIMETABLE
                                $q = "INSERT INTO schoollms_schema_userdata_teacher_timetable VALUES ($teacher_id, $teacher_timetable_id, $year_id, 'NEW')";
                                $teacher_result = sqlQuery($q);


                            }

                            //UPDATE TEACHER TIMETABLE SETTINGS
                            $sql = "SELECT * FROM schoollms_schema_userdata_school_timetable_items WHERE timetabl_id = $teacher_timetable_id AND day_id = $day_id AND period_label_id = $period_label_id";
                            $timetable_settings = sqlQuery($sql);

                            if (!empty($timetable_settings)) {
                                $sql = "UPDATE schoollms_schema_userdata_school_timetable_items SET grade_id = $grade_id , class_id = $class_id , room_id = $room_id , subject_id = $subject_id , teacher_id = $teacher_id , substitude_id = 0 WHERE timetabl_id = $teacher_timetable_id AND day_id = $day_id AND period_label_id = $period_label_id";
                            } else {
                                $sql = "INSERT INTO schoollms_schema_userdata_school_timetable_items (timetabl_id,day_id, period_label_id,grade_id,class_id, 
										room_id,subject_id,teacher_id,substitude_id) VALUES ($teacher_timetable_id, $day_id, $period_label_id, $grade_id, $class_id, $room_id, $subject_id, $teacher_id, 0)";
                            }
                            //echo "TEACHER Q $sql <br>";
                            $timetable_settings = sqlQuery($sql);
                            
                            //UPDATE TEACHER SCHOOL DETAILS
                            timetable_update_user_school_details($user_id, $user_type,$teacher_timetable_id, $school_id, $grade_id, $class_id, $subject_id, $year_id);
//                            $q = "SELECT * FROM schoollms_schema_userdata_teacher_schooldetails WHERE user_id = $user_id AND school_id = $school_id AND subject_id = $subject_id AND grade_id = $grade_id AND year_id = $year_id";
//
//                            //echo "<br> SUBJECT $q <br>";
//
//                             $result = sqlQuery($q);
//
//                            if (!empty($result)){
//                                $q = "DELETE FROM schoollms_schema_userdata_teacher_schooldetails WHERE user_id = $user_id AND school_id = $school_id AND subject_id = $subject_id AND grade_id = $grade_id AND year_id = $year_id";
//                                $result = sqlQuery($q);
//                                $sql = "SELECT * FROM schoollms_schema_userdata_school_timetable_items WHERE timetabl_id = $teacher_timetable_id AND grade_id = $grade_id AND subject_id = $subject_id";
//                                $timetable_settings = sqlQuery($sql);
//                                
//                                foreach ($timetable_settings as $settings) {
//                                    $class_id = $settings[0][4];
//                                    $q = "INSERT INTO schoollms_schema_userdata_teacher_schooldetails VALUES ($user_id, $school_id, $grade_id, $class_id, $subject_id, $year_id)";
//                                    $result = sqlQuery($q);
//                                }
//                                
//                            } else {
//                                $q = "INSERT INTO schoollms_schema_userdata_teacher_schooldetails VALUES ($user_id, $school_id, $grade_id, $class_id, $subject_id, $year_id)";
//                                $result = sqlQuery($q);
//                            }
                            break;

                        default:
                            break;
                    }
                }
                
            } elseif ($class_id !== 0) {
                //alert("we are here $class_id");
                $sql = "SELECT * FROM schoollms_schema_userdata_school_timetable_items WHERE timetabl_id = $timetable_id AND day_id = $day_id AND period_label_id = $period_label_id";
                $timetable_settings = sqlQuery($sql);

                echo "------ --- Q $sql";
                
                //var_dump($timetable_settings);
                
                if (!empty($timetable_settings)) {
                    $sql = "UPDATE schoollms_schema_userdata_school_timetable_items SET grade_id = $grade_id , class_id = $class_id , room_id = $room_id , subject_id = $subject_id , teacher_id = $teacher_id , substitude_id = 0 WHERE timetabl_id = $timetable_id AND day_id = $day_id AND period_label_id = $period_label_id";
                } else {
                    $sql = "INSERT INTO schoollms_schema_userdata_school_timetable_items (timetabl_id,day_id, period_label_id,grade_id,class_id, 
						room_id,subject_id,teacher_id,substitude_id) VALUES ($timetable_id, $day_id, $period_label_id, $grade_id, $class_id, $room_id, $subject_id, $teacher_id, 0)";
                }
                //echo "Q $sql <br>";
                $timetable_settings = sqlQuery($sql);
                
                //var_dump($timetable_settings);
                
                //UPDATE/INSERT LEARNER TIMETABLE
                $q = "SELECT * FROM schoollms_schema_userdata_learner_schooldetails WHERE school_id = $school_id AND grade_id = $grade_id AND class_id = $class_id AND year_id = $year_id";
                
                $learner_settings = sqlQuery($q);
                if (!empty($learner_settings)) {
                    foreach ($learner_settings as $learner_data) {
                        $user_id = $learner_data[0][0];
                        
                        //GET LEARNER NAMES
                        $q = "SELECT * FROM schoollms_schema_userdata_access_profile WHERE user_id = $user_id AND school_id = $school_id AND type_id = 2";
                        $learner_details = sqlQuery($q);
                        foreach ($learner_details as $learner_timedata) {
                            $name = $learner_timedata[0][4];
                            $surname = $learner_timedata[0][5];
                            break;
                        }
                        
                        //echo "LEARNER USER ID $user_id NAME $name SURNAME $surname <br>";
                        //CHECK IF LEARNER HAS TIMETABLE
                        $q = "SELECT * FROM schoollms_schema_userdata_learner_timetable WHERE user_id = $user_id AND year_id = $year_id";
                        $learner_timetable = sqlQuery($q);
                        //echo " ---- LEARNERS $q";
                        if (!empty($learner_timetable)){
                            foreach ($learner_timetable as $learner_timedata) {
                                $learner_timetable_id = $learner_timedata[0][1];
                                break;
                            }
                        } else {
                            $q = "SELECT * FROM schoollms_schema_userdata_school_timetable WHERE school_id = $school_id AND timetable_type_item_id = $user_id AND timetable_type_id = 1";
                            $learner_result = sqlQuery($q);

                            if (!empty($learner_result)) {
                                foreach ($learner_result as $learner_data) {
                                    $learner_timetable_id = $learner_data[0][1];
                                    break;
                                }
                            } else { 
                                $q = "INSERT INTO schoollms_schema_userdata_school_timetable VALUES (NULL, $school_id, 1, $user_id, '$surname $name')";
                                $learner_result = sqlQuery($q);
                                
                                $q = "SELECT * FROM schoollms_schema_userdata_school_timetable WHERE school_id = $school_id AND timetable_type_item_id = $user_id AND timetable_type_id = 1";
                                $learner_result = sqlQuery($q);
                                foreach ($learner_result as $learner_data) {
                                    $learner_timetable_id = $learner_data[0][1];
                                    break;
                                }
                            }
                            
                            //INSERT NEW LEARNER TIMETABLE
                            $q = "INSERT INTO schoollms_schema_userdata_learner_timetable (user_id,timetabl_id,year_id,timetabl_log) VALUES ($user_id, $learner_timetable_id, $year_id, 'NEW')";
                            $learner_result = sqlQuery($q);
                            
                            
                        }
                        
                        //echo "LEARNER TIMETABLE ID $learner_timetable_id <br>";
                        //UPDATE LEARNER TIMETABLE SETTINGS
                        $sql = "SELECT * FROM schoollms_schema_userdata_school_timetable_items WHERE timetabl_id = $learner_timetable_id AND day_id = $day_id AND period_label_id = $period_label_id";
                        $timetable_settings = sqlQuery($sql);

                        if (!empty($timetable_settings)) {
                            $sql = "UPDATE schoollms_schema_userdata_school_timetable_items SET grade_id = $grade_id , class_id = $class_id , room_id = $room_id , subject_id = $subject_id , teacher_id = $teacher_id , substitude_id = 0 WHERE timetabl_id = $learner_timetable_id AND day_id = $day_id AND period_label_id = $period_label_id";
                        } else {
                            $sql = "INSERT INTO schoollms_schema_userdata_school_timetable_items (timetabl_id,day_id, period_label_id,grade_id,class_id, 
								room_id,subject_id,teacher_id,substitude_id) VALUES ($learner_timetable_id, $day_id, $period_label_id, $grade_id, $class_id, $room_id, $subject_id, $teacher_id, 0)";
                        }
                        
                        //echo "LAST Q $sql <br>";
                        
                        $timetable_settings = sqlQuery($sql);
                        
                    }
                }
                
                //UPDATE/INSERT TEACHER TIMETABLE
//                $q = "SELECT * FROM schoollms_schema_userdata_teacher_schooldetails WHERE user_id = $teacher_id AND school_id = $school_id AND grade_id = $grade_id AND class_id = $class_id AND year_id = $year_id";
//                
//                $teacher_settings = sqlQuery($q);
//                if (!empty($teacher_settings)) {
//                    foreach ($teacher_settings as $teacher_data) {
//                        $user_id = $teacher_data[0][0];
                        //GET TEACHER NAMES
                        $q = "SELECT * FROM schoollms_schema_userdata_access_profile WHERE user_id = $teacher_id AND school_id = $school_id AND type_id = 4";
                        $teacher_details = sqlQuery($q);
                        foreach ($teacher_details as $teacher_timedata) {
                            $name = $teacher_timedata[0][4];
                            $surname = $teacher_timedata[0][5];
                            break;
                        }
                        
                        //echo "TEACHER USER ID $teacher_id NAME $name SURNAME $surname <br>";
                        //CHECK IF TEACHER HAS TIMETABLE
                        $q = "SELECT * FROM schoollms_schema_userdata_teacher_timetable WHERE user_id = $teacher_id AND year_id = $year_id";
			//			echo  "<hr/>$q<hr/>";
                        $teacher_timetable = sqlQuery($q);
                        //alert("WTF else");
                        if (!empty($teacher_timetable)){
                            foreach ($teacher_timetable as $teacher_timedata) {
                                $teacher_timetable_id = $teacher_timedata[0][1];
								//alert("WTF 0");
                                break;
                            }
                        } else {
                            $q = "SELECT * FROM schoollms_schema_userdata_school_timetable WHERE school_id = $school_id AND timetable_type_item_id = $teacher_id AND timetable_type_id = 2";
                            $teacher_result = sqlQuery($q);
				//			echo "<hr/>-- TEACHER TIMETBLE ID $q <hr/>";
                            if (!empty($teacher_result)) {
                                foreach ($teacher_result as $teacher_data) {
                                    $teacher_timetable_id = $teacher_data[0][1];
					//				alert("WTF 1");
                                    break;
                                }
                            } else { 
                                $q = "INSERT INTO schoollms_schema_userdata_school_timetable 
								(school_id,timetable_type_id,timetable_type_item_id,timetable_label,last_changed) 
								VALUES ( $school_id, 2, $teacher_id, '$surname $name',now())";
                                $teacher_result = sqlQuery($q);
                                //echo "<hr/>-- INSERT TEACHER  $q<hr/>";
                                $q = "SELECT * FROM schoollms_schema_userdata_school_timetable WHERE school_id = $school_id AND timetable_type_item_id = $teacher_id AND timetable_type_id = 2";
                                $teacher_result = sqlQuery($q);
                                foreach ($teacher_result as $teacher_data) {
                                    $teacher_timetable_id = $teacher_data[0][1];
									//alert("WTF 2");
                                    break;
                                }
                            }
                            
                            //INSERT NEW TEACHER TIMETABLE
                            $q = "INSERT INTO schoollms_schema_userdata_teacher_timetable VALUES ($teacher_id, $teacher_timetable_id, $year_id, 'NEW')";
                            $teacher_result = sqlQuery($q);
                            
                            
                        }
                        
                        //UPDATE TEACHER TIMETABLE SETTINGS
                        $sql = "SELECT * FROM schoollms_schema_userdata_school_timetable_items WHERE timetabl_id = $teacher_timetable_id AND day_id = $day_id AND period_label_id = $period_label_id";
                        $timetable_settings = sqlQuery($sql);

                        if (!empty($timetable_settings)) {
                            $sql = "UPDATE schoollms_schema_userdata_school_timetable_items SET grade_id = $grade_id , class_id = $class_id , room_id = $room_id , subject_id = $subject_id , teacher_id = $teacher_id , substitude_id = 0 WHERE timetabl_id = $teacher_timetable_id AND day_id = $day_id AND period_label_id = $period_label_id";
                        } else {
                            $sql = "INSERT INTO schoollms_schema_userdata_school_timetable_items (timetabl_id,day_id, period_label_id,grade_id,class_id, 
									room_id,subject_id,teacher_id,substitude_id) VALUES ($teacher_timetable_id, $day_id, $period_label_id, $grade_id, $class_id, $room_id, $subject_id, $teacher_id, 0)";
                        }
                        //echo "TEACHER Q $sql <br>";
                        $timetable_settings = sqlQuery($sql);
                        
                        //UPDATE TEACHER SCHOOL DETAILS
                        timetable_update_user_school_details($teacher_id, 4, $teacher_timetable_id, $school_id, $grade_id, $class_id, $subject_id, $year_id);
//                        $q = "SELECT * FROM schoollms_schema_userdata_teacher_schooldetails WHERE user_id = $teacher_id AND school_id = $school_id AND subject_id = $subject_id AND grade_id = $grade_id AND year_id = $year_id";
//
//                        //echo "<br> SUBJECT $q <br>";
//
//                         $result = sqlQuery($q);
//
//                        if (!empty($result)){
//                            $q = "DELETE FROM schoollms_schema_userdata_teacher_schooldetails WHERE user_id = $teacher_id AND school_id = $school_id AND subject_id = $subject_id AND grade_id = $grade_id AND year_id = $year_id";
//                            $result = sqlQuery($q);
//                            $sql = "SELECT * FROM schoollms_schema_userdata_school_timetable_items WHERE timetabl_id = $teacher_timetable_id AND grade_id = $grade_id AND subject_id = $subject_id";
//                            $timetable_settings = sqlQuery($sql);
//
//                            foreach ($timetable_settings as $settings) {
//                                $class_id = $settings[0][4];
//                                $q = "INSERT INTO schoollms_schema_userdata_teacher_schooldetails VALUES ($teacher_id, $school_id, $grade_id, $class_id, $subject_id, $year_id)";
//                                $result = sqlQuery($q);
//                            }
//
//                        } else {
//                            $q = "INSERT INTO schoollms_schema_userdata_teacher_schooldetails VALUES ($teacher_id, $school_id, $grade_id, $class_id, $subject_id, $year_id)";
//                            $result = sqlQuery($q);
//                        }
//                    }
//                }
            }
            break;
        
        case 'general':
            $sql = "select * from schoollms_schema_userdata_timetable_settings where school_id = $school_id";
			echo "school_id : $school_id";
            $timetable_settings = sqlQuery($sql);
			 $q ="";
            if (!empty($timetable_settings)) {
                $q = "UPDATE schoollms_schema_userdata_timetable_settings SET settings='$settings' WHERE school_id=$school_id";
                $timetable_settings = sqlQuery($q); 

            } else {
                $q = "INSERT INTO schoollms_schema_userdata_timetable_settings(school_id,settings) VALUES ($school_id, '$settings')";
                $timetable_settings = sqlQuery($q);
            }
			echo  $q ;
            break;
            
        case 'subject':
            $sql = "select * from schoollms_schema_userdata_timetable_subject_settings where school_id = $school_id";
            $timetable_settings = sqlQuery($sql);

            if (!empty($timetable_settings)) {
                //echo "SET $settings <br />";
                $q = "UPDATE schoollms_schema_userdata_timetable_subject_settings SET subject_settings='$settings' WHERE school_id=$school_id";
                //echo "Q $q <br />";
                $timetable_settings = sqlQuery($q); 

            } else {
                $q = "INSERT INTO schoollms_schema_userdata_timetable_subject_settings VALUES ($school_id, '$settings')";
                $timetable_settings = sqlQuery($q);
                
                
            }
            break;

        case 'class':
            $sql = "select * from schoollms_schema_userdata_timetable_class_settings where school_id = $school_id";
            $timetable_settings = sqlQuery($sql);

            if (!empty($timetable_settings)) {
                $q = "UPDATE schoollms_schema_userdata_timetable_class_settings SET settings='$settings' WHERE school_id=$school_id";
                $timetable_settings = sqlQuery($q); 

            } else {
                $q = "INSERT INTO schoollms_schema_userdata_timetable_class_settings VALUES ($school_id, '$settings')";
                $timetable_settings = sqlQuery($q);
            }
            
            timetable_generate_classlist($school_id, $settings);
            break;
        
        case 'learner':
            $sql = "select * from schoollms_schema_userdata_timetable_learner_settings where school_id = $school_id";
            $timetable_settings = sqlQuery($sql);

            if (!empty($timetable_settings)) {
                
                $q = "UPDATE schoollms_schema_userdata_timetable_learner_settings SET settings='$settings' WHERE school_id=$school_id";
                $timetable_settings = sqlQuery($q); 

            } else {
                $q = "INSERT INTO schoollms_schema_userdata_timetable_learner_settings VALUES ($school_id, '$settings')";
                $timetable_settings = sqlQuery($q);
            }
            
            break;
        
        case 'teacher':
            $sql = "select * from schoollms_schema_userdata_timetable_teacher_settings where school_id = $school_id";
            $timetable_settings = sqlQuery($sql);

            if (!empty($timetable_settings)) {
                $q = "UPDATE schoollms_schema_userdata_timetable_teacher_settings SET settings='$settings' WHERE school_id=$school_id";
                $timetable_settings = sqlQuery($q); 

            } else {
                $q = "INSERT INTO schoollms_schema_userdata_timetable_teacher_settings VALUES ($school_id, '$settings')";
                $timetable_settings = sqlQuery($q);
            }
            break;
            
        default:
            break;
    }
    
}

function timetable_update_user_school_details($user_id, $user_type, $user_timetable_id, $school_id, $grade_id, $class_id, $subject_id, $year_id){
    //UPDATE TEACHER SCHOOL DETAILS
    switch ($user_type){
        case 2://LEARNERS
            $user_schooldetails_table = "schoollms_schema_userdata_learner_schooldetails";
            
            $q = "SELECT * FROM $user_schooldetails_table  WHERE user_id = $user_id AND school_id = $school_id AND grade_id = $grade_id AND year_id = $year_id";

            //echo "<br> SUBJECT $q <br>";

             $result = sqlQuery($q);

            if (!empty($result)){
                $q = "DELETE FROM $user_schooldetails_table WHERE user_id = $user_id AND school_id = $school_id AND grade_id = $grade_id AND year_id = $year_id";
                $result = sqlQuery($q);
                $sql = "SELECT * FROM schoollms_schema_userdata_school_timetable_items WHERE timetabl_id = $user_timetable_id AND grade_id = $grade_id";
                $timetable_settings = sqlQuery($sql);

                foreach ($timetable_settings as $settings) {
                    $class_id = $settings[0][4];
                    $q = "INSERT INTO $user_schooldetails_table VALUES ($user_id, $school_id, $grade_id, $class_id, $year_id)";
                    $result = sqlQuery($q);
                }

            } else {
                $q = "INSERT INTO schoollms_schema_userdata_teacher_schooldetails VALUES ($user_id, $school_id, $grade_id, $class_id, $year_id)";
                $result = sqlQuery($q);
            }
            break;
        
        case 4://TEACHERS
            $user_schooldetails_table = "schoollms_schema_userdata_teacher_schooldetails";
            
            $q = "SELECT * FROM $user_schooldetails_table  WHERE user_id = $user_id AND school_id = $school_id AND subject_id = $subject_id AND grade_id = $grade_id AND year_id = $year_id";

            //echo "<br> SUBJECT $q <br>";

             $result = sqlQuery($q);

            if (!empty($result)){
                $q = "DELETE FROM $user_schooldetails_table WHERE user_id = $user_id AND school_id = $school_id AND subject_id = $subject_id AND grade_id = $grade_id AND year_id = $year_id";
                $result = sqlQuery($q);
                $sql = "SELECT * FROM schoollms_schema_userdata_school_timetable_items WHERE timetabl_id = $user_timetable_id AND grade_id = $grade_id AND subject_id = $subject_id";
                $timetable_settings = sqlQuery($sql);

                foreach ($timetable_settings as $settings) {
                    $class_id = $settings[0][4];
                    $q = "INSERT INTO $user_schooldetails_table VALUES ($user_id, $school_id, $grade_id, $class_id, $subject_id, $year_id)";
                    $result = sqlQuery($q);
                }

            } else {
                $q = "INSERT INTO schoollms_schema_userdata_teacher_schooldetails VALUES ($user_id, $school_id, $grade_id, $class_id, $subject_id, $year_id)";
                $result = sqlQuery($q);
            }
            break;
        
    }
    
    
}

function timetable_settings_update($school_id, $settings, $type, $type_id, $more=null){
    
    switch ($type) {

        case 'teacher_update': 
//          $subject_settings = "subject_id=$subject_id%grade_id=$grade_id-number_periods=$number_periods";
//          $teacher_settings = "teacher_settings#user_id=$user_id:$subject_settings,substitute=$substitute";
//          $settings_string = "year_id=$year_id<number_teachers=$number_teachers|$teacher_settings";
            $settings_tokens = explode("<", $settings);
            $test_tokens = explode("|", $settings_tokens[1]);
			echo "<hr/>";
			    
            
            if (strpos($type_id, "|")){
                $teacher_tokens = explode("|", $type_id);
            } else {
                $year_id = $type_id;
            }
            $test_tokens = timetable_values_get('teacher_settings',  $test_tokens);
			
            $teacher_subject_settings = array ();
            
            $teacher_settings = $test_tokens['teacher_settings'];
			var_dump($test_tokens); 
			echo "<br/>";
			var_dump($teacher_settings);
            echo "<hr/>";
            echo "TYPE ID $type_id <br>";
			
            $search_test_tokens = explode(":", $teacher_settings);
            $teacher_subject_data = $search_test_tokens[1];
            $search_test_token = explode("=", $search_test_tokens[0]);
            $teacher_id = $search_test_token[1];
            echo "TEACHER ID $teacher_id <br>";
            
            echo "TEACHER SETTINGS $teacher_settings <br>";
            echo "<hr/>";
			var_dump($teacher_tokens);
			echo "<hr/>";
            foreach ($teacher_tokens as $key => $value) {
                echo "KEY $key VALUE $value <br />";
                if (strpos($value, '#')){
					echo "EK SE -- ";
                    $value_tokens = explode("#", $value);
                    $more .= "$value_tokens[0]#";
                    $search_test_tokens = explode("#", $teacher_tokens[$key]);
                      
                    $test_teacher = array ();
                    $test_subject = array ();
                    echo "FOUND TOKENS $search_test_tokens[1] <br />";
                    if (strpos($search_test_tokens[1], "+")){
                        echo "LOAD TEACHERS <br>";
                        $test_teacher_tokens = explode("+", $search_test_tokens[1]);
                        $copy_test_teacher_tokens = $test_teacher_tokens;
                        foreach ($test_teacher_tokens as $key2 => $teacher_values) {
                            $teacher_values = explode(":", $teacher_values);
                            array_push($test_teacher, $teacher_values[0]);
                            
                            
                            //Get Teacher Subject Settings
                            $teacher_subject_settings = timetable_settings_get($school_id, 'teacher_subject_settings', $teacher_values, $teacher_subject_settings);
                            
                        }
                    } else {
                        $copy_test_teacher_tokens = array ();
                        $teacher_values = explode(":", $search_test_tokens[1]);
                        array_push($copy_test_teacher_tokens, $teacher_values);
                        array_push($test_teacher, $teacher_values[0]);
                        $teacher_subject_settings = timetable_settings_get($school_id, 'teacher_subject_settings', $teacher_values, $teacher_subject_settings);
                    }
                    
                    echo "TEACHERS";
                    print_r($test_teacher);                    
                    $found = FALSE;
                    foreach ($test_teacher as $key2 => $value2) {
                        $value2_tokens = explode("=", $value2);
                        
                        echo "COMPARE $value2_tokens[1] === $teacher_id <br />";
                        if ($value2_tokens[1] == $teacher_id){
                            $found = TRUE;
                            
                            //UPDATE SUBJECTS
                            echo "NEW SETTINGS $teacher_subject_data OLD SETTINGS <br>";
                            print_r($teacher_subject_settings[$teacher_id]);
                            
                            $copy_test_teacher_tokens[$key2] = timetable_recursive_update('teacher_settings', $school_id, $teacher_subject_data, $teacher_subject_settings[$teacher_id]);
                            
                            echo "RESULT RECURSIVE $copy_test_teacher_tokens[$key2] <br>";
                            
                            $settings = implode("+", $copy_test_teacher_tokens);
                            break;
                            //$search_test_token[1];
                        }
                        
                    }
                    
                    if (!$found){
                        $teacher_settings .= "+$search_test_tokens[1]";
                        $settings = $teacher_settings;
                    }
//                    } else {
//                    
//                        foreach ($test_tokens as $key => $value) {
//    //                //echo "KEY $key VALUE $value <br />";
//                            if (strcmp($key, 'teacher_settings') == 0){
                                //$more .= "teacher_settings";
                                $settings = $more.$settings;
//                                break;
//                            } else {
//                                $more .= "$key=$value|";
//                            }
//                        }
//                    }
                    echo "IMPLODE SET $settings <br />";
                   // break;
                
                } else {
                    $entry = $teacher_tokens[$key];
                    echo "ENTRY $entry  <br />";
                    $more .= "$entry|";
                }
            }
            break;
        
        case 'teacher':
            $settings = timetable_settings_process($school_id, 'schoollms_schema_userdata_timetable_teacher_settings', $settings, $type, $type_id);

            break;
        
        case 'class':
            $settings = timetable_settings_process($school_id, 'schoollms_schema_userdata_timetable_class_settings', $settings, $type, $type_id);
            break;
        
        case 'class_update':
            $settings = timetable_settings_process_update($school_id, $settings, $type, $type_id, $more);
            break;
        
        case 'learner':
            $settings = timetable_settings_process($school_id, 'schoollms_schema_userdata_timetable_learner_settings', $settings, $type, $type_id);
            break;
        
        case 'learner_update':
            $settings = timetable_settings_process_update($school_id, $settings, $type, $type_id, $more);
            break;
        
        case 'grade_update':
            //type_id = subject_color=|period_type=double|period_times=afternoons|grade_setting#grade_id=12:grade_subject_color=ab2567,notional_time=TEST,period_cycle=10,minimum_learners=TEST
            //settings = subject_id=18<subject_color=|period_type=double|period_times=afternoons|grade_setting#grade_id=12:grade_subject_color=ab2567,notional_time=TEST,period_cycle=10,minimum_learners=TEST
            $settings_tokens = explode("<", $settings);
            $test_tokens = explode("|", $settings_tokens[1]);
            $grade_tokens = explode("|", $type_id);
            
            foreach ($grade_tokens as $key => $value) {
                //echo "KEY $key VALUE $value <br />";
                if (strpos($value, '#')){
                    $value_tokens = explode("#", $value);
                    $more .= "$value_tokens[0]#";
                    $search_test_tokens = explode("#", $test_tokens[$key]);
                    $search_test_token = explode(":", $search_test_tokens[1]);
                    $search_test_token = explode("=", $search_test_token[0]);
                    $test_grades = array ();
                    
                    //echo "FOUND TOKENS $value_tokens[1] <br />";
                    if (strpos($value_tokens[1], "+")){
                        $test_grade_tokens = explode("+", $value_tokens[1]);
                        $copy_test_grade_tokens = $test_grade_tokens;
                        foreach ($test_grade_tokens as $key2 => $grade_values) {
                            $grade_values = explode(":", $grade_values);
                            array_push($test_grades, $grade_values[0]);
                        }
                    } else {
                        $copy_test_grade_tokens = array ();
                        $grade_values = explode(":", $value_tokens[1]);
                        array_push($copy_test_grade_tokens, $grade_values);
                        array_push($test_grades, $grade_values[0]);
                    }
                    
                    $found = FALSE;
                    foreach ($test_grades as $key2 => $value2) {
                        $value2_tokens = explode("=", $value2);
                        
                        //echo "COMPARE $value2_tokens[1] === $search_test_token[1] <br />";
                        if ($value2_tokens[1] === $search_test_token[1]){
                            $found = TRUE;
                            $copy_test_grade_tokens[$key2] = $search_test_tokens[1];
                            $settings = implode("+", $copy_test_grade_tokens);
                            break;
                            //$search_test_token[1];
                        }
                        
                    }
                    
                    if (!$found){
                        $value_tokens[1] .= "+$search_test_tokens[1]";
                        $settings = $value_tokens[1];
                    }
                    
                    $settings = $more.$settings;
                    //echo "IMPLODE SET $settings <br />";
                    break;
                
                } else {
                    $entry = $test_tokens[$key];
                    //echo "ENTRY $entry  <br />";
                    $more .= "$entry|";
                }
            }
            break;
        
        case 'subject':
            $sql = "select * from schoollms_schema_userdata_timetable_subject_settings where school_id = $school_id";
            $timetable_settings = sqlQuery($sql);

            $subject_id = $type_id;
            
            if (!empty($timetable_settings)){
                foreach ($timetable_settings as $setting_item) {
                    $id = $setting_item[0][0];
                    $item = $setting_item[0][1];
                    break;
                }
                
                if (strpos($item, ";")){
                    $item_tokens = explode(";", $item);
                } else {
                    $item_tokens = array ();
                    array_push($item_tokens, $item);
                }

                $count_item_tokens = count($item_tokens);
                $item_tokens_copy = $item_tokens;
                $found = FALSE;
                $subject_string_copy = "";
                foreach ($item_tokens as $key => $value) {
                    $value_tokens = explode("<", $value);
                    $subject_string_copy .= $value_tokens[0]."<";
                    $subject_tokens = explode("=", $value_tokens[0]);

                    if ($subject_tokens[1] === $subject_id){
                        $found = TRUE;
                        //echo "UPDATE GRADE SET BEFORE $settings VALUE TOKENS $value_tokens[1] <br />";
                        $settings = timetable_settings_update($school_id, $settings, 'grade_update', $value_tokens[1], $subject_string_copy);
                        //echo "UPDATE GRADE SET AFTER $settings <br />";
                        
                        $item_tokens_copy[$key] = $settings;
                        break;
                    } else {
                        $subject_string_copy = "";
                    }
                }
//                
                if ($count_item_tokens > 1 && $found){
                    $settings = implode(";", $item_tokens_copy);
                } elseif ($count_item_tokens > 1 && !$found) {
                    array_push($item_tokens_copy, $settings);
                    $settings = implode(";", $item_tokens_copy);
                } elseif ($found) {
                    $settings = $item_tokens_copy[0];
                } elseif (!$found) {
                    array_push($item_tokens_copy, $settings);
                    $settings = implode(";", $item_tokens_copy);
                }
                
            }
            break;

        default:
            break;
    }
    
    return $settings;
}

function timetable_settings_process_update($school_id, $settings, $type, $type_id, $more=null){
    
    //type_id = subject_color=|period_type=double|period_times=afternoons|grade_setting#grade_id=12:grade_subject_color=ab2567,notional_time=TEST,period_cycle=10,minimum_learners=TEST
    //settings = subject_id=18<subject_color=|period_type=double|period_times=afternoons|grade_setting#grade_id=12:grade_subject_color=ab2567,notional_time=TEST,period_cycle=10,minimum_learners=TEST
    $settings_tokens = explode("<", $settings);
    $test_tokens = explode("|", $settings_tokens[1]);
    $grade_tokens = explode("|", $type_id);

    foreach ($grade_tokens as $key => $value) {
        //echo "KEY $key VALUE $value <br />";
        if (strpos($value, '#')){
            $value_tokens = explode("#", $value);
            $more .= "$value_tokens[0]#";
            $search_test_tokens = explode("#", $test_tokens[$key]);
            $search_test_token = explode(":", $search_test_tokens[1]);
            $search_test_token = explode("=", $search_test_token[0]);
            $test_grades = array ();

            //echo "FOUND TOKENS $value_tokens[1] <br />";
            if (strpos($value_tokens[1], "+")){
                $test_grade_tokens = explode("+", $value_tokens[1]);
                $copy_test_grade_tokens = $test_grade_tokens;
                foreach ($test_grade_tokens as $key2 => $grade_values) {
                    $grade_values = explode(":", $grade_values);
                    array_push($test_grades, $grade_values[0]);
                }
            } else {
                $copy_test_grade_tokens = array ();
                $grade_values = explode(":", $value_tokens[1]);
                array_push($copy_test_grade_tokens, $grade_values);
                array_push($test_grades, $grade_values[0]);
            }

            $found = FALSE;
            foreach ($test_grades as $key2 => $value2) {
                $value2_tokens = explode("=", $value2);

                //echo "COMPARE $value2_tokens[1] === $search_test_token[1] <br />";
                if ($value2_tokens[1] === $search_test_token[1]){
                    $found = TRUE;
                    $copy_test_grade_tokens[$key2] = $search_test_tokens[1];
                    $settings = implode("+", $copy_test_grade_tokens);
                    break;
                    //$search_test_token[1];
                }

            }

            if (!$found){
                $value_tokens[1] .= "+$search_test_tokens[1]";
                $settings = $value_tokens[1];
            }

            $settings = $more.$settings;
            //echo "IMPLODE SET $settings <br />";
            break;

        } else {
            $entry = $test_tokens[$key];
            //echo "ENTRY $entry  <br />";
            $more .= "$entry|";
        }
    }
    
    return $settings;
}

function timetable_settings_process($school_id, $table, $settings, $type, $type_id){
    
    switch($type){
        
        case 'teacher':
            $process_update = 'teacher_update';
            break;
        
        case 'learner':
            $process_update = 'learner_update';
            break;
        
        case 'subject':
            $process_update = 'subject_update';
            break;
        
        case 'class':
            $process_update = 'class_update';
            break;
        
        default:
            break;
    }
    
    $sql = "select * from $table where school_id = $school_id";
    $timetable_settings = sqlQuery($sql);

    if (!empty($timetable_settings)){
        foreach ($timetable_settings as $setting_item) {
            $id = $setting_item[0][0];
            $item = $setting_item[0][1];
            break;
        }

        if (strpos($item, ";")){
            $item_tokens = explode(";", $item);
        } else {
            $item_tokens = array ();
            array_push($item_tokens, $item);
        }

        $count_item_tokens = count($item_tokens);
        $item_tokens_copy = $item_tokens;
        $found = FALSE;
        $subject_string_copy = "";
        foreach ($item_tokens as $key => $value) {
            $value_tokens = explode("<", $value);
            $subject_string_copy .= $value_tokens[0]."<";
            $subject_tokens = explode("=", $value_tokens[0]);

            if ($subject_tokens[1] == $type_id){
                $found = TRUE;
                echo "UPDATE GRADE SET BEFORE $settings VALUE TOKENS $value_tokens[1] <br />";
				echo "<br/>HHHRRR--- $school_id, $process_update,  $subject_string_copy <br/>";
                $settings = timetable_settings_update($school_id, $settings, $process_update, $value_tokens[1], $subject_string_copy);
                echo "UPDATE GRADE SET AFTER $settings <br />";

                $item_tokens_copy[$key] = $settings;
                break;
            } else {
                $subject_string_copy = "";
            }
        }
//                
        if ($count_item_tokens > 1 && $found){
            $settings = implode(";", $item_tokens_copy);
        } elseif ($count_item_tokens > 1 && !$found) {
            array_push($item_tokens_copy, $settings);
            $settings = implode(";", $item_tokens_copy);
        } elseif ($found) {
            $settings = $item_tokens_copy[0];
        } elseif (!$found) {
            array_push($item_tokens_copy, $settings);
            $settings = implode(";", $item_tokens_copy);
        }
    }    
    return $settings;
}

function timetable_values_get($type,  $settings, $temp_data = null){

    $result = array();
    switch ($type){
        case 'timetable_string_data_tokens':
            $string_data_tokens = explode("#", $settings);
            foreach ($string_data_tokens as $key => $string_items) {
                $string_item_tokens = explode(":", $string_items);
                $string_item_tokens = explode(",", $string_item_tokens[1]);
                foreach ($string_item_tokens as $index => $value) {
                    $result["$index"] = $value;
                }
            }
            break;
        case 'teacher_settings':
            foreach ($settings as $key => $value) {
                if (strpos($value, "#")){
                    $teacher_item_tokens = explode("#", $value);
                } else {
                    $teacher_item_tokens = explode("=", $value);
                }
                $index = $teacher_item_tokens[0];
                $value = $teacher_item_tokens[1];
                $result["$index"] = $value;
            }
            break;
        
        case 'subject_details':
            $subject_details = explode("|", $settings);
            foreach ($subject_details as $index => $value) {
                if (strpos($value, "#")){
                    $subject_item_tokens = explode("#", $value);
                } else {
                    $subject_item_tokens = explode("=", $value);
                }
                $index = $subject_item_tokens[0];
                $value = $subject_item_tokens[1];
                $result["$index"] = $value;
            }
            break;
        
        case 'grade_subject_settings':
            foreach($settings as $key=> $grade_subject_item){
                $grade_subject_item_tokens = explode("=", $grade_subject_item);
                $index = $grade_subject_item_tokens[0];
                $value = $grade_subject_item_tokens[1];
                $result["$index"] = $value;
            }
            break;
        
        case 'number_of_teachers':
            
            break;
        
        case 'grade_from_to':
            $from_grade = $settings['from_grade'];
    
            $to_grade = $settings['to_grade'];
            
            if (is_string($from_grade)){
                $count_grade = 0;
                $temp_grade = $from_grade;
            } else {
                $count_grade = $from_grade;
                $temp_grade = $from_grade;
            }
            
            $result['count_grade'] = $count_grade;
            $result['temp_grade'] = $temp_grade;
            break;
       
    }
    
    return $result;
}


function timetable_validate_selection($school_id, $settings){
    
}

function timetable_recursive_update($type, $school_id, $new_settings, $old_settings){
    echo "<br/>EKSE HERE <br/>";	
    var_dump($old_settings);
	echo "<br/>EKSE HERE<hr/>";
    switch ($type) {
        case 'teacher_settings':
            $update_setting = array ();
            $old_settings_copy = $old_settings;
            foreach ($old_settings as $index=>$old_settings_data){
				echo "<hr/>AHHAHHAA -- <br/>";
				var_dump($old_settings_data);
				var_dump($new_settings);
				echo "<hr/>";
                $temp_subject_id_tokens = explode("=", $old_settings_data['subject_id']);
                $temp_subject_id = $temp_subject_id_tokens[1];
                
                $new_subject_id_data = explode("%", $new_settings);
                $new_subject_id_tokens = explode("=", $new_subject_id_data[0]);
                
                $new_subject_id = $new_subject_id_tokens[1];
                $new_grade_id_items = explode("!", $new_subject_id_data[1]);
                $new_grade_id_tokens = explode("=", $new_grade_id_items[0]);
                $new_grade_id = $new_grade_id_tokens[1];
                
                if ($new_subject_id == $temp_subject_id){
                   //Compare Grade Data
                    $grade_found = FALSE;
                   if (strpos($old_settings_data['grade_data'], '&')){
                       $grade_data_tokens = explode("&", $old_settings_data['grade_data']);
                       $copy_grade_data_tokens = $grade_data_tokens; 
                       foreach ($grade_data_tokens as $key => $grade_items) {
                           $grade_items_tokens = explode("!", $grade_items);
                           $grade_id_items = explode("=", $grade_items_tokens[0]);
                           $old_grade_id = $grade_id_items[1];
                           if ($old_grade_id == $new_grade_id){
                               $grade_found = TRUE;
                               $old_grade_string = $grade_items_tokens[1];
                               $new_grade_string = $new_grade_id_items[1];
                               echo "OLD $old_grade_string NEW $new_grade_string <br>";
                                $grade_items_tokens[1] = $new_grade_id_items[1];
                                $copy_grade_data_tokens[$key] = implode("!", $grade_items_tokens);
                                $old_settings_copy[$index]['grade_data'] = implode("&", $copy_grade_data_tokens);
                               break;
                           }
                       }
                       
                       if (!$grade_found){
                           $old_settings_copy[$index]['grade_data'] .= "&$new_grade_id_items[1]";
                       }
                    } else {
                       $grade_data_tokens = explode("!", $old_settings_data['grade_data']);
                       $grade_items = explode("=", $grade_data_tokens[0]);
                       $old_grade_id = $grade_items[1];
                       
                       if ($old_grade_id == $new_grade_id){
                           $grade_found = TRUE;
                          $grade_data_tokens[1] = $new_grade_id_items[1];
                       } else {
                           $grade_data_tokens[1] .= "&".$new_grade_id_items[1];
                       }
                       //$old_settings_data['grade_data'] = implode(";", $grade_data_tokens);
                        $old_settings_copy[$index]['grade_data'] = implode("!", $grade_data_tokens);
                     
                   }
                   
                  
                   break;
                }
                
            }
            
            $temp_string = "";
            foreach ($old_settings_copy as $key => $value) {
                $temp_subject_id = $value['subject_id'];
                $temp_data = $value['grade_data'];
                echo "OLD SETTINGS COPY TEMP SUBJECT ID<br>";
                print_r($temp_subject_id);
                echo "OLD SETTINGS COPY GRADE DATA<br>";
                print_r($temp_data);
                $temp_string = "$temp_subject_id%$temp_data";
                array_push($update_setting, $temp_string);
            }
            $result = implode("*", $update_setting);
            break;

        default:
            break;
    }
    return $result;
}

function timetable_time_calculate($type, $temp_time, $settings, $timetable_slots, $count_days, $count_periods){

    $results = array ();
    switch($type){
        
        case 'period_times':
            //$selectedTime = "9:15";
            $temp_time_tokens = explode(":", $temp_time);
            
            $period_time = $settings['period_time'];
            $end_time = strtotime("+$period_time minutes", strtotime($temp_time));
            $end_time = date('H:i', $end_time);
            $end_time_tokens = explode(":", $end_time);
            
            //Check if Period End Time is Less Than any Break Time
            $break_times = $settings['break_times'];
            
           
            
            $break_time_tokens = explode("*", $break_times);
            
            foreach ($break_time_tokens as $key => $temp_break_time) {
                $results['break_times'] = 0;
                
                $temp_break_time_tokens = explode("!", $temp_break_time);
                $temp_break_tokens = explode(":", $temp_break_time_tokens[1]);
                $key++;
                
                $break_time = $timetable_slots[$count_days][$count_periods]["break_time_$key"];
                
                // echo "BREAK TIME $break_time KEY $key <br>";
                if (isset($timetable_slots[$count_days]["break_time_$key"])){
                    //$results['break_times'] = 0;
                    $results['period_times'] = "$temp_time-$end_time";
                    //$results['period_end_time'] = $end_time;
                    $results['temp_time'] = $end_time;
                    //echo "BREAK TIME SET<br>";
                    continue;
                }
                
                //echo "BREAK TIME NOT SET<br>";
                
                //$results['break_times'] = 0;
                
                //echo "IF ($end_time_tokens[0] <= $temp_break_tokens[0] && $end_time_tokens[1] <= $temp_break_tokens[1]) <br>";
                if ($end_time_tokens[0] <= $temp_break_tokens[0] && $end_time_tokens[1] <= $temp_break_tokens[1]){
                    //echo "TRUE <br>";
                    //echo "if ($end_time_tokens[0] == $temp_break_tokens[0] && $end_time_tokens[1] == $temp_break_tokens[1]) <br>";
                    if ($end_time_tokens[0] == $temp_break_tokens[0] && $end_time_tokens[1] == $temp_break_tokens[1]){
                      //  echo "TRUE <br>";
                        $break_start_time = $temp_break_time_tokens[1];
                        $break_length = $temp_break_time_tokens[2];
                        $break_end_time = strtotime("+$break_length minutes", strtotime($break_start_time));
                        $break_end_time = date('H:i', $break_end_time);
                        $break_end_time_tokens = explode(":", $break_end_time);
                        $results['break_times'] = "$break_start_time-$break_end_time";
                        $temp_time = $break_end_time;
                        $end_time = strtotime("+$period_time minutes", strtotime($temp_time));
                        $end_time = date('H:i', $end_time);
                        $results['period_times'] = "$temp_time-$end_time";
                        //$results['period_end_time'] = $end_time;
                        $results['temp_time'] = $end_time;
                        $results['break_key'] = $key;
                    } else {
                        //echo "TRUE <br>";
                        $results['break_times'] = 0;
                        $results['period_times'] = "$temp_time-$end_time";
                        //$results['period_end_time'] = $end_time;
                        $results['temp_time'] = $end_time;
                    }
                    break;
                //} else if ($settings['number_of_breaks'] == 1){
                } else if ($end_time_tokens[0] <= $temp_break_tokens[0] && $end_time_tokens[1] > $temp_break_tokens[1]){
                    //echo "if ($end_time_tokens[0] <= $temp_break_tokens[0] && $end_time_tokens[1] > $temp_break_tokens[1]) TRUE <br>";
                    $break_start_time = $temp_break_time_tokens[1];
                    $break_length = $temp_break_time_tokens[2];
                    $break_end_time = strtotime("+$break_length minutes", strtotime($break_start_time));
                    $break_end_time = date('H:i', $break_end_time);
                    $break_end_time_tokens = explode(":", $break_end_time);

                    //echo "if ($temp_time_tokens[0] >= $break_end_time_tokens[0]) <br>";
                    if ($temp_time_tokens[0] >= $break_end_time_tokens[0]){
                        //echo "TRUE <br>";
                        //if ($temp_time_tokens[1] >= $break_end_time_tokens[1]){
                            $results['break_times'] = "$break_start_time-$break_end_time";
                            $temp_time = $break_end_time;
                            $end_time = strtotime("+$period_time minutes", strtotime($temp_time));
                            $end_time = date('H:i', $end_time);
                            $results['period_times'] = "$temp_time-$end_time";
                            //$results['period_end_time'] = $end_time;
                            $results['temp_time'] = $end_time;
                            $results['break_key'] = $key;
                            
                        //} else {

                        //}
                    } else {
                        //echo "FALSE <br>";
                        $results['break_times'] = 0;
                        $results['period_times'] = "$temp_time-$end_time";
                        //$results['period_end_time'] = $end_time;
                        $results['temp_time'] = $end_time;
                        //break;
                    }
                    break;
                } else if ($end_time_tokens[0] > $temp_break_tokens[0]){
                    echo "if ($end_time_tokens[0] > $temp_break_tokens[0]) TRUE <br>";
                    $break_start_time = $temp_break_time_tokens[1];
                    $break_length = $temp_break_time_tokens[2];
                    $break_end_time = strtotime("+$break_length minutes", strtotime($break_start_time));
                    $break_end_time = date('H:i', $break_end_time);
                    $break_end_time_tokens = explode(":", $break_end_time);
                    
                    //echo "if ($temp_time_tokens[0] >= $break_end_time_tokens[0]) <br>";
                    if ($temp_time_tokens[0] >= $break_end_time_tokens[0]){
                      ///      echo "TRUE <br>";
                        //if ($temp_time_tokens[1] >= $break_end_time_tokens[1]){
                            $results['break_times'] = "$break_start_time-$break_end_time";
                            $temp_time = $break_end_time;
                            $end_time = strtotime("+$period_time minutes", strtotime($temp_time));
                            $end_time = date('H:i', $end_time);
                            $results['period_times'] = "$temp_time-$end_time";
                            //$results['period_end_time'] = $end_time;
                            $results['temp_time'] = $end_time;
                            $results['break_key'] = $key;
                            //break;
                        //} else {

                        //}
                    } else {
                        //echo "FALSE <br>";
                        $results['break_times'] = 0;
                        $results['period_times'] = "$temp_time-$end_time";
                        //$results['period_end_time'] = $end_time;
                        $results['temp_time'] = $end_time;
                        
                    }
                    break;
                } else {
                    //echo "if ($end_time_tokens[0] > $temp_break_tokens[0]) ELSE <br> ";
                    //CHECK IF BREAK FOUND
//                    if (isset($timetable_slots[$count_days]['break_time_1'])){
//                        $results['period_times'] = "$temp_time-$end_time";
//                        //$results['period_end_time'] = $end_time;
//                        $results['temp_time'] = $end_time;
//                    } else {
                        //Get Break END TIME
                        $break_start_time = $temp_break_time_tokens[1];
                        $break_length = $temp_break_time_tokens[2];
                        $break_end_time = strtotime("+$break_length minutes", strtotime($break_start_time));
                        $break_end_time = date('H:i', $break_end_time);
                        $break_end_time_tokens = explode(":", $break_end_time);

                        //echo "if ($temp_time_tokens[0] >= $break_end_time_tokens[0]) <br>";
                        if ($temp_time_tokens[0] >= $break_end_time_tokens[0]){
                            //echo "TRUE <br>";
                            //if ($temp_time_tokens[1] >= $break_end_time_tokens[1]){
                                $results['break_times'] = "$break_start_time-$break_end_time";
                                $temp_time = $break_end_time;
                                $end_time = strtotime("+$period_time minutes", strtotime($temp_time));
                                $end_time = date('H:i', $end_time);
                                $results['period_times'] = "$temp_time-$end_time";
                                //$results['period_end_time'] = $end_time;
                                $results['temp_time'] = $end_time;
                                
                            //} else {

                            //}
                        } else {
                            //echo "FALSE <br>";
                            $results['break_times'] = 0;
                            $results['period_times'] = "$temp_time-$end_time";
                            //$results['period_end_time'] = $end_time;
                            $results['temp_time'] = $end_time;
                        }
                        break;
                    //}
//                } else {
//                    
                }
            }
            
            //
            
            break;
        
        default:
            break;
    }
    
    $results['timetable_slots'] = $timetable_slots;
    return $results;
}


function timetable_settings_get($school_id, $type, $data=null, $more=null){

    $return_result = array ();
    
    $settings_found = FALSE;
    echo "<hr/> timetable_settings_get($school_id, $type, $data=null, $more=null) <hr/>";
    switch ($type) {
        
        case 'teacher_subject_settings':
            //$teacher_values = explode("*", $data[0]);
			echo "<hr/>STARTTT ";
			var_dump($data[0]);
			echo "<hr/>";
            $teacher_id_tokens = explode("=", $data[0]);
			echo "<hr/>";
			var_dump($data[0]);
			echo "<hr/>";
			var_dump($teacher_id_tokens);
			$teacher_id = $teacher_id_tokens[1]; 
			var_dump($teacher_id);
			echo "<hr/>";
           
            $teacher_settings_tokens = $data[1];
                
            $teacher_subjects = array ();
            $teacher_subjects['subject_id'] = array ();
            $teacher_subjects['grade_data'] = array ();
            if (strpos($teacher_settings_tokens, "*")){
                $teacher_subject_tokens = explode("*", $teacher_settings_tokens);
                foreach ($test_subject_tokens as $key2 => $subject_values) {
                    $subject_values = explode("%", $subject_values);
                    array_push($teacher_subjects['subject_id'], $subject_values[0]);
                    array_push($teacher_subjects['grade_data'], $subject_values[1]);
                }
                   
            } else {
                //$copy_test_subject_tokens = array ();
                $subject_values = explode("%", $teacher_settings_tokens[0]);
                array_push($teacher_subjects['subject_id'], $subject_values[0]);
                array_push($teacher_subjects['grade_data'], $subject_values[1]);   
            }
            
            if (is_array($more[$teacher_id])){
                array_push($more[$teacher_id], $teacher_subjects);
            } else {
                $more[$teacher_id] = array ();
                array_push($more[$teacher_id], $teacher_subjects);
            }
            
            $return_result = $more;
            
            break;
        
        case 'general':
            $sql = "select settings from schoollms_schema_userdata_timetable_settings where school_id = $school_id";
            $timetable_settings = sqlQuery($sql);

            if (!empty($timetable_settings)) {
                $settings_found = TRUE;
                foreach ($timetable_settings as $value) {
                    $settings = $value[0][0];
                }

                if (strpos($settings, "|")){

                    $settings_tokens = explode("|", $settings);

                    foreach ($settings_tokens as $key => $token) {
                        $token_items = explode("=", $token);
                        switch ($token_items[0]){

                            case 'days':
                                //$days = $token_items[1];
                                $return_result['days'] = $token_items[1];
                                break;

                            case 'periods':
                                //$periods = $token_items[1];
                                $return_result['periods'] = $token_items[1];
                                break;

                            case 'period_time':
                                //$period_time = $token_items[1];
                                $return_result['period_time'] = $token_items[1];
                                break;

                            case 'break_times':
                                //$break_time = $token_items[1];
                                $return_result['break_times'] = $token_items[1];
                                break;
                            
                            case 'class_start_time':
                                //$break_time = $token_items[1];
                                $return_result['class_start_time'] = $token_items[1];
                                break;
                            
                            case 'school_start_time':
                                //$break_time = $token_items[1];
                                $return_result['class_start_time'] = $token_items[1];
                                break;

                            case 'number_of_breaks':
                                //$break_time = $token_items[1];
                                $return_result['number_of_breaks'] = $token_items[1];
                                break;
                            
                            case 'rotation_type':
                                //$break_time = $token_items[1];
                                $return_result['rotation_type'] = $token_items[1];
                                break;
                            
                            case 'from_grade':
                                //$from_grade = $token_items[1];
                                $return_result['from_grade'] = $token_items[1];
                                break;

                            case 'to_grade':
                                //$to_grade = $token_items[1];
                                $return_result['to_grade'] = $token_items[1];
                                break;

                            case 'classletters':
                                //$classletters = $token_items[1];
                                $return_result['classletters'] = $token_items[1];
                                break;

                            default:
                                break;
                        }

                    }
                }
            }

            $return_result['found'] = $settings_found;
            
            break;
        
        case 'subjects':
            $sql = "select * from schoollms_schema_userdata_timetable_subject_settings where school_id = $school_id";
            $timetable_settings = sqlQuery($sql);

            $found = FALSE;
            if (!empty($timetable_settings)){
                $found = TRUE;
                foreach ($timetable_settings as $setting_item) {
                    $id = $setting_item[0][0];
                    $item = $setting_item[0][1];
                    break;
                }
            }

            if (!$found){
                $return_result = "none";
            } else {
                $return_result = $item;
            }
            break;
        
        case 'learner':
            $sql = "select * from schoollms_schema_userdata_timetable_learner_settings where school_id = $school_id";
            $timetable_settings = sqlQuery($sql);

            $found = FALSE;
            if (!empty($timetable_settings)){
                $found = TRUE;
                foreach ($timetable_settings as $setting_item) {
                    $id = $setting_item[0][0];
                    $item = $setting_item[0][1];
                    break;
                }
            }

            if (!$found){
                $return_result = "none";
            } else {
                $return_result = $item;
            }
            break;

        case 'class':
            $sql = "select * from schoollms_schema_userdata_timetable_class_settings where school_id = $school_id";
            $timetable_settings = sqlQuery($sql);

            $found = FALSE;
            if (!empty($timetable_settings)){
                $found = TRUE;
                foreach ($timetable_settings as $setting_item) {
                    $id = $setting_item[0][0];
                    $item = $setting_item[0][1];
                    break;
                }
            }

            if (!$found){
                $return_result = "none";
            } else {
                $return_result = $item;
            }
            break;
            
        default:
            break;
    }
    
    return $return_result;
    
}

function timetable_generate_classlist($school_id){

    $settings = timetable_settings_get($school_id, 'general');
    
    $learner_settings = timetable_settings_get($school_id, 'learner');
    
    
    $classletters = $settings['classletters'];
    
    $from_grade = $settings['from_grade'];
    
    $to_grade = $settings['to_grade'];
    
    if (strcmp($learner_settings, "none") == 0){
        
    } else {
        // Following the requirements
        // --- Max Number of Learners per Class per Grade - grade_max_learners_class
        // --- Class Spread Start and Spread
        // --- (Optional) Potential Score
        // --- (Optional) Learner Average
        if (is_string($from_grade)){
            $count_grade = 0;
            $temp_grade = $from_grade;
        } else {
            $count_grade = $from_grade;
            $temp_grade = $from_grade;
        }
        
        if (strpos($classletters, "-")){
            //A-Z
            $letters = "A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z";
        } else {
            $letters = $classletters;
        }

        $classes = array ();

        $letter_tokens = explode(",", $letters);

        $class_labels = array ();
        foreach ($letter_tokens as $key => $letter) {
            while ($count_grade <= $to_grade){
                $label = "CLASS $count_grade"."$letter";
                $classes[$label] = array ();
                array_push($class_labels, $label);
                $count_grade++;
                //$temp_grade = $count_grade;
            }    
        }
        $classes_copy = $classes;
        
        $learner_settings_tokens = explode(";", $learner_settings);
        
        foreach ($learner_settings_tokens as $key => $learner_settings_items) {
            $grade_tokens = explode("<", $learner_settings_items);
            $grade_tokens_copy = $grade_tokens;
            $grade_data_tokens = explode("=", $grade_tokens[0]);
            
            $grade_id = $grade_data_tokens[1];
            
            //Get Rules for Adding to Classes
            
            $learner_settings_data_tokens = explode("|", $grade_tokens[1]);
            $learner_settings_data_tokens_copy = $learner_settings_data_tokens;
            foreach ($learner_settings_data_tokens as $index => $items) {
                if (strpos($items, "#")){
                    $item_tokens = explode("#", $items);
                    $item_tokens_copy = $item_tokens;
                    $learners_data = explode("+", $item_tokens[1]);
                    $learners_data_copy = $learners_data;
                    foreach ($learners_data as $entry => $learner_item) {
                        $learner_data_tokens = explode(":", $learner_item);
                        $learner_id_tokens = explode("=", $learner_data_tokens[0]);
                        $user_id = $learner_id_tokens[1];
                        $learner_data_tokens_copy = $learner_data_tokens;
                        $learner_base_tokens = explode(",", $learner_data_tokens[1]);
                        
                        //Using Baseline Data Decide on the Class to Add the Learner
                        
                        //Using Number of Learners Per Class Load Learner in Class
                        foreach ($class_labels as $key => $label) {
                            if (count($classes[$label]) == $number_of_learners){
                                continue;
                            } else {
                                array_push($classes[$label], $user_id);
                         
                                $q = "SELECT class_id FROM schoollms_schema_userdata_school_classes WHERE class_label = '$label'";
                                $result = sqlQuery($q);

                                $found = FALSE;
                                if (!empty($result)){
                                    $found = TRUE;
                                    foreach ($result as $setting_item) {
                                        $id = $setting_item[0][0];
                                        $class_id = $setting_item[0][1];
                                        break;
                                    }
                                }
                                
                                $learner_base_tokens_copy = $learner_base_tokens;
                                foreach ($learner_base_tokens as $key => $base_items) {
                                    $base_tokens = explode("=", $base_items);
                                    
                                    switch ($base_tokens[0]) {
                                        case 'next_grade':
                                            $base_tokens[1] = $grade_id;
                                            $base_items = implode("=", $base_tokens);
                                            
                                            break;
                                        
                                        case 'next_class':
                                            $base_tokens[1] = $class_id;
                                            $base_items = implode("=", $base_tokens);
                                            
                                            break;

                                        default:
                                            break;
                                    }
                                    
                                    $learner_base_tokens_copy[$key] = $base_items;
                                    $learner_data_tokens_copy[1] = implode(",", $learner_base_tokens_copy);
                                    $learner_item_copy = implode(":", $learner_data_tokens_copy);
                                    $learners_data_copy[$entry] = $learner_item_copy;
                                    $item_tokens_copy[1] = implode("+", $learners_data_copy);
                                    $items_copy = implode("#", $item_tokens_copy);
                                    $learner_settings_data_tokens_copy[$index] = $items_copy;
                                    $grade_tokens_copy[1] = implode("|", $learner_settings_data_tokens_copy);
                                    $learner_settings_items_copy = implode("<", $grade_tokens_copy);
                                    $settings_string = $learner_settings_items_copy;
                                    $settings_string = timetable_settings_update($school_id, $settings_string, 'learner', $grade_id);

                                    //echo "AFTER SETTINGS $settings_string <br />";

                                    timetable_settings_save($school_id, $settings_string, 'learner');
                                }
                                break;
                            }
                        }    
                    }
                    
                    //INSERT/UPDATE LEARNER CLASS DATA INTO THE LEARNER SCHOOL DETAILS TABLE
                    foreach ($classes as $label => $learners) {
                        //Check If CLASS EMPTY
                        if (empty($learners)){
                            continue;
                        }
                        
                        $q = "SELECT class_id FROM schoollms_schema_userdata_school_classes WHERE class_label = '$label'";
                        $result = sqlQuery($q);

                        $found = FALSE;
                        if (!empty($result)){
                            $found = TRUE;
                            foreach ($result as $setting_item) {
                                $id = $setting_item[0][0];
                                $class_id = $setting_item[0][1];
                                break;
                            }
                        }
                        
                        foreach ($learners as $key => $user_id) {
                            $q = "SELECT * FROM schoollms_schema_userdata_learner_schooldetails WHERE user_id = $user_id AND school_id = $school_id AND year_id = $year_id";
                            $result = sqlQuery($q);
                            if (!empty($result)){
                                $q = "UPDATE schoollms_schema_userdata_learner_schooldetails SET grade_id = $grade_id, class_id = $class_id WHERE user_id = $user_id AND school_id = $school_id AND year_id = $year_id";
                            } else {
                                $q = "INSERT INTO schoollms_schema_userdata_learner_schooldetails (user_id,school_id,grade_id,class_id,year_id) VALUES ($user_id, $school_id, $grade_id, $class_id, $year_id)";
                            }
                            $result = sqlQuery($q);
                        }
                        
                    }
                 
                } else {
                    $item_tokens = explode("=", $items);
                    
                    switch ($item_tokens[0]) {
                        case 'number_of_learners':
                            $number_of_learners = $item_tokens[1]; 
                            break;

                        case 'year_id':
                            $year_id = $item_tokens[1]; 
                            break;
                        
                        default:
                            break;
                    }
                }
            }
        }
    }     
}

function timetable_generate_timetable($school_id, $year_id, $output_type=null){
    
    //echo "School $school_id YEAR $year_id <br>";
    /* IN ORDER TO GENERATE TIMETABLE SLOTS
     * Given
     * - Timetable Days - Cycle
     * - Timetable Periods per Day
     * - Period Length
     * - Number of Breaks, Times and Length
     * With the above we can generate a timetable slot view in array
     * Foreach day -
     * --- Foreach period -
     * ------ Starting the class_start_time -- remember school_start_time and morning_class_time
     * ------ Set temp_time to class_start_time if not set
     * ------ Add period time to temp_time
     * ------ If new temp_time is less than temp break_time 
     * --------- save period_time
     * -------Otherwise add temp break_time, break_length to new period start time which is set to temp_time and get new break_time if exist
     * 
     * IN ORDER TO LOAD SUBJECTS IN THE TIMETABLE SLOTS PER GRADE PER CLASS - WITH TEACHERS AUTO LOADED AS PER SETTINGS
     * Given
     * - Timetable Slots 
     * - Subject Period Type
     * - Subject Period Times
     * - Per Grade - Notional Time, Periods/Cycle/Class
     * - For Grade 10 - 12 - If Subject is Choice - we must check minimum number of learners and entry average 
     * - ClassLists per Grade - Remember Class lists for Grade 10 - 12 are created using a random distribution of learners irrespective of subject choice
     * - 
     * FOREACH Grade
     * --- FOREACH Class
     * ------ FOREACH Day
     * ---------- FOREACH Period
     * -------------- FOREACH Subject 
     * 1----------------- Check number of teachers
     * 2----------------- Check if there EXISTS P1 in all other classes in D1 with subject
     * 3----------------- IF TRUE
     * 4------------------------- Check for P2:D1, P3:D1,...
     * 5----------------- Until there is an open SLOT
     * 6----------------- Otherwise goto D2, D3, D4,...  
     * 7----------------- Repeat 1 - 4
     * --------- FOREACH Timetable Slot
     * ------------ Find slot where Subject is not Taught in other Classlist Timetable slot and there is at least one teacher available If there is only one teacher for Subject
     * ------------ Otherwise foreach number of teachers save subject to classlist timetable
     * 
     * FOR GRADE 10 - 12 - THE SUBJECTS ARE LOADED PER LEARNER
     * - A combination of subject choices per learner group determines the keys - which enable learners with different subject choices to have one register class
     *  
     */
    
    $settings = timetable_settings_get($school_id, 'general');
    
    $learner_settings = timetable_settings_get($school_id, 'learner');
    
    $teacher_settings = timetable_settings_get($school_id, 'teacher');
    
    $class_settings = timetable_settings_get($school_id, 'class');
    
    $classletters = $settings['classletters'];
    
    $from_grade = $settings['from_grade'];
    
    $to_grade = $settings['to_grade'];
    
    //GENERATE TIMETABLE SLOTS
    $days = $settings['days'];
    
    $periods = $settings['periods'];
    
    $total_periods = $periods + 2;
    $period_time = $settings['period_time'];
    
    $count_days = 1;
    
    $timetable_slots = array ();
    
//    echo "I GET HERE <br>";
    while ($count_days <= $days){
        $timetable_slots[$count_days] = array ();
        $count_periods = 1;
        $temp_time = 0;
        while ($count_periods <= $total_periods){
            $timetable_slots[$count_days][$count_periods] = array ();
            
            if ($count_periods == 1){
                $start_time = $settings['school_start_time'];
                $end_time = strtotime("+30 minutes", strtotime($start_time));
                $end_time = date('H:i', $end_time);
                $timetable_slots[$count_days][$count_periods]['time'] = "$start_time-$end_time";
                $timetable_slots[$count_days][$count_periods]['type'] = 'registration';
                $count_periods++;
                continue;
            }
            
            if ($temp_time == 0){
                $temp_time = $settings['class_start_time'];
            }
            
            $period_times = timetable_time_calculate('period_times', $temp_time, $settings, $timetable_slots, $count_days, $count_periods);

            $break = $period_times['break_times'];
            ///echo "BREAK $break <br>";
            if ($period_times['break_times'] == 0){
                ///echo "CLASS TIME DAY $count_days PERIOD $count_periods<br>";
                $timetable_slots[$count_days][$count_periods]['time'] = $period_times['period_times'];
                $timetable_slots[$count_days][$count_periods]['type'] = 'class';
                $temp_time = $period_times['temp_time'];
            } else {
                
                $key = $period_times['break_key'];
                $timetable_slots[$count_days][$count_periods]['time'] = $period_times['period_times'];
                $timetable_slots[$count_days][$count_periods]['type'] = 'class';
         
                $count_periods++;
                ///echo "BREAK TIME DAY $count_days PERIOD $count_periods KEY $key<br>";
                $timetable_slots[$count_days]["break_time_$key"] = $period_times['break_times'];
                $timetable_slots[$count_days][$count_periods]['time'] = $period_times['break_times'];
                $timetable_slots[$count_days][$count_periods]['type'] = 'break';
                
                
                $temp_time = $period_times['temp_time'];
            }
            $count_periods++;
        }
        $count_days++;
    }
    
    //print_r($timetable_slots);
    
    //ADDING SUBJECTS TO TIMETABLE SLOTS
    $get_values = timetable_values_get('grade_from_to',  $settings);
    $count_grade = $get_values['count_grade'];
    $temp_grade = $get_values['temp_grade'];
    
    
    //GET CLASSES
    $classes = array ();
    while ($count_grade <= $to_grade){
        $classes[$count_grade] = array ();
        $q = "SELECT class_id FROM schoollms_schema_userdata_school_classes WHERE class_id IN (select distinct(class_id) from schoollms_schema_userdata_learner_schooldetails where school_id = $school_id and grade_id = $count_grade and year_id = $year_id)";
        $result = sqlQuery($q);
        if (!empty($result)){
            foreach ($result as $setting_item) {
                //print_r($setting_item);
                $class_id = $setting_item[0][0];
                //$class_id = $setting_item[0][1];
                //echo "COUNT GRADE  $count_grade ID $id CLASS ID $class_id <br >";
                array_push($classes[$count_grade], $class_id);
                //break;
            }
        }
        $count_grade++;
 
    }
    
    $timetable_slots_copy = $timetable_slots;
    
    $subjects = timetable_settings_get($school_id, 'subjects');
    
    //echo "SUBJECTS $subjects<br>";
    
    
    $subject_tokens = explode(";", $subjects);
//  
    //echo "SUBJECT TOKENS <br>";
    
    //print_r($subject_tokens);
    
    $get_values = timetable_values_get('grade_from_to',  $settings);
    $count_grade = $get_values['count_grade'];
    $temp_grade = $get_values['temp_grade'];


    $timetables = array ();
    $timetables_string = "";
    
    //GENERATE BLANK TIMETABLES
    //FOR EACH GRADE
    while ($count_grade <= $to_grade){
        $timetables[$count_grade] = array ();
        $temp_classes = $classes[$count_grade];
        //FOR EACH CLASS IN GRADE
        foreach ($temp_classes as $id => $class_id){
            $timetables[$count_grade][$class_id] = array ();
            //FOR EACH DAY FOR CLASS
            foreach($timetable_slots as $day => $period_data){
                $timetables[$count_grade][$class_id][$day] = array ();
                //FOR EACH PERIOD ON DAY
                foreach ($period_data as $period => $entries){
                    $timetables[$count_grade][$class_id][$day][$period] = array ();
                    $timetables[$count_grade][$class_id][$day][$period]['subject_id'] = 0;
                    //$timetables_string .= "index:grade_id=$count_grade,class_id=$class_id,day_id=$day,period=$period#data:subject_id=0,teacher_id=0,venue_id=0,substitute_id=0|";
                    //print_r($timetables);
                }
            }
        }
        $count_grade++;
        //$temp_grade = $count_grade;
    }
    
    //$timetables_string = substr($timetables_string, 0, strlen($timetables_string) - 1);
    
    $timetables_captured = $timetables;

    //LOAD NEW TIMETABLES
    $get_values = timetable_values_get('grade_from_to',  $settings);
    $count_grade = $get_values['count_grade'];
    $temp_grade = $get_values['temp_grade'];
    //FOR EACH GRADE
    while ($count_grade <= $to_grade){
        //$timetables[$count_grade] = array ();
     
        $temp_classes = $classes[$count_grade];
        
        //FOR EACH CLASS IN GRADE
        foreach ($temp_classes as $id => $class_id){
            //GET SUBJECTS FROM TIMETABLE SUBJECT SETTINGS
            $subject_tokens_copy = $subject_tokens;
            /* subject_id=1<subject_color=FFFFFF|period_type=random|period_times=even|grade_setting#grade_id=12:grade_subject_color=FFFFFF,notional_time=27.5,period_cycle=10,subject_type=Optional,minimum_learners=50+grade_id=9:grade_subject_color=FFFFFF,notional_time=27.5,period_cycle=10,subject_type=Optional,minimum_learners=50;
             * subject_id=2<subject_color=FFFFFF|period_type=random|period_times=even|grade_setting#grade_id=12:grade_subject_color=FFFFFF,notional_time=27.5,period_cycle=10,subject_type=Optional,minimum_learners=50;
             * subject_id=3<subject_color=FFFFFF|period_type=random|period_times=even|grade_setting#grade_id=12:grade_subject_color=FFFFFF,notional_time=27.5,period_cycle=10,subject_type=Optional,minimum_learners=50
             */
            //echo "SUBJECT TOKENS $subject_tokens <br>";

            foreach ($subject_tokens_copy as $key => $subject_data) {
                //echo "SUBJECT DATA $subject_data <br>";

                $subject_data_tokens = explode("<", $subject_data);

                $subject_details = timetable_values_get('subject_details',  $subject_data_tokens[1]);


                $grade_details_tokens = $subject_details["grade_setting"];

                //echo "GRADE SETTINGS $grade_details_tokens <br>";

                $grades_data = explode("+", $grade_details_tokens);
                $grade_found = FALSE;
                foreach ($grades_data as $entry => $grade_settings) {

                    $grade_settings_data = explode(":", $grade_settings);

                    $grade_id_tokens = explode("=", $grade_settings_data[0]);

                    //GET SUBJECT TYPE
                    $grade_subject_settings = explode(",", $grade_settings_data[1]);

                    //echo "if ($temp_grade == $grade_id_tokens[1]) <br>";
                    if ($temp_grade == $grade_id_tokens[1]){
//
                       // echo "TRUE <br>";

                        //print_r($grade_subject_settings);

                        $grade_subject_settings = timetable_values_get('grade_subject_settings',  $grade_subject_settings);

                        $subject_type = $grade_subject_settings["subject_type"];

                        $non_core = FALSE;
                        //echo "if ( $subject_type == 'Core') <br>";
                        //FOR EACH DAY FOR CLASS
                        foreach($timetable_slots as $day => $period_data){
                            //$timetables[$count_grade][$class_id][$day] = array ();

                            //FOR EACH PERIOD ON DAY
                            foreach ($period_data as $period => $entries){

                                //IF period_type IS NOT Break
//                                if ($period == 1){
//                                    continue;
//                                }
                                
                                //$entry_type = $entries['type'];
                                //echo "ENTRY $entry_type DAY $day PERIOD $period <br>";
                                if (strcmp($entries['type'],'break') == 0 || strcmp($entries['type'],'registration') == 0){
                                    continue;
                                }
                                
                         
                                $subject_id_tokens = explode("=", $subject_data_tokens[0]);
                                $subject_id = $subject_id_tokens[1];
                                //echo "SLOT ENTRY TYPE $entry_type SUBJECT $subject_id GRADE $temp_grade DAY $day PERIOD $period<br>";
                                
                                if ($subject_type == "Core"){
                                    $grade_found =TRUE;
                                    //CHECK IF SUBJECT Core OR Optional
                                    //$subject_id_tokens = explode("=", $subject_data_tokens[0]);
                                    //$subject_id = $subject_id_tokens[1];

                                    //GET NUMBER OF TEACHERS THAT TEACH subject_id IN THIS  grade_id
                                    //$get_values = timetable_values_get('number_of_teachers',  $teacher_settings, $temp_grade);

                                    //echo "timetable_find_slot($timetables, $temp_grade, $class_id, $day, $period,  $subject_id, $timetables_string)<br>";
                                    //CHECK IF subject_id IS ASSIGNED TO class_id FOR current day ON current period 
                                    $timetables = timetable_find_slot($timetables, $temp_grade, $class_id, $day, $period,  $subject_id); 
                                    //$timetables_captured = $timetables;

                                    if ($timetables[-1]){
                                        break;
                                    }
                                } else {
                                    $non_core = TRUE;
                                    break;
                                }
                            }
                            if ($non_core ){
                                break;
                            }
                            
                            if ($timetables[-1]){
                                //$timetables[-1] = FALSE;
                                break;
                            }
                        }
                        //break;
//                                        //$grade_found = TRUE
                    }
                    
                    if ($timetables[-1]){
                        $timetables[-1] = FALSE;
                        break;
                    }
                    
                    if ($non_core ){
                        break;
                    }
                }
                    //}
                //}
            }
                //}
            //}
        }
        $count_grade++;
        $temp_grade = $count_grade;
    }
    
    //Store/Display Time Tables
    if (is_null($output_type)){
        
    } else {
        switch ($output_type){
            
            case 'display':
                return timetable_display($school_id, $year_id, $timetables);
                break;
            
            case 'store':
                $timetables_copy = $timetables;
                foreach ($timetables as $grade_id => $class_data){
                    foreach ($class_data as $class_id => $slot_data){
                        //Get Timetable ID
                        $q = "SELECT timetabl_id FROM schoollms_schema_userdata_school_timetable WHERE timetable_type_item_id = $class_id AND school_id = $school_id";
                        
                        $result = sqlQuery($q);
                        if (!empty($result)){
                            foreach ($result as $setting_item) {
                                //print_r($setting_item);
                                $timetable_id = $setting_item[0][0];
                                break;
                            }
                            
                            $q = "SELECT * FROM schoollms_schema_userdata_school_timetable_items WHERE timetabl_id = $timetable_id";
                            $result = sqlQuery($q);
                            if (!empty($result)){
                                timetable_store('update', $timetable_id, $class_id, $slot_data);
                            } else {
                                timetable_store('insert', $timetable_id, $class_id, $slot_data);
                            }
                        }
                    }
                }
                break;
            
            default:
                break;
        }
    }
}

function timetable_display($school_id, $year_id, $timetables){
    
    //Show Timetables
    $display = "";
    foreach ($timetables as $grade_id => $class_data){
        $display .= "Timetables for Grade $grade_id <br>";
        foreach ($class_data as $class_id => $slot_data){
            $q = "SELECT class_label FROM schoollms_schema_userdata_school_classes WHERE school_id = $school_id AND class_id = $class_id";
            $result = sqlQuery($q);
            foreach ($result as $setting_item) {
                //print_r($setting_item);
                $classlabel = $setting_item[0][0];
                break;
            }
            $display .= "$classlabel TIMETABLE<br>";
            
            $display .= "<table>";
            foreach ($slot_data as $day => $period_data) {
                $display .= "<tr><th> Day $day </th>";
           
                foreach ($period_data as $period => $slot_details) {
                    $subject_id = $slot_details['subject_id'];
                    $q = "SELECT subject_title FROM schoollms_schema_userdata_school_subjects WHERE subject_id = $subject_id";
                    $result = sqlQuery($q);
                    foreach ($result as $setting_item) {
                        //print_r($setting_item);
                        $subject = $setting_item[0][0];
                        break;
                    }
                    $display .= "<th> Period $period </th><td> $subject </td> ";
                    
                }
                $display .= "</tr>";
            }
            $display .= "</table><br>";

        }
        $display .= "</br>";
    }
    
    return $display;
}

function timetable_store($type, $timetable_id, $class_id, $slot_data){
    
    foreach ($slot_data as $day => $period_data) {
        foreach ($period_data as $period => $slot_details) {
            $subject_id = $slot_details['subject_id'];
            switch($type){
                case 'update':
                    $q = "UPDATE schoollms_schema_userdata_school_timetable_items SET subject_id = $subject_id WHERE timetabl_id = $timetable_id AND day_id = $day AND period_id = $period";
                    break;
                
                case 'insert':
                    $room_id = 0;
                    //$subject_id, 
                    $teacher_id = 0;
                    $substitute_id = 0;
                    $q = "INSERT INTO schoollms_schema_userdata_school_timetable_items (timetable_id, day_id, period_id, room_id, subject_id, teacher_id, substitute_id) VALUES ($timetable_id, $day, $period, $room_id, $subject_id, $teacher_id, $substitute_id)";
                    break;
                
                default:
                    break;
                
            }
            $result = sqlQuery($q);
        }
    }
}

function timetable_find_slot($timetables, $temp_grade, $class_id, $day, $period,  $subject_id){
    $timetables_copy = $timetables;
    
    if (strpos($period, "_")){
        $timetables[-1] = TRUE;
        return $timetables;
    }
    
    foreach ($timetables_copy[$temp_grade] as $temp_class_id => $temp_slots){
        //echo "FIRST CLASS $temp_class_id FOR GRADE $temp_grade WORKING WITH CLASS  $class_id DAY $day PERIOD $period  <br>";
        if ($class_id == $temp_class_id){
            continue;
        } else if ($temp_slots[$day][$period]['subject_id'] == $subject_id){
            $period_available = FALSE;
            foreach ($temp_slots[$day] as $temp_period => $period_data){
                //echo "FIRST PERIOD $temp_period  <br>";
                if ($temp_period == 1){
                    continue;
                }
                
                if ($temp_period == $period){
                    continue;
                }
                $timetables_copy2 = $timetables;
                //$timetables_string_tokens = explode("|", $timetables_string);
                //foreach ($timetables_string_tokens as $key => $string_data) {
                       
                    foreach ($timetables_copy2[$temp_grade] as $temp_class_id => $temp_slots){
                        //echo "SECOND IN FIRST CLASS $temp_class_id FOR GRADE $temp_grade WORKING WITH CLASS  $class_id DAY $day PERIOD $period  <br>";
                        if ($class_id == $temp_class_id){
                            continue;
                        } else if ($temp_slots[$day][$temp_period]['subject_id'] == $subject_id){
                            break;
                            //$string_data_tokens = timetable_values_get("timetable_string_data_tokens", $string_data);
                           
                        } else {
                            //echo "PERIOD FOUND $temp_period";
                            $period_available = TRUE;
                            $period = $temp_period;
                        }
                    }
                    if ($period_available){
                        if ($timetables[$temp_grade][$class_id][$day][$period]['subject_id'] == 0){
                            break;
                        } else {
                            $period_available = FALSE;
                        }
                    }
                    
                //}
                //$timetables = timetable_find_slot($timetables, $temp_grade, $class_id, $day, $temp_period,  $subject_id);
            }
        } else {
            if ($timetables[$temp_grade][$class_id][$day][$period]['subject_id'] == 0){
                $period_available = TRUE;
                break;
            } else {
                $period_available = FALSE;
            }
            
        }
    }
    
    if ($period_available){
        
        echo "AFTER PERIOD LOOP GRADE $temp_grade CLASS $class_id HAS  SUBJECT $subject_id ASSIGNED PERIOD $period DAY $day <br>";
        $timetables[$temp_grade][$class_id][$day][$period]['subject_id'] = $subject_id;
        $timetables[-1] = TRUE;
    } else {
        $timetables_copy = $timetables;
        foreach ($timetables_copy[$temp_grade][$class_id] as $temp_day => $temp_slots){
            //echo "SECOND DAY $temp_day FOR GRADE $temp_grade WORKING WITH CLASS  $class_id  PERIOD $period  <br>";
            if ($temp_day == $day){
                continue;
            }
            
            $timetables_copy2 = $timetables;
            //$timetables_string_tokens = explode("|", $timetables_string);
            
            foreach ($timetables_copy2[$temp_grade] as $temp_class_id => $temp_slots){
                if ($class_id == $temp_class_id){
                    continue;
                } else if ($temp_slots[$temp_day][$period]['subject_id'] == $subject_id){
                    $period_available = FALSE;
                    foreach ($temp_slots[$temp_day] as $temp_period => $period_data) {
                        
                        if (strpos($temp_period, "_")){
                            $timetables[-1] = TRUE;
                            return $timetables;
                        }
                        
                        if ($temp_period == 1){
                            continue;
                        }
                        
                        if ($temp_period == $period) {
                            continue;
                        }
                        $timetables_copy2 = $timetables;
                        //$timetables_string_tokens = explode("|", $timetables_string);
                        //foreach ($timetables_string_tokens as $key => $string_data) {



                        foreach ($timetables_copy2[$temp_grade] as $temp_class_id => $temp_slots) {
                            if ($class_id == $temp_class_id) {
                                continue;
                            } else if ($temp_slots[$temp_day][$temp_period]['subject_id'] == $subject_id) {
                                break;
                                //$string_data_tokens = timetable_values_get("timetable_string_data_tokens", $string_data);
                            } else {
                                $period_available = TRUE;
                                $period = $temp_period;
                            }
                        }
                        if ($period_available) {
                            if ($timetables[$temp_grade][$class_id][$day][$period]['subject_id'] == 0){
                                break;
                            } else {
                                $period_available = FALSE;
                            }
                        }

                        //}
                        //$timetables = timetable_find_slot($timetables, $temp_grade, $class_id, $day, $temp_period,  $subject_id);
                    }
                } else {
                    if ($timetables[$temp_grade][$class_id][$day][$period]['subject_id'] == 0){
                        $period_available = TRUE;
                        $period = $temp_period;
                        $day = $temp_day;
                    } else {
                        $period_available = FALSE;
                    }
                }
            }
            if ($period_available){
                break;
            }
            //$timetables = timetable_find_slot($timetables, $temp_grade, $class_id, $temp_day, $period,  $subject_id);
        }
        
        if ($period_available){
            echo "AFTER DAY/PERIOD LOOP GRADE $temp_grade CLASS $class_id HAS  SUBJECT $subject_id ASSIGNED PERIOD $period DAY $day <br>";
            $timetables[$temp_grade][$class_id][$day][$period]['subject_id'] = $subject_id;
            $timetables[-1] = TRUE;
        }
    }
    
    if (!$period_available){
       
        if ($timetables[$temp_grade][$class_id][$day][$period]['subject_id'] == 0){
            echo "AFTER BOTH LOOPS GRADE $temp_grade NO CLASS HAS SUBJECT THUS CLASS $class_id HAS SUBJECT $subject_id ASSIGNED PERIOD $period DAY $day <br>";
            //$timetables[$temp_grade][$class_id][$day][$period]['subject_id'] = $subject_id;
            $timetables[-1] = TRUE;
        } else {
            $timetables[-1] = FALSE;
        }
        
    }
    
    return $timetables;
}

function AddUpdateTeacherGradeSubject($teacher_id, $grade_id,$subject_id,$school_id,$year_id)
{
	global $data;
	global $data1;
	//echo "$data->db";
	
	$thegrad_id = 0;
	$sql = "select id from timetable.grade where grade = concat('GR','$grade_id')";
	$data->execSQL($sql);
	if($row = $data->getRow())
	{
		$thegrad_id = $row->id;
	}
	else{
		$sql = "insert into timetable.grade (grade) values (concat('GR','$grade_id'))";
		$data->execNonSql($sql);
		$thegrad_id = $data->insertid;
	}
	//echo "$sql <br/>";
	
	$theteacher_id = 0;
	$sql = "select t.id from  timetable.teacher t 
		join schoollms_schema_userdata_access_profile sp on sp.surname = t.surname
		where type_id = 4 and user_id = $teacher_id and t.school_id = $school_id";
	$data->execSQL($sql);
	if($row = $data->getRow())
	{
		$theteacher_id = $row->id;
	}
	else{
		$sql = "insert into timetable.teacher (name,surname,full_name,school_id) select name, surname, concat(surname,' ',name) ,$school_id
		from schoollms_schema_userdata_access_profile
		where user_id = $teacher_id";
		$data->execNonSql($sql);
		$theteacher_id = $data->insertid;
	}
	//echo "$sql <br/>";
	
	$thesubject_id = 0;
	$sql = "select * from timetable.subjects s
		join schoollms_schema_userdata_school_subjects ss on ss.subject_title = subject_fullname
		where ss.subject_id = $subject_id";
	//echo "$sql <br/>";
	$data->execSQL($sql);
	if($row = $data->getRow())
	{
		$thesubject_id = $row->id;
	}
	else{
		$sql = "insert into timetable.subjects (subject_name, subject_fullname,year_id)  
		select subject_title,subject_title, $year_id 
		from  schoollms_schema_userdata_school_subjects where subject_id = $subject_id";
		$data->execNonSql($sql);
		$thesubject_id = $data->insertid;
	}
	//echo "$sql <br/>";
	
	$sql = "select * 
		from timetable.teachersubject
		where subjectid = $thesubject_id
		and gradeid = $thegrad_id
		and teacherid = $theteacher_id";
	$data->execSQL($sql);
	if($row = $data->getRow())
	{
		;
	}
	else{
		$sql = "insert into timetable.teachersubject(subjectid,gradeid,teacherid) 
		values ('$thesubject_id','$thegrad_id','$theteacher_id')";
		$data->execNonSql($sql);
	}
	//echo $sql;
	
}

function timetable_new_id($table, $key){
    
    $q = "SELECT $key FROM $table";
    
    $result = sqlQuery($q);
    $index = 0;
    foreach ($result as $key => $row) {
        $tem_index = $row[0];
        if ($tem_index[0] > $index){
            $index = $tem_index[0];
        }
    }
    $index++;
    return $index;
}

function timetable_save($save_type, $save_string){
    
    switch($save_type){
        case 'admin_timetable_slot':
            
            break;
        
        case 'new_user':
            $save_tokens = explode("#", $save_string);
            $id = $save_tokens[0];
            $subject_tokens = explode("*", $save_tokens[1]);
            $school_id = $save_tokens[2];
            $user_type = $save_tokens[3];
            $user_id = $save_tokens[4];
            $string = $save_tokens[5];
            if ($user_id !== 0){
                $q = "SELECT * FROM schoollms_schema_userdata_access_profile WHERE user_id = '$user_id'";
            } else { 
                $q = "SELECT * FROM schoollms_schema_userdata_access_profile WHERE access_id = '$id' AND school_id $school_id AND type_id = $user_type";
       	   //echo "<br> $q <br>";
            }
            
            $result = sqlQuery($q);
	    //var_dump( $result);
            //echo "<br> AFTER SELECT<br>";
            //$user_id = 0;
            if (!empty($result) and trim($id) !== "" || $user_id !== 0){
                //echo "UPDATE <br>";
                $string_tokens = explode(",", $string);
                $fields = "school_id, access_id, type_id, name, surname";
                $field_tokens = explode(",", $fields);
                $update = "";
                foreach ($string_tokens as $key => $value) {
                    $field = $field_tokens[$key];
                    $update .="$field='$value',";
                }
                $update = trim($update, ",");
                
                if ($user_id !== 0){
                    $q = "UPDATE schoollms_schema_userdata_access_profile SET $update WHERE user_id = '$user_id'";
                    
                    $result = sqlQuery($q);
                } else {
                    $q = "UPDATE schoollms_schema_userdata_access_profile SET $update WHERE access_id = '$id' AND school_id $school_id AND type_id = $user_type";
                    //echo "<br> UPDATE $q <br>";
                    $result = sqlQuery($q);
                    
                    $q = "SELECT user_id FROM schoollms_schema_userdata_access_profile WHERE access_id = '$id'";

                    $result = sqlQuery($q);

                    foreach ($result as $value) {
                        $user_id = $value[0][0];
                        break;
                    }
                }
                
            } else {
                //echo "INSERT $string<br>";
                $user_id = timetable_new_id('schoollms_schema_userdata_access_profile', 'user_id');
                $q = "INSERT INTO schoollms_schema_userdata_access_profile (user_id,school_id, access_id, type_id, name, surname) VALUES ($user_id,$string)";
            
                //echo "<br> INSERT $q <br>";
                $result = sqlQuery($q);
            }          
            
            switch ($user_type) {
                case 2:


                    break;
                
                case 4:
                    foreach ($subject_tokens as $key => $subject_data) {
                        $subject_items = explode("!", $subject_data);
                        $year_id = $subject_items[1];
                        $subject_id = $subject_items[2];
                        $grade_id = $subject_items[3];

                        $q = "SELECT * FROM schoollms_schema_userdata_teacher_schooldetails WHERE user_id = $user_id AND school_id = $school_id AND subject_id = $subject_id AND grade_id = $grade_id AND year_id = $year_id";
                        
                        //echo "<br> SUBJECT $q <br>";
                        
                         $result = sqlQuery($q);
                         
                         if (!empty($result)){

                        } else {
                            $q = "INSERT INTO schoollms_schema_userdata_teacher_schooldetails VALUES ($user_id, $school_id, $grade_id, 0, $subject_id, $year_id)";
                            $result = sqlQuery($q);
                        }
                    }
                    break;

                default:
                    break;
            }
            
            //echo "SQL $q RESULT $result<br>";
            break;
        
            
        case 'save_learner_parent':
            $string_tokens = explode("#", $save_string);
            $user_id = $string_tokens[0];
            $parent_user_ids = $string_tokens[1];
            
            $parent_user_id_tokens = explode(",", $parent_user_ids);
            
            $count = count($parent_user_id_tokens);
            
            if ($count == 1){
                $parent_1_user_id = $parent_user_ids;
                $q = "SELECT * FROM schoollms_schema_userdata_learner_parent WHERE user_id = $user_id AND parent LIKE '%parent_1:$parent_1_user_id'";
                $result = sqlQuery($q);
                if (!empty($result)){

                } else {
                    $q = "INSERT INTO schoollms_schema_userdata_learner_parent VALUES ($user_id,'parent_1:$parent_1_user_id,parent_2:0')";
                    $result = sqlQuery($q);
                }
            } else {
                $parent_1_user_id = $parent_user_id_tokens[0];
                $parent_2_user_id = $parent_user_id_tokens[1];
                
                $q = "SELECT * FROM schoollms_schema_userdata_learner_parent WHERE user_id = $user_id AND parent LIKE '%parent_1:$parent_1_user_id'";
                $result = sqlQuery($q);
                if (!empty($result)){

                } else {
                    $q = "INSERT INTO schoollms_schema_userdata_learner_parent VALUES ($user_id,'parent_1:$parent_1_user_id,parent_2:$parent_2_user_id')";
                    $result = sqlQuery($q);

                }
            }
            
            break;
            
    }
    
    if ($save_type == 'new_user'){
        return $user_id;
    }
}
?>
