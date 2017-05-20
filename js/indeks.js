
var subjectChoice = [
	{selectionid:'1',value:'Grade Choice'},
	{selectionid:'2',value:'Learner Choice'}
];		
 var subjects;			
$(document).ready(function () {

	//alert("Ek se ");
	//return;
	var user_type = getUrlParameter("user_type");
	if(user_type != 6)
	{
		$('#mydiv').find('input, textarea, button, select').attr('disabled','disabled');
	}
	
	 //viewTimeTable();
	
	var school_id = getUrlParameter("school_id");
	if(school_id != null && school_id != undefined)
	{
		//alert(school_id);
		viewTimeTable();
		getStep1Data();
		getSchoolData(school_id);
		if(user_type == 6)
		{
			getSubjectData();
			addStudentsSetup();
			getTeacherSettings();
			setStudentSettings();
			SettingsPerRotation();
			//alert("we are here");			
			previous();
			previous();
		}
		
	}

	$('.numbersOnly').keyup(function () { 
		this.value = this.value.replace(/[^0-9\.]/g,'');
	});
	
	for(i =1;i<=3;i++)
	{
		$("#rw"+i+"parent2").hide();					
	}
	
	$("#number_of_parents").on('change', function() {
		
		for(z=1;z<=2;z++)
		{
			for(i =1;i<=3;i++)
			{
				$("#rw"+i+"parent"+z).hide();					
			}
		}
		
		for(z=1;z<=$("#number_of_parents").val();z++)
		{
			for(i =1;i<=3;i++)
			{
				$("#rw"+i+"parent"+z).show();					
			}
		}
	});
	
	//alert(subjects);
	getTimetable(0);
	
	
	//$('#students').datagrid({singleSelect:false,remoteFilter:true,enableFilter:true});


var page = getUrlParameter("page");
$("#" + page).addClass("current");

$(".next").click(function () {
	//alert("next");
	next();
});

$(".previous").click(function () {
	//alert("previous");
	previous();
});

$("#student_grade").on('change', function() {
	setStudentSettings();
});

$("#teachertimetable_grade_id").on('change', function() {
	//alert($("#teachertimetable_grade_id").val());
	
	viewTimeTable($("#teachertimetable_grade_id").val());
});		

for(i =2;i <5;i++)
{					
	$("#row_break_"+i).hide();					
}


$("#number_of_breaks").on('change', function() {
	//alert($("#number_of_breaks").val());
	
	for(i =1;i <5;i++)
	{				
		$("#row_break_"+i).hide();					
	}
	
	for(i =0;i<$("#number_of_breaks").val();i++)
	{
		$("#row_break_"+(i+1)).show();	
	}
	
	
	
	/*if($("#number_of_breaks").val() == "1")
	{
		$("#break_type").val("first break");
		$("#break_type").prop("disabled", true);
	}
	else{
		$("#break_type").prop("disabled", false);
	}*/
});


for(i = 2;i <= 16;i++)
{					
	$("#row_subject_"+i).hide();					
}


$("#number_of_subjects").on('change', function() {
	//alert($("#number_of_breaks").val());
	
	for(i =1;i <=16;i++)
	{				
		$("#row_subject_"+i).hide();					
	}
	
	for(i =1;i<=$("#number_of_subjects").val();i++)
	{
		$("#row_subject_"+i).show();	
	}
	
	
	
	/*if($("#number_of_breaks").val() == "1")
	{
		$("#break_type").val("first break");
		$("#break_type").prop("disabled", true);
	}
	else{
		$("#break_type").prop("disabled", false);
	}*/
});

$("#subject_id").on('change', function() {			
	//alert($("#subjects").val());
	var school_id = getUrlParameter("school_id");
	var setting = getJasonData("school_id="+school_id+"&action=GETSUBJECTSETTINGS&subject_id="+$("#subject_id").val());	
	//alert(setting);
	setting =  jQuery.parseJSON(setting);
	
	//alert(setting);
	if (setting.length == 0) {
		alert("No Settings found for the selected subject");
		return;
	}
	 gradeSubjectSetting(setting);
	
});

$("#timetable_id").on('change', function() {
	//alert($("#timetable_id").val());
	getTimetable($("#timetable_id").val());
	getClassList($("#timetable_id").val());
});

$("#teacher_list").on('change', function() {
	//alert($("#teacher_list").val());
	var timetable_id = getTimetableID($("#teacher_list").val());
	//alert("We are here " + timetable_id);
	getTimetable(timetable_id);
	
	var class_id = $("#timetable_id").val();
	var classes = getJasonData("action=GETCLASSLIST&class_id="+class_id+"&year_id="+$("#teachertimetable_year_id").val());
	classes = jQuery.parseJSON(classes);
	//alert(classes);
	$("#class_list").html("");
	$('#class_list').append(
		$('<option></option>').val(0).html("Select Learner To View Time Table")
	); 
	$.each(classes, function(i, item) {
		var name = item.access_id + " " + item.name + " " + item.surname;
		$('#class_list').append(
			$('<option></option>').val(item.user_id).html(name)
		); 
	});
});


$("#class_list").on('change', function() {
	var timetable_id = getTimetableID($("#class_list").val());
	//alert("We are here " + timetable_id);
	getTimetable(timetable_id);
	//getClassList($("#timetable_id").val());
	
	var class_id = $("#timetable_id").val();
	var teachers = getJasonData("action=GETCLASSTEACHER&class_id="+class_id);
	teachers = jQuery.parseJSON(teachers);
	//alert(teachers);
	$("#teacher_list").html("");
	$('#teacher_list').append(
		$('<option></option>').val(0).html("Select Teacher To View Time Table")
	); 
	$.each(teachers, function(i, item) {
		//var name = item.access_id + " " + item.name + " " + item.surname;
		var name = item.name + " " + item.surname;
		$('#teacher_list').append(
			$('<option></option>').val(item.user_id).html(name)
		); 
	});
});

var theUrl = "getpages.php?page=school";


  $.ajax({  
	type: "GET",  
	url: theUrl,  
	data: "",
	success: function(data) {  
	  $("#capturetable").html(data)
	}  
  }); 
 

$("#btnAddSubject").click(function(){
	alert("add subject");	
	var table = "<table width='100%'><tr>Subject Name<td></td><td><input type='text' name='ttSubject' id='ttSubject'></td></tr>";
	table = table + "<tr><td colspan=2><input type='button' name='btnSaveSubject' id='btnSaveSubject' value='Save Subject' />&nbsp;&nbsp; <input type='button' name='btnCancelSaveSubject' id='btnCancelSaveSubject' value='Cancel' /> </table>";
	
	$('#slotinfo').html(table);
	$("#teachers").html("");
	$("#thetitle").html("");
	
	$("#btnCancelSaveSubject").click(function(){
		$('#dlg').dialog('close');
	});
	
	$("#btnSaveSubject").click(function(){
		//alert($("#ttSubject").val());
		if($.trim($("#ttSubject").val()) != "")
		{
			var result = getJasonData("action=ADDSUBJECT&subjectname="+$("#ttSubject").val());
			alert(result);
			result = getJasonData("action=getsubjects");
			result = jQuery.parseJSON(result);
			$('#subject_id').html("");
			$.each(result, function(i, item) {
				$('#subject_id').append(
					$('<option></option>').val(item.subject_id).html(item.subject_title)
				); 
			});
			
			
			$('#dlg').dialog('close');
		}	
		else{
			alert("Please enyer subject name");
		}	
		
	});

	$('#dlg').dialog('open').dialog('center').dialog('setTitle', 'Add Subject');
});




 
/*("#class_list").searchable({
	maxListSize: 100,
	maxMultiMatch: 100,
	latency: $("#latency").val(),
	exactMatch: false,
	wildcards: true,
	ignoreCase: true
});*/
 

});


