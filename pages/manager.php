<div title="General Settings" style="padding:10px">

	<ul class="easyui-tree" data-options="method:'get',animate:true"></ul>
	
	
   <fieldset id="mydiv" name="mydiv">
   <legend>Timetable General Settings:</legend>
   
	<form action="#" id="frmSchoolInfo" name="frmSchoolInfo">
	   <table><tr><th id="instructions" name="instructions"> </th></tr></table>
		
	   <table><tr><th id="_schoolname" name="_schoolname">  </th></tr></table>
		
	   <table border=1>
				<tr><th> Settings Variable</th><th> Current Value </th> <th> Select New Value </th> </tr>
				<tr><th> Timetable Days </th> <td id="_days" name="_days" >  </td> <td> <select name='days'> <option value=5> 5 </option> <option value=6> 6 </option> <option value=7> 7 </option> <option value=8> 8 </option> <option value=9> 9 </option> <option value=10> 10 </option> </select> days </td> </tr>
				 <tr><th> School Start Time </th> <td id="_school_start_time" name="_school_start_time" >  </td> <td> <input type="text" name="school_start_time" id="school_start_time" maxlength="6" size="6"  /> <font color="red"> Format HH:MM </font> </td> </tr>
				 <tr><th> Class Start Time </th> <td id="_class_start_time" name="_class_start_time" >  </td> <td> <input type="text" name="class_start_time" id="class_start_time" maxlength="6" size="6"  /> <font color="red"> Format HH:MM </font> </td> </tr>
				 
				 <tr><th> Periods </th> <td id="_periods" name="_periods" >  </td> <td> <select name='periods'> <option value=5> 5 </option> <option value=6> 6 </option> <option value=7> 7 </option> <option value=8> 8 </option> <option value=9> 9 </option> <option value=10> 10 </option> </select> </td> </tr>
				 <tr><th> Period Time </th> <td id="_period_time" name="_period_time" >  </td> <td> <select name='period_time'> <option value=30> 30 </option> <option value=35> 35 </option> <option value=40> 40 </option> <option value=45> 45 </option> <option value=50> 50 </option> <option value=55> 55 </option> <option value=60> 60 </option></select> minutes</td> </tr>
				 <tr><th> Number of breaks </th> <td id="_number_of_breaks" name="_number_of_breaks" >  </td> <td> <select name='number_of_breaks' id='number_of_breaks'> <option value=1> 1 </option><option value=2> 2 </option><option value=3> 3 </option><option value=4> 4 </option> </select>  breaks per school day </td> </tr>
				 
				 <tr id="row_break_1" name="row_break_1"><th> First Break Time </th> <td id="_break_1" name="_break_1" >  </td> <td> <input type="text" name="break_time_1" id="break_time_1" maxlength="6" size="6"   /> <font color="red"> Format HH:MM </font>  for <select name='break_length_1' id='break_length_1'> <option value=15> 15 </option> <option value=20> 20 </option> <option value=25> 25 </option><option value=30> 30 </option> <option value=35> 35 </option> <option value=40> 40 </option> <option value=45> 45 </option> <option value=50> 50 </option> <option value=55> 55 </option> <option value=60> 60 </option></select> minutes    </td> </tr>
				 <tr id="row_break_2" name="row_break_2"><th> Second Break Time </th> <td id="_break_2" name="_break_2" >  </td> <td> <input type="text" name="break_time_2" id="break_time_2" maxlength="6" size="6"  /> <font color="red"> Format HH:MM </font>  for  <select name='break_length_2'  id='break_length_2'> <option value=15> 15 </option> <option value=20> 20 </option> <option value=25> 25 </option><option value=30> 30 </option> <option value=35> 35 </option> <option value=40> 40 </option> <option value=45> 45 </option> <option value=50> 50 </option> <option value=55> 55 </option> <option value=60> 60 </option></select> minutes   </td> </tr>
				 <tr id="row_break_3" name="row_break_3"><th> Third Break Time </th> <td id="_break_3" name="_break_3" >  </td> <td> <input type="text" name="break_time_3" id="break_time_3" maxlength="6" size="6"  /> <font color="red"> Format HH:MM </font>  for <select name='break_length_3' id='break_length_3'> <option value=15> 15 </option> <option value=20> 20 </option> <option value=25> 25 </option><option value=30> 30 </option> <option value=35> 35 </option> <option value=40> 40 </option> <option value=45> 45 </option> <option value=50> 50 </option> <option value=55> 55 </option> <option value=60> 60 </option></select> minutes   </td> </tr>
				 <tr id="row_break_4" name="row_break_4"><th> Fourth Break Time </th> <td id="_break_4" name="_break_4" >  </td> <td> <input type="text" name="break_time_4" id="break_time_4" maxlength="6" size="6" /> <font color="red"> Format HH:MM </font>  for  <select name='break_length_4' id='break_length_4'> <option value=15> 15 </option> <option value=20> 20 </option> <option value=25> 25 </option><option value=30> 30 </option> <option value=35> 35 </option> <option value=40> 40 </option> <option value=45> 45 </option> <option value=50> 50 </option> <option value=55> 55 </option> <option value=60> 60 </option></select> minutes   </td> </tr>
				 
				 <tr><th> Grades </th> <td id="_from_grade" name="_from_grade" >  </td><td> From <select name='from_grade' id='from_grade'> <option value="R"> R </option> <option value=1> 1 </option> <option value=2> 2 </option> <option value=3> 3 </option><option value=4> 4 </option><option value=5> 5 </option><option value=6> 6 </option><option value=7> 7 </option><option value=8> 8 </option><option value=9> 9 </option><option value=10> 10 </option><option value=11> 11 </option><option value=12> 12 </option></select> To <select name='to_grade' id='to_grade'> <option value="R"> R </option> <option value=1> 1 </option> <option value=2> 2 </option> <option value=3> 3 </option><option value=4> 4 </option><option value=5> 5 </option><option value=6> 6 </option><option value=7> 7 </option><option value=8> 8 </option><option value=9> 9 </option><option value=10> 10 </option><option value=11> 11 </option><option value=12> 12 </option> </select></td>  </tr>
				 <tr><th> Classletters <input type='hidden' name='school_id' id='school_id' ></th> <td id="_classletters" name="_classletters">  </td> <td> <input type="text" name="classletters" placeholder="Type A-Z or preferred letters separated by comma" size=60> </td> </tr>
				<tr><th> Roration Type </th> <td id="_rotation_type" name="_rotation_type" >  </td><td> <select name='rotation_type' id='rotation_type' > <option value="Teacher Rotates"> Teacher Rotates </option> <option value="Learner Rotates"> Learner Rotates </option>  </td>  </tr>
				 
		</table>				   
	   </form> 
	   <button id="btnSaveSettings1" name="btnSaveSettings1" onClick="saveSettings1()">Save Settings</button>
   </fieldset>
	
	
	<button class="previous">Previous</button>&nbsp;&nbsp;&nbsp;&nbsp;<button onclick="getSubjectData()">Next</button>
</div>
<div title="Subject Settings" style="padding:10px">
	<fieldset>
	<form action="#" id="frmSubjectSetting" name="frmSubjectSetting">
	
	<legend> Subject Settings:</legend>
	<br/>
		<?php echo $subjects; ?> 
	<br/>
	<br/>
	<br/>

	<table border=1><tr><th> Settings Variable</th><th> Current Value </th> <th> Select New Value </th> </tr>
		   <tr><th> Color </th> <td> <input class="jscolor" value="$color" id="subject_color" name="subject_color" ></td> <td>  <button class="jscolor {valueElement:'subject_color', styleElement:'subject_color'}">Pick a color</button></td> </tr>
		   <tr><th> Period Type </th> <td  name="_period_type" id="_period_type">  </td> <td> <select name='period_type'> <option value='random'> Random </option> <option value='single'> Single </option><option value='double'> Double </option> </select></td> </tr>
		   <tr><th> Period Times </th> <td  name="_period_times" id="_period_times">  </td> <td> <select name='period_times'> <option value='random'> Random </option> <option value='mornings'> Mornings </option><option value='afternoons'> Afternoons </option> <option value='even'> Even </option> </select></td> </tr> 
		   <tr><th> Grade Settings </th> <td>  </td> <td> <table border=1><tr><th> Grade </th><td> <select name='grade_id' id='grade_id'>  </select> </td></tr> <tr><th> Color </th> <td> <input class="jscolor" value="$color" id="grade_subject_color" name="grade_subject_color" > <button class="jscolor {valueElement:'grade_subject_color', styleElement:'grade_subject_color'}" name='grade_subject_color' >Pick a color</button></td> </tr> <tr><th> Notional Time </th><td> <input type="text" name="notional_time" placeholder="Type Notional Time per Week in Hours" size=60> </td> </tr><tr><th> Period/Cycle </th><td> <select name='periods_cycle'> <option value=2> 2 </option> <option value=3> 3 </option> <option value=4> 4 </option><option value=5> 5 </option> <option value=6> 6 </option> <option value=7> 7 </option> <option value=8> 8 </option> <option value=9> 9 </option> <option value=10> 10 </option> <option value=11> 11 </option> <option value=12> 12 </option> </select> </td> </tr> <tr> <th>Subject Type </th><td> <select id="subject_type" name="subject_type" > <option value="Core">Core</option><option value="Optional">Optional</option>  </select>  </td> </tr><tr><th> Minimum Learners/Class </th> <td> <input type="text" name="minimum_learners" placeholder="Type ideal number of learners required per class" size=60> </td> </tr>
			</tr><tr><th> Grade Entry Average </th> <td> <input type="text" name="minimum_learners" placeholder="Grade entry average" size=60 class="numbersOnly"> </td> </tr>
		   </table></td> </tr>
		   </table> 
		
	</form> 
	<button id="btnSaveSettings1" name="btnSaveSettings1" onClick="saveSubjectSettings()">Save Settings</button>
	</fieldset>
	<button class="previous">Previous</button>&nbsp;&nbsp;&nbsp;&nbsp;<button class="next" >Next</button>
</div>


<div title="Learner Settings" style="padding:10px">
   <br/><br/>
	<table>
		<tr><td>Grade</td><td> 
			<select name="student_grade" id="student_grade"> </select>
		</td></tr>
		<tr><td>Year</td><td> 
			<?php echo $learneryear; ?> </select>
		</td></tr>
		<tr><td>Number of learners per class</td><td> 
			<input type="text" id="number_of_learners" name="number_of_learners"  class="numbersOnly"/> </select>
		</td></tr>
		
	</table>
	<br/>
	<br/>
	<fieldset>
	 <table id="students"></table>
	
	<br/><br/>
	<button id="btnSaveStudentGrade" name="btnSaveStudentGrade" onClick="SaveStudentGrade()">Save</button>
	
	
	</fieldset>
	<br/><br/>
	
	

<button class="previous">Previous</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="next">Next</button>
</div>



<div title="Venue Settings" style="padding:10px">
		<div id="capturetable" name="capturetable">

		</div>
		<div id="additinaltable" name="additinaltable">

		</div>
		<div id="datatable" name="datatable">

		</div>
		<table>
			<tr>
			<td>
			<div id="p" class="easyui-panel" title="Buildings" style="width:500px;height:300px;padding:10px;float:right;">
				<p style="font-size:14px">Building Info</p>
				<ul>
					<li>Put Building Info here</li>
					
				</ul>

			</div>
			</td>
			<td>

			<div id="p2" class="easyui-panel" title="Rooms" style="width:500px;height:300px;padding:10px;float:right;">
				<p style="font-size:14px">Room Info</p>
				<ul>
					<li>Put Room infor here</li>
				</ul>
			</div>
			</td>
			</tr>

		</table>

<button class="previous">Previous</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="next">Next</button>
</div>




<div title="Class Settings" style="padding:10px">
	
		<fieldset>
			<legend>Class Settings</legend>
			<br/>
			<br/>
			<table>
				<tr><td>Rotation</td><td id="_selected_rotation" id="_selected_rotation"></td></tr>
				<tr><td>Year</td><td><?php echo $classyear; ?>   </td></tr>
			</table>	
			<br/>
			<br/>
			<table id="tblClassVenueSetting"></table>
			<br/><br/>
			<button id="btnSaveClassSetting" name="btnSaveClassSetting" onClick="SaveClassSetting()">Save</button>
		</fieldset>
		
	<button class="previous">Previous</button>&nbsp;&nbsp;&nbsp;&nbsp;<button class="next">Next</button>
</div>

<div title="Teacher Settings" style="padding:10px">				
		<fieldset>
			<legend>Time Table Teacher Settings</legend>
			<br/>
				<table>
				<tr><th>Select Subject</th><td><?php echo $teacher_subjects; ?></td><tr>
				<tr><th>Select Grade</th><td><select name="teacher_grade" id="teacher_grade"> </select>  </td><tr>	
				<tr><th>Select Year</th><td><?php echo $teacheryear; ?></td><tr>						
				
				</table>
			<br/>
			<br/>
			<br/>						
				<table id="tblTeacheSetting"></table>
				
				<br/><br/>
				<button id="btnSaveTeacherSetting" name="btnSaveTeacherSetting" onClick="SaveTeacherSetting()">Save</button>
				<br/><br/>
		</fieldset>
		

<button class="previous">Previous</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="next">Next</button>
</div>

<div title="Exam Settings" style="padding:10px">
<button class="previous">Previous</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="next">Next</button>
</div>
<div title="Extra Mural Settings" style="padding:10px">
<button class="previous">Previous</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<button onclick="viewTimeTable()">View Time Tables</button>
</div>


<div title="View/Modify Time Table" style="padding:10px">
	<table>
		<tr>
			<td>Year</td><td><?php echo $teachertimetable_year_id; ?> </td> 
			<td>Grade</td><td><select name='teachertimetable_grade_id' id='teachertimetable_grade_id'></select> </td> 
			<td>Class</td><td><select name='timetable_id' id='timetable_id'>  </select></td>
			<td>Class List </td><td><select name='class_list' id='class_list' style="width:450px;">  </select></td>
			
		</tr>
	</table>
	<br/><br/>
	<div id="timetable" name="timetable">  
		
	</div>

<button class="previous">Previous</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</div>

	<div title="Calendar Settings" style="padding:10px">
	<button class="previous">Previous</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="next">Next</button>
	</div>

	<div title="Reports" style="padding:10px">
		<table>
			<tr><td>Time Table </td><td><select name='timetable_id' id='timetable_id'>  </select></td></tr>
		</table>
		<br/><br/>
		<div id="report" name="report">  
			
		</div>
	
	<button class="previous">Previous</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
	</div>