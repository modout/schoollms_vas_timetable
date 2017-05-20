<?php
include('../lib/db.inc');
//include('../lib/timetable.php');
$school_id = 1;
$user_type = -1;

$event_id = 0;
if (isset($_GET['event_id'])){
    $event_id = $_GET['event_id'];
}
//$q = "SELECT * FROM schoollms_schema_userdata_events WHERE event_id = $event_id";
//$data->execSQL($q);
//if ($data->numrows > 0){
//    $row = $data->getRow();
//    $title = $row->event_title;
//} else {
//    $title = "EVENT TITLE";
//}
?>
<html>
	<head>
		
		<meta name="description" content="SchoolLMS Timetable Settings Page"/>
		<meta name="viewport" content="width=device-width, user-scalable=no"/><!-- "position: fixed" fix for Android 2.2+ -->
		<link rel="stylesheet" href="../css/style.css" type="text/css" media="screen"/>
		<script type="text/javascript">
			var redipsURL = '/javascript/drag-and-drop-example-3/';
		</script>
		<!--<script type="text/javascript" src="header.js"></script>
		<script type="text/javascript" src="redips-drag-min.js"></script>
		<script type="text/javascript" src="script.js"></script> -->
		<!--<script type="text/javascript" src="../timetable.js"></script>-->
		<!--<script type="text/javascript" src="../jscolor.js"></script>-->
		
		
		<link rel="stylesheet" type="text/css" href="../themes/jquery-ui.css" />
		<link rel="stylesheet" type="text/css" href="../themes/default/easyui.css">
		<link rel="stylesheet" type="text/css" href="../themes/icon.css">
		<!-- script type="text/javascript" src="js/jquery-2.0.0.min.js"></script -->
		<script type="text/javascript" src="../js/jquery.1.11.1.min.js"></script>
		<script src="../js/jquery.easyui.min.js" type="text/javascript"></script>
		<!--<script src="../js/datagrid-filter.js" type="text/javascript"></script>-->
		<script type="text/javascript" src="../js/jquery.searchabledropdown-1.0.8.min.js"></script>
		<script src="../js/jscode.js" type="text/javascript"></script>
                <script src="../js/signature.js" type="text/javascript"></script>
		<!--<script src="../js/teachersetting.js" type="text/javascript"></script>-->	
		<!--<script src="../js/classvenuesettings.js" type="text/javascript"></script>-->
		<!--<script src="../js/studentsettings.js" type="text/javascript"></script>-->
        </head>
<body style="background:#1FFFFF;">
<!--div id="eventRegisterDlg" class="easyui-dialog" style="width:400px;height:250px;padding:10px 20px"
            closed="true" buttons="#dlg-buttons" -->
    <div class="ftitle" name="thetitle" id="thetitle"><strong> <?php echo $title ?></strong></div>
        <form runat="server" id="frmEventRegister" name="frmEventRegister"  >
            
            <?php if (isset($_GET['type']) && $_GET['type'] == 'view') {
                $table = "schoollms_schema_userdata_event_$event_id"."_register";
                $data2 = $data;
                $q = "SELECT DISTINCT register_id, school, surname,first_names,count(*) Number_of_Registers FROM $table GROUP BY surname,first_names ORDER BY surname, first_names ASC";
                $result = $data->exec_sql($q, "array");
                if ($data->numrows > 0){ ?>
            <table border=2 style="width: 100%" cellspacing=2 cellpadding=2 >
                <tr><th><b>Register ID</b></th><th> <b> School </b> </th><th> <b> Surname </b> </th><th> <b> Name </b> </th><th> <b> Registrations </b> </th><th> <b> Actions </b> </th></tr>
            <?php
                    //GET REGISTER FIELDS
                    
                    foreach ($result as $key=>$row){
                        $register_id = $row["register_id"];
                        
                        $surname = $row["surname"];
                        $name = $row["first_names"];
                        $number = $row["Number_of_Registers"];
                        $school = $row["school"];
                        $q = "SELECT * FROM schoollms_schema_userdata_schools WHERE school_id = '$school'";
                        $data2->execSQL($q);
                        $row2 = $data2->getRow();
                        $school = $row2->school_name;
                        echo "<tr><td> $register_id </td><td> $school </td><td> $surname </td> <td> $name </td> <td> $number </td> <td> <a href='event_register.php?event_id=$event_id&type=editregister&register=$register_id'> EDIT REGISTER </a></td>  </tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<b> NO REGISTRATION DATA </b>";
                }
            } elseif (isset($_GET['type']) && $_GET['type'] == 'report'){
                /*
                 * GET REGISTER FIELDS
                 * 
                 * DISTRICT 15
                 * 
                 * REGION JHB (5), TSHWAGA (5), EKHUDIBENG (5)
                 * 
                 *  
                 */
                $table = "schoollms_schema_userdata_event_"."$event_id"."_register";
                $db = $data->db;
                $q = "SELECT * FROM information_schema.tables "
                        . "WHERE table_schema = '$db'"
                        . " AND table_name = '$table'";
                //echo "CHECK Q $q <br>";
                $data->execSQL($q);
                $num = $data->numrows;
                //echo "NUM $num <br>";

                if ($data->numrows == 0){
                    echo "<b> NO REGISTRATION FORM CREATED FOR THIS EVENT </b><br> <a href='event_register.php?event_id=$event_id&type=createform'> CLICK HERE TO CREATE FORM </a>";
                } else {
                    //GET DISTRICT STATS
//                    $q = "SELECT * FROM schoollms_schema_userdata_school_district";
//                    $district_report = $data->exec_sql($q, "array");
//                    foreach ($district_report as $key => $district_data) {
//                        
//                    }
                    
                    $q = "desc $table";
                    $report_fields = $data->exec_sql($q, "array");
                    foreach ($report_fields as $key => $field_row) {
                        $field = $field_row["Field"];
                        if ($field == "register_id"){
                            $q = "SELECT COUNT($field) AS $field FROM $table";
                            $data->execSQL($q);
                            $row = $data->getRow();
                            $total = $row->$field;
                            echo "<table border=2><tr><th><b> Total_Registers </b></th></tr><tr><td>  $total </td></tr>";
                            echo "</table><br><br>";
                        } else {
                            $q = "SELECT $field, COUNT($field) Number_of_$field FROM $table GROUP BY $field ORDER BY $field";
                            $data->execSQL($q);
                            echo "<table border=2><tr><th><b> $field </b></th><th><b> Number_of_$field </b></th></tr>";
                            while ($row = $data->getRow()){
                                $field_data = $row->$field;
                                $number = "Number_of_$field";
                                $field_number = $row->$number;
                                echo "<tr><td> $field_data </td><td> $field_number </td></tr>";
                            }
                            echo "</table><br><br>";
                        }
                    }
                }
                
                
            } elseif (isset($_GET['type']) && $_GET['type'] == 'manage'){
                $table = "schoollms_schema_userdata_event_"."$event_id"."_register";
                $db = $data->db;
                $q = "SELECT * FROM information_schema.tables "
                        . "WHERE table_schema = '$db'"
                        . " AND table_name = '$table'";
                //echo "CHECK Q $q <br>";
                $data->execSQL($q);
                $num = $data->numrows;
                //echo "NUM $num <br>";

                if ($data->numrows == 0){
                    echo "<b> NO REGISTRATION FORM CREATED FOR THIS EVENT </b><br> <a href='event_register.php?event_id=$event_id&type=createform'> CLICK HERE TO CREATE FORM </a>";
                } else {
                    echo "<b> MANAGE REGISTRATION FORM FIELDS </b><br>";
                }
            } elseif (isset($_GET['type']) && $_GET['type'] == 'editregister'){
                $register_id = $_GET['register'];
                //GET CAPTURED VALUE FOR EDITING DEPENDING ON DATA ACCESS RIGHTS
                $event_register_table = "schoollms_schema_userdata_event_$event_id"."_register";
                $q = "SELECT * FROM $event_register_table WHERE register_id = $register_id";
                //echo "Q $q <br>";
                $register_data = $data->exec_sql($q, "array");
                //GET SECTION
                $q = "SELECT * FROM schoollms_schema_userdata_event_tools WHERE event_id = $event_id AND form_type_id = 1";                
                //echo "Q $q<br>";
                $data->execSQL($q);
                
                if ($data->numrows > 0){
                    $row = $data->getRow();
                    $form_id = $row->form_id;
                    $q = "SELECT * FROM schoollms_schema_userdata_event_register_sections WHERE form_id = $form_id ORDER BY section_order ASC";
                    $result = $data->exec_sql($q, "array");
                    ?>
                <table border=2 style="width: 100%" cellspacing=2 cellpadding=2 >
                <?php
                    if ($data->numrows > 0){
                        $counter = 1;
                        $data1 = $data;
                        foreach ($result as $key=>$row){
                            $section_id = $row["section_id"];
                            $section_title = $row["section_title"];
                            echo "<tr><th><b> $section_title </b> </th></tr>";
                
                            $q = "SELECT * FROM schoollms_schema_userdata_event_register_fields WHERE form_id = $form_id AND field_section_id = $section_id ORDER BY field_order ASC";
                            $result2 = $data1->exec_sql($q, "array");
                            
                            if ($data1->numrows > 0){
                                echo "<tr><td><table style='width: 100%' cellspacing=2 cellpadding=2>";
                                if ($counter == 1){
                                    echo "<tr><td align='left' colspan=2><input type='hidden' name='event_id' value=$event_id></td></tr>";  
                                    $counter++;
                                }
                                
                                foreach($result2 as $key2=>$row2){
                                    $field_id = $row2["field_id"];
                                    $field_type_id = $row2["field_type_id"];
                                    $field_input_id = $row2["field_input_id"];
                                    $field_input_name = $row2["field_input_name"];
                                    $field_input_placeholder = $row2["field_input_placeholder"];
                                    $field_label = $row2["field_label"];
                                  
                                    $value = $register_data[0]["$field_input_name"];
                                    
                                    switch ($field_type_id){
                                        
                                        case 1://TEXT
                                            echo "<tr><td style='width: 40%'>
                                                        $field_label
                                                    </td>
                                                    <td align='left'>
                                                        <input type='text' id='$field_input_id' name='$field_input_name'  value='$value' />
                                                    </td>
                                                  </tr>";
                                            break;
                                        
                                        case 2://SELECT
                                            echo "<tr><td style='width: 40%'>
                                                        $field_label
                                                    </td>
                                                    <td align='left'>
                                                        <select id='$field_input_id' name='$field_input_name'>";
                                            
                                            $q = "SELECT * FROM schoollms_schema_userdata_event_field_input_data WHERE field_id = $field_id";
                                            $data->execSQL($q);
                                            $row = $data->getRow();
                                            $data_id = $row->field_input_type_data_id;
                                            $q = "SELECT * FROM schoollms_schema_userdata_event_field_data WHERE data_id = $data_id";
                                            $data->execSQL($q);
                                            $row = $data->getRow();
                                            $data_source = $row->data_source;
                                            $data_source_tokens = explode("#", $data_source);
                                            $tablename = $data_source_tokens[0];
                                            $option_index = $data_source_tokens[1];
                                            if (count($data_source_tokens) > 3){
                                                
                                            } else {
                                                $option_views = $data_source_tokens[2];
                                            }
                                            $q = "SELECT * FROM $tablename ORDER BY $option_views ASC";
                                            //echo "Q $q <br>";
                                            $data->execSQL($q);
                                            $option_item = "";
                                            while ($row3 = $data->getRow()){
                                                $option_index_id = $row3->$option_index;
                                                
                                                if (count($data_source_tokens) > 3){
                                                    $option_views = "";
                                                    $temp_data_source_tokens = $data_source_tokens;
                                                    foreach ($temp_data_source_tokens as $key3 => $value3) {
                                                        if ($key3 <= 1){
                                                            continue;
                                                        }
                                                        
                                                        $item = $row3->$value3;
                                                        $option_item .= "$item ";
                                                    }

                                                } else {
                                                    $option_item = $row3->$option_views;
                                                }
                                                
                                                if ($value == $option_index_id){
                                                    echo "<option value='$option_index_id' selected> $option_item </option>";
                                                } else {
                                                    echo "<option value='$option_index_id'> $option_item </option>";
                                                }
                                            }
                                            
                                             echo    "</select> 
                                                      </td>
                                                  </tr>";
                                            break;
                                    }
                           
                                }
                                echo "</table>
                                </td>
                                </tr>";
                            }
                            
                        }
                        
                    }
                    
                }
                ?>
            </table>
             <?php
            } else {
           
           ?>
            <table border=2 style="width: 100%" cellspacing=2 cellpadding=2 >   
                <tr><th> <b> Biographic Information</b> </th></tr>
                <tr><td>
			<table style="width: 100%" cellspacing=2 cellpadding=2 >   
				<tr>
					<td align="left" colspan=2>
						<?php echo "<input type='hidden' name='event_id' value=$event_id>" ?>
					</td>
				</tr>			
				<tr>
					<td style="width: 40%">
						Surname
					</td>
					<td align="left">
						<input type="text" id="surname" name="surname"  placeholder="Surname" />
					</td>
				</tr>
				<tr>
					<td style="width: 40%">
						 First Name(s)
					</td>
					<td align="left">
						<input type="text" id="first_names" name="first_names"  placeholder="First Names" />
					</td>
				</tr>
				<tr>
					<td style="width: 40%">
						 Gender
					</td>
					<td align="left">
                                            <select name="gender" id="gender"><option value='Male'> Male </option><option value='Female'> Female </option> </select>
					</td>
				</tr>
                                <tr>
					<td style="width: 40%">
						 Age
					</td>
					<td align="left">
						<input type="text" id="age" name="age"  placeholder="Age" />
					</td>
				</tr>
                                <tr>
					<td style="width: 40%">
						 Age Group
					</td>
					<td align="left">
                                            <select name="age_group" id="age_group"><option value='20-40'> 20-40 </option><option value='40-60'> 40-60 </option><option value='60-80'> 60-80 </option> </select>
					</td>
				</tr>
                                <tr>
					<td style="width: 40%">
						 Home Language
					</td>
					<td align="left">
						<input type="text" id="home_language" name="home_language"  placeholder="Home Language" />
					</td>
				</tr>
                                <tr>
					<td style="width: 40%">
						 Population Group
					</td>
					<td align="left">
						<input type="text" id="population_group" name="population_group"  placeholder="Population Group" />
					</td>
				</tr>
                                <tr>
					<td style="width: 40%">
						 Nationality
					</td>
					<td align="left">
						<input type="text" id="nationality" name="nationality"  placeholder="Nationality" />
					</td>
				</tr>
                                <tr>
					<td style="width: 40%">
						 South African ID
					</td>
					<td align="left">
						<input type="text" id="sa_id" name="sa_id"  placeholder="South African ID" />
					</td>
				</tr>
                                
                                <tr>
					<td style="width: 40%">
						 Non-South African ID (If Non-South African)
					</td>
					<td align="left">
						<input type="text" id="non_sa_id" name="non_sa_id"  placeholder="Non-South African ID" />
					</td>
				</tr>
                                <tr>
					<td style="width: 40%">
						 Marital Status
					</td>
					<td align="left">
						<select name="marital_status" id="marital_status"><option value='Single'> Single </option><option value='Married'> Married </option> <option value='Divorced'> Divorced </option> <option value='Widow'> Widow </option> </select>
					</td>
				</tr>
                                <tr>
					<td style="width: 40%">
						 Disability
					</td>
					<td align="left">
						<select name="disability" id="disability"><option value='No'> No </option><option value='Yes'> Yes </option>  </select>
					</td>
				</tr>
                                </table>
                    </td>
                </tr>
                <tr><th> <b>Contact Details</b> </th></tr>
                <tr><td>
                        <table style="width: 100%" cellspacing=2 cellpadding=2 >
                               
                                <tr>
					<td style="width: 40%">
						 Cell Number
					</td>
					<td align="left">
						<input type="text" id="cell_no" name="cell_no"  placeholder="Cell Number" />
					</td>
				</tr>
                                <tr>
					<td style="width: 40%">
						 E-mail
					</td>
					<td align="left">
						<input type="text" id="email" name="email"  placeholder="Email" />
					</td>
                                        
				</tr>
                                <tr>
					<td style="width: 40%">
						 Telephone No.:
					</td>
					<td align="left">
						<input type="text" id="tel_no" name="tel_no"  placeholder="Telephone Number" />
					</td>
				</tr>
                                <tr>
					<td style="width: 40%">
						 Fax Number
					</td>
					<td align="left">
						<input type="text" id="fax_no" name="fax_no"  placeholder="Fax Number" />
					</td>
				</tr>
                        </table>
                    </td>
                </tr>
                <tr><th> <b>Residential Address </b> </th></tr>
                <tr><td>
                        <table style="width: 100%" cellspacing=2 cellpadding=2 >   
                                <tr>
					<td style="width: 40%">
						 Street 
					</td>
					<td align="left">
						<input type="text" id="street" name="street"  placeholder="Street" />
					</td>
				</tr>
                                <tr>
					<td style="width: 40%">
						 Suburb
					</td>
					<td align="left">
						<input type="text" id="suburb" name="suburb"  placeholder="Suburb" />
					</td>
				</tr>
                                <tr>
					<td style="width: 40%">
						 City
					</td>
					<td align="left">
						<input type="text" id="city" name="city"  placeholder="City" />
					</td>
				</tr>
                                <tr>
					<td style="width: 40%">
						 Province
					</td>
					<td align="left">
						<input type="text" id="province" name="province"  placeholder="Province" />
					</td>
				</tr>
                                <tr>
					<td style="width: 40%">
						 Postal Code
					</td>
					<td align="left">
						<input type="text" id="postal_code" name="postal_code"  placeholder="Postal Code" />
					</td>
				</tr>
                            </table>
                    </td>
                </tr>
                <tr><th> <b>School Information</b> </th></tr>
                <tr><td>
                        <table style="width: 100%" cellspacing=2 cellpadding=2 >
                                <tr>
					<td style="width: 40%">
						 School's Name / Company
					</td>
					<td align="left">
						<input type="text" id="school" name="school"  placeholder="School Name / Company" />
					</td>
				</tr>
                                <tr>
					<td style="width: 40%">
						 Principal
					</td>
					<td align="left">
						<input type="text" id="principal" name="principal"  placeholder="Principal" />
					</td>
				</tr>
                                <tr>
					<td style="width: 40%">
                                                Telephone Number
					</td>
					<td align="left">
						<input type="text" id="school_tel_no" name="school_tel_no"  placeholder="Telephone Number" />
					</td>
				</tr>
                                <tr>
					<td style="width: 40%">
						 Fax Number
					</td>
					<td align="left">
						<input type="text" id="school_fax_no" name="school_fax_no"  placeholder="Fax Number" />
					</td>
				</tr>
                                
                                <tr>
					<td style="width: 40%">
						 District
					</td>
					<td align="left">
						<select name="district" id="district"><option value='EN'> EN </option><option value='ES'> ES </option> <option value='GE'> GE </option> <option value='GN'> GN </option><option value='GW'> GW</option><option value='JC'> JC </option><option value='JE'> JE </option><option value='JN'> JN </option><option value='JS'> JS </option><option value='JW'> JW </option><option value='SE'> SE </option><option value='SW'> SW </option><option value='TN'> TN </option><option value='TS'> TS </option><option value='TW'> TW </option><option value='OTHER'> OTHER </option> </select>
					</td>
				</tr>
                                <tr>
					<td style="width: 40%">
						 If Other (Please Type)
					</td>
					<td align="left">
						<input type="text" id="district_other" name="district_other"  placeholder="Other" />
					</td>
				</tr>
                                </table>
                    </td>
                </tr>
                <tr><th> <b>Qualifications & Experiences </b> </th></tr>
                <tr><td>
                        <table style="width: 100%" cellspacing=2 cellpadding=2 >
                                <tr>
					<td style="width: 40%">
						 Highest Grade Passed
					</td>
					<td align="left">
						<select name="highest_grade" id="highest_grade"><option value='No Education'> No Education </option><option value='ABET'> ABET </option> <option value='Primary School'> Primary School </option> <option value='Secondary School'> Secondary School </option><option value='Tertiary School'> Tertiary School</option> </select>
					</td>
				</tr>
                                <tr>
					<td style="width: 40%">
						 Highest Qualification
					</td>
					<td align="left">
						<select name="highest_qualification" id="highest_qualification"><option value='Certificate'> Certificate </option><option value='Diploma'> Diploma </option> <option value='Degree'> Degree </option> <option value='Masters'> Masters </option><option value='PHD'> PHD</option><option value='Other'> Other</option> </select>
					</td>
				</tr>
                                <tr>
					<td style="width: 40%">
						 If Other (Please Type)
					</td>
					<td align="left">
						<input type="text" id="highest_qualification_other" name="highest_qualification_other"  placeholder="Other" />
					</td>
				</tr>
                                
                                <tr>
					<td style="width: 40%">
						 Signature
					</td>
					<td align="left">
                                            <div id="canvas">
                                                    <canvas class="roundCorners" id="newSignature"
                                                    style="height: 50%; width: 80%; position: relative; margin: 0; padding: 0; border: 1px solid #c4caac;"></canvas>
                                            </div>
                                            <script>
                                                    signatureCapture();
                                            </script>
                                            <button type="button" onclick="signatureClear()">
                                                    Clear signature
                                            </button>
<!--                                            <button type="button" onclick="signatureSave()">
                                                    Save signature
                                            </button>
                                            <img id="saveSignature" alt="Saved image png"/>-->

					</td>
				</tr>
                                <!-- rashidat@sacredheart.co.za -->
                                
                            </table>
			
		</form>
		<table width="80%">
		<tr><td>
				<button id="btnRegisterEvent" name="btnRegisterEvent" value="register" onClick="saveEventRegister()">Save</button>
					</td>
					<td>
						<!--<button id="btnSaveLearner" name="btnRegisterEvent" onClick="$('#eventRegisterDlg').dialog('close');">Cancel</button>-->
					</td>
				</tr>
		</table>
            <?php } ?> 
	<!--/div-->
    </body>
</html>