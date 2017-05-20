<div title="Data" style="padding:10px">
	<?php
        include('util.php');

        $q = "SELECT * FROM schoollms_schema WHERE columns like '%table_id%'";

        $result = $data->exec_sql($q, "array");

        $select_data .= "<option value=0> Select Data Schema <option>";

        if ($data->numrows > 0){
            foreach($result as $key=>$row){
                $table_id = $row["table_id"];
                $table = $row["tablename"];
//                $q = "SELECT * FROM schoollms_schema_userdata_access_profile WHERE user_id = $learner_user_id";
//                $data->execSQL($q);
//                $learner_row = $data->getRow();
//                $access_id = $learner_row->access_id;
//                $name = $learner_row->name;
//                $surname = $learner_row->surname;
//
//                $q = "SELECT * FROM schoollms_schema_userdata_school_timetable WHERE timetable_type_id = 1 AND timetable_type_item_id = $learner_user_id";
//                $data->execSQL($q);
//
//                if ($data->numrows > 0){
//                    $learner_row = $data->getRow();
//                    $learner_timetable_id = $learner_row->timetabl_id;

                    $select_data .= "<option value=$table_id> $table <option>";
//                } elseif ($data->numrows == 0) {
//
//                }
            }

        }
//	?>
    <form action="#" id="frmData" name="frmData">
        <center>
        <table>
		<tr><td>Select Data </td><td><select name='data_type' id='data_type'><?php echo $select_data ?>  </select></td></tr>
                <tr><td>Select Action </td><td><select name='data_action' id='data_action'><?php echo $select_action ?>  </select></td></tr>
	</table>
        <!-- img id="learnerloading" name="learnerloading" src="images/loading.gif" -->
	<div id="data_timetable" name="data_timetable">  
		
	</div>
        </center>
    </form>
<!--	<br/><br/>
	<button onClick="openWindow('addschool.php')">Add New School </button> &nbsp;&nbsp;&nbsp;
	<button onClick="getStep1Data();next();">Next</button>-->
</div>
<div title="System" style="padding:10px">
	<?php
//        //include('util.php');
//        $url = "http://www.gpsvisualizer.com/draw/";
//		echo "SELECT SCHOOL <br> $schools <br><br>";
//                
//        //$pars= "action=CHECKSYNC&table=$table";
//        //echo "$url?$pars <br>\n";
//        //$contents = do_post_request($url,$pars);
//                
//        //echo "<iframe src='$url' width=100% height=100%>  </iframe>";
//	?>
<!--	<br/><br/>
	<button onClick="openWindow('addschool.php')">Add New School </button> &nbsp;&nbsp;&nbsp;
	<button onClick="getStep1Data();next();">Next</button>-->
</div>
<div title="User" style="padding:10px">
	<?php
//        //include('util.php');
//        $url = "http://www.gpsvisualizer.com/draw/";
//		echo "SELECT SCHOOL <br> $schools <br><br>";
//                
//        //$pars= "action=CHECKSYNC&table=$table";
//        //echo "$url?$pars <br>\n";
//        //$contents = do_post_request($url,$pars);
//                
//        //echo "<iframe src='$url' width=100% height=100%>  </iframe>";
//	?>
<!--	<br/><br/>
	<button onClick="openWindow('addschool.php')">Add New School </button> &nbsp;&nbsp;&nbsp;
	<button onClick="getStep1Data();next();">Next</button>-->
</div>
<div title="Security" style="padding:10px">
	<?php
//        //include('util.php');
//        $url = "http://www.gpsvisualizer.com/draw/";
//		echo "SELECT SCHOOL <br> $schools <br><br>";
//                
//        //$pars= "action=CHECKSYNC&table=$table";
//        //echo "$url?$pars <br>\n";
//        //$contents = do_post_request($url,$pars);
//                
//        //echo "<iframe src='$url' width=100% height=100%>  </iframe>";
//	?>
<!--	<br/><br/>
	<button onClick="openWindow('addschool.php')">Add New School </button> &nbsp;&nbsp;&nbsp;
	<button onClick="getStep1Data();next();">Next</button>-->
</div>
<div title="Apps" style="padding:10px">
	<?php
//        //include('util.php');
//        $url = "http://www.gpsvisualizer.com/draw/";
//		echo "SELECT SCHOOL <br> $schools <br><br>";
//                
//        //$pars= "action=CHECKSYNC&table=$table";
//        //echo "$url?$pars <br>\n";
//        //$contents = do_post_request($url,$pars);
//                
//        //echo "<iframe src='$url' width=100% height=100%>  </iframe>";
//	?>
<!--	<br/><br/>
	<button onClick="openWindow('addschool.php')">Add New School </button> &nbsp;&nbsp;&nbsp;
	<button onClick="getStep1Data();next();">Next</button>-->
</div>