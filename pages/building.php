<form id="frmSchoolInfo" name="frmSchoolInfo" method="POST">
	<table>
		<tr>
			<td colspan="2"><input type="hidden" name="schoolid" id="schoolid" value=":SCHOOLID" /></td>
		</tr>
		<tr>
			<td colspan="2"><input type="hidden" name="currentpage" id="currentpage" value="SchoolBuilding" /></td>
		</tr>
		<tr>
			<td>Building Number</td><td><input type="text" name="buildingnumber" id="buildingnumber" /></td>
		</tr>
		<tr>
			<td>Building Name</td><td><input type="text" name="buildingname" id="buildingname" /></td>
		</tr>
		<tr>
			<td>Number of Floors</td><td><input type="text" name="numberofrooms" id="numberofrooms" /></td>
		</tr>
		<tr>
			<td colspan="2"><input type="button" name="btnSave" id="btnSave" value="save" onClick="processSubmit();"/></td>
		</tr>
	</table>
</form>