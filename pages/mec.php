<?php

extract($_GET);

$menu_url = $_SERVER['REQUEST_URI'];

$q = "SELECT * FROM schoollms_schema_userdata_timetable_menu_url WHERE user_id = $user_id";

$data->execSQL($q);

if ($data->numrows > 0){
    $q = "UPDATE schoollms_schema_userdata_timetable_menu_url SET menu_url = '$menu_url' WHERE user_id = $user_id";
} else {
    $q = "INSERT INTO schoollms_schema_userdata_timetable_menu_url VALUES  ('$user_id', '$menu_url')";
}
$data->execNonSql($q);

?>
<div title="Track Timetable Stats" style="padding:10px">
    <?php 
 
        echo "<input type='hidden' name='mec_id' value='$user_id'/>";
        echo "<input type='hidden' name='user_type' value='$user_type'/>";
        echo "<input type='hidden' name='year_id' value='$year_id'/>";
        //echo "<input type='hidden' name='server_url' value='$server_url'/>";
    ?>
	<table border="1">
<!--            <tr><td>Select Year </td>
                <td>Select Grade </td>
                <td>Select Subject </td>
                <td>Select Teacher </td>
                <td>Select Class </td>
                <td>Select Learner </td>
            </tr>-->
            <tr><td><select name='mec_year_id' id='mec_year_id'></select></td>
                <td><select name='mec_district_id' id='mec_district_id'></select></td>
                <td><select name='mec_school_id' id='mec_school_id'></select></td>
                <td><select name='mec_grade_id' id='mec_grade_id'></select></td>
                <td><select name='mec_subject_id' id='mec_subject_id'></select></td>
                <td><select name='mec_teacher_id' id='mec_teacher_id'></select></td>
                <td><select name='mec_class_id' id='mec_class_id'></select></td>
                <td><select name='mec_learner_id' id='mec_learner_id'></select></td>
            </tr>
	</table>
        <center><img id="mecloading" name="mecloading" src="images/loading.gif"></center>
        <div id="container" style="height: 500px; min-width: 500px"></div>
<!--	<div id="learner_timetable" name="mec_timetable">  
		
	</div>-->

<!--<button class="previous">Previous</button>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-->
</div>

<div title="School Reports" style="padding:10px">
    
</div>

<div title="School Calendar" style="padding:10px">
    
</div>

<script>
	

	$(document).ready(function(){

		viewStatsTimetable('mec');
		
                $("#mec_year_id").on('change', function() {
			//alert($("#teachertimetable_grade_id").val());
			if ($("#mec_year_id").val() !== 0 || $("#mec_year_id").val() !== undefined){
                            viewStatsTimetable('mec');
                        } 
		});
               
                $("#mec_grade_id").on('change', function() {
			//alert($("#teachertimetable_grade_id").val());
			if ($("#mec_grade_id").val() !== 0 || $("#mec_grade_id").val() !== undefined){
                            viewStatsTimetable('mec');
                        } 
		});
                
                $("#mec_subject_id").on('change', function() {
			//alert($("#teachertimetable_grade_id").val());
			if ($("#mec_subject_id").val() !== 0 || $("#mec_subject_id").val() !== undefined){
                            viewStatsTimetable('mec');
                        } 
		});
                
                $("#mec_teacher_id").on('change', function() {
			//alert($("#teachertimetable_grade_id").val());
			if ($("#mec_teacher_id").val() !== 0 || $("#mec_teacher_id").val() !== undefined){
                            viewStatsTimetable('mec');
                        } 
		});
                
                $("#mec_class_id").on('change', function() {
			//alert($("#teachertimetable_grade_id").val());
			if ($("#mec_class_id").val() !== 0 || $("#mec_class_id").val() !== undefined){
                            viewStatsTimetable('mec');
                        } 
		});
                
		$("#mec_learner_id").on('change', function() {
			//alert($("#teachertimetable_grade_id").val());
			if ($("#mec_learner_id").val() !== 0 || $("#mec_learner_id").val() !== undefined){
                            viewLearnerTimeTable('mec');
                        } 
		});
                
                $("#mec_parent_id").on('change', function() {
			//alert($("#teachertimetable_grade_id").val());
			if ($("#mec_parent_id").val() !== 0 || $("#mec_parent_id").val() !== undefined){
                            viewLearnerTimeTable('mec');
                        } 
		});
                
                $(function() {
                    
                    //if ($("#mec_parent_id").val() == 0 && $("#mec_learner_id").val() == 0 && $("#mec_class_id").val() == 0 && $("#mec_year_id").val() == 0 && $("#mec_grade_id").val() == 0 && $("#mec_subject_id").val() == 0 && $("#mec_teacher_id").val() == 0){ 
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
                                            $("#mecloading").hide();

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
    });
		
</script>