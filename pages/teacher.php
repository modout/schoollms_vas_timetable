<?php
extract($_GET);

$sql = "select distinct class_label
from schoollms_schema_userdata_school_classes  c
join schoollms_schema_userdata_school_timetable_items ti on c.class_id = ti.class_id
where
  teacher_id = '$user_id'
order by class_label desc";

$data->execSQL($sql);
$classes = "<select name='classes' id='classes' onchange=\"selectClass(this.value)\">";
$classes .= "<option value='ALL'>Select Class</option>";
while($row = $data->getRow())
{
	$classes .= "<option value='$row->class_label'>$row->class_label</option>";
}

$classes .= "</select>";

$server_ip = $_SERVER["SERVER_ADDR"] == '154.0.172.207' ? 'www.schoollms.net': $_SERVER["SERVER_ADDR"];
//$uri = $_SERVER["SERVER_URI"];
//echo $uri;
//exit;
$q = "SELECT * FROM schoollms_schema_userdata_schools WHERE school_id = $school_id";
$data->execSQL($q);
$row = $data->getRow();
$school_acronym = $row->school_acronym;
$support_url = "http://www.ekasiit.com";
$track_url = "http://".$server_ip."/vas/track/dashboard/?school_id=$school_id"; 
$etrack_url = "pages/elearntracker.php?school_id=$school_id&year_id=$year_id&user_type=-1&user_id=$user_id"; 
$chat_url = "https://appear.in/principal_$school_acronym"; 
$admin_timetable = "http://".$server_ip."/vas/timetable/pages/admin_timetable.php";
$admin_class = "http://".$server_ip."/vas/timetable/pages/admin_class.php";
$q = "SELECT * FROM schoollms_schema_userdata_access_profile WHERE user_id = $user_id";
//echo "Q $q";

$data->execSQL($q);
$row = $data->getRow();
$name = $row->name;
$surname = $row->surname;

$year = date('Y');

echo "<input type='hidden' name='user_id' value='$user_id'/>";
echo "<input type='hidden' name='user_type' value='$user_type'/>";
echo "<input type='hidden' name='year_id' value='$year_id'/>";
echo "<input type='hidden' name='school_id' value='$school_id'/>";
?>

<div class="tabs-selected" title="Timetable" style="padding:10px">
<h1><?php echo "$name $surname"; ?> Timetable <?php echo "$year"; ?></h1>
    <table>
        <tr>
            <td>Year</td><td><?php echo $teachertimetable_year_id; ?> </td> 
            <td>Grade</td><td><select name='teachertimetable_grade_id' id='teachertimetable_grade_id'></select> </td>
            <td>Timetable </td><td><select name='timetable_id' id='timetable_id'>  </select></td>
            <td>Class List </td><td><select name='class_list' id='class_list' style="width:450px;">  </select></td>		
            <td colspan='2'>
                <button id="getTimeTable" name="getTimeTable">Load Time Table</button>
            </td>
       	    <td> <a href="http://school.eb.co.uk" target="_blank"><img src="images/britannica.jpg"></a> </td> 
	</tr>
    </table>
    <br/><center><img id="teacherloading" name="teacherloading" src="images/loading.gif"><center><br/>	
    <!-- div  class="easyui-progressbar" style="width:80%" id="prgrsBar" name="prgrsBar" ></div -->
    <div id="timetable" name="timetable">  

    </div>

<!-- button class="previous">Previous</button -->&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<button class="previous">Previous</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<button class="next">Next</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
</div>
<div id="timetabletab" title="Timetable Stats" style="padding:10px">
    <center><button id="btnReloadReport" name="btnReloadReport" onClick="reloadStatsReport('teacher', <?php echo $user_id ?>)">Refresh Report</button>
    </center>
        <div id="container" style="height: 500px; width: 95%"></div>
<!--        <table>
		<tr>
			<td>Select Date Range</td>
			<td>&nbsp;&nbsp;&nbsp; From : <input type="text" id="fromdate" name="fromdate" readonly> &nbsp;&nbsp;&nbsp; To : <input type="text" id="todate" name="todate" readonly></td>
		</tr>
		<tr>
			<td>
				&nbsp;&nbsp;&nbsp; Select Class
			</td>
			<td></td>
		</tr>
		
	</table>
	<br/><br/>
	<table width="100%">
	<tr>
		<td style="width:300px" align="left">
			<div id="donutcolored" style="height:300px"></div>
		</td>
		<td style="width:650px" align="left">
			<div id="drilldown" style="height:350px"></div>
		</td>
	</tr>
	</table>
	<div id="report" name="report">  
		
	</div>-->

<!--<button class="previous">Previous</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
</div>
<div title="E-Learning Stats" style="padding:10px">
      <table id="table2" style="width:100%; border-collapse: separate; border-spacing: 3px 3px;">
<!--		<colgroup>
			<col width="6.25%"/>
			<col width="12.5%"/>
			<col width="12.5%"/>
			<col width="12.5%"/>
			<col width="12.5%"/>
			<col width="12.5%"/>
			<col width="12.5%"/>
			<col width="12.5%"/>
			<col width="12.5%"/>
		</colgroup>-->
		<tbody>
			<tr width="100%">
				<td class="redips-mark blank" id="firstTd">
					<?php
                                            $startdate = null;
                                            if(isset($_GET["startdate"]))
                                            {
                                                $startdate = $_GET["startdate"];
                                            }

                                            $today = null;
                                            if(isset($_GET["today"]))
                                            {
                                                $today = $_GET["today"];
                                            }
                                            
                                            echo navigation_menu($school_id,$startdate,$today);
                                        ?>
				</td>
                                <td width="100%">
                                    <div id="divHeader" style="overflow:hidden;width:100%;position:relative">
                                        <table width="100%" cellspacing="0" cellpadding="0" border="1" >
                                            <colgroup>
                                                    <!--<col width="6.25%"/>-->
                                                    <col width="11%"/>
                                                    <col width="11%"/>
                                                    <col width="11%"/>
                                                    <col width="11%"/>
                                                    <col width="11%"/>
                                                    <col width="11%"/>
                                                    <col width="11%"/>
                                                    <col width="12.5%"/>
                                            </colgroup>
                                            <tbody width="100%">
                                                <tr width="100%">
                                                    <?php
                                                    

                                                    echo periods($school_id,$startdate,$today) ?>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </td>
			</tr>
                        <tr width="100%">
                            <td valign="top">
                              <div id="firstcol" style="overflow: hidden;height:370px;position:relative">
                                <table width="100%" cellspacing="0" cellpadding="0" border="1" >
                                    <colgroup>
                                        <col width="6.25%"/>
    <!--                                        <col width="12.5%"/>
                                            <col width="12.5%"/>
                                            <col width="12.5%"/>
                                            <col width="12.5%"/>
                                            <col width="12.5%"/>
                                            <col width="12.5%"/>
                                            <col width="12.5%"/>
                                            <col width="12.5%"/>-->
                                    </colgroup>
                                    <tbody>
                                        <?php  
                                        //PRINT DAYS
                                        $slots = print_days(22,$user_type,$user_id,$year_id,$school_id, $startdate,$today);
                                        ?>
                                    </tbody>
                                </table>
                              </div>
                            </td>
                            <td valign="top">
                                <div id="table_div" style="overflow:scroll;width:100%;height:385px;position:relative" onscroll="fnScroll()" >
                                    <table width="100%" cellspacing="0" cellpadding="0" border="1" >
                                        <colgroup>
                                                <!--<col width="6.25%"/>-->
                                                <col width="12.5%"/>
                                                <col width="12.5%"/>
                                                <col width="12.5%"/>
                                                <col width="12.5%"/>
                                                <col width="12.5%"/>
                                                <col width="12.5%"/>
                                                <col width="12.5%"/>
                                                <col width="12.5%"/>
                                        </colgroup>
                                        <tbody>
                                              <?php
                                                //PRINT SLOTS
                                                //echo "STARTDATE $startdate TODAY $today<br>";
                                                foreach ($slots as $key => $row) {
                                                    print $row;
                                                }

                                                //print_days($id,$user_type,$user_id,$year_id,$school_id, $startdate,$today);
                                                /*if(isset($user_id)){
                                                        print_days($id,$user_type,$user_id,$year_id);
                                                }
                                                else{
                                                        print_days($id,$user_type);
                                                }*/

                                                 ?>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
		</tbody>
	</table> 
</div>
<div title="Track Learners" style="padding:10px">
    <iframe src="<?php echo $track_url; ?>" height=100% width=100%></iframe> 
</div>
<div title="Chatroom" style="padding:10px">
    <iframe src="<?php echo $chat_url; ?>" height=100% width=100%></iframe> 
</div>

<div title="School Reports" style="padding:10px">
    
</div>

<div title="School Calendar" style="padding:10px">
    
</div>
<div title="Support" style="padding:10px">
    <iframe src="<?php echo $support_url; ?>" height=100% width=100%></iframe> 
</div>
<div id="teacher_timetable_settings" name="teacher_timetable_settings" title="Admin Timetable" style="padding:10px">
        <iframe src="<?php echo $admin_timetable; ?>" height=100% width=100%></iframe>
</div>
<div id="teacher_class_settings" name="teacher_class_settings" title="Admin Class" style="padding:10px">
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
   <br/><br/>
   <center>
   <table>
                <tr>
                        <td>Year</td><td><?php echo $teachertimetable_year_id; ?> </td>
                        <td>Grade</td><td><select name="student_grade1" id="student_grade1"> </select></td>
                        <td>Class</td><td><select name='class_id' id='class_id'>  </select></td>
                </tr>
        </table>
        <br/><br/><img id="loading" name="loading" src="../images/loading.gif"><br/>
        <fieldset>
         <table id="classlists" border="2"></table>
        </fieldset>
        </center>
</div>

</div>

<script type="text/javascript" >

	//var index = $('#tabs a[href="#timetabletab"]').parent().index();
	//$("#tabs").tabs("option", "active", index);

	
	$(document).ready(function () {
//		$( "#fromdate").datepicker({ dateFormat: 'yy-mm-dd' });
//		$( "#todate").datepicker({ dateFormat: 'yy-mm-dd' });
//		$("#getTimeTable").click(function () {
//				//alert("previous");
//				alert("This is the saubmit");
//			});
//			
//		var tid = setTimeout(mycode, 3000);			
//		
//		$("#donutcolored").html("");
//		getGraphData();
                var user_id = getUrlParameterPlain("user_id");
                var school_id = getUrlParameterPlain("school_id");
                var year_id = getUrlParameterPlain("year_id");
               
                $("#teachertimetable_grade_id").on('change', function() {
                        //alert($("#teachertimetable_grade_id").val());

                        viewTimeTable($("#teachertimetable_grade_id").val());
                });

                $("#btnAddUser").click(function () {
                        adduser();
                        //next();
                });

                $("#student_grade1").on('change', function() {
                    getTeacherGradeClasses(user_id,school_id,year_id,$("#student_grade1").val());
                });

                $("#class_id").on('change', function() {
                    getTeacherGradeClassList(user_id,,school_id,year_id,$("#student_grade1").val(),$("#class_id").val());
                });

		//window.onload = function() {
                        getTeacherGrades(user_id,school_id,year_id);
                //};
 
                $(function() {
                         
//                         alert('HELLO WORLD');
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
                            //alert('CLASSES '+classes);
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
		
	});
	
	function mycode() {
		//alert("Aha");
		tid = setTimeout(mycode, 3000); // repeat myself
	}
	
	function getGraphData()
	{
		$.getJSON("api/process.php?action=GETMEMBERSTATS&id=2", function( json ) {
			//alert(json)
		 /*Morris.Donut({
                element: 'donutcolored',
                data: Object.keys(json.donutcol).map(function(key) {return json.donutcol[key]}),
                labelColor: '#303641',
                colors: ['#f26c4f', '#00a651', '#00bff3', '#0072bc']
            }).on('click', function (i, row) {  
			  
			  getDrillDownData(row);
			});;*/
		});
	}
	
	function selectClass(value)
	{
		$("#donutcolored").html("");
		getGraphData();
	}
	
	function getDrillDownData(row)
	{
		var fromdate = $("#fromdate").val();
		var todate = $("#todate").val();
		var _class = $("#classes").val();	
		var user_id = getUrlParameterPlain("user_id");		
		var pars = "action=GETDRILLDOWNDATA&fromdate="+fromdate+"&todate="+todate+"&class="+_class+"&label="+row.label+"&value="+row.value+"&user_id="+user_id;
		//alert(pars);
		var result  = getJasonData(pars);	
		//alert(result);
		$("#drilldown").html(result);
	}
	
	

</script>
