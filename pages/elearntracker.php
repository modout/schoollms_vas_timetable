<?php

include('lib/timetable_nodb.php');
$school_id = 1;
$user_type = -1;
$year_id = 3;
?>
 
<div style="margin-top: 95px; padding-top: 95px;">
<h1 style="color: #428bca " style="margin-top: 10px; margin-bottom: 30px"><center>E-Learning Tracker</center></h1>
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
<script>
$(document).ready(function () {
   
   $("#eventRegisterDlg").hide();

});

</script>
