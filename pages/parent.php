<?php

extract($_GET);

$menu_url = $_SERVER['REQUEST_URI'];

$q = "SELECT * FROM schoollms_schema_userdata_timetable_menu_url WHERE user_id = $user_id";

$data->execSQL($q);

if ($data->numrows > 0){
    $q = "UPDATE schoollms_schema_userdata_timetable_menu_url SET menu_url = '$menu_url' WHERE user_id = $user_id";
} else {
    $q = "INSERT INTO schoollms_schema_userdata_timetable_menu_url VALUES  ('$user_id', '$menu_url')";
}
$data->execNonSql($q);

$q = "SELECT * FROM schoollms_schema_userdata_learner_parent WHERE parent LIKE '%$user_id%'";

$result = $data->exec_sql($q, "array");

$select_learner .= "<option value=0> Select To View Learner Stats <option>";

if ($data->numrows > 0){
    foreach($result as $key=>$row){
        $learner_user_id = $row["user_id"];
        $q = "SELECT * FROM schoollms_schema_userdata_access_profile WHERE user_id = $learner_user_id";
        $data->execSQL($q);
        $learner_row = $data->getRow();
        $access_id = $learner_row->access_id;
        $name = $learner_row->name;
        $surname = $learner_row->surname;
        
        $q = "SELECT * FROM schoollms_schema_userdata_school_timetable WHERE timetable_type_id = 1 AND timetable_type_item_id = $learner_user_id";
        $data->execSQL($q);
        
        if ($data->numrows > 0){
            $learner_row = $data->getRow();
            $learner_timetable_id = $learner_row->timetabl_id;

            $select_learner .= "<option value=$learner_timetable_id-$learner_user_id-$user_id> $access_id     $surname, $name <option>";
        } elseif ($data->numrows == 0) {
            
        }
    }
    
}


?>
<div title="Learner Timetable" style="padding:10px">
    <?php 
 
        echo "<input type='hidden' name='parent_id' value='$user_id'/>";
        echo "<input type='hidden' name='user_type' value='$user_type'/>";
        echo "<input type='hidden' name='year_id' value='$year_id'/>";
        //echo "<input type='hidden' name='server_url' value='$server_url'/>";
    ?>
	<table>
		<tr><td>Select Learner </td><td><select name='learner_timetable_id' id='learner_timetable_id'><?php echo $select_learner ?>  </select></td></tr>
	</table>
        <center><img id="learnerloading" name="learnerloading" src="images/loading.gif"></center>
	<div id="learner_timetable" name="learner_timetable">  
		
	</div>

<!--<button class="previous">Previous</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
</div>

<div title="Learner Report" style="padding:10px">
    
</div>

<div title="School Calendar" style="padding:10px">
    
</div>

<script>
	

	$(document).ready(function(){

		viewStatsTimetable('parent');
		
		$("#learner_timetable_id").on('change', function() {
			//alert($("#teachertimetable_grade_id").val());
			if ($("#learner_timetable_id").val() == 0 || $("#learner_timetable_id").val() == undefined){
                            viewStatsTimetable($('parent');
                        } else {
                            viewLearnerTimeTable($("#learner_timetable_id").val(), $("#parent_id").val());
                        }
		});
		
        });
</script>