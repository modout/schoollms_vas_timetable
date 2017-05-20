<?php

$sql = "select s.*
from schoollms_schema_userdata_schools s
join schoollms_schema_userdata_school_type st on st.type_id = s.school_type";
$data->execSQL($sql);
$schools = "<select name='school_name' id='school_name'>";
while($row = $data->getRow())
{
	$schoolname = str_replace("^"," ", $row->school_name);
	$schools .= "<option value='$row->school_id'>$schoolname</option>";
}
$schools .= "</select>";

?>

<div style="margin-top: 95px; padding-top: 95px;">
<h1 style="color: #428bca "><center>Registered Users</center></h1>
	<div class="main">
		<form class="contact-forms" id="frmDemo" name="frmDemo">
			<!-- end /.header-->
			

				<!-- start country -->
				<div class="main-row">
					<center style="font-weight: bold">Select School</center>
					<label class="input select">
						<?php echo  $schools; ?>
						<i></i>
					</label>
				</div>
		</form>
	</div>
</div>


