<?php
session_start();
$_SESSION = array();


include('../lib/db.inc');
include('../lib/timetable.php');

extract($_GET);
extract($_POST);

$sql = "select * from schoollms_schema_userdata_school_year";
$data->execSQL($sql);
$year = date("Y");
//echo $year;
$teachertimetable_year_id = "<select name=\"teachertimetable_year_id\" id=\"teachertimetable_year_id\" >";
$teachertimetable_year_id .= "<option value='All' >All</option>";
while($row = $data->getRow())
{
        if($year == $row->year_label)
        {
                $yearselected = "selected";
        }
        else{
                $yearselected = "";
        }
 $teachertimetable_year_id .= "<option value='$row->year_id' $yearselected>$row->year_label</option>";
}
$teachertimetable_year_id .= "</select>";



?>
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
</div>
<script>


        $(document).ready(function(){

                //getTimetable(22);
		window.onload = function() {
  			getTeacherGrade(user_id);	
		};
	
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
