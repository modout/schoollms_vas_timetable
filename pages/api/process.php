<?php

//include('data_db_mysqli.inc');
include('../lib/timetable.php');
//include("../Mailer/SchoolLmsMailer.php");
define("SITE_URL", "http://schoollms.sipnet.co.za/");

extract($_POST);
if(!isset($action))
{
	extract($_GET);
}

$audit_data = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
//sendConfimationEmail("ssiphiwo@gmail.com", "Siphiwo", 2);

if(!isset($action))
{
	die();
}

if(strtoupper($action) == "DEMO"){
	$school_id = 1;
	$url = "localhost/fromserver2/";
	switch($type_id){
		case 2:
			break;
		case 3:
			break;
		case 4:
			$user_ids = array(1508, 1500);
			$user_id_key = rand(0, 1);
			$user_id = $user_ids[$user_id_key];
			$pars = "school_id=$school_id&user_type=$type_id&user_id=$user_id";
			break;
		case 5:
			break;		
		case 6:
			break;
		case 7:
			break;
	}
	
        $data = "http://$url?$pars";
        timetable_audit_log($user_id, $action, $data);
        
	header("Location: http://$url?$pars");
	
}

if(strtoupper($action) == "SAVETIMETABLESLOT" || strtoupper($action) == "UPDATETIMETABLESLOT"){

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
    
}

if(strtoupper($action) == "SAVEYEAR"){
    
    $q = "SELECT * FROM schoollms_schema_userdata_school_year WHERE year_label LIKE '%$year%'";
    
    $data->execSQL($q);
    
    if($data->numrows == 0)
    {
       $q = "INSERT INTO schoollms_schema_userdata_school_year VALUES (NULL, '$year')";
       $data->execNonSql($q);
    }
    
    /*
     * Auto Create DATA for the following;
     *  1 - CLASSES
     *  2 - TIMETABLES
     *  3 - CALENDAR
     */
}

if(strtoupper($action) == "UPDATELEARNER"){
    
    timetable_audit_log($user_id, $action, $audit_data);
    
    $add_learner_string = "$learner_id#$subjects%$classes#$school_id#2#$user_id#$school_id, '$learner_id',2,'$name','$surname'";

    $user_id = timetable_save('new_user', $add_learner_string);

    if (isset($parent_id_1) && !empty($parent_id_1)){
        $add_parent_string = "$parent_id_1#3*$parent_name_1*$parent_surname_1#$school_id, '$parent_id_1',3,'$parent_name_1','$parent_surname_1'";
        $parent_1_user_id = timetable_save('new_user', $add_parent_string);

        $add_string = "$user_id#$parent_1_user_id";
    }

    if (isset($parent_id_2) && !empty($parent_id_2)){
        $add_parent_string = "$parent_id_2#3*$parent_name_2*$parent_surname_2#$school_id, '$parent_id_2',3,'$parent_name_2','$parent_surname_2'";
        $parent_2_user_id = timetable_save('new_user', $add_parent_string);

        $add_string .= ",$parent_2_user_id";
    }

    timetable_save('save_learner_parent', $add_string);
}

if(strtoupper($action) == "SAVELEARNER"){
    
    timetable_audit_log($user_id, $action, $audit_data);
    
    $grade_id = $next_grade;
    $baseline = $potential_score;
    $subjects = "TBA";
    $classes = "TBA";
    $user_id = 0;
    $add_learner_string = "$learner_id#$subjects%$classes#$school_id#2#$user_id#$school_id, '$learner_id',2,'$name','$surname'";

    $user_id = timetable_save('new_user', $add_learner_string);

    if (isset($parent_id_1) && !empty($parent_id_1)){
        $add_parent_string = "$parent_id_1#3*$parent_name_1*$parent_surname_1#$school_id, '$parent_id_1',3,'$parent_name_1','$parent_surname_1'";
        $parent_1_user_id = timetable_save('new_user', $add_parent_string);

        $add_string = "$user_id#$parent_1_user_id";
    }

    if (isset($parent_id_2) && !empty($parent_id_2)){
        $add_parent_string = "$parent_id_2#3*$parent_name_2*$parent_surname_2#$school_id, '$parent_id_2',3,'$parent_name_2','$parent_surname_2'";
        $parent_2_user_id = timetable_save('new_user', $add_parent_string);

        $add_string .= ",$parent_2_user_id";
    }

    timetable_save('save_learner_parent', $add_string);

//    if ($grade_id !== 0){
//        $learner_settings = "learner_settings#user_id=$user_id:baseline=$baseline,learner_average=$learner_average,subject_choice=$subject_choice,current_grade=$current_grade,current_class=$current_class,next_grade=$next_grade,next_class=$next_class";
//
//
//        $settings_string = "grade_id=$grade_id<number_of_learners=$number_of_learners|year_id=$year_id|$learner_settings";
//
//        echo "BEFORE SETTINGS $settings_string <br />";
//
//        $settings_string = timetable_settings_update($school_id, $settings_string, 'learner', $grade_id);
//
//        echo "AFTER SETTINGS $settings_string <br />";
//
//        timetable_settings_save($school_id, $settings_string, 'learner');
//    }
//
//    if ($next_grade !== 0){
//        //INSERT LEARNER CURRENT SETTINGS
//        $class = "Class $next_grade"."$next_class";
//
//         $q = "SELECT class_id FROM schoollms_schema_userdata_school_classes WHERE class_label = '$class'";
//        $result = sqlQuery($q);
//        $class_id = 0;
//        foreach ($result as $row){
//            $class_id = $row[0][0];
//            break;
//        }
//
//        $q = "INSERT INTO schoollms_schema_userdata_learner_schooldetails(user_id,school_id,grade_id,class_id,year_id) VALUES ($user_id, $school_id, $next_grade, $class_id, $year_id)";
//        $result = sqlQuery($q);
//    }
    
    
}

if (strtoupper($action) == "VIEWELEARNTRACKER"){
    
    ?>
    <div style="margin-top: 4px; padding-top: 1px;">
    <!--<h1 style="color: #428bca " style="margin-top: 10px; margin-bottom: 30px"><center>E-Learning Tracker</center></h1>-->
    <table id="table2" style="width:100%; border-collapse: separate; border-spacing: 3px 3px;">
    <!--		<colgroup>
                            <col width="6.25%"/>
                            <col width="12.5%"/>
                            <col width="12.5%"/>
                            <col width="12.5%"/>
                            <col width="12.5%"/>
                            <col width="12.5%"/>
                            <col width="12.5%"/>
                            <col width="12.5%"/>
                            <col width="12.5%"/>
                    </colgroup>-->
                    <tbody>
                            <tr width="100%">
                                    <td class="redips-mark blank" id="firstTd">
                                            <?php
                                                $startdate = null;
                                                if(isset($_GET["startdate"]))
                                                {
                                                    $startdate = $_GET["startdate"];
                                                }

                                                $today = null;
                                                if(isset($_GET["today"]))
                                                {
                                                    $today = $_GET["today"];
                                                }

                                                echo navigation_menu($school_id,$startdate,$today, $user_id, $data);
                                            ?>
                                    </td>
                                    <td width="100%">
                                        <div id="divHeader" style="overflow:hidden;width:100%;position:relative">
                                            <table width="100%" cellspacing="0" cellpadding="0" border="1" >
                                                <colgroup>
                                                        <!--<col width="6.25%"/>-->
                                                        <col width="11%"/>
                                                        <col width="11%"/>
                                                        <col width="11%"/>
                                                        <col width="11%"/>
                                                        <col width="11%"/>
                                                        <col width="11%"/>
                                                        <col width="11%"/>
                                                        <col width="12.5%"/>
                                                </colgroup>
                                                <tbody width="100%">
                                                    <tr width="100%">
                                                        <?php


                                                        echo periods($school_id,$startdate,$today) ?>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                            </tr>
                            <tr>
                                <td valign="top">
                                  <div id="firstcol" style="overflow: hidden;height:400px;position:relative">
                                    <table width="100%" cellspacing="0" cellpadding="0" border="1" >
                                        <colgroup>
                                            <col width="6.25%"/>
        <!--                                        <col width="12.5%"/>
                                                <col width="12.5%"/>
                                                <col width="12.5%"/>
                                                <col width="12.5%"/>
                                                <col width="12.5%"/>
                                                <col width="12.5%"/>
                                                <col width="12.5%"/>
                                                <col width="12.5%"/>-->
                                        </colgroup>
                                        <tbody>
                                            <?php  
                                            //PRINT DAYS
                                            $slots = print_days($id,$user_type,$user_id,$year_id,$school_id, $startdate,$today, $data);
                                            ?>
                                        </tbody>
                                    </table>
                                  </div>
                                </td>
                                <td valign="top">
                                    <div id="table_div" style="overflow:scroll;width:100%;height:400px;position:relative" onscroll="fnScroll()" >
                                        <table width="100%" cellspacing="0" cellpadding="0" border="1" >
                                            <colgroup>
                                                    <!--<col width="6.25%"/>-->
                                                    <col width="12.5%"/>
                                                    <col width="12.5%"/>
                                                    <col width="12.5%"/>
                                                    <col width="12.5%"/>
                                                    <col width="12.5%"/>
                                                    <col width="12.5%"/>
                                                    <col width="12.5%"/>
                                                    <col width="12.5%"/>
                                            </colgroup>
                                            <tbody>
                                                  <?php
                                                    //PRINT SLOTS
                                                    //echo "STARTDATE $startdate TODAY $today<br>";
                                                    foreach ($slots as $key => $row) {
                                                        print $row;
                                                    }

                                                    //print_days($id,$user_type,$user_id,$year_id,$school_id, $startdate,$today);
                                                    /*if(isset($user_id)){
                                                            print_days($id,$user_type,$user_id,$year_id);
                                                    }
                                                    else{
                                                            print_days($id,$user_type);
                                                    }*/

                                                     ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                    </tbody>
            </table>
    </div>
<?php
}

if(strtoupper($action) == "LOGIN"){
        timetable_audit_log($user_id, $action, $audit_data);
        
	$year = date("Y");
	$sql = "select * from schoollms_schema_userdata_school_year where year_label = '$year'";
	$data->execSQL($sql);
	$year_id = 0;
	if($row = $data->getRow())
	{
		$year_id = $row->year_id;
	}
	
	$pars = "FAILED";
	$q = "SELECT * FROM schoollms_schema_userdata_access_profile WHERE access_id = '$access_id' AND name = '$name' AND surname = '$surname'";
	$data->execSQL($q);
	if($row = $data->getRow()){
		$school_id = $row->school_id;
		$user_id = $row->user_id;
		$user_type = $row->type_id;
		$pars = "viewtimetable.php?school_id=$school_id&user_type=$user_type&user_id=$user_id&year_id=$year_id&id=22";
	}
	
        //$data = $pars;
	//timetable_audit_log($user_id, $action, $data);
	echo $pars;
}

if(strtoupper($action) == "GETSCHOOLINFO")
{
	$sql = "select * from schoollms_schema_userdata_schools where school_id = $school_id";
	$data->execSQL($sql);
	$result = array();
	while($row = $data->getRow())
	{
		$result = $row;
	}
	
	echo json_encode($result);
}

if(strtoupper($action) == "BUILDREPORTS")
{
    $q = "SELECT ";
    
}

if(strtoupper($action) == "GETREPORTS")
{
    $q = "SELECT ";
    
}

if(strtoupper($action) == "GETTEACHER")
{
	$sql = "select * from schoollms_schema_userdata_access_profile
		where
		  user_id = $id";
	$data->execSQL($sql);
	$result = array();
	while($row = $data->getRow())
	{
		$result[] = $row;
	}
	echo json_encode($result);
	
}

if(strtoupper($action) == "GETPERSONNEL")
{
	
	$sql = "select * from schoollms_schema_userdata_access_profile
		where
		  type_id = $type_id and school_id = $school_id";
	//echo $sql;
	$data->execSQL($sql);
	$result = array();
	while($row = $data->getRow())
	{
		$result[] = $row;
	}
	
	echo "{ \"total\": \"".$data->numrows."\",  \"rows\":";
	echo json_encode($result);
	echo "}";
}

if(strtoupper($action) == "GETPERSONNEL2")
{
	//echo "We are here";
	$page = isset($_POST['page']) ? intval($_POST['page']) : 1;
	$rows = isset($_POST['rows']) ? intval($_POST['rows']) : 10;
	$sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'access_id';
	$order = isset($_POST['order']) ? strval($_POST['order']) : 'asc';
	$offset = ($page-1)*$rows;
	
	$sql = "select count(*) num
		from schoollms_schema_userdata_learner_schooldetails sd
		join schoollms_schema_userdata_school_grades sg on sg.grade_id = sd.grade_id
		join schoollms_schema_userdata_school_classes sc on sc.class_id = sd.class_id
		join schoollms_schema_userdata_access_profile ap on ap.user_id = sd.user_id
		where
		  type_id = $type_id and sg.grade_id = $grade_id and sd.year_id = $year_id and ap.school_id = $school_id ";
	//echo $sql;
	$data->execSQL($sql);
	
	$result = array();
	if($row=$data->getRow())
	{
		$result["total"] = $row->num;
	}
	
	$sql = "select ap.*, sg.grade_title, sg.grade_id,sc.class_label, sc.class_id 
		from schoollms_schema_userdata_learner_schooldetails sd
		join schoollms_schema_userdata_school_grades sg on sg.grade_id = sd.grade_id
		join schoollms_schema_userdata_school_classes sc on sc.class_id = sd.class_id
		join schoollms_schema_userdata_access_profile ap on ap.user_id = sd.user_id
		where
		  type_id = $type_id and sg.grade_id = $grade_id and sd.year_id = $year_id  and ap.school_id = $school_id  order by $sort $order limit $offset,$rows";
		  // and sg.grade_id  <= $end_grade_id 
	$data->execSQL($sql);
	//echo $sql;
	$items = array();
	while($row = $data->getRow())
	{
		$items[] = $row;
	}
	$result["rows"] = $items;
	
	//echo "{ \"total\": \"".$data->numrows."\",  \"rows\":";
	echo json_encode($result);
	//echo "}";
}

if(strtoupper($action) == "GETPERSONNEL3")
{
	$sql = "select ap.*, sg.grade_title, sg.grade_id,sc.class_label, sc.class_id 
		from schoollms_schema_userdata_learner_schooldetails sd
		join schoollms_schema_userdata_school_grades sg on sg.grade_id = sd.grade_id
		join schoollms_schema_userdata_school_classes sc on sc.class_id = sd.class_id
		join schoollms_schema_userdata_access_profile ap on ap.user_id = sd.user_id
		where
		  type_id = $type_id and  sd.user_id = $user_id";
	$data->execSQL($sql);
	$result = array();
	if($row= $data->getRow())
	{
		$result = $row;
	}
	echo json_encode($result);
}

if(strtoupper($action) == "GETSCHOOLSETTING")
{
	$sql = "select settings from schoollms_schema_userdata_timetable_settings where school_id = $school_id";
	$data->execSQL($sql);
	$result = array();
	$strresult = "";
	while($row = $data->getRow())
	{
		//echo $row->settings;
		$expl = explode("|",$row->settings);
		for($i = 0;$i<count($expl);$i++)
		{
			
			$result[] = $expl[$i];
			$strresult .= "{\"". str_replace("=","\":\"",$expl[$i])."\"} ,";
		}
	}
	//$strresult = "[".substr($strresult,0,strlen($strresult) -2)."]";
	//echo $strresult;
	//var_dump($result);
	echo json_encode($result);
}

class SubjectSettings{
	var $subject_info;
	var $subject_id;
	var $grade_setting;
}

if(strtoupper($action) == 'GETSCHOOLYEAR'){
    
}


if(strtoupper($action) == 'GETSCHOOLSUBJECTS'){
    
}

if(strtoupper($action) == 'GETSCHOOLTEACHERS'){
    
}

if(strtoupper($action) == 'GETSCHOOLCLASSES'){
    
}

if(strtoupper($action) == 'GETSCHOOLLEARNERS'){
    
}

if(strtoupper($action) == 'GETSCHOOLPARENTS'){
    
}



if(strtoupper($action) == 'GETSCHOOLGRADES'){
    //YEAR
    $q = "SELECT * FROM schoollms_schema_userdata_school_year";
    $result = $data->exec_sql($q, "array");

    $select_year .= "<option value=0> Select Year To View Year Stats <option>";

    if ($data->numrows > 0){
        foreach($result as $key=>$row){
            $year = $row['year_title'];
            $year_id = $row['year_id'];
            $select_year .= "<option value=$year_id> $year <option>";
        }
    }

    //GRADE
    $q = "SELECT * FROM schoollms_schema_userdata_school_grades";
    $result = $data->exec_sql($q, "array");

    $select_grade .= "<option value=0> Select Grade To View Grade Stats <option>";

    if ($data->numrows > 0){
        foreach($result as $key=>$row){
            $grade_title = $row['grade_title'];
            $grade_id = $row['grade_id'];
            //GET SCHOOL GRADES FROM SETTINGS

            $select_grade .= "<option value=$grade_id> $grade_title <option>";
        }
    }

    //SUBJECT
    $q = "SELECT * FROM schoollms_schema_userdata_school_subjects";
    $result = $data->exec_sql($q, "array");

    $select_subject .= "<option value=0> Select Subject To View Subject Stats <option>";

    if ($data->numrows > 0){
        foreach($result as $key=>$row){
            $subject = $row['subject_title'];
            $subject_id = $row['subject_id'];
            //GET SCHOOL GRADES FROM SETTINGS

            $select_subject .= "<option value=$subject_id> $subject_title <option>";
        }
    }

    //TEACHER
    $q = "SELECT * FROM schoollms_schema_userdata_access_profile WHERE school_id = 1 AND type_id = 4 order by surname ASC";
    $result = $data->exec_sql($q, "array");

    $select_teacher .= "<option value=0> Select Teacher To View Teacher Stats <option>";

    if ($data->numrows > 0){
        foreach($result as $key=>$row){
            if (is_numeric($row["access_id"])){
                $user_id = $row['user_id'];
                $surname = $row["surname"];
                $name = $row["name"];
                //GET TEACHER WHO IS TEACHING THIS YEAR
                $q = "SELECT * FROM schoollms_schema_userdata_teacher_schooldetails WHERE user_id = $user_id AND school_id = $school_id AND year_id = $year_id";
                $teacher_result = $data->execSQL($q);
                if ($data->numrows > 0){
                    $select_teacher .= "<option value=$user_id> $surname $name <option>";
                }
            }
        }
    }

    //CLASS
    $q = "SELECT * FROM schoollms_schema_userdata_school_classes WHERE school_id = 1 AND year_id = $year_id";
    $result = $data->exec_sql($q, "array");

    $select_class .= "<option value=0> Select Class To View Class Stats <option>";

    if ($data->numrows > 0){
        foreach($result as $key=>$row){
            $class_id = $row["class_id"];
            $class_label = $row["class_label"];
            $select_class .= "<option value=$class_id> $class_label <option>";
        }
    }

    //LEARNERS
    $q = "SELECT * FROM schoollms_schema_userdata_access_profile WHERE school_id = 1 AND type_id = 2 order by surname ASC";
    $result = $data->exec_sql($q, "array");

    $select_learner .= "<option value=0> Select Learner To View Learner Stats <option>";

    if ($data->numrows > 0){
        foreach($result as $key=>$row){
            //if (is_numeric($row["access_id"])){
                $user_id = $row['user_id'];
                $surname = $row["surname"];
                $name = $row["name"];
                //GET TEACHER WHO IS TEACHING THIS YEAR
                $q = "SELECT * FROM schoollms_schema_userdata_learner_schooldetails WHERE user_id = $user_id AND school_id = $school_id AND year_id = $year_id";
                $teacher_result = $data->execSQL($q);
                if ($data->numrows > 0){
                    $select_learner .= "<option value=$user_id> $surname $name <option>";
                }
           // }
        }
    }
}

if(strtoupper($action) == "GETSUBJECTSETTINGS")
{
	$sql = "select * from schoollms_schema_userdata_timetable_subject_settings where school_id = $school_id";
	$data->execSQL($sql);
	$result = array();
	
	while($row = $data->getRow())
	{
		$dat = explode(";",$row->subject_settings);
		//$dat = explode(";","subject_id=11<color=ab2567|period_type=random|period_times=random|grade_setting#grade_id=8:notional_time=Type Notional Time per Week in Hours,period_cycle=5,minimum_learners=Type ideal number of learners required per class;subject_id=18<color=|period_type=random|period_times=random|grade_setting#grade_id=10:grade_subject_color=ab2567,notional_time=Type Notional Time per Week in Hours,period_cycle=5,minimum_learners=Type ideal number of learners required per class;subject_id=15<color=|period_type=random|period_times=random|grade_setting#grade_id=10:grade_subject_color=ab2567,notional_time=Type Notional Time per Week in Hours,period_cycle=5,minimum_learners=Type ideal number of learners required per class");
		for($i = 0; $i < count($dat);$i++)
		{
			//echo "one <br/>";
			$subjectSettngs =new SubjectSettings();
			$subdata = explode("<",$dat[$i]);
			$subjectdata = explode("=", $subdata[0]);
			$subjectSettngs->subject_id =$subjectdata; 
			$sub = explode("#",$subdata[1]);
			$subjectSettngs->subject_info = explode("|",$sub[0]);
			$subjectSettngs->grade_setting = $sub[1];
			if($subject_id == $subjectdata[1])
			{
				$result[] = $subjectSettngs;
			}
		}
		
	}
	
	echo json_encode($result);
}

if(strtoupper($action) == "GETVENUES")
{
	if(!isset($school_id))
	{
		$school_id = 1;
	}
	$sql = "select * from schoollms_schema_userdata_school_building_rooms
		where room_type = $type and school_id = $school_id";
	$data->execSQL($sql);
	$result = array();
	
	while($row = $data->getRow())
	{
		$result[] = $row;
	}
	echo json_encode($result);
}

if(strtoupper($action) == "GETCLASSES")
{
	$sql = "select * from schoollms_schema_userdata_school_classes where school_id = $school_id and year_id = $year_id";
	$data->execSQL($sql);
	$result = array();
	
	while($row = $data->getRow())
	{
		$result[] = $row;
	}
	echo json_encode($result);
}

if(strtoupper($action) == "GETGRADECLASSES")
{
    if ($grade_id == "All"){    
        $sql = "select * from schoollms_schema_userdata_school_classes where school_id = $school_id and year_id = $year_id order by class_label asc";
    } else {
        $sql = "select * from schoollms_schema_userdata_school_classes where school_id = $school_id and grade_id = $grade_id and year_id = $year_id order by class_label asc";
    }
    $data->execSQL($sql);
    $result = array();

    while($row = $data->getRow())
    {
        //CHECK IF CLASS EXISTS IN LMS
        //IF TRUE SKIP
        //ELSE ADD TO LMS
        
        $result[] = $row;
    }
    echo json_encode($result);
}

if(strtoupper($action) == "GETGRADECLASSLIST")
{
    $return = "";
    //echo "if ($class_id == 'All' && $grade_id == 'All'){<br>";
    
    if ($class_id == "All" && $grade_id == "All"){
        $sql = "select * from schoollms_schema_userdata_school_classes where school_id = $school_id and year_id = $year_id order by class_label asc";
        //echo "SQL $sql";
        $result = $data->exec_sql($sql, "array");
        //var_dump($result);
        foreach ($result as $key => $row) {
            $grade_id = $row["grade_id"];
            $class_id = $row["class_id"];
            $class_label = $row["class_label"];
            
            $teacher = getclassteacher($data, $class_id, $year_id);
            $user_id = $teacher[0];
            $teacher_name = $teacher[1];
            $num_teachers = $teacher[2];
            if ($num_teachers == 1){
                $return .= "<tr><th><center> <b>CLASS ID:</b> $class_id <b>CLASS:</b> $class_label <b>CLASS TEACHER: </b>$teacher_name  <br><button name='bntAddLearners' id='bntAddLearners' onClick='addLearnersToClass($grade_id,$class_id)'>Add Learners</button><button name='bntViewTimetable' id='bntViewTimetable' onClick='viewTimetable($user_id, $school_id, 4)'>View Teacher Timetable</button><button name='bntModifyTeacher' id='bntModifyTeacher' onClick='modifyClassTeacher($grade_id, $class_id, $user_id)'>Modify Class Teacher</button><button name='bntRemoveTeacher' id='bntRemoveTeacher' onClick='removeTeacherClass($grade_id, $class_id, $user_id)'>Remove Teacher From Class</button></center></th></tr>";
            } elseif ($num_teachers > 1) {
                $teacher_list = $teacher[3];
                $return .= "<tr><th><center> <b>CLASS ID:</b> $class_id <b>CLASS:</b> $class_label <b>CLASS TEACHER: </b>$teacher_name  <br><button name='bntAddLearners' id='bntAddLearners' onClick='addLearnersToClass($grade_id,$class_id)'>Add Learners</button><button name='bntSelectClassTeacher' id='bntSelectClassTeacher' onClick='selectClassTeacher($teacher_list, $class_id)'>Select Class Teacher</button></center></th></tr>";
            } else {
                $return .= "<tr><th><center> <b>CLASS ID:</b> $class_id <b>CLASS:</b> $class_label <b>CLASS TEACHER: </b>$teacher_name  <br><button name='bntAddLearners' id='bntAddLearners' onClick='addLearnersToClass($grade_id,$class_id)'>Add Learners</button><button name='bntAddClassTeacher' id='bntAddClassTeacher' onClick='addClassTeacher($class_id)'>Add Class Teacher</button></center></th></tr>";
            }
        
            //CHECK IF TEACHER EXISTS IN CLASS IN LMS
            //IF TRUE SKIP
            //ELSE ADD TEACHER TO CLASS IN LMS
            
       //echo "CLASS ID $class_id Q $q<br>";
            $sql = "select DISTINCT p.*
               from schoollms_schema_userdata_learner_schooldetails sd
               join schoollms_schema_userdata_school_classes sc on sc.class_id = sd.class_id and sc.year_id = sd.year_id
               join schoollms_schema_userdata_access_profile p on p.user_id = sd.user_id
               where sd.class_id = $class_id and sd.year_id = $year_id and p.type_id = 2 order by sd.grade_id, sc.class_label, p.surname asc";
            
            //echo "SQL ALL $sql <br><br>";
            $return = getgradeclasslist($data, $sql, $grade_id, $class_id, $return);

        }
   } elseif ($class_id == "All"){
       $sql = "select * from schoollms_schema_userdata_school_classes where school_id = $school_id and grade_id = $grade_id and year_id = $year_id order by class_label asc";
       $result = $data->exec_sql($sql, "array");
       foreach($result as $key=>$row){
           $class_id = $row["class_id"];
           $class_label = $row["class_label"];
           
           $teacher = getclassteacher($data, $class_id, $year_id);
            $user_id = $teacher[0];
            $teacher_name = $teacher[1];
            $num_teachers = $teacher[2];
            if ($num_teachers == 1){
                $return .= "<tr><th><center> <b>CLASS ID:</b> $class_id <b>CLASS:</b> $class_label <b>CLASS TEACHER: </b>$teacher_name  <br><button name='bntAddLearners' id='bntAddLearners' onClick='addLearnersToClass($grade_id,$class_id)'>Add Learners</button><button name='bntViewTimetable' id='bntViewTimetable' onClick='viewTimetable($user_id, $school_id, 4)'>View Teacher Timetable</button><button name='bntRemoveTeacher' id='bntRemoveTeacher' onClick='removeTeacherClass($grade_id, $class_id, $user_id)'>Remove Teacher From Class</button></center></th></tr>";
            } elseif ($num_teachers > 1) {
                $teacher_list = $teacher[3];
                $return .= "<tr><th><center> <b>CLASS ID:</b> $class_id <b>CLASS:</b> $class_label <b>CLASS TEACHER: </b>$teacher_name  <br><button name='bntAddLearners' id='bntAddLearners' onClick='addLearnersToClass($grade_id,$class_id)'>Add Learners</button><button name='bntSelectClassTeacher' id='bntSelectClassTeacher' onClick='selectClassTeacher($teacher_list, $class_id)'>Select Class Teacher</button></center></th></tr>";
            } else {
                $return .= "<tr><th><center> <b>CLASS ID:</b> $class_id <b>CLASS:</b> $class_label <b>CLASS TEACHER: </b>$teacher_name  <br><button name='bntAddLearners' id='bntAddLearners' onClick='addLearnersToClass($grade_id,$class_id)'>Add Learners</button><button name='bntAddClassTeacher' id='bntAddClassTeacher' onClick='addClassTeacher($class_id)'>Add Class Teacher</button></center></th></tr>";
            }
        

        //echo "CLASS ID $class_id Q $q<br>";
            $sql = "select DISTINCT p.*
               from schoollms_schema_userdata_learner_schooldetails sd
               join schoollms_schema_userdata_school_classes sc on sc.class_id = sd.class_id
               join schoollms_schema_userdata_access_profile p on p.user_id = sd.user_id
               where sd.class_id = $class_id and sd.year_id = $year_id and p.type_id = 2 order by sd.grade_id, sc.class_label, p.surname asc";

            //echo "SQL GRADEID $sql <br><br>";
            $return = getgradeclasslist($data, $sql, $grade_id, $class_id, $return);

       }
   } else {
       $sql = "select * from schoollms_schema_userdata_school_classes where school_id = $school_id and class_id = $class_id and year_id = $year_id order by class_label asc";
       $data->execSQL($sql);
       $row = $data->getRow();
       $grade_id = $row->grade_id;
       $class_label = $row->class_label;
        $teacher = getclassteacher($data, $class_id, $year_id);
        $user_id = $teacher[0];
        $teacher_name = $teacher[1];
        $num_teachers = $teacher[2];
        if ($num_teachers == 1){
            $return .= "<tr><th><center> <b>CLASS ID:</b> $class_id <b>CLASS:</b> $class_label <b>CLASS TEACHER: </b>$teacher_name  <br><button name='bntAddLearners' id='bntAddLearners' onClick='addLearnersToClass($grade_id,$class_id)'>Add Learners</button><button name='bntViewTimetable' id='bntViewTimetable' onClick='viewTimetable($user_id, $school_id, 4)'>View Teacher Timetable</button><button name='bntRemoveTeacher' id='bntRemoveTeacher' onClick='removeTeacherClass($grade_id, $class_id, $user_id)'>Remove Teacher From Class</button></center></th></tr>";
        } elseif ($num_teachers > 1) {
            $teacher_list = $teacher[3];
            $return .= "<tr><th><center> <b>CLASS ID:</b> $class_id <b>CLASS:</b> $class_label <b>CLASS TEACHER: </b>$teacher_name  <br><button name='bntAddLearners' id='bntAddLearners' onClick='addLearnersToClass($grade_id,$class_id)'>Add Learners</button><button name='bntSelectClassTeacher' id='bntSelectClassTeacher' onClick='selectClassTeacher($teacher_list, $class_id)'>Select Class Teacher</button></center></th></tr>";
        } else {
            $return .= "<tr><th><center> <b>CLASS ID:</b> $class_id <b>CLASS:</b> $class_label <b>CLASS TEACHER: </b>$teacher_name  <br><button name='bntAddLearners' id='bntAddLearners' onClick='addLearnersToClass($grade_id,$class_id)'>Add Learners</button><button name='bntAddClassTeacher' id='bntAddClassTeacher' onClick='addClassTeacher($class_id)'>Add Class Teacher</button></center></th></tr>";
        }

    //echo "CLASS ID $class_id Q $q<br>";
        $sql = "select DISTINCT p.*
               from schoollms_schema_userdata_learner_schooldetails sd
               join schoollms_schema_userdata_school_classes sc on sc.class_id = sd.class_id
               join schoollms_schema_userdata_access_profile p on p.user_id = sd.user_id 
               where sd.class_id = $class_id and sd.year_id = $year_id and p.type_id = 2 order by sd.grade_id, sc.class_label, p.surname asc";

        //echo "SQL CLASSID $sql <br><br>";
        $return = getgradeclasslist($data, $sql, $grade_id, $class_id, $return);
  
    }
    
    echo $return;
   //echo json_encode($result);
   
}

        
if(strtoupper($action) == "SELECTMERITS")
{
    timetable_audit_log($user_id, $action, $audit_data);
    
    $q = "SELECT * FROM schoollms_schema_userdata_school_merits WHERE school_id = $school_id";
    
    $data->execSQL($q);
    
    $return = "<tr><th> Merits </th></tr>"
            . "<tr><td>"
            . "<input type='hidden' name='date' value='$date'>"
            . "<input type='hidden' name='day' value='$day'>"
            . "<input type='hidden' name='period' value='$period'>"
            . "<input type='hidden' name='teacher_id' value='$teacher_id'>"
            . "<input type='hidden' name='learner_id' value='$learner_id'>"
            . "<select name='merit_id' size=15 multiple>";
    $count = 1;
    while($row = $data->getRow())
   {
        
       $merit_id = $row->merit_id;
       $merit = strtoupper($row->merit);
       $merit = str_replace('^', ' ', $merit);
       
//       if ($count == 1){
//           $return .= "<table border=2><tr><td></td><th>ID No.:</th><th>Full Name</th></tr>";
//       }
       $return .= "<option value='$merit_id'>$merit </option>";
   }
   $return .= "</select></td> </tr> ";
   
   echo $return;
} 


if(strtoupper($action) == "SELECTDEMERITS")
{
    timetable_audit_log($user_id, $action, $audit_data);
    
    $q = "SELECT * FROM schoollms_schema_userdata_school_demerits WHERE school_id = $school_id";
    
    $data->execSQL($q);
    
    $return = "<tr><th> Demerits </th></tr>"
            . "<tr><td>"
            . "<input type='hidden' name='date' value='$date'>"
            . "<input type='hidden' name='day' value='$day'>"
            . "<input type='hidden' name='period' value='$period'>"
            . "<input type='hidden' name='teacher_id' value='$teacher_id'>"
            . "<input type='hidden' name='learner_id' value='$learner_id'>"
            . "<select name='demerit_id' size=15 multiple>";
    $count = 1;
    while($row = $data->getRow())
   {
        
       $demerit_id = $row->demerit_id;
       $demerit = strtoupper($row->demerit);
       $demerit = str_replace('^', ' ', $demerit);
       
//       if ($count == 1){
//           $return .= "<table border=2><tr><td></td><th>ID No.:</th><th>Full Name</th></tr>";
//       }
       $return .= "<option value='$demerit_id'>$demerit </option>";
   }
   $return .= "</select></td> </tr> ";
   
   echo $return;
} 

if(strtoupper($action) == "VIEWLEARNERMERITS")
{
    timetable_audit_log($user_id, $action, $audit_data);
    
    $q = "SELECT * FROM schoollms_schema_userdata_access_profile WHERE user_id = $learner_id";
    $data->execSQL($q);
    $row = $data->getRow();
    $name = $row->name;
    $surname = $row->surname;
    $learner = strtoupper("$surname, $name");
    $learner = str_replace("^", " ", $learner);
    
    $return = "<tr><th> $learner </th></tr>";
    
    $q = "SELECT * FROM schoollms_schema_userdata_learner_merits WHERE user_id = $learner_id";
    
    $data->execSQL($q);
    
    $numrows = $data->numrows;
    
    if ($numrows > 0){
        $row = $data->getRow();
        $merit_data = $row->merit_data;
        if (strpos($merit_data, "#")){
            $merit_data_tokens = explode("#", $merit_data);
        } else {
            $merit_data_tokens = array ("$merit_data");
        }
        $return .= "<tr><td><table border=2><tr><th>Date </th><th>Period Day </th><th>Period </th><th>Teacher </th><th>Merit Point </th><th>Merit </th></tr>";
        foreach ($merit_data_tokens as $key=>$item){
            $item_tokens = explode("~", $item);
            $merit_id = $item_tokens[0];
            //GET MERIT
            $q = "SELECT * FROM schoollms_schema_userdata_school_merits WHERE merit_id = $merit_id";
            $data->execSQL($q);
            $row = $data->getRow();
            $merit_point = $row->merit_point;
            $merit = $row->merit;
            
            $date = $item_tokens[1];
            $day = $item_tokens[2];
            $period_label_id = $item_tokens[3];
            //GET PERIOD LABEL
            $q = "SELECT * FROM schoollms_schema_userdata_school_timetable_period_labels WHERE period_label_id = $period_label_id";
            $data->execSQL($q);
            $row = $data->getRow();
            $period = $row->period_label;
            
            $teacher_id = $item_tokens[4];
            //GET TEACHER DETAILS
            $q = "SELECT * FROM schoollms_schema_userdata_access_profile WHERE user_id = $teacher_id";
            $data->execSQL($q);
            $row = $data->getRow();
            $name = $row->name;
            $surname = $row->surname;
            $teacher = strtoupper("$name $surname");
            $teacher = str_replace("^", " ", $teacher);
            $return .= "<tr><td>$date</td><td>$day</td><td>$period</td><td>$teacher</td><td>$merit_point</td><td>$merit</td></tr>";
        }
        $return .= "</table></td></tr>";
        
    } else {
        $return .= "<tr><td><b> The learner has no merits </b></td></tr>"; 
    }
    
    echo $return;
}

if(strtoupper($action) == "GETTEACHERVENUE")
{
    $q = "SELECT * schoollms_schema_userdata_teacher_venue WHERE user_id = $user_id AND year_id = $year_id";
    $data->execSQL($q);
    if ($data->numrows > 0){
        $row = $data->getRow();
        echo $row->venue_id;
    } else {
        echo 0;
    }
}

if(strtoupper($action) == "SAVECLASSVENUE")
{
    
    if (isset($user_id)){//SAVE TEACHER CLASS VENUE
        $q = "SELECT * FROM schoollms_schema_userdata_teacher_venue WHERE user_id = $user_id AND year_id = $year_id";
        $data->execSQL($q);
        if ($data->numrows > 0){
            $q = "UPDATE schoollms_schema_userdata_teacher_venue SET venue_id = $room_id WHERE user_id = $user_id AND  year_id = $year_id";
            $data->execNonSql($q);
            echo "EXISTING Teacher Venue Updated";
        } else {
            
            $q = "INSERT INTO schoollms_schema_userdata_teacher_venue VALUES ($user_id, $room_id, $year_id)";
            $data->execNonSql($q);
            echo "NEW Teacher Venue Saved";
        }
        
        
    } else {//SAVE CLASS VENUE
        $q = "SELECT * FROM schoollms_schema_userdata_class_venue WHERE class_id = $class_id AND year_id = $year_id";
        $data->execSQL($q);
        if ($data->numrows > 0){
            $q = "UPDATE schoollms_schema_userdata_class_venue SET venue_id = $room_id WHERE class_id = $class_id AND  year_id = $year_id";
            $data->execNonSql($q);
        } else {
            $q = "INSERT INTO schoollms_schema_userdata_class_venue VALUES ($class_id, $room_id, $year_id)";
            $data->execNonSql($q);
        }
        echo "Class Venue Saved";
    }
}

if(strtoupper($action) == "GETEVENTS")
{
    timetable_audit_log($user_id, $action, $audit_data);
    
    $return = "";
    
    $q = "SELECT * FROM schoollms_schema_userdata_events WHERE event_owner_type =8 AND event_owner_id = $school_id";
    //echo "Q $q <br>";
    $data->execSQL($q);
    if ($data->numrows > 0){
        $return .= "<tr><th><b> EventID </b></th><th><b> Title </b></th><th><b> When </b></th><th><b> Where </b></th> <th><b> Action </b></th></tr>";
        while ($row = $data->getRow()){
            $event_id = $row->event_id;
            $event_title = $row->event_title;
            $event_date = $row->event_date;
            $event_locations = $row->event_locations;
            //GET ALL LOCATIONS FROM schoollms_schema_userdata_event_locations
            $locations = "";
            if (!is_null($event_locations)){
                $location_tokens = explode(",", $locations);
                foreach ($location_tokens as $key => $location_id) {
                    
                }
            } else {
                $locations = "<button name='bntManageVenue' id='bntManageVenue' onClick='manageVenue($event_id)'>Manage Venue/Location</button>";
            }
            $return .= "<tr><td> $event_id </td><td> $event_title </td><td>$event_date </td><td>$locations</td><td><button name='bntManageEvent' id='bntManageEvent' onClick='manageEvent($event_id)'>Manage Event</button><button name='bntManageTeam' id='bntManageTeam' onClick='manageTeam($event_id)'>Manage Team</button><button name='bntManageRegisterForm' id='bntManageRegisterForm' onClick='manageRegisterForm($event_id)'>Manage Registration Form</button><button name='bntViewRegister' id='bntViewRegister' onClick='viewEventRegister($event_id)'>View Register</button><button name='bntBuildEventReport' id='bntBuildEventReport' onClick='buildEventReport($event_id)'>Build Report</button></td></tr>";

        }

    }
    
    echo $return;
}

if(strtoupper($action) == "SAVEEVENTREGISTER")
{
    timetable_audit_log($user_id, $action, $audit_data);
    
    $fields = explode("-", $fields);
    $table_fields = "";
    $insert_values = ""
            ;
    $table = "schoollms_schema_userdata_event_"."$event_id"."_register";
    $db = $data->db;
    $q = "SELECT * FROM information_schema.tables "
            . "WHERE table_schema = '$db'"
            . " AND table_name = '$table'";
    //echo "CHECK Q $q <br>";
    $data->execSQL($q);
    $num = $data->numrows;
    echo "NUM $num <br>";
    
    if ($data->numrows == 0){
        $table_fields .= "register_id int auto_increment,";
        $insert_values .= "NULL,";
        foreach ($fields as $key => $field) {
            if ($field == 'event_id'){
                continue;
            }
            
            $table_fields .= "$field longblob,";
            $value = mysql_escape_string($_GET["$field"]);
            $insert_values .= "'$value',";
        }
        $table_fields .= "PRIMARY KEY(register_id)";
        $table_fields = rtrim($table_fields, ",");
        $insert_values = rtrim($insert_values, ",");
        //CREATE EVENT TABLE
         $q = "CREATE TABLE {$table} ($table_fields)";
         echo "CREATE Q $q <br>";
         $data->execNonSql($q);
    } else {
        $insert_values .= "NULL,";
        foreach ($fields as $key => $field) {
            if ($field == 'event_id'){
                continue;
            }
            
            $value = mysql_escape_string($_GET["$field"]);
            $insert_values .= "'$value',";
        }
        $insert_values = rtrim($insert_values, ",");
    }
    
    $q = "insert into $table values ($insert_values)";
    echo "INSERT Q $q <br>";
    $data->execNonSql($q);
    
}

if(strtoupper($action) == "VIEWLEARNERDEMERITS")
{
    timetable_audit_log($user_id, $action, $audit_data);
    
    //GET LEARNER DETAILS
    $q = "SELECT * FROM schoollms_schema_userdata_access_profile WHERE user_id = $learner_id";
    $data->execSQL($q);
    $row = $data->getRow();
    $name = $row->name;
    $surname = $row->surname;
    $learner = strtoupper("$surname, $name");
    $learner = str_replace("^", " ", $learner);
    
    $return = "<tr><th> $learner </th></tr>";
    
    $q = "SELECT * FROM schoollms_schema_userdata_learner_demerits WHERE user_id = $learner_id";
    
    $data->execSQL($q);
    
    $numrows = $data->numrows;
    
    if ($numrows > 0){
        $row = $data->getRow();
        $demerit_data = $row->demerit_data;
        if (strpos($demerit_data, "#")){
            $demerit_data_tokens = explode("#", $demerit_data);
        } else {
            $demerit_data_tokens = array ("$demerit_data");
        }
        $return .= "<tr><td><table border=2><tr><th>Date </th><th>Period Day </th><th>Period </th><th>Teacher </th><th>Demerit Point </th><th>Demerit </th></tr>";
        foreach ($demerit_data_tokens as $key=>$item){
            $item_tokens = explode("~", $item);
            $demerit_id = $item_tokens[0];
            //GET MERIT
            $q = "SELECT * FROM schoollms_schema_userdata_school_demerits WHERE demerit_id = $demerit_id";
            $data->execSQL($q);
            $row = $data->getRow();
            $demerit_point = $row->demerit_point;
            $demerit = $row->demerit;
            
            $date = $item_tokens[1];
            $day = $item_tokens[2];
            $period_label_id = $item_tokens[3];
            //GET PERIOD LABEL
            $q = "SELECT * FROM schoollms_schema_userdata_school_timetable_period_labels WHERE period_label_id = $period_label_id";
            $data->execSQL($q);
            $row = $data->getRow();
            $period = $row->period_label;
            
            $teacher_id = $item_tokens[4];
            //GET TEACHER DETAILS
            $q = "SELECT * FROM schoollms_schema_userdata_access_profile WHERE user_id = $teacher_id";
            $data->execSQL($q);
            $row = $data->getRow();
            $name = $row->name;
            $surname = $row->surname;
            $teacher = strtoupper("$name $surname");
            $teacher = str_replace("^", " ", $teacher);
            $return .= "<tr><td>$date</td><td>$day</td><td>$period</td><td>$teacher</td><td>$demerit_point</td><td>$demerit</td></tr>";
        }
        $return .= "</table></td></tr>";
    } else {
        $return .= "<tr><td><b> The learner has no merits </b></td></tr>"; 
    }
    
    echo $return;
}

if(strtoupper($action) == "ADDLEARNERMERITS")
{
    timetable_audit_log($user_id, $action, $audit_data);

    $merit_data = "$merit_id~$date~$day~$period~$teacher_id";
    
    //Check IF LEARNER ALREADY HAS MERITS
    
    $q = "SELECT * FROM schoollms_schema_userdata_learner_merits WHERE user_id = $learner_id";
    
    $data->execSQL($q);
    
    $numrows = $data->numrows;
    
    if ($numrows == 0){
        //Get Merit Points
        $q = "SELECT * FROM schoollms_schema_userdata_school_merits WHERE merit_id = $merit_id";
        $data->execSQL($q);
        $row = $data->getRow();
        $merit_points = $row->merit_point;
        //Add Learner Merit
        $q = "INSERT INTO schoollms_schema_userdata_learner_merits VALUES ($learner_id, $merit_points, '$merit_data')";
        $data->execNonSql($q);
    } else {
        
        $row = $data->getRow();
        $merit_points = $row->merit_points;
        $merit_data2 = $row->merit_data;
        
        $merit_data_tokens = explode("#", $merit_data2);
        $add_data = TRUE;
        foreach ($merit_data_tokens as $key => $items) {
            if ($items == $merit_data){
                $add_data = FALSE;
                break;
            }
        }
        
        if ($add_data){
            $q = "SELECT * FROM schoollms_schema_userdata_school_merits WHERE merit_id = $merit_id";
            $data->execSQL($q);
            $row = $data->getRow();
            $merit_points += $row->merit_point;
            $merit_data2 .= "#$merit_data";
            $q = "UPDATE schoollms_schema_userdata_learner_merits SET merit_points = $merit_points, merit_data = '$merit_data2' WHERE user_id = $learner_id";
            $data->execNonSql($q);
        }
            
    }
}

if(strtoupper($action) == "ADDLEARNERDEMERITS")
{
    timetable_audit_log($user_id, $action, $audit_data);

    $demerit_data = "$demerit_id~$date~$day~$period~$teacher_id";
    
    //Check IF LEARNER ALREADY HAS MERITS
    
    $q = "SELECT * FROM schoollms_schema_userdata_learner_demerits WHERE user_id = $learner_id";
    
    $data->execSQL($q);
    
    $numrows = $data->numrows;
    
    if ($numrows == 0){
        //Get Merit Points
        $q = "SELECT * FROM schoollms_schema_userdata_school_demerits WHERE demerit_id = $demerit_id";
        $data->execSQL($q);
        $row = $data->getRow();
        $demerit_points = $row->demerit_point;
        //Add Learner Merit
        $q = "INSERT INTO schoollms_schema_userdata_learner_demerits VALUES ($learner_id, $demerit_points, '$demerit_data')";
        $data->execNonSql($q);
    } else {
        
        $row = $data->getRow();
        $demerit_points = $row->demerit_points;
        $demerit_data2 = $row->demerit_data;
        
        $demerit_data_tokens = explode("#", $demerit_data2);
        $add_data = TRUE;
        foreach ($demerit_data_tokens as $key => $items) {
            if ($items == $demerit_data){
                $add_data = FALSE;
                break;
            }
        }
        
        if ($add_data){
            $q = "SELECT * FROM schoollms_schema_userdata_school_demerits WHERE demerit_id = $demerit_id";
            $data->execSQL($q);
            $row = $data->getRow();
            $demerit_points += $row->demerit_point;
            $demerit_data2 .= "#$demerit_data";
            $q = "UPDATE schoollms_schema_userdata_learner_demerits SET demerit_points = $demerit_points, demerit_data = '$demerit_data2' WHERE user_id = $learner_id";
            $data->execNonSql($q);
        }
            
    }
}

if(strtoupper($action) == "SELECTTEACHER")
{
    timetable_audit_log($user_id, $action, $audit_data);
    
    $q = "SELECT * FROM schoollms_schema_userdata_access_profile WHERE school_id = $school_id AND type_id = 4 AND NOT access_id = '' order by surname, name asc";
    $data->execSQL($q);
    
    $return = "<tr><th> Select Teacher </th></tr>"
            . "<tr><td><input type='hidden' name='add_grade_id' value='$grade_id'><input type='hidden' name='add_class_id' value='$class_id'><select name='teacher_id'>";
    $count = 1;
    while($row = $data->getRow())
   {
        
       $user_id = $row->user_id;
       $access_id = $row->access_id;
       $name = strtoupper($row->name);
       $name = str_replace('^', ' ', $name);
       $surname = strtoupper($row->surname);
       $surname = str_replace('^', ' ', $surname);
       
//       if ($count == 1){
//           $return .= "<table border=2><tr><td></td><th>ID No.:</th><th>Full Name</th></tr>";
//       }
       $return .= "<option value='$user_id'>$access_id &emsp; &emsp; $surname, $name </option>";
   }
   $return .= "</select></td> </tr> ";
   
   echo $return;
}

if(strtoupper($action) == "MODIFYCLASSTEACHER")
{
    
    timetable_audit_log($user_id, $action, $audit_data);
    
    //GET subject_id 
    $q = "SELECT * FROM schoollms_schema_userdata_teacher_schooldetails WHERE user_id = $teacher_id AND school_id = $school_id AND year_id = $year_id AND grade_id = $grade_id AND class_id = $class_id";
    $data->execSQL($q);
    $row = $data->getRow();
    $subject_id = $row->subject_id;
    
    //GET NEW TEACHER room_id
    $q = "SELECT * FROM schoollms_schema_userdata_teacher_venue WHERE user_id = $user_id AND year_id = $year_id";
    $data->execSQL($q);
    if ($data->numrows > 0){
        $row = $data->getRow();
        $room_id = $row->venue_id;
            
        $q = "UPDATE schoollms_schema_userdata_school_timetable_items SET teacher_id = $teacher_id2, room_id = $room_id WHERE grade_id = $grade_id AND class_id = $class_id AND teacher_id = $teacher_id AND subject_id = $subject_id";
        $data->execNonSql($q);
        
        $q = "SELECT * FROM schoollms_schema_userdata_teacher_schooldetails WHERE user_id = $teacher_id2 AND school_id = $school_id AND year_id = $year_id AND grade_id = $grade_id AND class_id = $class_id AND subject_id = $subject_id";
        
        $data->execSQL($q);
        if ($data->numrows == 0){
            //CHECK IF SUBJECT CLASS OR REGISTER CLASS
            
            //DELETE FROM CLASS
           $q = "DELETE FROM schoollms_schema_userdata_teacher_schooldetails WHERE user_id = $teacher_id AND school_id = $school_id AND year_id = $year_id AND grade_id = $grade_id AND class_id = $class_id AND subject_id = $subject_id";
            $data->execNonSql($q);
            
            $q = "INSERT INTO schoollms_schema_userdata_teacher_schooldetails VALUES ($teacher_id2, $school_id,$grade_id,$class_id,$subject_id,$year_id)";
            $data->execNonSql($q);
            
            //UPDATE TIMETABLE ITEMS
            $q = "SELECT * FROM schoollms_schema_userdata_school_timetable WHERE school_id = $school_id AND timetable_type_id = 2 AND timetable_type_item_id = $teacher_id";
            $data->execSQL($q);
            if ($data->numrows > 0){
                $row = $data->getRow();
                $timetable_id = $row->timetabl_id;
                $q = "DELETE FROM schoollms_schema_userdata_school_timetable_items WHERE timetabl_id = $timetabl_id AND grade_id = $grade_id AND class_id = $class_id AND teacher_id = $teacher_id AND subject_i = $subject_id";
                $data->execNonSql($q);

            }
        }

    } 
}

if(strtoupper($action) == "REMOVETEACHERCLASS")
{
    
    timetable_audit_log($user_id, $action, $audit_data);
    //DELETE FROM CLASS
   $q = "DELETE FROM schoollms_schema_userdata_teacher_schooldetails WHERE user_id = $teacher_id AND school_id = $school_id AND year_id = $year_id AND grade_id = $grade_id AND class_id = $class_id";
    
    $data->execNonSql($q);
    
    //DELETE FROM TIMETABLE ITEMS
    $q = "DELETE FROM schoollms_schema_userdata_school_timetable_items WHERE grade_id = $grade_id AND class_id = $class_id AND teacher_id = $teacher_id";
    $data->execNonSql($q);
    
}


if(strtoupper($action) == "ADDTEACHERTOCLASS")
{

    timetable_audit_log($user_id, $action, $audit_data);
    
    $grade_id = $add_grade_id;
    $class_id = $add_class_id;
    
    //Check IF LEARNER ALREADY EXISTS IN CLASS
    $q = "SELECT * FROM schoollms_schema_userdata_teacher_schooldetails WHERE user_id = $teacher_id AND school_id = $school_id AND year_id = $year_id AND grade_id = $grade_id AND class_id = $class_id";
    
    
    
    $data->execSQL($q);
    
    $numrows = $data->numrows;
    
    //echo "NUMROWS $numrows SQL $q <br>";
    
    if ($numrows == 0){
        //Add Learner to Class
        $q = "INSERT INTO schoollms_schema_userdata_teacher_schooldetails VALUES ($teacher_id, $school_id, $grade_id, $class_id, 0, $year_id)";
        $data->execNonSql($q);
        
        //Get Class Timetable
        
        //Add to Teacher Timetable
        
        //Add Teacher to Class in LMS
        
    } else {
        //echo "Teacher Already Exists";
    }
}

if(strtoupper($action) == "SELECTLEARNERS")
{
    timetable_audit_log($user_id, $action, $audit_data);
    
    $q = "SELECT * FROM schoollms_schema_userdata_access_profile WHERE school_id = $school_id AND type_id = 2 order by surname, name asc";
    $data->execSQL($q);
    
    $return = "<tr><th> Select Learners </th></tr>"
            . "<tr><td><input type='hidden' name='add_grade_id' value='$grade_id'><input type='hidden' name='add_class_id' value='$class_id'><select name='learner_id' size=15 multiple>";
    $count = 1;
    while($row = $data->getRow())
   {
        
       $user_id = $row->user_id;
       $access_id = $row->access_id;
       $name = strtoupper($row->name);
       $name = str_replace('^', ' ', $name);
       $surname = strtoupper($row->surname);
       $surname = str_replace('^', ' ', $surname);
       
//       if ($count == 1){
//           $return .= "<table border=2><tr><td></td><th>ID No.:</th><th>Full Name</th></tr>";
//       }
       $return .= "<option value='$user_id'>$access_id &emsp; &emsp; $surname, $name </option>";
   }
   $return .= "</select></td> </tr> ";
   
   echo $return;
} 

if(strtoupper($action) == "REMOVELEARNERCLASS")
{
    
    timetable_audit_log($user_id, $action, $audit_data);
    //DELETE FROM CLASS
   $q = "DELETE FROM schoollms_schema_userdata_learner_schooldetails WHERE user_id = $learner_id AND school_id = $school_id AND year_id = $year_id AND grade_id = $grade_id AND class_id = $class_id";
    
    $data->execNonSql($q);
    
    //DELETE FROM TIMETABLE ITEMS
    //GET LEARNER TIMETABLE
}

if(strtoupper($action) == "ADDLEARNERTOCLASS")
{

    timetable_audit_log($user_id, $action, $audit_data);
    
    $grade_id = $add_grade_id;
    $class_id = $add_class_id;
    
    //Check IF LEARNER ALREADY EXISTS IN CLASS
    $q = "SELECT * FROM schoollms_schema_userdata_learner_schooldetails WHERE user_id = $learner_id AND school_id = $school_id AND year_id = $year_id AND grade_id = $grade_id AND class_id = $class_id";
    
    
    
    $data->execSQL($q);
    
    $numrows = $data->numrows;
    
    //echo "NUMROWS $numrows SQL $q <br>";
    
    if ($numrows == 0){
        //Add Learner to Class
        $q = "INSERT INTO schoollms_schema_userdata_learner_schooldetails VALUES ($learner_id, $school_id, $grade_id, $class_id, $year_id)";
        $data->execNonSql($q);
        
        //Get Class Timetable
        
        //Add to Learner Timetable
        
        //Add Learner to Class in LMS
        
    } else {
        //echo "Learner Already Exists";
    }
}

if(strtoupper($action) == "GETCLASSLIST")
{
    $q = "SELECT timetable_type_item_id FROM schoollms_schema_userdata_school_timetable WHERE school_id = $school_id AND timetabl_id = $timetable_id AND timetable_type_id = 3";
    $data->execSQL($q);
                        
    while($row = $data->getRow())
   {
	  $class_id = $row->timetable_type_item_id;
   }    
    
       
   //echo "CLASS ID $class_id Q $q<br>";
    
   $sql = "select *
   from schoollms_schema_userdata_learner_schooldetails sd
   join schoollms_schema_userdata_access_profile p on p.user_id = sd.user_id
   where sd.class_id = $class_id and year_id = case when $year_id = 'All' then year_id else $year_id  end order by p.surname asc";
   $data->execSQL($sql);
   $result = array();
   while($row = $data->getRow())
   {
	   $result[] = $row;
   }
   
   echo json_encode($result);
   
}

if(strtoupper( $action) == "RESETTEACHERTIMETABLE" )
{
    //CHECK THE TIMETABLE ID IN LEARNER TIMETABLE
    $q = "SELECT * FROM schoollms_schema_userdata_teacher_timetable WHERE user_id = $user_id AND year_id = $year_id"; 
    //echo "Q1 $q <br>";
    $data->execSQL($q);
    if ($data->numrows == 1){
        $row = $data->getRow();
        $timetable_id = $row->timetabl_id;
        $q = "SELECT * FROM schoollms_schema_userdata_school_timetable WHERE timetabl_id = $timetable_id AND timetable_type_id = 1 AND timetable_type_item_id = $user_id";
        //echo "Q2 $q <br>";
        $data->execSQL($q);
        if ($data->numrows == 1){        
           $q = "DELETE FROM schoollms_schema_userdata_school_timetable_items WHERE timetabl_id = $timetable_id";
           //echo "Q3 $q <br>";
           $data->execNonSql($q);
           
           echo TRUE;
        } elseif ($data->numrows == 0) {
            $q = "DELETE FROM schoollms_schema_userdata_teacher_timetable WHERE user_id = $user_id AND year_id = $year_id";
          //echo "Q4 $q <br>";
           $data->execNonSql($q);
           
           echo TRUE;
        } else {
            echo FALSE;
        }
    } elseif ($data->numrows > 1){
        $q = "DELETE FROM schoollms_schema_userdata_teacher_timetable WHERE user_id = $user_id AND year_id = $year_id";
          //echo "Q5 $q <br>";
           $data->execNonSql($q);
           
           echo TRUE;
    } elseif ($data->numrows == 0){    
        echo TRUE;
    } else {
        echo FALSE;
    }
    echo TRUE;
}

if(strtoupper( $action) == "RESETLEARNERTIMETABLE" )
{
    //CHECK THE TIMETABLE ID IN LEARNER TIMETABLE
    $q = "SELECT * FROM schoollms_schema_userdata_learner_timetable WHERE user_id = $user_id and year_id = $year_id"; 
    //echo "Q1 $q <br>";
    $data->execSQL($q);
    if ($data->numrows == 1){
        $row = $data->getRow();
        $timetable_id = $row->timetabl_id;
        $q = "SELECT * FROM schoollms_schema_userdata_school_timetable WHERE timetabl_id = $timetable_id AND timetable_type_id = 1 AND timetable_type_item_id = $user_id";
        //echo "Q2 $q <br>";
        $data->execSQL($q);
        if ($data->numrows == 1){        
           $q = "DELETE FROM schoollms_schema_userdata_school_timetable_items WHERE timetabl_id = $timetable_id";
           //echo "Q3 $q <br>";
           $data->execNonSql($q);
           
           echo TRUE;
        } elseif ($data->numrows == 0) {
            $q = "DELETE FROM schoollms_schema_userdata_learner_timetable WHERE user_id = $user_id AND year_id = $year_id";
          //echo "Q4 $q <br>";
           $data->execNonSql($q);
           
           echo TRUE;
        } else {
            echo FALSE;
        }
    } elseif ($data->numrows > 1){
        $q = "DELETE FROM schoollms_schema_userdata_learner_timetable WHERE user_id = $user_id AND year_id = $year_id";
          //echo "Q5 $q <br>";
           $data->execNonSql($q);
           
           echo TRUE;
    } elseif ($data->numrows == 0){    
        echo TRUE;
    } else {
        echo FALSE;
    }
}

if(strtoupper( $action) == "GETSUBJECTS" )
{
	$sql = "select * from schoollms_schema_userdata_school_subjects order by subject_title asc";
	$data->execSQL($sql);
	$result = array();
	while($row = $data->getRow())
	{
		//$result[] = $row->subject_title;
		$result[] = $row;
	}
	echo json_encode($result);
}

if(strtoupper( $action) == "TIMETABLESLOT" )
{
	$result= "";
	$sql = "select concat(tbl_row,'_',tbl_col) as pos, timetabl_id, slot_code, slot_details
						from schoollms_schema_userdata_school_timetable_view
						where concat(tbl_row,'_',tbl_col) = '$rowcol' and slot_code = '$slot_code'";
	
	$data->execSQL($sql);
	if($row = $data->getRow())
	{
		$result = $row->slot_details;
	}
	
	$data = explode("<br>",$result);
	if(count($data) == 4)
	{
		echo "<table border=1>";
		echo "<tr><td>Subject</td><td>$data[0]</td></tr>";
		echo "<tr><td>Teacher</td><td>$data[1]</td></tr>";
		echo "<tr><td>Classroom</td><td>$data[2]</td></tr>";
		echo "<tr><td>Substitutes</td><td>$data[3]</td></tr>";
		echo "</table";
	}
	
	echo json_encode($result);	
}

if(strtoupper( $action) == "GETSLOTCLASSLIST" )
{
	$class = str_replace(" ","%",$class);
	$sql = "select access_id, name, surname
   from schoollms_schema_userdata_learner_schooldetails sd
   join schoollms_schema_userdata_access_profile p on p.user_id = sd.user_id
   join schoollms_schema_userdata_school_classes sc on sc.class_id = sd.class_id
   where class_label like '%$class%'";
   $data->execSQL($sql);
	$result = array();
	while($row = $data->getRow())
	{
		$result[] = $row;
	}
	echo json_encode($result);
}


if(strtoupper($action) == "GETGRADES")
{
	$sql = "select * from schoollms_schema_userdata_school_grades where grade_id >= $from and grade_id <= $to";
	$data->execSQL($sql);
	$result = array();
	while($row = $data->getRow())
	{
//		foreach($result as $key=>$value)
//		{
//			if($value == $row)
//			{
//				continue;
//			}
//			else{
				$result[] = $row;
//			}
//		}
		
	}
	echo json_encode($result);
}

if(strtoupper($action) == "GETSTUDENTCLASS")
{
	$sql = "SELECT * FROM schoollms_schema_userdata_school_classes where grade_id = $grade_id";
	$data->execSQL($sql);
	$result = array();
	while($row = $data->getRow())
	{
		$result[] = $row;
	}
	echo json_encode($result);
}

if(strtoupper($action) == "GETSTUDENTCLASS2")
{
	if(strtoupper(str_replace(" ","",$letters)) == "A-Z")
	{
		$letters = "A,B,C,D,E,F,G,H,I,J,K,L,M,N,O,P,Q,R,S,T,U,V,W,X,Y,Z";
	}
	
	$classes = explode(",",$letters);
	$result = "[";
	for($i = 0; $i < count($classes);$i++)
	{
		$result .= "{\"class_id\":\"$classes[$i]\",\"class_label\":\"$classes[$i]\"},";
	}
	$result = substr($result,0,strlen($result) -1);
	$result .= "]";
	//echo json_encode($result);
	echo $result;
}

if(strtoupper($action) == "PRINT_DAYS")
{ 
	//var_dump($_GET);
timetable_audit_log($user_id, $action, $audit_data);
?>
    <table id="table2" style="width:100%; border-collapse: separate; border-spacing: 3px 3px;">
    <!--		<colgroup>
                            <col width="6.25%"/>
                            <col width="12.5%"/>
                            <col width="12.5%"/>
                            <col width="12.5%"/>
                            <col width="12.5%"/>
                            <col width="12.5%"/>
                            <col width="12.5%"/>
                            <col width="12.5%"/>
                            <col width="12.5%"/>
                    </colgroup>-->
                    <tbody>
                            <tr width="100%">
                                    <td class="redips-mark blank" id="firstTd">
                                            <?php
                                                $startdate = null;
                                                if(isset($_GET["startdate"]))
                                                {
                                                    $startdate = $_GET["startdate"];
                                                }

                                                $today = null;
                                                if(isset($_GET["today"]))
                                                {
                                                    $today = $_GET["today"];
                                                }

                                                echo navigation_menu($school_id,$startdate,$today, $user_id, $data);
                                            ?>
                                    </td>
                                    <td width="100%">
                                        <div id="divHeader" style="overflow:hidden;width:100%;position:relative">
                                            <table width="100%" cellspacing="0" cellpadding="0" border="1" >
                                                <colgroup>
                                                        <!--<col width="6.25%"/>-->
                                                        <col width="11%"/>
                                                        <col width="11%"/>
                                                        <col width="11%"/>
                                                        <col width="11%"/>
                                                        <col width="11%"/>
                                                        <col width="11%"/>
                                                        <col width="11%"/>
                                                        <col width="12.5%"/>
                                                </colgroup>
                                                <tbody width="100%">
                                                    <tr width="100%">
                                                        <?php


                                                        echo periods($school_id,$startdate,$today) ?>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </td>
                            </tr>
                            <tr>
                                <td valign="top">
                                  <div id="firstcol" style="overflow: hidden;height:370px;position:relative">
                                    <table width="100%" cellspacing="0" cellpadding="0" border="1" >
                                        <colgroup>
                                            <col width="6.25%"/>
        <!--                                        <col width="12.5%"/>
                                                <col width="12.5%"/>
                                                <col width="12.5%"/>
                                                <col width="12.5%"/>
                                                <col width="12.5%"/>
                                                <col width="12.5%"/>
                                                <col width="12.5%"/>
                                                <col width="12.5%"/>-->
                                        </colgroup>
                                        <tbody>
                                            <?php  
                                            //PRINT DAYS
                                            $slots = print_days($id,$user_type,$user_id,$year_id,$school_id, $startdate,$today, $data, $parent_id);
                                            ?>
                                        </tbody>
                                    </table>
                                  </div>
                                </td>
                                <td valign="top">
                                    <div id="table_div" style="overflow:scroll;width:100%;height:400px;position:relative" onscroll="fnScroll()" >
                                        <table width="100%" cellspacing="0" cellpadding="0" border="1" >
                                            <colgroup>
                                                    <!--<col width="6.25%"/>-->
                                                    <col width="12.5%"/>
                                                    <col width="12.5%"/>
                                                    <col width="12.5%"/>
                                                    <col width="12.5%"/>
                                                    <col width="12.5%"/>
                                                    <col width="12.5%"/>
                                                    <col width="12.5%"/>
                                                    <col width="12.5%"/>
                                            </colgroup>
                                            <tbody>
                                                  <?php
                                                    //PRINT SLOTS
                                                    //echo "STARTDATE $startdate TODAY $today<br>";
                                                    foreach ($slots as $key => $row) {
                                                        print $row;
                                                    }

                                                    //print_days($id,$user_type,$user_id,$year_id,$school_id, $startdate,$today);
                                                    /*if(isset($user_id)){
                                                            print_days($id,$user_type,$user_id,$year_id);
                                                    }
                                                    else{
                                                            print_days($id,$user_type);
                                                    }*/

                                                     ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                    </tbody>
            </table>
    </div>
<!--	<table id="table2" style="width:100%; border-collapse: separate; border-spacing: 3px 3px;">
						<colgroup>
							<col width="6.25%"/>
							<col width="12.5%"/>
							<col width="12.5%"/>
							<col width="12.5%"/>
							<col width="12.5%"/>
							<col width="12.5%"/>
                            <col width="12.5%"/>
                            <col width="12.5%"/>
                            <col width="12.5%"/>
						</colgroup>
						<tbody>
							<tr>
								 if checkbox is checked, clone school subjects to the whole table row  
								<td class="redips-mark blank">
									<input id="week" type="checkbox" title="Apply school subjects to the week" checked/>
									<input id="report" type="checkbox" title="Show subject report"/>
								</td>
                                <?php //echo periods($school_id) ?>
							<td class="redips-mark dark">Monday</td>
								<td class="redips-mark dark">Tuesday</td>
								<td class="redips-mark dark">Wednesday</td>
								<td class="redips-mark dark">Thursday</td>
								<td class="redips-mark dark">Friday</td>
								

							</tr>
                                                      
								<?php  
								
								//print_days($id,$user_type,$user_id,$year_id,$school_id);
								/*if(isset($user_id)){
									print_days($id,$user_type,$user_id,$year_id);
								}
								else{
									print_days($id,$user_type);
								}*/

								 ?>
						</tbody>
					</table>-->
	
	<?php
}

if(strtoupper($action) == "GETSUBJECTTEACHER")
{
	$sql = "SELECT distinct *, concat(name, ' ', surname) teacher_name from schoollms_schema_userdata_access_profile"
                . " where user_id in (select user_id from schoollms_schema_userdata_teacher_schooldetails"
                . " where school_id = $school_id and subject_id = $subject_id and year_id = $year_id)";
	
        //echo "SQL $sql<br>";
	$data->execSQL($sql);
	$result = array();
	while($row = $data->getRow())
	{
		$result[] = $row;
	}
	echo json_encode($result);
	
}

if (strtoupper($action) == "GRADESELECT")
{
    $q = "SELECT * FROM schoollms_schema_userdata_school_grades WHERE school_id = $school_id AND year_id = $year_id";
    $data->execSQL($q);
    $result = array();
    while($row = $data->getRow())
    {
        $result[] = $row;
    }
    echo json_encode($result);
}

if(strtoupper($action) == "TIMETABLESELECT" )
{
       // $q = "SELECT * FROM schoollms_schema_userdata_school_classes  sc join SELECT * FROM schoollms_schema_userdata_school_timetable st WHERE sc.school_id = $school_id AND sc.grade_id = '$timetable_label' AND timetable_type_id IN (3,8) AND sc.class_id = timetable_type_item_id order by class_label";
        $q =  "SELECT distinct st.timetabl_id, st.timetable_label FROM schoollms_schema_userdata_school_classes sc join schoollms_schema_userdata_school_timetable st on sc.school_id = 1 AND sc.year_id = $year_id AND sc.grade_id = '$timetable_label' AND sc.class_id = timetable_type_item_id AND st.timetable_type_id IN (3,8) order by class_label";
// IN (SELECT timetable_type_item_id FROM schoollms_schema_userdata_school_timetable WHERE school_id = $school_id AND timetable_type_id IN (3,8)) ORDER BY class_label ASC";

        $data->execSQL($q);
  /*      $in = "(''";
        while ($row = $data->getRow()){
                $class_label = $row->class_label;
                $class_id = $row->class_id;
                //$in .= ", '$class_label'";
                $in .= ", $class_id";
        }
        $in .= ")";


	$sql = "select * from schoollms_schema_userdata_school_timetable where school_id = $school_id AND timetable_type_id IN (3,8)";
	if(isset($timetable_label))
	{
		//$sql .= " and TRIM(timetable_label) in $in OR timetable_type_item_id in $in2";
		$sql .= " and timetable_type_item_id in $in";
		$sql .= " order by timetable_label";
	}	
	
	$data->execSQL($sql);

        $numrows = $data->numrows;*/
       
	$result = array();
	while($row = $data->getRow())
	{
		$result[] = $row;
	}
	echo json_encode($result);
}


if(strtoupper($action) == "GETIMAGE")
{
	$ctype = "image/png";
	switch( $file_extension ) {
		case "gif": $ctype="image/gif"; break;
		case "png": $ctype="image/png"; break;
		case "jpeg":
		case "jpg": $ctype="image/jpeg"; break;
		default:
	}
	header('Content-type: ' . $ctype);
	$sql = "select * from schoollms_schema_userdata_user_photo where user_id = '$user_id'";
	$imagedb->execSQL($sql);
	$result = array();
    while($row = $imagedb->getRow())
    {
	   //$result[] = $row->photo;
	   echo base64_decode($row->photo);
	   break;
    } 
	
    echo json_encode($result);
}

if(strtoupper($action) == "GETSTUDENTPARENT")
{
	$sql = "SELECT * FROM schoollms_schema_userdata_learner_parent";
	$data->execSQL($sql);
	$result = array();
	while($row = $data->getRow())
	{
		$result[] = $row;
	}
	echo json_encode($result);
}

if(strtoupper($action) == "GETCLASSTEACHER")
{
	$sql = "select group_concat(DISTINCT i.substitude_id ORDER BY i.substitude_id DESC SEPARATOR ',') substitude_ids,
		name,surname,access_id, user_id
		from schoollms_schema_userdata_school_timetable_items i
		join schoollms_schema_userdata_access_profile p on p.user_id = i.teacher_id
		where
		  class_id =(SELECT timetable_type_item_id 
			FROM schoollms_schema_userdata_school_timetable 
			WHERE timetabl_id = $class_id
			AND timetable_type_id = 3
			limit 0,1)
		group by name,surname,access_id";
	$data->execSQL($sql);
	$result = array();
	while($row = $data->getRow())
	{
		$result[] = $row;
	}
	echo json_encode($result);
}
if(strtoupper($action) == "TIMETABLEDAYS")
{
	$sql = "select * from schoollms_schema_userdata_school_timetable_days";
	$data->execSQL($sql);
	$result = array();
	while($row = $data->getRow())
	{
		$result[] = $row;
	}
	echo json_encode($result);
}
if(strtoupper($action) == "GETROOMS")
{
	$sql = "select * from schoollms_schema_userdata_school_building_rooms where school_id = $school_id";
	$data->execSQL($sql);
	$result = array();
	while($row = $data->getRow())
	{
		$result[] = $row;
	}
	echo json_encode($result);
}

if(strtoupper($action) == "GETPERIODS")
{
	$sql = "select * from schoollms_schema_userdata_school_timetable_period
	where sChoOl_Id = $school_id";
	$sql = "select l.* ,period_start, period_end
	from schoollms_schema_userdata_school_timetable_period_labels l
	join schoollms_schema_userdata_school_timetable_period p on p.period_label_id = l.period_label_id and l.school_id = p.school_id
	where l.school_id = $school_id
	and week_day_id = 7
	order by UNIX_TIMESTAMP(concat('2016-01-01',' ',period_start))  asc";
	$data->execSQL($sql);
	$result = array();
	while($row = $data->getRow())
	{
		$result[] = $row;
	}
	echo json_encode($result);
}

if(strtoupper($action) == "GETPERIODLABELS")
{
	$sql = "select * from schoollms_schema_userdata_school_timetable_period_labels where school_id = $school_id";
	$data->execSQL($sql);
	$result = array();
	while($row = $data->getRow())
	{
		$result[] = $row;
	}
	echo json_encode($result);
}
if(strtoupper($action) == "GETTIMETABLEID")
{
	$sql = "select * from schoollms_schema_userdata_school_timetable where timetable_type_item_id = $id";
	$data->execSQL($sql);
	$result = "";
	if($row = $data->getRow())
	{
		$result = $row->timetabl_id;
	}
	echo $result;
}

function tokenize($value,$year_id =0)
{
	$tokenizers = array(":","%","!",",");
	//$value = "user_id=1513:subject_id=5%grade_id=8!number_periods=54&grade_id=10!number_periods=18*subject_id=3%grade_id=8!number_periods=54&grade_id=10!number_periods=18,substitute=1";
	$result = array();
	
	$values = explode("*",$value);
	$subjects = array();
	$subjectGrades = array();
	$teach = new teacher();
	for($i=0;$i<count($values);$i++)
	{
		$subjects[$i] = new clSubject();
		$values[$i] = explode("&",$values[$i]);
		for($j=0;$j<count($values[$i]);$j++)
		{
			$subjectGrades[$j] = new subjectGrade();
			$value = $values[$i][$j];
			//echo "Value = ". $value ;
			//echo "<br/>";
			for($z=0;$z<count($tokenizers);$z++)
			{	
				
				//echo "<hr/> $tokenizers[$i] $value <hr/>";
				$res = explode($tokenizers[$z],$value);
				
				//if(count($res) ==2)
				//{
					$data =  explode("=",$res[0]);
					//echo "<hr/>";
					//echo $res[0];
					//echo "<br/>";
					//var_dump($data);
					//echo "<hr/>";
					switch($data[0])
					{
						case "user_id" :
						{
							$teach->user_id = $data[1];
							break;
						}
						case "subject_id" :
						{
							//$subjects[$i] = new clSubject();
							$subjects[$i]->subject_id = $data[1];;
							break;
						}
						
						case "grade_id" :
						{
							$subjectGrades[$j]->grade_id = $data[1];
							break;
						}
						case "number_periods" :
						{
							//var_dump($data);
							//echo "<br/><hr/>";
							$subjectGrades[$j]->number_periods = $data[1];
							break;
						}
						case "substitute" :
						{
							$teach->substitute = $data[1];
							break;
						}
					}
					$result[] = $res[0];
					if(count($res) ==2)
					{
						$value = $res[1];
					}
				
				//}
				//echo "$tokenizers[$i] -- $value<br/>";
				$ii = $i;
			}
			$res = explode($tokenizers[count($tokenizers)-1],$value);
			$dt = explode("=",$res[0]);
			$teach->substitute = $dt[1];
			$subjects[$i]->subjectsGrades[] =$subjectGrades[$j];
		}
		
		$teach->subject[] = $subjects[$i];		
		$teach->year_id = $year_id;
		$sql = "select * from schoollms_schema_userdata_access_profile where user_id = $teach->user_id";
		global $data1;
		$data1->execSQL($sql);
		$result = array();
		
		if($row = $data1->getRow())
		{
			$teach->teacher_name = $row->name ." ".$row->surname;
		}
		return $teach;
	//echo json_encode($teach); 
	//exit;
	}
	
}

function explodetoAssoc($variable,$explodeby = "|",$assocdelimiter= "=") 
{ 
    //echo "$variable is the variable ";
	$list = explode($explodeby,$variable); 
	$theresult = array();
	foreach($list as $key=>$value)
	{
		$data = explode($assocdelimiter,$value); 
		$theresult[$data[0]] = $data[1]; 
	}
	/*$result = array(); 
	for($i=0;$i< count($list);$i++) 
	{ 
		var_dump($list);
		$data = explode(@assocdelimiter,$list[$i]); 
		$result[$data[0]] = $data[1]; 
	} */
	//var_dump($result["classletters"]);
	//echo "<hr/>";
	return $theresult; 
}

if (strtoupper($action) == "GETSUBJECTTERMS"){
    
    $q = "SELECT type_title FROM schoollms_schema_userdata_school_calendar_type WHERE type_title LIKE  '%Term%'";
    //echo "Q $q <br>";
            //var_dump($data);
    $data->execSQL($q);
    $terms = array ();
    while($row = $data->getRow()){
        $term = $row->type_title;
        $term_tokens = explode("^", $term);
        $term_no = $term_tokens[1];
        $terms [] = $term_no;
    }

    $subject_terms = array ();
    $subject_url_tokens = explode (' ', $subject_name);
    $subject_url = $subject_url_tokens[0];
    foreach ($terms as $key=>$term_no){

        $subs = explode("/",$subject_name);
        //var_dump($subs);
        $moreSQL = "";
        $q = "";
        if(count($subs)  > 1)
        {
            for($i=0;$i<count($subs);$i++)
            {
                    $moreSQL .= " upper(n.title) like upper('%$subs[$i]%$grade_no%') or";
            }
            $moreSQL = substr($moreSQL,0,strlen($moreSQL)-2);
            $q = "select n.title, n.nid, concat('Term ',$term_no,' - ',n2.title) as title, quiz_nid, alias from node n "
                . "join opigno_quiz_app_quiz_sort o on o.gid = n.nid "
                . "join node n2 on n2.nid = quiz_nid "
                . "join url_alias u on u.source like concat('%',quiz_nid) "
                . "where ($moreSQL)";
        } else {
            $q = "select n.title, n.nid, concat('Term ',$term_no,' - ',n2.title) as title, quiz_nid, alias from node n "
                . "join opigno_quiz_app_quiz_sort o on o.gid = n.nid "
                . "join node n2 on n2.nid = quiz_nid "
                . "join url_alias u on u.source like concat('%',quiz_nid) "
                . "where upper(n.title) like upper('%$subject_name%$grade_no%$term_no%') AND u.alias LIKE '%$subject_url%' AND NOT u.alias LIKE '%file%'";
        }

        //echo "TERM Q $q <br>";
        //echo $q;
        $data2->execSQL($q);

        if($row2 = $data2->getRow()){
            do {
                                //echo "WTF";
                $subject_terms[] = $row2;
            } while($row2 = $data2->getRow());
        } else {

            //CREATE SUBJECT TERMS AND RETURN NEW IDS
           /* define('DRUPAL_ROOT', getcwd());
            require_once DRUPAL_ROOT.'/includes/bootstrap.inc';
            drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
            global $user;
            $account = user_authenticate($_REQUEST["user"], $_REQUEST["passwd"]);
            $user = user_load($account, TRUE);
            drupal_session_regenerate();

            $node = new stdClass();
            $node->type = 'course';
            node_object_prepare($node);

            $node->title = "$key - ".date('YmdHis'); //. date('c');
            $node->language = LANGUAGE_NONE;

            /*
             * field_data_course_pretest_ref                          |
            | field_data_course_pretest_ref_sync                     |
            | field_data_course_quota                                |
            | field_data_course_quota_sync                           |
            | field_data_course_required_course_ref                  |
            | field_data_course_required_course_ref_sync             |
            | field_data_course_required_quiz_ref                    |
            | field_data_course_required_quiz_ref_sync               |
            | field_data_opigno_class_courses                        |
            | field_data_opigno_class_courses_sync                   |
            | field_data_opigno_course_categories                    |
            | field_data_opigno_course_categories_sync               |
            | field_data_opigno_course_image                         |
            | field_data_opigno_course_image_sync                    |
            | field_data_opigno_course_tools                         |
            | field_data_opigno_course_tools_sync
             * 
             *
            $node->opigno_course_tools[$node->language][0]['field']['tool'] = '';
            $node->field_track_position[$node->language][0]['field']['lon'] = (double) $longi;
            $node->field_track_position[$node->language][0]['field']['map_height'] = $height;
            $node->field_track_position[$node->language][0]['field']['map_width'] = $width;
            $node->field_track_position[$node->language][0]['field']['zoom'] = $zoom;
            $node->field_track_position[$node->language][0]['field']['name'] = "$key - ".date('YmdHis');*/
        }
    }

    echo json_encode($subject_terms);
}

if (strtoupper($action) == "PUBLISHLESSON"){

    $q = "SELECT subject_id FROM schoollms_schema_userdata_school_subjects WHERE subject_title = '$subject'";
      
//    echo "Q $q <br>\n";

    $data->execSQL($q);
    $row = $data->getRow();
    $subject_id = $row->subject_id;
//    echo "<hr/>$subject_id<hr/>";
    $class = str_replace(",","%",$class);
    $q = "SELECT class_id FROM schoollms_schema_userdata_school_classes WHERE class_label LIKE '%$class%' and school_id = $school_id and year_id = $year_id";

//    echo "<hr/> $q <hr/>";


    $result = sqlQuery($q);

    foreach ($result as $key => $value) {
        $class_id = $value[0][0];
        break;
    }

    $time_tokens = explode("-", $time);

    $start_time = $time_tokens[0];

    $end_time = $time_tokens[1];

    $q = "SELECT week_day_id FROM schoollms_schema_userdata_school_timetable_weekdays WHERE week_day_label like '%$week_day%'";

//    echo "Q $q <br>\n";

    $result = sqlQuery($q);

    $week_day_id = "";
    foreach ($result as $key => $value) {
        $week_day_id = $value[0][0];
        break;
    }
    $date = explode("~",$date);

//    $q = "SELECT period_label_id FROM schoollms_schema_userdata_school_timetable_period WHERE period_start = '$start_time' AND period_end = '$end_time' and school_id = $school_id and week_day_id = $week_day_id";
    $q = "select pl.period_label_id
        from schoollms_schema_userdata_school_timetable_period ls
        join schoollms_schema_userdata_school_timetable_period_labels pl on pl.period_label_id = ls.period_label_id
        where ls.school_id =  $school_id
        and pl.period_label_id =  '$date[2]'
        and week_day_id = $week_day_id";

//    echo "Q $q <br>\n";

    $result = sqlQuery($q);
    $period_id ="";
    foreach ($result as $key => $value) {
        $period_id = $value[0][0];
        break;
    }


//    echo  $day;
    $q = "SELECT day_id FROM schoollms_schema_userdata_school_timetable_days WHERE day_label = '$day'";

    //echo "Q $q <br>\n";

    $result = sqlQuery($q);

    foreach ($result as $key => $value) {
        $day_id = $value[0][0];
        break;
    }
        
    $datedata = explode("~",$date);
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
//            echo " -- AHHA  $sql-- <hr/>";
        $period_class_id = $row->class_id;
        if($period_class_id != $class_id)
        {
                $class_id = $row->class_id;
        }			
    }
		
		
    $q = "INSERT INTO schoollms_schema_userdata_school_timetable_subject_lessons (day_id,period_label_id,class_id,subject_id,lesson_date,lesson_url,lesson_title) VALUES ($day_id, $period_id, $class_id, $subject_id, '$date[0]', '$lessonurl', '$lesson')";
    $data->execNonSql($q);
//    echo "Q $q <br>\n";

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
//    echo "<hr/> $sql";
}

if(strtoupper($action) == "UNPUBLISHLESSON")
{
    timetable_audit_log($user_id, $action, $audit_data);
    
	//'$lesson_title','$period_label_id','$day_id','$date_stamp','$lesson_url','$class_id','$subject_id';
	$sql = "delete from schoollms_schema_userdata_school_timetable_subject_lessons
	where day_id = $day_id and period_label_id = $period_label_id
	and class_id = $class_id and subject_id = $subject_id 
	and lesson_url = '$lesson_url' and lesson_title = '$lesson_title'
	and date_format(str_to_date(lesson_date,'%d-%b-%Y'),'%d-%m-%Y') = '$date_stamp'";
	$data->execNonSql($sql);
	echo $sql;
}

if(strtoupper($action) == "REMOVESLOT")
{
	timetable_audit_log($user_id, $action, $audit_data);
	var_dump($_GET);
	$sql = "delete from schoollms_schema_userdata_school_timetable_items
	where period_label_id = '$theperiod' 
	and timetabl_id = $time_table_id
	and day_id = (select day_id
		from schoollms_schema_userdata_school_timetable_days
		where day_label = '$day' limit 0,1 )";
	echo "<hr/>$sql";
	$data->execNonSql($sql);
	if(!isset($subject_id) and isset($subject))
	{
		$subject_id = 0;
		$sql = "select * from schoollms_schema_userdata_school_subjects where subject_title = '$subject'";
		$data->execSQL($sql);
		if($row = $data->getRow())
		{
			$subject_id = $row->subject_id;
		}
		
		if(isset($date_stamp))
		{	
			$sql = "delete from schoollms_schema_userdata_school_timetable_subject_lessons
			where day_id = (select day_id
				from schoollms_schema_userdata_school_timetable_days
				where day_label = '$day' limit 0,1 ) and period_label_id = '$theperiod'
			and class_id = $class_id and subject_id = $subject_id 
			and date_format(str_to_date(lesson_date,'%d-%b-%Y'),'%d-%m-%Y') = '$date_stamp'";
			echo "<hr/>$sql";
			$data->execNonSql($sql);
		}
		if(isset($date))
		{		
			$date = str_replace(">","",$date);
			$sql = "delete from schoollms_schema_userdata_school_timetable_subject_lessons
			where day_id = (select day_id
				from schoollms_schema_userdata_school_timetable_days
				where day_label = '$day' limit 0,1 ) and period_label_id = '$theperiod'
			and class_id = $class_id and subject_id = $subject_id 
			and lesson_date = '$date'";
			echo "<hr/>$sql";
			$data->execNonSql($sql);
		}
	}
	else{	
		if(isset($date_stamp))
		{	
			$sql = "delete from schoollms_schema_userdata_school_timetable_subject_lessons
			where day_id = (select day_id
				from schoollms_schema_userdata_school_timetable_days
				where day_label = '$day' limit 0,1 ) and period_label_id = '$theperiod'
			and class_id = $class_id and subject_id = $subject_id 
			and date_format(str_to_date(lesson_date,'%d-%b-%Y'),'%d-%m-%Y') = '$date_stamp'";
			echo "<hr/>$sql";
			$data->execNonSql($sql);
		}
		if(isset($date))
		{		
			$date = str_replace(">","",$date);
			$sql = "delete from schoollms_schema_userdata_school_timetable_subject_lessons
			where day_id = (select day_id
				from schoollms_schema_userdata_school_timetable_days
				where day_label = '$day' limit 0,1 ) and period_label_id = '$theperiod'
			and class_id = $class_id and subject_id = $subject_id 
			and lesson_date = '$date'";
			echo "<hr/>$sql";
			$data->execNonSql($sql);
		}
	}
	
}

if(strtoupper($action) == "MARKREGISTER")
{
    timetable_audit_log($user_id, $action, $audit_data);
	$result = "DONE";
	
	$timedata = explode("~",$timeslot);
	$teacher_id = 0;
	$timedata[1] = str_replace("<b>","",$timedata[1]);
	$timedata[1] = str_replace("</b>","",$timedata[1]);
	$daydata = explode("<br>",$timedata[1]);
	//var_dump($daydata);
	//var_dump($timedata);
	$sql = "select * from schoollms_schema_userdata_school_timetable where timetabl_id = '$timedata[3]'";
	$data->execSQL($sql);
	if($row = $data->getRow())
	{
		$teacher_id = $row->timetable_type_item_id;
	}
	
	//echo $teacher_id;
	$start_time = "";
	$end_time = "";
	
	$sql = "select w.*, p.*
		from schoollms_schema_userdata_school_timetable_period p
		join schoollms_schema_userdata_school_timetable_period_labels pl on pl.period_label_id = p.period_label_id
		join schoollms_schema_userdata_school_timetable_weekdays w on w.week_day_id = p.week_day_id
		where period_label = '$timedata[2]'
		and week_day_label like '%$daydata[1]%'";
		
		
	$data->execSQL($sql);
	if($row = $data->getRow())
	{
		$start_time = $row->period_start;
		$end_time = $row->period_end;
	}
	$now = date("H:1");
	$nowparts = explode(":",$now);
	$startparts = explode(":",$start_time);
	$endparts = explode(":",$end_time);
	
	echo "$startparts[0] <= $nowparts[0] and $endparts[0] >= $nowparts[0]";
	if(!($startparts[0] <= $nowparts[0] and $endparts[0] >= $nowparts[0]))
	{
		$sql = "";
		if($ispresent=="true")
		{
			$sql = "delete from schoollms_schema_userdata_learner_attendance_record where 
			learner_id = '$learner_id' and is_present = 0 and teacher_id = '$teacher_id' and day_date = '$daydata[2]' and 
			week_day_label = '$daydata[1]' and period_label= '$timedata[2]' and 
			subject= '$subject' and year_id = '$year_id' and school_id = '$school_id' and class = '$class'";
		}
		else{
			$sql = "insert into schoollms_schema_userdata_learner_attendance_record (learner_id,register_time,is_present,teacher_id,day_date,
			week_day_label,period_label,subject,year_id,school_id,class) values ('$learner_id',now(),0,'$teacher_id','$daydata[2]',
			'$daydata[1]','$timedata[2]','$subject','$year_id','$school_id','$class')";
		}
		echo "<br/>$sql";
		$data->execNonSql($sql);		
	}
	else{
		$result = "WRONDTIMESLOT";
	}
	
	echo "$result";
	
}

if(strtoupper($action) == "MERIT")
{
    timetable_audit_log($user_id, $action, $audit_data);
	$result = "DONE";
	
	$timedata = explode("~",$timeslot);
	$teacher_id = 0;
	$timedata[1] = str_replace("<b>","",$timedata[1]);
	$timedata[1] = str_replace("</b>","",$timedata[1]);
	$daydata = explode("<br>",$timedata[1]);
	//var_dump($daydata);
	//var_dump($timedata);
	$sql = "select * from schoollms_schema_userdata_school_timetable where timetabl_id = '$timedata[3]'";
	$data->execSQL($sql);
	if($row = $data->getRow())
	{
		$teacher_id = $row->timetable_type_item_id;
	}
	
	//echo $teacher_id;
	$start_time = "";
	$end_time = "";
	
	$sql = "select w.*, p.*
		from schoollms_schema_userdata_school_timetable_period p
		join schoollms_schema_userdata_school_timetable_period_labels pl on pl.period_label_id = p.period_label_id
		join schoollms_schema_userdata_school_timetable_weekdays w on w.week_day_id = p.week_day_id
		where period_label = '$timedata[2]'
		and week_day_label like '%$daydata[1]%'";
		
		
	$data->execSQL($sql);
	if($row = $data->getRow())
	{
		$start_time = $row->period_start;
		$end_time = $row->period_end;
	}
	$now = date("H:1");
	$nowparts = explode(":",$now);
	$startparts = explode(":",$start_time);
	$endparts = explode(":",$end_time);
	//echo "$startparts[0] <= $nowparts[0] and $endparts[0] >= $nowparts[0]";
	if(!($startparts[0] <= $nowparts[0] and $endparts[0] >= $nowparts[0]))
	{
		$sql = "insert into schoollms_schema_userdata_learner_merit(learner_id , merit_time , merit_type_id,teacher_id,day_date, week_day_label ,period_label ,subject,
		year_id,school_id ,class) values ('$learner_id' , now() , '$merit_id','$teacher_id','$daydata[2]',
		'$daydata[1]','$timedata[2]','$subject','$year_id','$school_id','$class')";
		echo $sql;
		$data->execNonSql($sql);
	}
	else{
		$result = "WRONDTIMESLOT";
	}
	
	echo "$result";
	
}

if(strtoupper($action) == "GETMEMBERSTATS")
{
	echo "	
	{\"donutcol\" :	{
	  \"d1\" : {
		   	\"label\" : \"Absentees\",
	   		\"value\" : \"".rand ( 1, 100)."\"
  			 },
 	  \"d2\" : {
		   	\"label\" : \"Demerits\",
	   		\"value\" : \"".rand ( 1, 100)."\"
 			  },
 	  \"d3\" : {
		   	\"label\" : \"Merits\",
	   		\"value\" : \"".rand ( 1, 100)."\"
  			 },
	  \"d4\" : {
		   	\"label\" : \"100% Attendance\",
	   		\"value\" : \"".rand ( 1, 100)."\"
  			 }	 
   				}}
	
	";
}

if(strtoupper($action) == "GETDRILLDOWNDATA")
{
	$school_id = 0;
	$year_id = 0;
	$sql = "";
	
	if(trim($todate) == "")
	{
		$todate = date("Y-m-d");		
	}
	else{
		$todatedata =  explode("-",$todate);	
		
	}
	if(trim($fromdate) == "")
	{		
		$sql = "select * from schoollms_schema_userdata_school_year where year_label = '".date("Y")."'";
		$fromdate = date("Y")."-01-01";		
	}
	else{
		$fromdatedata = explode("-",$fromdate);		
		$sql = "select * from schoollms_schema_userdata_school_year where year_label = '$fromdatedata[0]'";
	}
	
	//echo "<br/>$sql</br>";
	$data->execSQL($sql);
	if($row = $data->getRow())
	{
		$year_id = $row->year_id;
	}
	
	$sql = "select distinct school_id
	from schoollms_schema_userdata_school_classes  c
	join schoollms_schema_userdata_school_timetable_items ti on c.class_id = ti.class_id
	where
	  teacher_id = '$user_id'
	order by class_label desc";
	$data->execSQL($sql);
	if($row = $data->getRow())
	{
		$school_id = $row->school_id;
	}
	
	$result = "";
	switch($label)
	{
		case "Absentees":
		
			$sql = "select *
			from schoollms_schema_userdata_learner_attendance_record r
			join schoollms_schema_userdata_access_profile p on p.user_id = r.learner_id
			where class = '$class'
			and year_id = '$year_id'
			and register_time >='$fromdate' 
			and  register_time <='$todate'";
			$data->execSQL($sql);
			//echo $sql;
			$result = "<h2>$label</h2><br/><table border='1' cellpadding='2' cellspacing=2 width='100%'>";
			$result .= "<tr> <th> Learner Name</th><th>Week Day</th><th>Subject</th> <th>Period</th> <th>Absentee Date</th> </tr>";
			while($row = $data->getRow())
			{
				//echo "<br/>here";
				$result .= "<tr> <td> $row->surname, $row->name</td><td>$row->week_day_label</td><td>$row->subject </td> <td>$row->period_label </td> <td>$row->day_date</td> </tr>";
			}
			$result .="</table>";
			break;
		case "Demerits":
		case "Merits":
			$sql = "select *
				from schoollms_schema_userdata_learner_merit r 
				join schoollms_schema_userdata_access_profile p on p.user_id = r.learner_id 
				join schoollms_schema_userdata_learner_merit_type t on t.merit_type_id = r.merit_type_id
				where class = '$class'
			and year_id = '$year_id'
				and merit_time >='$fromdate' 
				and merit_time <='$todate'";
			
			if($label == "Demerits"){
				$sql .= " and is_demerit = 1";
			}
			else{
				$sql .= " and is_demerit = 0";
			}
			
			$data->execSQL($sql);
			//echo $sql;
			$result = "<h2>$label</h2><br/><table border='1' cellpadding='2' cellspacing=2 width='100%'>";
			$result .= "<tr> <th> Learner Name</th><th>Week Day</th><th>Subject</th> <th>Period</th> <th>$label Date</th><th>&nbsp;</th> </tr>";
			while($row = $data->getRow())
			{
				//echo "<br/>here";
				$result .= "<tr> <td> $row->surname, $row->name</td><td>$row->week_day_label</td><td>$row->subject </td> <td>$row->period_label </td> <td>$row->merit_time</td> <td>$row->merit_type</td>  </tr>";
			}
			$result .="</table><br/>";
			
			$sql = "select count(*) num ,merit_type
				from schoollms_schema_userdata_learner_merit r 
				join schoollms_schema_userdata_access_profile p on p.user_id = r.learner_id 
				join schoollms_schema_userdata_learner_merit_type t on t.merit_type_id = r.merit_type_id
				where class = '$class'
			and year_id = '$year_id'
				and merit_time >='$fromdate' 
				and merit_time <='$todate'";
			
			if($label == "Demerits"){
				$sql .= " and is_demerit = 1";
			}
			else{
				$sql .= " and is_demerit = 0";
			}
			$sql .= " group by merit_type";
			$data->execSQL($sql);
			$result .= "<br/><h2>Summary</h2><br/><table border='1' cellpadding='2' cellspacing=2 width='100%'>";
			$result .= "<tr> <th>Number </th><th>$label</th></tr>";
			while($row = $data->getRow())
			{
				$result .="<tr> <td> $row->num</td> <td>$row->merit_type</td> </tr>";
			}
			$result .="</table><br/>";
				
			break;
		case "100% Attendance":
			$sql = "select *
				from schoollms_schema_userdata_learner_schooldetails sd
				join schoollms_schema_userdata_access_profile p on p.user_id = sd.user_id
				join schoollms_schema_userdata_school_classes c on sd.class_id = c.class_id
				where sd.year_id = $year_id and c.class_label = '$class'
				and p.user_id not in (select learner_id from schoollms_schema_userdata_learner_attendance_record 
					where register_time between '$fromdate' and '$todate' and class = '$class' and teacher_id = '$user_id')";
				$data->execSQL($sql);
				$result = "<h2>$label</h2><br/><table>";
				while($row = $data->getRow())
				{
					$result .= "<tr><td>$row->surname, $row->name</td></tr>";
				}
				$result .= "</table>";
				//echo $result;
			break;
	}
	
	echo $result;
	
}

if(strtoupper($action) == "GETYEARID")
{
	$year = date("Y");
	$sql = "select * from schoollms_schema_userdata_school_year where year_label = '$year'";
	$data->execSQL($sql);
	$year_id = 0;
	if($row = $data->getRow())
	{
		$year_id = $row->year_id;
	}
	echo $year_id;
}

if(strtoupper($action) == "REGISTER")
{
    timetable_audit_log($user_id, $action, $audit_data);
    
	$year = date("Y");
	$sql = "select * from schoollms_schema_userdata_school_year where year_label = '$year'";
	$data->execSQL($sql);
	$year_id = 0;
	if($row = $data->getRow())
	{
		$year_id = $row->year_id;
	}
	//echo $year_id;
	
	$time = time();
        $key = md5("TIME $time SAVE USER SCHOOL $school_id TYPE $type_id ACCESS $email NAME $name SURNAME $surname");
	
//	$sql = "insert into schoollms_schema_userdata_conference_registration ( user_md5, name, surname ,email ,type_id, gender ,  marital_status)
//	values('$key','$name','$surname','$email','$type_id','$type','$marital_status')";
//	$data->execNonSql($sql);
//	$reg_id = $data->insertid;
//	
//	//sendConfimationEmail($email,"$name $surname", $reg_id);
//	$url = SITE_URL."/sendconfirmationmail.php?email=$email&fullname=$name $surname&registration_id=$reg_id";
//	$res = file($url);
	
	if($name == 'Conference Demo')
	{
		$sql = "select user_id
			from schoollms_schema_userdata_learner_schooldetails sd
			join schoollms_schema_userdata_school_classes c on c.class_id = sd.class_id
			where class_label LIKE '%CLASS%'
			limit 0,1";
		$data->execSQL($sql);
		$theUser_id = 955;
		if($row = $data->getRow())
		{
			$theUser_id = $row->user_id;
		}
		
		$email = substr($email,0,13);
		$sql = "INSERT INTO schoollms_schema_userdata_access_profile (school_id, type_id, access_id, name, surname) values ($school_id, $type_id, '$email', '$name', '$surname')";
		//echo $sql;
		$data->execNonSql($sql);
		$user_id = $data->insertid;
		
		$sql = "insert into schoollms_schema_securitydata_offline_access_register (user_id,client_device_key) values ('$user_id','$key')";
		$data->execNonSql($sql);
		$security_reg_id = $data->insertid;
		
		$sql = "insert into schoollms_schema_userdata_school_timetable (school_id,timetable_type_id,timetable_type_item_id,timetable_label)
		values ('$school_id','1','$user_id','$surname $name')";
		//echo $sql;
		$data->execNonSql($sql);
		$timetable_id = $data->insertid;
		
		$sql = "insert into schoollms_schema_userdata_learner_schooldetails(user_id,school_id,grade_id,class_id,year_id) 
		select $user_id ,'$school_id', grade_id, class_id,year_id
		from schoollms_schema_userdata_learner_schooldetails
		where user_id = $theUser_id ";
		$data->execNonSql($sql);
		
		$sql = "insert into schoollms_schema_userdata_learner_timetable 
			(user_id, timetabl_id,year_id)
			values ($user_id,$timetable_id,$year_id)";
		$data->execNonSql($sql);
		
		$sql = "insert into schoollms_schema_userdata_school_timetable_items (timetabl_id, day_id, period_label_id, grade_id, class_id, 
			room_id, subject_id, teacher_id, substitude_id )
			select distinct $timetable_id,day_id, period_label_id, grade_id, class_id, 
			room_id, subject_id, teacher_id, substitude_id 
			from schoollms_schema_userdata_school_timetable_items i
			join schoollms_schema_userdata_school_timetable t on t.timetabl_id = i.timetabl_id 
			where timetable_type_item_id = $theUser_id ";
		$data->execNonSql($sql);
	}
	//echo $sql;
	
	$year = date("Y");
	$sql = "select * from schoollms_schema_userdata_school_year where year_label = '$year'";
	$data->execSQL($sql);
	$year_id = 0;
	if($row = $data->getRow())
	{
		$year_id = $row->year_id;
	}
	
	$user_id =955;
	$user_type = 2;
        $user_ids = array ();
	switch($type_id){
            
            case 2:
                $user_id_key = rand(0, count($user_ids)-1);
		$user_id = $user_ids[$user_id_key];
	
		$user_type = 2;
		$sql = "select user_id
			from schoollms_schema_userdata_learner_schooldetails sd
			join schoollms_schema_userdata_school_classes c on c.class_id = sd.class_id
			where class_label like '%CLASS%'";
		$data->execSQL($sql);
		$theUser_id = 955;
		$key = rand(0,$data->numrows-1);
		$user_ids = array();
		while($row = $data->getRow())
		{
			$user_ids[]  = $row->user_id;
		}
		$user_id = $user_ids[$key];
                break;
                
            case 3:
                $user_type = 3;
                $q = "SELECT * FROM schoollms_schema_userdata_access_profile WHERE school_id = 1 AND type_id = 3 ";
                $result = $data->exec_sql($q, "array");
                $key = rand(0,$data->numrows-1);
                $parent_ids = array ();
                foreach ($result as $key => $row) {
                    $parent_ids[] = $row["user_id"];
                }
                $user_id = $parent_ids[$key];
                
                $q = "SELECT * FROM schoollms_schema_userdata_learner_parent WHERE parent LIKE '%$user_id%'";
                
                $data->execSQL($q);
                if ($data->numrows > 0){
                    //DO NOTHING
                } else {
                    $q = "SELECT * FROM schoollms_schema_userdata_learner_schooldetails WHERE school_id = 1 and grade_id IN (8,9)";
                    $result = $data->exec_sql($q, "array");
                    $findkey = rand(0,$data->numrows-1);
                    foreach ($result as $key => $row) {
                        if ($findkey == $key){
                            $learner_user_id = $row->user_id;
                            $q = "INSERT INTO schoollms_schema_userdata_learner_parent VALUES ($learner_user_id, 'parent_1:$user_id')";
                            $data->execNonSql($q);
                            break;
                        }
                    }
                }
                break;
            
            case 4:
		$user_type = 4;
//                $q = "select user_id from schoollms_schema_userdata_access_profile where type_id = 4 and not access_id = '' and not access_id like '%TN%'";
//		$data->execSQL($q);
//                if ($data->numrows > 0){
//                    while($row = $data->getRow()){
//                        $user_id = $row->user_id;
//                        $user_ids[] = $user_id;
//                    }
//                } else {
                    $user_ids = array(1518,1477,1479,1482,1484,1485,1486,1488,1489,1491,1492,1494);
                    
//                }
                 break;   
                
	}
	
	echo "$key-viewtimetable.php?user_type=$user_type&user_id=$user_id&year_id=$year_id&school_id=$school_id&id=22";
	
}

function sendConfimationEmail($email, $fullname, $registration_id)
{
	$message  = "Good Day $fullname<br/><br/>Thank you for registering for the School LMS Demo.<br/><br/>";
	$message  .= "Please click on the link below to confirm your registration <br/><br/>".SITE_URL."/confirm.php&id=$registration_id<br/>";
	$message  .= "<br/>Welcome to School LMS";
	
	SchoolLmsMailer::ProcessMail($email,$fullname,$message,"Registration Confirmation");
}

if(strtoupper($action) == "USERACCESS")
{
	$sql = "select ap.user_id, username, REPLACE(ap.name, '^', '') name,REPLACE(ap.surname, '^', '') surname,ap.type_id,t.type_title,
		case when status ='active' then 1 else 0 end ck, status, ap.access_id
		from schoollms_schema_userdata_access_profile ap
		left join schoollms_schema_userdata_user_type t on t.type_Id = ap.type_Id
		left join school_lms_prod_vas_track.schoollms_schema_securitydata_access sa on sa.access_id = ap.user_id		
		where school_id = $school_id
		and (username like '%$search%' or name like '%$search%' 
			or surname like '%$search%' or status like '%$search%'
			or t.type_title like '%$search%' )";
	//	echo $sql;
	$data->execSQL($sql);
	$result = array();
	while($row = $data->getRow())
	{
		$result[] = $row;
	}
	echo json_encode($result);
}

if(strtoupper($action) == "ADDACCESSCONTROLUSER")
{
    timetable_audit_log($user_id, $action, $audit_data);
    
	$year = date("Y");	
	$sql = "select * from schoollms_schema_userdata_school_year where year_label = '$year'";
	$data->execSQL($sql);
	$year_id = 0;
	if($row = $data->getRow())
	{
		$year_id = $row->year_id;
	}
	$user_id = "";
	$sql = "select * from  schoollms_schema_userdata_access_profile where access_id = '$email'";
	$data->execSQL($sql);
	if($row = $data->getRow())
	{
		$user_id = $row->user_id;
	}
	else{
		//insert here		
		$sql = "insert into schoollms_schema_userdata_access_profile (access_id, name, surname,type_id,school_id) values
		('$email','$name','$surname','$type_id','$school_id')";
		$data->execNonSql($sql);
		$user_id = $data->insertid;		
	}
	
	//if()
	$sql = "select *
		from schoollms_schema_userdata_school_classes
		where class_label like '%Class%$grade_id%$class_id%'
		AND year_id = $year_id";
	$data->execSQL($sql);
	$class_id = "";
	if($row = $data->getRow())
	{
		$class_id = $row->class_id;
	}
	$id = "";
	$sql  = "";
	if($usertype == "learner")
	{
		$sql = "insert into schoollms_schema_userdata_learner_schooldetails (user_id,school_id,grade_id,class_id,year_id)
		values ('$user_id','$school_id','$grade_id','$class_id','$year_id')";
		$data->execNonSql($sql);
		$id= $user_id;
	}
	
	if($usertype == "teacher")
	{
		$sql = "insert into schoollms_schema_userdata_school_teacher_schooldetails (user_id,school_id,grade_id,class_id,year_id)
		values ('$user_id','$school_id','$grade_id','$class_id','$year_id')";
		$data->execNonSql($sql);
		$id= $user_ids;
	}
	//add the script to add to School LMS here...
	
	//echo "$sql -- $id -- $class_id -- $user_id";
	echo "User Added";
	
}


if(strtoupper($action) == "ADDACCESSUSER")
{
    timetable_audit_log($user_id, $action, $audit_data);
    
	$year = date("Y");	
	$sql = "select * from schoollms_schema_userdata_school_year where year_label = '$year'";
	$data->execSQL($sql);
	$year_id = 0;
	if($row = $data->getRow())
	{
		$year_id = $row->year_id;
	}
	$user_id = "";
	$sql = "select * from  schoollms_schema_userdata_access_profile where access_id = '$email'";
	$data->execSQL($sql);
	if($row = $data->getRow())
	{
		$user_id = $row->user_id;
	}
	else{
		//insert here		
		$sql = "insert into schoollms_schema_userdata_access_profile (access_id, name, surname,type_id,school_id) values
		('$email','$name','$surname','$type_id','$school_id')";
		$data->execNonSql($sql);
		$user_id = $data->insertid;		
	}
	
	$sql = "select *
		from schoollms_schema_userdata_school_classes
		where class_label like '%Class%$add_student_grade%$add_learner_class%'
		AND year_id = $year_id";
	$data->execSQL($sql);
	$class_id = "";
	if($row = $data->getRow())
	{
		$class_id = $row->class_id;
	}
	$id = "";
	$sql  = "";
	if($type_id == "2")
	{
		$sql = "insert into schoollms_schema_userdata_learner_schooldetails (user_id,school_id,grade_id,class_id,year_id)
		values ('$user_id','$school_id','$add_student_grade','$class_id','$year_id')";
		$data->execNonSql($sql);
		$id= $user_id;
	}
	
	if($type_id == "4")
	{
		$sql = "insert into schoollms_schema_userdata_school_teacher_schooldetails (user_id,school_id,grade_id,class_id,year_id)
		values ('$user_id','$school_id','$add_student_grade','$class_id','$year_id')";
		$data->execNonSql($sql);
		$id= $user_ids;
	}
}

if(strtoupper($action) == "ADDSUBJECT")
{
    timetable_audit_log($user_id, $action, $audit_data);
    
	$sql = "insert into schoollms_schema_userdata_school_subjects (subject_title,subject_src) values ('$subjectname','SchoolLMS')";
	$data->execNonSql($sql);
	if($data->insertid > 0)
	{
		echo "Subject Added Successfully";
	}
	else{
		echo "An error occured. Please try again later.";
	}
}

if(strtoupper($action) == "ACTIONLOG")
{
    timetable_audit_log($user_id, $action, $audit_data);
    
	$user_type = "";
	$sql = "select * from schoollms_schema_userdata_user_type where type_id = $user_type_id";	
	$data->execSQL($sql);
	if($row = $data->getRow())
	{
		$user_type = strtolower($row->type_title);
	}
	
	$log_action = str_replace(" ","%",$log_action);
	$sql = "select * from action_type where lower(action_type) like lower('%$user_type%$log_action%')";
	echo "$sql<hr/>";
	
	$data->execSQL($sql);
	$action_type_id = 0;
	if($row = $data->getRow())
	{
		$action_type_id = $row->action_type_id;
	}
	
	$other_data = explode(",",$other_params);
	$sql = "insert into user_action_log(action_type_id,action_time,user_id,day_id,period_label_id,class_id,subject_id,lesson_date)
	values ('$action_type_id',now(),$user_id,'$other_data[2]','$other_data[1]','$other_data[5]','$other_data[6]','$other_data[3]')";
	$data->execNonSql($sql);
	echo $sql;	
	
	switch($user_type)
	{
		case "learner":
			
			break;
		case "teacher":
			break;		
	}
}

function getgradeclasslist($data, $sql, $grade_id, $class_id, $return){
    
    $data->execSQL($sql);
    if ($data->numrows > 0){
       $return .= "<tr><td><b> Total Number of Learners:</b> $data->numrows </td><tr>"; 
       $return .= "<tr><th><table border=2><tr><th><b> ID No. </b></th><th><b> Full Name </b> </th><th> <b> Photo </b> </th> <th> <b> Subject(s) </b></th> <th> <b>Action </b></th></tr>";
       while($row1 = $data->getRow())
       {
           $user_id = $row1->user_id;
           $access_id = $row1->access_id;
           $school_id = $row1->school_id;
           $name = strtoupper($row1->name);
           $name = str_replace('^', ' ', $name);
           $surname = strtoupper($row1->surname);
           $surname = str_replace('^', ' ', $surname);
           $learner_name = "$surname $name";
           $learner_name = str_replace ('\n', $learner_name);
           //CHECK IF LEARNER EXISTS IN CLASS IN LMS
            //IF TRUE SKIP
            //ELSE ADD LEARNER TO CLASS IN LMS
           
           $return .= "<tr><td> $access_id </td><td> $surname, $name </td><td><img src='api/process.php?action=GETIMAGE&user_id=$user_id'/></td><td></td><td><button name='bntViewTimetable' id='bntViewTimetable' onClick='viewTimetable($user_id, $school_id, 2)'>View Timetable</button><button name='bntRemoveLearner' id='bntRemoveLearner' onClick='removeLearnerClass($grade_id, $class_id, $user_id)'>Remove from Class</button></td></tr>";
            //$result[] = $row;
       }
       $return .= "</table><br><br></td></tr>";
    } else {
       $return .= "<br><br>"; 
    }
    
    return $return;
}

function getclassteacher($data, $class_id, $year_id){
     
    //CHECK IF CLASS IS SUBJECT CLASS OR REGISTER CLASS
    $q = "select distinct(teacher_id) from schoollms_schema_userdata_school_timetable_items "
            . "where timetabl_id in "
            . "(select timetabl_id from schoollms_schema_userdata_school_timetable where timetable_type_id = 3 and timetable_type_item_id "
            . "in (select class_id from schoollms_schema_userdata_school_classes where class_id = $class_id and year_id = $year_id)) "
            . "and period_label_id = 1";
    
    $data->execSQL($q);
    
    //echo "REGISTER Q $q <br>";
    
    if ($data->numrows == 1){//CHECK IF REGISTER CLASS
        $row = $data->getRow();
        $user_id = $row->teacher_id;
        $q = "SELECT * FROM schoollms_schema_userdata_access_profile WHERE user_id = $user_id AND type_id = 4";
        $data->execSQL($q);
        //echo "TEACHER Q $q <br>";
        $row = $data->getRow();
        $teacher_name = $row->name." ".$row->surname;
        $num_teachers = $data->numrows;
        $teacher_list = "";
        //echo "TEACHER $teacher_name <br>";
    } elseif ($data->numrows == 0) {//CHECK IF SUBJECT CLASS
        $q = "select distinct(teacher_id) from schoollms_schema_userdata_school_timetable_items "
            . "where timetabl_id in "
            . "(select timetabl_id from schoollms_schema_userdata_school_timetable where timetable_type_id = 8 and timetable_type_item_id "
            . "in (select class_id from schoollms_schema_userdata_school_classes where class_id = $class_id and year_id = $year_id))";
        $data->execSQL($q);
        
        //echo "SUBJECT Q $q <br>";
        if ($data->numrows == 1){
            $row = $data->getRow();
            $user_id = $row->teacher_id;
            $q = "SELECT * FROM schoollms_schema_userdata_access_profile WHERE user_id = $user_id AND type_id = 4";
            $data->execSQL($q);
            //echo "TEACHER Q $q <br>";
            $row = $data->getRow();
            $teacher_name = $row->name." ".$row->surname;
            $num_teachers = $data->numrows;
            $teacher_list = "";
            //echo "TEACHER $teacher_name <br>";
        } elseif ($data->numrows == 0){
            $num = $data->numrows;
            $user_id = 0;
            $teacher_name = "$num Teachers Found";
            $num_teachers = $data->numrows;
            $teacher_list = "";
        } else {
            $num = $data->numrows;
            $user_id = 0;
            $teacher_name = "$num Teachers Found";
            $num_teachers = $data->numrows;
            $teacher_list = "";
            while($row = $data->getRow()){
                $teacher_id = $row->teacher_id;
                $teacher_list .= "$teacher_id-";
            }
            $teacher_list = rtrim($teacher_list, "-");
        }
    } else {
        $num = $data->numrows;
        $user_id = 0;
        $teacher_name = "$num Teachers Found";
        $num_teachers = $data->numrows;
        $teacher_list = "";
        while($row = $data->getRow()){
            $teacher_id = $row->teacher_id;
            $teacher_list .= "$teacher_id-";
        }
        $teacher_list = rtrim($teacher_list, "-");
    }
    
    return array ($user_id, "$teacher_name", $num_teachers, "$teacher_list");
}
?>
