<div style="margin-top: 95px; padding-top: 95px;">
<h1 style="color: #428bca "><center>Registered Schools</center></h1>

		<table id="example" class="display" cellspacing="0" width="100%">
		<thead>
			<tr>
				<th>School Name</th>
				<th>School Address</th>
				<th>Server IP</th>
				<th>Registered Users</th>
				<th>School Type</th>
				<th>&nbsp;</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th>School Name</th>
				<th>School Address</th>
				<th>Server IP</th>
				<th>Registered Users</th>
				<th>School Type</th>
				<th>&nbsp;</th>
			</tr>
		</tfoot>
		<tbody>
			<?php
			
			$sql = "select *, (select count(*) from schoollms_schema_userdata_school_user_register_user u where u.school_id = s.school_id) num_reg
			from schoollms_schema_userdata_schools s
			join schoollms_schema_userdata_school_type st on st.type_id = s.school_type
			";
			$data->execSQL($sql);

			while($row = $data->getRow())
			{
				echo "<tr><td>$row->school_name</td><td>$row->school_address</td><td>$row->school_server_ip</td>";
				echo "<td>$row->num_reg</td><td>$row->type_title</td><td> </td></tr>";
			}				
			?>
		</tbody>		
		</table>

</div>
