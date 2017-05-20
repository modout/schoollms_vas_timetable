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



//$user_type = 4;
/**
usertype : 2
staff : 1
learner : 2
parent : 3
teacher : 4
manager :  5
support : 6

[1/11/2016, 15:43] Modise: Teachers see View Timetable...
[1/11/2016, 15:44] Modise: But the school in Tzaneen has nobody that can do what I still need to do...
[1/11/2016, 15:44] Modise: Parents see view Timetable...
[1/11/2016, 15:45] Modise: Management sees View Timetable same as Admin plus Reports...
[1/11/2016, 15:46] Modise: Support sees all tabs...

*/

$student_table_title = "All Students";
$students_per_grade = "Grade Students";


$sql = "select * from schoollms_schema_userdata_schools";
$data->execSQL($sql);
$schools = "<select name=\"schools\" id=\"schools\" $disable>";
$schools .= "<option value='0'>Select School</option>"; 
while($row = $data->getRow())
{
	$school_name = str_replace('^',' ',$row->school_name);
	if($row->school_id == $school_id )
	{	
		$schools .= "<option value='$row->school_id' selected>$school_name</option>"; 
	}
	else{
		$schools .= "<option value='$row->school_id' >$school_name</option>";
	}
}
$schools .= "</select>";

$sql = "select * from schoollms_schema_userdata_school_subjects ";
$data->execSQL($sql);
$subjects = "<select name=\"subject_id\" id=\"subject_id\" >";
$subjects .= "<option value='0'>Select Subject to get Settings</option>"; 

$teacher_subjects1 = "<select name=\"teacher_subject_id1\" id=\"teacher_subject_id1\" >";
$teacher_subjects1 .= "<option value='0'>Select Subject</option>"; 
$teacher_subjects2 = "<select name=\"teacher_subject_id2\" id=\"teacher_subject_id2\" >";
$teacher_subjects2 .= "<option value='0'>Select Subject</option>";
$teacher_subjects3 = "<select name=\"teacher_subject_id3\" id=\"teacher_subject_id3\" >";
$teacher_subjects3 .= "<option value='0'>Select Subject</option>"; 
$teacher_subjects4 = "<select name=\"teacher_subject_id4\" id=\"teacher_subject_id4\" >";
$teacher_subjects4 .= "<option value='0'>Select Subject</option>"; 
$teacher_subjects5 = "<select name=\"teacher_subject_id5\" id=\"teacher_subject_id5\" >";
$teacher_subjects5 .= "<option value='0'>Select Subject</option>"; 
$teacher_subjects6 = "<select name=\"teacher_subject_id6\" id=\"teacher_subject_id6\" >";
$teacher_subjects6 .= "<option value='0'>Select Subject</option>";
$teacher_subjects7 = "<select name=\"teacher_subject_id7\" id=\"teacher_subject_id7\" >";
$teacher_subjects7 .= "<option value='0'>Select Subject</option>"; 
$teacher_subjects8 = "<select name=\"teacher_subject_id8\" id=\"teacher_subject_id8\" >";
$teacher_subjects8 .= "<option value='0'>Select Subject</option>"; 
$teacher_subjects9 = "<select name=\"teacher_subject_id9\" id=\"teacher_subject_id9\" >";
$teacher_subjects9 .= "<option value='0'>Select Subject</option>"; 
$teacher_subjects10 = "<select name=\"teacher_subject_id10\" id=\"teacher_subject_id10\" >";
$teacher_subjects10 .= "<option value='0'>Select Subject</option>";
$teacher_subjects11 = "<select name=\"teacher_subject_id11\" id=\"teacher_subject_id11\" >";
$teacher_subjects11 .= "<option value='0'>Select Subject</option>"; 
$teacher_subjects12 = "<select name=\"teacher_subject_id12\" id=\"teacher_subject_id12\" >";
$teacher_subjects12 .= "<option value='0'>Select Subject</option>"; 
$teacher_subjects13 = "<select name=\"teacher_subject_id13\" id=\"teacher_subject_id13\" >";
$teacher_subjects13 .= "<option value='0'>Select Subject</option>"; 
$teacher_subjects14 = "<select name=\"teacher_subject_id14\" id=\"teacher_subject_id14\" >";
$teacher_subjects14 .= "<option value='0'>Select Subject</option>";
$teacher_subjects15 = "<select name=\"teacher_subject_id15\" id=\"teacher_subject_id15\" >";
$teacher_subjects15 .= "<option value='0'>Select Subject</option>"; 
$teacher_subjects16 = "<select name=\"teacher_subject_id16\" id=\"teacher_subject_id16\" >";
$teacher_subjects16 .= "<option value='0'>Select Subject</option>";
$row1 = $row;
while($row1 = $data->getRow())
{
	$teacher_subjects1 .= "<option value='$row1->subject_id' >$row1->subject_title</option>";
	$teacher_subjects2 .= "<option value='$row1->subject_id' >$row1->subject_title</option>";
        $teacher_subjects3 .= "<option value='$row1->subject_id' >$row1->subject_title</option>";
	$teacher_subjects4 .= "<option value='$row1->subject_id' >$row1->subject_title</option>";
        $teacher_subjects5 .= "<option value='$row1->subject_id' >$row1->subject_title</option>";
	$teacher_subjects6 .= "<option value='$row1->subject_id' >$row1->subject_title</option>";
        $teacher_subjects7 .= "<option value='$row1->subject_id' >$row1->subject_title</option>";
	$teacher_subjects8 .= "<option value='$row1->subject_id' >$row1->subject_title</option>";
        $teacher_subjects9 .= "<option value='$row1->subject_id' >$row1->subject_title</option>";
	$teacher_subjects10 .= "<option value='$row1->subject_id' >$row1->subject_title</option>";
        $teacher_subjects11 .= "<option value='$row1->subject_id' >$row1->subject_title</option>";
	$teacher_subjects12 .= "<option value='$row1->subject_id' >$row1->subject_title</option>";
        $teacher_subjects13 .= "<option value='$row1->subject_id' >$row1->subject_title</option>";
	$teacher_subjects14 .= "<option value='$row1->subject_id' >$row1->subject_title</option>";
        $teacher_subjects15 .= "<option value='$row1->subject_id' >$row1->subject_title</option>";
	$teacher_subjects16 .= "<option value='$row1->subject_id' >$row1->subject_title</option>";
        $subjects .= "<option value='$row1->subject_id' >$row1->subject_title</option>";
}
$teacher_subjects1 .= "</select>";
$teacher_subjects2 .= "</select>";
$teacher_subjects3 .= "</select>";
$teacher_subjects4 .= "</select>";
$teacher_subjects5 .= "</select>";
$teacher_subjects6 .= "</select>";
$teacher_subjects7 .= "</select>";
$teacher_subjects8 .= "</select>";
$teacher_subjects9 .= "</select>";
$teacher_subjects10 .= "</select>";
$teacher_subjects11 .= "</select>";
$teacher_subjects12 .= "</select>";
$teacher_subjects13 .= "</select>";
$teacher_subjects14 .= "</select>";
$teacher_subjects15 .= "</select>";
$teacher_subjects16 .= "</select>";
$subjects .= "</select>";

//$subjects .= "</select>";
$sql = "select * from schoollms_schema_userdata_school_year";
$data->execSQL($sql);
$year = date("Y");
//echo $year;
$teachertimetable_year_id = "<select name=\"teachertimetable_year_id\" id=\"teachertimetable_year_id\" >";
$teachertimetable_year_id .= "<option value='All' >All</option>";
$teacheryear1 = "<select name=\"year_id1\" id=\"year_id1\" >";
$teacheryear2 = "<select name=\"year_id2\" id=\"year_id2\" >";
$teacheryear3 = "<select name=\"year_id3\" id=\"year_id3\" >";
$teacheryear4 = "<select name=\"year_id4\" id=\"year_id4\" >";
$teacheryear5 = "<select name=\"year_id5\" id=\"year_id5\" >";
$teacheryear6 = "<select name=\"year_id6\" id=\"year_id6\" >";
$teacheryear7 = "<select name=\"year_id7\" id=\"year_id7\" >";
$teacheryear8 = "<select name=\"year_id8\" id=\"year_id8\" >";
$teacheryear9 = "<select name=\"year_id9\" id=\"year_id9\" >";
$teacheryear10 = "<select name=\"year_id10\" id=\"year_id10\" >";
$teacheryear11 = "<select name=\"year_id11\" id=\"year_id11\" >";
$teacheryear12 = "<select name=\"year_id12\" id=\"year_id12\" >";
$teacheryear13 = "<select name=\"year_id13\" id=\"year_id13\" >";
$teacheryear14 = "<select name=\"year_id14\" id=\"year_id14\" >";
$teacheryear15 = "<select name=\"year_id15\" id=\"year_id15\" >";
$teacheryear16 = "<select name=\"year_id16\" id=\"year_id16\" >";
$learneryear = "<select name=\"student_year_id\" id=\"student_year_id\" >";
$classyear = "<select name=\"class_year_id\" id=\"class_year_id\" >";
$ttyear = "<select name=\"tt_year_id\" id=\"tt_year_id\" >";
$yearselected = "";
while($row = $data->getRow())
{
	if($year == $row->year_label)
	{
		$yearselected = "selected";
	}
	else{
		$yearselected = "";
	}
	$teacheryear1 .= "<option value='$row->year_id' $yearselected>$row->year_label</option>";
        $teacheryear2 .= "<option value='$row->year_id' $yearselected>$row->year_label</option>";
        $teacheryear3 .= "<option value='$row->year_id' $yearselected>$row->year_label</option>";
        $teacheryear4 .= "<option value='$row->year_id' $yearselected>$row->year_label</option>";
        $teacheryear5 .= "<option value='$row->year_id' $yearselected>$row->year_label</option>";
        $teacheryear6 .= "<option value='$row->year_id' $yearselected>$row->year_label</option>";
        $teacheryear7 .= "<option value='$row->year_id' $yearselected>$row->year_label</option>";
        $teacheryear8 .= "<option value='$row->year_id' $yearselected>$row->year_label</option>";
	$teacheryear9 .= "<option value='$row->year_id' $yearselected>$row->year_label</option>";
        $teacheryear10 .= "<option value='$row->year_id' $yearselected>$row->year_label</option>";
        $teacheryear11 .= "<option value='$row->year_id' $yearselected>$row->year_label</option>";
        $teacheryear12 .= "<option value='$row->year_id' $yearselected>$row->year_label</option>";
        $teacheryear13 .= "<option value='$row->year_id' $yearselected>$row->year_label</option>";
        $teacheryear14 .= "<option value='$row->year_id' $yearselected>$row->year_label</option>";
        $teacheryear15 .= "<option value='$row->year_id' $yearselected>$row->year_label</option>";
        $teacheryear16 .= "<option value='$row->year_id' $yearselected>$row->year_label</option>";
        $learneryear .= "<option value='$row->year_id' $yearselected>$row->year_label</option>";
	$classyear .= "<option value='$row->year_id' $yearselected>$row->year_label</option>";
	$ttyear .= "<option value='$row->year_id' $yearselected>$row->year_label</option>";
	$teachertimetable_year_id .= "<option value='$row->year_id' $yearselected>$row->year_label</option>";
}

$classyear .= "</select>";
$teacheryear1 .= "</select>";
$teacheryear2 .= "</select>";
$teacheryear3 .= "</select>";
$teacheryear4 .= "</select>";
$teacheryear5 .= "</select>";
$teacheryear6 .= "</select>";
$teacheryear7 .= "</select>";
$teacheryear8 .= "</select>";
$teacheryear9 .= "</select>";
$teacheryear10 .= "</select>";
$teacheryear11 .= "</select>";
$teacheryear12 .= "</select>";
$teacheryear13 .= "</select>";
$teacheryear14 .= "</select>";
$teacheryear15 .= "</select>";
$teacheryear16 .= "</select>";
$learneryear .= "</select>";
$ttyear .= "</select>";
$teachertimetable_year_id .= "</select>";



?>
<!DOCTYPE html PUBLIC>
<!--
Author: W3layouts
Author URL: http://w3layouts.com
License: Creative Commons Attribution 3.0 Unported
License URL: http://creativecommons.org/licenses/by/3.0/
-->
<!DOCTYPE html>
<html>
<head>
 <link rel="shortcut icon" type="image/x-icon" href="images/schoollmslogo.ico" />
<title>SchoolLMS &COPY; 2016</title>
<!-- for-mobile-apps -->
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<meta name="keywords" content="School LMS" />
<!-- //for-mobile-apps -->

<link rel="stylesheet" type="text/css" href="themes/jquery-ui.css" />
<link rel="stylesheet" type="text/css" href="themes/default/easyui.css">
<link rel="stylesheet" type="text/css" href="themes/icon.css">

<link rel="stylesheet" href="css/font-awesome.min.css">		
<link rel="stylesheet" href="css/imagepopup.css">	
	
<link href="css/bootstrap.css" rel="stylesheet">
<link href='css/questrial.css' rel='stylesheet' type='text/css'>
<link href='css/italic.css' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="css/j-forms.css">
<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
<link rel="stylesheet" href="css/footer-distributed.css">
<link rel="stylesheet" href="css/jquery.contextmenu.css">

<script type="text/javascript" src="js/modernizr-2.6.2.min.js"></script>
<script src="js/jquery.1.11.1.min.js"></script>
<script src="js/jquery.contextmenu.js"></script>
<script type="text/javascript" src="js/bootstrap.js"></script>
<script src="js/responsive-tabs.js"></script>
<script type="text/javascript" src="js/jscode.js"></script>
<script src="js/highstock.js"></script>
<script src="js/highslide.js"></script>
<script src="js/modules/exporting.js"></script>
		<script type="text/javascript" src="js/jquery-ui.js"></script>
		<script src="js/jquery.easyui.min.js" type="text/javascript"></script>
		<script src="js/datagrid-filter.js" type="text/javascript"></script>
		<script type="text/javascript" src="js/jquery.searchabledropdown-1.0.8.min.js"></script>
		<script src="js/teachersetting.js" type="text/javascript"></script>	
		<script src="js/classvenuesettings.js" type="text/javascript"></script>
		<script src="js/studentsettings.js" type="text/javascript"></script>
		<script src="js/accesscontrol.js" type="text/javascript"></script>
		
		<script src="js/indeks.js" type="text/javascript"></script>
		
<style>
	.button {
		background-color: #4CAF50; /* Green */
		border: none;
		color: white;
		padding: 15px 32px;
		text-align: center;
		text-decoration: none;
		display: inline-block;
		font-size: 16px;
	}

	.header {
		background:#1E90FF;
		height:135px;
		width:100%;
		position:fixed;
		top:0;
		left:0; 						
	}
	.scroller {
		overflow:scroll;    
	}
	
	/* subject colors */
.s-1 {
	background-color: #AAC8E2;
}

.s-10 {
	background-color: #FFA500;
}

.s-11 {
	background-color: #00FFFF;
}

.s-12 {
	background-color: #E7D783;
}

.s-13 {
	background-color: #E99AE6;
}

.s-14 {
	background-color: #C4AFFF;
}

.s-15 {
	background-color: #91DEC5;
}

.s-16 {
	background-color: #CFE17F;
}

.s-17 {
	background-color: #E7BD83;
}

.s-18 {
	background-color: #FF00FF;
}

.s-19 {
	background-color: #AAC8E2;
}

.s-2 {
	background-color: #E7D783;
}

.s-20 {
	background-color: #FFA500;
}

.s-21 {
	background-color: #00FFFF;
}

.s-22 {
	background-color: #E7D783;
}

.s-23 {
	background-color: #E99AE6;
}

.s-24 {
	background-color: #C4AFFF;
}

.s-25 {
	background-color: #91DEC5;
}

.s-26 {
	background-color: #CFE17F;
}

.s-27 {
	background-color: #E7BD83;
}

.s-28 {
	background-color: #FF00FF;
}

.s-29 {
	background-color: #A5F09D;
}

.s-3 {
	background-color: #E99AE6;
}
.s-30
{
	background-color: #FFA500;
}
.s-31
{
	background-color: #00FFFF;
}
.s-32 {
	background-color: #E7D783;
}

.s-33 {
	background-color: #E99AE6;
}

.s-34 {
	background-color: #C4AFFF;
}

.s-35 {
	background-color: #91DEC5;
}

.s-36 {
	background-color: #CFE17F;
}

.s-37 {
	background-color: #E7BD83;
}

.s-38 {
	background-color: #FF00FF;
}

.s-39 {
	background-color: #A5F09D;
}
.s-4 {
	background-color: #C4AFFF;
}
.s-40
{
	background-color: #FFA500;
}
.s-41
{
	background-color: #00FFFF;
}
.s-42 {
	background-color: #E7D783;
}

.s-43 {
	background-color: #E99AE6;
}

.s-44 {
	background-color: #C4AFFF;
}

.s-45 {
	background-color: #91DEC5;
}

.s-46 {
	background-color: #CFE17F;
}

.s-47 {
	background-color: #E7BD83;
}

.s-48 {
	background-color: #FF00FF;
}

.s-49 {
	background-color: #A5F09D;
}
.s-5 {
	background-color: #91DEC5;
}
.s-50
{
	background-color: #FFA500;
}
.s-51
{
	background-color: #00FFFF;
}
.s-52 {
	background-color: #E7D783;
}

.s-53 {
	background-color: #E99AE6;
}

.s-54 {
	background-color: #C4AFFF;
}

.s-55 {
	background-color: #91DEC5;
}

.s-56 {
	background-color: #CFE17F;
}

.s-57 {
	background-color: #E7BD83;
}

.s-58 {
	background-color: #FF00FF;
}

.s-59 {
	background-color: #A5F09D;
}
.s-6 {
	background-color: #CFE17F;
}
.s-60
{
	background-color: #FFA500;
}
.s-61
{
	background-color: #00FFFF;
}
.s-62 {
	background-color: #E7D783;
}

.s-63 {
	background-color: #E99AE6;
}

.s-64 {
	background-color: #C4AFFF;
}

.s-65 {
	background-color: #91DEC5;
}

.s-66 {
	background-color: #CFE17F;
}

.s-67 {
	background-color: #E7BD83;
}

.s-68 {
	background-color: #FF00FF;
}

.s-69 {
	background-color: #A5F09D;
}
.s-7 {
	background-color: #E7BD83;
}
.s-70
{
	background-color: #FFA500;
}
.s-71
{
	background-color: #00FFFF;
}
.s-72 {
	background-color: #E7D783;
}

.s-73 {
	background-color: #E99AE6;
}

.s-74 {
	background-color: #C4AFFF;
}

.s-75 {
	background-color: #91DEC5;
}

.s-76 {
	background-color: #CFE17F;
}

.s-77 {
	background-color: #E7BD83;
}

.s-78 {
	background-color: #FF00FF;
}

.s-79 {
	background-color: #A5F09D;
}
.s-8 {
	background-color: #FFC5C2;
}
.s-80
{
	background-color: #FFA500;
}
.s-81
{
	background-color: #00FFFF;
}
.s-82 {
	background-color: #E7D783;
}

.s-83 {
	background-color: #E99AE6;
}

.s-84 {
	background-color: #C4AFFF;
}

.s-85 {
	background-color: #91DEC5;
}

.s-86 {
	background-color: #CFE17F;
}

.s-87 {
	background-color: #E7BD83;
}

.s-88 {
	background-color: #FF00FF;
}

.s-89 {
	background-color: #A5F09D;
}
.s-9 {
	background-color: #A5F09D;
}
.s-90
{
	background-color: #FFA500;
}
.s-91
{
	background-color: #00FFFF;
}
.s-92 {
	background-color: #E7D783;
}

.s-93 {
	background-color: #E99AE6;
}

.s-94 {
	background-color: #C4AFFF;
}

.s-95 {
	background-color: #91DEC5;
}

.s-96 {
	background-color: #CFE17F;
}

.s-97 {
	background-color: #E7BD83;
}

.s-98 {
	background-color: #FF00FF;
}

.s-99 {
	background-color: #A5F09D;
}


/* blank cells (upper left corner) */
.blank {
	background-color: white;
}


/* background color for lunch */
.lunch {
	color: #665;
	background-color: #f8eeee;
}


/* trash cell */
.redips-trash {
	color: white;
	background-color: #6386BD;
}


/* message line */
#message {
	color: white;
	background-color: #aaa;
	text-align: center;
	margin-top: 10px;
}


/* dark cells (first column and table header) */
.dark{
	color: #444;
	background-color: #e0e0e0;
}

.button_container{
	padding-top: 10px;
	text-align: right;
}

		/* "Save" button */
		.button_container input{
			background-color: #6A93D4;
			color: white; 
			border-width: 1px;
			width: 40px;
			padding: 0px;
		}

.tabs-header{
	position: fixed;
	z-index: 100;
}
.tabs-panels{
	padding-top: 30px;
}

@media only screen and (max-width: 768px){
	.navbar-fixed-bottom{
		position: static;
	}
}
	
</style>

<script>
	

	var contextMenuParams = "";
	var theItems = "";
	var theE;
	$(document).ready(function(e){
		//getTimetable(22);
		 theE = e;
			
		var user_type = getUrlParameter("user_type");		
		if(user_type == 4)
		{
			theItems = [
            {label:'Publish Notes',     icon:'icons/shopping-basket.png',             action:function() { callViewTimeTableSlot(contextMenuParams,'publishnotes' ) } },                
            {label:'Lesson Plan',     icon:'icons/shopping-basket.png',             action:function() { callViewTimeTableSlot(contextMenuParams,'lessonplan' ) } },
            {label:'Publish Lesson',     icon:'icons/book-open-list.png',              action:function() { callViewTimeTableSlot(contextMenuParams,'publishlesson' ) } },
            {label:'Class List', icon:'icons/receipt-text.png',                action:function() { callViewTimeTableSlot(contextMenuParams,'classlist' ) } },
            null, // divider
            {label:'Upload Test',         icon:'icons/application-monitor.png',         action:function() { callViewTimeTableSlot(contextMenuParams,'uploadtest' ) } },
            //{label:'Cheese',        icon:'icons/bin-metal.png',                   action:function() { alert('clicked 5') } },
            {label:'More Resources',         icon:'icons/magnifier-zoom-actual-equal.png', action:function() { alert('clicked 6') } },
            //null, // divider
            //{label:'Onwards',       icon:'icons/application-table.png',           action:function() { alert('clicked 7') } },
            //{label:'Flutters',      icon:'icons/cassette.png',                    action:function() { alert('clicked 8') } }
          ];
		}
		
		if(user_type == 2)
		{
			theItems = [
            {label:'Take Notes',     icon:'icons/shopping-basket.png',             action:function() { callViewTimeTableSlot(contextMenuParams,'takenotes' ) } },                
            {label:'Upload Study Notes',     icon:'icons/shopping-basket.png',             action:function() { callViewTimeTableSlot(contextMenuParams,'uploadstudynotes' ) } },
            {label:'Upload Assignment',     icon:'icons/book-open-list.png',              action:function() { callViewTimeTableSlot(contextMenuParams,'uploadassignment' ) } },
            /*{label:'Class List', icon:'icons/receipt-text.png',                action:function() { callViewTimeTableSlot(contextMenuParams,'classlist' ) } },
            null, // divider
            {label:'Upload Test',         icon:'icons/application-monitor.png',         action:function() { callViewTimeTableSlot(contextMenuParams,'uploadtest' ) } },
            {label:'Cheese',        icon:'icons/bin-metal.png',                   action:function() { alert('clicked 5') } },
            {label:'More Resources',         icon:'icons/magnifier-zoom-actual-equal.png', action:function() { alert('clicked 6') } },
            null, // divider
            {label:'Onwards',       icon:'icons/application-table.png',           action:function() { alert('clicked 7') } },
            {label:'Flutters',      icon:'icons/cassette.png',                    action:function() { alert('clicked 8') } }*/
          ];
		}
		
		if(user_type == 6)
		{
			theItems = [
            {label:'Upload Study Notes',     icon:'icons/shopping-basket.png',             action:function() { callViewTimeTableSlot(contextMenuParams,'uploadstudynotes' ) } },
            {label:'Upload Assignment',     icon:'icons/book-open-list.png',              action:function() { callViewTimeTableSlot(contextMenuParams,'uploadassignment' ) } },
            /*{label:'Class List', icon:'icons/receipt-text.png',                action:function() { callViewTimeTableSlot(contextMenuParams,'classlist' ) } },
            null, // divider
            {label:'Upload Test',         icon:'icons/application-monitor.png',         action:function() { callViewTimeTableSlot(contextMenuParams,'uploadtest' ) } },
            {label:'Cheese',        icon:'icons/bin-metal.png',                   action:function() { alert('clicked 5') } },
            {label:'More Resources',         icon:'icons/magnifier-zoom-actual-equal.png', action:function() { alert('clicked 6') } },
            null, // divider
            {label:'Onwards',       icon:'icons/application-table.png',           action:function() { alert('clicked 7') } },
            {label:'Flutters',      icon:'icons/cassette.png',                    action:function() { alert('clicked 8') } }*/
          ];
		}
			
		//alert(theItems);
		//alert(user_type);
		/*$(".thecontextmenu").contextPopup({
          items: theItems
        });*/
		
		/*$(".thecontextmenu").bind('contextmenu',function(e){
				e.preventDefault();
				$('#mm').menu('show', {
					left: e.pageX,
					top: e.pageY
				});
			});*/
		
		$(".thecontextmenu").click(function () {
			//alert("next");
			//next();
			param = "TTHS";
			//alert(param);
			
		});
		
		$( 'ul.nav.nav-tabs  a' ).click( function ( e ) {
			e.preventDefault();
			$( this ).tab( 'show' );
		  } );
		  
		  $( '.js-alert-test' ).click( function () {
            alert( 'Button Clicked: Event was maintained' );
          } );
          fakewaffle.responsiveTabs( [ 'xs', 'sm' ] );

	});
	
	function menuHandler(item)
	{
		//alert(item.name);
		callViewTimeTableSlot(contextMenuParams,item.name);
	}
</script>

</head>
<body style="background: #1E90FF;">

<div id="mm" class="easyui-menu" data-options="onClick:menuHandler" style="width:120px;">
<?php
        $timetabl_id = 0;
        if (isset($_GET['view_type'])){
            $q = "SELECT timetabl_id FROM schoollms_schema_userdata_school_timetable WHERE timetable_type_id = 1 AND timetable_type_item_id = $user_id";
            $data->execSQL($q);
            $row = $data->getRow();
            $timetabl_id = $row->timetabl_id;
        } elseif($user_type == 4) //teacher
	{
		?>
		<div data-options="name:'lessonplan'">Lesson Plan</div>
                <div data-options="name:'publishnotes'">Publish Notes</div>
                <div data-options="name:'createlesson'">Create Lesson</div>
		<div data-options="name:'publishlesson',iconCls:'icon-save'">Publish Lesson</div>
		<div data-options="name:'classlist',iconCls:'icon-print'">Class List</div>
                <div data-options="name:'blocktablets'">Block Tablets</div>
                <div data-options="name:'broadcastlesson'">Broadcast Lesson</div>
                <div data-options="name:'openchatroom'">Open Chatroom</div>
                <div data-options="name:'requestsupport'">Request Support</div>
                <div data-options="name:'lmstraining'">SPHSLMS Training</div>
		<!--<div class="menu-sep"></div>-->
		<!--<div data-options="name:'exit'">Exit</div>-->
		<?php
		
	} elseif($user_type == 2) //learner
	{
		?>
                <div data-options="name:'takenotes'">Take Notes</div>
		<div data-options="name:'uploadstudynotes'">Study Notes</div>
                <div data-options="name:'uploadhomework'">Homework</div>
		<div data-options="name:'uploadassignment'">Assignment</div>
                <div data-options="name:'uploadporttfolio'">Portfolio</div>
		<?php
	}
	
?>		
	</div>

<div class="header" style="z-index: 100"><center>
	<?php
	
            if (empty($school_id)){
		
	?>
		<img src="images/schoollmslogo.png" alt="SchoolLMS" />
		<?php
                    
            } else {
                echo "<img src='$school_emblem' alt='$school_name' />"; 
                    ?>
                    <!--<img src="images/schoollogo.png" alt="Sunward Park School LMS" />-->
                    <?php
            }
			?>
	</center><br/>
</div>

<div class="content">


<div id="content" style="margin-top: 115px">		
	
	<div id="tabs" class="easyui-tabs scroller" style="width:98%;height:550px;flex: 1; overflow: auto;margin-left:10px; ">
			
			
			<?php 
			
                        if($user_type == 0)
			{
				//echo "halo";
				require_once("install/install.php");
			}
                        
			if($user_type == 1)
			{
				include_once("pages/staff.php");
			}
//                        if (isset($_GET['view_type'])){
//                            include_once("pages/viewlearner.php");
//                        }else
                        if($user_type == 2)
			{
				include_once("pages/learner.php");
			}
			if($user_type == 3)
			{
				include_once("pages/parent.php");
			}
			if($user_type == 4)
			{
				include_once("pages/teacher.php");
			}
			if($user_type == 5)
			{
				include_once("pages/manage.php");
			}			
			
			if($user_type == 6)
			{
				//echo "halo";
				require("pages/support.php");
			}
                        
                        if($user_type == 7)
			{
				//echo "halo";
				require("pages/core_support.php");
			}
                        
                        if($user_type == 8)
			{
				//echo "halo";
				require("pages/principal.php");
			}
                        
                         if($user_type == 9)
			{
				//echo "halo";
				require("pages/mec.php");
			}
                        
                        if($user_type == 10)
			{
				//echo "halo";
				require("pages/discipline.php");
			}

                        if($user_type == 11)
			{
				//echo "halo";
				require("pages/view_teachers.php");
			}
			?>
			
		</div>
		
		<div id="dlg" class="easyui-dialog" style="width:500px;height:500px;padding:10px 20px"
			 closed="true" buttons="#dlg-buttons">
			 <div class="ftitle">Time Table Slot Information</div>
			<p id="slotinfo" name="slotinfo">
			<table width="100%">
					<tr><td>
						<a href="javascript:save()" class='button medium green' >
							Save</a>  
							</td>
							<td><a href="javascript:close()" class='button medium green'
								style="text-decoration: none">Cancel</a>
							</td>
						</tr>
					</table>	
			</p>
		</div>
		
		<div id="accesscontoldlg" class="easyui-dialog" style="width:500px;height:300px;padding:10px 20px"
			 closed="true" buttons="#dlg-buttons">
			 <div class="ftitle">Add User</div>
			<p id="slotinfo" name="slotinfo">
			<form id="frmAddUser" name="frmAddUser" >
			<table width="100%">
			<tr>
				<td>User Type</td><td>  
				<?php
					$roles = "<select name='type_id' id='type_id' class='form-control' >";
					$sql = "select * from schoollms_schema_userdata_user_type";
					$roles .= "<option value='0'>Select Role</option>";			
					$data->execSQL($sql);
					while($row = $data->getRow())
					{
						$roles .= "<option value='$row->type_id'>$row->type_title</option>";			
					}
					$roles .= "</select>";
					echo  $roles;
				?>				
				</td>
			</tr>
			<tr>
				<td>Email Address</td>
				<td><input type="text" name="email" id="email"  />
			</tr>
			<tr>
				<td>First Name</td>
				<td><input type="text" name="name" id="name"  />
			</tr>
			<tr>
				<td>Surname</td>
				<td><input type="text" name="surname" id="surname"  />
			</tr>
			<tr id="graderow" name="graderow">
				<td>Grade</td>
				<td><select name='add_student_grade' id='add_student_grade'> 
                    </select>
				</td>
			</tr>	
			<tr id="classrow" name="classrow">
                <td>
                    Current Class
                </td>
                <td align="left">
                    <select name="add_learner_class" id="add_learner_class">
                    </select>
                </td>
            </tr>			
			<tr><td>
				<a href="javascript:" class='button medium green' onclick="saveAddUser()"
				style="text-decoration: none" >
					Save</a>  
					</td>
					<td><a href="javascript:" class='button medium green' onClick="closeAddUser()";
						style="text-decoration: none">Cancel</a>
					</td>
				</tr>
			</table>
			</form>			
			</p>
		</div>
	<?php  
		if($user_type != 4 and $user_type != 2)
		{
	?>
	 
	
	<?php
		}
		
		if($user_type == 6)
		{
	?>
	
		<div id="toolbar">
			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newLearner()">New Learner</a>
		</div>
		
		<div id="teacher_toolbar">
			<a href="javascript:void(0)" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="newTeahcer()">New Teacher</a>
		</div>	
		
	<?php		
		}
	?>
	
	
	
	
	 <div id="newLearnerDlg" class="easyui-dialog" style="width:520px;height:550px;padding:10px 20px"
            closed="true" buttons="#dlg-buttons">
        <div class="ftitle"><strong>Learner Information</strong></div>
         <form runat="server" id="frmSaveLearner" name="frmSaveLearner"  >
        <table style="width: 100%" cellspacing=2 cellpadding=2 >   
			<tr>
				<td align="left" colspan=2>
					<input type="hidden" id="user_id" name="user_id" />
				</td>
			</tr>	
            <tr>
                <td style="width: 40%">
                     Learner ID
                </td>
                <td align="left">
                    <input type="text" id="learner_id" name="learner_id"  placeholder="Learner ID" />
                </td>
            </tr>
            <tr>
                <td>
                   Name  
                </td>
                <td>
                    <input type="text" id="name" name="name"  placeholder="Learner Name" />
                </td>
            </tr>
            <tr>
                <td>
                    Surname 
                </td>
                <td>
                    <input type="text" id="surname" name="surname" placeholder="Learner Surname"/>
                </td>
            </tr>
<!--            <tr>
                <td>
                    Potential Score
                </td>
                <td>
                    <input type="text" id="potential_score" name="potential_score"  placeholder="Potential Score" />
                </td>
            </tr>
            <tr>
                <td>
                   Learner Average
                </td>
                <td>
                    <input type="text" id="learner_average" name="learner_average"  placeholder="Learner Average" />
                </td>
            </tr>-->
<!--            <tr>
                <td>
                    Subject Choice
                </td>
                <td align="left">
                    <select name="subject_choice" id="subject_choice">
                        <option value="1">Grade Choice</option>
                        <option value="2">Learner Choice</option>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    Current Grade
                </td>
                <td align="left">
                    <select name="current_grade" id="current_grade">
                    </select>
                </td>
            </tr>
			 <tr>
                <td>
                    Current Class
                </td>
                <td align="left">
                    <select name="current_class" id="current_class">
                    </select>
                </td>
            </tr>
			 <tr>
                <td>
                    Next Grade
                </td>
                <td align="left">
                    <select name="next_grade" id="next_grade">
                    </select>
                </td>
            </tr>
			 <tr>
                <td>
                    Next Class
                </td>
                <td align="left">
                    <select name="next_class" id="next_class">
                    </select>
                </td>
            </tr>-->
			<tr>
                <td>
                    Number of Registered Parents/Guardians
                </td>
                <td align="left">
                    <select name="number_of_parents" id="number_of_parents">
						<option value=1>1</option>
						<option value=2>2</option>
                    </select>
                </td>
            </tr>
			
			<tr id="rw1parent1" name="rw1parent1">
                <td>
                    Parent 1 Name 
                </td>
                <td>
                    <input type="text" id="parent_name_1" name="parent_name_1"  placeholder="Parent Name" />
                </td>
            </tr>
			<tr id="rw2parent1" name="rw2parent1" >
                <td>
                    Parent/Guardian 1 Surname
                </td>
                <td>
                    <input type="text" id="parent_surname_1" name="parent_surname_1"  placeholder="Parent Surname" />
                </td>
            </tr>
			<tr id="rw3parent1" name="rw3parent1">
                <td>
                    Parent/Guardian 1 ID Number
                </td>
                <td>
                    <input type="text" id="parent_id_1" name="parent_id_1"  placeholder="Parent ID Number" />
                </td>
            </tr>
			
			<tr id="rw1parent2" name="rw1parent2" style="visibility:false" >
                <td>
                    Parent 2 Name 
                </td>
                <td>
                    <input type="text" id="parent_name_2" name="parent_name_2"  placeholder="Parent Name" />
                </td>
            </tr>
			<tr id="rw2parent2" name="rw2parent2" style="visibility:false" >
                <td>
                    Parent 2 Surname
                </td>
                <td>
                    <input type="text" id="parent_surname_2" name="parent_surname_2"  placeholder="Parent Surname" />
                </td>
            </tr>
			<tr id="rw3parent2" name="rw3parent2" style="visibility:false">
                <td>
                    Parent 2 ID Number
                </td>
                <td>
                    <input type="text" id="parent_id_2" name="parent_id_2"  placeholder="Parent ID Number" />
                </td>
            </tr>
        </table>
        </form>
		<table width="80%">
		<tr><td>
				<button id="btnSaveLearner" name="btnSaveLearner" onClick="SaveNewLearner()">Save</button>
					</td>
					<td>
						<button id="btnSaveLearner" name="btnSaveLearner" onClick="$('#newLearnerDlg').dialog('close');">Cancel</button>
					</td>
				</tr>
		</table>
    </div>	
	
	
	
</div>

		<div class="navbar navbar-default navbar-custom navbar-fixed-bottom" style="margin-bottom: 0px; color: #ffffff; background-color: #1E90FF;">
		
		<div class="container">
			<div class="col-sm-2">
				<p>&copy SchoolLMS 2016</p>
			</div>


			<div class="col-sm-5">
				<p style="align-right: true">Powered by Ekasi IT & Sunward Park High School</p>
			</div>	
			<div class="col-sm-3">
				<p>Follow Us On Social Media</p>
			</div>
			<div class="col-sm-2" >
                   
                   <p style="padding-left: 0px; margin-left: 0;">				   
                    <a href="https://www.facebook.com/schoollms/?fref=ts" target="_blank"><i class="fa fa-facebook-square fa-2x"  style="color: white"></i></a>  
                    <a href="https://twitter.com/school_lms" target="_blank"><i class="fa fa-twitter-square fa-2x"  style="color: white"></i></a>
					<!-- Trigger the Modal -->
					<img id="myImg" src="images/aboutus.png" alt="About Us" >
                                        <br>
                                        

					<!-- The Modal -->
					<div id="myModal" class="modal">

						<!-- The Close Button -->
						<span class="close" onclick="document.getElementById('myModal').style.display='none'">&times;</span>

						<!-- Modal Content (The Image) -->
						<img class="modal-content" id="img01">

						<!-- Modal Caption (Image Text) -->
						<div id="caption"></div>
					</div>
                   
					<script>
						// Get the modal
						var modal = document.getElementById('myModal');

						// Get the image and insert it inside the modal - use its "alt" text as a caption
						var img = document.getElementById('myImg');
						var modalImg = document.getElementById("img01");
						var captionText = document.getElementById("caption");
						img.onclick = function(){
						modal.style.display = "block";
						modalImg.src = "images/AboutSchoolLMS.jpg";
						modalImg.alt = this.alt;
						captionText.innerHTML = this.alt;
						}

						// Get the <span> element that closes the modal
						var span = document.getElementsByClassName("close")[0];

						// When the user clicks on <span> (x), close the modal				
						span.onclick = function() { 
						modal.style.display = "none";
						}
					</script>
					
                   </p>
                </div>
			</div>
		</div>
   
		
</div>
    
 
		<!-- Scripts -->
		
</body>
</html>
