<?php 
global $data;
//include("db.inc");
//include("util.php");
//var_dump($data);

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

function prepareTrackerView($user_id, $time_table_id,$user_type, $year_id){
    //echo "Time table ID : $time_table_id <br/>";
    global $timetable_id;
    
    $timetable_id = -1;
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
        
        
	
    $result = sqlQuery("delete from schoollms_schema_userdata_school_timetable_view where timetabl_id = $timetable_id");
    //echo "TIMETABLE $timetable_id \n";
    //IF FALSE Get User Time Table According to Today
    $today = date('D:d M:m Y H:i:s');
    
    $this_day = date('Y-m-d');
    //Get TimeDays From Today
    $days = days($today);
    
    //REMOVE PREVIOUS TIMETABLE LOAD IF FIRST DATE IS DIFFERENT TO TODAY
    
    //FOR EACH DAY LOAD USER TIMETABLE
    $row = 1;
    //echo "<hr/>";
	//var_dump($days);
	//echo "<hr/>";
    foreach ($days as $day) {
        
        $timetable_day = $day['timetable_day'];
        $timetable_day_date = $day['timetable_day_date'];
        
        $tokens = explode(" ", $timetable_day_date);
        
        $year = $tokens[2];
        $month_items = explode(":", $tokens[1]);
        $day_items = explode(":", $tokens[0]);
        $clock_items = explode(":", $tokens[3]);
        
        $date_stamp = "$day_items[1]-$month_items[1]-$year";
        $lesson_date = "$year-$month_items[1]-$day_items[1]";
        //GET DAY ITEMS
        //switch ($user_type){
        
            //case 2:
        
        $sql = "select * from schoollms_schema_userdata_school_timetable_period_labels order by period_label_id asc";
        
        
        $timetable_items = sqlQuery($sql);

        $col = 1;
        $item_count = count($timetable_items);
        //echo "TTITEMS = $item_count SQL $sql <br> date_stamp : $date_stamp";
        
        $slot_details = "";
        //echo "<hr/> num : ".count($timetable_items);
        $timetable_items = sqlQuery($sql);
        
        $col = 1;
        $item_count = count($timetable_items);
        //echo "TTITEMS = $item_count SQL $sql <br> date_stamp : $date_stamp";
        
        $slot_details = "";
        //echo "<hr/> num : ".count($timetable_items);
        foreach ($timetable_items as $items) {
			
            foreach ($items as  $item){

                $period_label_id = $item[0];
                
                $event = FALSE;
                //echo "I GET HERE<br>";
                if ($item[3] == 'Registration'){
                    //CHECK IF THERE IS ANY EVENT FOR REGISTRATION ON THIS DAY
//                    $q = "SELECT * FROM schoollms_schema_userdata_events WHERE event_register = 'Y' AND event_date like '%$date_stamp%'";
//                    $result = sqlQuery($q);
//                    //echo count($result);
//                    if (count($result) > 0){
//                        
//                        $event = TRUE;
//                        $slot_details = "<table>";
//                        foreach ($result as $key => $value) {
//                            
//                            $eid = $value[0][0];
//                            $ehost = $value[0][3];
//                            $etitle = $value[0][4];
//                            $slot_details .= "<tr><td><span style='color: black; font-size: 8pt'><b>Event:</b></span><br><span style='color: black; font-size: 10pt'>$etitle  </span><br><span style='color: black; font-size: 8pt'><b>Host:</b></span><br><button type='submit' style='background-color:transparent; border-color:transparent;' id='btnRegister' name='btnRegister' onClick='eventRegister($eid)'><img src='./images/register_3.jpeg'></button></td></tr>";
//                        
//                            break;
//                        }
//                        $slot_details .= "</table>";
//                        //echo $slot_details;
//                    } else {
//                        continue;
//                    }
                    continue;
                }
                
                if ($item[3] == 'Break'){
                    
                    continue;
                }
                
                $subject_id = 14;
                if (!$event){
                    $sql = "SELECT * FROM user_action_log WHERE day_id = $timetable_day AND period_label_id = $period_label_id";

                    $timetable_logs = sqlQuery($sql);

                    $num_teachers_accessed = 0;
                    $num_teachers_published = 0;
                    $num_learners_accessed = 0;

                    //echo "USER LOG $sql <br>";

                    foreach ($timetable_logs as $key => $log) {

                        /*
                         * user_action_log_id | bigint(20)   | NO   | PRI | NULL    | auto_increment |
    | action_type_id     | int(11)      | YES  |     | NULL    |                |
    | action_time        | datetime     | YES  |     | NULL    |                |
    | user_id            | int(11)      | YES  |     | NULL    |                |
    | day_id             | int(11)      | YES  |     | NULL    |                |
    | period_label_id    | int(11)      | YES  |     | NULL    |                |
    | class_id           | int(11)      | YES  |     | NULL    |                |
    | subject_id         | int(11)      | YES  |     | NULL    |                |
    | lesson_date   
                         */
                        $action_type = $log[0][1];
                        $action_time = $log[0][2];
                        $user_id = $log[0][3];
                        $day_id = $log[0][4];
                        $period_l_id = $log[0][5];
                        $class_id = $log[0][6];
                        $subject_id = $log[0][7];
                        $lesson_date = $log[0][8];


                        if (empty($user_id) || !isset($user_id)){
                            //echo "USERID $user_id<br>";
                            continue;
                        }

                        //echo "GOT TYPE $action_type TIME $action_time UID $user_id DAY $day_id PERIOD $period_l_id CLASS $class_id SUB $subject_id <br>";
                        //GET USER SCHOOL ID
                        $q = "SELECT * FROM schoollms_schema_userdata_access_profile WHERE user_id = $user_id";

                        $user_school_data = sqlQuery($q);

                        $school_id = 0;
                        foreach($user_school_data as $key2 => $user_school_items){

                            $school_id = $user_school_items[1];
                            $user_type = $user_school_items[1];
                            break;
                        }

                        $not_found = TRUE;
                        if ($school_id !== 0 && $school_id !== '' && !empty($school_id)){
                            $q = "SELECT * FROM schoollms_schema_userdata_school_timetable_period WHERE school_id = $school_id AND week_day_id = $day_id AND period_label_id = $period_l_id";

                            $period_data = sqlQuery($q);

                            foreach ($period_data as $key2 => $period_items) {

                                /*
                                 * period_id       | int(11)      | NO   | PRI | NULL              | auto_increment              |
                                | school_id       | int(11)      | YES  |     | NULL              |                             |
                                | week_day_id     | int(11)      | YES  |     | NULL              |                             |
                                | period_start    | varchar(255) | YES  |     | NULL              |                             |
                                | period_end      | varchar(255) | YES  |     | NULL              |                             |
                                | period_label_id | int(11)      | YES  |     | NULL              |                           
                                 */
                                $action_time_tokens = explode(" ", $action_time);
                                $act_time = $action_time_tokens[1];
                                $period_start = $period_items[4];
                                $period_end = $period_items[5];

                                //echo "ACT TIME $act_time PS $period_start PE $period_end <br>";
                                //IF TIME BETWEEN
                                $date1 = strtotime("$action_time");
                                $date2 = strtotime("$lesson_date $period_start");
                                $date3 = strtotime("$lesson_date $period_end");
                                if ($date1 > $date2 && $date1 < $date3){


                                    switch ($action_type){

                                        case 1://Teacher Publish Lesson
                                            //$num_teachers_accessed = 0;
                                            $num_teachers_published++;
                                            //$num_learners_accessed = 0;
                                            $not_found = FALSE;
                                            break;

                                        case 2://Teacher Access Lesson
                                            $num_teachers_accessed++;
                                            //$num_teachers_published++;
                                            //$num_learners_accessed = 0;
                                            $not_found = FALSE;
                                            break;

                                        case 3://Learner Access Lesson
                                            //$num_teachers_accessed = 0;
                                            //$num_teachers_published++;
                                            $num_learners_accessed++;
                                            $not_found = FALSE;    
                                            break;

                                        default:

                                            break;
                                    }
                                }
                            }
                        }
                    }

                    if ($not_found){
                        $subject_id = 6;
                        $slot_details = "<b> 0% </b> STATS ";
                    } else {
                        $subject_id = 9;
                        $slot_details = "<table><tr><td><b>#Published:</b> $num_teachers_published</td></tr>"
                            . "<tr><td><b>#Taught:</b> $num_teachers_accessed </td></tr>"
                            . "<tr><td><b>#Learned:</b> $num_learners_accessed</td></tr></table>";
                    }
                }
                //echo "SLOT $slot_details <br />";
                //$slot_details = mysql_real_escape_string($slot_details);
                
                //echo "TT $timetable_id,D $timetable_day,P $period_id,R $room_id,S $subject_id,T $teacher_id,SUB $substitute_id <br>";
				//echo "$slot_details <br/>";
                //$slot_details = sqlEscapeString($slot_details);
		//echo "AFTER ESCAPE $slot_details <br/>";

                //$pos = "$row"."_$period_id"
                $sql = "insert into schoollms_schema_userdata_school_timetable_view (timetabl_id, day_date, tbl_row, tbl_col, slot_code, slot_details) values ($timetable_id,'$date_stamp','$row','$period_label_id','s-$subject_id','$slot_details')";
                //echo "SQL $sql <br />";
                $result = sqlQuery($sql);
                
                //echo "RESULT $result <br>";
            }
        }
        
        $row++;
    }
	
    //echo "return $timetable_id<br/>";
    return $timetable_id;
}

//Prepare School Time Table View for user;
function prepareTableView($user_id, $time_table_id,$user_type, $year_id){
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
            
            break;
        }
    } else {
        switch ($user_type){
        
            case 2:
                $timetable_id = $time_table_id;
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
    $days = days($today);
    
    //REMOVE PREVIOUS TIMETABLE LOAD IF FIRST DATE IS DIFFERENT TO TODAY
    
    //FOR EACH DAY LOAD USER TIMETABLE
    $row = 1;
    //echo "<hr/>";
	//var_dump($days);
	//echo "<hr/>";
    foreach ($days as $day) {
        
        $timetable_day = $day['timetable_day'];
        $timetable_day_date = $day['timetable_day_date'];
        
        $tokens = explode(" ", $timetable_day_date);
        
        $year = $tokens[2];
        $month_items = explode(":", $tokens[1]);
        $day_items = explode(":", $tokens[0]);
        $clock_items = explode(":", $tokens[3]);
        
        $date_stamp = "$day_items[1]-$month_items[1]-$year";
        //GET DAY ITEMS
        //switch ($user_type){
        
            //case 2:
        $sql = "select distinct timetabl_id, day_id, period_label_id, grade_id, class_id, 
room_id, subject_id, teacher_id, substitude_id from schoollms_schema_userdata_school_timetable_items where timetabl_id = $timetable_id and day_id =  $timetable_day order by period_label_id asc";
              //  break;
            
            //case 4:
            //    $sql = "select * from schoollms_schema_userdata_school_timetable_items where teacher_id = $user_id and day_id =  $timetable_day order by period_label_id asc";
          //      break;
            
        //}
        
        $timetable_items = sqlQuery($sql);
        
        $col = 1;
        $item_count = count($timetable_items);
        //echo "TTITEMS = $item_count SQL $sql <br> date_stamp : $date_stamp";
        
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
                //echo "SQL $sql <br />";
                $result = sqlQuery($sql);
                
                //echo "RESULT $result <br>";
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

function build_learner_timetable($user_id, $year_id){
    
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

function get_today($startdate, $today,$school_id=null){
    
    if (!is_null($startdate)){
        $today_tokens = explode(" ", $today);
        $day_tokens =  explode(":", $today_tokens[0]);
        $month_tokens =  explode(":", $today_tokens[1]);
        $temp_today = date_create("$day_tokens[1]-$month_tokens[1]-$today_tokens[2]");
        //Get School Days
        if (!is_null($school_id)){
            $timetable_days = sqlQuery("select * from schoollms_schema_userdata_school_timetable_days where school_id = $school_id");
        } else {
            $timetable_days = sqlQuery("select * from schoollms_schema_userdata_school_timetable_days");
        }

        $num_timetable_days = count($timetable_days);

        //echo "NUMDAYS $num_timetable_days <br>";
        
        if ($startdate < 0){
            //Subtract Days
            date_sub($temp_today, date_interval_create_from_date_string("$num_timetable_days days"));
        } else {
            //Add Days
            date_add($temp_today,date_interval_create_from_date_string("$num_timetable_days days"));
        }
        
        $temp_today = date_format($temp_today, 'D:d-M:m-Y');
        $temp_today_tokens = explode("-", $temp_today);
        
        $today = "$temp_today_tokens[0] $temp_today_tokens[1] $temp_today_tokens[2] $today_tokens[2]";
        
    }
    
    return $today;
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

function periods ($school_id, $startdate=null,$today=null){
    
   
    //$periods = sqlQuery('select *  from schoollms_schema_userdata_school_timetable_period order by period_id');
    //echo "SCHOOL_ID $school_id";
    $sql = "select l.* 
		from schoollms_schema_userdata_school_timetable_period_labels l
		join schoollms_schema_userdata_school_timetable_period p on p.period_label_id = l.period_label_id and l.school_id = p.school_id and l.year_id = p.year_id
		where l.school_id = $school_id AND l.year_id =  4
		and week_day_id = 1
		order by UNIX_TIMESTAMP(concat('2016-01-01',' ',period_start))  asc";
    //$period_labels = sqlQuery("select * from schoollms_schema_userdata_school_timetable_period_labels where school_id = $school_id order by period_label_id ASC");
	$period_labels = sqlQuery($sql);
    
    //var_dump($period_labels);
   
     
    foreach ($period_labels as $period_label) {
    
        $id = $period_label[0][0];
        //$start = $period[0][3];
        //$label_id = $period[0][5];
        $label = $period_label[0][3];//sqlQuery("select period_label from schoollms_schema_userdata_school_timetable_period_labels where period_label_id = $label_id");
//        foreach ($period_label as $period_label_data) {
//            $label = $period_label_data[0][0];
//            break;
//        }
//        $end = $period[0][4];
        
        print "<td class=\"redips-mark dark\"><div class='tableHeader'><b>$label </b></div></td>";
        //print "<td class=\"redips-mark dark\"><b>$label </b><br> $start-$end </td>";
    }
    
//    exit;
    
    
    
}

function days($today, $school_id=null){
    
    //Get Today Items
    $today_items = explode(" ", $today);
    //echo "today Items : $today_items";
    $year = $today_items[2];
    $month_items = explode(":", $today_items[1]);
    $day_items = explode(":", $today_items[0]);
    $clock_items = explode(":", $today_items[3]);
    
    //Get Year Start Date
    $year_start_date = sqlQuery("select start_time from schoollms_schema_userdata_school_year_calendar where calendar_type = 1 and year_id in (select year_id from schoollms_schema_userdata_school_year where year_label = '$year')");
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
    if (!is_null($school_id)){
        $timetable_days = sqlQuery("select * from schoollms_schema_userdata_school_timetable_days where school_id = $school_id");
    } else {
        $timetable_days = sqlQuery("select * from schoollms_schema_userdata_school_timetable_days");
    }
    
    $num_timetable_days = count($timetable_days);
    
   // echo "NUM TABLE DAYS $num_timetable_days";
    
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
    
//     global $data;
    
//    $q = "SELECT settings FROM schoollms_schema_userdata_school_timetable_days_settings WHERE school_id = $school_id";
//    $data->execSQL($q);
//    $row = $data->getRow();
//    $settings = $row->settings;
//    $settings_tokens = explode("=", $settings);
    $start_day = 1;
    
    if ($num_days > $num_timetable_days){
        $today_day = $num_days - $num_timetable_days + $start_day;
        while ($today_day > $num_timetable_days){
            $today_day = $today_day - $num_timetable_days + $start_day;
        }
    } else {
        $today_day = $num_timetable_days - $num_days + $start_day;
    }
    
    if ($today_day <= 0){
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
            $links = get_timetable_slot_links($timetable_id, $date_stamp, $day_id, $period_label_id, $class_id, $user_type, $user_id);
            break;
        
        case 5:
            
        case 6:
            $links = "";
            break;
        
        default:
            break;
    }
    $links = urlencode($links);
    //echo "<b>$subject</b><br>$teacher<br>$room<br>$substitute<br>$links";
    return @"<b>$subject</b><br>$teacher<br>$room<br>$substitute <br>~ $links";
   
    
}

//get time table links
function get_timetable_slot_links($timetable_id, $date_stamp, $day_id, $period_label_id, $class_id, $user_type, $user_id){
    
    $links = "";
    
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
            $links .= "<b><i> LESSON(S):</i> </b> <br>";
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
                    $url = "http://172.16.0.9/learn/local_timetable_schoollms_link.php";
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
                    $url = "http://172.16.0.9/teach/local_timetable_schoollms_link.php";
                    $username="$name $surname";
                    
                    foreach ($lessons as $lesson_url => $lesson_title) {
						$lesson_title = explode("-|!~#-",$lesson_title);
                        //$pars = "action=open_link&q=$lesson_url&username=$username&passwd=$passwd";
                        $pars = "action=open_link&q=$lesson_url&username=$username&passwd=$passwd&date=$date_stamp&day=$day_id&period_label_id=$period_label_id&user_type=$user_type&user_id=$user_id&lesson_title=$lesson_title[0]";
                        $links .= "<a href='$url?$pars' target='_blank'> $lesson_title[0] </a> &nbsp;--&nbsp; <a href=\"javascript:removelesson('$lesson_title[0]','$period_label_id','$day_id','$date_stamp','$lesson_url','$class_id','$lesson_title[1]')\">Unpublish</a> <br><br>";
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
        //global $user_id;
        global $timetable_id;
        
        //$day_slots = array ();
        //echo "SCHOOL id = $school_id<br/>";
	// if $rs is null than query database (this should be only first time)
	//var_dump($rs);
	if ($rs == null) {
		//echo "<br/>Then it is here<br/>";
                if ($user_type > 0){ 
                    $time_table_id = prepareTableView($user_id,$time_table_id,$user_type, $year_id);
                } else {
                    $time_table_id = prepareTrackerView($user_id,$time_table_id,$user_type, $year_id);
                }
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
        $period_labels = sqlQuery('select *  from schoollms_schema_userdata_school_timetable_period_labels order by period_label_id');
        
		$weekday_tokens = sqlQuery("SELECT * FROM schoollms_schema_userdata_school_timetable_weekdays WHERE week_day_label LIKE '%$weekday%' ");
		//print_r($weekday_tokens);
		$index = 0;
		foreach($weekday_tokens as $key=>$value)
		{
			$index = $key;
		}
		
        $week_day_id = $weekday_tokens[$index][0][0];
        
        $periods = sqlQuery("SELECT * FROM schoollms_schema_userdata_school_timetable_period WHERE school_id = $school_id AND week_day_id = $week_day_id ORDER BY period_label_id");
	
	//echo count($period_labels);
	//echo "<br/>";
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
		$return_slots .= '<td><div style="width:100%; max-height:75px; overflow:auto">';
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
                                        
					if(!empty($class1[$i]))
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
		print '<td> <div style="width:100%; max-height:100%; overflow:auto">';
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
		print '</div></td>';
	}
	print "</tr>\n";
}

?>
