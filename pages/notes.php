<?php
include('../lib/db.inc');
//include('../lib/timetable.php');
$school_id = 1;
$user_type = -1;

$event_id = 0;
if (isset($_GET['event_id'])){
    $event_id = $_GET['event_id'];
}
//$q = "SELECT * FROM schoollms_schema_userdata_events WHERE event_id = $event_id";
//$data->execSQL($q);
//if ($data->numrows > 0){
//    $row = $data->getRow();
//    $title = $row->event_title;
//} else {
//    $title = "EVENT TITLE";
//}
?>
<html>
	<head>
		
		<meta name="description" content="SchoolLMS Notes"/>
		<meta name="viewport" content="width=device-width, user-scalable=no"/><!-- "position: fixed" fix for Android 2.2+ -->
		<link rel="stylesheet" href="../css/style.css" type="text/css" media="screen"/>
		<script type="text/javascript">
			var redipsURL = '/javascript/drag-and-drop-example-3/';
		</script>
		<!--<script type="text/javascript" src="header.js"></script>
		<script type="text/javascript" src="redips-drag-min.js"></script>
		<script type="text/javascript" src="script.js"></script> -->
		<!--<script type="text/javascript" src="../timetable.js"></script>-->
		<!--<script type="text/javascript" src="../jscolor.js"></script>-->
		
		
		<link rel="stylesheet" type="text/css" href="../themes/jquery-ui.css" />
		<link rel="stylesheet" type="text/css" href="../themes/default/easyui.css">
		<link rel="stylesheet" type="text/css" href="../themes/icon.css">
		<!-- script type="text/javascript" src="js/jquery-2.0.0.min.js"></script -->
		<script type="text/javascript" src="../js/jquery.1.11.1.min.js"></script>
		<script src="../js/jquery.easyui.min.js" type="text/javascript"></script>
		<!--<script src="../js/datagrid-filter.js" type="text/javascript"></script>-->
		<script type="text/javascript" src="../js/jquery.searchabledropdown-1.0.8.min.js"></script>
		<script src="../js/jscode.js" type="text/javascript"></script>
                <script src="../js/signature.js" type="text/javascript"></script>
		<!--<script src="../js/teachersetting.js" type="text/javascript"></script>-->	
		<!--<script src="../js/classvenuesettings.js" type="text/javascript"></script>-->
		<!--<script src="../js/studentsettings.js" type="text/javascript"></script>-->
        </head>
<body style="background:#1FFFFF;">
<!--div id="eventRegisterDlg" class="easyui-dialog" style="width:400px;height:250px;padding:10px 20px"
            closed="true" buttons="#dlg-buttons" -->

    <div class="ftitle" name="thetitle" id="thetitle"><strong> <?php echo $title ?></strong></div>
        <form runat="server" id="frmNotes" name="frmNotes"  >

          
            
        <div id="canvas" style="overflow:auto; width:100%; height:90%; position: relative; margin: 0; padding: 0; border: 1px solid #c4caac;">
        <canvas class="roundCorners" id="newSignature"></canvas>
        </div>
        <script>
                signatureCapture();
        </script>
                
        
        <table style="width: 100%" cellspacing=2 cellpadding=2 >
        <tr>
            <td>
         <button type="button" onclick="signatureSave()">
                Save notes
        </button>
        <button type="button" onclick="signatureAdd()">
                Add page
        </button>
        <button type="button" onclick="signatureAll()">
                View all pages
        </button>         
        <button type="button" onclick="signatureClear()">
                Clear notes
        </button>
            </td>
        </tr>
        </table>
        </form>
	 
	<!--/div-->
    </body>
</html>
                                            