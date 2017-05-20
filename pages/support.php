<div title="School Detail" style="padding:10px">
	<?php
        //include('util.php');
        $url = "http://www.gpsvisualizer.com/draw/";
		echo "SELECT SCHOOL <br> $schools <br><br>";
                
        //$pars= "action=CHECKSYNC&table=$table";
        //echo "$url?$pars <br>\n";
        //$contents = do_post_request($url,$pars);
                
        //echo "<iframe src='$url' width=100% height=100%>  </iframe>";
	?>
	<br/><br/>
	<button onClick="openWindow('addschool.php')">Add New School </button> &nbsp;&nbsp;&nbsp;
	<button onClick="getStep1Data();next();">Next</button>
</div>




<div title="General Settings" style="padding:10px">

	<ul class="easyui-tree" data-options="method:'get',animate:true"></ul>
		
	
   <fieldset id="mydiv" name="mydiv">
   <legend>Timetable General Settings:</legend>
   
	<form action="#" id="frmSchoolInfo" name="frmSchoolInfo">
	   <table><tr><th id="instructions" name="instructions"> </th></tr></table>
		
	   <table><tr><th id="_schoolname" name="_schoolname">  </th></tr></table>
		
	   <table border=1>
				<tr><th> Settings Variable</th><th> Current Value </th> <th> Select New Value </th> </tr>
				<tr><th> Timetable Days </th> <td id="_days" name="_days" >  </td> <td> <select name='days'> <option value=2> 2 </option> <option value=3> 3 </option> <option value=4> 4 </option> <option value=5> 5 </option> <option value=6> 6 </option> <option value=7> 7 </option> <option value=8> 8 </option> <option value=9> 9 </option> <option value=10> 10 </option> </select> days </td> </tr>
				 <tr><th> School Start Time </th> <td id="_school_start_time" name="_school_start_time" >  </td> <td> <input type="text" name="school_start_time" id="school_start_time" maxlength="6" size="6"  /> <font color="red"> Format HH:MM </font> </td> </tr>
				 <tr><th> Class Start Time </th> <td id="_class_start_time" name="_class_start_time" >  </td> <td> <input type="text" name="class_start_time" id="class_start_time" maxlength="6" size="6"  /> <font color="red"> Format HH:MM </font> </td> </tr>
				 
				 <tr><th> Periods </th> <td id="_periods" name="_periods" >  </td> <td> <select name='periods'> <option value=5> 5 </option> <option value=6> 6 </option> <option value=7> 7 </option> <option value=8> 8 </option> <option value=9> 9 </option> <option value=10> 10 </option> </select> </td> </tr>
				 <!-- tr><th> Periods Day</th> <td id="_period_day" name="_period_day" >  </td> <td> <select name='period_day' id='period_day'> <option value='Monday'> Monday </option> <option value='Tuesday'> Tuesday </option> <option value='Wednesday'> Wednesday </option> <option value='Thursday'> Thursday </option> <option value='Friday'> Friday </option> <option value='Saturday'> Saturday </option> <option value='Sunday'> Sunday </option> </select> </td> </tr>
				 <tr><th> Period Time </th> <td id="_period_time" name="_period_time" >  </td> <td> <select name='period_time'> <option value=30> 30 </option> <option value=35> 35 </option> <option value=40> 40 </option> <option value=45> 45 </option> <option value=50> 50 </option> <option value=55> 55 </option> <option value=60> 60 </option></select> minutes</td> </tr -->
				 <tr>
					<td>
						<strong>Period Settings</strong>
					</td>
					<td>&nbsp;</td>
					<td>
						<table>
							<tr>
								<td><strong>Period Day</strong></td>
								<th>| Monday </th>
								<th>| Tuesday </th>
								<th>| Wednesday </th>
								<th>| Thursday </th>
								<th>| Friday </th>
								<th>| Saturday </th>
								<th>| Sunday </th>
							</tr>
							<tr>
								<td><strong>Period Time</strong></td>
								<td><input type="text" name="monday_period_time" id="monday_period_time" maxlength="2" size="2"   /></td>
								<td><input type="text" name="tuesday_period_time" id="tuesday_period_time" maxlength="2" size="2"   /></td>
								<td><input type="text" name="wednesday_period_time" id="wednesday_period_time" maxlength="2" size="2"   /></td>
								<td><input type="text" name="thursday_period_time" id="thursday_period_time" maxlength="2" size="2"   /></td>
								<td><input type="text" name="friday_period_time" id="friday_period_time" maxlength="2" size="2"   /></td>
								<td><input type="text" name="saturday_period_time" id="saturday_period_time" maxlength="2" size="2"   /></td>
								<td><input type="text" name="sunday_period_time" id="sunday_period_time" maxlength="2" size="2"   /></td>
							</tr>
						</table>
					</td>				 
				 </tr>
				 
				 <tr><th> Number of breaks </th> <td id="_number_of_breaks" name="_number_of_breaks" >  </td> <td> <select name='number_of_breaks' id='number_of_breaks'> <option value=1> 1 </option><option value=2> 2 </option><option value=3> 3 </option><option value=4> 4 </option> </select>  breaks per school day </td> </tr>
                                 <!--<tr id="row_break_0" name="row_break_0"><th> Break Day </th> <th> </th> <td><table><tr><th></th><th></th><th></th><th>| Thursday |</th><th>| Friday |</th><th>| Saturday |</th><th>| Sunday |</th></tr></table></td>-->
                                 <tr id="row_break_1" name="row_break_1"><th> First Break Time </th> <td id="_break_1" name="_break_1" >  </td> <td><table><tr><td>| Monday <br><input type="text" name="monday_break_time_1" id="monday_break_time_1" maxlength="6" size="6"   /><br> for <input type="text" name="monday_break_length_1" id="monday_break_length_1" maxlength="2" size="2"   />  min |</td><td>| Tuesday <br><input type="text" name="tuesday_break_time_1" id="tuesday_break_time_1" maxlength="6" size="6"   /> <br>for <input type="text" name="tuesday_break_length_1" id="tuesday_break_length_1" maxlength="2" size="2"   />  min |</td> <td>| Wednesday <br><input type="text" name="wednesday_break_time_1" id="wednesday_break_time_1" maxlength="6" size="6"   /> <br> for <input type="text" name="wednesday_break_length_1" id="wednesday_break_length_1" maxlength="2" size="2"   />  min |</td> <td>| Thursday <br><input type="text" name="thursday_break_time_1" id="thursday_break_time_1" maxlength="6" size="6"   /> <br> for <input type="text" name="thursday_break_length_1" id="thursday_break_length_1" maxlength="2" size="2"   />  min |</td> <td>| Friday <br><input type="text" name="friday_break_time_1" id="friday_break_time_1" maxlength="6" size="6"   /> <br>for <input type="text" name="friday_break_length_1" id="friday_break_length_1" maxlength="2" size="2"   />  min |</td> <td>| Saturday <br><input type="text" name="saturday_break_time_1" id="saturday_break_time_1" maxlength="6" size="6"   /> <br>for <input type="text" name="saturday_break_length_1" id="saturday_break_length_1" maxlength="2" size="2"   />  min |</td><td>| Sunday <br><input type="text" name="sunday_break_time_1" id="sunday_break_time_1" maxlength="6" size="6"   /><br> for <input type="text" name="sunday_break_length_1" id="sunday_break_length_1" maxlength="2" size="2"   />  min </td></tr></table><font color="red"> Format HH:MM </font></td> </tr>
                                 <tr id="row_break_2" name="row_break_2"><th> Second Break Time </th> <td id="_break_2" name="_break_2" >  </td> <td><table><tr><td>| Monday <br><input type="text" name="monday_break_time_2" id="monday_break_time_2" maxlength="6" size="6"   /><br> for <input type="text" name="monday_break_length_2" id="monday_break_length_2" maxlength="2" size="2"   />  min |</td><td>| Tuesday <br><input type="text" name="tuesday_break_time_2" id="tuesday_break_time_2" maxlength="6" size="6"   /> <br> for <input type="text" name="tuesday_break_length_2" id="tuesday_break_length_2" maxlength="2" size="2"   />  min |</td> <td>| Wednesday <br><input type="text" name="wednesday_break_time_2" id="wednesday_break_time_2" maxlength="6" size="6"   /><br> for <input type="text" name="wednesday_break_length_2" id="wednesday_break_length_2" maxlength="2" size="2"   />  min |</td> <td>| Thursday <br><input type="text" name="thursday_break_time_2" id="thursday_break_time_2" maxlength="6" size="6"   /> <br> for <input type="text" name="thursday_break_length_2" id="thursday_break_length_2" maxlength="2" size="2"   />  min |</td> <td>| Friday <br><input type="text" name="friday_break_time_2" id="friday_break_time_2" maxlength="6" size="6"   /><br> for <input type="text" name="friday_break_length_2" id="friday_break_length_2" maxlength="2" size="2"   />  min |</td> <td>| Saturday <br><input type="text" name="saturday_break_time_2" id="saturday_break_time_2" maxlength="6" size="6"   /> <br> for <input type="text" name="saturday_break_length_2" id="saturday_break_length_2" maxlength="2" size="2"   />  min |</td><td>| Sunday <br><input type="text" name="sunday_break_time_2" id="sunday_break_time_2" maxlength="6" size="6"   /><br> for <input type="text" name="sunday_break_length_2" id="sunday_break_length_2" maxlength="2" size="2"   />  min </td></tr></table><font color="red"> Format HH:MM </font></td> </tr>
                                 <tr id="row_break_3" name="row_break_3"><th> Third Break Time </th> <td id="_break_3" name="_break_3" >  </td> <td><table><tr><td>| Monday <br><input type="text" name="monday_break_time_3" id="monday_break_time_3" maxlength="6" size="6"   /><br> for <input type="text" name="monday_break_length_3" id="monday_break_length_3" maxlength="2" size="2"   />  min |</td><td>| Tuesday <br><input type="text" name="tuesday_break_time_3" id="tuesday_break_time_3" maxlength="6" size="6"   /> <br> for <input type="text" name="tuesday_break_length_3" id="tuesday_break_length_3" maxlength="2" size="2"   />  min |</td> <td>| Wednesday <br><input type="text" name="wednesday_break_time_3" id="wednesday_break_time_3" maxlength="6" size="6"   /><br> for <input type="text" name="wednesday_break_length_3" id="wednesday_break_length_3" maxlength="2" size="2"   />  min |</td> <td>| Thursday <br><input type="text" name="thursday_break_time_3" id="thursday_break_time_3" maxlength="6" size="6"   /> <br> for <input type="text" name="thursday_break_length_3" id="thursday_break_length_3" maxlength="2" size="2"   />  min |</td> <td>| Friday <br><input type="text" name="friday_break_time_3" id="friday_break_time_3" maxlength="6" size="6"   /><br> for <input type="text" name="friday_break_length_3" id="friday_break_length_3" maxlength="2" size="2"   />  min |</td> <td>| Saturday <br><input type="text" name="saturday_break_time_3" id="saturday_break_time_3" maxlength="6" size="6"   /> <br>for <input type="text" name="saturday_break_length_3" id="saturday_break_length_3" maxlength="2" size="2"   />  min |</td><td>| Sunday <br><input type="text" name="sunday_break_time_3" id="sunday_break_time_3" maxlength="6" size="6"   /><br> for <input type="text" name="sunday_break_length_3" id="sunday_break_length_3" maxlength="2" size="2"   />  min </td></tr></table><font color="red"> Format HH:MM </font></td> </tr>
                                 <tr id="row_break_4" name="row_break_4"><th> Fourth Break Time </th> <td id="_break_4" name="_break_4" >  </td> <td> <table><tr><td>| Monday <br><input type="text" name="monday_break_time_4" id="monday_break_time_4" maxlength="6" size="6"   /><br> for <input type="text" name="monday_break_length_4" id="monday_break_length_4" maxlength="2" size="2"   />  min |</td><td>| Tuesday <br><input type="text" name="tuesday_break_time_4" id="tuesday_break_time_4" maxlength="6" size="6"   /> <br> for <input type="text" name="tuesday_break_length_4" id="tuesday_break_length_4" maxlength="2" size="2"   />  min |</td> <td>| Wednesday <br><input type="text" name="wednesday_break_time_4" id="wednesday_break_time_4" maxlength="6" size="6"   /><br> for <input type="text" name="wednesday_break_length_4" id="wednesday_break_length_4" maxlength="2" size="2"   />  min |</td> <td>| Thursday <br><input type="text" name="thursday_break_time_4" id="thursday_break_time_4" maxlength="6" size="6"   /> <br> for <input type="text" name="thursday_break_length_4" id="thursday_break_length_4" maxlength="2" size="2"   />  min |</td> <td>| Friday <br><input type="text" name="friday_break_time_4" id="friday_break_time_4" maxlength="6" size="6"   /><br> for <input type="text" name="friday_break_length_4" id="friday_break_length_4" maxlength="2" size="2"   />  min |</td> <td>| Saturday <br><input type="text" name="saturday_break_time_4" id="saturday_break_time_4" maxlength="6" size="6"   /><br> for <input type="text" name="saturday_break_length_4" id="saturday_break_length_4" maxlength="2" size="2"   />  min |</td><td>| Sunday <br><input type="text" name="sunday_break_time_4" id="sunday_break_time_4" maxlength="6" size="6"   /><br> for <input type="text" name="sunday_break_length_4" id="sunday_break_length_4" maxlength="2" size="2"   />  min </td></tr></table><font color="red"> Format HH:MM </font></td> </tr>
				 
				 <tr><th> Grades </th> <td id="_from_grade" name="_from_grade" >  </td><td> From <select name='from_grade' id='from_grade'> <option value="R"> R </option> <option value=1> 1 </option> <option value=2> 2 </option> <option value=3> 3 </option><option value=4> 4 </option><option value=5> 5 </option><option value=6> 6 </option><option value=7> 7 </option><option value=8> 8 </option><option value=9> 9 </option><option value=10> 10 </option><option value=11> 11 </option><option value=12> 12 </option></select> To <select name='to_grade' id='to_grade'> <option value="R"> R </option> <option value=1> 1 </option> <option value=2> 2 </option> <option value=3> 3 </option><option value=4> 4 </option><option value=5> 5 </option><option value=6> 6 </option><option value=7> 7 </option><option value=8> 8 </option><option value=9> 9 </option><option value=10> 10 </option><option value=11> 11 </option><option value=12> 12 </option> </select></td>  </tr>
				 <tr><th> Has Registration Class?</th><td id="_has_registration" name="_has_registration"></td><td> <select id="has_registration" name="has_registration"> <option value="Yes"> Yes</option> <option value="No"> No</option> </select> </td></tr>  
				 <tr><th> Registration Class Length</th><td id="_registration_length" name="_registration_length"></td><td> <select id="registration_length" name="registration_length">  
						<option value="00">00</option><option value="05">05</option><option value="10">10</option><option value="15">15</option><option value="20">20</option>
						<option value="25">25</option><option value="30">30</option><option value="35">35</option><option value="40">40</option><option value="45">45</option>
						<option value="50">50</option><option value="55">55</option> <option value="60">60</option> 
				 </select>  Minutes </td></tr>  
				 <tr><th> Classletters <input type='hidden' name='school_id' id='school_id' ></th> <td id="_classletters" name="_classletters">  </td> <td> <input type="text" name="classletters" id="classletters" placeholder="Type A-Z or preferred letters separated by comma" size=60> </td> </tr>
				<tr><th> Rotation Type </th> <td id="_rotation_type" name="_rotation_type" >  </td><td> <select name='rotation_type' id='rotation_type' > <option value="Teacher Rotates"> Teacher Rotates </option> <option value="Learner Rotates"> Learner Rotates </option>  </td>  </tr>
				 
		</table>				   
	   </form> 
	   <button id="btnSaveSettings1" name="btnSaveSettings1" onClick="saveSettings1()">Save Settings</button>
   </fieldset>
	
	
	<button class="previous">Previous</button>&nbsp;&nbsp;&nbsp;&nbsp;<button onclick="getSubjectData()">Next</button>
</div>

<div title="Access Control" style="padding:10px">
	
	<br/>
	<table><tr><td><input type="text" id="searchStr" name="searchStr" /> </td><td>&nbsp;&nbsp;&nbsp; <button type="button" id="btnSearch" name="btnSearch" onClick="accessControl()">Search </button></td> 
		<td> &nbsp;&nbsp;&nbsp;<input type="button" id="btnAddUser" name="btnAddUser" value="Add Users" /> </td> </tr></table>
	 <br/>
	 <table id="accesscontrol"></table>
	 <br/>
	<button class="previous" >Previous</button> &nbsp;&nbsp;&nbsp;
	<button class="next" >Next</button>
</div>

<div title="Subject Settings" style="padding:10px">
	<fieldset>
	<form action="#" id="frmSubjectSetting" name="frmSubjectSetting">
	
	<legend> Subject Settings:</legend>
	<br/>
		<?php echo $subjects; ?> &nbsp;&nbsp;&nbsp;<input type="button" value="Add Subject" id="btnAddSubject" name="btnAddSubject"/>
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

<div title="Teacher Settings" style="padding:10px">	
<div id="newTeacherDlg" class="easyui-dialog" style="width:520px;height:250px;padding:10px 20px"
            closed="true" buttons="#dlg-buttons">
        <div class="ftitle" name="thetitle" id="thetitle"><strong>Teacher Information</strong></div>
        <form runat="server" id="frmSaveTeacher" name="frmSaveTeacher"  >
			<table style="width: 100%" cellspacing=2 cellpadding=2 >   
				<tr>
					<td align="left" colspan=2>
						<input type="hidden" id="user_id" name="user_id" />
					</td>
				</tr>			
				<tr>
					<td style="width: 40%">
						 Teacher ID
					</td>
					<td align="left">
						<input type="text" id="teacher_id" name="teacher_id"  placeholder="Teacher ID" />
					</td>
				</tr>
				<tr>
					<td style="width: 40%">
						 Teacher Initials
					</td>
					<td align="left">
						<input type="text" id="teacher_initials" name="teacher_initials"  placeholder="Teacher Initials" />
					</td>
				</tr>
				<tr>
					<td style="width: 40%">
						 Teacher Surname
					</td>
					<td align="left">
						<input type="text" id="teacher_surname" name="teacher_surname"  placeholder="Teacher Surname" />
					</td>
				</tr>
                                <tr>
					<td style="width: 40%">
						 Teacher Subjects  
					</td>
					<td align="left">
                                                <br>
                                                Select New Teacher Subject Settings
                                                <br>
                                                <table>
                                                <tr><th>Number of Subjects </th><td id="_number_of_subjects" name="_number_of_subjects"> <select name='number_of_subjects' id='number_of_subjects'> <option value=1> 1 </option><option value=2> 2 </option><option value=3> 3 </option><option value=4> 4 </option> <option value=5> 5 </option><option value=6> 6 </option><option value=7> 7 </option><option value=8> 8 </option><option value=9> 9 </option><option value=10>10 </option><option value=11> 11 </option><option value=12> 12 </option> <option value=13> 13 </option><option value=14> 14 </option><option value=15> 15 </option><option value=16> 16 </option> </select></td></tr>
                                                </table>
						<table>
                                                <tr><th>Year</th><th>Subject</th><th>Grade</th><tr>
                                                <tr id="row_subject_1" name="row_subject_1"><td><?php echo $teacheryear1; ?></td><td><?php echo $teacher_subjects1; ?></td><td><select name="teacher_grade_1" id="teacher_grade_1"> </select>  </td><tr>						
                                                <tr id="row_subject_2" name="row_subject_2"><td><?php echo $teacheryear2; ?></td><td><?php echo $teacher_subjects2; ?></td><td><select name="teacher_grade_2" id="teacher_grade_2"> </select>  </td><tr>	
                                                <tr id="row_subject_3" name="row_subject_3"><td><?php echo $teacheryear3; ?></td><td><?php echo $teacher_subjects3; ?></td><td><select name="teacher_grade_3" id="teacher_grade_3"> </select>  </td><tr>						
                                                <tr id="row_subject_4" name="row_subject_4"><td><?php echo $teacheryear4; ?></td><td><?php echo $teacher_subjects4; ?></td><td><select name="teacher_grade_4" id="teacher_grade_4"> </select>  </td><tr>
                                                <tr id="row_subject_5" name="row_subject_5"><td><?php echo $teacheryear5; ?></td><td><?php echo $teacher_subjects5; ?></td><td><select name="teacher_grade_5" id="teacher_grade_5"> </select>  </td><tr>						
                                                <tr id="row_subject_6" name="row_subject_6"><td><?php echo $teacheryear6; ?></td><td><?php echo $teacher_subjects6; ?></td><td><select name="teacher_grade_6" id="teacher_grade_6"> </select>  </td><tr>	
                                                <tr id="row_subject_7" name="row_subject_7"><td><?php echo $teacheryear7; ?></td><td><?php echo $teacher_subjects7; ?></td><td><select name="teacher_grade_7" id="teacher_grade_7"> </select>  </td><tr>						
                                                <tr id="row_subject_8" name="row_subject_8"><td><?php echo $teacheryear8; ?></td><td><?php echo $teacher_subjects8; ?></td><td><select name="teacher_grade_8" id="teacher_grade_8"> </select>  </td><tr>
                                                <tr id="row_subject_9" name="row_subject_9"><td><?php echo $teacheryear9; ?></td><td><?php echo $teacher_subjects9; ?></td><td><select name="teacher_grade_9" id="teacher_grade_9"> </select>  </td><tr>						
                                                <tr id="row_subject_10" name="row_subject_10"><td><?php echo $teacheryear10; ?></td><td><?php echo $teacher_subjects10; ?></td><td><select name="teacher_grade_10" id="teacher_grade_10"> </select>  </td><tr>	
                                                <tr id="row_subject_11" name="row_subject_11"><td><?php echo $teacheryear11; ?></td><td><?php echo $teacher_subjects11; ?></td><td><select name="teacher_grade_11" id="teacher_grade_11"> </select>  </td><tr>						
                                                <tr id="row_subject_12" name="row_subject_12"><td><?php echo $teacheryear12; ?></td><td><?php echo $teacher_subjects12; ?></td><td><select name="teacher_grade_12" id="teacher_grade_12"> </select>  </td><tr>
                                                <tr id="row_subject_13" name="row_subject_13"><td><?php echo $teacheryear13; ?></td><td><?php echo $teacher_subjects13; ?></td><td><select name="teacher_grade_13" id="teacher_grade_13"> </select>  </td><tr>						
                                                <tr id="row_subject_14" name="row_subject_14"><td><?php echo $teacheryear14; ?></td><td><?php echo $teacher_subjects14; ?></td><td><select name="teacher_grade_14" id="teacher_grade_14"> </select>  </td><tr>	
                                                <tr id="row_subject_15" name="row_subject_15"><td><?php echo $teacheryear15; ?></td><td><?php echo $teacher_subjects15; ?></td><td><select name="teacher_grade_15" id="teacher_grade_15"> </select>  </td><tr>						
                                                <tr id="row_subject_16" name="row_subject_16"><td><?php echo $teacheryear16; ?></td><td><?php echo $teacher_subjects16; ?></td><td><select name="teacher_grade_16" id="teacher_grade_16"> </select>  </td><tr>
                                                </table>
                                                <br>
                                                Current Subject Settings
                                                <br>
					</td>
				</tr>
			</table>
			
		</form>
		<table width="80%">
		<tr><td>
				<button id="btnSaveTeacher" name="btnSaveTeacher" onClick="SaveNewTeacher()">Save</button>
					</td>
					<td>
						<button id="btnSaveLearner" name="btnSaveLearner" onClick="$('#newTeacherDlg').dialog('close');">Cancel</button>
					</td>
				</tr>
		</table>
	</div>			
		<fieldset>
			<legend>Time Table Teacher Settings</legend>
<!--			<br/>
				<table>
				<tr><th>Select Subject</th><td><?php //echo $teacher_subjects; ?></td><tr>
				<tr><th>Select Grade</th><td><select name="teacher_grade" id="teacher_grade"> </select>  </td><tr>	
				<tr><th>Select Year</th><td><?php //echo $teacheryear; ?></td><tr>						
				
				</table>
			<br/>-->
			<br/>
			<br/>						
				<table id="tblTeacheSetting"></table>
				
				<br/><br/>
				<button id="btnSaveTeacherSetting" name="btnSaveTeacherSetting" onClick="SaveTeacherSetting()">Save</button>
				<br/><br/>
		</fieldset>
		

<button class="previous">Previous</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="next">Next</button>
</div>

<div title="Class Settings" style="padding:10px">
    <div id="addTeacherDlg" class="easyui-dialog" style="width:600px;height:400px;padding:10px 20px"
            closed="true" buttons="#dlg-buttons">
        <div class="ftitle" name="thetitle" id="thetitle"><strong>Add Class Teacher</strong></div>
        <form runat="server" id="frmSaveTeacher" name="frmSaveTeacher"  >

            <table id="select_teacher" border=1></table>
         </form>
		<table width="80%">
		<tr><td>
				<button id="btnSaveTeacher" name="btnSaveTeacher" onClick="SaveClassTeacher()">Save</button>
					</td>
					<td>
						<button id="btnSaveLearner" name="btnSaveTeacher" onClick="$('#addTeacherDlg').dialog('close');">Cancel</button>
					</td>
				</tr>
		</table>
	</div>
    <div id="addLearnersDlg" class="easyui-dialog" style="width:600px;height:400px;padding:10px 20px"
            closed="true" buttons="#dlg-buttons">
        <div class="ftitle" name="thetitle" id="thetitle"><strong>Add Learners To Class</strong></div>
        <form runat="server" id="frmSaveLearners" name="frmSaveLearners"  >

            <table id="select_learners" border=1></table>
         </form>
		<table width="80%">
		<tr><td>
				<button id="btnSaveLearners" name="btnSaveLearners" onClick="SaveLearnersToClass()">Save</button>
					</td>
					<td>
						<button id="btnSaveLearner" name="btnSaveLearner" onClick="$('#addLearnersDlg').dialog('close');">Cancel</button>
					</td>
				</tr>
		</table>
	</div>
    <div id="addClassDlg" class="easyui-dialog" style="width:520px;height:250px;padding:10px 20px"
            closed="true" buttons="#dlg-buttons">
        <div class="ftitle" name="thetitle" id="thetitle"><strong>Add Class</strong></div>
        <form runat="server" id="frmSaveClass" name="frmSaveClass"  >

         </form>
		<table width="80%">
		<tr><td>
				<button id="btnSaveClass" name="btnSaveClass" onClick="SaveClass()">Save</button>
					</td>
					<td>
						<button id="btnSaveClass" name="btnSaveClass" onClick="$('#addClassDlg').dialog('close');">Cancel</button>
					</td>
				</tr>
		</table>
	</div>
   <br/><br/>
   <center>
   <table>
		<tr>
			<td>Year</td><td><?php echo $teachertimetable_year_id; ?> </td> 
                        <td>Grade</td><td><select name="student_grade1" id="student_grade1"> </select></td>
                        <td>Class</td><td><select name='class_id' id='class_id'>  </select></td>
		</tr>
	</table>
	<br/>
	<br/>
        <button name="bntAddClass" id="bntAddClass" onClick="addNewClass()">Add Class</button>
	<br/><br/><img id="loading" name="loading" src="images/loading.gif"><br/>
	<fieldset>
         
	 <table id="classlists" border="2"></table>
         
	<br/><br/>
	<button id="btnSaveStudentGrade" name="btnSaveStudentGrade" onClick="SaveStudentGrade()">Save</button>
	
	
	</fieldset>
        </center>
   <button class="previous">Previous</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="next">Next</button>
</div>


<div title="Venue Settings" style="padding:10px">
	
		<fieldset>
			<legend>Venue Settings</legend>
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


<div title="Calendar Settings" style="padding:10px">
    <div id="addYearDlg" class="easyui-dialog" style="width:520px;height:250px;padding:10px 20px"
            closed="true" buttons="#dlg-buttons">
        <div class="ftitle" name="thetitle" id="thetitle"><strong>Add Year</strong></div>
        <form runat="server" id="frmSaveYear" name="frmSaveYear">
            <input type="text" id="year" name="year"  placeholder="" size="4"/>
         
		<table width="80%">
		<tr><td>
				<button id="btnSaveYear" name="btnSaveYear" onClick="SaveYear()">Save</button>
					</td>
					<td>
						<button id="btnSaveYear" name="btnSaveYear" onClick="$('#addYearDlg').dialog('close');">Cancel</button>
					</td>
				</tr>
		</table>
            </form>
	</div>
    <fieldset>
        <legend>Calendar Settings</legend>
        <br/>
        <br/>
        <center>
            
        <button name="bntAddYear" id="bntAddYear" onClick="addNewYear()">Add Year</button>
	<br/><br/><!--img id="loading" name="loading" src="images/loading.gif"><br/>-->
        <table>
                <tr>
                    <td>Year</td><td><?php echo $classyear; ?>   </td>
                    <td>Calendar Type</td><td><?php echo $calendar_type; ?>   </td>
                    <td>Start Time </td><td> <input id="start_time" name="start_time" /></td>
                    <td>Stop Time </td><td> <input id="stop_time" name="stop_time" /></td>
                    <td>Calendar Name </td><td> <input id="calendar_name" name="calendar_name" /></td>
                </tr>
                <tr>
                    <td> <button id="btnSaveCalendarItem" name="btnSaveCalendarItem" onClick="SaveCalendarItem()">Save Calendar</button> </td><td></td>
                    <td></td><td></td>
                    <td></td><td></td>
                    <td></td><td></td>
                    <td></td><td></td>
                </tr>
        </table>	
        <br/>
        <br/>
        <table id="tblCalendar"></table>
        <br/><br/>
        </center>
    </fieldset>
    
<button class="previous">Previous</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="next">Next</button>
</div>

<div title="Exam Settings" style="padding:10px">
<button class="previous">Previous</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="next">Next</button>
</div>
<div title="Extra Mural Settings" style="padding:10px">
<button class="previous">Previous</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<button onclick="btnViewTimeTable()">View Time Tables</button>
</div>

<div title="Merit/Demerit Settings" style="padding:10px">
<button class="previous">Previous</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="next">Next</button>
</div>

<div title="Device Tracking Settings" style="padding:10px">
<button class="previous">Previous</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="next">Next</button>
</div>

<div title="Learning Tracking Settings" style="padding:10px">
<button class="previous">Previous</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<button class="next">Next</button>
</div>

<div title="View/Modify Time Table" style="padding:10px">
	<table>
		<tr>
			<td>Year</td><td><?php echo $teachertimetable_year_id; ?> </td> 
			<td>Grade</td><td><select name='teachertimetable_grade_id' id='teachertimetable_grade_id'  ></select> </td> 
			<td>Class</td><td><select name='timetable_id' id='timetable_id'>  </select></td>
		
			<td>Class List </td>
			<td colspan="3">
				<div style="border: 2px solid black;">
					<select name='class_list' id='class_list' style="width:250px;height:20px"> </select>
				</div>
			</td>
			<td>
				Teacher List
			</td>
			<td colspan="2">
				<div style="border: 2px solid black;">
					<select name='teacher_list' id='teacher_list' style="width:250px;height:20px"> </select>
				</div>
			</td>
			
		</tr>
	</table>
	<br/><br/>
	<button name="bntAddTimetableSlot" id="bntAddTimetableSlot" onClick="addNewTimeTableSlot()">Add Time Table Slot</button>
<br/><br/><center><img id="timetableloading" name="loading" src="images/loading.gif"><center><br/>	
<div id="timetable" name="timetable">  
		
</div>

<button class="previous">Previous</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</div>
<div title="Reports" style="padding:10px">
    <table>
	<tr><td>Timetable </td><td><select name='timetable_id' id='timetable_id'>  </select></td></tr>
    </table>
    <br/><br/>
    <div id="report" name="report">  
			
    </div>
	
    <button class="previous">Previous</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</div>

<script>
	

	$(document).ready(function(){

		//getTimetable(22);
                
                $("#teachertimetable_year_id").on('change', function(){
                   
                });
		
		$("#teachertimetable_grade_id").on('change', function() {
			//alert($("#teachertimetable_grade_id").val());
			
			viewTimeTable($("#teachertimetable_grade_id").val());
		});
		
		$("#btnAddUser").click(function () {
			adduser();
			//next();
		});
		
                $("#student_grade1").on('change', function() {
                    getClass($("#student_grade1").val());
                });
                
                $("#class_id").on('change', function() {
                    getGradeClassList($("#student_grade1").val(),$("#class_id").val());
                });
                
		$("#graderow").hide();
		$("#classrow").hide();
		
		$("#type_id").on('change', function() {
			if( $("#type_id option:selected").text() == "learner")
			{
				$("#graderow").show();
				$("#classrow").show();
			}
			else{
				$("#graderow").hide();
				$("#classrow").hide();
			}
		});

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
