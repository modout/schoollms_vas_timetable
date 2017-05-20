<?php

/*
 * Copyright (C) 2014 Modise Makhetha <modise@ekasiit.com>
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License or more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 */

function school_lms_elt_activity_service($message){

    $activity_message = explode(":", $message);
    
    $message_action = $activity_message[0];
    
    switch ($message_action){
        
    }
}

function school_lms_gps_service($message){
    
    $gps_message = explode(":", $message);
    
    $message_type = $gps_message[0];
    
    switch ($message_type){
    
        case 'history':
            $device_id = $gps_message[1];
            $gps_data = $gps_message[2];
            
            if ($device_id != '1'){
                $table = "schoollms_schema_userdata_gps_history";

                if (!db_table_exists($table)){
                    $q = "CREATE TABLE {$table} (device_id blob, gps_history longblob)";
                    db_query($q);
                };

                $q = "SELECT gps_history FROM {$table} WHERE device_id = '$device_id'";

                $result = db_query($q);

                $num = $result->rowCount();

                if ($num > 0){
                  $history = $result->fetchField();

                  if (strpos($history, "#")){
                      //Verify History is New
                      $history_tokens = explode("#", $history);

                      $found = false;
                      foreach ($history_tokens as $key => $history_item) {
                          if (strcmp($gps_data, $history_item) == 0){
                              $found = true;
                          }
                      }
                  } elseif (strcmp($gps_data, $history) == 0){
                      $found = true;
                  }

                  if (!$found){
                      $history = $gps_data."#".$history;
                      $q = "UPDATE {$table} SET gps_history = '$history' WHERE device_id = '$device_id'";
                      db_query($q);
                  }

                } else {
                    $q = "INSERT INTO {$table} VALUES ('$device_id', '$gps_data')";
                    db_query($q);
                }
            };
            break;
            
        case 'school_id':
        case 'device_id':    
            switch ($message_type) {
                case 'school_id':
                    $school_id = $gps_message[1];
            
                    $q = "SELECT device_uuid FROM schoollms_schema_userdata_device_tracker WHERE school_id = '$school_id'";

                    $result = db_query($q);
            
                    $device_id = $result->fetchField();
                    break;

                case 'device_id':
                    $device_id = $gps_message[1];
    

                    break;
                default:
                    break;
            }
    
            $device_state_table = "schoollms_userdata_state_".$device_id;
    
            $q = "SELECT state_data FROM $device_state_table WHERE state_path = 'gps'";
    
            $result = db_query($q);
    
            $num = $result->rowCount();
    
            if ($num > 0){
                $gps_data = $result->fetchField();
       
                $gps_temp = explode(",", $gps_data);
                $lat = (double) $gps_temp[0];
                $longi = (double) $gps_temp[1];
                $timestamp = (int) ceil($gps_temp[2]/1000);
                $time_stamp = date("Y-m-d H:i:s", $timestamp);
                $accuracy = $gps_temp[3];
     
        
                switch ($message_type) {
                    case 'school_id':
                        $gps = "$lat#$longi#$time_stamp#$accuracy";

                        break;

                    case 'device_id':
                        $gps = "Latitude: $lat, Longitude: $longi, TimeStamp: $time_stamp, Accuracy: $accuracy (metres)";
    
                
                        break;
            
                    default:
                        break;
                }
       
       
        
            } else {
                $gps = "NOT FOUND";
            }
            return $gps;
            break;
            
        case 'update':
           
            $gps = $gps_message[2];
            $device_id = $gps_message[1];
            
            $q = "UPDATE {schoollms_schema_userdata_device_info} SET device_gps = '$gps' WHERE device_uuid = '$device_id'";

            $result = db_query($q);

            $q = "UPDATE {schoollms_schema_userdata_device_tracker} SET gps = '$gps' WHERE device_uuid = '$device_id'";

            $result = db_query($q);
            
            $gps_temp = explode(",", $gps);
            $lat = (double) $gps_temp[0];
            $longi = (double) $gps_temp[1];
            $time_stamp = $gps_temp[2];
            $accuracy = $gps_temp[3];

            $location = "$longi $lat";

            $q = "SELECT nid FROM node WHERE title = '$device_id'";

            $result = db_query($q);
            $entity_id = $result->fetchField();

            //cho "EID = $entity_id";
            
            $q = "UPDATE field_data_field_location SET field_location_wkt = 'POINT($location)', field_location_geo_type = 'point', field_location_lat = $lat,  field_location_lon = $longi, field_location_left = $lat, field_location_top = $longi, field_location_right = $lat, field_location_bottom = $longi, field_location_accuracy = $accuracy where entity_id = $entity_id";

            $result = db_query($q);
            break;
        
        default:
            break;
    }
    
    
}

function school_lms_user_details_service($message){
    
    $detail_tokens = explode(":", $message);
    
    $action = $detail_tokens[0];
    $type = $detail_tokens[1];
    
    //echo "MESSAGE $action $type";
    
    switch($action){
        
        case 'register':
            
            switch ($type) {
                case 'access':
                    $device_id = $detail_tokens[2];
                    $data = $detail_tokens[3];
         
                     //Get User Details
                    $data_tokens = explode(",", $data);
                    
                    $details = array ();
                    
                    foreach ($data_tokens as $key => $item) {
                        
                        $item_tokens = explode("=", $item);
                        
                        $field = $item_tokens[0];
                        
                        $value = $item_tokens[1];
                        
                        $details["$field"] = $value;
                        
                    }
                    
                    $username = $details['name']." ".$details['surname'];
                    $user_id = $details["user_id"];
                    $photo = $details["photo"];
                    //Register Device Data
                    //Create Password
                    $rpassword = strrev($device_id);
                    $password = "54o0$rpassword"."1Lw$";
                    $message = "device_id=$device_id,$data,secret=$password";
                    $result = school_lms_add_device_data($message);
                   
                    //GET SCHOOL ID
                    $q = "SELECT school_id FROM schoollms_schema_userdata_access_profile WHERE user_id = $user_id";
                    $result = db_query($q);
                    $school_id = $result->fetchField();
 
                    //SAVE PHOTO
                    $result = db_query("SELECT * FROM schoollms_schema_userdata_user_photo WHERE user_id = $user_id");
                    
                    $num = $result->rowCount();
                    
                    if ($num > 0){
                        $q = "UPDATE schoollms_schema_userdata_user_photo SET photo = '$photo' WHERE user_id = $user_id";
                    } else {
                        $q = "INSERT INTO schoollms_schema_userdata_user_photo VALUES ($user_id, '$photo')";
                    }
                    //$return = $q;
                    //Get SchoolID
                    //$q = "SELECT username FROM schoollms_schema_securitydata_access WHERE device_uuid = '$device_id'";
                    $result = db_query($q);

                    //SAVE PHOTO ON EACH PORTAL`
                    $photo_filename = "user_$user_id"."_device_$device_id"."_photo.png";
                    $filepath = "/var/www/html/learn/sites/default/files/$photo_filename"; 
                    $data = base64_decode($photo); // decode an image        
                    file_put_contents($filepath, $data);
                    
		    //CHECK IF USER_ID EXISTS AND UPDATE ACCORDINGLY
                    $result = db_query("SELECT identities FROM schoollms_schema_securitydata_user_identities WHERE user_id = $user_id");


                    //$result = db_query("SELECT * FROM users WHERE uid = $user_id");
//                    
                    $num = $result->rowCount();
//                    
                    /*if ($num > 0){
                        $return = "$username user was already registered";
                    } else {
                        $return = "$username user registered for the first time";
                    }*/
                    
                    if ($num > 0){
                        //GET USER ID FOR SUPPORT
                        $identities = $result->fetchField();

                        $id_tokens = explode(",", $identities);

			$uid_tokens = explode(":", $id_tokens[0]);

			$uid = $uid_tokens[1];

                        $mail = "$device_id@schoollms.net";
                        $q = "UPDATE users SET name = '$username', mail = '$mail' WHERE uid = $uid";
                        
                    } else {
                        //Get Device 
                        //$q = "SELECT password FROM schoollms_schema_securitydata_access WHERE device_uuid = '$device_id'";

                        //$result = db_query($q);

                        //$device_password = $result->fetchField();

                        //This will generate a random password, you could set your own here
                        //$password = user_password(8);
                        $password = "learn123";

                        //echo "SCHOOLID $school_id PASSWORD $password";

                        //set up the user fields
                        $fields = array(
                            'name' => $username, //'user_name',
                            'mail' => "$device_id@schoollms.net",
                            'pass' => $password,
                            'status' => 0,
                            'init' => 'email address',
                            'roles' => array(
                                DRUPAL_AUTHENTICATED_RID => 'learner',
                            ),
                        );

                        //the first parameter is left blank so a new user is created
                        $account = user_save('', $fields);

                        // If you want to send the welcome email, use the following code

                        // Manually set the password so it appears in the e-mail.
                        $account->password = $fields['pass'];

                        $email = "$device_id@schoollms.net";
                        // Send the e-mail through the user module.
                        //drupal_mail('user', 'register_approval_required', $email, NULL, array('account' => $account), variable_get('site_mail', '<a href="mailto:info@schoollms.net">info@schoollms.net</a>')); 
                        
                        $uid = $account->uid;
                        //STORE SUPPORT USER_ID AGAINST SCHOOLLMS USER_ID
                        $q = "INSERT INTO schoollms_schema_securitydata_user_identities VALUES ($user_id,'support:$uid,learn:$uid,teach:$uid,train:$uid,parent:$uid')";
                        $result = db_query($q);
                        
                        //$account = user_load($uid);

                        $filepath = $filepath;

                        $file = (object) array(
                            'uid' => $uid,
                            'uri' => $filepath,
                            'filemime' => file_get_mimetype($filepath),
                            'status' => 1,
                          );

                        $file = file_copy($file, 'public://');

                        $edit['picture'] = $file;
                        user_save($account, $edit);
                        
                    }

		    $result = db_query("SELECT * FROM users WHERE uid = $uid");
                    
                    foreach ($result as $key => $row) {
                        $uid = $row->uid;
                        $name = $row->name;
                        $pass = $row->pass;
                        $mail = $row->mail;
                        $signature = $row->signature;
                        $signature_format = $row->signature_format;
                        $created = $row->created;
                        $access = $row->access;
                        $login = $row->login;
                        $status = $row->status;
                        $timezone = $row->timezone;
                        $language = $row->language;
                        $picture = $row->picture;
                        $init = $row->init;
                        $data = $row->data;
                        
                    }
                     
		    $result2 = db_query("SELECT * FROM file_managed WHERE fid = $picture");

                    // Change active database.
                    
                    $db_host = 'localhost';

                    // define database name, user name and password
                    switch($school_id){
                        case 1:
                            $db_name = 'school_lms_qa_schools_sphs_learn';
                            $db_user = 'root';
                            $db_pwd = '$0W3t0';
                            break;
                        
                        case 2:
                            $db_name = 'school_lms_prod_schools_mhs_learn';
                            $db_user = 'root';
                            $db_pwd = 'westcon';
                            break;
                      
                    }
                   
                    // open database connection and select database
                    //$link = mysql_connect('localhost', 'root', '');
                    $db_conn = mysql_connect($db_host, $db_user, $db_pwd);
                    mysql_select_db($db_name, $db_conn);
                    
                    $sql = "INSERT INTO users VALUES ($uid,'$name','$pass','$mail','','$signature','$signature_format','$created','$access','$login','$status','$timezone','$language','$picture','$init','$data')";
                    
                    $result2 = mysql_query($sql, $db_conn);

                    foreach ($result2 as $key => $row) {
                        $fid = $row->fid;
                        $uid = $row->uid;
                        $filename = $row->filename;
                        $uri = $row->uri;
                        $filemime = $row->filemime;
                        $filesize = $row->filesize;
                        $status = $row->status;
                        $timestamp = $row->timestamp;
                        $type = $row->type;
                        
                    }
                    
                    $sql = "INSERT INTO file_managed VALUES ($fid, $uid,'$filename','$uri','$filemime','$filesize','$status','$timestamp','$type')";
                    
                    $db_result = mysql_query($sql, $db_conn);
                    
                    mysql_query('commit', $db_conn);
                    mysql_close($db_conn);
//                    
//                    $result = db_query("SELECT * FROM users WHERE uid = $user_id");
//                    
//                    foreach ($result as $key => $row) {
//                        $user_id = $row->uid;
//                        $name = $row->name;
//                        $pass = $row->pass;
//                        $mail = $row->mail;
//                        $signature = $row->signature;
//                        $signature_format = $row->signature_format;
//                        $created = $row->created;
//                        $access = $row->access;
//                        $login = $row->login;
//                        $status = $row->status;
//                        $timezone = $row->timezone;
//                        $language = $row->language;
//                        $picture = $row->picture;
//                        $init = $row->init;
//                        $data = $row->data;
//                        
//                    }
//                    
//                    
//                    // Change active database.
//                    
//                    $db_host = 'localhost';
//
//                    // define database name, user name and password
//                    $db_name = 'school_lms_qa_schools_sphs_learn';
//                    $db_user = 'root';
//                    $db_pwd = '$0W3t0';
//
//                    // open database connection and select database
//                    //$link = mysql_connect('localhost', 'root', '');
//                    $db_conn = mysql_connect($db_host, $db_user, $db_pwd);
//                    mysql_select_db($db_name, $db_conn);
//                    
//                    $sql = "INSERT INTO users VALUES ($uid,'$name','$pass','$mail','','$signature','$signature_format','$created','$access','$login','$status','$timezone','$language','$picture','$init','$data')";
//                    
//                    $db_result = mysql_query($sql, $db_conn);
                    /*
                     * db_set_active('learn');
                    // Run queries.
                    //uid,name,pass,mail,theme,signature,signature_format,created,access,login,status,timezone,language,picture,init,data
                    $result = db_query("SELECT * FROM users WHERE uid = $user_id");
                    
                    $num = $result->rowCount();
                    
                    if ($num > 0){
                        $q = "UPDATE users SET name = '$name', pass = '$pass', mail = '$mail' WHERE uid = $user_id";
                    } else {
                        $q = "INSERT INTO users VALUES ($uid,'$name','$pass','$mail','','$signature','$signature_format','$created','$access','$login','$status','$timezone','$language','$picture','$init','$data')";
                    }
                    $result = db_query($q);
                    
                    
                    // Change active database.
                    db_set_active('teach');
                    // Run queries.
                    //uid,name,pass,mail,theme,signature,signature_format,created,access,login,status,timezone,language,picture,init,data
                    $result = db_query("SELECT * FROM users WHERE uid = $user_id");
                    
                    $num = $result->rowCount();
                    
                    if ($num > 0){
                        $q = "UPDATE users SET name = '$name', pass = '$pass', mail = '$mail' WHERE uid = $user_id";
                    } else {
                        $q = "INSERT INTO users VALUES ($uid,'$name','$pass','$mail','','$signature','$signature_format','$created','$access','$login','$status','$timezone','$language','$picture','$init','$data')";
                    }
                    $result = db_query($q);
                    
                    // Change active database.
                    db_set_active('train');
                    // Run queries.
                    //uid,name,pass,mail,theme,signature,signature_format,created,access,login,status,timezone,language,picture,init,data
                    $result = db_query("SELECT * FROM users WHERE uid = $user_id");
                    
                    $num = $result->rowCount();
                    
                    if ($num > 0){
                        $q = "UPDATE users SET name = '$name', pass = '$pass', mail = '$mail' WHERE uid = $user_id";
                    } else {
                        $q = "INSERT INTO users VALUES ($uid,'$name','$pass','$mail','','$signature','$signature_format','$created','$access','$login','$status','$timezone','$language','$picture','$init','$data')";
                    }
                    $result = db_query($q);
                    
                    // Change active database.
                    db_set_active('parent');
                    // Run queries.
                    //uid,name,pass,mail,theme,signature,signature_format,created,access,login,status,timezone,language,picture,init,data
                    $result = db_query("SELECT * FROM users WHERE uid = $user_id");
                    
                    $num = $result->rowCount();
                    
                    if ($num > 0){
                        $q = "UPDATE users SET name = '$name', pass = '$pass', mail = '$mail' WHERE uid = $user_id";
                    } else {
                        $q = "INSERT INTO users VALUES ($uid,'$name','$pass','$mail','','$signature','$signature_format','$created','$access','$login','$status','$timezone','$language','$picture','$init','$data')";
                    }
                    $result = db_query($q);
                    // IMPORTANT: return current active database to the default one. If you
                    // don't do this, everything after that point will be executed against the
                    // above database, and cause errors most probably. If you are connecting
                    // multiple Drupal installations, you can cause a pretty big mess if data is
                    // partially saved in the other database.
                    // Always double check that you have this line!
                    db_set_active();
                     * 
                     */
                    break;

                default:
                    break;
            }
            break;
        
        case 'get':
            
            switch($type){
                case 'field':
                    $field_required = $detail_tokens[2];
                    $table = $detail_tokens[3];
                    $field_search = $detail_tokens[4];
                    $value_search = $detail_tokens[5];
            
                    $q = "SELECT $field_required FROM {$table} WHERE $field_search = '$value_search'";
                    $result = db_query($q);
                    $num = $result->rowCount();
            
                    if ($num > 0){
                        $return = $result->fetchField();
                    } else {
                        $return = 'none';
                    }
                    break;
                    
                case 'details':
                    $access_id = $detail_tokens[2];
                    $vas_table = $detail_tokens[3];

                    $q = "SELECT * FROM $vas_table WHERE access_id = '$access_id'";
                    $result = db_query($q);
                    
                    $num = $result->rowCount();
            
                    //echo "NUM $num";
                    
                    $return = array ();
                    if ($num > 0){
                        $return['details-status'] = 'found';
                        foreach ($result as $key => $row) {
                            $user_id = $row->user_id;
                            $name = $row->name;
                            $surname = $row->surname;
                            //$grade = $row->grade;
                            //$classroom = $row->classroom;
                            
                            $return['user_id'] = $user_id;
                            $return['name'] = $name;
                            $return['surname'] = $surname;
                            //$return['grade'] = $grade;
                            //$return['classroom'] = $classroom;
                            break;
                        }
                        $return = $return;
                        
                    } else {
                        $return['details-status'] = 'none';
                    }
                    break;
            }
            break;
        
        case 'check':
            
            switch ($type) {
                case 'access_id':
                    $access_id = $detail_tokens[2];
                    $vas_table = $detail_tokens[3];

                    $q = "SELECT * FROM {$vas_table} WHERE access_id = '$access_id'";
                    $result = db_query($q);
                    
                    $num = $result->rowCount();
                    
                    if ($num > 0){
                        $return = true;
                    } else {
                        $return = false;
                    }
                    break;

                default:
                    break;
            }
        default:
            break;
    }
    
    return $return;
}

/**
 * 
 * @param type $message
 */
function school_lms_schema_service($message){
    
    $schema_tokens = explode(":", $message);
    
    $action = $schema_tokens[0];
     
    //$id = $schema_tokens[2];
    
    switch ($action) {
        case 'get_list':
            $schema = $schema_tokens[1];
            //echo "ACTION $action SCHEMA $schema";
            
            $q = "SELECT table_id, tablename FROM {$schema}";
            $result = db_query($q);
            
            $return = array ();
            foreach ($result as $key => $row) {
                $table_id = $row->table_id;
                $table = $row->tablename;
                $return[$table_id] = "$table";
                //array_push($return, $row->tablename);
            }
            break;

        case 'get_item':
            $schema = $schema_tokens[1];
            
            $table_tokens = explode("#", $schema_tokens[2]);
            
            $item_type = $table_tokens[0];
            
            switch ($item_type){
                
                case 'table':
                    $table_id = $table_tokens[1];
                    $q = "SELECT tablename FROM {$schema} WHERE table_id = $table_id";
                    //echo "QUERY $q";    
            
                    $result = db_query($q);
            
                    $num = $result->rowCount();
            
                    if ($num > 0){
                        $return = $result->fetchField();
                    } else {
                        $return = 0;
                    }
                    break;
                    
                case 'field':
                    $field_id = $table_tokens[1];
                    $tablename = $table_tokens[2];
                    
                    $message = "get_field:$schema:$tablename#$field_id";
                    
                    $return = school_lms_schema_service($message);
                    break;
            }
            //echo "RETURN $return";
            break;
        
        case 'get_fields':
            //"get_fields:$schema:$selected_table";
            $schema = $schema_tokens[1];
            $table_id = $schema_tokens[2];
            if (is_numeric($table_id)){
                $q = "SELECT columns FROM {$schema} WHERE table_id = $table_id";
            } else {
                $q = "SELECT columns FROM {$schema} WHERE tablename = '$table_id'";
            }
            //return "QUERY $q";
            
            $result = db_query($q);
            
            $num = $result->rowCount();
            
            if ($num > 0){
                $columns = $result->fetchField();
                $column_tokens = explode(",", $columns);
                $columns = array ();
                foreach($column_tokens as $key => $column){
                        $col_tokens = explode("-", $column);
                        array_push($columns, $col_tokens[0]);
                }
                
                if (is_numeric($table_id)){
                    $return = $columns;
                } else {
                    $return = $columns; 
                }
            } else {
                $return = 0;
            }
            break;
        
        case 'check_field_form':
            $table_form = $schema_tokens[1];
            $field = $schema_tokens[2];
            
            $q = "SELECT * FROM {$table_form} WHERE field = '$field'";
            $result = db_query($q);
            $num = $result->rowCount();
            if ($num > 0){
                $return = true;
            } else {
                $return = false;
            }
            break;
                    
        case 'get_field':
            $schema = $schema_tokens[1];
            $table_data = $schema_tokens[2];
            $table_tokens = explode("#", $table_data);
            $table = $table_tokens[0];
            $field_id = $table_tokens[1];
            
            $q = "SELECT columns FROM {$schema} WHERE tablename = '$table'";
            //echo "QUERY $q";
            $result = db_query($q);
            
            $num = $result->rowCount();
            
            if ($num > 0){
                $columns = $result->fetchField();
                $column_tokens = explode(",", $columns);
                foreach($column_tokens as $key => $column){
                        if ($key == $field_id){
                            $col_tokens = explode("-", $column);
                            $return = $col_tokens[0];
                            break;
                        }
                }
            } else {
                $return = 0;
            }
            break;
            
        case 'get_field_details':
            $schema = $schema_tokens[1];
            $table_data = $schema_tokens[2];
            $table_tokens = explode("#", $table_data);
            $table = $table_tokens[0];
            $field_detail = $table_tokens[1];
            $field_search_tokens = explode("%", $table_tokens[2]);
            $field_search_field = $field_search_tokens[0];
            $field_search_value = $field_search_tokens[1];
            
            $return = 0;
            switch ($field_detail) {
                
                case 'field_id':
                    $q = "SELECT columns FROM {$schema} WHERE tablename = '$table'";
                    //echo "QUERY $q";
                    $result = db_query($q);
            
                    $num = $result->rowCount();
            
                    if ($num > 0){
                        $columns = $result->fetchField();
                        $column_tokens = explode(",", $columns);
                        foreach($column_tokens as $key => $column){
                            $col_tokens = explode("-", $column);
                            if (strcmp($field_search_value, $col_tokens[0])== 0){
                                $return = $key;
                                break;
                            }
                        }
                    } else {
                        $return = 0;
                    }

                    break;

                default:
                    $return = 0;
                    break;
            }
            break;
            
        case 'get_field_forms':
            $schema = $schema_tokens[1];
            $table = $schema_tokens[2];
            $table_form = $table."_form";
            
            //GET ALL FIELD FORM DETAILs
            $q = "SELECT * FROM {$table_form} ORDER BY field_order ASC";
            
            $result = db_query($q);
            
            $field_forms = array ();
            foreach ($result as $key => $row) {
                $field_name = $row->field;
                $field_label = $row->field_label;
                $field_guide = $row->field_instructions;
                $field_type = $row->field_type;
                $field_autoincrement = $row->field_autoincrement;
                $field_manual = $row->field_manual;
                $field_data_link = $row->field_data_link;
                $field_detail = "$field_name"."|"."$field_label"."|"."$field_guide"."|"."$field_type"."|"."$field_autoincrement"."|"."$field_manual"."|"."$field_data_link";
                
                array_push($field_forms, $field_detail);
            }
            $return = $field_forms;
            break;
            
        case 'get_reference_data':
            $schema = $schema_tokens[1];
            $reference_tablename = $schema_tokens[2];
            $reference_fieldname = $schema_tokens[3];
            $reference_display = $schema_tokens[4];
            
            $q = "SELECT $reference_display FROM {$reference_tablename} ORDER BY $reference_fieldname ASC";
            
            //echo "$q";
            $result = db_query($q);
            
            $num = $result->rowCount();
            
            if ($num > 0){
                $return = array ();
                foreach ($result as $key => $row) {
                    $ref_id = $row->$reference_fieldname;
                    $ref_title = $row->$reference_display;
                    
                    //$return["$ref_id"] = "$ref_title";
                    array_push($return, "$ref_title");
                } 
                
            } else {
                $return = array ('0' => 'None');
                //$return = 0;
            }
            break;
        
        case 'get_result':
            $for = $schema_tokens[1];
            $from = $schema_tokens[2];
            
            $table = "schoollms_data_message_$from";
           
            $q = "SELECT message FROM {$table} WHERE message_to = '$for' AND message_status = 'message_delivered at $for'";
   
            //echo "QUERY $q";
            
            $result = db_query($q);
            
            $num = $result->rowCount();
            
            if ($num > 0){
                $return = $result->fetchField();
            } else {
                $return = 0;
            }
            break;
            
        default:
            break;
    }
    
    return $return;
}
/**
 * 
 * @param type $message
 * @return string
 */
function school_lms_state_service($message){
    
    $state_tokens = explode(":", $message);
    
    $action = $state_tokens[0];
    
    switch ($action){
        
        case 'get':           
    
            $data_exists = count($state_tokens);
            
            //$get_data = false;
            $data = $state_tokens[1];
            $id = $state_tokens[2];
            $field = "state_data";
            $query = " WHERE state_path = '$data'";
            
            //echo "ID IS $id FIELD $field";
            
            //Check if State Table Exists
            $table = "schoollms_userdata_state_$id";

            if (!db_table_exists($table)) {
                $q = "CREATE TABLE $table (state_id int, state_path varchar(255), state_data blob)";
                $result = db_query($q);
            }

            $q = "SELECT state_path FROM {$table}";

            $result = db_query($q);

            $num = count($result->fetchAll());

            if ($num > 0) {
                $q = "SELECT $field FROM {$table} $query";               
                $result = db_query($q);
                $state = "";
                foreach($result as $key => $row){
                    $state = $row->$field;
                    break;
                }
                return $state;
            } else {
                return 'none';
            }
            break;
        
        case 'set':
            $data_exists = count($state_tokens);
            
            if ($data_exists > 3){
                $path = $state_tokens[1];
                $data = $state_tokens[2];
                $id = $state_tokens[3];
            } else {
                $path = $state_tokens[1];
                $id = $state_tokens[2];
                $data = 'none';
            }
            
            //Check if State Table Exists
            $table = "schoollms_userdata_state_$id";

            if (!db_table_exists($table)) {
                $q = "CREATE TABLE $table (state_id int, state_path varchar(255), state_data blob)";
                $result = db_query($q);
            }

            $q = "SELECT state_id FROM {$table} WHERE state_path = '$path'";

            $result = db_query($q);
            
            $num = $result->rowCount();
            
            
            if ($num > 0){
                $id = $result->fetchField();
                $q = "UPDATE {$table} SET state_data = '$data' WHERE state_id = $id";
            } else {
                $q = "SELECT * FROM {$table}";
                $result = db_query($q);
                $num = $result->rowCount();
                $num++;
                $q = "INSERT INTO {$table} VALUES ($num, '$path', '$data')";
            } 
            //echo "QUERY $q";
            switch ($path) {
                case 'gps':
                    $message = "history:$id:$data";
                    school_lms_gps_service($message);
                    
                    
                    break;

                default:
                    break;
            }
            
            $result = db_query($q);
            break;
        
        case 'clear':
            
            break;
    }
    //echo "Got Here";
    return 1;
    
}

/**
 * 
 * @param type $message
 * @return boolean
 */
function school_lms_message_check($message) {

    $id = $message;

    $table = "schoollms_userdata_message_$id";

    if (db_table_exists($table)) {
        $q = "SELECT * FROM {$table}";

        $result = db_query($q);
        $num = count($result->fetchAll());

        if ($num > 0) {
            $message = "set:message_type:user:$id";
            school_lms_state_service($message);
            return true;
        }
    }
    
    $table = "schoollms_systemdata_message_$id";

    if (db_table_exists($table)) {
        $q = "SELECT * FROM {$table}";

        $result = db_query($q);
        $num = count($result->fetchAll());

        if ($num > 0) {
            $message = "set:message_type:system:$id";
            school_lms_state_service($message);
            return true;
        }
    }
    
    $table = "schoollms_securitydata_message_$id";

    if (db_table_exists($table)) {
        $q = "SELECT * FROM {$table}";

        $result = db_query($q);
        $num = count($result->fetchAll());

        if ($num > 0) {
            $message = "set:message_type:security:$id";
            school_lms_state_service($message);
            return true;
        }
    }
    
    $table = "schoollms_appdata_message_$id";

    if (db_table_exists($table)) {
        $q = "SELECT * FROM {$table}";

        $result = db_query($q);
        $num = count($result->fetchAll());

        if ($num > 0) {
            $message = "set:message_type:app:$id";
            school_lms_state_service($message);
            return true;
        }
    }
    return false;
}

/**
 * 
 * @param type $message
 * @return type
 */
function school_lms_exists_service($message) {

    $select_tokens = explode(":", $message);

    $action = $select_tokens[0];

    switch ($action) {

        case 'schema'://Schema Exists
        case 'table'://Table Exists
            $table = $select_tokens[1];
            return db_table_exists($table);
            
            break;

        case 'function'://Function Exists
        default:
    }
}

/* * *
 * 
 */

function school_lms_create_service($message) {

    $select_tokens = explode(" ", $message);

    $type = $select_tokens[1];

    switch ($type) {

        case 'schema'://Create Schema
            $table = strtolower($select_tokens[1]);
            $table = "$table" . "_schema";
            $q = "CREATE TABLE $table (table_id int, tablename blob, columns blob)";
            $result = db_query($q);
            break;

        case 'table'://Create Table
            $table_data = $select_tokens[1];
            $table_tokens = explode("#", $table_data);

            $table = $table_tokens[0];
            $col_tokens = explode(",", $table_tokens[1]);

            $query = "CREATE TABLE $table (";
            foreach ($col_tokens as $key => $tokens) {

                $col_data = explode("-", $tokens);

                $field = $col_data[0];

                $type_data = explode("!", $col_data[1]);
                $temp = "";
                if (count($type_data) > 1) {
                    $type = $type_data[0];
                    $size = $type_data[1];
                    $temp = "$field $type ($size)";
                } else {
                    $type = $type_data[0];
                    $temp = "$field $type";
                }

                if ($key == 0) {
                    $query .= "$temp";
                } else {
                    $query .= ", $temp";
                }
            }
            $query .= ")";
            $result = db_query($q);
            break;

        default:
    }
    return $result;
}

/**
 * 
 * @param type $message
 * 
 */
function school_lms_add_device_data($message) {

    $device_data = explode(",", $message);

    $required = 0;
    foreach ($device_data as $key => $data) {
        $data_tokens = explode("=", $data);

        $field = $data_tokens[0];
        $value = $data_tokens[1];

        switch ($field) {

            
            case 'surname':
                $surname = $value;
                $required++;
                break;
            
            case 'name':
                $name = $value;
                $required++;
                break;
            
            case 'user_id':
                $user_id = $value;
                $required++;
                break;
            
            case 'access_id':
                $username = $value;
                $required++;
                break;

            case 'device_id':
                $required++;
                $device_id = $value;
                break;

            case 'secret':
                $required++;
                $password = $value;
                break;

            default:
        }
        if ($required == 6) {
            break;
        }
    }
    //if ($required == 6) {
        $username = "$name $surname";
        
        //Check IF USER ALREADY REQUESTED ACCESS
        $q = "SELECT * FROM {schoollms_schema_securitydata_access} WHERE access_id = $user_id OR device_uuid LIKE '%$device_id%'";
        
        $result = db_query($q);

        $num = $result->rowCount();
        
        if ($num > 0){
            //Check if Device is Similar to Registered
            $q = "SELECT * FROM {schoollms_schema_securitydata_access} WHERE device_uuid LIKE '%$device_id%' AND access_id = $user_id";

            $result = db_query($q);

            $num = $result->rowCount();
            
            if ($num > 0){
                $response = "You already registered with USERNAME: $username - See School Admin";
            } else {
                $device = "$device_id";
                foreach($result as $key => $row){
                    $device .= ",".$row->device_uuid;
                }
                $q = "UPDATE {schoollms_schema_securitydata_access} SET device_uuid = '$device', username = '$username' WHERE access_id = $user_id";
        
                $result = db_query($q);
            }
        } else {
            $q = "INSERT INTO {schoollms_schema_securitydata_access} VALUES ($user_id, '$username', '$password', 'inactive', '$device_id')";
            $result = db_query($q);

            if ($result) {
                $response = 'Waiting for registration approval';
            } else {
                $response = $result;
            }
        }
    //}
}

/**
 * 
 * @param type $message
 * @return boolean
 */
function school_lms_access_register($message) {

    $device_id = $message;

    //Check IF USER ALREADY REGISTERED AND ACTIVATED
    $q = "SELECT * FROM {schoollms_schema_securitydata_access} WHERE device_uuid LIKE '%$device_id%'";

    $result = db_query($q);

    $num = $result->rowCount();

    $response = array ();
    if ($num > 0) {
	$row = $result->fetchObject();
        $response['status'] = $row->status;  
        $response['user_id'] = $row->access_id;
    } else {
        $response['status'] = "$device_id not_registered";
        $response['user_id'] = 0;
    }

    return $response;
}

/**
 * 
 * @param type $message
 * @return type
 */
function school_lms_access_link($message) {

    $msg_tokens = explode(":", $message);

    $username = $msg_tokens[0];

    $password = $msg_tokens[1];

    $device = $msg_tokens[2];

    //Check IF USER ALREADY REQUESTED ACCESS
    $q = "SELECT * FROM {schoollms_schema_securitydata_access} WHERE device_uuid = '$device'";

    $result = db_query($q);

    $num = count($result->fetchAll());

    if ($num > 0) {
        $response = "You already registered with USERNAME: $username - See School Admin";
    } else {
        $q = "SELECT COUNT(access_id) FROM {schoollms_schema_securitydata_access}";
        $result = db_query($q);
        $id = $result->fetchField();
        $id++;
        $q = "INSERT INTO {schoollms_schema_securitydata_access} VALUES ($id, '$username', '$password', 'inactive', '$device')";
        $result = db_query($q);

        if ($result) {
            $response = 'Waiting for registration approval';
        } else {
            $response = $result;
        }
    }

    return $response;
}

/**
 * 
 * @param type $device
 * @return type
 */
function school_lms_access_status($device) {

    $q = "SELECT status FROM {schoollms_schema_userdata_access} WHERE device_uuid = '$device'";

    $result = db_query($q);

    $num = $result->rowCount();

    if ($num > 0) {
        $q = "SELECT status FROM {schoollms_schema_userdata_access} WHERE device_uuid = '$device'";
        $result = db_query($q);
        $status = $result->fetchField();
    } else {
        $q = "SELECT COUNT(access_id) FROM {schoollms_schema_userdata_access}";
        $result = db_query($q);
        $id = $result->fetchField();
        $id++;
        $q = "INSERT INTO {schoollms_schema_userdata_access} VALUES ($id, '$device', 'offline')";
        $result = db_query($q);
        $status = 'offline';

        //Check IF Device Already Tried To Sign In
        $table = "schoollms_userdata_message_$device";
        $server = "schoollms";
        $q = "SELECT message_status FROM $table WHERE message_to = '$server'";

        $result = db_query($q);

        $num = $result->rowCount();

        if ($num > 0) {
            //Read Status
            $q = "SELECT message_status FROM $table WHERE message_to = '$server'";

            $result = db_query($q);
            $message_status = $result->fetchField();

            $msg_tokens = explode(" ", $message_status);
            $status = $msg_tokens[0];
            $from = $msg_tokens[2];

            //Update FROM That MESSAGE RECEIVED;
            $updater = "$server:$device";
            school_lms_response_update($updater);
        } else {
            //Try to Sign-IN
            $message = "type:user?from:$device,to:$server,message:search schoollms_schema_systemdata_devices device_uuid $device,status:waiting search $device";

            $response = school_lms_message_service($message);
        }
    }

    return $status;
}

/**
 * 
 * @param type $updater
 */
function school_lms_response_update($updater) {

    $tokens = explode(">", $updater);

    $from = $tokens[0];
    $to = $tokens[1];

    $type_tokens = explode("?", $tokens[2]);
    
    $type_data = explode(":", $type_tokens[0]);
    
    $type = $type_data[1];
    
    switch ($type) {
        case 'data':
            $table_prefix = "schoollms_data_message_";
            break;
        
        case 'user':
            $table_prefix = "schoollms_userdata_message_";
            break;
        
        case 'system':
            $table_prefix = "schoollms_systemdata_message_";
            break;
        
        case 'security':
            $table_prefix = "schoollms_securitydata_message_";
            break;
        
        case 'app':
            $table_prefix = "schoollms_appdata_message_";
            break;
        
        default:
            break;
    }
    
    $table = $table_prefix."$from";

    $q = "UPDATE {$table} SET message_status = 'message_delivered at $to' WHERE message_to = '$to'";

    //echo "QUERY $q";
    
    db_query($q);
    
    $table = $table_prefix."$to";
    
    //Remove message from queue and updoate queue index - Depending on type of message
//    $q = "DELETE FROM {$table} WHERE message_to = '$from'";
//    
//    db_query($q);
//    
//    //Re-order message_id
//    $q = "SELECT * FROM {$table}";
//    
//    $result = db_query($q);
//    
//    $num = $result->rowCount();
//    
//    if ($num > 0){
//        $q = "SELECT * FROM {$table}";
//    
//        $result_all = db_query($q);
//        
//        $q = "TRUNCATE {$table}";
//    
//        $result_del = db_query($q);
//        
//        $msg_id = 1;
//        foreach ($result_all as $key => $row) {
//            $message_to = $row->message_to;
//            $message = $row->message;
//            $message_status = $row->message_status;
//            
//            $q = "INSERT INTO {$table} VALUES ($msg_id, '$message_to', '$message', '$message_status'";
//            db_query($q);
//            
//        }
//    }
}

/**
 * 
 * @param type $message
 * @return type
 */
function school_lms_response_service($message) {

    //school_lms_message_serve();
    $type_tokens = explode("?", $message);
    $type_data = explode(":", $type_tokens[0]);
    $type = $type_data[1];

    switch ($type) {

        case 'data':
            $message = $type_tokens[1];
            $msg_tokens = explode(";", $message);
            //Get From
            $from_data = explode(":", $msg_tokens[0]);
            $from = $from_data[1];
            //Get To
            $to_data = explode(":", $msg_tokens[1]);
            $to = $to_data[1];
            //Get Message
            $msg_data = explode(":", $msg_tokens[2]);
            $msg = $msg_data[1];
            //Get Status
            $status_data = explode(":", $msg_tokens[3]);
            $status = $status_data[1];

            $table = "schoollms_data_message_$to";
            $q = "SELECT * FROM {$table} WHERE message_to = '$from'";

            $result = db_query($q);
            $num = count($result->fetchAll());

            $response = "wait";
            
            //echo "RESPONSE NUM $num";
            
            if ($num > 0) {
                //Clear Message To Self and SendFrom
                $q = "SELECT * FROM {$table} WHERE message_to = '$from'";

                $result = db_query($q);
                $row = $result->fetchAssoc();
                $response_from = $to;
                $response_message = $row['message'];
                $response_status = $row['message_status'];
                //$response = array ("$response_from", "$response_message", "$response_status");
                $response_data = explode(" ", $response_message);
                $response = $response_data[0];
                //school_lms_data_message_serve($from);
                //echo "NUM > 0 RESPONSE $response";
                //}
            } else {
                $table = "schoollms_data_message_$from";
                $q = "SELECT message_status FROM {$table} WHERE message_to = '$to'";
                $result = db_query($q);
                $msg_tokens = explode(" ", $result->fetchField());
                $response = $msg_tokens[0];
                //echo "NUM == 0 RESPONSE $response";
                school_lms_data_message_serve($from);
                //$handle = background_process_start('school_lms_message_serve', $from); 
            }
            //
            return $response;
            break;

        case 'system':

            break;

        case 'user':
            $message = $type_tokens[1];
            $msg_tokens = explode(",", $message);
            
            if (strcmp(trim($msg_tokens[0]), "pm_message") == 0){
                $to = $msg_tokens[1];
                $table = "schoollms_userdata_message_$to";
                $q = "SELECT * FROM {$table} WHERE message LIKE '%new_pm_message%'";
                $result = db_query($q);
                $num = $result->rowCount();
                
                $messages = array ();
                if ($num > 0){
                   $messages['found'] = true;
                   $messages['count'] = $num;
                   $messages['messages'] = array ();
                   foreach ($result as $key => $row) {
                       $message_to = $row->message_to;
                       $message = $row->message;
                       array_push($messages['messages'], "$message_to#$message");
                   }
                   
                } else {
                    $messages['found'] = false; 
                }
                $response = $messages;
            } else {
                //Get From
                $from_data = explode(":", $msg_tokens[0]);
                $from = $from_data[1];
                //Get To
                $to_data = explode(":", $msg_tokens[1]);
                $to = $to_data[1];
                //Get Message
                $msg_data = explode(":", $msg_tokens[2]);
                $msg = $msg_data[1];
                //Get Status
                $status_data = explode(":", $msg_tokens[3]);
                $status = $status_data[1];

                $table = "schoollms_userdata_message_$to";
                $q = "SELECT * FROM {$table} WHERE message_to = '$from'";

                $result = db_query($q);
                $num = count($result->fetchAll());

                $response = "wait";
                if ($num > 0) {
                    //Clear Message To Self and Send User Login to From
                    $q = "SELECT * FROM {$table} WHERE message_to = '$from'";

                    $result = db_query($q);
                    $row = $result->fetchAssoc();
                    $response_from = $to;
                    $response_message = $row['message'];
                    $response_status = $row['message_status'];
                    //$response = array ("$response_from", "$response_message", "$response_status");
                    $response_data = explode(" ", $response_message);
                    $response = $response_data[0];
                    //}
                } else {
                    $table = "schoollms_userdata_message_$from";
                    $q = "SELECT message_status FROM {$table} WHERE message_to = '$to'";
                    $result = db_query($q);
                    $msg_tokens = explode(" ", $result->fetchField());
                    $response = $msg_tokens[0];
                    school_lms_message_serve($from);
                    //$handle = background_process_start('school_lms_message_serve', $from); 
                }
            }
            //
            return $response;

        default :
            break;
    }
}

/**
 * 
 * @param type $message
 * @return type
 */
function school_lms_message_service($message) {

    //echo "MESSAGE $message";
    //school_lms_message_serve();
    //GET MESSAGE TYPE
    //EXPLODE BY ?
    //module_load('school_lms', 'school_lms.module');
    
    $type_tokens = explode("?", $message);
    $type_data = explode(":", $type_tokens[0]);
    $type = $type_data[1];

    switch ($type) {

        case 'data':
            $message = $type_tokens[1];
            $msg_tokens = explode(";", $message);
            if (strstr($message, ":")) {
                //Get From
                $from_data = explode(":", $msg_tokens[0]);
                $from = $from_data[1];
                //Get To
                $to_data = explode(":", $msg_tokens[1]);
                $to = $to_data[1];
                //Get Message
                $msg_data = explode(":", $msg_tokens[2]);
                $msg = $msg_data[1];
                //Get Status
                $status_data = explode(":", $msg_tokens[3]);
                $status = $status_data[1];
            } else {
                $from = $msg_tokens[0];
                $to = $msg_tokens[1];
                $msg = $msg_tokens[2];
                $status = $msg_tokens[3];
            }
            //Check IF Device Data Message Table Exists
            $table = "schoollms_data_message_$from";
            if (db_table_exists($table)) {
                //Check If Message Is Same
                $q = "SELECT * FROM {$table} WHERE message_to = '$to' AND message_status = '$status'";
                $result = db_query($q);
                $num = $result->rowCount();
                if ($num > 0) {
                    //If TRUE+
                    // Request Message Serve
                    //echo "MESSAGE SERVICE TABLE $table DO NOTHING ";
                } else {
                    //If FALSE - Check if there are messages sent to $to
                    $q = "SELECT * FROM {$table} WHERE message_to = '$to'";
                    $result = db_query($q);
                    $num = $result->rowCount();
                    if ($num > 0) {
                        //If TRUE - there are - process them
                        $q = "SELECT message_id, message_status FROM {$table} WHERE message_to = '$to'";
                        $result = db_query($q);
                        
                        foreach ($result as $key => $row) {

                            $message_id = $row->message_id;
                            $status_data = $row->message_status;
                            
                            $status_tokens = explode(" ", $status_data);
                            
                            $message_status = $status_tokens[0];
                            
                            //echo "MESSAGE SERVICE TABLE $table MSG_ID $message_id STATUS $message_status";
                            
                            
                            switch ($message_status) {
                                
                                case 'message_delivered':
                                    //echo "I AM $message_status";
                                    $q = "UPDATE $table SET message = '$msg', message_status = '$status' WHERE message_id = $message_id";
                                    $result = db_query($q);
                                    //echo "UPDATE RESULT $result";
                                    
                                    break;
                                
                                 case 'waiting':
                                    $result = _school_lms_message_insert($table, $to, $msg, $status);
                                    break;

                                default:
                                    break;
                            }

                        }
                        //echo "<br> I GET HERE OUTSIDE LOOP";
                        // Request Message Serve
                    } else {
                        
                        //echo "MESSAGE SERVICE TABLE $table INSERT ";
                        
                        $q = "SELECT message_id FROM {$table}";
                        $result = db_query($q);
                        $id = $result->rowCount();
                        $id++;

                        $q = "INSERT INTO {$table} VALUES ($id, '$to', '$msg', '$status')";
                        $result = db_query($q);
                    }
                }
                //Request Message Serve
            } else {
                //echo "MESSAGE SERVICE TABLE $table CREATE ";
                //Create Table
                $q = "CREATE TABLE {$table} (message_id int, message_to text, message blob, message_status text)";
                $result = db_query($q);

                if (!db_table_exists("schoollms_data_message_tables")) {
                    $q = "CREATE TABLE {schoollms_data_message_tables} (table_id int, tablename longtext)";
                    $result = db_query($q);
                }
                
                //Note Table
                $q = "SELECT table_id FROM {schoollms_data_message_tables}";
                $result = db_query($q);
                $id = $result->rowCount();
                $id++;

                $q = "INSERT INTO {schoollms_data_message_tables} VALUES ($id, '$table')";
                $result = db_query($q);

                //Insert Message
                if ($result) {
                    $q = "SELECT message_id FROM {$table}";
                    $result = db_query($q);
                    $id = $result->rowCount();
                    $id++;

                    $q = "INSERT INTO {$table} VALUES ($id, '$to', '$msg', '$status')";
                    $result = db_query($q);
                }
            }
            ////Request Message Serve
            //school_lms_message_serve($from);
            //IF SENDER IS SERVER STOP SERVE
            //echo "DATA MESSAGE SERVICE TABLE $table ";
            $result = school_lms_data_message_serve($from);
            break;

        case 'system':

            break;

        case 'user':
            $message = $type_tokens[1];
            $temp_messages = array();
            if (strstr($message, "#")) {
                $temp_messages = explode("#", $message);
                $count_msgs = count($temp_messages);
            } else {
                $temp_messages[0] = $message;
                $count_msgs = 1;
            }

            foreach ($temp_messages as $key => $temp_message) {

                $msg_tokens = explode(",", $temp_message);
                if (strstr($temp_message, ":")) {
                    //Get From
                    $from_data = explode(":", $msg_tokens[0]);
                    $from = $from_data[1];
                    //Get To
                    $to_data = explode(":", $msg_tokens[1]);
                    $to = $to_data[1];
                    //Get Message
                    $msg_data = explode(":", $msg_tokens[2]);
                    $msg = $msg_data[1];
                    //Get Status
                    $status_data = explode(":", $msg_tokens[3]);
                    $status = $status_data[1];
                } else {
                    $from = $msg_tokens[0];
                    $to = $msg_tokens[1];
                    $msg = $msg_tokens[2];
                    $status = $msg_tokens[3];
                }
                //Check IF Device Message Table Exists
                $table = "schoollms_userdata_message_$from";
                if (db_table_exists($table)) {
                    //Check If Message Is Same
                    $q = "SELECT * FROM {$table} WHERE message_to = '$to' AND message_status = '$status'";
                    $result = db_query($q);
                    $num = $result->rowCount();
                    if ($num > 0) {
                        //If TRUE
                        // Request Message Serve
                    } else {
                        //If FALSE
                        $q = "SELECT * FROM {$table} WHERE message_to = '$to'";
                        $result = db_query($q);
                        $num = $result->rowCount();
                        if ($num > 0) {
                            // Request Message Serve
                            //echo "I GET HERE NUM > 0";
                        } else {
                            //echo "I GET HERE";
                            $q = "SELECT COUNT(message_id) FROM {$table}";
                            $result = db_query($q);
                            $id = $result->fetchField();
                            $id++;

                            $q = "INSERT INTO {$table} VALUES ($id, '$to', '$msg', '$status')";
                            $result = db_query($q);
                        }
                    }
                    //Request Message Serve
                } else {
                    //Create Table
                    $q = "CREATE TABLE {$table} (message_id int, message_to text, message blob, message_status text)";
                    $result = db_query($q);

                    if (!db_table_exists("schoollms_userdata_message_tables")) {
                        $q = "CREATE TABLE {schoollms_userdata_message_tables} (table_id int, tablename longtext)";
                        $result = db_query($q);
                    }
                    
                    //Note Table
                    $q = "SELECT COUNT(table_id) FROM {schoollms_userdata_message_tables}";
                    $result = db_query($q);
                    $id = $result->fetchField();
                    $id++;
                    
                    $q = "INSERT INTO {schoollms_userdata_message_tables} VALUES ($id, '$table')";
                    $result = db_query($q);

                    //Insert Message
                    if ($result) {
                        $q = "SELECT COUNT(message_id) FROM {$table}";
                        $result = db_query($q);
                        $id = $result->fetchField();
                        $id++;

                        $q = "INSERT INTO {$table} VALUES ($id, '$to', '$msg', '$status')";
                        $result = db_query($q);
                    }
                }
                ////Request Message Serve
                school_lms_message_serve("$from:$to");
                //$handle = background_process_start('school_lms_message_serve', $from);
            }
            break;

        case 'security':

            break;

        case 'app':

            break;

        default:
            break;
    }

    //Check IF THERE IS NO EXISTING MESSAGES PENDING RESPONSES FROM THE SAME DESTINATION
    //Insert Message in System Message Queue
//        $q = "SELECT * FROM {schoollms_systemdata_messages} WHERE message_from = '$from' AND message_to = '$to' AND message_status = '$status'";
//        $result = db_query($q);
//        $num = count($result->fetchAll());
//        if ($num > 0) {
//            $result = 1;
//            school_lms_message_serve();
//        } else {
//            //from:$server,to:$server,message:open_queue message,status:waiting message
//            $q = "SELECT message_id FROM {schoollms_systemdata_messages} WHERE message_from = '$from' AND message_to = '$to' AND message_status = 'waiting message'";
//            $result = db_query($q);
//            $num = count($result->fetchAll());
//        
//            if ($num > 0) {
//                $result = db_query($q);
//                $message_id = $result->fetchField();
//                $q = "UPDATE {schoollms_systemdata_messages} SET message='$msg', message_status='$status' WHERE message_id = $message_id";
//                $result = db_query($q);
//            } else {
//                $q = "SELECT COUNT(message_id) FROM {schoollms_systemdata_messages}";
//                $result = db_query($q); 
//                $id = $result->fetchField();
//                $id++;
//                $q = "INSERT INTO {schoollms_systemdata_messages} VALUES ($id, '$from', '$to', '$msg', '$status')";
//                $result = db_query($q);
//            }
//            school_lms_message_serve();
//        }
//    }

    return $result;
}

/**
 * 
 * @param type $table
 * @param type $to
 * @param type $msg
 * @param type $status
 */
function _school_lms_message_insert($table, $to, $msg, $status){
    
    $q = "SELECT COUNT(message_id) FROM {$table}";
    $result = db_query($q);
    $id = $result->fetchField();
    $id++;

    $q = "INSERT INTO {$table} VALUES ($id, '$to', '$msg', '$status')";
    $result = db_query($q);
    
    return $result;
}

/**
 * 
 * @return type
 */
function school_lms_get_user_count() {
    return db_query("SELECT COUNT(uid) FROM {users}")->fetchField();
}

/**
 * 
 * @return type
 */
function school_lms_get_user_device() {
    return db_query("SELECT device_uuid FROM {schoollms_schema_userdata_device_info}")->fetchField();
}

/**
 * 
 * @param type $data
 * @return type
 */
function school_lms_load_user_device($data) {

    $update = false;
    if (!isset($data)) {
        $q = "SELECT COUNT(device_uuid) FROM {schoollms_schema_userdata_device_info}";
        $count = db_query($q)->fetchField();

        $count++;

        //Get MAC Details (UUID)
        $mac_address = "device$count";

        //Get GPS
        $gps = "0,0";

        //Get IP
        $ip = "0.0.0.0";
        
        $school_id = 0;
    } else {
        $data_tokens = explode(":", $data);

        //Get MAC Details (UUID)
        $mac_address = $data_tokens[0];

        //Get GPS
        $gps = $data_tokens[1];

        //Get IP
        $ip = $data_tokens[2];
        
        $school_id = $data_tokens[3];

        $q = "SELECT * FROM {schoollms_schema_userdata_device_info} WHERE device_uuid = '$mac_address'";

        $result = db_query($q);

        $num = $result->rowCount();

        if ($num > 0) {
            $update = true;
            $q = "UPDATE {schoollms_schema_userdata_device_info} SET device_gps = '$gps', device_ip = '$ip' WHERE device_uuid = '$mac_address'";

            $result = db_query($q);

            $q = "UPDATE {schoollms_schema_userdata_device_tracker} SET gps = '$gps' WHERE device_uuid = '$mac_address'";

            $result = db_query($q);
            
            $gps_temp = explode(",", $gps);
            $lat = (double) $gps_temp[0];
            $longi = (double) $gps_temp[1];
            $time_stamp = $gps_temp[2];
            $accuracy = $gps_temp[3];

            $location = "$longi $lat";

            $q = "SELECT nid FROM node WHERE title = '$mac_address'";

            $entity_id = db_query($q)->fetchField();

            $q = "UPDATE field_data_field_location SET field_location_wkt = 'POINT($location)', field_location_geo_type = 'point', field_location_lat = $lat,  field_location_lon = $longi, field_location_left = $lat, field_location_top = $longi, field_location_right = $lat, field_location_bottom = $longi, field_location_accuracy = $accuracy where entity_id = $entity_id";

            $result = db_query($q);
        }
    }

    if (!$update) {
        $q = "INSERT INTO {schoollms_schema_userdata_device_info} VALUES ('$mac_address', '$gps', '$ip')";
        $result = db_query($q);

        $q = "INSERT INTO {schoollms_schema_userdata_device_tracker} VALUES ('$mac_address', '$school_id', '$gps')";
        $result = db_query($q);
        
        //Device History with Timestamp
        
        global $user;

        //$body_text = 'This is the body text I want entered with the node.';

        $node = new stdClass();
        $node->type = 'school_device_tracker';
        node_object_prepare($node);

        $node->title = "$mac_address"; //. date('c');
        $node->language = LANGUAGE_NONE;

        $gps_temp = explode(",", $gps);
        $lat = (float) $gps_temp[0];
        $longi = (float) $gps_temp[1];
        $time_stamp = $gps_temp[2];
        $accuracy = $gps_temp[3];

        $location = "$longi $lat";

        $node->field_location[$node->language][0]['field']['columns']['wkt'] = "POINT($location)";
        $node->field_location[$node->language][0]['field']['columns']['lat'] = $lat;
        $node->field_location[$node->language][0]['field']['columns']['lon'] = $longi;
        $node->field_location[$node->language][0]['field']['columns']['right'] = $lat;
        $node->field_location[$node->language][0]['field']['columns']['left'] = $lat;
        $node->field_location[$node->language][0]['field']['columns']['top'] = $longi;
        $node->field_location[$node->language][0]['field']['columns']['bottom'] = $longi;
        $node->field_location[$node->language][0]['field']['columns']['geo_type'] = 'point';
        //$node->body[$node->language][0]['summary'] = text_summary($body_text);
        //$node->body[$node->language][0]['format'] = 'filtered_html';

        $path = "content/$mac_address"; //. date('YmdHis');
        $node->path = array('alias' => $path);

        node_save($node);

        $q = "SELECT nid FROM node WHERE title = '$mac_address'";

        $entity_id = db_query($q)->fetchField();

        $q = "UPDATE field_data_field_location SET field_location_wkt = 'POINT($location)', field_location_geo_type = 'point', field_location_lat = $lat,  field_location_lon = $longi, field_location_left = $lat, field_location_top = $longi, field_location_right = $lat, field_location_bottom = $longi, field_location_accuracy = $accuracy where entity_id = $entity_id";

        $result = db_query($q);
// entit
// y_create replacfield_data_field_locationes the procedural steps in the first example of
// creating a new object $node and setting its 'type' and uid property
//        $values = array(
//            'type' => 'school_device_tracker',
//            'uid' => $user->uid,
//            'status' => 1,
//            'comment' => 1,
//            'promote' => 0,
//        );
//        $entity = entity_create('node', $values);
//// The entity is now created, but we have not yet simplified use of it.
//// Now create an entity_metadata_wrapper around the new node entity
//// to make getting and setting values easier
//        $ewrapper = entity_metadata_wrapper('node', $entity);
//// Using the wrapper, we do not have to worry about telling Drupal
//// what language we are using. The Entity API handles that for us.
//        $ewrapper->title->set("$mac_address");
//        
//        
//        $ewrapper->field_location->columns->wkt->set(array('value' => "(POINT($location)"));
//        $ewrapper->field_location->columns->lat->set(array('value' =>$lat));
//        $ewrapper->field_location->columns->lon->set(array('value' =>$longi));
//        $ewrapper->field_location->columns->left->set(array('value' =>$lat));
//        $ewrapper->field_location->columns->right->set(array('value' =>$lat));
//        $ewrapper->field_location->columns->top->set(array('value' =>$longi));
//        $ewrapper->field_location->columns->bottom->set(array('value' =>$longi));
//        $ewrapper->field_location->columns->geo_type->set(array('value' =>"point"));
        //$ewrapper->field_location->srid->set($longi);
// Setting the body is a bit different from other properties or fields
// because the body can have both its complete value and its
// summary
        //$my_body_content = 'A bunch of text about things that interest me';
        //$ewrapper->body->set(array('value' => $my_body_content));
        //$ewrapper->body->summary->set('Things that interest me');
// Setting the value of an entity reference field only requires passing
// the entity id (e.g., nid) of the entity to which you want to refer
// The nid 15 here is just an example.
//        $ref_nid = 15;
//// Note that the entity id (e.g., nid) must be passed as an integer not a
//// string
//        $ewrapper->field_my_entity_ref->set(intval($ref_nid));
//// Entity API cannot set date field values so the 'old' method must
//// be used
//        $my_date = new DateTime('January 1, 2013');
//        $entity->field_my_date[LANGUAGE_NONE][0] = array(
//            'value' => date_format($my_date, 'Y-m-d'),
//            'timezone' => 'UTC',
//            'timezone_db' => 'UTC',
//        );
//// Now just save the wrapper and the entity
//// There is some suggestion that the 'true' argument is necessary to
//// the entity save method to circumvent a bug in Entity API. If there is
//// such a bug, it almost certainly will get fixed, so make sure to check.
//        $ewrapper->save();
    };

    return $result;
}

/**
 * 
 * @param type $tablename
 * @return type
 */
function school_lms_check_meta_data($tablename) {
    return data_get_table($tablename);
}

/**
 * 
 * @param type $data
 * @return type
 */
function school_lms_add_meta_data($data) {

    $data_tokens = explode("#", $data);

    //Get Tablename
    $tablename = $data_tokens[0];

    //Get Fields
    $fields = $data_tokens[1];

    //Add Meta Data
    $q = "INSERT INTO {schoollms_metadata} VALUES ('$tablename', '$fields')";
    $result = db_query($q);

    //Create Meta Data Table


    return $result;
}
