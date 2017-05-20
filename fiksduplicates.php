<?php

include("lib/db.inc");

$data = new data();
$data->username = "root";
$data->password = "$0W3t0";
$data->host = "localhost";
$data->db = "school_lms_qa_support";

$data1 = new data();
$data1->username = "root";
$data1->password = "$0W3t0";
$data1->host = "localhost";
$data1->db = "school_lms_qa_support";

//get all duplicated teachers
$sql = "select count(user_id) num, concat(name, ' ',surname) fullname
		from schoollms_schema_userdata_access_profile
		where  concat(name, ' ',surname) <> ''' '''
		and type_id = 4
		and user_id not in (select teacher_id from duplicate_teacher_userid )
		group by concat(name, ' ',surname) 
		having count(user_id)  > 1";
$data->execSQL($sql);
echo $data->numrows;
$dups = array();
while($row = $data->getRow())
{
	echo "$row->num  <br/>";
	if($row->num > 1)
	{
		echo "here <br/>";
		$dups[] = $row;
	}
}

//echo count($dups);
//echo "<hr/>";
for($i =0;$i < count($dups);$i++)
{
	$sql = "select max(num), teacher_id, timetabl_id
		from
		(
		select count(*) num, teacher_id, t.timetabl_id
		from schoollms_schema_userdata_school_timetable_items i
		join schoollms_schema_userdata_access_profile p on p.user_id = i.teacher_id
		join schoollms_schema_userdata_school_timetable t on timetable_type_item_id = user_id
		  and t.timetabl_id = t.timetabl_id
		where 
		  concat(name, ' ',surname) = '".$dups[$i]->fullname."'
		group by teacher_id, t.timetabl_id
		)tbl";
	
	$data->execSQL($sql);
	$timetable_id = 0;
	$teacher_id = 0;
	echo "numrows : $data->numrows '".$dups[$i]->fullname."'<br/>";
	if($row = $data->getRow() and $data->numrows == 1)
	{
		echo "HERE WE ARE<br/>\n";
		//echo "$sql <br/>";
		$timetable_id = $row->timetabl_id;
		$teacher_id = $row->teacher_id;
		/*$sql = "select count(*) num, teacher_id, t.timetabl_id
		from schoollms_schema_userdata_school_timetable_items i
		join schoollms_schema_userdata_access_profile p on p.user_id = i.teacher_id
		join schoollms_schema_userdata_school_timetable t on timetable_type_item_id = user_id
		  and t.timetabl_id = t.timetabl_id
		where 
		  concat(name, ' ',surname) = '".$dups[$i]->fullname."'
		  and i.teacher_id not in ($teacher_id)
		  and t.timetabl_id not in($timetable_id) 
		group by teacher_id, t.timetabl_id";
		$sql = "select count(user_id) num, concat(name, ' ',surname) fullname, user_id, teacher_id
		from schoollms_schema_userdata_access_profile p
        join (select distinct timetabl_id, teacher_id
        from schoollms_schema_userdata_school_timetable_items) tlb on teacher_id = user_id
		where  concat(name, ' ',surname) = '".$dups[$i]->fullname."'
        and teacher_id not in ($teacher_id) and timetabl_id not in($timetable_id) 
		and type_id = 4
		group by concat(name, ' ',surname) , user_id,teacher_id";*/
		$sql = "select * from schoollms_schema_userdata_access_profile
		where concat(name, ' ',surname) = '".$dups[$i]->fullname."'
		and user_id <> $teacher_id";
		echo "<br/>WWAATTT $sql <br/>";
		$data->execSQL($sql);
		while($datrow = $data->getRow())
		{
			//echo "WE ARE IN!!! '".$dups[$i]->fullname."' -- $datrow->user_id <br/>";
			$update = "update schoollms_schema_userdata_school_timetable_items
				set timetabl_id = $timetable_id, teacher_id = $teacher_id
				where  teacher_id= $datrow->user_id";
				$data1->execNonSql($update);			
				echo "Done $update <hr/>";
				
				/*$update1 = "update schoollms_schema_userdata_access_profile set user_id = -1*user_id 
				where user_id = $datrow->user_id and user_id <> -1*$datrow->user_id";
				$data1->execNonSql($update);			
				echo " Done : $update1 <hr/>";
				*/
				
				$insert = "insert into duplicate_teacher_userid (teacher_id) values ('$datrow->user_id')";
				$data1->execNonSql($insert);	
			//die();
		}
	}
	else{
		
		echo "Yabheda '".$dups[$i]->fullname."'<br/> ";
	}
}

?>