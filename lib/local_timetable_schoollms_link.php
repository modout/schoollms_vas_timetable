<?php

include("data_db.inc");
$data = new data();
$data->username = "root";
$data->password = "$0W3t0";
$data->host = "localhost";
$data->db = "$lms_db";

$subject_nid = array ();

if (!isset($_SERVER["HTTP_HOST"])) {
  parse_str($argv[1], $_GET);
  parse_str($argv[1], $_POST);
}

extract($_GET);
extract($_POST);

switch ($action){

      
    case 'add_user':
        switch ($user_type) {
            case 2:
                $role = "learner";
                $password = "learn123";
                break;
            
            case 4:
                $role = "educator";
                $password = "teach123";
                break;

            default:
                break;
        }
        
        define('DRUPAL_ROOT', getcwd());
        require_once DRUPAL_ROOT.'/includes/bootstrap.inc';
        drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
        module_load_include('module', 'entity', $name= NULL);
        module_load_include('module', 'node', $name= NULL);
        module_load_include('inc', 'node', 'node.pages');
        module_load_include('inc', 'pathauto', $name= NULL);
        module_load_include('module', 'field', $name= NULL);
        module_load_include('module', 'geofield', $name= NULL);
        module_load_include('install', 'geofield', $name= NULL);
        module_load_include('module', 'ctools', $name= NULL);
        module_load_include('module', 'menu', $name= NULL);
          
        global $user;
        $account = user_authenticate("System Admin", "$0W3t0");
        $user = user_load($account, TRUE);
        drupal_session_regenerate();
        
        

        //echo "SCHOOLID $school_id PASSWORD $password";

        //set up the user fields
        $fields = array(
            'name' => $username, //'user_name',
            'mail' => "$device_id@schoollms.net",
            'pass' => $password,
            'status' => 0,
            'init' => 'email address',
            'roles' => array(
                DRUPAL_AUTHENTICATED_RID => $role,
            ),
        );

        //the first parameter is left blank so a new user is created
        $account = user_save('', $fields);
        
        $uid = $account->uid;
        //STORE SUPPORT USER_ID AGAINST SCHOOLLMS USER_ID
        $q = "INSERT INTO $timetable_db.schoollms_schema_securitydata_user_identities VALUES ($user_id,'support:$uid,learn:$uid,teach:$uid,train:$uid,parent:$uid')";
        $result = db_query($q);
        break;
    
    case 'open_link':
        define('DRUPAL_ROOT', getcwd());
        require_once DRUPAL_ROOT.'/includes/bootstrap.inc';
        drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
        module_load_include('module', 'entity', $name= NULL);
        module_load_include('module', 'node', $name= NULL);
        module_load_include('inc', 'node', 'node.pages');
        module_load_include('inc', 'pathauto', $name= NULL);
        module_load_include('module', 'field', $name= NULL);
        module_load_include('module', 'geofield', $name= NULL);
        module_load_include('install', 'geofield', $name= NULL);
        module_load_include('module', 'ctools', $name= NULL);
        module_load_include('module', 'menu', $name= NULL);
          
        global $user;
        $account = user_authenticate($username, $passwd);
        $user = user_load($account, TRUE);
        drupal_session_regenerate();
        drupal_goto($q);
        break;
    
    case 'save_new_class':
        $subject_tokens = explode(",", $subject_nids);
        define('DRUPAL_ROOT', getcwd());
        require_once DRUPAL_ROOT.'/includes/bootstrap.inc';
        drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
        module_load_include('module', 'entity', $name= NULL);
        module_load_include('module', 'node', $name= NULL);
        module_load_include('inc', 'node', 'node.pages');
        module_load_include('inc', 'pathauto', $name= NULL);
        module_load_include('module', 'field', $name= NULL);
        module_load_include('module', 'geofield', $name= NULL);
        module_load_include('install', 'geofield', $name= NULL);
        module_load_include('module', 'ctools', $name= NULL);
        module_load_include('module', 'menu', $name= NULL);
          
        global $user;
        $account = user_authenticate($username, $passwd);
        $user = user_load($account, TRUE);
        drupal_session_regenerate();
        
        $node = new stdClass();
        $node->type = 'class';
        node_object_prepare($node);

        if ($grade_no < 0){
            $node->title = "$class_label"; //. date('c');
        } else {
            $node->title = "CLASS $grade_no"."$class_label"; //. date('c');
        }
        $node->language = 'und';
        $node->uid = $user->uid; 
        $node->status = 0; //(1 or 0): published or not
        $node->revision = FALSE;
        $node->promote = 0; //(1 or 0): promoted to front page
        $node->comment = 0; // 0 = comments disabled, 1 = read only, 2 = read/write
        $node->sticky = 0;
        $node->log = NULL;
        $node->created  = time() - (rand( 1,240) * 60);
        $node->field_group_group[$node->language][]['field']['group_group_value'] = 1;
        $node->field_group_access[$node->language][]['field']['group_access_value'] = 2;
        $node->field_requires_validation[$node->language][]['field']['requires_validation_value'] = 1;
        $node->field_anomymous_visibility[$node->language][]['field']['anomymous_visibility_value'] = 0;
        $node->field_catalogue_visibility[$node->language][]['field']['catalogue_visibility_value'] = 0;
        //FOREACH CORE SUBJECT IN grade_no
        $node->field_opigno_class_courses[$node->language][]['opigno_class_courses_target_id'] = $subject_nid;
        //$node->field_opigno_class_courses[$node->language][]['target_type'] = "course";
        $node->field_class_quota[$node->language][]['field']['class_quota_value'] = -1;
        //$node->opigno_course_tools[$node->language][5]['field']['columns']['tool'] = "";
        //$node->opigno_course_tools[$node->language][0]['field']['tool'] = '';
        $node = node_submit($node);
        node_save($node);
        break;
    
    case 'save_new_subject':
        define('DRUPAL_ROOT', getcwd());
        require_once DRUPAL_ROOT.'/includes/bootstrap.inc';
        drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
        module_load_include('module', 'entity', $name= NULL);
        module_load_include('module', 'node', $name= NULL);
        module_load_include('inc', 'node', 'node.pages');
        module_load_include('inc', 'pathauto', $name= NULL);
        module_load_include('module', 'field', $name= NULL);
        module_load_include('module', 'geofield', $name= NULL);
        module_load_include('install', 'geofield', $name= NULL);
        module_load_include('module', 'ctools', $name= NULL);
        module_load_include('module', 'menu', $name= NULL);
          
        global $user;
        $account = user_authenticate($username, $passwd);
        $user = user_load($account, TRUE);
        drupal_session_regenerate();
      
        $term_no = 1;
        //$subject_nid = array ();
        while ($term_no <= 4){  
            //echo "ADD $add_new_subject SUBJECT $subject_name Grade $grade_no - Term $term_no";
            $node = new stdClass();
            $node->type = 'course';
            node_object_prepare($node);
//
            $node->title = "$subject_name Grade $grade_no - Term $term_no"; //. date('c');
            $node->language = 'und';
            $node->uid = $user->uid; 
            $node->status = 0; //(1 or 0): published or not
            $node->revision = FALSE;
            $node->promote = 0; //(1 or 0): promoted to front page
            $node->comment = 0; // 0 = comments disabled, 1 = read only, 2 = read/write
            $node->sticky = 0;
            $node->log = NULL;
            $node->created  = time() - (rand( 1,240) * 60);
            $node->field_group_group[$node->language][]['field']['group_group_value'] = 1;
            $node->field_opigno_commerce_price[$node->language][]['field']['opigno_commerce_price_amount'] = 0;
            $node->field_opigno_commerce_price[$node->language][]['field']['opigno_commerce_price_currency_code'] = 'ZAR';
            $node->field_opigno_commerce_price[$node->language][]['field']['opigno_commerce_price_data'] = 'a:1:{s:10:"components";a:0:{}}';
            $node->field_group_access[$node->language][]['field']['group_access_value'] = 2;
            $node->field_requires_validation[$node->language][]['field']['requires_validation_value'] = 1;
            $node->field_anomymous_visibility[$node->language][]['field']['anomymous_visibility_value'] = 0;
            $node->field_opigno_course_categories[$node->language][]['field']['opigno_course_categories_tid'] = 23;//MUST FIND WAY TO ASSOCIATE GRADE DATA
            $node->field_field_school_term[$node->language][]['field']['field_school_term'] = "Term $term_no";
            $node->field_opigno_course_tools[$node->language][]['field']['opigno_course_tools_tool'] = 'quiz';
            $node->field_opigno_course_tools[$node->language][]['field']['opigno_course_tools_tool'] = 'quiz_import';
            $node->field_opigno_course_tools[$node->language][]['field']['opigno_course_tools_tool'] = 'tft';
            $node->field_opigno_course_tools[$node->language][]['field']['opigno_course_tools_tool'] = 'video';
            $node->field_opigno_course_tools[$node->language][]['field']['opigno_course_tools_tool'] = 'poll';
            $node->field_opigno_course_tools[$node->language][]['field']['opigno_course_tools_tool'] = 'forum';
            $node->field_opigno_course_tools[$node->language][]['field']['opigno_course_tools_tool'] = 'opigno_group_statistics';
            $node->field_opigno_course_tools[$node->language][]['field']['opigno_course_tools_tool'] = 'in_house';
            $node->field_opigno_course_tools[$node->language][]['field']['opigno_course_tools_tool'] = 'audio';
            $node->field_course_quota[$node->language][]['field']['course_quota_value'] = -1;
            //$node->opigno_course_tools[$node->language][5]['field']['columns']['tool'] = "";
            //$node->opigno_course_tools[$node->language][0]['field']['tool'] = '';
            $node = node_submit($node);
            node_save($node);

            $q = "SELECT nid FROM node WHERE title = '$subject_name Grade $grade_no - Term $term_no'";

            $subject_nid["$subject_name Grade $grade_no - Term $term_no"] = db_query($q)->fetchField();

            $term_no++;
        }

        echo json_encode($subject_nid);
        break;
    
    case 'get_subject_link':
        
        break;
    
    case 'get_subject_terms':
        
        $q = "select n.title, n.nid, n2.title, quiz_nid, alias from node n "
            . "join opigno_quiz_app_quiz_sort o on o.gid = n.nid "
            . "join node n2 on n2.nid = quiz_nid "
            . "join url_alias u on u.source like concat('%node/',quiz_nid) "
            . "where upper(n.title) like upper('%$subject_name%$grade_no%$term_no');";
        //echo $q;
        $data->execSQL($q);
        $subject_terms = array ();
        if($row = $data->getRow()){
            do {
				//echo "WTF";
                $subject_terms[] = $row;
            } while($row = $data->getRow());
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
            | field_data_course_quota                                |
            | field_data_course_required_course_ref                  |
            | field_data_course_required_quiz_ref                    |
            | field_data_opigno_class_courses                        |
            | field_data_opigno_course_categories                    |
            | field_data_opigno_course_image                         |
            | field_data_opigno_course_tools                         |
             * 
             *
            $node->opigno_course_tools[$node->language][0]['field']['tool'] = '';
            $node->field_track_position[$node->language][0]['field']['lon'] = (double) $longi;
            $node->field_track_position[$node->language][0]['field']['map_height'] = $height;
            $node->field_track_position[$node->language][0]['field']['map_width'] = $width;
            $node->field_track_position[$node->language][0]['field']['zoom'] = $zoom;
            $node->field_track_position[$node->language][0]['field']['name'] = "$key - ".date('YmdHis');*/
        }
        
        echo json_encode($subject_terms);

        break;

    case 'add_user_profile':
        define('DRUPAL_ROOT', getcwd());
        require_once DRUPAL_ROOT.'/includes/bootstrap.inc';
        drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);
        module_load_include('module', 'entity', $name= NULL);
        module_load_include('module', 'node', $name= NULL);
        module_load_include('inc', 'node', 'node.pages');
        module_load_include('inc', 'pathauto', $name= NULL);
        module_load_include('module', 'field', $name= NULL);
        module_load_include('module', 'geofield', $name= NULL);
        module_load_include('install', 'geofield', $name= NULL);
        module_load_include('module', 'ctools', $name= NULL);
        module_load_include('module', 'menu', $name= NULL);
        module_load_include('module', 'og', $name= NULL);

        $data2 = $data;
        $data3 = $data;

        //$passwd = "$0W3t0";
        //$username = "System Admin";
        $account = user_authenticate($username, $passwd);

        global $user;

        $q = "SELECT * FROM schoollms_schema_userdata_access_profile WHERE user_id = $user_id";

        //echo "SQL $q <br>";

        $data->execSQL($q);

        if ($data->numrows > 0){
            $row = $data->getRow();
            $name = $row->name;
            $surname = $row->surname;
            $user_type = $row->type_id;
            $access_id = $row->access_id;
            $school_id = $row->school_id;
            switch ($user_type) {
                case 2://Learner
                    $table = "schoollms_schema_userdata_learner_schooldetails";   
                    $username = "$surname $name";
                    $passwd = "learn123";
                    $role = "learner";

                    break;

                case 4://Teacher
                    $table = "schoollms_schema_userdata_teacher_schooldetails";   
                    $username = "$name $surname";
                    $passwd = "teach123";
                    $role = "educator";
                    break;

                default:
                    break;
            }

            //echo "USERNAME $username <br>";

             switch ($role){
                case 'learner':
                    $og_role = $role;
                    break;

                case 'educator':
                    $og_role = 'teacher';
                    break;
            }

            $email = "$access_id@schoollms.net";
            $q = "SELECT * FROM users WHERE name = '$username' AND mail = '$email'";
            $data->execSQL($q);
            $numrows = $data->numrows;
            //echo "NUM $numrows LMS ACCOUNT SQL $q <br>";

            if ($numrows > 0){

                $row = $data->getRow();
                $uid = $row->uid;
                $account = user_load($uid);

                $q = "SELECT * FROM $timetable_db.$table WHERE user_id = $user_id AND year_id = $year_id";

                //echo "SQL1 $q <br>";

                $result = $data->exec_sql($q, "array");

                $numrows = count($result);
                
                //var_dump($result);
                //echo "SQL1 NUMROWS $numrows <br>";      
                foreach ($result as $key=>$row){

                    //echo "LOOP SQL1";
                    switch($user_type){
                        case 2://Learner
                            $subject_id = 0;
                            $class_id = $row["class_id"];
                            $grade_id = $row["grade_id"];

                            $q = "SELECT * FROM $timetable_db.schoollms_schema_userdata_school_classes WHERE school_id = $school_id AND class_id = $class_id";
                            $data2->execSQL($q);
                            $row1 = $data2->getRow();
                            $class = trim($row1->class_label);

                            $sql = "select * from node where upper(title) like upper('%$class%') and type = 'class'";

                            add_user_group($data3, $sql, $account, $user, $og_role, "class", "$class");

                            
                            add_user_class_subjects($data3, $account, $user, $school_id, $og_role, $user_id, $class_id);
                            break;

                        case 4://Teacher
                            $subject_id = $row["subject_id"];
                            $class_id = $row["class_id"];
                            $grade_id = $row["grade_id"];

                            if ($class_id > 0){
                                $q = "SELECT * FROM $timetable_db.schoollms_schema_userdata_school_classes WHERE school_id = $school_id AND class_id = $class_id";
                                $data2->execSQL($q);

                                //echo "SQL2 $q <br>";
                                $row1 = $data2->getRow();
                                $class = trim($row1->class_label);

                                $sql = "select * from node where upper(title) like upper('%$class%') and type = 'class'";

                                add_user_group($data3, $sql, $account, $user, $og_role, "class", "$class");
                            }

                            $q = "SELECT * FROM $timetable_db.schoollms_schema_userdata_school_subjects WHERE subject_id = $subject_id";
                            $data2->execSQL($q);
                            $row1 = $data2->getRow();
                            $subject = trim($row1->subject_title);

                            $sql = "select * from node where upper(title) like upper('%$subject%$grade_id%') and type = 'course'";

                            //echo "SQL3 $sql <br>";

                            add_user_group($data3, $sql, $account, $user, $og_role, "course", "$subject%$grade_id");
                            //echo "ADD COMPLETED <br>";
                            break;

                    }

                }
            }
        }        
        break;
       
        
}


        
function add_user_class_subjects($data, $account, $school_id, $og_role, $user_id, $class_id){
    
    $data2 = $data; 
    
    $data->execSQL($sql);
    $row = $data->getRow();
    $nid = $row->nid;
    $q = "SELECT * FROM field_data_opigno_class_courses WHERE entity_id = $nid";
    $result = $data->exec_sql($sql, "array");
    foreach($result as $key=>$row){
        $gid = $row["opigno_class_courses_target_id"];

        og_group('node', $gid, array(
                "entity type"       => "user",
                "entity"            => $account,
                'state'             => OG_STATE_ACTIVE,
                "membership type"   => OG_MEMBERSHIP_TYPE_DEFAULT,
            ));

        // = $node->nid;
        $q = "SELECT * FROM og_role WHERE gid = $gid AND name = '$og_role'";

        $data->execSQL($q);
        $row1 = $data2->getRow();
        $rid = $row1->rid;
        // Changes the users role in the group (1 = non-member, 2 = member, 3 = administrator member)
        //echo  "<br/>START : ". time();
        og_role_grant('node', $gid, $account->uid, $rid);

    }
}

function add_user_group($data, $sql, $account, $user, $og_role, $type, $type_details){
    
    //echo "ADD GROUP <BR>";
    $data2 = $data;
    $result = $data->exec_sql($sql, "array");
    
    $numrows = $data->numrows;
 
    if ($numrows > 0){           
        foreach($result as $key=>$node)
        {
            //$group = og_get_group("node", $row->nid);
            og_group('node', $node["nid"], array(
                    "entity type"       => "user",
                    "entity"            => $account,
                    'state'             => OG_STATE_ACTIVE,
                    "membership type"   => OG_MEMBERSHIP_TYPE_DEFAULT,
                ));

            $gid = $node["nid"];
            $q = "SELECT * FROM og_role WHERE gid = $gid AND name = '$og_role'";

            $data2->execSQL($q);
            $row = $data2->getRow();
            $rid = $row->rid;
            // Changes the users role in the group (1 = non-member, 2 = member, 3 = administrator member)
            //echo  "<br/>ADD ROLE START : ". time();
            og_role_grant('node', $node["nid"], $account->uid, $rid);
            //echo  "<br/>END : ". time();
            //drupal_set_message(t("Finished"));
        }
    } else {
        switch ($type) {
            case "class":
                add_class($user, $type_details);
                add_user_group($data, $sql, $account, $user, $og_role, "course", $type_details);
                break;
            
            case "course":
                add_subject($user, $type_details);
                add_user_group($data, $sql, $account, $user, $og_role, "course", $type_details);
                break;

            default:
                break;
        }
    }
}

function add_class($user, $details){
    
    $title = $details;
    $details_tokens = explode(" ", $details);
    
    if (strtoupper($details_tokens[0]) == "CLASS"){
        
        $grade_id = strlen($details_tokens[1]) == 3 ? substr($details_tokens[0], 0, 2) : substr($details_tokens[0], 0, 1); 
    } else {
        
        $grade_id = 0;
        $subject = "";
        foreach ($details_tokens as $key => $value) {
            if (is_numeric($value)){
                $grade_id = $value;
                break;
            }
            
            $subject .= "$value ";
        }
        $subject = trim($subject);
        
    }
    
    $node = new stdClass();
    $node->type = 'class';
    node_object_prepare($node);

    $node->title = "$title"; //. date('c');
    $node->language = 'und';
    $node->uid = $user->uid; 
    $node->status = 0; //(1 or 0): published or not
    $node->revision = FALSE;
    $node->promote = 0; //(1 or 0): promoted to front page
    $node->comment = 0; // 0 = comments disabled, 1 = read only, 2 = read/write
    $node->sticky = 0;
    $node->log = NULL;
    $node->created  = time() - (rand( 1,240) * 60);
    $node->field_group_group[$node->language][]['field']['group_group_value'] = 1;
    $node->field_group_access[$node->language][]['field']['group_access_value'] = 2;
    $node->field_requires_validation[$node->language][]['field']['requires_validation_value'] = 1;
    $node->field_anomymous_visibility[$node->language][]['field']['anomymous_visibility_value'] = 0;
    $node->field_catalogue_visibility[$node->language][]['field']['catalogue_visibility_value'] = 0;
    //FOREACH CORE SUBJECT IN grade_no
    
    //$q = "SELECT * FROM node WHERE ";
    $node->field_opigno_class_courses[$node->language][]['opigno_class_courses_target_id'] = $subject_nid;
    //$node->field_opigno_class_courses[$node->language][]['target_type'] = "course";
    $node->field_class_quota[$node->language][]['field']['class_quota_value'] = -1;
    //$node->opigno_course_tools[$node->language][5]['field']['columns']['tool'] = "";
    //$node->opigno_course_tools[$node->language][0]['field']['tool'] = '';
    $node = node_submit($node);
    node_save($node);
}

function add_subject($user, $details){
    
    $details_tokens = explode("%", $details);
    $subject_name = $details_tokens[0];
    $grade_no = $details_tokens[1];
    
    $term_no = 1;
    //$subject_nid = array ();
    while ($term_no <= 4){  
        //echo "ADD $add_new_subject SUBJECT $subject_name Grade $grade_no - Term $term_no";
        $node = new stdClass();
        $node->type = 'course';
        node_object_prepare($node);
//
        $node->title = "$subject_name Grade $grade_no - Term $term_no"; //. date('c');
        $node->language = 'und';
        $node->uid = $user->uid; 
        $node->status = 0; //(1 or 0): published or not
        $node->revision = FALSE;
        $node->promote = 0; //(1 or 0): promoted to front page
        $node->comment = 0; // 0 = comments disabled, 1 = read only, 2 = read/write
        $node->sticky = 0;
        $node->log = NULL;
        $node->created  = time() - (rand( 1,240) * 60);
        $node->field_group_group[$node->language][]['field']['group_group_value'] = 1;
        $node->field_opigno_commerce_price[$node->language][]['field']['opigno_commerce_price_amount'] = 0;
        $node->field_opigno_commerce_price[$node->language][]['field']['opigno_commerce_price_currency_code'] = 'ZAR';
        $node->field_opigno_commerce_price[$node->language][]['field']['opigno_commerce_price_data'] = 'a:1:{s:10:"components";a:0:{}}';
        $node->field_group_access[$node->language][]['field']['group_access_value'] = 2;
        $node->field_requires_validation[$node->language][]['field']['requires_validation_value'] = 1;
        $node->field_anomymous_visibility[$node->language][]['field']['anomymous_visibility_value'] = 0;
        $node->field_opigno_course_categories[$node->language][]['field']['opigno_course_categories_tid'] = 23;//MUST FIND WAY TO ASSOCIATE GRADE DATA
        $node->field_field_school_term[$node->language][]['field']['field_school_term'] = "Term $term_no";
        $node->field_opigno_course_tools[$node->language][]['field']['opigno_course_tools_tool'] = 'quiz';
        $node->field_opigno_course_tools[$node->language][]['field']['opigno_course_tools_tool'] = 'quiz_import';
        $node->field_opigno_course_tools[$node->language][]['field']['opigno_course_tools_tool'] = 'tft';
        $node->field_opigno_course_tools[$node->language][]['field']['opigno_course_tools_tool'] = 'video';
        $node->field_opigno_course_tools[$node->language][]['field']['opigno_course_tools_tool'] = 'poll';
        $node->field_opigno_course_tools[$node->language][]['field']['opigno_course_tools_tool'] = 'forum';
        $node->field_opigno_course_tools[$node->language][]['field']['opigno_course_tools_tool'] = 'opigno_group_statistics';
        $node->field_opigno_course_tools[$node->language][]['field']['opigno_course_tools_tool'] = 'in_house';
        $node->field_opigno_course_tools[$node->language][]['field']['opigno_course_tools_tool'] = 'audio';
        $node->field_course_quota[$node->language][]['field']['course_quota_value'] = -1;
        //$node->opigno_course_tools[$node->language][5]['field']['columns']['tool'] = "";
        //$node->opigno_course_tools[$node->language][0]['field']['tool'] = '';
        $node = node_submit($node);
        node_save($node);

//        $q = "SELECT nid FROM node WHERE title = '$subject_name Grade $grade_no - Term $term_no'";
//
//        $subject_nid["$subject_name Grade $grade_no - Term $term_no"] = db_query($q)->fetchField();

        $term_no++;
    }
}

function remove_user_group($gid, $user){
    
    og_ungroup('node', $gid, 'user', $user->uid);
}