<?php

include('lib/timetable.php');
	$school_id = 2;
	$id=22;
	$user_types = array(2,4);
	//$user_type = $user_types[rand(0,1)];
	$user_type = 4;
	$user_id = 995;
	if($user_type == 4)
	{
		$user_ids = array(1518, 1500, 1505, 1508, 1577, 1656, 1576, 1559, 1486);
		$count = count($user_ids);
		$user_id_key = rand(0, $count);
		$user_id = $user_ids[$user_id_key];
	}
	else{
		$sql = "select user_id
			from schoollms_schema_userdata_learner_schooldetails sd
			join schoollms_schema_userdata_school_classes c on c.class_id = sd.class_id
			where class_label = 'CLASS 9P'";
		$data->execSQL($sql);
		$theUser_id = 955;
		$key = rand(0,$data->numrows-1);
		$user_ids = array();
		while($row = $data->getRow())
		{
			$user_ids[]  = $row->user_id;
		}
		$user_id = $user_ids[$key];
	}
	
	$year = date("Y");
	$sql = "select * from schoollms_schema_userdata_school_year where year_label = '$year'";
	$data->execSQL($sql);
	$year_id = 0;
	if($row = $data->getRow())
	{
		$year_id = $row->year_id;
	}
//echo "id=$id&user_type=$user_type&user_id=$user_id&year_id=$year_id&school_id=$school_id";
?>
<div style="margin-top: 95px; padding-top: 95px;">
<h1 style="color: #428bca " style="margin-top: 10px; margin-bottom: 30px"><center>E-Learning Tracker</center></h1>
<table id="table2" style="width:100%; border-collapse: separate; border-spacing: 3px 3px;">
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
				<td class="redips-mark blank">
					&nbsp;
				</td>
				<?php echo periods($school_id) ?>
				

			</tr>
									  
				<?php  
				
				print_days($id,$user_type,$user_id,$year_id,$school_id,$startdate);
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
