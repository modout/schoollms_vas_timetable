<?php
session_start();
$_SESSION = array();


include('lib/db.inc');
include('lib/timetable_nodb.php');

extract($_GET);
extract($_POST);
//var_dump($_GET);
//echo $user_id;;


//include('data_db_mysqli.inc');
//include('config_mysql_learn.php');

$user_type = isset($user_type) ? $user_type:4;


$disable = "";
$school_emblem = "";
if(isset($_REQUEST['school_id']))
{
 $school_id = $_REQUEST['school_id'];
 $disable = ($user_type == 1 or $user_type== 5)? "" :"disabled";
 $sql = "select school_emblem from schoollms_schema_userdata_schools where school_id= $school_id";
 $data->execSQL($sql);
 $row =  $data->getRow();
 $school_emblem = $row->school_emblem;
 if (strpos($row->school_name, "^")){
    $school_tokens = explode ("^", $row->school_name);
    $school_name = implode (" ", $school_tokens);
 } else {
     $school_name = $row->school_name;
 }
} 
else{
$school_id = 0;
}
?>

<html>
	<head>
		
		<meta name="description" content="SchoolLMS Timetable Settings Page"/>
		<meta name="viewport" content="width=device-width, user-scalable=no"/><!-- "position: fixed" fix for Android 2.2+ -->
		<link rel="stylesheet" href="style.css" type="text/css" media="screen"/>
		<script type="text/javascript">
			var redipsURL = '/javascript/drag-and-drop-example-3/';
		</script>
		<!--<script type="text/javascript" src="header.js"></script>
		<script type="text/javascript" src="redips-drag-min.js"></script>
		<script type="text/javascript" src="script.js"></script> -->
		<script type="text/javascript" src="timetable.js"></script>
		<script type="text/javascript" src="jscolor.js"></script>
		
		
		<link rel="stylesheet" type="text/css" href="themes/jquery-ui.css" />
		<link rel="stylesheet" type="text/css" href="themes/default/easyui.css">
		<link rel="stylesheet" type="text/css" href="themes/icon.css">
		<!-- script type="text/javascript" src="js/jquery-2.0.0.min.js"></script -->
		<script type="text/javascript" src="js/jquery.1.11.1.min.js"></script>
		<script src="js/jquery.easyui.min.js" type="text/javascript"></script>
		<script src="js/datagrid-filter.js" type="text/javascript"></script>
		<script type="text/javascript" src="js/jquery.searchabledropdown-1.0.8.min.js"></script>
		<script src="js/jscode.js" type="text/javascript"></script>
		<script src="js/teachersetting.js" type="text/javascript"></script>	
		<script src="js/classvenuesettings.js" type="text/javascript"></script>
		<script src="js/studentsettings.js" type="text/javascript"></script>
		<script type="text/javascript" >
	$(document).ready(function () {
		
		//var lessons = sendDataByGet("","getdatafromurl.php");
		//alert(lessons);
		var _class = getUrlParameterPlain("class");
		var subject = getUrlParameterPlain("subject");
		//alert(_class);
		_class = _class.split(" ");
		
		var grade = _class[1].toString();
		grade = grade.trim();
		grade = grade.substring(0,grade.length-1);
		//alert(grade);
		//alert(subject);
		var param ="action=get_subject_terms&subject_name="+subject+"&grade_no="+grade+"&term_no=1";
		//alert(param);
		$("#data").val(param);
		/*var result = sendDataByGet(param,"timetable_subject_lessons_settings.php");
		//alert(result);
		result =  jQuery.parseJSON(result)	;
		$('#lesson').html("");
		
		$.each(result, function(i, item) {
			$('#lesson').append(
				$('<option></option>').val(result[i].title+"&lessonurl="+result[i].alias).html(result[i].title)
			); 
		});*/
		//action=get_subject_terms&subject_name=GEOGRAPHY (J-GEO)&grade_no=8&term_no=1
		
		
	});

	function toggelpressent(user_id,value)
	{		
		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth()+1; //January is 0!
		var yyyy = today.getFullYear();

		if(dd<10) {
			dd='0'+dd
		} 

		if(mm<10) {
			mm='0'+mm
		} 
		mm = getMonthName(mm);
		today = dd+'-'+mm+'-'+yyyy;
		//alert(today);
		var year_id = getUrlParameterPlain("year_id");
		var school_id = getUrlParameterPlain("school_id");
		//alert(year_id + " " + school_id);
		var _class = getUrlParameterPlain("class");
                var subject_class = getUrlParameterPlain("subject_class");
                if (subject_class != undefined){
                    _class = subject_class;
                }
 
                //alert(_class);
		var subject = getUrlParameterPlain("subject");
		var theTimeslot = getUrlParameterPlain("timeslot");			
		//alert(timeslot);
		var timeslot = theTimeslot.split('<br>');
		var thetime = timeslot[0].split('~');
		//alert(timeslot[1]);
		var day = thetime[1];
		day = day.replace("<b>","");
		day = day.replace("</b>","");
		var period = timeslot[2].split("~");
		
		if(period[0] != today)
		{
			alert("Can only mark today's register");
			return;
		}
		
		var ispresent = value.checked;
		//alert(checked);
		
		var result = markRegister(theTimeslot,subject, user_id,year_id,school_id,_class,ispresent);
		//alert(result);
		if(result=="ABSENT" || result=="PRESENT")
		{
                    alert("Learner Marked "+result);
                } else if (result=="PAST") {
                    alert("Cannot Mark Register for a PERIOD that has past for more than 2 periods");
                } else if (result=="COMING") {
                    alert("Cannot Mark Register for a PERIOD that is not started");
		} else {
                    alert("System FAILED to Mark Register");
                }
		//alert(period[0]);
		//var pars = "save_type=attendance_register&user_id="+user_id+"&period_label="+period[1]+"&year_id="+year_id+"&class_label="+_class+"&day="+day+"&date="+period[0]+"&subject="+subject;
		//alert(pars);
		//sendDataByGet(pars,"timetable_save.php");
		
	}
	
	function demerit(user_id,value)
	{
		
		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth()+1; //January is 0!
		var yyyy = today.getFullYear();

		if(dd<10) {
			dd='0'+dd
		} 

		if(mm<10) {
			mm='0'+mm
		} 
		mm = getMonthName(mm);
		today = dd+'-'+mm+'-'+yyyy;
		//alert(today);
		var year_id = getUrlParameterPlain("year_id");
		var school_id = getUrlParameterPlain("school_id");
		//alert(year_id + " " + school_id);
		var _class = getUrlParameterPlain("class");
		var subject = getUrlParameterPlain("subject");
		var theTimeslot = getUrlParameterPlain("timeslot");			
		//alert(timeslot);
		var timeslot = theTimeslot.split('<br>');
		var thetime = timeslot[0].split('~');
		//alert(timeslot[1]);
		var day = thetime[1];
		day = day.replace("<b>","");
		day = day.replace("</b>","");
		var period = timeslot[2].split("~");
		
		if(period[0] != today)
		{
			alert("Can only Give demerits for today's classes");
			return;
		}
		
		//alert(timeslot[1]);
		var day = thetime[1];
		day = day.replace("<b>","");
		day = day.replace("</b>","");
		var period = timeslot[2].split("~");
		
		var pars = "action=MERIT&timeslot="+theTimeslot+"&subject="+subject+"&learner_id="+user_id+"&year_id="+year_id+"&school_id="+school_id+"&class="+_class+"&merit_id="+value;	
		//var pars = "save_type=MERIT&user_id="+user_id+"&period_label="+period[1]+"&year_id="+year_id+"&class_label="+_class+"&day="+day+"&date="+period[0]+"&subject="+subject+"&demerit_reason="+value;
		var result  = getJasonData(pars);		
		//alert(pars);
	}
	
	function merit(user_id,value)
	{
		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth()+1; //January is 0!
		var yyyy = today.getFullYear();

		if(dd<10) {
			dd='0'+dd
		} 

		if(mm<10) {
			mm='0'+mm
		} 
		mm = getMonthName(mm);
		today = dd+'-'+mm+'-'+yyyy;
		//alert(today);
		var year_id = getUrlParameterPlain("year_id");
		var school_id = getUrlParameterPlain("school_id");
		//alert(year_id + " " + school_id);
		var _class = getUrlParameterPlain("class");
		var subject = getUrlParameterPlain("subject");
		var theTimeslot = getUrlParameterPlain("timeslot");			
		//alert(timeslot);
		var timeslot = theTimeslot.split('<br>');
		var thetime = timeslot[0].split('~');
		//alert(timeslot[1]);
		var day = thetime[1];
		day = day.replace("<b>","");
		day = day.replace("</b>","");
		var period = timeslot[2].split("~");
		
		if(period[0] != today)
		{
			alert("Can only Give demerits for today's classes");
			return;
		}
		
		//alert(timeslot[1]);
		var day = thetime[1];
		day = day.replace("<b>","");
		day = day.replace("</b>","");
		var period = timeslot[2].split("~");
		
		var pars = "action=MERIT&timeslot="+theTimeslot+"&subject="+subject+"&learner_id="+user_id+"&year_id="+year_id+"&school_id="+school_id+"&class="+_class+"&merit_id="+value;	
		//var pars = "action=MERIT&user_id="+user_id+"&period_label="+period[1]+"&year_id="+year_id+"&class_label="+_class+"&day="+day+"&date="+period[0]+"&subject="+subject+"&merit_reason="+value;
		var result  = getJasonData(pars);		
		//alert(pars);
		//sendDataByGet(pars,"timetable_save.php");
	}
	
	function getMonthName(month)
	{
		var result = "Jan";
		switch(month)
		{
			case "01":
			{
				result = "Jan";
				break;
			}
			
			case "02":
			{
				result = "Feb";
				break;
			}
			
			case "03":
			{
				result = "Mar";
				break;
			}
			
			case "04":
			{
				result = "Apr";
				break;
			}
			
			case "05":
			{
				result = "May";
				break;
			}
			
			case "06":
			{
				result = "Jun";
				break;
			}
			case "07":
			{
				result = "Jul";
				break;
			}
			case "08":
			{
				result = "Aug";
				break;
			}
			case "09":
			{
				result = "Sep";
				break;
			}
			case "10":
			{
				result = "Oct";
				break;
			}
			case "11":
			{
				result = "Nov";
				break;
			}
			case "12":
			{
				result = "Dec";
				break;
			}
		}
		return result;
	}
</script>
		
</head>
<body style="background:#1E90FF;">
	<div class="header"><center>
                <?php
                echo "<img src='$school_emblem' alt='$school_name' />";
                ?>
	<!--<img src="images/schoollogo.png" alt="Sunward Park School LMS" />-->
	</center><br/>
    </div>
	<div id="viewLearnerMeritsDlg" class="easyui-dialog" style="width:600px;height:400px;padding:10px 20px"
            closed="true" buttons="#dlg-buttons">
        <div class="ftitle" name="thetitle" id="thetitle"><strong>View Learner Merits</strong></div>
        <form runat="server" id="frmViewLearnerMerits" name="frmViewLearnerMerits"  >

            <table id="view_learner_merits" border=1></table>
         </form>
		<table width="80%">
		<tr><td>
				<!--<button id="btnViewLearnerMerits" name="btnViewLearnerMerits" onClick="ViewLearnerMerits()">Save</button>-->
					</td>
					<td>
						<button id="btnViewLearnerMerits" name="btnViewLearnerMerits" onClick="$('#viewLearnerMeritsDlg').dialog('close');">Cancel</button>
					</td>
				</tr>
		</table>
	</div>
    
        <div id="addLearnerMeritsDlg" class="easyui-dialog" style="width:600px;height:400px;padding:10px 20px"
            closed="true" buttons="#dlg-buttons">
        <div class="ftitle" name="thetitle" id="thetitle"><strong>Add Learner Merits</strong></div>
        <form runat="server" id="frmAddLearnerMerits" name="frmAddLearnerMerits"  >

            <table id="select_learner_merits" border=1></table>
         </form>
		<table width="80%">
		<tr><td>
				<button id="btnSaveLearnerMerits" name="btnSaveLearnerMerits" onClick="SaveLearnerMerits()">Save</button>
					</td>
					<td>
						<button id="btnSaveLearnerMerits" name="btnSaveLearnerMerits" onClick="$('#addLearnerMeritsDlg').dialog('close');">Cancel</button>
					</td>
				</tr>
		</table>
	</div>
    
        <div id="viewLearnerDemeritsDlg" class="easyui-dialog" style="width:600px;height:400px;padding:10px 20px"
            closed="true" buttons="#dlg-buttons">
        <div class="ftitle" name="thetitle" id="thetitle"><strong>View Learner Demerits</strong></div>
        <form runat="server" id="frmViewLearnerDemerits" name="frmViewLearnerDemerits"  >

            <table id="view_learner_demerits" border=1></table>
         </form>
		<table width="80%">
		<tr><td>
				<!--<button id="btnViewLearnerMerits" name="btnViewLearnerMerits" onClick="ViewLearnerMerits()">Save</button>-->
					</td>
					<td>
						<button id="btnViewLearnerDemerits" name="btnViewLearnerDemerits" onClick="$('#viewLearnerDemeritsDlg').dialog('close');">Cancel</button>
					</td>
				</tr>
		</table>
	</div>
    
        <div id="addLearnerDemeritsDlg" class="easyui-dialog" style="width:600px;height:400px;padding:10px 20px"
            closed="true" buttons="#dlg-buttons">
        <div class="ftitle" name="thetitle" id="thetitle"><strong>Add Learner Demerits</strong></div>
        <form runat="server" id="frmAddLearnerDemerits" name="frmAddLearnerDemerits"  >

            <table id="select_learner_demerits" border=1></table>
         </form>
		<table width="80%">
		<tr><td>
				<button id="btnSaveLearnerDemerits" name="btnSaveLearnerDemerits" onClick="SaveLearnerDemerits()">Save</button>
					</td>
					<td>
						<button id="btnSaveLearnerDemerits" name="btnSaveLearnerDemerits" onClick="$('#addLearnerDemeritsDlg').dialog('close');">Cancel</button>
					</td>
				</tr>
		</table>
	</div>
    
	<div id="tabs" class="easyui-tabs scroller" style="width:100%;height:550px;flex: 1; overflow: auto;margin-left:10px">
		<div title="Classlist" style="padding:10px">	
	
<?php

//include('lib/db.inc');
include('lib/util.php');

/* $lessondb = new data();
$lessondb->username = "root";
$lessondb->password = "12_s5ydw3ll1979";
$lessondb->host = "localhost";
$lessondb->db = "school_lms_prod_schools_sphs_teach";  */

extract($_POST);
extract($_GET);
$timeslotData = explode("~",$_GET["timeslot"]);
//print_r($timeslotData);
$period_label = $timeslotData[3];
$slot_date_data = explode("<br>",$timeslotData[1]);
$slot_date = $slot_date_data[2];

//alert("QUERY $sql");
if (isset($subject_class)){
    $class_title = $subject_class;
    $subject_class = str_replace(" ","%",$subject_class);
    $sqlq = "select distinct access_id, name, surname, sd.user_id from schoollms_schema_userdata_learner_schooldetails sd join schoollms_schema_userdata_access_profile p on p.user_id = sd.user_id join schoollms_schema_userdata_school_classes sc on sc.class_id = sd.class_id where class_label like '%$subject_class%' and sd.year_id = $year_id and p.school_id = $school_id and p.type_id = 2 order by surname, name asc";
} else {
    $class_title = $class;
    $class = str_replace(" ","%",$class);
    //alert("CLASS $class");
    $sqlq = "select distinct access_id, name, surname, sd.user_id from schoollms_schema_userdata_learner_schooldetails sd join schoollms_schema_userdata_access_profile p on p.user_id = sd.user_id join schoollms_schema_userdata_school_classes sc on sc.class_id = sd.class_id where class_label like '%$class%' and sd.year_id = $year_id and p.school_id = $school_id and p.type_id = 2 order by surname, name asc";
//echo $sql;
//$SQLQ = "TESTING $sqlq";
//alert("QUERY $SQLQ");
}
$table = "<table border=1 id=tblpopup name=tblpopup width='100%'> <tr><td><b>ID Number</b></td><td><b>Student Name</b></td><td><b>Image</b></td><td><b>Present</b></td><td><b>Merit</b></td><td><b>Demerit</b></td><td><b>Action(s)</b></td></tr>";
//alert("TABLE $table");
$result = $data->exec_sql($sqlq, "array");

foreach($result as $key=>$row)
{
        
        $learner_id = $row["user_id"];
        $surname = $row["surname"];
        $name = $row["name"];
        $access_id = $row["access_id"];
        //GET LEARNER MERIT POINTS
        $q = "SELECT * FROM schoollms_schema_userdata_learner_merits WHERE user_id = $learner_id";
        $data->execSQL($q);
        if ($data->numrows > 0){
            $row = $data->getRow();
            $merit_point = $row->merit_points;
        } else {
            $merit_point = 0;
        }
        //GET LEARNER DEMERIT POINTS
        $q = "SELECT * FROM schoollms_schema_userdata_learner_demerits WHERE user_id = $learner_id";
        $data->execSQL($q);
        if ($data->numrows > 0){
            $row = $data->getRow();
            $demerit_point = $row->demerit_points;
        } else {
            $demerit_point = 0;
        }
        
        //CHECK LEARNER REGISTER
        //GET PERIOD LABEL
        $q = "SELECT period_label FROM schoollms_schema_userdata_school_timetable_period_labels WHERE period_label_id = $period_label";
        $data->execSQL($q);
        $row = $data->getRow();
        $period_label_title = $row->period_label;
        $q = "SELECT * FROM schoollms_schema_userdata_learner_attendance_record WHERE learner_id = $learner_id AND period_label = '$period_label_title' AND day_date = '$slot_date'";
        //echo "Q $q <br>";
        //exit;
        $data->execSQL($q);
        if ($data->numrows > 0){
            $checked = "";
        } else {
            $checked = "checked";
        }
        $fullname = strtoupper("$surname, $name");
	$table .=  "<tr><td>$access_id</td><td> $fullname </td><td><img src='api/process.php?action=GETIMAGE&user_id=$learner_id'/></td><td><input type='checkbox'  id='".$access_id."_present' name='".$access_id."_present' value='present' onclick=\"toggelpressent('$learner_id',this);\" $checked /></td>
	<td><center><b>$merit_point</b></center> <br><button name='ViewLearnerMerits' id='bntViewLearnerMerits' onClick='viewLearnerMerits($learner_id)'>View Merit(s)</button><br><button name='AddLearnerMerits' id='bntAddLearnerMerits' onClick='addLearnerMerits($learner_id)'>Add Merit(s)</button></td>
	<td><center><b>$demerit_point</b></center><br><button name='ViewLearnerDemerits' id='bntViewLearnerDemerits' onClick='viewLearnerDemerits($learner_id)'>View Demerit(s)</button><br><button name='AddLearnerDemerits' id='bntAddLearnerDemerits' onClick='addLearnerDemerits($learner_id)'>Add Demerit(s)</button></td>
	<td><button name='bntViewTimetable' id='bntViewTimetable' onClick='viewTimetable($learner_id, $school_id, 2)'>View Timetable</button></td></tr>";
}
$table .= "";

//alert("I AM DONE WITH LEARNERS");
echo "<center> <h2> $class_title </h2></center>";
echo $table;
?>
		</div>
	</div>

<p id="data" name="data">
</p>
</body>
</html>
