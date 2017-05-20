<?php

$q = "SELECT * FROM schoollms_schema_userdata_access_profile WHERE user_id = $user_id";
//echo "Q $q";
$data->execSQL($q);
$row = $data->getRow();
$name = $row->name;
$surname = $row->surname;

$year = date('Y');
?>
<div title="Learner Timetable" style="padding:10px">
<h1><?php "$surname, $name"; ?> Timetable <?php echo $year; ?></h1>
	<!-- table>
		<tr><td>Time Table </td><td><select name='timetable_id' id='timetable_id'>  </select></td></tr>
	</table -->
	<br/><br/><center><img id="learnerloading" name="learnerloading" src="images/loading.gif"></center<br/>
	<div id="timetable" name="timetable">  
		
	</div>

<!-- button class="previous">Previous</button -->&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</div>