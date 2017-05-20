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

