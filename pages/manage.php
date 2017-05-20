<div title="Manage School" style="padding:10px">
	
	<br/><br/>
	<div id="school" name="school">  
		
	</div>

<!--<button class="previous">Previous</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
</div>
<div title="Manage Stats" style="padding:10px">
	
	<br/><br/>
	<div id="stats" name="stats">  
		
	</div>

<!--<button class="previous">Previous</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
</div>
<div title="Manage Reports" style="padding:10px">
	
	<button name="bntBuildReport" id="bntBuildReport" onClick="buildReport()">Build Report</button>
        <!--<button name="bntManageEventRegister" id="bntManageEventRegister" onClick="manageEventRegister()">Manage Event Register</button>-->
	<br/><br/><img id="reportsloading" name="reportsloading" src="images/loading.gif"><br/>
	<div id="reports" name="reports">  
		
	</div>

<!--<button class="previous">Previous</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
</div>
<div title="Manage Events" style="padding:10px">
	
	<center>
   <table>
		<tr>
			<td>Year</td><td><?php echo $teachertimetable_year_id; ?> </td> 
                        <td>Month</td><td><select name="month" id="month"> </select></td>
                        <td>Day</td><td><select name='day' id='day'>  </select></td>
		</tr>
	</table>
	<br/>
	<br/>
        <button name="bntAddEvent" id="bntAddEvent" onClick="addNewEvent()">Add Event</button>
        <!--<button name="bntManageEventRegister" id="bntManageEventRegister" onClick="manageEventRegister()">Manage Event Register</button>-->
	<br/><br/><img id="eventsloading" name="eventsloading" src="images/loading.gif"><br/>
	<fieldset>
         
	 <table id="events" border="2"></table>
         
	<!--<br/><br/>-->
	<!--<button id="btnSaveStudentGrade" name="btnSaveStudentGrade" onClick="SaveStudentGrade()">Save</button>-->
	
	
	</fieldset>
        </center>

<!--<button class="previous">Previous</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
</div>
<div title="Manage Workflows" style="padding:10px">
	
	<br/><br/>
	<div id="events" name="events">  
		
	</div>

<!--<button class="previous">Previous</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
</div>
<div title="Manage Tracking" style="padding:10px">
	
	<br/><br/>
	<div id="events" name="events">  
		
	</div>

<!--<button class="previous">Previous</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
</div>
<div title="Manage Content" style="padding:10px">
	
	<br/><br/>
	<div id="events" name="events">  
		
	</div>

<!--<button class="previous">Previous</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
</div>
<div title="Manage Access" style="padding:10px">
	
	<br/><br/>
	<div id="events" name="events">  
		
	</div>

<!--<button class="previous">Previous</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
</div>
<script>
	

	$(document).ready(function(){

		getEvents(0,0,0);
		
//		$("#year_id").on('change', function() {
//			//alert($("#teachertimetable_grade_id").val());
//			
//			viewTimeTable($("#teachertimetable_grade_id").val());
//		});
//		
//		$("#btnAddUser").click(function () {
//			adduser();
//			//next();
//		});
//		
//                $("#student_grade1").on('change', function() {
//                    getClass($("#student_grade1").val());
//                });
//                
//                $("#class_id").on('change', function() {
//                    getGradeClassList($("#student_grade1").val(),$("#class_id").val());
//                });
//                
//		$("#graderow").hide();
//		$("#classrow").hide();
//		
//		$("#type_id").on('change', function() {
//			if( $("#type_id option:selected").text() == "learner")
//			{
//				$("#graderow").show();
//				$("#classrow").show();
//			}
//			else{
//				$("#graderow").hide();
//				$("#classrow").hide();
//			}
//		});

	});
	
	function closeAddUser()
	{
		//alert("Close");
		if( $("#type_id option:selected").text() == "learner")
		{
			
		}
		else{
			
		}
		$("#accesscontoldlg").dialog('close');
	}
	
	function saveuser()
	{
		var school_id = getSchoolID();
		//alert("save user");
		//alert($("#frmAccessControl").serialize()&"&usertype="+$("#type_id option:selected").text());
		var params= "action=ADDACCESSCONTROLUSER&"+$("#frmAccessControl").serialize()+"&usertype="+$("#type_id option:selected").text()+"&school_id="+school_id;
		//alert(params);
		var result = getJasonData(params);
		alert(result);
		//$("#searchStr").val($("#name").val());
	}
</script>