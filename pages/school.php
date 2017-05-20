<form id="frmSchoolInfo" name="frmSchoolInfo" method="POST">
	<table>
		<tr>
			<td colspan="2"><input type="hidden" name="schoolid" id="schoolid" value=":SCHOOLID" /></td>
		</tr>
		<tr>
			<td colspan="2"><input type="hidden" name="currentpage" id="currentpage" value="SchoolInfoForm" /></td>
		</tr>
		<tr>
			<td>School Name</td><td><input type="text" name="schoolname" id="schoolname" /></td>
		</tr>
		<tr>
			<td>Number of buildings</td><td><input type="text" name="numberofbuildings" id="numberofbuildings" /></td>
		</tr>
		<tr>
			<td>Number of fields</td><td><input type="text" name="numberoffields" id="numberoffields" /></td>
		</tr>
		<tr>
			<td colspan="2"><input type="button" name="btnSave" id="btnSave" value="save" onClick="processSubmit();"/></td>
		</tr>
	</table>
</form>