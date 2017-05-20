var whorotates= "";
var subjects = [];
var grades = [];
var classes = [];

/*8*******

START FROM PHP FILE

*************/

//TABLE SCROLLING

fnAdjustTable = function(){

  var colCount = $('#firstTr>td').length; //get total number of column

  var m = 0;
  var n = 0;
  var brow = 'mozilla';
  
  jQuery.each(jQuery.browser, function(i, val) {
    if(val == true){
      brow = i.toString();
    }
  });
  
  $('.tableHeader').each(function(i){
    if (m < colCount){

      if (brow == 'mozilla'){
        $('#firstTd').css("width",$('.tableFirstCol').innerWidth());//for adjusting first td
        $(this).css('width',$('#table_div td:eq('+m+')').innerWidth());//for assigning width to table Header div
      }
      else if (brow == 'msie'){
        $('#firstTd').css("width",$('.tableFirstCol').width());
        $(this).css('width',$('#table_div td:eq('+m+')').width()-2);//In IE there is difference of 2 px
      }
      else if (brow == 'safari'){
        $('#firstTd').css("width",$('.tableFirstCol').width());
        $(this).css('width',$('#table_div td:eq('+m+')').width());
      }
      else {
        $('#firstTd').css("width",$('.tableFirstCol').width());
        $(this).css('width',$('#table_div td:eq('+m+')').innerWidth());
      }
    }
    m++;
  });

  $('.tableFirstCol').each(function(i){
    if(brow == 'mozilla'){
      $(this).css('height',$('#table_div td:eq('+colCount*n+')').outerHeight());//for providing height using scrollable table column height
    }
    else if(brow == 'msie'){
      $(this).css('height',$('#table_div td:eq('+colCount*n+')').innerHeight()-2);
    }
    else {
      $(this).css('height',$('#table_div td:eq('+colCount*n+')').height());
    }
    n++;
  });

};

//function to support scrolling of title and first column
fnScroll = function(){
  $('#divHeader').scrollLeft($('#table_div').scrollLeft());
  $('#firstcol').scrollTop($('#table_div').scrollTop());
};
//END OF TABLE SCROLLING

function getStep1Data()
{
	//alert($("#schools").val());
	
	var school_id = getUrlParameter("school_id");
	//alert("School ID : " + school_id);
	if(school_id == undefined || school_id == "undefined")
	{
		school_id = $("#schools").val();
	}
	
	if($("#schools").val()  == "0")
	{
		alert('Please Select School')
	}
	else{
		if(school_id == undefined )
		{
			school_id = $("#schools").val();
		}
		//alert(school_id);
		getSchoolData(school_id);
		//alert("Step 1");
		//next();
	}
}
		
 function processSubmit()
 {
	var theUrl = "getpages.php?"+$("#frmSchoolInfo").serialize();
	$.ajax({  
		type: "GET",  
		url: theUrl,  
		data: "",
		success: function(data) {  
		  $("#capturetable").html(data)
		}  
	  }); 
	//alert(url);
	//alert('Submitting');
 }
 
function save()
{
	var user_type = getUrlParameter("user_type");
	if(user_type != 4)
	{
		//alert($("#subjectselect").val());
		//alert($("#venueSelect").val());
		//alert($('#periodColumnVal').html());
		var day = $('#dayColumnVal').html().split("</b>");
		day = day[0];
		day = day.replace("<b>","");
		//alert($('#dayColumnVal').html());
		var teacher = $("teacherselect").val();
		//var teacher_id=$("teacherselect").val();
		if(teacher == null || teacher == undefined)
		{
			teacher = $("#teacherColumnVal").html();				
		}
		
		
		var school_id = getUrlParameter("school_id");
		if(school_id == undefined || school_id == "undefined")
		{
			school_id = $("#schools").val();
		}		
		
		var params = "school_id="+school_id+"&year_id="+$("#teachertimetable_year_id").val()+"&grade_id="+$("#teachertimetable_grade_id").val();
		params = params + "&class_id="+$("#timetable_id").val() + "&subject_id="+$("#subjectselect").val()+"&classroom="+$("#venueSelect").val();
		params = params +"&period_time="+$('#periodColumnVal').html()+"&teacher="+teacher+"&teacher_id="+teacher_id+"&day="+day+"&save_type=admin_timetable_slot";
		//alert(params);
		sendDataByGet(param,"timetable_save.php");
	}
	
	alert("Time Table Slot Information Saved.");
				
	$('#dlg').dialog('close');
}

function close()
{
	//alert("Time Table Slot Information Saved.");
	$('#dlg').dialog('close');
}



function newViewTimeTableSlot(parameters,id)
{
	//alert(id);
    contextMenuParams = parameters;
	//var parentOffset = $("#"+id).offset(); 
    var parentOffset = $(id).offset(); 
   //or $(this).offset(); if you really just want the current element's offset
   var relX = theE.pageX - parentOffset.left;
   var relY = theE.pageY - parentOffset.top;
	$('#mm').menu('show', {
					left: parentOffset.left,
					top: parentOffset.top
				});
	//alert(contextMenuParams);
	//viewTimeTableSlot(params[0],params[1],params[2],params[3]);
}

function callViewTimeTableSlot(parameters, action)
{
    
	var params = parameters.split("::");
	var count_params = params.length;
        //alert("COUNT PARAMS "+count_params);
	var user_type = getUrlParameter("user_type");			
	if(user_type == undefined)
	{
		user_type = times.split("~");
		user_type = user_type[4];
	}
	//alert(params + " -- " +action);
        if(user_type == 2)
	{
            switch(action)
		{
                        case "takenotes":
			{
				params[1] = params[1].replace('<b>','');
				params[1] = params[1].replace('</b>','');
				var school_id = getSchoolID();
				var year_id = 0;
				//alert(times);
				if($("#teachertimetable_year_id").val() == undefined)
				{
					year_id = getYearID();
				}else{
					year_id = $("#teachertimetable_year_id").val();
				}
				
				var subject = params[1];
				subject.replace("<b>");
				subject.replace("</b>");
				//alert(year_id);
                                
                                if (count_params === 4){
                                    window.open("pages/notes.php?class="+params[2]+"&subject="+subject+"&timeslot="+params[3]+"&year_id="+year_id+"&school_id="+school_id,'1453910749489','width=700,height=500,toolbar=0,menubar=0,location=0,status=1,scrollbars=1,resizable=1,left=0,top=0');					
                                } else {
                                    window.open("pages/notes.php?class="+params[2]+"&subject="+subject+"&subject_class="+params[4]+"&timeslot="+params[3]+"&year_id="+year_id+"&school_id="+school_id,'1453910749489','width=700,height=500,toolbar=0,menubar=0,location=0,status=1,scrollbars=1,resizable=1,left=0,top=0');					
                                }
                                break;
			}
                }
        }
        
	if(user_type == 4)
	{
		switch(action)
		{
                        case "publishnotes":
			{
				alert("Push Notes");
				break;
			}
                        
			case "lessonplan":
			{
				alert("Show Lesson Plan");
				break;
			}
			
			case "publishlesson":
			{
				//alert(parameters);
				var table = "<table><tr><td><br/><br/>:LESSONS</td></tr><tr><td><br/><br/><br/><button id='btnSavePopup'  name='btnSavePopup'>Publish Lesson</button></td></tr></table>";
				var _class = params[2];
                                var subject_class = "";
                                if (_class.indexOf('X') > -1){
                                    subject_class = params[4];
                                }
                                
				var subject = params[1];
				//var _class = params[2];
				//var subject = subject;
				var timeslot = params[3];	
				//alert(timeslot);
				subject = subject.replace("<b>","");
				subject = subject.replace("</b>","");
				//alert(_class);
				//_class = _class.replace(","," ");
				_class = _class.split(" ");
				
				var grade = _class[1].toString();
				grade = grade.trim();
				grade = grade.substring(0,grade.length-1);
				//alert(grade);
				//alert(subject);
				var param ="subject_name="+subject+"&grade_no="+grade+"&term_no=1";
				//alert(param);
				$("#data").val(param);
                                var result = getJasonData("action=GETLESSONS&"+param);
				//var result = sendDataByGet(param,"timetable_subject_lessons_settings.php");
				//alert(result);
				result =  jQuery.parseJSON(result)	;
				$('#lesson').html("");
				var lessons = "<select id='lesson' name='lesson'>";
				$.each(result, function(i, item) {
					lessons = lessons + "<option value='"+result[i].title+"&lessonurl="+result[i].alias+"' >"+result[i].title+"</option>";
					//lessons = lessons + "<option value='2' >"+result[i].title+"</option>";
				});
				lessons = lessons +"</select>";
				
				table = table.replace(":LESSONS",lessons);
				$('#slotinfo').html(table);
				$("#teachers").html("");
				
				$("#btnSavePopup").click(function () {
					//alert("we are here");
					var year_id = getUrlParameterPlain("year_id");
					var school_id = getUrlParameterPlain("school_id");
					var user_id = getUrlParameterPlain("user_id");
					//alert(year_id + " " + school_id);
							
					//alert(timeslot);
					timeslot = timeslot.split('<br>');
					var thetime = timeslot[0].split('~');
					//alert(timeslot[1]);
					var day = thetime[1];
					day = day.replace("<b>","");
					day = day.replace("</b>","");
					//_class = _class.replace(","," ");
                                        
                                        
					var params = "save_type=publish_lesson&subject="+subject+"&class="+_class+"&subject_class="+subject_class+"&date="+timeslot[2]+"&time="+thetime[0]+"&lesson="+$("#lesson").val()+"&day="+day;
					params = params+ "&school_id="+school_id+"&year_id="+year_id+"&week_day="+timeslot[1]+"&user_id="+user_id;
					//alert(params);
                                        var lesson_published = getJasonData("action=PUBLISHLESSON&"+params);
		//alert(classlist);
                                        lesson_published =  jQuery.parseJSON(lesson_published);
                                        
                                        if (lesson_published[0]){
    //					sendDataByGet(params,"timetable_save.php")
                                            //alert(sendDataByGet(params,"timetable_save.php"));
                                            //alert(params);
                                            var teacher_id = getUrlParameter("user_id");
                                            var timetable_id = getTimetableID(teacher_id);
                                            //alert("We are here " + timetable_id);
                                            getTimetable(timetable_id);

                                            $('.thecontextmenu').contextPopup({
                                              items: theItems
                                            });

                                            alert("Lesson Published");
                                            $('#dlg').dialog('close');
                                        } else {
                                            alert(lesson_published[1]);
                                        }
					/*var daddy = window.self;
					daddy.opener = window.self;
					daddy.close();	*/
				});
				
				$('#dlg').dialog('open').dialog('center').dialog('setTitle', 'Publish Lesson');
				//alert("Publish Lesson");
				break;
			}
			
			case "classlist":
			{
				//alert("Show Class List");
				params[1] = params[1].replace('<b>','');
				params[1] = params[1].replace('</b>','');
				var school_id = getSchoolID();
				var year_id = 0;
				//alert(times);
				if($("#teachertimetable_year_id").val() == undefined)
				{
					year_id = getYearID();
				}else{
					year_id = $("#teachertimetable_year_id").val();
				}
				
				var subject = params[1];
				subject.replace("<b>");
				subject.replace("</b>");
				//alert(year_id);
                                
                                if (count_params === 4){
                                    window.open("classlist.php?class="+params[2]+"&subject="+subject+"&timeslot="+params[3]+"&year_id="+year_id+"&school_id="+school_id,'1453910749489','width=700,height=500,toolbar=0,menubar=0,location=0,status=1,scrollbars=1,resizable=1,left=0,top=0');					
                                } else {
                                    window.open("classlist.php?class="+params[2]+"&subject="+subject+"&subject_class="+params[4]+"&timeslot="+params[3]+"&year_id="+year_id+"&school_id="+school_id,'1453910749489','width=700,height=500,toolbar=0,menubar=0,location=0,status=1,scrollbars=1,resizable=1,left=0,top=0');					
                                }
                                break;
			}
			
			case "uploadtest":
			{
				alert("Upload Test");
				break;
			}
                        
                        case "openchatroom":
			{
				window.open("https://appear.in/open_chatroom");
				break;
			}
                        
                        case "requestsupport":
			{
				
				break;
			}
                        
                        case "lmstraining":
                        {
                            var year_id = getUrlParameterPlain("year_id");
                            var school_id = getUrlParameterPlain("school_id");
                            var user_id = getUrlParameterPlain("user_id");
                            var param ="user_id="+user_id+"&school_id="+school_id+"&year_id="+year_id;
                            
                            var training_link = getJasonData("action=GETTRAININGLINK&"+params);
                            window.open(training_link);
                            break;
                        }
                       
		}
	}
	
	if(user_type == 6)
	{
		viewTimeTableSlot(params[0],params[1],params[2],params[3]);
	}
}
	  
function viewTimeTableSlot(information,subject,slotClass,times) {
	var user_type = getUrlParameter("user_type");
	var school_id = getSchoolID();
        var year_id = 0;
        //alert(times);
        if($("#teachertimetable_year_id").val() == undefined)
        {
                year_id = getYearID();
        }
        else{
                year_id = $("#teachertimetable_year_id").val();
        }
        if(user_type == undefined)
	{
		user_type = times.split("~");
		user_type = user_type[4];
	}
	//alert(times);
	//alert(user_type);
	if(user_type == 4)
	{
		//alert(times);
		subject = subject.replace('<b>','');
		subject = subject.replace('</b>','');
		
		//alert(year_id);
		window.open("classlist.php?class="+slotClass+"&subject="+subject+"&timeslot="+times+"&year_id="+year_id+"&school_id="+school_id,'1453910749489','width=700,height=500,toolbar=0,menubar=0,location=0,status=1,scrollbars=1,resizable=1,left=0,top=0');
		return;
		
		var classlist = getJasonData("action=GETSLOTCLASSLIST&class="+slotClass);
		//alert(classlist);
		classlist =  jQuery.parseJSON(classlist)	;
		var table = "<table border=1 id=tblpopup name=tblpopup width='100%'> <tr><td>ID Number</td><td>Student Name</td><td>Image</td><td>Present</td></tr>";
		
		$.each(classlist, function(i, item) {
			table = table+ "<tr><td>"+item.access_id+"</td><td>"+item.name + "  "+item.surname+"</td><td>< img src='process.php?action=GETIMAGE&user_id=2'/></td><td><input type='checkbox' name='"+item.access_id+"_present' value='present' /></td></tr>";
		});
		table = table+"</table>";
		//information = table
		$('#slotinfo').html(table);
	}
	
	if(user_type == 6)
	{	
		//alert(user_type);
		var subselect = "";
		subject = subject.replace('<b>','');
		subject = subject.replace('</b>','');
		
		if($.trim(subject) != "")
		{
			var subjects = getJasonData("action=getsubjects");
			
			subjects =  jQuery.parseJSON(subjects)	;
			subselect = "<select id='subjectselect' name='subjectselect' onchange='getSubjectTeacher(this.value, "+school_id+","+year_id+")'>";
			$.each(subjects, function(i, item) {
				//alert(item.subject_id);
				if(item.subject_title == subject)
				{
					subselect = subselect + "<option value='"+item.subject_id+"' selected>"+item.subject_title+"</option>";
				}
				else{
					subselect = subselect + "<option value='"+item.subject_id+"'>"+item.subject_title+"</option>";
				}
			});
			subselect = subselect +"</select>";				
		}
		//alert(subselect);
		
		var info = information.split("<br>");
		var teacher = info[1];
		
		var teacherSelect = "";
		teacherSelect = "<select id='teacherlist' name='teacherlist' onchange='getTeacherVenue(this.value,"+school_id+","+year_id+")'>";
		var teachers = getJasonData("action=GETCLASSTEACHER&class_id="+$("#timetable_id").val());
		teachers = jQuery.parseJSON(teachers);
		
		//$("#teacherlist").html("");
		$.each(teachers, function(i, item) {
			//var name = item.access_id + " " + item.name + " " + item.surname;
			var name = item.name + " " + item.surname;
			if(name == teacher)
			{
				teacherSelect = teacherSelect + "<option value='"+item.user_id+"' selected>"+name+"</option>";						
			}
			else{
				teacherSelect = teacherSelect + "<option value='"+item.user_id+"'>"+name+"</option>";
			
			}					
		});
		teacherSelect = teacherSelect + "</select>";
		var mytimes = times.split("~");
		var periodtimes = mytimes[0].split("-");
		mytimes[1] = mytimes[1].replace('<b>','');
		mytimes[1] = mytimes[1].replace('</b>','');
		var theday = mytimes[1].split("<br>");
		var selectedPeriod = mytimes[2];
		//alert("days...");
		//alert("selectedPeriod = " + selectedPeriod)
		var days = getJasonData("action=TIMETABLEDAYS");
		days = jQuery.parseJSON(days);
		//alert(days);
		var myDays = "<select id='day' name='day' disabled='true'>";
		//alert(mytimes[2]);
		$.each(days, function(i, item) {
			//alert(days[i].day_label);
			if(days[i].day_label.trim() == theday[0].trim())
			{
				myDays = myDays + "<option value='"+days[i].day_id+"' selected>"+days[i].day_label+"</option>";
			}
			else{
				myDays = myDays +  "<option value='"+days[i].day_id+"' >"+days[i].day_label+"</option>";
			}
			
		});
		myDays = myDays +"</select>";
		//alert(myDays);
		var school_id = getSchoolID();
		
		var rooms = getJasonData("action=GETROOMS&school_id="+school_id);
		rooms = jQuery.parseJSON(rooms);
		//alert("rooms "+ rooms);

		var theRooms = "<select id='room' name='room'>";
		$.each(rooms, function(i, item) {
			theRooms = theRooms +  "<option value='"+rooms[i].room_id+"' >"+rooms[i].room_label+"</option>";
		});
		
		theRooms = theRooms+ "</select>";
		
		var periods = getJasonData("action=GETPERIODS&school_id="+school_id);
		//alert(periods);
		periods = jQuery.parseJSON(periods);
		var thePeriods = "<select id='period_label_id' name='period_label_id' disabled='true' >";
		$.each(periods, function(i, item) {
			if(selectedPeriod == periods[i].period_label_id)
			{
				thePeriods = thePeriods +  "<option value='"+periods[i].period_label_id+"' selected>"+periods[i].period_label+"</option>";
			}
			else{
				thePeriods = thePeriods +  "<option value='"+periods[i].period_label_id+"' >"+periods[i].period_label+"</option>";
			}
		});
		
		thePeriods = thePeriods+ "</select>";
		//alert('Aha');
		
		var table = "<table><tr><td>Subject</td><td>:SUBJECTS</td></tr><tr><td>Teacher List</td><td id='teachers' name='teachers' >:TEACHER</td></tr><tr>";
		table = table + "</tr><tr><td>Day</td><td>:DAYS</td></tr>";
		table = table + "<tr><td>Period</td><td id='starttime' name='starttime'>:STARTTIME</select></td></tr>";
		//table = table + "<tr><td>End Time</td><td id='endtime' name='endtime'>";
		//table = table + ":ENDTIME</td></tr><tr></tr>";
		table = table + "<tr><td>Room</td><td>:ROOM</td></tr>";
                table = table + "<tr><td>Change Type</td><td><select id='change_type' name='change_type'><option value='currentslot'> Change Only Current Slot </option><option value='allslotsinthisclass'> Change All Slots In This Class </option></select></td></tr>";
		table = table + "<tr><td><input type='button' value='Save' name='btnSaveTimeTableSlot' id='btnSaveTimeTableSlot' onClick='saveTimeTableSlot()' /></td>";
		table = table +"<td><input type='button' value='Cancel' name='btnCancelTimeTableSlot' id='btnCancelTimeTableSlot' onclick=\"$('#dlg').dialog('close')\"/></td></tr></table>";
		
		
		table = table.replace(":SUBJECTS",subselect);
		table = table.replace(":TEACHER",teacherSelect);
		//table = table.replace(":STARTTIME",periodtimes[0]);
		//table = table.replace(":ENDTIME",periodtimes[1]);
		table = table.replace(":STARTTIME",thePeriods);
		table = table.replace(":DAYS",myDays);	
		table = table.replace(":ROOM",theRooms);				
		
		
		$('#slotinfo').html(table);
		$("#teachers").html(teacherSelect);
		
		$('#dlg').dialog('open').dialog('center').dialog('setTitle', 'Timetable Slot');
		
	}
}
		
function saveTimeTableSlot()
{
	//alert('Subject ID '+ $("#subjectselect").val());
	//alert('Teacher ID '+ $("#teacherlist").val());
	//alert('Day ID '+ $("#day").val());
	//alert('starttime '+ $("#starttime").html());
	//alert('endtime '+ $("#endtime").html());
	/*var param = "subject_id="+$("#subjectselect").val()+"&teacher_id="+ $("#teacherlist").val()+"&day_id="+$("#day").val();
	param =param + "&period_label_id="+$("#period_label_id").val()+"&endtime="+$("#endtime").html()+"&timetable_id="+$("#timetable_id").val();
	param = param + "&year_id="+$("#teachertimetable_year_id").val()+"&grade_id="+$("#teachertimetable_grade_id").val();
	param = param + "&room_id="+$("#room").val()+"&save_type=save_timetable_slot";
	//param = param + "&timetable_teacher_id="+$("#teacher_list").val();
	//param = param + "&timetable_learner_id="+$("#class_list").val();
	param = param + "&timetable_user_id="+($("#teacher_list").val() == 0?$("#class_list").val():$("#teacher_list").val());*/
	var teacher_id = $("#teacherlist").val();
	if(teacher_id == undefined || teacher_id == "undefined")
	{
		teacher_id = 0;
	}
	var param = "subject_id="+$("#subjectselect").val()+"&teacher_id="+ teacher_id+"&day_id="+$("#day").val();
	param =param + "&period_label_id="+$("#period_label_id").val()+"&timetable_id="+$("#timetable_id").val();
	param = param + "&year_id="+$("#teachertimetable_year_id").val()+"&grade_id="+$("#teachertimetable_grade_id").val();
	param = param + "&room_id="+$("#room").val()+"&save_type=save_timetable_slot";
	//param = param + "&timetable_teacher_id="+$("#teacher_list").val();
	//param = param + "&timetable_learner_id="+$("#class_list").val();
	param = param + "&timetable_user_id="+($("#teacher_list").val() == 0?$("#class_list").val():$("#teacher_list").val());
	
	var school_id = getUrlParameter("school_id");
	if(school_id == undefined || school_id == "undefined")
	{
		school_id = $("#schools").val();
	}			
	param = param + "&school_id="+school_id;
	//alert(param);
	sendDataByGet(param,'timetable_save.php');
	getTimetable($("#timetable_id").val());
	getClassList($("#timetable_id").val());
	alert("Timetable Slot Changes Saved Changes");
	$('#dlg').dialog('close');
	//alert(param);			
}
		
function addNewTimeTableSlot()
{			
	var subselect = "";
	var subjects = getJasonData("action=getsubjects");
	var school_id = getUrlParameter("school_id"); 
	if(school_id == undefined || school_id == "undefined") 
	{ 
		school_id = $("#schools").val(); 
	}
        
	var year_id = $("#teachertimetable_year_id").val();
        
	subjects =  jQuery.parseJSON(subjects)	;
	subselect = "<select id='subjectselect' name='subjectselect' onchange='getSubjectTeacher(this.value, "+school_id+","+year_id+")'>";
	$.each(subjects, function(i, item) {
		subselect = subselect + "<option value='"+item.subject_id+"'>"+item.subject_title.toUpperCase()+"</option>";
	});
	subselect = subselect +"</select>";
	
	var teacherSelect = "";
	teacherSelect = "<select id='teacherlist' name='teacherlist'>";
	var teachers = getJasonData("action=GETCLASSTEACHER&class_id="+$("#timetable_id").val());
	teachers = jQuery.parseJSON(teachers);
        //alert('addNewTimeTableSlot '+ teachers);
	$.each(teachers, function(i, item) {
		var name = item.name + " " + item.surname;
		teacherSelect = teacherSelect + "<option value='"+item.user_id+"'>"+name+"</option>";			
	});
	teacherSelect = teacherSelect + "</select>";
	var days = getJasonData("action=TIMETABLEDAYS");
	days = jQuery.parseJSON(days);
	var myDays = "<select id='day' name='day'>";
	$.each(days, function(i, item) {
		myDays = myDays +  "<option value='"+days[i].day_id+"' >"+days[i].day_label+"</option>";				
	});
	myDays = myDays + "</select>";
	var rooms = getJasonData("action=GETROOMS&school_id="+school_id);
	rooms = jQuery.parseJSON(rooms);
	var theRooms = "<select id='room' name='room'>";
	$.each(rooms, function(i, item) {
		theRooms = theRooms +  "<option value='"+rooms[i].room_id+"' >"+rooms[i].room_label+"</option>";
	});
	theRooms = theRooms+ "</select>";
	
	var periods = getJasonData("action=GETPERIODS&school_id="+school_id);
	//alert(periods);
	periods = jQuery.parseJSON(periods);
	var thePeriods = "<select id='period_label_id' name='period_label_id'>";
	$.each(periods, function(i, item) {
		thePeriods = thePeriods +  "<option value='"+periods[i].period_label_id+"' >"+periods[i].period_label+"</option>";
	});
	
	thePeriods = thePeriods+ "</select>";
	
	var startHour = "<select name='starthour' id='starthour'>";
	var endHour = "<select name='endhour' id='endhour'>";
	for(i=1;i<=24;i++)
	{
		var z =i;
		if(i < 10)
		{
			z = "0"+z;
		}
		startHour =startHour+ "<option value='"+z+"'>"+z+"<option>";
		endHour =endHour+ "<option value='"+z+"'>"+z+"<option>";
	}				
	startHour = startHour + "</select>";
	endHour = endHour + "</select>";
	
	var starMinute = "<select name='startminute' id='startminute' >";
	var endMinute = "<select name='endminute' id='endminute' >";			
	
	for(i = 0;i<60;i=i+5)
	{
		var z =i;
		if(i < 10)
		{
			z = "0"+z;
		}
		starMinute =starMinute+ "<option value='"+z+"'>"+z+"<option>";
		endMinute =endMinute+ "<option value='"+z+"'>"+z+"<option>";
	}
	
	starMinute = starMinute + "</select>";
	endMinute = endMinute + "</select>";
	var starttime = startHour + " : "+ starMinute;
	var endtime = endHour + " : "+ + endMinute;
	
	var table = "<table><tr><td>Subject</td><td>:SUBJECTS</td></tr><tr><td>Teacher List</td><td id='teachers' name='teachers' >:TEACHER</td></tr><tr>";
	table = table + "</tr><tr><td>Day</td><td>:DAYS</td></tr>";
	table = table + "<tr><td>Period</td><td id='starttime' name='starttime'>:STARTTIME</select></td></tr>";
	table = table + "<tr><td>Room</td><td>:ROOM</td></tr>";
	table = table + "<tr><td><input type='button' value='Save' name='btnSaveTimeTableSlot' id='btnSaveTimeTableSlot' onClick='addTimeTableSlot()' /></td>";
	table = table +"<td><input type='button' value='Cancel' name='btnCancelTimeTableSlot' id='btnCancelTimeTableSlot' onclick=\"$('#dlg').dialog('close')\"/></td></tr></table>";
	
	
	table = table.replace(":SUBJECTS",subselect);
	table = table.replace(":TEACHER",teacherSelect);
	table = table.replace(":STARTTIME",thePeriods);
	table = table.replace(":ENDTIME",endtime);
	table = table.replace(":DAYS",myDays);	
	table = table.replace(":ROOM",theRooms);
	$('#slotinfo').html(table);
	$("#teachers").html(teacherSelect);
		
	$('#dlg').dialog('open').dialog('center').dialog('setTitle', 'Add Timetable Slot');			
	
}
		
		
function addTimeTableSlot()
{
	var teacher_id =  $("#teacherlist").val();
	if( $("#teacherlist").val() == undefined ||  $("#teacherlist").val() == "undefined")
	{
		teacher_id = "0";
	}
	var param = "subject_id="+$("#subjectselect").val()+"&teacher_id="+ teacher_id +"&day_id="+$("#day").val();
	param =param + "&period_label_id="+$("#period_label_id").val()+"&timetable_id="+$("#timetable_id").val();
	param = param + "&year_id="+$("#teachertimetable_year_id").val()+"&grade_id="+$("#teachertimetable_grade_id").val();
	param = param + "&room_id="+$("#room").val()+"&save_type=save_timetable_slot";
	//param = param + "&timetable_teacher_id="+$("#teacher_list").val();
	//param = param + "&timetable_learner_id="+$("#class_list").val();
	param = param + "&timetable_user_id="+($("#teacher_list").val() == 0?$("#class_list").val():$("#teacher_list").val());
	var school_id = getUrlParameter("school_id");
	if(school_id == undefined || school_id == "undefined")
	{
		school_id = $("#schools").val();
	}			
	param = param + "&school_id="+school_id;
	//alert("WTF 2 : "+ param);
	sendDataByGet(param,'timetable_save.php');
	getTimetable($("#timetable_id").val());
	getClassList($("#timetable_id").val());
	alert("Timetable Slot Changes Saved Changes");
	$('#dlg').dialog('close');
}
	
function viewStatsTimetable(user_group){
   
    //alert (user_group);
    
    var user_type = getUrlParameter("user_type");
    var user_id = getUrlParameter("user_id");
    var year_id = getUrlParameter("year_id");//$("#year_id").val();
    //var server_url = $("#server_url").val();
    //alert(server_url);
    var timetable = "";
    var params = "";
    var school_id = getUrlParameter("school_id");
    //alert(school_id);
//    school_id = getSchoolID();


    if(school_id == undefined || school_id == "undefined")
    {
            school_id = $("#schools").val();
    }
    
    switch(user_group){
     
        case 'parent':
    
            var params = "action=VIEWELEARNTRACKER&id=22&user_type="+user_type+"&user_id="+user_id+"&year_id="+year_id+"&school_id="+school_id;

            $("#learnerloading").show();

            $.ajax({
                 type: "GET",
                 url: 'api/process.php',
                 data: params,
                 success: function(response){
                             ///alert(response);
                             timetable = response;
                             $("#learner_timetable").html(timetable);

                              $("#learnerloading").hide();
                             //value = 100;
                             //$('#prgrsBar').progressbar('setValue', value);
                 }
             });
           
            break;
        
        case 'mec':
            var mec_year_id = $("#mec_year_id").val();;
            if (mec_year_id == undefined){
                mec_year_id = 0;
            }
            
            var mec_district_id = $("#mec_district_id").val();
            if (mec_district_id == undefined){
                mec_district_id = 0;
            }
            
            var mec_school_id = $("#mec_school_id").val();
            if (mec_school_id == undefined){
                mec_school_id = 0;
            }
            
            var mec_grade_id = $("#mec_grade_id").val();
            if (mec_grade_id == undefined){
                mec_grade_id = 0;
            }
            
            var mec_subject_id = $("#mec_subject_id").val();
            if (mec_subject_id == undefined){
                mec_subject_id = 0;
            }
            
            var mec_teacher_id = $("#mec_teacher_id").val();
            if (mec_teacher_id == undefined){
                mec_teacher_id = 0;
            }
            
            var mec_class_id = $("#mec_class_id").val();
            if (mec_class_id == undefined){
                mec_class_id = 0;
            }
            
            var mec_learner_id = $("#mec_learner_id").val();
            if (mec_learner_id == undefined){
                mec_learner_id = 0;
            }
            
            var mec_parent_id = $("#mec_parent_id").val();
            if (mec_parent_id == undefined){
                mec_parent_id = 0;
            }

            $("#mecloading").show();

            
            var years = getSchoolYears(school_id);
              
            years =  jQuery.parseJSON(years);
            //alert(years);
            
            $('#mec_year_id').html("");
             $('#mec_year_id').append(
                    $('<option></option>').val(0).html('Select Year To View Year Stats')
            );
            var selected = "";
            $.each(years, function(i, item) {
                    //alert(timetableid[i].timetabl_id);
                 //alert('mec year id '+mec_year_id+' year_id '+item.year_id);
                if (mec_year_id == item.year_id){
                    selected = "selected";
                } else {
                    selected = "";
                }
                $('#mec_year_id').append(
                                $('<option '+selected+'></option>').val(item.year_id).html(item.year_label)
                        ); 
            });
            
            var districts;
            if (mec_year_id == 0){
                districts = getSchoolDistrict(year_id);
            } else {
                districts = getSchoolDistrict(mec_year_id);
            }
            
            districts =  jQuery.parseJSON(districts);
            
            $('#mec_district_id').html("");
             $('#mec_district_id').append(
                    $('<option></option>').val(0).html('Select District To View District Stats')
            );
            $.each(districts, function(i, item) {
                    //alert(timetableid[i].timetabl_id);
                    //alert('mec grade id '+mec_grade_id+' grade_id '+item.grade_id);
                if (mec_district_id == item.district_id){
                    selected = "selected";
                } else {
                    selected = "";
                }    
                $('#mec_district_id').append(
                                $('<option '+selected+'></option>').val(item.district_id).html(item.district_name)
                        ); 
            });
            
            var schools;
            if (mec_year_id == 0){
                schools = getSchools(year_id);
            } else {
                schools = getSchools(mec_year_id);
            }
            
            schools =  jQuery.parseJSON(schools);
            
            $('#mec_district_id').html("");
             $('#mec_district_id').append(
                    $('<option></option>').val(0).html('Select School To View School Stats')
            );
            $.each(schools, function(i, item) {
                    //alert(timetableid[i].timetabl_id);
                    //alert('mec grade id '+mec_grade_id+' grade_id '+item.grade_id);
                if (mec_school_id == item.school_id){
                    selected = "selected";
                } else {
                    selected = "";
                }    
                $('#mec_school_id').append(
                                $('<option '+selected+'></option>').val(item.schools_id).html(item.school_name)
                        ); 
            });
            
            var grades;
            if (mec_year_id == 0){
                grades = getSchoolGrades(school_id,year_id);
            } else {
                grades = getSchoolGrades(school_id,mec_year_id);
            }
            
            grades =  jQuery.parseJSON(grades);
            
            $('#mec_grade_id').html("");
             $('#mec_grade_id').append(
                    $('<option></option>').val(0).html('Select Grade To View Grade Stats')
            );
            $.each(grades, function(i, item) {
                    //alert(timetableid[i].timetabl_id);
                    //alert('mec grade id '+mec_grade_id+' grade_id '+item.grade_id);
                if (mec_grade_id == item.grade_id){
                    selected = "selected";
                } else {
                    selected = "";
                }    
                $('#mec_grade_id').append(
                                $('<option '+selected+'></option>').val(item.grade_id).html(item.grade_title)
                        ); 
            });
            
            var subjects;
            if (mec_year_id == 0){
                subjects =  getSchoolSubjects(school_id,year_id, mec_grade_id);
            } else {
                subjects =  getSchoolSubjects(school_id,mec_year_id,mec_grade_id);
            }

            subjects =  jQuery.parseJSON(subjects);
            
            $('#mec_subject_id').html("");
             $('#mec_subject_id').append(
                    $('<option></option>').val(0).html('Select Subject To View Subject Stats')
            );
            $.each(subjects, function(i, item) {
                    //alert(timetableid[i].timetabl_id);
                if (mec_subject_id == item.subject_id){
                    selected = "selected";
                } else {
                    selected = "";
                }    
                $('#mec_subject_id').append(
                                $('<option '+selected+'></option>').val(item.subject_id).html(item.subject_title)
                        ); 
            });
//            var teachers = getSchoolTeachers(school_id,year_id, grade_id, subject_id);
            var teachers;
            if (mec_year_id == 0){
                teachers =  getSchoolTeachers(school_id,year_id, mec_grade_id, mec_subject_id);
            } else {
                teachers =  getSchoolTeachers(school_id,mec_year_id,mec_grade_id,mec_subject_id);
            }

            teachers =  jQuery.parseJSON(teachers);
            
            $('#mec_teacher_id').html("");
             $('#mec_teacher_id').append(
                    $('<option></option>').val(0).html('Select Teacher To View Teacher Stats')
            );
            $.each(teachers, function(i, item) {
                    //alert(timetableid[i].timetabl_id);
                if (mec_teacher_id == item.user_id){
                    selected = "selected";
                } else {
                    selected = "";
                }    
                $('#mec_teacher_id').append(
                                $('<option '+selected+'></option>').val(item.user_id).html(item.surname+' '+item.name)
                        ); 
            });
//            var classes = getSchoolClasses(school_id,year_id, grade_id, subject_id, teacher_id);
            var classes;
            if (mec_year_id == 0){
                classes =  getSchoolClasses(school_id,year_id, mec_grade_id, mec_subject_id, mec_teacher_id);
            } else {
                classes =  getSchoolClasses(school_id,mec_year_id,mec_grade_id,mec_subject_id, mec_teacher_id);
            }

            classes =  jQuery.parseJSON(classes);
            
            $('#mec_class_id').html("");
             $('#mec_class_id').append(
                    $('<option></option>').val(0).html('Select Class To View Class Stats')
            );
            $.each(classes, function(i, item) {
                    //alert(timetableid[i].timetabl_id);
                if (mec_class_id == item.class_id){
                    selected = "selected";
                } else {
                    selected = "";
                }    
                $('#mec_class_id').append(
                                $('<option '+selected+'></option>').val(item.class_id).html(item.class_label)
                        ); 
            });
//            var learners = getSchoolLearners(school_id,year_id,grade_id,subject_id, teacher_id, class_id);
            var learners;
            if (mec_year_id == 0){
                learners =  getSchoolLearners(school_id,year_id, mec_grade_id, mec_subject_id, mec_teacher_id);
            } else {
                learners =  getSchoolLearners(school_id,mec_year_id,mec_grade_id,mec_subject_id);
            }

            learners =  jQuery.parseJSON(learners);
            
            $('#mec_learner_id').html("");
             $('#mec_learner_id').append(
                    $('<option></option>').val(0).html('Select Learner To View Learner Stats')
            );
            $.each(learners, function(i, item) {
                    //alert(timetableid[i].timetabl_id);
                if (mec_learner_id == item.user_id){
                    selected = "selected";
                } else {
                    selected = "";
                }    
                $('#mec_learner_id').append(
                                $('<option '+selected+'></option>').val(item.user_id).html(item.surname+' '+item.name)
                        ); 
            });
//            var parents = getSchoolParents(school_id,year_id,learner_id);
            var parents;
            if (mec_year_id == 0){
                parents =  getSchoolParents(school_id,year_id, mec_grade_id, mec_subject_id, mec_teacher_id, mec_learner_id);
            } else {
                parents =  getSchoolParents(school_id,mec_year_id,mec_grade_id,mec_subject_id, mec_learner_id);
            }

            parents =  jQuery.parseJSON(parents);
            
            $('#mec_parent_id').html("");
             $('#mec_parent_id').append(
                    $('<option></option>').val(0).html('Select Parent To View Parent Stats')
            );
            $.each(parents, function(i, item) {
                    //alert(timetableid[i].timetabl_id);
                if (mec_parent_id == item.user_id){
                    selected = "selected";
                } else {
                    selected = "";
                }    
                $('#mec_parent_id').append(
                                $('<option '+selected+'></option>').val(item.user_id).html(item.surname+' '+item.name)
                        ); 
            });
            
           
	//alert(timetableid);
	
            

            
            //$("#loading").hide();
            var params = "action=VIEWELEARNTRACKER&id=22&user_type="+user_type+"&user_id="+user_id+"&year_id="+year_id+"&school_id="+school_id;
            //getGradeClassList(grade_id, 'All');
            $.ajax({
                 type: "GET",
                 url: 'api/process.php',
                 data: params,
                 success: function(response){
                             ///alert(response);
                             timetable = response;
                             $("#mec_timetable").html(timetable);

                              $("#mecloading").hide();
                             //value = 100;
                             //$('#prgrsBar').progressbar('setValue', value);
                 }
             });
            break;
            
        case 'principal':
            var principal_year_id = $("#principal_year_id").val();;
            if (principal_year_id == undefined){
                principal_year_id = 0;
            }
            
            var principal_grade_id = $("#principal_grade_id").val();
            if (principal_grade_id == undefined){
                principal_grade_id = 0;
            }
            
            var principal_subject_id = $("#principal_subject_id").val();
            if (principal_subject_id == undefined){
                principal_subject_id = 0;
            }
            
            var principal_teacher_id = $("#principal_teacher_id").val();
            if (principal_teacher_id == undefined){
                principal_teacher_id = 0;
            }
            
            var principal_class_id = $("#principal_class_id").val();
            if (principal_class_id == undefined){
                principal_class_id = 0;
            }
            
            var principal_learner_id = $("#principal_learner_id").val();
            if (principal_learner_id == undefined){
                principal_learner_id = 0;
            }
            
            var principal_parent_id = $("#principal_parent_id").val();
            if (principal_parent_id == undefined){
                principal_parent_id = 0;
            }

            $("#principalloading").show();

            
            var years = getSchoolYears(school_id);
              
            years =  jQuery.parseJSON(years);
            //alert(years);
            
            $('#principal_year_id').html("");
             $('#principal_year_id').append(
                    $('<option></option>').val(0).html('Select Year To View Year Stats')
            );
            var selected = "";
            $.each(years, function(i, item) {
                    //alert(timetableid[i].timetabl_id);
                 //alert('principal year id '+principal_year_id+' year_id '+item.year_id);
                if (principal_year_id == item.year_id){
                    selected = "selected";
                } else {
                    selected = "";
                }
                $('#principal_year_id').append(
                                $('<option '+selected+'></option>').val(item.year_id).html(item.year_label)
                        ); 
            });
            
            var grades;
            if (principal_year_id == 0){
                grades = getSchoolGrades(school_id,year_id);
            } else {
                grades = getSchoolGrades(school_id,principal_year_id);
            }
            
            grades =  jQuery.parseJSON(grades);
            
            $('#principal_grade_id').html("");
             $('#principal_grade_id').append(
                    $('<option></option>').val(0).html('Select Grade To View Grade Stats')
            );
            $.each(grades, function(i, item) {
                    //alert(timetableid[i].timetabl_id);
                    //alert('principal grade id '+principal_grade_id+' grade_id '+item.grade_id);
                if (principal_grade_id == item.grade_id){
                    selected = "selected";
                } else {
                    selected = "";
                }    
                $('#principal_grade_id').append(
                                $('<option '+selected+'></option>').val(item.grade_id).html(item.grade_title)
                        ); 
            });
            
            var subjects;
            if (principal_year_id == 0){
                subjects =  getSchoolSubjects(school_id,year_id, principal_grade_id);
            } else {
                subjects =  getSchoolSubjects(school_id,principal_year_id,principal_grade_id);
            }

            subjects =  jQuery.parseJSON(subjects);
            
            $('#principal_subject_id').html("");
             $('#principal_subject_id').append(
                    $('<option></option>').val(0).html('Select Subject To View Subject Stats')
            );
            $.each(subjects, function(i, item) {
                    //alert(timetableid[i].timetabl_id);
                if (principal_subject_id == item.subject_id){
                    selected = "selected";
                } else {
                    selected = "";
                }    
                $('#principal_subject_id').append(
                                $('<option '+selected+'></option>').val(item.subject_id).html(item.subject_title)
                        ); 
            });
//            var teachers = getSchoolTeachers(school_id,year_id, grade_id, subject_id);
            var teachers;
            if (principal_year_id == 0){
                teachers =  getSchoolTeachers(school_id,year_id, principal_grade_id, principal_subject_id);
            } else {
                teachers =  getSchoolTeachers(school_id,principal_year_id,principal_grade_id,principal_subject_id);
            }

            teachers =  jQuery.parseJSON(teachers);
            
            $('#principal_teacher_id').html("");
             $('#principal_teacher_id').append(
                    $('<option></option>').val(0).html('Select Teacher To View Teacher Stats')
            );
            $.each(teachers, function(i, item) {
                    //alert(timetableid[i].timetabl_id);
                if (principal_teacher_id == item.user_id){
                    selected = "selected";
                } else {
                    selected = "";
                }    
                $('#principal_teacher_id').append(
                                $('<option '+selected+'></option>').val(item.user_id).html(item.surname+' '+item.name)
                        ); 
            });
//            var classes = getSchoolClasses(school_id,year_id, grade_id, subject_id, teacher_id);
            var classes;
            if (principal_year_id == 0){
                classes =  getSchoolClasses(school_id,year_id, principal_grade_id, principal_subject_id, principal_teacher_id);
            } else {
                classes =  getSchoolClasses(school_id,principal_year_id,principal_grade_id,principal_subject_id, principal_teacher_id);
            }

            classes =  jQuery.parseJSON(classes);
            
            $('#principal_class_id').html("");
             $('#principal_class_id').append(
                    $('<option></option>').val(0).html('Select Class To View Class Stats')
            );
            $.each(classes, function(i, item) {
                    //alert(timetableid[i].timetabl_id);
                if (principal_class_id == item.class_id){
                    selected = "selected";
                } else {
                    selected = "";
                }    
                $('#principal_class_id').append(
                                $('<option '+selected+'></option>').val(item.class_id).html(item.class_label)
                        ); 
            });
//            var learners = getSchoolLearners(school_id,year_id,grade_id,subject_id, teacher_id, class_id);
            var learners;
            if (principal_year_id == 0){
                learners =  getSchoolLearners(school_id,year_id, principal_grade_id, principal_subject_id, principal_teacher_id);
            } else {
                learners =  getSchoolLearners(school_id,principal_year_id,principal_grade_id,principal_subject_id);
            }

            learners =  jQuery.parseJSON(learners);
            
            $('#principal_learner_id').html("");
             $('#principal_learner_id').append(
                    $('<option></option>').val(0).html('Select Learner To View Learner Stats')
            );
            $.each(learners, function(i, item) {
                    //alert(timetableid[i].timetabl_id);
                if (principal_learner_id == item.user_id){
                    selected = "selected";
                } else {
                    selected = "";
                }    
                $('#principal_learner_id').append(
                                $('<option '+selected+'></option>').val(item.user_id).html(item.surname+' '+item.name)
                        ); 
            });
//            var parents = getSchoolParents(school_id,year_id,learner_id);
            var parents;
            if (principal_year_id == 0){
                parents =  getSchoolParents(school_id,year_id, principal_grade_id, principal_subject_id, principal_teacher_id, principal_learner_id);
            } else {
                parents =  getSchoolParents(school_id,principal_year_id,principal_grade_id,principal_subject_id, principal_learner_id);
            }

            parents =  jQuery.parseJSON(parents);
            
            $('#principal_parent_id').html("");
             $('#principal_parent_id').append(
                    $('<option></option>').val(0).html('Select Parent To View Parent Stats')
            );
            $.each(parents, function(i, item) {
                    //alert(timetableid[i].timetabl_id);
                if (principal_parent_id == item.user_id){
                    selected = "selected";
                } else {
                    selected = "";
                }    
                $('#principal_parent_id').append(
                                $('<option '+selected+'></option>').val(item.user_id).html(item.surname+' '+item.name)
                        ); 
            });
            
           
	//alert(timetableid);
	
            

            
            //$("#loading").hide();
            var params = "action=VIEWELEARNTRACKER&id=22&user_type="+user_type+"&user_id="+user_id+"&year_id="+year_id+"&school_id="+school_id;
            //getGradeClassList(grade_id, 'All');
            $.ajax({
                 type: "GET",
                 url: 'api/process.php',
                 data: params,
                 success: function(response){
                             ///alert(response);
                             timetable = response;
                             $("#principal_timetable").html(timetable);

                              $("#principalloading").hide();
                             //value = 100;
                             //$('#prgrsBar').progressbar('setValue', value);
                 }
             });
            break;
    }
    
}

function getSchoolYears(school_id){
    
     var years = getJasonData("action=GETSCHOOLYEARS&school_id="+school_id);
     
     return years;
}

function getSchoolDistrict(school_id,year_id){
    
     var districts = getJasonData("action=GETSCHOOLDISTRICT&year_id="+year_id);
     
     return districts;
}

function getSchools(year_id){
    
     var schools = getJasonData("action=GETSCHOOLS&year_id="+year_id);
     
     return schools;
}

function getSchoolGrades(school_id,year_id){
    
     var grades = getJasonData("action=GETSCHOOLGRADES&school_id="+school_id+"&year_id="+year_id);
     
     return grades;
}

function getSchoolSubjects(school_id,year_id, grade_id){
    
     var subjects = getJasonData("action=GETSCHOOLSUBJECTS&school_id="+school_id+"&year_id="+year_id+"&grade_id="+grade_id);
     
     return subjects;
}

function getSchoolTeachers(school_id,year_id, grade_id, subject_id){
    
     var teachers = getJasonData("action=GETSCHOOLTEACHERS&school_id="+school_id+"&year_id="+year_id+"&grade_id="+grade_id+"&subject_id="+subject_id);
     
     return teachers;
}

function getSchoolClasses(school_id,year_id, grade_id, subject_id, teacher_id){
    
     var classes = getJasonData("action=GETSCHOOLCLASSES&school_id="+school_id+"&year_id="+year_id+"&grade_id="+grade_id+"&subject_id="+subject_id+"&teacher_id="+teacher_id);
     
     return classes;
}

function getSchoolLearners(school_id,year_id, grade_id, subject_id, teacher_id, class_id){
    
     var learners = getJasonData("action=GETSCHOOLLEARNERS&school_id="+school_id+"&year_id="+year_id+"&grade_id="+grade_id+"&subject_id="+subject_id+"&teacher_id="+teacher_id+"&class_id="+class_id);
     
     return learners;
}

function getSchoolParents(school_id,year_id, grade_id, subject_id, teacher_id, class_id, learner_id){
    
     var parents = getJasonData("action=GETSCHOOLPARENTS&school_id="+school_id+"&year_id="+year_id+"&grade_id="+grade_id+"&subject_id="+subject_id+"&teacher_id="+teacher_id+"&class_id="+class_id+"&learner_id="+learner_id);
     
     return parents;
}

function viewLearnerTimeTable(timetableID){
    
    var timetable_id_tokens = timetableID.split("-");
    timetableID = timetable_id_tokens[0];
    var user_id = timetable_id_tokens[1];
    var parent_id = timetable_id_tokens[2];
    var user_type = 2;
    
    //var user_id = getUrlParameter("learner_id");
    var year_id = getUrlParameter("year_id");
    
    var school_id = getUrlParameter("school_id");
    //alert(school_id);
    school_id = getSchoolID();

    if(school_id == undefined || school_id == "undefined")
    {
            school_id = $("#schools").val();
    }
    var timetable = "";
    var params = "";
    if(user_id == null || user_id == undefined)
    {
            params = "action=PRINT_DAYS&parent_id="+parent_id+"&id="+timetableID+"&user_type="+user_type+"&year_id="+year_id+"&user_id=0"+"&school_id="+school_id;

    } else {//
            //alert(user_id);
            params = "action=PRINT_DAYS&parent_id="+parent_id+"&id="+timetableID+"&user_type="+user_type+"&user_id="+user_id+"&year_id="+year_id+"&school_id="+school_id;				
    }
    //alert(params);
    timetable ="";
    
    $("#learnerloading").show();
    
    $.ajax({
         type: "GET",
         url: 'api/process.php',
         data: params,
         success: function(response){
                     ///alert(response);
                     timetable = response;
                     $("#learner_timetable").html(timetable);
                     
                      $("#learnerloading").hide();
                     //value = 100;
                     //$('#prgrsBar').progressbar('setValue', value);
         }
     });
    
}
		
function getTimetable(timetableID)
{
    	
	//var user_type = 4;//getUrlParameter("user_type");
	//var user_id = 1500;//getUrlParameter("user_id");
	//var year_id = 3;//$("#teachertimetable_year_id").val();
	var user_type = getUrlParameter("user_type");
	var user_id = getUrlParameter("user_id");
	var year_id = $("#teachertimetable_year_id").val();
	
	if( $("#teachertimetable_year_id").val() == undefined)
	{
		year_id = getYearID();
	}
	
	//alert("We are here");
	//alert(user_type);
	//alert(user_id);
	//alert(year_id);
	var school_id = getUrlParameter("school_id");
	//alert(school_id);
	school_id = getSchoolID();
	
	if(school_id == undefined || school_id == "undefined")
	{
		school_id = $("#schools").val();
	}
	var timetable = "";
	var params = "";
	if(user_id == null || user_id == undefined)
	{
		params = "action=PRINT_DAYS&id="+timetableID+"&user_type="+user_type+"&year_id="+year_id+"&user_id=0"+"&school_id="+school_id;
		
	}
	else{
		//alert(user_id);
		params = "action=PRINT_DAYS&id="+timetableID+"&user_type="+user_type+"&user_id="+user_id+"&year_id="+year_id+"&school_id="+school_id;				
	}
	//alert(params);
	timetable ="";
	//timetable = getJasonData(params);
	//$("#prgrsBar").who();
	//$("#prgrsBar").show();
	//var value = $('#prgrsBar').progressbar('getValue');
        $("#learnerloading").show();
        $("#teacherloading").show();
         $("#timetableloading").show();
	var isDone = false;
	$.ajax({
     type: "GET",
     url: 'api/process.php',
     data: params,
     success: function(response){
		 ///alert(response);
		 timetable = response;
		 $("#timetable").html(timetable);
                 $("#timetableloading").hide();
                  $("#teacherloading").hide();
                  $("#learnerloading").hide();
		 //value = 100;
		 //$('#prgrsBar').progressbar('setValue', value);
     }
     });
	/*setTimeout(
	  function() 
	  {
		//alert(value);
		if(value < 80 && !isDone)
		{
			value += Math.floor(Math.random() * 10);
		}
		else{
			//$('#prgrsBar').hide();
		}
		//$('#prgrsBar').progressbar('setValue', value);
		//do something special
	  }, 100);*/
     
}

function viewLearnerTimetable(user_id, school_id){
    
//    var school_id = getUrlParameter("school_id");
//    if(school_id == undefined || school_id == "undefined")
//    {
//            school_id = $("#schools").val();
//    }
    var year_id = $("#teachertimetable_year_id").val();
    //alert($("#teachertimetable_year_id").val());
    if($("#teachertimetable_year_id").val() == undefined)
    {
            year_id = getYearID();
    }
    
    
    window.open("viewtimetable.php?school_id="+school_id+"&view_type=teacher&user_type="+user_type+"&user_id="+user_id+"&year_id="+year_id+"&id=22", 'Learner Timetable','width=700,height=500,toolbar=0,menubar=0,location=0,status=1,scrollbars=1,resizable=1,left=0,top=0');
    
}

function viewTimetable(user_id, school_id, user_type){
    
//    var school_id = getUrlParameter("school_id");
//    if(school_id == undefined || school_id == "undefined")
//    {
//            school_id = $("#schools").val();
//    }
    var year_id = $("#teachertimetable_year_id").val();
    //alert($("#teachertimetable_year_id").val());
    if($("#teachertimetable_year_id").val() == undefined)
    {
            year_id = getYearID();
    }
    
    var resetimetable;
    
    switch(user_type){
        case 2:{//LEARNER
            resetimetable = getJasonData("action=RESETLEARNERTIMETABLE&user_id="+user_id+"&year_id="+year_id);
            break;
        }
        
        case 4:{//TEACHER
            resetimetable = getJasonData("action=RESETTEACHERTIMETABLE&user_id="+user_id+"&year_id="+year_id);
            break;
        }
    }
    
    
    if (resetimetable){
        window.open("viewtimetable.php?school_id="+school_id+"&user_type="+user_type+"&user_id="+user_id+"&year_id="+year_id+"&id=22", 'Learner Timetable','width=700,height=500,toolbar=0,menubar=0,location=0,status=1,scrollbars=1,resizable=1,left=0,top=0');
    } else {
        alert('DATA ERROR: Timetable Failed To Reset For USERID '+user_id+' USERTYPE '+user_type);
    }
}

function reloadStatsReport(type, user_id){
    
    $("#"+type+"loading").show();
     
    switch(type){
    
        case 'teacher':{
                $(function() {
                         
                    //if ($("#principal_parent_id").val() == 0 && $("#principal_learner_id").val() == 0 && $("#principal_class_id").val() == 0 && $("#principal_year_id").val() == 0 && $("#principal_grade_id").val() == 0 && $("#principal_subject_id").val() == 0 && $("#principal_teacher_id").val() == 0){ 
                        var seriesOptions = [],
                        yAxisOptions = [],
                        seriesCounter = 0,
                        //statuses = ['Total', 'Open', 'Resolved','Closed', 'Re-Open', 'WIP', 'Enhancement', 'Assigned'],
                        colors = Highcharts.getOptions().colors;

                        ////localStorage.setItem("TimeLineReport", "Help");
                        //localStorage.clear();

                        var classes = [];
                        var classes_length = 0;
                        var learners = [];
                        var learners_length = 0;
                        var priorityCounter = 0;
                    //    $( document ).ready(function(){
                    //    
                            //$.getJSON('production_issues_action.php?project_id='+project_id+'&user_id='+user_id+'&type=all&status='+status+'&stamp=status', function(data_statuses) {
                            classes = getTeacherClassStats(user_id,-1);    
                             classes =  jQuery.parseJSON(classes);
                            //alert('GRADES '+grades);
    //                            statuses = data_statuses;
                                //issues_length = issues.length;

                        //     alert('issues '+issues+' lenght '+issues_length);
                        //     
                        //     });
                                //issues_length = 0;
                                $.each(classes, function(i, classe) {
                                        classes_length++;
                                        //alert('LENGTH '+grades_length);
                                });
                        //    });

                    //            if ( webStorageSupported && localStorage.getItem("TimeLineReport") !== null) {
                    //  //...
                    //                console.log('Oh YEss');
                    //                for(i = 0; i <= 7; i++){
                    //                    //var csv = localStorage.getItem(i);
                    //                      // seriesOptions[i] = csv.split(',');
                    //                        seriesOptions[i] = localStorage.getItem(i);
                    //                }
                    ////                seriesOptions = $.parseJSON(localStorage.getItem("TimeLineReport"));
                    //                createChart();
                    //                
                    //            } else {
                                    $.each(classes, function(i, classe) {

                                        //alert('Grade '+grade.grade_id);
                    //                if (page_status == "Drilldown"){ 
                    //                        //THEN GET STATUS REPORT AND DRAW BAR CHARTS
                    //                        alert('Status is '+drill_status+' Series Index is '+i);
                    //                        $('#wait').hide();
                    //                        $('#banner').hide();
                    //                } else {
                                       var classstats = getTeacherClassStats(user_id,classe.class_id);
                                        classstats =  jQuery.parseJSON(classstats);
                                       //$.each(gradestats, function(i, gradestat) {
                                        //$.getJSON('production_issues_action.php?project_id='+project_id+'&user_id='+user_id+'&type=all&status='+status+'&stamp=report', function(data) {
                    //                        if( webStorageSupported){
                    //                            localStorage.setItem(i, JSON.stringify(data));
                    //                              //localStorage.setItem(i, data);
                    //                        };
                                             //alert('GTITLE '+grade.grade_title);

                                            seriesOptions[i] = {
                                                name: classe.class_label,
                                                data: classstats.stats,
                                                color: classstats.color || colors[i]

                                            };
                                            //IN ORDER TO DRILL DOWN
                                            //CHECK IF THE STATUS IS WHAT WE CLICKED

                                            // As we're loading the data asynchronously, we don't know what order it will arrive. So
                                            // we keep a counter and create the chart when all the data is loaded.
                                            seriesCounter++;
                                            if (seriesCounter == classes_length) {
                                            //if (seriesCounter == statuses.length) {
                                                createChart();
                    //                            if ( webStorageSupported){
                    //                                localStorage.setItem("TimeLineReport", JSON.stringify(seriesOptions));
                    //                            };

                                                //$('#wait').hide();
                                                //$('#drill-down').hide();

                                            }
                                        });
//                                            $("#teacherloading").hide();

                    //                }
                                    //});
                    //            }
                    //            localStorage.setItem("TimeLineReport", seriesOptions);
                      //      });




                            function createDrillDown(name, categories, data, color){

                    //            function setChart {
                                chart.xAxis[0].setCategories(categories, false);
                                chart.series[0].remove(false);
                                chart.addSeries({
                                    name: name,
                                    data: data,
                                    color: color || 'white'
                                }, false);
                                chart.redraw();
                    //        }

                            }

                            function createTimeBarChart(stamp){

                                var colors = Highcharts.getOptions().colors;
                                var categories = [];
                                var name = 'Learner Attendance Register';
                                var date_stamp = Highcharts.dateFormat('%A, %b %e, %Y', stamp);
                                var data = [];

                                //$.getJSON('production_issues_action.php?project_id='+project_id+'&user_id='+user_id+'&type=all&status='+status+'&stamp=status', function(data_statuses) {
                                var grades = getGradeStats(-1);
                                    //statuses = data_statuses;
                                //issues_length = issues.length;

                        //     alert('issues '+issues+' lenght '+issues_length);
                        //     
                        //     });
                                var grades_length = 0;
                                    $.each(grades, function(i, grade) {
                                        grades_length++;
                                    });
                        //    });

                                    $.each(grades, function(i, grade) {

                                    });
                                //});
                            }

                            function createBarChart(status, stamp){

                                    var colors = Highcharts.getOptions().colors;
                                    var categories = [];
                                    var name = status+' Class Attendance';
                                    var date_stamp = Highcharts.dateFormat('%A, %b %e, %Y', stamp);
                                    var data = [];
                                    $.getJSON('production_issues_action.php?project_id='+project_id+'&user_id='+user_id+'&type=all&status='+status+'&stamp=priority', function(data_priority) {
                                        categories = data_priority;
                    //                });

                                        $.each(categories, function(i, priority) {
                                            priorities_length++;
                                        });
                                        $.each(categories, function(i, priority) {
                                        //
                                            $.getJSON('production_issues_action.php?project_id='+project_id+'&user_id='+user_id+'&type=all&status='+status+'&stamp=priority_stats&priority='+priority+'&time='+stamp, function(stats) {
                                                data[i] = {y:stats.data_count, color: stats.color || colors[i], drilldown: {name:priority, categories:stats.categories, data:stats.data, color:stats.color || colors[i], status:status,time:stamp, viewdetails:{name:priority, status:status, time:stamp}}};


                                                priorityCounter++;
                                                //alert('count '+priorityCounter+' total '+priorities_length);
                                                if (priorityCounter == priorities_length){
                                                    drawBars(name, categories, data, status, date_stamp, stats.color);
                                                    //alert('data is '+data);
                                                }
                                             });

                                        });

                                        //alert('data is '+data);

                                   });

                            }

                            function drawBars(name, categories, data, status, date_stamp, color){

                                    var colors = Highcharts.getOptions().colors;

                                    chart = new Highcharts.Chart({
                                    chart: {
                                        renderTo: 'container',
                                        type: 'column'
                                    },
                                    title: {
                                        text: status+' Attendance register on '+date_stamp
                                    },
                                    subtitle: {
                                        text: 'Click the bars to view atttendance details.'
                                    },
                                    xAxis: {
                                        categories: categories
                                    },
                                    yAxis: {
                                        title: {
                                            text: 'Number of learners'
                                        }
                                    },
                                    plotOptions: {
                                        column: {
                                            cursor: 'pointer',
                                            point: {
                                                events: {
                                                    click: function() {
                                                        var drilldown = this.drilldown;
                                                        var viewdetails = this.viewdetails;
                                                        if (drilldown) { // drill down
                    //                                        var url = 'bug_page.php?report=true&report_page=issues&category=102&status='+drilldown.status+'&priority='+drilldown.name;
                    //                                        $(location).attr('href', url);

                                                            //alert('Name '+drilldown.name+' Status '+drilldown.status+' Time '+drilldown.time);
                                                            createDrillDown(drilldown.name, drilldown.categories, drilldown.data, drilldown.color);
                                                        } else if(viewdetails) {
                    //                                        var url = 'bug_page.php?report=true&report_page=issues&category=102&status='+viewdetails.status+'&priority='+viewdetails.name+'&date='+viewdetails.time;
                    //                                        $(location).attr('href', url);
                                                              alert('This.Name '+this.name+' ViewDetails.Name '+viewdetails.name+' Status '+viewdetails.status+' Time '+viewdetails.time);
                                                        } else { // restore
                                                            createDrillDown(this.name, this.categories, this.data, this.color);
                                                        }
                                                    }
                                                }
                                            },
                                            dataLabels: {
                                                enabled: true,
                                                color: colors[0],
                                                style: {
                                                    fontWeight: 'bold'
                                                },
                                                formatter: function() {
                                                    return this.y;
                                                }
                                            }
                                        }
                                    },
                                    tooltip: {
                                        formatter: function() {
                                            var point = this.point,
                                                s = this.x +':<b>'+ this.y +' Issue(s) </b><br/>';
                                            if (point.drilldown) {
                                                s += 'Click to view '+ point.category +' issues ';
                                            } else {
                                                s += 'Click to return to Issues Priority Report';
                                            }
                                            return s;
                                        }
                                    },
                                    series: [{
                                        name: name,
                                        data: data,
                                        color: this.color || 'white'
                                    }],
                                    exporting: {
                                        enabled: false
                                    }
                                });


                            }
                            // create the chart when all data is loaded
                            function createChart() {
                                var chart = new Highcharts.StockChart({
                                    chart: {
                                        renderTo: 'container'
                                    },
                                    title: {
                                        text: 'Class Attendance Register Report'
                                    },
                                    subtitle: {
                                        text: 'Please point on the Timelines and click on the Class dot to view Learner Stats and more details'
                                    },
                                    rangeSelector: {
                                        selected: 4
                                    },
                                    yAxis: {
                                    //labels: {
                                    //formatter: function() {
                                    //return (this.value > 0 ? '+' : '') + this.value + '%';
                                    //}
                                    //},
                                        plotLines: [{
                                            value: 0,
                                            width: 2,
                                            color: 'silver'
                                        }]
                                    },
                                    plotOptions: {
                        //            series: {
                        //            //compare: 'percent'
                        //                name: this.name,
                        //                marker: {
                        //                    radius: 4
                        //                }
                        //            },
                                        series: {
                                                cursor: 'pointer',
                                                point: {
                                                    events: {
                                                        click: function() {
                        //                                      var stamp = Highcharts.dateFormat('%A, %b %e, %Y', this.x);
                                                              var stamp = this.x;
                                                              var status = this.series.name;

                    //                                          alert('Time was '+stamp+' Status is '+status);

                    //                                          if (status != 'Total'){
    //                                                              $('#wait').show();
    //                                                              createBarChart(status, stamp);
    //                                                              $('#wait').hide();
                    //                                          } else {
                                                                  //Do Nothing
                                                                  // $('#drill-down').hide('slowly');
                    //                                          }
                    //                                          $('#banner').hide('slowly');
                    //                                          $('#drill-down').load(page+'?status='+status,'', function(){
                    //                                            });
                        //                                    hs.htmlExpand(null, {
                        //                                        pageOrigin: {
                        //                                            x: this.pageX,
                        //                                            y: this.pageY
                        //                                        },
                        //                                        headingText: this.series.name,
                        //                                        maincontentText: Highcharts.dateFormat('%A, %b %e, %Y', this.x) +':<br/> '+
                        //                                            this.y +' issues',
                        //                                        width: 200
                        //                                    });
                                                        }
                                                    }
                                                },
                                                marker: {
                                                    lineWidth: 1
                                                }
                                            }
                                    },
                        //            legend: {
                        //                align: 'left',
                        //                verticalAlign: 'top',
                        ////                y: 20,
                        //                floating: true,
                        //                borderWidth: 0
                        //            },
                                    tooltip: {
                                        pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b><br/>',
                                        valueDecimals: 0
                                    },
                    //                legend: {
                    //                layout: 'vertical',
                    //                align: 'right',
                    //                verticalAlign: 'top',
                    //                x: -10,
                    //                y: 100,
                    //                borderWidth: 0
                    //                },
                                    series: seriesOptions
                                });
                            }

                    //}
            });
        }
        break;
        
        case 'principal':{
            
        $(function() {
                    
                    //if ($("#principal_parent_id").val() == 0 && $("#principal_learner_id").val() == 0 && $("#principal_class_id").val() == 0 && $("#principal_year_id").val() == 0 && $("#principal_grade_id").val() == 0 && $("#principal_subject_id").val() == 0 && $("#principal_teacher_id").val() == 0){ 
                        var seriesOptions = [],
                        yAxisOptions = [],
                        seriesCounter = 0,
                        //statuses = ['Total', 'Open', 'Resolved','Closed', 'Re-Open', 'WIP', 'Enhancement', 'Assigned'],
                        colors = Highcharts.getOptions().colors;

                        ////localStorage.setItem("TimeLineReport", "Help");
                        //localStorage.clear();

                        var grades = [];
                        var grades_length = 0;
                        var classes = [];
                        var classes_length = 0;
                        var priorityCounter = 0;
                    //    $( document ).ready(function(){
                    //    
                            //$.getJSON('production_issues_action.php?project_id='+project_id+'&user_id='+user_id+'&type=all&status='+status+'&stamp=status', function(data_statuses) {
                            grades = getGradeStats(-1);    
                             grades =  jQuery.parseJSON(grades);
                            //alert('GRADES '+grades);
    //                            statuses = data_statuses;
                                //issues_length = issues.length;

                        //     alert('issues '+issues+' lenght '+issues_length);
                        //     
                        //     });
                                //issues_length = 0;
                                $.each(grades, function(i, grade) {
                                        grades_length++;
                                        //alert('LENGTH '+grades_length);
                                });
                        //    });

                    //            if ( webStorageSupported && localStorage.getItem("TimeLineReport") !== null) {
                    //  //...
                    //                console.log('Oh YEss');
                    //                for(i = 0; i <= 7; i++){
                    //                    //var csv = localStorage.getItem(i);
                    //                      // seriesOptions[i] = csv.split(',');
                    //                        seriesOptions[i] = localStorage.getItem(i);
                    //                }
                    ////                seriesOptions = $.parseJSON(localStorage.getItem("TimeLineReport"));
                    //                createChart();
                    //                
                    //            } else {
                                    $.each(grades, function(i, grade) {

                                        //alert('Grade '+grade.grade_id);
                    //                if (page_status == "Drilldown"){ 
                    //                        //THEN GET STATUS REPORT AND DRAW BAR CHARTS
                    //                        alert('Status is '+drill_status+' Series Index is '+i);
                    //                        $('#wait').hide();
                    //                        $('#banner').hide();
                    //                } else {
                                       var gradestats = getGradeStats(grade.grade_id);
                                        gradestats =  jQuery.parseJSON(gradestats);
                                       //$.each(gradestats, function(i, gradestat) {
                                        //$.getJSON('production_issues_action.php?project_id='+project_id+'&user_id='+user_id+'&type=all&status='+status+'&stamp=report', function(data) {
                    //                        if( webStorageSupported){
                    //                            localStorage.setItem(i, JSON.stringify(data));
                    //                              //localStorage.setItem(i, data);
                    //                        };
                                             //alert('GTITLE '+grade.grade_title);

                                            seriesOptions[i] = {
                                                name: grade.grade_title,
                                                data: gradestats.stats,
                                                color: gradestats.color || colors[i]

                                            };
                                            //IN ORDER TO DRILL DOWN
                                            //CHECK IF THE STATUS IS WHAT WE CLICKED

                                            // As we're loading the data asynchronously, we don't know what order it will arrive. So
                                            // we keep a counter and create the chart when all the data is loaded.
                                            seriesCounter++;
                                            if (seriesCounter == grades_length) {
                                            //if (seriesCounter == statuses.length) {
                                                createChart();
                    //                            if ( webStorageSupported){
                    //                                localStorage.setItem("TimeLineReport", JSON.stringify(seriesOptions));
                    //                            };

                                                //$('#wait').hide();
                                                //$('#drill-down').hide();

                                            }
                                        });
                                            $("#principalloading").hide();

                    //                }
                                    //});
                    //            }
                    //            localStorage.setItem("TimeLineReport", seriesOptions);
                      //      });




                            function createDrillDown(name, categories, data, color){

                    //            function setChart {
                                chart.xAxis[0].setCategories(categories, false);
                                chart.series[0].remove(false);
                                chart.addSeries({
                                    name: name,
                                    data: data,
                                    color: color || 'white'
                                }, false);
                                chart.redraw();
                    //        }

                            }

                            function createTimeBarChart(stamp){

                                var colors = Highcharts.getOptions().colors;
                                var categories = [];
                                var name = 'Grades Attendance Register';
                                var date_stamp = Highcharts.dateFormat('%A, %b %e, %Y', stamp);
                                var data = [];

                                //$.getJSON('production_issues_action.php?project_id='+project_id+'&user_id='+user_id+'&type=all&status='+status+'&stamp=status', function(data_statuses) {
                                var grades = getGradeStats(-1);
                                    //statuses = data_statuses;
                                //issues_length = issues.length;

                        //     alert('issues '+issues+' lenght '+issues_length);
                        //     
                        //     });
                                var grades_length = 0;
                                    $.each(grades, function(i, grade) {
                                        grades_length++;
                                    });
                        //    });

                                    $.each(grades, function(i, grade) {

                                    });
                                //});
                            }

                            function createBarChart(status, stamp){

                                    var colors = Highcharts.getOptions().colors;
                                    var categories = [];
                                    var name = status+' Class Attendance';
                                    var date_stamp = Highcharts.dateFormat('%A, %b %e, %Y', stamp);
                                    var data = [];
                                    $.getJSON('production_issues_action.php?project_id='+project_id+'&user_id='+user_id+'&type=all&status='+status+'&stamp=priority', function(data_priority) {
                                        categories = data_priority;
                    //                });

                                        $.each(categories, function(i, priority) {
                                            priorities_length++;
                                        });
                                        $.each(categories, function(i, priority) {
                                        //
                                            $.getJSON('production_issues_action.php?project_id='+project_id+'&user_id='+user_id+'&type=all&status='+status+'&stamp=priority_stats&priority='+priority+'&time='+stamp, function(stats) {
                                                data[i] = {y:stats.data_count, color: stats.color || colors[i], drilldown: {name:priority, categories:stats.categories, data:stats.data, color:stats.color || colors[i], status:status,time:stamp, viewdetails:{name:priority, status:status, time:stamp}}};


                                                priorityCounter++;
                                                //alert('count '+priorityCounter+' total '+priorities_length);
                                                if (priorityCounter == priorities_length){
                                                    drawBars(name, categories, data, status, date_stamp, stats.color);
                                                    //alert('data is '+data);
                                                }
                                             });

                                        });

                                        //alert('data is '+data);

                                   });

                            }

                            function drawBars(name, categories, data, status, date_stamp, color){

                                    var colors = Highcharts.getOptions().colors;

                                    chart = new Highcharts.Chart({
                                    chart: {
                                        renderTo: 'container',
                                        type: 'column'
                                    },
                                    title: {
                                        text: status+' Attendance register on '+date_stamp
                                    },
                                    subtitle: {
                                        text: 'Click the bars to view atttendance details.'
                                    },
                                    xAxis: {
                                        categories: categories
                                    },
                                    yAxis: {
                                        title: {
                                            text: 'Number of learners'
                                        }
                                    },
                                    plotOptions: {
                                        column: {
                                            cursor: 'pointer',
                                            point: {
                                                events: {
                                                    click: function() {
                                                        var drilldown = this.drilldown;
                                                        var viewdetails = this.viewdetails;
                                                        if (drilldown) { // drill down
                    //                                        var url = 'bug_page.php?report=true&report_page=issues&category=102&status='+drilldown.status+'&priority='+drilldown.name;
                    //                                        $(location).attr('href', url);

                                                            //alert('Name '+drilldown.name+' Status '+drilldown.status+' Time '+drilldown.time);
                                                            createDrillDown(drilldown.name, drilldown.categories, drilldown.data, drilldown.color);
                                                        } else if(viewdetails) {
                    //                                        var url = 'bug_page.php?report=true&report_page=issues&category=102&status='+viewdetails.status+'&priority='+viewdetails.name+'&date='+viewdetails.time;
                    //                                        $(location).attr('href', url);
                                                              alert('This.Name '+this.name+' ViewDetails.Name '+viewdetails.name+' Status '+viewdetails.status+' Time '+viewdetails.time);
                                                        } else { // restore
                                                            createDrillDown(this.name, this.categories, this.data, this.color);
                                                        }
                                                    }
                                                }
                                            },
                                            dataLabels: {
                                                enabled: true,
                                                color: colors[0],
                                                style: {
                                                    fontWeight: 'bold'
                                                },
                                                formatter: function() {
                                                    return this.y;
                                                }
                                            }
                                        }
                                    },
                                    tooltip: {
                                        formatter: function() {
                                            var point = this.point,
                                                s = this.x +':<b>'+ this.y +' Issue(s) </b><br/>';
                                            if (point.drilldown) {
                                                s += 'Click to view '+ point.category +' issues ';
                                            } else {
                                                s += 'Click to return to Issues Priority Report';
                                            }
                                            return s;
                                        }
                                    },
                                    series: [{
                                        name: name,
                                        data: data,
                                        color: this.color || 'white'
                                    }],
                                    exporting: {
                                        enabled: false
                                    }
                                });


                            }
                            // create the chart when all data is loaded
                            function createChart() {
                                var chart = new Highcharts.StockChart({
                                    chart: {
                                        renderTo: 'container'
                                    },
                                    title: {
                                        text: 'Attendance Register Report'
                                    },
                                    subtitle: {
                                        text: 'Please point on the Timelines and click on the Grade dot to view Classes, Subjects, Learners, Teacher and more details'
                                    },
                                    rangeSelector: {
                                        selected: 4
                                    },
                                    yAxis: {
                                    //labels: {
                                    //formatter: function() {
                                    //return (this.value > 0 ? '+' : '') + this.value + '%';
                                    //}
                                    //},
                                        plotLines: [{
                                            value: 0,
                                            width: 2,
                                            color: 'silver'
                                        }]
                                    },
                                    plotOptions: {
                        //            series: {
                        //            //compare: 'percent'
                        //                name: this.name,
                        //                marker: {
                        //                    radius: 4
                        //                }
                        //            },
                                        series: {
                                                cursor: 'pointer',
                                                point: {
                                                    events: {
                                                        click: function() {
                        //                                      var stamp = Highcharts.dateFormat('%A, %b %e, %Y', this.x);
                                                              var stamp = this.x;
                                                              var status = this.series.name;

                    //                                          alert('Time was '+stamp+' Status is '+status);

                    //                                          if (status != 'Total'){
    //                                                              $('#wait').show();
    //                                                              createBarChart(status, stamp);
    //                                                              $('#wait').hide();
                    //                                          } else {
                                                                  //Do Nothing
                                                                  // $('#drill-down').hide('slowly');
                    //                                          }
                    //                                          $('#banner').hide('slowly');
                    //                                          $('#drill-down').load(page+'?status='+status,'', function(){
                    //                                            });
                        //                                    hs.htmlExpand(null, {
                        //                                        pageOrigin: {
                        //                                            x: this.pageX,
                        //                                            y: this.pageY
                        //                                        },
                        //                                        headingText: this.series.name,
                        //                                        maincontentText: Highcharts.dateFormat('%A, %b %e, %Y', this.x) +':<br/> '+
                        //                                            this.y +' issues',
                        //                                        width: 200
                        //                                    });
                                                        }
                                                    }
                                                },
                                                marker: {
                                                    lineWidth: 1
                                                }
                                            }
                                    },
                        //            legend: {
                        //                align: 'left',
                        //                verticalAlign: 'top',
                        ////                y: 20,
                        //                floating: true,
                        //                borderWidth: 0
                        //            },
                                    tooltip: {
                                        pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b><br/>',
                                        valueDecimals: 0
                                    },
                    //                legend: {
                    //                layout: 'vertical',
                    //                align: 'right',
                    //                verticalAlign: 'top',
                    //                x: -10,
                    //                y: 100,
                    //                borderWidth: 0
                    //                },
                                    series: seriesOptions
                                });
                            }

                    //}
            });
        }
    }

    $("#"+type+"loading").hide();
}

function getGradeStats(grade){
    $("#principalloading").show();
    var school_id = getUrlParameter("school_id");
    var year_id = getUrlParameter("year_id");
    var grades;
    if (grade < 0){
        grades = getSchoolGrades(school_id,year_id);
    } else {
        grades = getJasonData("action=STATSREPORT&type=grade_attendance&school_id="+school_id+"&year_id="+year_id+"&grade_id="+grade);
    }
    
    return grades;
}

function getTeacherClassStats(teacher_id, class_id){
    //$("#teacherloading").show();
    var school_id = getUrlParameter("school_id");
    var year_id = getUrlParameter("year_id");
    var classes;
    if (class_id < 0){
        classes = getTeacherSchoolClasses(school_id,year_id,teacher_id);
    } else {
        classes = getJasonData("action=STATSREPORT&type=class_attendance&school_id="+school_id+"&year_id="+year_id+"&user_id="+teacher_id+"&class_id="+class_id);
    }
    
    return classes;
}

function getTeacherSchoolClasses(school_id,year_id,teacher_id){
    var classes = getJasonData("action=GETTEACHERCLASSES&school_id="+school_id+"&year_id="+year_id+"&user_id="+teacher_id);
    return classes;
}

function getSchoolStats(school_id){
    $("#mecloading").show();
    //var school_id = getUrlParameter("school_id");
    var year_id = getUrlParameter("year_id");
    var schools;
    if (school_id < 0){
        schools = getSchoolGrades(school_id,year_id);
    } else {
        schools = getJasonData("action=STATSREPORT&type=school_attendance&school_id="+school_id+"&year_id="+year_id);
    }
    
    return schools;
}

function viewRegisterStats(){
    
}

function eventRegister(event_id){
    
    var school_id = getUrlParameter("school_id");
    if(school_id == undefined || school_id == "undefined")
    {
            school_id = $("#schools").val();
    }
    var year_id = $("#teachertimetable_year_id").val();
    //alert($("#teachertimetable_year_id").val());
    if($("#teachertimetable_year_id").val() == undefined)
    {
            year_id = getYearID();
    }
    
    window.open("pages/event_register.php?event_id="+event_id, 'Event Registration Form','width=700,height=500,toolbar=0,menubar=0,location=0,status=1,scrollbars=1,resizable=1,left=0,top=0');
    
}

function saveEventRegister(){
    
    var params = $("#frmEventRegister").serialize();
    //alert(params);
    var params_tokens = params.split("&");
    //var num_learners = params_tokens.length;
    var send_params = "";
    var send_params2 = "";
    var event_tokens = [];
    var form_fields = [];
    var found = 0;
    $.each(params_tokens, function(i, event_item) {
        event_tokens = event_item.split("=");
        form_fields[i] = event_tokens[0];
        if (typeof event_tokens[1] !== 'undefined' && event_tokens[1] !== ''){
            found++;
        } 
        //alert (event_item);
    });
    var canvas = document.getElementById("newSignature");// save canvas image as data url (png format by default)
	var signature = canvas.toDataURL("image/png");
        //alert(dataURL);
        
    var fields = form_fields.join("-");
    fields = fields.concat('-signature');
    //alert('found '+found+' length '+params_tokens.length);
    if (found == params_tokens.length){
        //alert("action=SAVEEVENTREGISTER&fields="+fields+"&"+params+"&signature="+signature);
        var save_register = getJasonData("action=SAVEEVENTREGISTER&fields="+fields+"&"+params+"&signature="+signature);
        alert('Your registration is saved - confirmation to be sent to your email - shortly');
        window.close();
    } else {
        alert('The form is incomplete - Please do not leave any field blank');
    }
}

var teacher_id = 0;
function getTeacherName(id)
{
	teacher_id = id;
	var MyRows = $('table#tblpopup').find('tbody').find('tr');
	var teachers  = getJasonData("action=GETTEACHER&id="+id);
	teachers =  jQuery.parseJSON(teachers)	;
	$.each(teachers, function(i, item) {
		//alert(item.subject_id);
		//teacherselect = teacherselect + "<option value='"+item.user_id+"'>"+item.teacher_name+"</option>";
		$(MyRows[1]).find('td:eq(1)').html(item.name+" "+item.surname);
	});
}

function getTeacherVenue(teacher_id,school_id,year_id){		
	//alert(subject_id);
	var MyRows = $('table#tblpopup').find('tbody').find('tr');
	var teacher_venue  = getJasonData("action=GETTEACHERVENUE&teacher_id="+teacher_id+"&school_id="+school_id+"&year_id="+year_id);			
	//alert(teachers);
        
        var venues  = getJasonData("action=GETROOMS&school_id="+school_id);			
	venues =  jQuery.parseJSON(venues)	;
	var venueselect = "<select id='room' name='room'>";
	venueselect = venueselect + "<option value='select'>Select Venue</option>";
	$.each(venues, function(i, item) {
		//alert(item.subject_id);
            if (teacher_venue !== 0 && teacher_venue == item[i].room_id){
		venueselect = venueselect + "<option value='"+item[i].room_id+"' selected>"+item[i].room_label+"</option>";
            } else {
                venueselect = venueselect + "<option value='"+item[i].room_id+"'>"+item[i].room_label+"</option>";
            }
		
	});
	venueselect = venueselect + "</select>";
	//alert(teacherselect);
	//alert($("#teachers").html());
	$("#room").html(venueselect);
	//$(MyRows[1]).find('td:eq(1)').html(teacherselect);
}

function getSubjectTeacher(subject_id,school_id,year_id){		
	//alert(subject_id);
	var MyRows = $('table#tblpopup').find('tbody').find('tr');
	var teachers  = getJasonData("action=GETSUBJECTTEACHER&subject_id="+subject_id+"&school_id="+school_id+"&year_id="+year_id);			
	//alert(teachers);
	teachers =  jQuery.parseJSON(teachers)	;
	var teacherselect = "<select id='teacherlist' name='teacherlist' onchange='getTeacherName(this.value)'";
	teacherselect = teacherselect + "<option value='select'>Select Teacher</option>";
	$.each(teachers, function(i, item) {
		//alert(item.subject_id);
		teacherselect = teacherselect + "<option value='"+item.user_id+"'>"+item.teacher_name+"</option>";
		
	});
	teacherselect = teacherselect + "</select>";
	//alert(teacherselect);
	//alert($("#teachers").html());
	$("#teachers").html(teacherselect);
	//$(MyRows[1]).find('td:eq(1)').html(teacherselect);
}

function viewLearnerMerits(user_id){
    
    var school_id = getUrlParameter("school_id");
    if(school_id == undefined || school_id == "undefined")
    {
            school_id = $("#schools").val();
    }
    var year_id = $("#teachertimetable_year_id").val();
    //alert($("#teachertimetable_year_id").val());
    if($("#teachertimetable_year_id").val() == undefined)
    {
            year_id = getYearID();
    }
    
    var merits = getJasonData("action=VIEWLEARNERMERITS&learner_id="+user_id+"&school_id="+school_id+"&year_id="+year_id);
    $('#view_learner_merits').html("");
    
    $('#viewLearnerMeritsDlg').dialog({title: 'View Learner Merits'});
    
    $('#viewLearnerMeritsDlg').dialog('open').dialog('center').dialog('setTitle', 'View Learner Merits');
    
    $('#view_learner_merits').html(merits);
    
}

function addLearnerMerits(learner_id){
    
    var monthShortNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
  "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0!
    //alert('Month '+mm);
    var yyyy = today.getFullYear();

    if(dd<10) {
            dd='0'+dd
    } 
//
//    if(mm<10) {
//            mm='0'+mm
//    }
    
    //alert('Month '+mm);
    mm = monthShortNames[mm-1];
    today = dd+'-'+mm+'-'+yyyy;
    //alert(today);
    var year_id = getUrlParameterPlain("year_id");
    var school_id = getUrlParameterPlain("school_id");
    //alert(year_id + " " + school_id);
    var _class = getUrlParameterPlain("class");
    var subject = getUrlParameterPlain("subject");
    var theTimeslot = getUrlParameterPlain("timeslot");			
    //alert(theTimeslot);
    var timeslot = theTimeslot.split('<br>');
    var thetime = timeslot[0].split('~');
    //alert(timeslot[1]);
    var day = thetime[1];
    day = day.replace("<b>","");
    day = day.replace("</b>","");
    var period = timeslot[2].split("~");

    //alert ('PERIOD '+period[0]+' TODAY '+today);
    
    if(period[0] != today)
    {
            alert("Can only give MERITS for today's classes");
            return;
    }

    //alert(timeslot[1]);
    var day = thetime[1];
    day = day.replace("<b>","");
    day = day.replace("</b>","");
    var period = timeslot[2].split("~");
                
    $("#loading").show();
    
    var school_id = getUrlParameter("school_id");
    if(school_id == undefined || school_id == "undefined")
    {
            school_id = $("#schools").val();
    }
    var year_id = $("#teachertimetable_year_id").val();
    //alert($("#teachertimetable_year_id").val());
    if($("#teachertimetable_year_id").val() == undefined)
    {
            year_id = getYearID();
    }
    
   //alert("action=SELECTMERITS&date="+period[0]+"&period="+period[2]+"&day="+day+"&teacher_id="+period[1]+"&learner_id="+learner_id+"&school_id="+school_id+"&year_id="+year_id);
    
     var merits = getJasonData("action=SELECTMERITS&date="+period[0]+"&period="+period[2]+"&day="+day+"&teacher_id="+period[1]+"&learner_id="+learner_id+"&school_id="+school_id+"&year_id="+year_id);
    $('#select_learner_merits').html("");
    
    $('#addLearnerMeritsDlg').dialog({title: 'Add Learner Merits'});
    
    $('#addLearnerMeritsDlg').dialog('open').dialog('center').dialog('setTitle', 'Add Learner Merits');
    
    $('#select_learner_merits').html(merits);
    
    $("#loading").hide();
}	

function SaveLearnerMerits(){
    
    $("#teacherloading").show();
    
    var school_id = getUrlParameter("school_id");
    if(school_id == undefined || school_id == "undefined")
    {
            school_id = $("#schools").val();
    }
    var year_id = $("#teachertimetable_year_id").val();
    //alert($("#teachertimetable_year_id").val());
    if($("#teachertimetable_year_id").val() == undefined)
    {
            year_id = getYearID();
    }
    
    var params = $("#frmAddLearnerMerits").serialize();
    var params_tokens = params.split("&");
    //var num_learners = params_tokens.length;
    var send_params = "";
    var send_params2 = "";
    var learner_tokens = [];
    $.each(params_tokens, function(i, learner) {
        learner_tokens = learner.split("=");
        if (learner_tokens[0] == 'merit_id'){
            send_params2 = learner.concat('&',send_params);
            var adding_learner_merits = getJasonData("action=ADDLEARNERMERITS&"+send_params2+"&school_id="+school_id+"&year_id="+year_id);
            //alert("action=ADDLEARNERTOCLASS&"+send_params2+"&school_id="+school_id+"&year_id="+year_id);
            
        } else {
            send_params = send_params.concat('&',learner);
        }
    });

    //alert ('GradeID '+add_grade_id+'ClassID '+add_class_id+'Num Learners '+num_learners+' PARAMS '+params);
    
    $('#addLearnerMeritsDlg').dialog('close');
    
    $("#teacherloading").hide();
    
    location.reload();
}

function viewLearnerDemerits(user_id){
    
    var school_id = getUrlParameter("school_id");
    if(school_id == undefined || school_id == "undefined")
    {
            school_id = $("#schools").val();
    }
    var year_id = $("#teachertimetable_year_id").val();
    //alert($("#teachertimetable_year_id").val());
    if($("#teachertimetable_year_id").val() == undefined)
    {
            year_id = getYearID();
    }
    
    var demerits = getJasonData("action=VIEWLEARNERDEMERITS&learner_id="+user_id+"&school_id="+school_id+"&year_id="+year_id);
    $('#view_learner_demerits').html("");
    
    $('#viewLearnerDemeritsDlg').dialog({title: 'View Learner Demerits'});
    
    $('#viewLearnerDemeritsDlg').dialog('open').dialog('center').dialog('setTitle', 'View Learner Demerits');
    
    $('#view_learner_demerits').html(demerits);
}

function addLearnerDemerits(learner_id){
    
    var monthShortNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
  "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth()+1; //January is 0!
    //alert('Month '+mm);
    var yyyy = today.getFullYear();

    if(dd<10) {
            dd='0'+dd
    } 
//
//    if(mm<10) {
//            mm='0'+mm
//    }
    
    //alert('Month '+mm);
    mm = monthShortNames[mm-1];
    today = dd+'-'+mm+'-'+yyyy;
    //alert(today);
    var year_id = getUrlParameterPlain("year_id");
    var school_id = getUrlParameterPlain("school_id");
    //alert(year_id + " " + school_id);
    var _class = getUrlParameterPlain("class");
    var subject = getUrlParameterPlain("subject");
    var theTimeslot = getUrlParameterPlain("timeslot");			
    //alert(theTimeslot);
    var timeslot = theTimeslot.split('<br>');
    var thetime = timeslot[0].split('~');
    //alert(timeslot[1]);
    var day = thetime[1];
    day = day.replace("<b>","");
    day = day.replace("</b>","");
    var period = timeslot[2].split("~");

    //alert ('PERIOD '+period[0]+' TODAY '+today);
    
    if(period[0] != today)
    {
            alert("Can only give DEMERITS for today's classes");
            return;
    }

    //alert(timeslot[1]);
    var day = thetime[1];
    day = day.replace("<b>","");
    day = day.replace("</b>","");
    var period = timeslot[2].split("~");
                
    $("#loading").show();
    
    var school_id = getUrlParameter("school_id");
    if(school_id == undefined || school_id == "undefined")
    {
            school_id = $("#schools").val();
    }
    var year_id = $("#teachertimetable_year_id").val();
    //alert($("#teachertimetable_year_id").val());
    if($("#teachertimetable_year_id").val() == undefined)
    {
            year_id = getYearID();
    }
    
    //alert('GradeID '+grade_id+' ClassID '+class_id);
    
     var demerits = getJasonData("action=SELECTDEMERITS&date="+period[0]+"&period="+period[2]+"&day="+day+"&teacher_id="+period[1]+"&learner_id="+learner_id+"&school_id="+school_id+"&year_id="+year_id);
    $('#select_learner_demerits').html("");
    
    $('#addLearnerDemeritsDlg').dialog({title: 'Add Learner Demerit'});
    
    $('#addLearnerDemeritsDlg').dialog('open').dialog('center').dialog('setTitle', 'Add Learner Demerit');
    
    $('#select_learner_demerits').html(demerits);
    
    $("#loading").hide();
    
}

function SaveLearnerDemerits(){
    
    $("#loading").show();
    
    var school_id = getUrlParameter("school_id");
    if(school_id == undefined || school_id == "undefined")
    {
            school_id = $("#schools").val();
    }
    var year_id = $("#teachertimetable_year_id").val();
    //alert($("#teachertimetable_year_id").val());
    if($("#teachertimetable_year_id").val() == undefined)
    {
            year_id = getYearID();
    }
    
    var params = $("#frmAddLearnerDemerits").serialize();
    var params_tokens = params.split("&");
    //var num_learners = params_tokens.length;
    var send_params = "";
    var send_params2 = "";
    var learner_tokens = [];
    $.each(params_tokens, function(i, learner) {
        learner_tokens = learner.split("=");
        if (learner_tokens[0] == 'demerit_id'){
            send_params2 = learner.concat('&',send_params);
            var adding_learner_demerits = getJasonData("action=ADDLEARNERDEMERITS&"+send_params2+"&school_id="+school_id+"&year_id="+year_id);
            //alert("action=ADDLEARNERTOCLASS&"+send_params2+"&school_id="+school_id+"&year_id="+year_id);
            
        } else {
            send_params = send_params.concat('&',learner);
        }
    });

    //alert ('GradeID '+add_grade_id+'ClassID '+add_class_id+'Num Learners '+num_learners+' PARAMS '+params);
    
    $('#addLearnerDemeritsDlg').dialog('close');
    
    $("#loading").hide();
    
    location.reload();
}
/***************De

END ROM PHP FILE

***************/		

function getstudents()
{
	var xmlhttp;
	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	}
	else {// code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	var url = "process.php?action=GETSTUDENTS&t=" + Math.random();
	//alert(url);
	xmlhttp.open("GET", url, true);
	xmlhttp.send();
	students =  xmlhttp.responseText;
	
}

function setTab() {
    var tab = $('#tabs').tabs('getSelected');
	var index = $('#tabs').tabs('getTabIndex', tab);
	//alert(index);
	
	if(index== 2)
	{
		SettingsPerRotation();
		classSettings = [];
	}
	var user_type = getUrlParameter("user_type");
	if(index== 3 || (index== 2 && (user_type == 5 || user_type == 6)))
	{
		//getSubjectData();
		
		//var students = getJasonData("action=GETSTUDENTS");	
		//alert(students);
		setStudentSettings();
		var dg = $('#students');
		/*$('#students').datagrid({singleSelect:false,remoteFilter:true,enableFilter:true, 
					onLoadSuccess:function(data){
					  
						//var rows = $('#students').datagrid('getRows');
						//for(var i=0; i<rows.length; i++){
						//	$('#students').datagrid('beginEdit', i);
						//}
				   },
				   onCheck:function(index,row){onStudentClickCell(index,row);}});*/
				   
		//alert("this is the tab " + index);
		
		$('#students').datagrid('enableFilter'[{
				field:'name',
				type:'textbox',
				options:{precision:1},
				op:['equal','notequal','less','greater']
			}]);
		
	}
	//alert("here again");
}

var getUrlParameterPlain = function getUrlParameterPlain(sParam) {
		
		var sPageURL = decodeURIComponent(window.location.search.substring(1)),
		sURLVariables = sPageURL.split('&'),
		sParameterName,
		i;

		for (i = 0; i < sURLVariables.length; i++) {
			sParameterName = sURLVariables[i].split('=');

			if (sParameterName[0] === sParam) {
				return sParameterName[1] === undefined ? true : sParameterName[1];
			}
		}
	};

var getUrlParameter = function getUrlParameter(sParam) {
		var url = window.location.href.slice(window.location.href.indexOf('?') + 1).split("=");
		
		//alert(decUrl(url[1]));
		var durl = decUrl(url[1]);
		
		var urlvars = durl.split('&');
		/*var sPageURL = decodeURIComponent(window.location.search.substring(1)),
		sURLVariables = sPageURL.split('&'),*/
		var sParameterName,
		i;

		for (i = 0; i < urlvars.length; i++) {
			sParameterName = urlvars[i].split('=');

			if (sParameterName[0] === sParam) {
				return sParameterName[1] === undefined ? true : sParameterName[1];
			}
		}
		
		return getUrlParameterPlain(sParam);
	};
	
function next()
{
	//alert("In Next");
	var tab = $('#tabs').tabs('getSelected');
	var index = $('#tabs').tabs('getTabIndex', tab);            
	var indx = index + 1;
	//lert(indx);
	if($('#tabs').tabs('exists', indx))
	{
		$('#tabs').tabs('enableTab', indx);
		//alert(indx);
		$('#tabs').tabs('select', indx); // switch to third tab
		setTab();
	}
	//alert("Left next");
	return false;
}	

function previous()
{
	var tab = $('#tabs').tabs('getSelected');
	var index = $('#tabs').tabs('getTabIndex', tab);
	var indx = index - 1;
	if($('#tabs').tabs('exists', indx))
	{
		$('#tabs').tabs('enableTab', indx);
		$('#tabs').tabs('select', indx); // switch to third tab
		setTab();
	}
	return false;
}



function getJasonData(params) {
	//alert(params);
	var xmlhttp;
	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	}
	else {// code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	var url = "api/process.php?" + params + "&t=" + Math.random();
	//alert(url);
	xmlhttp.open("GET", url, false);
	xmlhttp.send();
	return xmlhttp.responseText;
}

function sendDataByGet(params,tofile) {
	//alert(params);
	var xmlhttp;
	if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		xmlhttp = new XMLHttpRequest();
	}
	else {// code for IE6, IE5
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	}
	var url = tofile+"?" + params + "&t=" + Math.random();
	//alert(url);
	xmlhttp.open("GET", url, false);
	xmlhttp.send();
	//alert(xmlhttp.responseText);
	return xmlhttp.responseText;
}


function getSchoolData(school_id)
{
	//alert("here we are bhfcsdkhdg");
	var setting = getJasonData("action=GETSCHOOLSETTING&school_id="+school_id);	
	//alert(setting);
	setting =  jQuery.parseJSON(setting);
	var school = getJasonData("action=GETSCHOOLINFO&school_id="+school_id);		
	//alert(school);
	school =  jQuery.parseJSON(school)	;
	
	if (setting.length == 0) {
		$("#instructions").html(" The timetable settings are not captured ");
	}
	var from_grade = 0;
	var to_grade = 1;
	//alert("Start here");
	$.each(setting, function(i, item) {
		var data = setting[i].split("=");
		if(data[0] == "from_grade")
		{
			//alert(data[1]);			
			from_grade = data[1];
			if(data[1] == "R")from_grade = "0";
		}
		if(data[0] == "to_grade")
		{
			$("#_from_grade").html($("#_from_grade").html() +" to "+ data[1]);
			to_grade = data[1];
		}
		else{
			$("#_"+data[0]).html(data[1]);
			if(data[0] == "rotation_type")
			{
				whorotates = data[1];
				$("#_selected_rotation").html(whorotates);
			}
		}
		if(data[0] == "break_times"){
			
			var breaks = data[1].split("*");
			for(i =0;i<breaks.length;i++)
			{
				var times = breaks[i].split("!");
				//alert( times);
				$("#_break_"+(i+1)).html("At " + times[1] + " for " + times[2] + " minutes");
				$("#break_time_"+(i+1)).val(times[1]);
				$("#break_length_"+(i+1)).val(times[2]);
				$("#row_break_"+(i+1)).show();
			}
		}
		
		$("#"+data[0]).val(data[1]);
		$("#school_id").val(school_id);
	});
	
	if(typeof from_grade == "string" && from_grade == "R")
	{
		//alert("from Grade " +from_grade);
		from_grade = 0;
	}
	else{
		from_grade = parseInt(from_grade);
	}
	//alert(from_grade);
	to_grade = parseInt(to_grade);
	$('#teachertimetable_grade_id').html("");	
	$('#teachertimetable_grade_id').append(
			$('<option></option>').val("All").html("All")
		); 
		
	$('#add_student_grade').html("");
	$('#add_student_grade').append(
			$('<option></option>').val("All").html("All")
		); 
	for(i = from_grade;i<=to_grade;i++)
	{
		//alert(i);
		if(from_grade == "0" && i == from_grade)
		{
			$('#teachertimetable_grade_id').append(
				$('<option></option>').val(i).html("R")
			);
			
			$('#add_student_grade').append(
				$('<option></option>').val(i).html("R")
			);
		}
		else{
			$('#teachertimetable_grade_id').append(
				$('<option></option>').val(i).html(i)
			);
			
			$('#add_student_grade').append(
				$('<option></option>').val(i).html(i)
			);
		}
		 //alert(i);
	}
	var school_name = school.school_name;
	$("#_schoolname").html(school_name +" Timetable Settings" );
	
	/*$.each(school, function(i, item) {
		$("#_schoolname").html(school[i].school_name);
	});*/
	accessControl();
} 

function saveSettings1()
{
	//alert($("#from_grade").val() );
	//alert($("#to_grade").val() );
	
	var school_id = getUrlParameter("school_id");
	if(school_id == undefined || school_id == "undefined")
	{
		school_id = $("#schools").val();
	}		
	if($("#from_grade").val() == "")
	{
		alert("Please select From Grade");
	}
	else
	 if($("#to_grade").val() == "")
	 {
		alert("Please select To Grade");
	 }
	 else{
		whorotates = $("#rotation_type").val();
		//alert($("#frmSchoolInfo").serialize());
		//alert($("#frmSchoolInfo").serialize()+"&save_type=save_general_timetable_settings");
		var param = $("#frmSchoolInfo").serialize()+"&save_type=save_general_timetable_settings";		
		var school_id = getUrlParameter("school_id");
		if(school_id == undefined || school_id == "undefined")
		{
			school_id = $("#schools").val();
		}		
		param = param + "&school_id="+school_id;
		//alert(param);
		sendDataByGet(param,"timetable_settings_save.php");
		getSchoolData(school_id);
	}
}

function saveSubjectSettings()
{
	//alert($("#frmSubjectSetting").serialize()+"&school_id="+$("#schools").val()+ "&grade_id="+$("#grade_id").val());
	var school_id = getUrlParameter("school_id");
	if(school_id == undefined || school_id == "undefined")
	{
		school_id = $("#schools").val();
	}			
	
	var grade;
	if($("#grade_id").val() == "R")
	{
		grade = "0";
	}
	else{
		grade = $("#grade_id").val();
	}
	//var value = $("#frmSubjectSetting").serialize()+"&school_id="+school_id+ "&grade_id="+$("#grade_id").val();
	var value = $("#frmSubjectSetting").serialize()+"&school_id="+school_id+ "&grade_id="+grade;
	//alert(value);
	sendDataByGet(value,"timetable_subject_settings_save.php");
	getSchoolData(school_id);
}

function getTeacherGrades(user_id,school_id,year_id)
{
	var grades = getJasonData("action=GETTEACHERGRADES&user_id="+user_id+"&school_id="+school_id+"&year_id="+year_id);
        //alert(timetableid);

    grades =  jQuery.parseJSON(grades);
    $('#student_grade1').html("");
     $('#student_grade1').append(
            $('<option></option>').val('All').html('All')
            );
     $.each(classes, function(i, item) {
            //alert(timetableid[i].timetabl_id);

        $('#student_grade1').append(
                        $('<option></option>').val(item.grade_id).html(item.grade_title)
                );
    });

    $("#loading").hide();
    getTeacherGradeClasses(user_id,school_id,year_id,'All');
	
}

function getSubjectData()
{
	//alert($("#_from_grade").html());
	var fromto = $("#_from_grade").html();
	//alert(fromto);
	var data = fromto.split(" to ");
	//alert(data[1]);
	//alert(typeof data[0]);
	var start;
	if(data[0] == "R")
	{
		start = 0;
	}
	else{ 
		start = parseInt(data[0]);
	}
	var end = parseInt(data[1]);
	//for (i = data[0]; i <= data[1]; i++)
	$('#grade_id').html("");
	$('#student_grade').html("");
        $('#student_grade1').html("");
	$('#add_student_grade').html("");
	$('#teacher_grade').html("");
	$('#teacher_grade_1').html("");
        $('#teacher_grade_2').html("");
        $('#teacher_grade_3').html("");
        $('#teacher_grade_4').html("");
        $('#teacher_grade_5').html("");
        $('#teacher_grade_6').html("");
        $('#teacher_grade_7').html("");
        $('#teacher_grade_8').html("");
        $('#teacher_grade_9').html("");
        $('#teacher_grade_10').html("");
        $('#teacher_grade_11').html("");
        $('#teacher_grade_12').html("");
        $('#teacher_grade_13').html("");
        $('#teacher_grade_14').html("");
        $('#teacher_grade_15').html("");
        $('#teacher_grade_16').html("");
	//alert("But We are here");
	//alert(start);
	//alert(end);
        $('#student_grade1').append(
                $('<option></option>').val('All').html('All')
        ); 

	for (i = start; i <= end; i++)
	{
		var z = i;
		if(i == "0")
		{
			z = "R";
		}
		$('#grade_id').append(
			$('<option></option>').val(z).html(z)
		); 
			
		$('#student_grade').append(
			$('<option></option>').val(z).html(z)
		); 
        
                
        
                $('#student_grade1').append(
			$('<option></option>').val(z).html(z)
		); 
		
		$('#add_student_grade').append(
			$('<option></option>').val(z).html(z)
		);
		
		$('#teacher_grade').append(
			$('<option></option>').val(z).html(z)
		);
        
                $('#teacher_grade_1').append(
			$('<option></option>').val(z).html(z)
		);
        
                $('#teacher_grade_2').append(
			$('<option></option>').val(z).html(z)
		);
        
                $('#teacher_grade_3').append(
			$('<option></option>').val(z).html(z)
		);
        
                $('#teacher_grade_4').append(
			$('<option></option>').val(z).html(z)
		);
        
                $('#teacher_grade_5').append(
			$('<option></option>').val(z).html(z)
		);
        
                $('#teacher_grade_6').append(
			$('<option></option>').val(z).html(z)
		);
        
                $('#teacher_grade_7').append(
			$('<option></option>').val(z).html(z)
		);
        
                $('#teacher_grade_8').append(
			$('<option></option>').val(z).html(z)
		);
        
                $('#teacher_grade_9').append(
			$('<option></option>').val(z).html(z)
		);
        
                $('#teacher_grade_10').append(
			$('<option></option>').val(z).html(z)
		);
        
                $('#teacher_grade_11').append(
			$('<option></option>').val(z).html(z)
		);
        
                $('#teacher_grade_12').append(
			$('<option></option>').val(z).html(z)
		);
        
                $('#teacher_grade_13').append(
			$('<option></option>').val(z).html(z)
		);
        
                $('#teacher_grade_14').append(
			$('<option></option>').val(z).html(z)
		);
        
                $('#teacher_grade_15').append(
			$('<option></option>').val(z).html(z)
		);
        
                $('#teacher_grade_16').append(
			$('<option></option>').val(z).html(z)
		);
	}
        
        getClass('All');
	//alert("here 1");
	next();
	//alert("here 2");
}

//EVENTS

function manageEventRegisterForm(event_id){
    
    var school_id = getUrlParameter("school_id");
    if(school_id == undefined || school_id == "undefined")
    {
            school_id = $("#schools").val();
    }
    var year_id = $("#teachertimetable_year_id").val();
    //alert($("#teachertimetable_year_id").val());
    if($("#teachertimetable_year_id").val() == undefined)
    {
            year_id = getYearID();
    }
    
    window.open("pages/event_register.php?event_id="+event_id+'&type=manage', 'Manage Registration Form','width=700,height=500,toolbar=0,menubar=0,location=0,status=1,scrollbars=1,resizable=1,left=0,top=0');
    
}

function viewEventRegister(event_id){
    
    var school_id = getUrlParameter("school_id");
    if(school_id == undefined || school_id == "undefined")
    {
            school_id = $("#schools").val();
    }
    var year_id = $("#teachertimetable_year_id").val();
    //alert($("#teachertimetable_year_id").val());
    if($("#teachertimetable_year_id").val() == undefined)
    {
            year_id = getYearID();
    }
    
    window.open("pages/event_register.php?event_id="+event_id+'&type=view', 'View Registration','width=700,height=500,toolbar=0,menubar=0,location=0,status=1,scrollbars=1,resizable=1,left=0,top=0');
    
}

function manageVenue(event_id){
    //GET VENUESETTINGS
}

function getReports(year_id, month_id, day_id){
    
    $('#reports').html("");
    
    $("#reportsloading").show();
    
    var school_id = getUrlParameter("school_id");
    if(school_id == undefined || school_id == "undefined")
    {
            school_id = $("#schools").val();
    }
    var year_id = $("#teachertimetable_year_id").val();
    //alert($("#teachertimetable_year_id").val());
    if($("#teachertimetable_year_id").val() == undefined)
    {
            year_id = getYearID();
    }
    var reports = getJasonData("action=GETREPORTS&year_id="+year_id+"&month_id="+month_id+"&day_id="+day_id+"&school_id="+school_id);

   
    //alert ('EVENTS '+events);
    
    $('#reports').html(reports);
    $("#reportsloading").hide();
}

function buildReport(year_id, month_id, day_id){
    
    $('#events').html("");
    
    $("#eventsloading").show();
    
    var school_id = getUrlParameter("school_id");
    if(school_id == undefined || school_id == "undefined")
    {
            school_id = $("#schools").val();
    }
    var year_id = $("#teachertimetable_year_id").val();
    //alert($("#teachertimetable_year_id").val());
    if($("#teachertimetable_year_id").val() == undefined)
    {
            year_id = getYearID();
    }
    var events = getJasonData("action=GETEVENTS&year_id="+year_id+"&month_id="+month_id+"&day_id="+day_id+"&school_id="+school_id);

   
    //alert ('EVENTS '+events);
    
    $('#events').html(events);
    $("#eventsloading").hide();
}

function buildEventReport(event_id){
    
//    $('#events').html("");
//    
//    $("#eventsloading").show();
    
    var school_id = getUrlParameter("school_id");
    if(school_id == undefined || school_id == "undefined")
    {
            school_id = $("#schools").val();
    }
    var year_id = $("#teachertimetable_year_id").val();
    //alert($("#teachertimetable_year_id").val());
    if($("#teachertimetable_year_id").val() == undefined)
    {
            year_id = getYearID();
    }
    
    window.open("pages/event_register.php?event_id="+event_id+'&type=report', 'Event Report','width=700,height=500,toolbar=0,menubar=0,location=0,status=1,scrollbars=1,resizable=1,left=0,top=0');

   
//    //alert ('EVENTS '+events);
//    
//    $('#events').html(events);
//    $("#eventsloading").hide();
}

function getEvents(year_id, month_id, day_id){
    
    $('#events').html("");
    
    $("#eventsloading").show();
    
    var school_id = getUrlParameter("school_id");
    if(school_id == undefined || school_id == "undefined")
    {
            school_id = $("#schools").val();
    }
    var year_id = $("#teachertimetable_year_id").val();
    //alert($("#teachertimetable_year_id").val());
    if($("#teachertimetable_year_id").val() == undefined)
    {
            year_id = getYearID();
    }
    var events = getJasonData("action=GETEVENTS&year_id="+year_id+"&month_id="+month_id+"&day_id="+day_id+"&school_id="+school_id);

   
    //alert ('EVENTS '+events);
    
    $('#events').html(events);
    $("#eventsloading").hide();
}
//END EVENTS

function getTeacherGradeClasses(user_id,school_id,year_id,grade_id){

    $("#loading").show();

    var school_id = getUrlParameter("school_id");
    if(school_id == undefined || school_id == "undefined")
    {
            school_id = $("#schools").val();
    }
    var year_id = $("#teachertimetable_year_id").val();
    //alert($("#teachertimetable_year_id").val());
    if($("#teachertimetable_year_id").val() == undefined)
    {
            year_id = getYearID();
    }

    //alert("action=GETTEACHERGRADECLASSES&user_id="+user_id+"&grade_id="+grade_id+"&school_id="+school_id+"&year_id="+year_id);
    var classes = getJasonData("action=GETTEACHERGRADECLASSES&user_id="+user_id+"&grade_id="+grade_id+"&school_id="+school_id+"&year_id="+year_id);
        //alert(timetableid);

    classes =  jQuery.parseJSON(classes);
    $('#class_id').html("");
     $('#class_id').append(
            $('<option></option>').val('All').html('All')
    );
    $.each(classes, function(i, item) {
            //alert(timetableid[i].timetabl_id);

        $('#class_id').append(
                        $('<option></option>').val(item.class_id).html(item.class_label)
                );
    });

    $("#loading").hide();
    getTeacherGradeClassList(user_id,school_id,year_id,grade_id, 'All');
    //var classes = getJasonData("action=GETGRADECLASSES&grade_id="+grade_id+"&school_id="+school_id+"&year_id="+year_id);
}


function getClass(grade_id){

    $("#loading").show();
    
    var school_id = getUrlParameter("school_id");
    if(school_id == undefined || school_id == "undefined")
    {
            school_id = $("#schools").val();
    }
    var year_id = $("#teachertimetable_year_id").val();
    //alert($("#teachertimetable_year_id").val());
    if($("#teachertimetable_year_id").val() == undefined)
    {
            year_id = getYearID();
    }

    //alert("action=GETGRADECLASSES&grade_id="+grade_id+"&school_id="+school_id+"&year_id="+year_id);
    var classes = getJasonData("action=GETGRADECLASSES&grade_id="+grade_id+"&school_id="+school_id+"&year_id="+year_id);
	//alert(timetableid);
	
    classes =  jQuery.parseJSON(classes);		
    $('#class_id').html("");
     $('#class_id').append(
            $('<option></option>').val('All').html('All')
    );
    $.each(classes, function(i, item) {
            //alert(timetableid[i].timetabl_id);
            
        $('#class_id').append(
                        $('<option></option>').val(item.class_id).html(item.class_label)
                ); 
    });
    
    $("#loading").hide();
    getGradeClassList(grade_id, 'All');
    //var classes = getJasonData("action=GETGRADECLASSES&grade_id="+grade_id+"&school_id="+school_id+"&year_id="+year_id);
}

function getTeacherGradeClassList(user_id,school_id,year_id,grade_id, class_id){
   
    $('#classlists').html("");
   
    $("#loading").show();
   
    var school_id = getUrlParameter("school_id");
    if(school_id == undefined || school_id == "undefined")
    {
            school_id = $("#schools").val();
    }
    var year_id = $("#teachertimetable_year_id").val();
    //alert($("#teachertimetable_year_id").val());
    if($("#teachertimetable_year_id").val() == undefined)
    {
            year_id = getYearID();
    }
    var classlist = getJasonData("action=GETTEACHERGRADECLASSLIST&grade_id="+grade_id+"&class_id="+class_id+"&school_id="+school_id+"&year_id="+year_id);


    $('#classlists').html(classlist);
    $("#loading").hide();
}


function getGradeClassList(grade_id, class_id){
    
    $('#classlists').html("");
    
    $("#loading").show();
    
    var school_id = getUrlParameter("school_id");
    if(school_id == undefined || school_id == "undefined")
    {
            school_id = $("#schools").val();
    }
    var year_id = $("#teachertimetable_year_id").val();
    //alert($("#teachertimetable_year_id").val());
    if($("#teachertimetable_year_id").val() == undefined)
    {
            year_id = getYearID();
    }
    var classlist = getJasonData("action=GETGRADECLASSLIST&grade_id="+grade_id+"&class_id="+class_id+"&school_id="+school_id+"&year_id="+year_id);

    
    $('#classlists').html(classlist);
    $("#loading").hide();
}

function addClassTeacher(grade_id,class_id){
    
    $("#loading").show();
    
    var school_id = getUrlParameter("school_id");
    if(school_id == undefined || school_id == "undefined")
    {
            school_id = $("#schools").val();
    }
    var year_id = $("#teachertimetable_year_id").val();
    //alert($("#teachertimetable_year_id").val());
    if($("#teachertimetable_year_id").val() == undefined)
    {
            year_id = getYearID();
    }
    
    //alert('GradeID '+grade_id+' ClassID '+class_id);
    
     var learners = getJasonData("action=SELECTTEACHER&grade_id="+grade_id+"&class_id="+class_id+"&school_id="+school_id+"&year_id="+year_id);
    $('#select_teacher').html("");
    
    $('#addTeacherDlg').dialog({title: 'Add Class Teacher'});
    
    $('#addTeacherDlg').dialog('open').dialog('center').dialog('setTitle', 'Add Class Teacher');
    
    $('#select_teacher').html(learners);
    
    $("#loading").hide();
}

function removeTeacherClass(grade_id,class_id,teacher_id){
    
    $("#loading").show();
    
    var school_id = getUrlParameter("school_id");
    if(school_id == undefined || school_id == "undefined")
    {
            school_id = $("#schools").val();
    }
    var year_id = $("#teachertimetable_year_id").val();
    //alert($("#teachertimetable_year_id").val());
    if($("#teachertimetable_year_id").val() == undefined)
    {
            year_id = getYearID();
    }
    
    var remove_learner = getJasonData("action=REMOVETEACHERCLASS&teacher_id="+teacher_id+"&grade_id="+grade_id+"&class_id="+class_id+"&school_id="+school_id+"&year_id="+year_id);
    
    getGradeClassList(grade_id, class_id);
    
    $("#loading").hide();
}

function SaveClassTeacher(){
    $("#loading").show();
    
    var school_id = getUrlParameter("school_id");
    if(school_id == undefined || school_id == "undefined")
    {
            school_id = $("#schools").val();
    }
    var year_id = $("#teachertimetable_year_id").val();
    //alert($("#teachertimetable_year_id").val());
    if($("#teachertimetable_year_id").val() == undefined)
    {
            year_id = getYearID();
    }
    
    var grade_id = $("#student_grade1").val();
    var class_id = $("#class_id").val();
    
    var add_grade_id = $("#add_grade_id").val();
    var add_class_id = $("#add_class_id").val();
    
    var params = $("#frmSaveTeacher").serialize();
    //var params_tokens = params.split("&");
    //var num_learners = params_tokens.length;
    var adding_to_class = getJasonData("action=ADDTEACHERTOCLASS&"+params+"&school_id="+school_id+"&year_id="+year_id);
    
    $('#addTeacherDlg').dialog('close');
    
    getGradeClassList(grade_id, class_id);
    
    $("#loading").hide();
    
}

function addLearnersToClass(grade_id,class_id){
    
    $("#loading").show();
    
    var school_id = getUrlParameter("school_id");
    if(school_id == undefined || school_id == "undefined")
    {
            school_id = $("#schools").val();
    }
    var year_id = $("#teachertimetable_year_id").val();
    //alert($("#teachertimetable_year_id").val());
    if($("#teachertimetable_year_id").val() == undefined)
    {
            year_id = getYearID();
    }
    
    //alert('GradeID '+grade_id+' ClassID '+class_id);
    
     var learners = getJasonData("action=SELECTLEARNERS&grade_id="+grade_id+"&class_id="+class_id+"&school_id="+school_id+"&year_id="+year_id);
    $('#select_learners').html("");
    
    $('#addLearnersDlg').dialog({title: 'Add Learners To Class'});
    
    $('#addLearnersDlg').dialog('open').dialog('center').dialog('setTitle', 'Add Learners To Class');
    
    $('#select_learners').html(learners);
    
    $("#loading").hide();
}

function removeLearnerClass(grade_id,class_id,learner_id){
    
    $("#loading").show();
    
    var school_id = getUrlParameter("school_id");
    if(school_id == undefined || school_id == "undefined")
    {
            school_id = $("#schools").val();
    }
    var year_id = $("#teachertimetable_year_id").val();
    //alert($("#teachertimetable_year_id").val());
    if($("#teachertimetable_year_id").val() == undefined)
    {
            year_id = getYearID();
    }
    
    var remove_learner = getJasonData("action=REMOVELEARNERCLASS&learner_id="+learner_id+"&grade_id="+grade_id+"&class_id="+class_id+"&school_id="+school_id+"&year_id="+year_id);
    
    getGradeClassList(grade_id, class_id);
    
    $("#loading").hide();
}

function SaveLearnersToClass(){
    $("#loading").show();
    
    var school_id = getUrlParameter("school_id");
    if(school_id == undefined || school_id == "undefined")
    {
            school_id = $("#schools").val();
    }
    var year_id = $("#teachertimetable_year_id").val();
    //alert($("#teachertimetable_year_id").val());
    if($("#teachertimetable_year_id").val() == undefined)
    {
            year_id = getYearID();
    }
    
    var grade_id = $("#student_grade1").val();
    var class_id = $("#class_id").val();
    
    var add_grade_id = $("#add_grade_id").val();
    var add_class_id = $("#add_class_id").val();
    
    var params = $("#frmSaveLearners").serialize();
    var params_tokens = params.split("&");
    //var num_learners = params_tokens.length;
    var send_params = "";
    var send_params2 = "";
    var learner_tokens = [];
    $.each(params_tokens, function(i, learner) {
        learner_tokens = learner.split("=");
        if (learner_tokens[0] == 'learner_id'){
            send_params2 = learner.concat('&',send_params);
            var adding_to_class = getJasonData("action=ADDLEARNERTOCLASS&"+send_params2+"&school_id="+school_id+"&year_id="+year_id);
            //alert("action=ADDLEARNERTOCLASS&"+send_params2+"&school_id="+school_id+"&year_id="+year_id);
            
        } else {
            send_params = send_params.concat('&',learner);
        }
    });

    //alert ('GradeID '+add_grade_id+'ClassID '+add_class_id+'Num Learners '+num_learners+' PARAMS '+params);
    
    $('#addLearnersDlg').dialog('close');
    
    getGradeClassList(grade_id, class_id);
    
    $("#loading").hide();
    
}

function addNewClass(){
    
    $('#addClassDlg').dialog({title: 'Add New Class'});
    $('#addClassDlg').dialog('open').dialog('center').dialog('setTitle', 'Add New Class');
}

function addNewYear(){
    
    $('#addYearDlg').dialog({title: 'Add New Year'});
    $('#addYearDlg').dialog('open').dialog('center').dialog('setTitle', 'Add New Year');
}

function SaveYear(){
    
    var year = $("#year").val();
    
    //alert(year);
    
    var save_year = getJasonData("action=SAVEYEAR&year="+year);
   
     $('#addYearDlg').dialog('close');
     
      location.reload();
}

function gradeSubjectSetting(data)
{
	$.each(data, function(i, item) {
		var grade_setting = item.grade_setting;
		$.each(item.subject_info,function(j,jitem){
			//alert(jitem);
			var jdata = jitem.split("=");
			//alert(jdata[1]);			
			$("#"+jdata[0]).val(jdata[1]);
			if(jdata[0].indexOf("color") > -1)
			{				
				if($.trim(jdata[1]) == "")
				{
					jdata[1] = "FFF677";
				}
				//alert(jdata[0] + " = " +jdata[1]);
				$("#the"+jdata[0]).val(jdata[1]);
				$("#btn"+jdata[0]).val(jdata[1]);	
				$("#_"+jdata[0]).val(jdata[1]);				
				$( "#"+jdata[0] ).focus();
				$( "#_"+jdata[0] ).focus();
				$( "#the"+jdata[0] ).focus();
				$( "#btn"+jdata[0] ).focus();
			}
			else{
				$("#_"+jdata[0]).html(jdata[1]);
				$( "#_"+jdata[0] ).focus();
			}
		});
		//alert(grade_setting);
		
		var gsettingdata = grade_setting.split(":");
		var data = gsettingdata[1].split(",");
		$.each(data,function(j,ditem){
			var ddata = ditem.split("=");
			$("#"+ddata[0]).val(ddata[1]);
			if(ddata[0].indexOf("color") > -1)
			{				
				if($.trim(ddata[1]) == "")
				{
					ddata[1] = "FFF677";
				}
				//alert(ddata[0] + " = " +ddata[1]);
				$("#the"+ddata[0]).val(ddata[1]);
				$("#btn"+ddata[0]).val(ddata[1]);	
				$("#_"+ddata[0]).val(ddata[1]);				
				$( "#"+ddata[0] ).focus();
				$( "#_"+ddata[0] ).focus();
				$( "#the"+ddata[0] ).focus();
				$( "#btn"+ddata[0] ).focus();
			}
			else{
				$("#_"+ddata[0]).html(ddata[1]);
				$( "#_"+ddata[0] ).focus();
			}
		});
		//$("#color").val(item.subject_info[0].color);
		
	});
}

function SaveStudentGrade()
{
	var numSelected = selectedStudents.length;
	//alert(numSelected);
	for(var i=0; i<numSelected; i++){
		//var rowIndex = $("#tblTeacheSetting").datagrid("getRowIndex", rows[i]);	
		//alert(rowIndex);		
		$('#students').datagrid('endEdit', selectedStudents[i]);	
	}
	
	var rows = $('#students').datagrid('getRows');
	
	for(var i=0; i<numSelected; i++){
		var user_id = rows[selectedStudents[i]].user_id;
		var grade_id = $("#student_grade").val();
		var school_id = getUrlParameter("school_id");
		if(school_id == undefined || school_id == "undefined")
		{
			school_id = $("#schools").val();
		}			
		var year_id = $("#student_year_id").val();
		var baseline = rows[selectedStudents[i]].baseline;
		var learner_average = rows[selectedStudents[i]].learner_average;
		var subject_choice = rows[selectedStudents[i]].subject_choice;
		var to_grade = rows[selectedStudents[i]].to_grade;
		var to_class = rows[selectedStudents[i]].to_class;
		var grade_title = rows[selectedStudents[i]].grade_title;
		var class_label = rows[selectedStudents[i]].class_label;
		//alert(subject_choice);
		//alert(learner_average);
		//alert(baseline);
		var param = "user_id="+user_id+"&grade_id="+grade_id+"&school_id="+school_id+"&baseline="+baseline+"&learner_average="+learner_average+
			"&subject_choice="+subject_choice+"&year_id="+year_id+"&number_of_leaners_per_class="+$("#number_of_learners").val()+
			"&next_grade="+to_grade+"&next_class="+to_class+"&current_grade="+grade_title+"&current_class="+class_label;
		//alert(param);
		sendDataByGet(param,"timetable_learner_settings_save.php");
	}
	
	numSelected = selectedStudents.length;
	var selectedrows = $('#students').datagrid('getSelections');
	while(selectedrows.length > 0)
	{
		var selectedrow = $('#students').datagrid('getSelected');
		var rowIndex = $("#students").datagrid("getRowIndex", selectedrow);
		//alert(rowIndex);
		$('#students').datagrid('deleteRow', rowIndex);
		selectedrows = $('#students').datagrid('getSelections');
	}	
	
	selectedStudents = [];
	
}

function SaveTeacherSetting()
{
	//var rows = $('#tblTeacheSetting').datagrid('getSelections');
	var numSelected = selectedTeachers.length;
	//alert(numSelected);
	for(var i=0; i<numSelected; i++){
		//var rowIndex = $("#tblTeacheSetting").datagrid("getRowIndex", rows[i]);	
		//alert(rowIndex);		
		$('#tblTeacheSetting').datagrid('endEdit', selectedTeachers[i]);	
	}
	
	var rows = $('#tblTeacheSetting').datagrid('getRows');
	for(var i=0; i<numSelected; i++){
		var row = rows[selectedTeachers[i]];
		var user_id = rows[selectedTeachers[i]].user_id;
		//alert(user_id);
		var school_id = getUrlParameter("school_id");
		if(school_id == undefined || school_id == "undefined")
		{
			school_id = $("#schools").val();
		}			
		var grade_id = $("#teacher_grade").val();
		var year_id = $("#year_id").val();
		var user_id = rows[selectedTeachers[i]].user_id;
		//alert(user_id);
		var substitute = rows[selectedTeachers[i]].substitute;
		//alert(substitute);
		var number_periods = rows[selectedTeachers[i]].number_periods;
		//alert(number_periods);
		var param = "school_id="+school_id+"&grade_id="+grade_id+"&year_id="+year_id+"&user_id="+user_id+"&substitute="+substitute+"&number_periods="+number_periods;
		param = param + "&subject_id="+$("#teacher_subject_id").val();
		//alert(param);
		sendDataByGet(param,"timetable_teacher_settings_save.php");
	}
		
	var selectedrows = $('#tblTeacheSetting').datagrid('getSelections');
	while(selectedrows.length > 0)
	{
		var selectedrow = $('#tblTeacheSetting').datagrid('getSelected');
		var rowIndex = $("#tblTeacheSetting").datagrid("getRowIndex", selectedrow);
		//alert(rowIndex);
		$('#tblTeacheSetting').datagrid('deleteRow', rowIndex);
		selectedrows = $('#tblTeacheSetting').datagrid('getSelections');
	}	
	selectedTeachers = [];
	
}

var selectedStudents = [];

function onStudentClickCell(index,row)
{
	var rows = $('#students').datagrid('getRows');
	var row = rows[index];
	//alert(rows[index].ck);
	//alert(rows[index].access_id);
	if (row.editing){
		$('#students').datagrid('endEdit', index);
	}
	else{
		$('#students').datagrid('beginEdit', index);
		selectedStudents.push(index);
	}
	
}

function onTeacherClickCell(index,row)
{
	var rows = $('#tblTeacheSetting').datagrid('getRows');
        //alert('I GET HERE INDEX' + index);
	var row = rows[index];
	if (row.editing){
		$('#tblTeacheSetting').datagrid('endEdit', index);		
	}
	else{
		$('#tblTeacheSetting').datagrid('beginEdit', index);
		selectedTeachers.push(index);
	}
}
var classSettings = [];
function onClassVenueClickCell(index,row)
{
	var rows = $('#tblClassVenueSetting').datagrid('getRows');
	var row = rows[index];
	if (row.editing){
		$('#tblClassVenueSetting').datagrid('endEdit', index);		
	}
	else{
		$('#tblClassVenueSetting').datagrid('beginEdit', index);
		classSettings.push(index);
	}
}

function SaveClassSetting()
{
	var numSelected = classSettings.length;
	//alert(numSelected);
	for(var i=0; i<numSelected; i++){
		//var rowIndex = $("#tblTeacheSetting").datagrid("getRowIndex", rows[i]);	
		//alert(rowIndex);		
		$('#tblClassVenueSetting').datagrid('endEdit', classSettings[i]);	
	}
	var rows = $('#tblClassVenueSetting').datagrid('getRows');
	for(var i=0; i<numSelected; i++){
		var school_id = getUrlParameter("school_id");
		if(school_id == undefined || school_id == "undefined")
		{
			school_id = $("#schools").val();
		}			
		var year_id = $("#class_year_id").val(); 
		var params = "school_id="+school_id+"&year_id="+year_id;
		if(whorotates == "Learner Rotates")
		{
			var user_id = rows[classSettings[i]].user_id;
			params = params + "&user_id="+user_id;
		}
		else{
			var class_id = rows[classSettings[i]].class_id;
			params = params + "&class_id="+class_id;
		}
		var room_id = rows[classSettings[i]].room_id;
		params = params+ "&room_id="+room_id;
		//alert(params);
                
                var save_venue = getJasonData("action=SAVECLASSVENUE&"+params);
		//sendDataByGet(params,"timetable_class_settings_save.php");
                alert(save_venue);
	}
	//alert(whorotates);
}

function btnViewTimeTable()
{
	viewTimeTable(0);
	next();
}

function viewTimeTable(timetable_label)
{
        $("#timetableloading").show();
        
	var user_type = getUrlParameter("user_type");
	
	//alert(timetable_label + " user_type = " + user_type);
	 
	if(timetable_label == undefined || timetable_label == "undefined")
	{
		timetable_label = 0;
	}
	var school_id = getUrlParameter("school_id");
	if(school_id == undefined || school_id == "undefined")
	{
		school_id = $("#schools").val();
	}
	var year_id = $("#teachertimetable_year_id").val();
	//alert($("#teachertimetable_year_id").val());
	if($("#teachertimetable_year_id").val() == undefined)
	{
		year_id = getYearID();
	}
	//alert("action=TIMETABLESELECT&school_id="+school_id+"&user_type="+user_type+"&timetable_label="+timetable_label+"&year_id="+year_id);
	//alert($("#_classletters").html());
	//alert("action=TIMETABLESELECT&school_id="+school_id+"&user_type="+user_type+"&timetable_label="+timetable_label);
	var timetableid = getJasonData("action=TIMETABLESELECT&school_id="+school_id+"&user_type="+user_type+"&timetable_label="+timetable_label+"&year_id="+year_id);
	//alert(timetableid);
	
		timetableid =  jQuery.parseJSON(timetableid);		
		$('#timetable_id').html("");
		 $('#timetable_id').append(
                                        $('<option></option>').val('All').html('All')
                                );
		$.each(timetableid, function(i, item) {
			//alert(timetableid[i].timetabl_id);
			$('#timetable_id').append(
					$('<option></option>').val(item.timetabl_id).html(item.timetable_label)
				); 
		});
	
        $("#timetableloading").hide();
	//alert("aha");
	//next();
}

function newLearner()
{
	 addStudentsSetup();
	$('#newLearnerDlg').dialog({title: 'New Learner'});
	$('#newLearnerDlg').dialog('open').dialog('center');	
}

function newTeahcer()
{
	//addStudentsSetup();
	$("#user_id").val("");
	$("#teacher_id").val("");
	$("#teacher_initials").val("");
	$("#teacher_surname").val("");
	$('#newTeacherDlg').dialog({title: 'New Teacher'});
	$('#newTeacherDlg').dialog('open').dialog('center');	
}

function addStudentsSetup()
{
	$("#user_id").val("");
	$("#learner_id").val("");
	$("#name").val("");
	$("#surname").val("");
	$("#current_grade").val("");
	var letters = $("#_classletters").html();
	var classes = getJasonData("action=GETSTUDENTCLASS2&letters="+letters);
	classes = jQuery.parseJSON(classes);
	//alert(classes);
	var fromto = $("#_from_grade").html();
	//alert(fromto);
	var data = fromto.split(" to ");
	var start = parseInt(data[0]);
	if(typeof data[0] == "string" || data[0] == "R")
	{
		start = "0";
	}
	var end = parseInt(data[1]);
	//alert("action=GETGRADES&from="+start+"&to="+end);
	var grades = getJasonData("action=GETGRADES&from="+start+"&to="+end);
	
	grades=  jQuery.parseJSON(grades)	;
	$('#current_grade').html("");
	$('#current_grade').append(
		$('<option></option>').val("").html("")
	); 
	
	$('#next_grade').html("");
	$('#next_grade').append(
		$('<option></option>').val("").html("")
	); 
	
	
	
	$.each(grades, function(i, item) {
		$('#current_grade').append(
			$('<option></option>').val(item.grade_id).html(item.grade_title)
		); 
		
		$('#next_grade').append(
			$('<option></option>').val(item.grade_id).html(item.grade_title)
		); 
		
	});
	
	$('#add_learner_class').html("");	
	$('#add_learner_class').append(
			$('<option></option>').val("").html("")
		); 
	$('#current_class').html("");
	$('#current_class').append(
			$('<option></option>').val("").html("")
		); 
		
		$('#next_class').html("");
		$('#next_class').append(
			$('<option></option>').val("").html("")
		); 
	
	$.each(classes, function(i, item) {
		$('#add_learner_class').append(
			$('<option></option>').val(item.class_id).html(item.class_label)
		); 
		
		$('#current_class').append(
			$('<option></option>').val(item.class_id).html(item.class_label)
		); 
		
		$('#next_class').append(
			$('<option></option>').val(item.class_id).html(item.class_label)
		); 
	});
	
	//alert("Exit");
}

function getSchoolID()
{
	var school_id = getUrlParameter("school_id");
	if(school_id == undefined || school_id == "undefined")
	{
		school_id = $("#schools").val();
	}
	if(school_id == undefined || school_id == "undefined")
	{
		school_id = "<?php echo $school_id; ?>";
	}
	return school_id;
}

function getYearID()
{
	var par = "action=getyearid";
	//alert(par);
	var result  = getJasonData(par);		
	return result;
}

function SaveNewLearner()
{
	//alert($("#user_id").val());
	var params = "";
	
	var school_id = getUrlParameter("school_id");
	if(school_id == undefined || school_id == "undefined")
	{
		school_id = $("#schools").val();
	}		
	if($("#user_id").val() == "")
	{
		//alert("save");
		params = $("#frmSaveLearner").serialize()+"&action=SAVELEARNER&school_id="+school_id+"&year_id="+$("#student_year_id").val();
	}
	else
	{
		//alert("Edit");
		params = $("#frmSaveLearner").serialize()+"&save_type=UPDATELEARNER&school_id="+school_id+"&year_id="+$("#student_year_id").val();
	}
	params = params + "&number_of_learners="+$("#number_of_learners").val();	
	params = params.replace("user_id=","user_id="+$("#user_id").val());
	alert(params);
	
	$('#newLearnerDlg').dialog('close');
	//sendDataByGet(params,"timetable_save.php");
        var savelearner = getJasonData(params);
	setStudentSettings();
	//$("#frmSaveLearner").reset();
	//$('#frmSaveLearner :input').each(function(){this.val("");});;
	alert("Student Information saved");
}

function SaveNewTeacher()
{
	//alert($("#year_id").val());
	var school_id = getUrlParameter("school_id");
	if(school_id == undefined || school_id == "undefined")
	{
		school_id = $("#schools").val();
	}			
	var params = "";
	if($("#user_id").val() == "")
	{
		params = $("#frmSaveTeacher").serialize()+"&save_type=new_teacher&school_id="+school_id+"&year_id="+$("#year_id").val();
	}
	else{
		params = $("#frmSaveTeacher").serialize()+"&save_type=update_teacher&school_id="+school_id+"&year_id="+$("#year_id").val();
	}
	params = params + "&subject_id="+$("#teacher_subject_id").val()+"&grade_id="+$("#teacher_grade").val();
	//alert(params);
	sendDataByGet(params,"timetable_save.php");
	getTeacherSettings();
	alert("Teacher Information saved");
	$('#newTeacherDlg').dialog('close');
	
}

function getClassList(class_id)
{
	//alert("action=GETCLASSLIST&class_id="+class_id+"&year_id="+$("#teachertimetable_year_id").val());
	var school_id = getUrlParameter("school_id");
	if(school_id == undefined || school_id == "undefined")
	{
		school_id = $("#schools").val();
	}	
	//alert("action=GETCLASSLIST&school_id="+school_id+"&timetable_id="+class_id+"&year_id="+$("#teachertimetable_year_id").val());
	var classes = getJasonData("action=GETCLASSLIST&school_id="+school_id+"&timetable_id="+class_id+"&year_id="+$("#teachertimetable_year_id").val());
	classes = jQuery.parseJSON(classes);
	//alert(classes);
	$("#class_list").html("");
	$('#class_list').append(
		$('<option></option>').val(0).html("Select Learner To View Time Table")
	); 
	$.each(classes, function(i, item) {
		var name = item.access_id + " " + item.surname + " " + item.name;
		$('#class_list').append(
			$('<option></option>').val(item.user_id).html(name)
		); 
	});
	//alert(class_id);
	//alert("action=GETCLASSTEACHER&class_id="+class_id);
	var teachers = getJasonData("action=GETCLASSTEACHER&class_id="+class_id);
	//alert(teachers);
	teachers = jQuery.parseJSON(teachers);
	
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
}

function getTimetableID(id)
{
	return getJasonData("action=GETTIMETABLEID&id="+id);	
}

function decUrl(url)
{
	return getJasonData("action=DECURL&url="+url);	
}

function openWindow(url)
{
	window.open(url,'1453910749489','width=700,height=500,toolbar=0,menubar=0,location=0,status=1,scrollbars=1,resizable=1,left=0,top=0');
}

function removelesson(lesson_title,period_label_id,day_id,date_stamp,lesson_url,class_id,subject_id)
{
	//alert("action=UNPUBLISHLESSON&lesson_title="+lesson_title+"&period_label_id="+period_label_id+"&day_id="+day_id+"&lesson_date="+date_stamp+"&lesson_url="+lesson_url+"&class_id="+class_id+"&subject_id="+subject_id);
	getJasonData("action=UNPUBLISHLESSON&lesson_title="+lesson_title+"&period_label_id="+period_label_id+"&day_id="+day_id+"&date_stamp="+date_stamp+"&lesson_url="+lesson_url+"&class_id="+class_id+"&subject_id="+subject_id);	
	var teacher_id = getUrlParameter("user_id");
	var timetable_id = getTimetableID(teacher_id);
	//alert("We are here " + timetable_id);
	getTimetable(timetable_id);
	$('.thecontextmenu').contextPopup({
	  items: theItems
	});
	//getTimetable(timetableID)
}

function removeslot(day,theperiod,time_table_id,school_id,date,subject,class_id)
{
	//alert("action=REMOVESLOT&day="+day+"&theperiod="+theperiod+"&time_table_id="+time_table_id+"&school_id="+school_id+"&date="+date+"&subject="+subject+"&class_id="+class_id);
	getJasonData("action=REMOVESLOT&day="+day+"&theperiod="+theperiod+"&time_table_id="+time_table_id+"&school_id="+school_id+"&date="+date+"&subject="+subject+"&class_id="+class_id);	
	getTimetable($("#timetable_id").val());
	getClassList($("#timetable_id").val());
		
}

function markRegister(timeslot,subject, learner_id,year_id,school_id,_class,ispresent)
{
	var pars = "action=MARKREGISTER&timeslot="+timeslot+"&subject="+subject+"&learner_id="+learner_id+"&year_id="+year_id+"&school_id="+school_id+"&class="+_class+"&ispresent="+ispresent;	
	//alert(pars);
	var result  = getJasonData(pars);		
	return result;
}

function adduser()
{
	$("#accesscontoldlg").dialog('open').dialog('center');
}

function closeAddUser()
{
	//alert("close");
	$("#accesscontoldlg").dialog('close');
}

function saveAddUser()
{
	//alert($("#frmAddUser").serialize());
	var school_id = getUrlParameter("school_id");
	if(school_id == undefined || school_id == "undefined")
	{
		school_id = $("#schools").val();
	}	
	
	var pars = "action=addaccessuser&"+$("#frmAddUser").serialize()+"&school_id="+school_id;
	//alert(pars);
	var result  = getJasonData(pars);	
	alert("User Added");
	$("#frmAddUser").find("input[type=text]").val("");
	$("#accesscontoldlg").dialog('close');
}

function openLink(url,user_id, user_type,action,other_params)
{
	//alert(other_params);
	var pars = "action=ACTIONLOG&user_id="+user_id+"&user_type_id="+user_type+"&log_action="+action+"&other_params="+other_params;
	//alert(pars);
	
	var result  = getJasonData(pars);	
	
	window.open(url, '_blank');
}
