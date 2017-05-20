<?php
$q = "SELECT timetabl_id FROM schoollms_schema_userdata_school_timetable WHERE timetable_type_id = 1 AND timetable_type_item_id = $user_id";
$data->execSQL($q);
$row = $data->getRow();
$timetabl_id = $row->timetabl_id;
?>
<input type='hidden' name='learner_timetable_id' value='<?php $timetabl_id ?>' />
<div title="Learner Timetable" style="padding:10px">
<h1>Learner Timetable</h1>
	<!-- table>
		<tr><td>Time Table </td><td><select name='timetable_id' id='timetable_id'>  </select></td></tr>
	</table -->
	<br/><br/><center><img id="learnerloading" name="learnerloading" src="images/loading.gif"></center<br/>
	<div id="learner_timetable" name="learner_timetable">  
		
	</div>

<!-- button class="previous">Previous</button -->&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</div>

<script>
	

	$(document).ready(function(){

		viewLearnerTimeTable($("#learner_timetable_id").val(), 0);
		
				
        });
</script>