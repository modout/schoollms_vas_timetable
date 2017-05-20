<?php



extract($_POST);
extract($_GET);

//var_dump($_GET);
// include config with database definition
include('./lib/timetable.php');
//include('lib/util.php');
if(isset($db))
{
	$data->db = $db;
        //$data2->db = $db;
        //$data3->db = $db;
}
//$save_type = isset($_REQUEST['save_type']) ? $_REQUEST['save_type'] : 0;

//var_dump($_GET);
//die();

switch ($save_type) {

    case 'merit':
        $q = "SELECT * FROM schoollms_schema_userdata_learner_timetable WHERE user_id = $user_id AND year_id = $year_id";
        
        $data->execSQL($q);
        
        if ($row=$data->getRow()){
            $timetabl_log = trim($row->timetabl_log);
            $merit_tag = "MERIT|$date|$day|$period_label|$class_label|$subject|$merit_reason";
            if (strcmp($timetabl_log,'NEW')==0){
                $q = "UPDATE schoollms_schema_userdata_learner_timetable SET timetabl_log = '$merit_tag' WHERE user_id = $user_id AND year_id = $year_id";
            } else {
                if (strpos($timetabl_log, '!%!')){
                    $log_tokens = explode("!%!", $timetabl_log);
                    $found = FALSE;
                    foreach ($log_tokens as $key => $log_item) {
                        if (strcmp(trim($log_item), trim($demerit_tag)) == 0) {
                            $found = TRUE;
                            break;
                        }
                    }
                    if (!$found){
                        $timetabl_log .= "!%!$merit_tag";
                        $q = "UPDATE schoollms_schema_userdata_learner_timetable SET timetabl_log = '$timetabl_log' WHERE user_id = $user_id AND year_id = $year_id";
                    }
                } elseif (strcmp(trim($timetabl_log), trim($attendance_tag)) == 0) {
                    //DO NOTHING
                } else {
                   $timetabl_log .= "!%!$demerit_tag";
                   $q = "UPDATE schoollms_schema_userdata_learner_timetable SET timetabl_log = '$timetabl_log' WHERE user_id = $user_id AND year_id = $year_id";
                }
            }
            echo "MERIT $merit_tag <br>";
            echo "Q $q";
            $data->execNonSql($q);
        }
        break;
    
    case 'demerit':
        $q = "SELECT * FROM schoollms_schema_userdata_learner_timetable WHERE user_id = $user_id AND year_id = $year_id";
        
        $data->execSQL($q);
        
        if ($row=$data->getRow()){
            $timetabl_log = $row->timetabl_log;
            $demerit_tag = "DEMERIT|$date|$day|$period_label|$class_label|$subject|$demerit_reason";
            if ($timetabl_log == 'NEW'){
                $q = "UPDATE schoollms_schema_userdata_learner_timetable SET timetabl_log = '$demerit_tag' WHERE user_id = $user_id AND year_id = $year_id";
            } else {
                if (strpos($timetabl_log, '!%!')){
                    $log_tokens = explode("!%!", $timetabl_log);
                    $found = FALSE;
                    foreach ($log_tokens as $key => $log_item) {
                        if (strcmp(trim($log_item), trim($demerit_tag)) == 0) {
                            $found = TRUE;
                            break;
                        }
                    }
                    if (!$found){
                        $timetabl_log .= "!%!$demerit_tag";
                        $q = "UPDATE schoollms_schema_userdata_learner_timetable SET timetabl_log = '$timetabl_log' WHERE user_id = $user_id AND year_id = $year_id";
                    }
                } elseif (strcmp(trim($timetabl_log), trim($attendance_tag)) == 0) {
                    //DO NOTHING
                } else {
                   $timetabl_log .= "!%!$demerit_tag";
                   $q = "UPDATE schoollms_schema_userdata_learner_timetable SET timetabl_log = '$timetabl_log' WHERE user_id = $user_id AND year_id = $year_id";
                }
            }
            $data->execNonSql($q);
        }
        break;
    
    case 'attendance_register':
        
        $q = "SELECT * FROM schoollms_schema_userdata_learner_timetable WHERE user_id = $user_id AND year_id = $year_id";
        
        $data->execSQL($q);
        
        if ($row=$data->getRow()){
            $timetabl_log = $row->timetabl_log;
            $attendance_tag = "PRESENT|$date|$day|$period_label|$class_label|$subject";
            if ($timetabl_log == 'NEW'){
                $q = "UPDATE schoollms_schema_userdata_learner_timetable SET timetabl_log = '$attendance_tag' WHERE user_id = $user_id AND year_id = $year_id";
            } else {
                if (strpos($timetabl_log, '!%!')){
                    $log_tokens = explode("!%!", $timetabl_log);
                    $found = FALSE;
                    foreach ($log_tokens as $key => $log_item) {
                        if (strcmp(trim($log_item), trim($attendance_tag)) == 0) {
                            $found = TRUE;
                            break;
                        }
                    }
                    if (!$found){
                        $timetabl_log .= "!%!$attendance_tag";
                        $q = "UPDATE schoollms_schema_userdata_learner_timetable SET timetabl_log = '$timetabl_log' WHERE user_id = $user_id AND year_id = $year_id";
                    }
                } elseif (strcmp(trim($timetabl_log), trim($attendance_tag)) == 0) {
                    //DO NOTHING
                } else {
                   $timetabl_log .= "!%!$attendance_tag";
                   $q = "UPDATE schoollms_schema_userdata_learner_timetable SET timetabl_log = '$timetabl_log' WHERE user_id = $user_id AND year_id = $year_id";
                }
            }
            $data->execNonSql($q);
        }
        break;
		
    case 'update_timetable_slot':
    case 'save_timetable_slot':
	//var_dump($_GET);
	//echo $_GET['timetable_id'];
	//echo "<hr/>";
        //subject_id=2&teacher_id=1489&day_id=3&start_time=8:55&endtime=9:50&timetable_id=35&year_id=3&grade_id=9&room_id=24&action=save_timetable_slot
        //$settings_string = "subject_id=$subject_id|timetable_id=$timetable_id|teacher_id=$teacher_id|day_id=$day_id|period_label_id=$period_label_id|grade_id=$grade_id|year_id=$year_id|grade_id=$grade_id|room_id=$room_id";
        if($save_type == 'update_timetable_slot')
		{
			$sql = "delete from schoollms_schema_userdata_school_timetable_items
			where period_label_id = $period_label_id
			and day_id = $day_id	
			and subject_id = $current_slot_subject_id
			and teacher_id = $current_slot_teacher_id";
			//echo "<br/>$sql<hr/>";
			$data->execNonSql($sql);
			$save_type = 'save_timetable_slot';
			$_GET["save_type"] = 'save_timetable_slot';
		}
		$str = "";
		//echo "WIT : $save_type <br/>";
        foreach($_GET as $key=> $value)
        {
			
				if($save_type == 'update_timetable_slot')
				{
					echo "key => $key <br/>";
					if($key=='save_type')
					{
						$value = 'save_timetable_slot';
					}
				}
                $str .= "$key=$value|";
        }
        $str = substr($str,0,strlen($str)-1);

        echo "$str";
        $settings_string = $str;
        //echo "$settings_string <br/>";
        timetable_settings_save($school_id, $settings_string, 'timetable_slot');
        break;
    /*case 'update_timetable_slot':
		extract($_GET);
		$sql = "select * from schoollms_schema_userdata_school_timetable where timetable_type_item_id = $teacher_id";
		$timetabl_id = "";
		$data->execSQL($sql);
		if($row = $data->getRow())
		{
			$timetabl_id = $row->timetabl_id;
		}
		
		
		$sql = "select * from schoollms_schema_userdata_school_timetable where timetable_type_item_id = $current_slot_teacher_id";
		$old_timetabl_id = "";
		$data->execSQL($sql);
		if($row = $data->getRow())
		{
			$old_timetabl_id = $row->timetabl_id;
		}
		
		$sql = "select * 
		from schoollms_schema_userdata_school_timetable_items
		where teacher_id = $current_slot_teacher_id
			and subject_id = $current_slot_subject_id
			and day_id = $day_id
			and period_label_id=$period_label_id
			and timetabl_id = $old_timetabl_id";
		$data->execSQL($sql);
		echo $sql;
		if($row = $data->getRow())
		{					
			$sql = "update schoollms_schema_userdata_school_timetable_items
			set timetabl_id = $timetabl_id
			where teacher_id = $current_slot_teacher_id
				and subject_id = $current_slot_subject_id
				and day_id = $day_id
				and period_label_id=$period_label_id
				and timetabl_id = $old_timetabl_id";
			$data->execNonSql($sql);
		}
		else{
			
		}
		
		$sql = "update schoollms_schema_userdata_school_timetable_items
			set teacher_id = $teacher_id
			,subject_id = $subject_id, room_id = $room_id
			where teacher_id = $current_slot_teacher_id
			and subject_id = $current_slot_subject_id
			and day_id = $day_id
			and period_label_id=$period_label_id";
			
		echo $sql;
		$data->execNonSql($sql);
		
		//remove old slots
		var_dump($_GET);
		$sql = "delete from schoollms_schema_userdata_school_timetable_items
		where period_label_id = $period_label_id
		and day_id = $day_id	
		and subject_id = $current_slot_subject_id
		and teacher_id = $current_slot_teacher_id";
		echo "<br/>$sql<hr/>";
		//$data->execNonSql($sql);
		
		//add new slots
		$class_id = 0;
		$sql = "select timetable_type_item_id from schoollms_schema_userdata_school_timetable 
			where timetabl_id = $timetable_id ";
		$data->execSQL($sql);
		if($row = $data->getRow())
		{
			$class_id = $row->timetable_type_item_id;
		}
		
		$sql = "insert into schoollms_schema_userdata_school_timetable_items 
			(timetabl_id, day_id, period_label_id, grade_id, class_id, room_id, subject_id, teacher_id, substitude_id)
			select timetabl_id,$day_id,$period_label_id,$grade_id,$class_id,$room_id,$subject_id,$teacher_id,0
			from schoollms_schema_userdata_learner_schooldetails sd
			join schoollms_schema_userdata_school_timetable t on user_id = timetable_type_item_id
			where class_id = $class_id";
		//$data->execNonSql($sql);
		echo "<br/>$sql<hr/>";
		
		$sql = "select * from schoollms_schema_userdata_school_timetable where timetable_type_item_id = $teacher_id";
		$teacher_timetabl_id = "";
		$data->execSQL($sql);
		if($row = $data->getRow())
		{
			$teacher_timetabl_id = $row->timetabl_id;
		}
		
		$sql = "insert into schoollms_schema_userdata_school_timetable_items 
			(timetabl_id, day_id, period_label_id, grade_id, class_id, room_id, subject_id, teacher_id, substitude_id)
			values ($teacher_timetabl_id,$day_id,$period_label_id,$grade_id,$class_id,$room_id,$subject_id,$teacher_id,0)";
		//$data->execNonSql($sql);
		echo "<br/>$sql<hr/>";
		
		break;	*/
    case 'save_general_timetable_settings':
        $break_times = "";
        $count_breaks = 1;

        while ($count_breaks <= $number_of_breaks){
            $time = isset($_REQUEST["break_time_$count_breaks"])? $_REQUEST["break_time_$count_breaks"]: "11:00"; 
            $length = isset($_REQUEST["break_length_$count_breaks"])? $_REQUEST["break_length_$count_breaks"]: 30;
            $break_times .= "break_$count_breaks!$time!$length*";
                //$break_times .= strpos($break_times, '*') !== FALSE ? "*break_$count_breaks!$time!$length" : "break_$count_breaks!$time!$length";
            $count_breaks++;
        }
        $break_times = substr($break_times,0,strlen($break_times)-1);

        $settings_string = "days=$days|periods=$periods|monday_period_time=$monday_period_time|tuesday_period_time=$tuesday_period_time|wednesday_period_time=$wednesday_period_time|thursday_period_time=$thursday_period_time|friday_period_time=$friday_period_time|saturday_period_time=$saturday_period_time|sunday_period_time=$sunday_period_time|number_of_breaks=$number_of_breaks|break_times=$break_times|from_grade=$from_grade|to_grade=$to_grade|classletters=$classletters|rotation_type=$rotation_type|school_start_time=$school_start_time|class_start_time=$class_start_time";

        //echo "School ID $school_id SETTINGS $settings_string";

        timetable_settings_save($school_id, $settings_string, 'general');
        break;
    
    case 'publish_lesson':
        $q = "SELECT subject_id FROM schoollms_schema_userdata_school_subjects WHERE subject_title = '$subject'";
      
        echo "SUBJECTID Q $q <br>\n";
        
        $result = sqlQuery($q);
        
        foreach ($result as $key => $value) {
            $subject_id = $value[0][0];
            break;
        }
        echo "<hr/>$subject_id<hr/>";
		$class = str_replace(",","%",$class);
        $q = "SELECT class_id FROM schoollms_schema_userdata_school_classes WHERE class_label LIKE '%$class%' and school_id = $school_id and year_id = $year_id";
        
        echo "<hr/> CLASSID $q <hr/>";
        
		
        $result = sqlQuery($q);
        
        foreach ($result as $key => $value) {
            $class_id = $value[0][0];
            break;
        }
		
		$time_tokens = explode("-", $time);
        
        $start_time = $time_tokens[0];
        
        $end_time = $time_tokens[1];
		
		  $q = "SELECT week_day_id FROM schoollms_schema_userdata_school_timetable_weekdays WHERE week_day_label like '%$week_day%'";
        
        echo "WEEKID Q $q <br>\n";
        
        $result = sqlQuery($q);
        
		            $week_day_id = "";
        foreach ($result as $key => $value) {
            $week_day_id = $value[0][0];
            break;
        }
        $date = explode("~",$date);
		
        $q = "SELECT period_label_id FROM schoollms_schema_userdata_school_timetable_period WHERE period_start = '$start_time' AND period_end = '$end_time' and school_id = $school_id and week_day_id = $week_day_id";
		$q = "select pl.period_label_id
			from schoollms_schema_userdata_school_timetable_period ls
			join schoollms_schema_userdata_school_timetable_period_labels pl on pl.period_label_id = ls.period_label_id
			where ls.school_id =  $school_id
			and pl.period_label_id =  '$date[2]'
			and week_day_id = $week_day_id";
        
        echo "PERIODLABELID Q $q <br>\n";
        
        $result = sqlQuery($q);
        $period_id ="";
        foreach ($result as $key => $value) {
            $period_id = $value[0][0];
            break;
        }
		
    
        echo  $day;
        $q = "SELECT day_id FROM schoollms_schema_userdata_school_timetable_days WHERE day_label = '$day'";
        
        echo "DAYID Q $q <br>\n";
        
        $result = sqlQuery($q);
        
        foreach ($result as $key => $value) {
            $day_id = $value[0][0];
            break;
        }
        
		$datedata = explode("~",$_GET["date"]);
		$timetable_id = $datedata[3];
		$sql = "select class_id
			from schoollms_schema_userdata_school_timetable_items
			where day_id = $day_id
			and period_label_id = $period_id
			and subject_id = $subject_id
			AND timetabl_id = $timetable_id ";
		$data->execSQL($sql);
		$period_class_id = $class_id;
		if($row = $data->getRow())
		{
			echo " -- AHHA  $sql-- <hr/>";
			$period_class_id = $row->class_id;
			if($period_class_id != $class_id)
			{
				$class_id = $row->class_id;
			}			
		}
		
		
        $q = "INSERT INTO schoollms_schema_userdata_school_timetable_subject_lessons (day_id,period_label_id,class_id,subject_id,lesson_date,lesson_url,lesson_title) VALUES ($day_id, $period_id, $class_id, $subject_id, '$date[0]', '$lessonurl', '$lesson')";
        $data->execNonSql($q);
        echo "INSERT LESSON Q $q <br>\n";
		
		$action_type_id = 0;
		
		$sql = "select action_type_id 
		from action_type where action_type = 'Teacher Publish Lesson'";
		$data->execSQL($sql);
		if($row = $data->getRow())
		{
			$action_type_id = $row->action_type_id;
		}
		
		//record action
		$sql = "insert into user_action_log (action_type_id,action_time,user_id,day_id,period_label_id,class_id,subject_id,lesson_date)
		values ($action_type_id,now(),$user_id,$day_id, $period_id, $class_id, $subject_id, '$date[0]')";
		$data->execNonSql($sql);
		echo "<hr/> $sql";
        die();
        //$result = sqlQuery($q);
        break;
    
    case 'class_settings_save':
         $settings = timetable_settings_get($school_id, 'general');
        
        //CREATE SUBJECT IN ADMIN - FOR ADMIN TO SYNC TO ALL
        $q = "SELECT class_lms_ids FROM schoollms_schema_userdata_school_class_lms_ids WHERE school_id = $school_id AND subject_id = $subject_id AND grade_id = $grade_id AND class_id = $class_id";
        
        $result = sqlQuery($q);
        
        $count = count($result);

        if ($count == 0){
            
        }
        break;
    
    case 'subject_settings_save':
//        $school_id = $_REQUEST['school_id'];
//        $subject_id = $_REQUEST['subject_id'];
//        $subject_color = $_REQUEST['subject_color'];
//        $grade_subject_color = $_REQUEST['grade_subject_color'];
//        $period_type = $_REQUEST['period_type'];
//        $period_times = $_REQUEST['period_times'];
//        $notional_time = $_REQUEST['notional_time'];
//        $grade_id = $_REQUEST['grade_id'];
//        $periods_cycle = $_REQUEST['periods_cycle'];
//        $minimum_learners = $_REQUEST['minimum_learners'];
//        $subject_type = $_REQUEST['subject_type'];

        //Must get existing grade_settings to be able to compare and modify where required.


        $grade_settings = "grade_setting#grade_id=$grade_id:grade_subject_color=$grade_subject_color,notional_time=$notional_time,period_cycle=$periods_cycle,subject_type=$subject_type,minimum_learners=$minimum_learners";

        $settings_string = "subject_id=$subject_id<subject_color=$subject_color|period_type=$period_type|period_times=$period_times|$grade_settings";

        //echo "BEFORE SETTINGS $settings_string <br />";

        $settings_string = timetable_settings_updateupdate($school_id, $settings_string, 'subject', $subject_id);

        //echo "AFTER SETTINGS $settings_string <br />";

        timetable_settings_save($school_id, $settings_string, 'subject');
        
        //CREATE SUBJECT IN ADMIN - FOR ADMIN TO SYNC TO ALL
        $q = "SELECT subject_lms_ids FROM schoollms_schema_userdata_school_subject_lms_ids WHERE school_id = $school_id AND subject_id = $subject_id AND grade_id = $grade_id";
        
        $result = sqlQuery($q);
        
        $count = count($result);

        if ($count == 0){
            //http://172.16.0.9/teachdev/local_timetable_schoollms_link.php?action=save_new_subject&username=System%20Admin&passwd=$0W3t0&subject_name=SUBJECT%20TEST&grade_no=8
            $q  = "SELECT subject_title FROM schoollms_schema_userdata_school_subjects WHERE subject_id = $subject_id";
            $result = sqlQuery($q);

            foreach ($result as $setting_item) {
                //print_r($setting_item);
                $subject_name = $setting_item[0][0];
                break;
            }

            $url = "http://127.0.0.1:8081/teach/local_timetable_schoollms_link.php";
            $pars = "action=save_new_subject&username=System Admin&passwd=$0W3t0&subject_name=$subject_name&grade_no=$grade_id";

            $contents = do_post_request($url,$pars);
        //echo "RESPONSE $contents PARA $pars<br>\n";
            $remote_response = json_decode($contents, TRUE);

            $subject_lms_ids  = "";
            foreach($remote_response as $subject_lms_name=>$subject_nid){
                $subject_lms_ids .= "$subject_lms_name=$subject_nid|";
            }
            $subject_lms_ids = trim($subject_lms_ids, "|");
            //GET subject_lms_links

            $q = "UPDATE schoollms_schema_userdata_school_subject_lms_ids SET subject_lms_ids = '$subject_lms_ids' WHERE school_id = $school_id AND subject_id = $subject_id AND grade_id = $grade_id";
            $result = sqlQuery($q);
            
        }
        
        break;
    
    case 'learn_timetable_slot':
        
        break;
    
    case 'teach_timetable_slot':
        $school_id = isset($_REQUEST['school_id']) ? $_REQUEST['school_id'] : 0;
        $year_id = isset($_REQUEST['year_id']) ? $_REQUEST['year_id'] : 0;
        $grade_id = isset($_REQUEST['grade_id']) ? $_REQUEST['grade_id'] : 0;
        $class_id = isset($_REQUEST['class_id']) ? $_REQUEST['class_id'] : 0;
        $user_id = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : 0;
        $subject_id = isset($_REQUEST['subject_id']) ? $_REQUEST['subject_id'] : 0;
        $room_id = isset($_REQUEST['room_id']) ? $_REQUEST['room_id'] : 0;
        $change_type = isset($_REQUEST['change_type']) ? $_REQUEST['change_type'] : 0;
        $change_value = isset($_REQUEST['change_value']) ? $_REQUEST['change_value'] : 0;
        $date = isset($_REQUEST['date']) ? $_REQUEST['date'] : 0;
        $lesson_id = isset($_REQUEST['lesson_id']) ? $_REQUEST['lesson_id'] : 0;
        break;
    
    case 'admin_timetable_slot':
        $school_id = isset($_REQUEST['school_id']) ? $_REQUEST['school_id'] : 0;
        $year_id = isset($_REQUEST['year_id']) ? $_REQUEST['year_id'] : 0;
        $grade_id = isset($_REQUEST['grade_id']) ? $_REQUEST['grade_id'] : 0;
        $class_id = isset($_REQUEST['class_id']) ? $_REQUEST['class_id'] : 0;
        $user_id = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : 0;
        $day = isset($_REQUEST['day']) ? $_REQUEST['day'] : 0;
        $period = isset($_REQUEST['period']) ? $_REQUEST['period'] : 0;
        $subject_id = isset($_REQUEST['subject_id']) ? $_REQUEST['subject_id'] : 0;
        $room_id = isset($_REQUEST['room_id']) ? $_REQUEST['room_id'] : 0;
        $change_type = isset($_REQUEST['change_type']) ? $_REQUEST['change_type'] : 0;
        $change_value = isset($_REQUEST['change_value']) ? $_REQUEST['change_value'] : 0;
        $date = isset($_REQUEST['date']) ? $_REQUEST['date'] : 0;
        $lesson_id = isset($_REQUEST['lesson_id']) ? $_REQUEST['lesson_id'] : 0;
        
        if ($day == 0 && $period == 0){
            //ERROR
        } else {
            //Get Day ID - USING school_id and year_id and day_label
            $q = "SELECT day_id FROM schoollms_schema_userdata_school_timetable_days WHERE day_label = '$day'";
            $result = sqlQuery($q);
            $day_id = 0;
   
            foreach ($result as $setting_item) {
                //print_r($setting_item);
                $day_id = $setting_item[0][0];
                break;
            }
            
            //Get Period ID - USING school_id, year_id and day_id and period_times
            $period_times = explode("-", $period);
            $period_start = $period_times[0];
            $period_end = $period_times[1];
            
            $q = "SELECT period_id FROM schoollms_schema_userdata_school_timetable_period WHERE period_start = '$period_start' AND period_end = '$period_end'";
            $result = sqlQuery($q);
            $period_id = 0;
   
            foreach ($result as $setting_item) {
                //print_r($setting_item);
                $period_id = $setting_item[0][0];
                break;
            }
            
            $save_string = "school_id=$school_id,year_id=$year_id,grade_id=$grade_id,class_id=$class_id,user_id=$user_id,day_id=$day_id,period_id=$period_id,subject_id=$subject_id,room_id=$room_id,change_type=$change_type,change_value=$change_value";
            timetable_save($save_type, $save_string);
            //USING TIMETABLE ID 
            //$q = "select * from schoollms_schema_userdata_school_timetable_items where timetable_id = $timetable_id and grade_id =  $grade_id and class_id = $class_id and day_id = $day_id and period_id = $period_id";
        }
        break;
    
    case 'learner_timetable_settings':
        $school_id = $_REQUEST['school_id'];
        $grade_id = $_REQUEST['grade_id'];
        $user_id = $_REQUEST['user_id'];
        $year_id = $_REQUEST['year_id'];
        $baseline = $_REQUEST['baseline'];
        $learner_average = $_REQUEST['learner_average'];
        $subject_choice = $_REQUEST['subject_choice'];
        $grade_entry_average= isset($_REQUEST['grade_entry_average']) ? $_REQUEST['grade_entry_average'] : 0;
        $number_of_learners= isset($_REQUEST['number_of_learners']) ? $_REQUEST['number_of_learners'] : 0;
        $current_grade= isset($_REQUEST['current_grade']) ? $_REQUEST['current_grade'] : 0;
        $current_class= isset($_REQUEST['current_class']) ? $_REQUEST['current_class'] : 0;
        $next_grade= isset($_REQUEST['next_grade']) ? $_REQUEST['next_grade'] : 0;
        $next_class= isset($_REQUEST['next_class']) ? $_REQUEST['next_class'] : 0;
        //Must get existing grade_settings to be able to compare and modify where required.
        //$current_settings = timetable_settings_get($school_id, "learner");

        $learner_settings = "learner_settings#user_id=$user_id:baseline=$baseline,learner_average=$learner_average,subject_choice=$subject_choice,current_grade=$current_grade,current_class=$current_class,next_grade=$next_grade,next_class=$next_class";

        $settings_string = "grade_id=$grade_id<number_of_learners=$number_of_learners|year_id=$year_id|$learner_settings";

        //echo "BEFORE SETTINGS $settings_string <br />";

        $settings_string = timetable_settings_update($school_id, $settings_string, 'learner', $grade_id);

        //echo "AFTER SETTINGS $settings_string <br />";

        timetable_settings_save($school_id, $settings_string, 'learner');
        break;
    
    case 'update_learner':
        $year_id = isset($_REQUEST['year_id']) ? $_REQUEST['year_id'] : 0;
        $school_id = isset($_REQUEST['school_id']) ? $_REQUEST['school_id'] : 0;
        $learner_id = isset($_REQUEST['learner_id']) ? $_REQUEST['learner_id'] : 0;
        $user_id = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : 0;
        $name = isset($_REQUEST['name']) ? $_REQUEST['name'] : "";
        $surname = isset($_REQUEST['surname']) ? $_REQUEST['surname'] : "";
        $baseline = isset($_REQUEST['baseline']) ? $_REQUEST['baseline'] : 0;
        $learner_average = $_REQUEST['learner_average'];
        $subject_choice = $_REQUEST['subject_choice'];
        $grade_entry_average= isset($_REQUEST['grade_entry_average']) ? $_REQUEST['grade_entry_average'] : 0;
        $number_of_learners= isset($_REQUEST['number_of_learners']) ? $_REQUEST['number_of_learners'] : 0;
        $current_grade= isset($_REQUEST['current_grade']) ? $_REQUEST['current_grade'] : 0;
        $current_class= isset($_REQUEST['current_class']) ? $_REQUEST['current_class'] : 0;
        $next_grade= isset($_REQUEST['next_grade']) ? $_REQUEST['next_grade'] : 0;
        $next_class= isset($_REQUEST['next_class']) ? $_REQUEST['next_class'] : 0;

        $parent_id_1 = isset($_REQUEST['parent_id_1']) ? $_REQUEST['parent_id_1'] : 0;
        $parent_name_1 = isset($_REQUEST['parent_name_1']) ? $_REQUEST['parent_name_1'] : "";
        $parent_surname_1 = isset($_REQUEST['parent_surname_1']) ? $_REQUEST['parent_surname_1'] : "";
        $parent_id_2 = isset($_REQUEST['parent_id_2']) ? $_REQUEST['parent_id_2'] : 0;
        $parent_name_2 = isset($_REQUEST['parent_name_2']) ? $_REQUEST['parent_name_2'] : "";
        $parent_surname_2 = isset($_REQUEST['parent_surname_2']) ? $_REQUEST['parent_surname_2'] : "";
        
        $q = "UPDATE schoollms_schema_userdata_access_profile SET school_id = $school_id, access_id = '$learner_id', name = '$name', surname= '$surname' WHERE user_id = $user_id";
        $result = sqlQuery($q);
        
        if ($parent_id_1 != 0){
            $add_parent_string = "$parent_id_1#$school_id, '$parent_id_1',3,'$parent_name_1','$parent_surname_1'";
            $parent_1_user_id = timetable_save('new_user', $add_parent_string);
            
            $add_string = "$user_id#$parent_1_user_id";
        }
        
        if ($parent_id_2 != 0){
            $add_parent_string = "$parent_id_2#$school_id, '$parent_id_2',3,'$parent_name_2','$parent_surname_2'";
            $parent_2_user_id = timetable_save('new_user', $add_parent_string);
            
            $add_string .= ",$parent_2_user_id";
        }
        
        timetable_save('save_learner_parent', $add_string);
     
        if ($grade_id != 0){
            $learner_settings = "learner_settings#user_id=$user_id:baseline=$baseline,learner_average=$learner_average,subject_choice=$subject_choice,current_grade=$current_grade,current_class=$current_class,next_grade=$next_grade,next_class=$next_class";


            $settings_string = "grade_id=$grade_id<number_of_learners=$number_of_learners|year_id=$year_id|$learner_settings";

            //echo "BEFORE SETTINGS $settings_string <br />";

            $settings_string = timetable_settings_update($school_id, $settings_string, 'learner', $grade_id);

            //echo "AFTER SETTINGS $settings_string <br />";

            timetable_settings_save($school_id, $settings_string, 'learner');
        }
        
        if ($next_grade != 0){
            //INSERT LEARNER CURRENT SETTINGS
            $class = "Class $next_grade"."$next_class";

             $q = "SELECT class_id FROM schoollms_schema_userdata_school_classes WHERE class_label = '$class' AND school_id = $school_id AND year_id = $year_id";
            $result = sqlQuery($q);
            $class_id = 0;
            foreach ($result as $row){
                $class_id = $row[0][0];
                break;
            }

            //echo "CLASS ID $class_id Q $q<br>";
            
            $q = "SELECT * FROM schoollms_schema_userdata_learner_schooldetails WHERE user_id = $user_id AND school_id = $school_id AND year_id = $year_id";
            $result = sqlQuery($q);
            
            //echo "LEARNER Q $q <br>";
            
            if (!empty($result)){
                $q = "UPDATE schoollms_schema_userdata_learner_schooldetails SET grade_id = $next_grade, class_id = $class_id, year_id = $year_id WHERE user_id = $user_id AND school_id = $school_id AND year_id = $year_id";
            } else {
                $q = "INSERT INTO schoollms_schema_userdata_learner_schooldetails VALUES ($user_id, $school_id, $next_grade, $class_id, $year_id)";
            }
            //echo "FINAL Q $q <br>";
            
            $result = sqlQuery($q);
        }
        break;
    
    case 'update_teacher':
        $year_id = isset($_REQUEST['year_id']) ? $_REQUEST['year_id'] : 0;
        $school_id = isset($_REQUEST['school_id']) ? $_REQUEST['school_id'] : 0;
        $teacher_id = isset($_REQUEST['teacher_id']) ? $_REQUEST['teacher_id'] : 0;
        $name = isset($_REQUEST['teacher_initials']) ? $_REQUEST['teacher_initials'] : "";
        $surname = isset($_REQUEST['teacher_surname']) ? $_REQUEST['teacher_surname'] : "";
        $user_id = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : 0;
		$subject_id = isset($_REQUEST['subject_id']) ? $_REQUEST['subject_id'] : 0;
        
        $q = "UPDATE schoollms_schema_userdata_access_profile SET school_id = $school_id, access_id = '$teacher_id', name = '$name', surname= '$surname' WHERE user_id = $user_id";
		
        $number_of_subjects = isset($_REQUEST['number_of_subjects']) ? $_REQUEST['number_of_subjects'] : 0;
        
        $subjects = "";
        $count_subjects = 1;

        while ($count_subjects <= $number_of_subjects){
            $year = isset($_REQUEST["year_id$count_subjects"])? $_REQUEST["year_id$count_subjects"]: 3;
            $subject = isset($_REQUEST["teacher_subject_id$count_subjects"])? $_REQUEST["teacher_subject_id$count_subjects"]: 0;
            $grade = isset($_REQUEST["teacher_grade_$count_subjects"])? $_REQUEST["teacher_grade_$count_subjects"]: 0;
            $subjects .= "subject_$count_subjects!$year!$subject!$grade*";
                //$break_times .= strpos($break_times, '*') !== FALSE ? "*break_$count_breaks!$time!$length" : "break_$count_breaks!$time!$length";
            $count_subjects++;
        }
        $subjects = trim($subjects, "*");
        
        $add_teacher_string = "$teacher_id#$subjects#$school_id#4#$user_id#$school_id, '$teacher_id',4,'$name','$surname'";
        //echo "$add_teacher_string <br/>";
        $user_id = timetable_save('new_user', $add_teacher_string);
//		echo "$q<br/>";
//		
//		$subject_settings = "subject_id=$subject_id%grade_id=$grade_id!number_periods=";
//
//		$teacher_settings = "teacher_settings#user_id=$user_id:$subject_settings,substitute=0";
//
//		$settings_string = "year_id=$year_id<number_teachers=|$teacher_settings";
//
//		echo "BEFORE SETTINGS $settings_string <br />";
//
//		$settings_string = timetable_settings_update($school_id, $settings_string, 'teacher', $year_id);
//
//		echo "AFTER SETTINGS $settings_string <br />";

		//timetable_settings_save($school_id, $settings_string, 'teacher');
		//AddUpdateTeacherGradeSubject($user_id, $grade_id,$subject_id,$school_id,$year_id);
		
        $result = sqlQuery($q);
        break;
    
    
    case 'new_learner':
        /*$year_id = isset($_REQUEST['year_id']) ? $_REQUEST['year_id'] : 0;
        $school_id = isset($_REQUEST['school_id']) ? $_REQUEST['school_id'] : 0;
        $learner_id = isset($_REQUEST['learner_id']) ? $_REQUEST['learner_id'] : 0;
        $name = isset($_REQUEST['name']) ? mysql_real_escape_string($_REQUEST['name']) : "";
        $surname = isset($_REQUEST['surname']) ? mysql_real_escape_string($_REQUEST['surname']) : "";
        $baseline = isset($_REQUEST['baseline']) ? $_REQUEST['baseline'] : 0;
        $learner_average = $_REQUEST['learner_average'];
        $subject_choice = $_REQUEST['subject_choice'];
        $grade_entry_average= isset($_REQUEST['grade_entry_average']) ? $_REQUEST['grade_entry_average'] : 0;
        $number_of_learners= isset($_REQUEST['number_of_learners']) ? $_REQUEST['number_of_learners'] : 0;
        $current_grade= isset($_REQUEST['current_grade']) ? $_REQUEST['current_grade'] : 0;
        $current_class= isset($_REQUEST['current_class']) ? $_REQUEST['current_class'] : 0;
        $next_grade= isset($_REQUEST['next_grade']) ? $_REQUEST['next_grade'] : 0;
        $next_class= isset($_REQUEST['next_class']) ? $_REQUEST['next_class'] : 0;
        
        
        $parent_id_1 = isset($_REQUEST['parent_id_1']) ? $_REQUEST['parent_id_1'] : 0;
        $parent_name_1 = isset($_REQUEST['parent_name_1']) ? $_REQUEST['parent_name_1'] : 0;
        $parent_surname_1 = isset($_REQUEST['parent_surname_1']) ? $_REQUEST['parent_surname_1'] : 0;
        $parent_id_2 = isset($_REQUEST['parent_id_2']) ? $_REQUEST['parent_id_2'] : 0;
        $parent_name_2 = isset($_REQUEST['parent_name_2']) ? $_REQUEST['parent_name_2'] : 0;
        $parent_surname_2 = isset($_REQUEST['parent_surname_2']) ? $_REQUEST['parent_surname_2'] : 0;
		*/
		extract($_GET);
		extract($_POST);
		$grade_id = $next_grade;
		$baseline = $potential_score;
                $subjects = "TBA";
                $classes = "TBA";
                $user_id = 0;
        $add_learner_string = "$learner_id#$subjects%$classes#$school_id#2#$user_id#$school_id, '$learner_id',2,'$name','$surname'";
        
        $user_id = timetable_save('new_user', $add_learner_string);
        
        if ($parent_id_1 != 0){
            $add_parent_string = "$parent_id_1#$school_id, '$parent_id_1',3,'$parent_name_1','$parent_surname_1'";
            $parent_1_user_id = timetable_save('new_user', $add_parent_string);
            
            $add_string = "$user_id#$parent_1_user_id";
        }
        
        if ($parent_id_2 != 0){
            $add_parent_string = "$parent_id_2#$school_id, '$parent_id_2',3,'$parent_name_2','$parent_surname_2'";
            $parent_2_user_id = timetable_save('new_user', $add_parent_string);
            
            $add_string .= ",$parent_2_user_id";
        }
        
        timetable_save('save_learner_parent', $add_string);
     
        if ($grade_id !== 0){
            $learner_settings = "learner_settings#user_id=$user_id:baseline=$baseline,learner_average=$learner_average,subject_choice=$subject_choice,current_grade=$current_grade,current_class=$current_class,next_grade=$next_grade,next_class=$next_class";


            $settings_string = "grade_id=$grade_id<number_of_learners=$number_of_learners|year_id=$year_id|$learner_settings";

            echo "BEFORE SETTINGS $settings_string <br />";

            $settings_string = timetable_settings_update($school_id, $settings_string, 'learner', $grade_id);

            echo "AFTER SETTINGS $settings_string <br />";

            timetable_settings_save($school_id, $settings_string, 'learner');
        }
        
        if ($next_grade !== 0){
            //INSERT LEARNER CURRENT SETTINGS
            $class = "Class $next_grade"."$next_class";

             $q = "SELECT class_id FROM schoollms_schema_userdata_school_classes WHERE class_label = '$class'";
            $result = sqlQuery($q);
            $class_id = 0;
            foreach ($result as $row){
                $class_id = $row[0][0];
                break;
            }

            $q = "INSERT INTO schoollms_schema_userdata_learner_schooldetails(user_id,school_id,grade_id,class_id,year_id) VALUES ($user_id, $school_id, $next_grade, $class_id, $year_id)";
            $result = sqlQuery($q);
        }
        
        //echo "LEARNER Q $add_learner_string <br>";
        //CREATE USER ACCOUNT ON SCHOOLLMS PORTALS
        //-- Learner Portal
        //-- Teacher Portal
        //-- Parent Portal
        //-- Admin Portal
        //-- Support Portal
        //-- Training Portal
        //-- VAS Portal
        
        //Get All User IDS from the portals to INSERT IN THE SUPPORT DB MASTER TABLE
        //SAVE LEARNER
        /*if ($user_id > 0){
            $q = "SELECT * FROM schoollms_schema_userdata_access_profile WHERE user_id = $user_id";
        } else {
            $q = "SELECT * FROM schoollms_schema_userdata_access_profile WHERE access_id = $learner_id";
        }
        
        $result = sqlQuery($q);
        
        if (!empty($result)){               
            //UPDATE USER DATA
            $q = "";
        } else {
            //INSERT INTO schoollms_schema_userdata_acces_profile
            $q = "INSERT INTO schoollms_schema_userdata_access_profile (school_id,access_id,type_id,name,surname) VALUES ($school_id, '$learner_id',2,'$name','$surname')";
        }
        $result = sqlQuery($q);
        
        //SAVE PARENTS
        $q = "SELECT * FROM schoollms_schema_userdata_access_profile WHERE access_id = $parent_id_1";
        $result = sqlQuery($q);
        if (!empty($result)){               
            //UPDATE USER DATA
            $q = "";
        } else {
            //INSERT INTO schoollms_schema_userdata_acces_profile
            $q = "INSERT INTO schoollms_schema_userdata_access_profile (school_id,access_id,type_id,name,surname) VALUES ($school_id, '$parent_id_1',3,'$parent_name_1','$parent_surname_1')";            
        }
        $result = sqlQuery($q);
        
        $q = "SELECT * FROM schoollms_schema_userdata_access_profile WHERE access_id = $parent_id_2";
        $result = sqlQuery($q);
        if (!empty($result)){               
            //UPDATE USER DATA
            $q = "";
        } else {
            //INSERT INTO schoollms_schema_userdata_acces_profile
            $q = "INSERT INTO schoollms_schema_userdata_access_profile (school_id,access_id,type_id,name,surname) VALUES ($school_id, '$parent_id_2',3,'$parent_name_2','$parent_surname_2')";
        }
        $result = sqlQuery($q);
        
        //STORE LEARNER PARENT DETAILS
        $q = "SELECT user_id FROM schoollms_schema_userdata_access_profile WHERE access_id = '$learner_id'";
        $result = sqlQuery($q);
        
        $user_id = 0;
        foreach ($result as $setting_item) {
            //print_r($setting_item);
            $user_id = $setting_item[0][0];
            break;
        }
        
        if ($user_id > 0){
            $q = "SELECT user_id FROM schoollms_schema_userdata_access_profile WHERE access_id = '$parent_id_1'";
            $result = sqlQuery($q);
         
            $parent_user_id_1 = 0;
            foreach ($result as $setting_item) {
                //print_r($setting_item);
                $parent_user_id_1 = $setting_item[0][0];
                break;
            }
            
            $q = "SELECT user_id FROM schoollms_schema_userdata_access_profile WHERE access_id = '$parent_id_2'";
            $result = sqlQuery($q);
         
            $parent_user_id_2 = 0;
            foreach ($result as $setting_item) {
                //print_r($setting_item);
                $parent_user_id_2 = $setting_item[0][0];
                break;
            }
            
            $q = "INSERT INTO schoollms_schema_userdata_learner_parent VALUES ($user_id,'parent_1:$parent_user_id_1,parent_2:$parent_id_2')";
            $result = sqlQuery($q);
            
            $learner_settings = "learner_settings#user_id=$user_id:baseline=$baseline,learner_average=$learner_average,subject_choice=$subject_choice,current_grade=$current_grade,current_class=$current_class,next_grade=$next_grade,next_class=$next_class";

            
            $settings_string = "grade_id=$grade_id<number_of_learners=$number_of_learners|year_id=$year_id|$learner_settings";

            //echo "BEFORE SETTINGS $settings_string <br />";

            $settings_string = timetable_settings_update($school_id, $settings_string, 'learner', $grade_id);

            //echo "AFTER SETTINGS $settings_string <br />";

            timetable_settings_save($school_id, $settings_string, 'learner');
            
            //INSERT LEARNER CURRENT SETTINGS
            $class = "Class $next_grade"."$next_class";
            
             $q = "SELECT class_id FROM schoollms_schema_userdata_school_classes WHERE class_label = '$class'";
            $result = sqlQuery($q);
            $class_id = 0;
            foreach ($result as $row){
                $class_id = $row[0][0];
                break;
            }
            
            $q = "INSERT INTO schoollms_schema_userdata_learner_schooldetails VALUES ($user_id, $school_id, $next_grade, $class_id, $year_id)";
            $result = sqlQuery($q);
        }
        //PREPARE DEVICE TABLE ENTRY
        //SAVE IDENTITIES IN ALL OTHER PORTALS LINKED TO THE MASTER USER_ID
        */
        break;
        
    case 'new_teacher':
        $year_id = isset($_REQUEST['year_id']) ? $_REQUEST['year_id'] : 0;
        $school_id = isset($_REQUEST['school_id']) ? $_REQUEST['school_id'] : 0;
        $teacher_id = isset($_REQUEST['teacher_id']) ? $_REQUEST['teacher_id'] : 0;
        $name = isset($_REQUEST['teacher_initials']) ? $_REQUEST['teacher_initials'] : "";
        $surname = isset($_REQUEST['teacher_surname']) ? $_REQUEST['teacher_surname'] : "";
        $number_of_subjects = isset($_REQUEST['number_of_subjects']) ? $_REQUEST['number_of_subjects'] : 0;
        
        $subjects = "";
        $count_subjects = 1;

        while ($count_subjects <= $number_of_subjects){
            $year = isset($_REQUEST["year_id$count_subjects"])? $_REQUEST["year_id$count_subjects"]: 3;
            $subject = isset($_REQUEST["teacher_subject_id$count_subjects"])? $_REQUEST["teacher_subject_id$count_subjects"]: 0;
            $grade = isset($_REQUEST["teacher_grade_$count_subjects"])? $_REQUEST["teacher_grade_$count_subjects"]: 0;
            $subjects .= "subject_$count_subjects!$year!$subject!$grade*";
                //$break_times .= strpos($break_times, '*') !== FALSE ? "*break_$count_breaks!$time!$length" : "break_$count_breaks!$time!$length";
            $count_subjects++;
        }
        $subjects = trim($subjects, "*");
        
        $add_teacher_string = "$teacher_id#$subjects#$school_id#4#$school_id, '$teacher_id',4,'$name','$surname'";
        //echo "$add_teacher_string <br/>";
        $user_id = timetable_save('new_user', $add_teacher_string);
        
//        $q = "SELECT * FROM schoollms_schema_userdata_access_profile WHERE access_id = $teacher_id";
//        $result = sqlQuery($q);
//                     
//            //UPDATE USER DA
//        if (!empty($result)){  TA
//            
//        } else {
//            //INSERT INTO schoollms_schema_userdata_acces_profile
//            $q = "INSERT INTO schoollms_schema_userdata_access_profile (school_id,access_id,type_id,name,surname) VALUES ($school_id, '$teacher_id',4,'$name','$surname')";
//        }
//        $result = sqlQuery($q);
        break;

    default:
        break;
}
