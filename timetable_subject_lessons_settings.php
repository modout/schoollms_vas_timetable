<?php

//CONNECT TO DB
//include 'sphs_school_data_db.inc';

include("lib/db.inc");

if (!isset($_SERVER["HTTP_HOST"])) {
  parse_str($argv[1], $_GET);
  parse_str($argv[1], $_POST);
}

extract($_GET);
extract($_POST);
//var_dump($_GET);
switch ($action){
    
    case 'get_subject_link':
        
        break;
    
    case 'get_subject_terms':
        
//        $term = get_timetable_term($data2);
//        
//        if ($term['found']){
//            $term_no = $term['no'];
//        }
        
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
        break;
        
       
        
}

function get_timetable_term($data){
  
    $term = array ();
    date_default_timezone_set('Africa/Johannesburg');
    $today = date('d-m-Y H:i:s');
    $day_tokens = explode(" ", $today);
    $date_tokens = explode("-", $day_tokens[0]);
    $year = $date_tokens[2];
    $q = "SELECT year_id FROM schoollms_schema_userdata_school_year WHERE year_label = '$year'";
    $data->execSQL($q); 
    $row = $data->getRow();
    $year_id = $row->year_id;
    $q = "SELECT * FROM schoollms_schema_userdata_school_year_calendar WHERE year_id = $year_id";

    $data->execSQL($q);
    $calendar_dates = array ();
    while ($row=$data->getRow()){
        $starttime = $row->start_time;
        $endtime = $row->end_time;
        $calendar_type = $row->calendar_type;
        $start_tokens = explode("|", $starttime);
        $start_date = $start_tokens[2]."-".$start_tokens[1]."-".$start_tokens[0]." ".$start_tokens[3].":".$start_tokens[4].":".$start_tokens[5];
        $end_tokens = explode("|", $endtime);
        $end_date = $end_tokens[2]."-".$end_tokens[1]."-".$end_tokens[0]." ".$end_tokens[3].":".$end_tokens[4].":".$end_tokens[5];
        $calendar_dates [$calendar_type] = "$start_date|$end_date";
    }

    $term['found'] = FALSE;
    $today_date = strtotime($today);
    foreach ($calendar_dates as $calendar_type=>$date_items){
        $date_tokens = explode("|", $date_items);
        $start_date = strtotime($date_tokens[0]);
        $end_date = strtotime($date_tokens[1]);

        if ($today_date > $start_date && $today_date < $end_date){
            $q = "SELECT type_title FROM schoollms_schema_userdata_school_calendar_type WHERE type_id = $calendar_type";
            $data2->execSQL($q);
            $row = $data->getRow();
            $term = $row->type_title;
            $term_tokens = explode("^", $term);
            $term_no = $term_tokens[1];
            //echo "TERM $term_no<br>";
            $term['found'] = TRUE;
            $term['no'] = $term_no;
            break;
        }
    }
    
    return $term;
}
