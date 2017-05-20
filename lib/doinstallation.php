<?php
session_start();
$_SESSION = array();

include("simple-php-captcha.php");
include('lib/db.inc');
include("lib/util.php");
?>
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
<link href="css/bootstrap.css" rel="stylesheet">
<link href='css/questrial.css' rel='stylesheet' type='text/css'>
<link href='css/italic.css' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="css/j-forms.css">
<link href="css/style.css" rel="stylesheet" type="text/css" media="all" />
<link rel="stylesheet" href="css/footer-distributed.css">
<link rel="stylesheet" href="css/test.css">
<link rel="stylesheet" href="css/custom.css">
<link rel="stylesheet" href="css/font-awesome.min.css">
<link rel="stylesheet" href="css/imagepopup.css">

<script type="text/javascript" src="js/modernizr-2.6.2.min.js"></script>
<script src="js/jquery.1.11.1.min.js"></script>
<script src="js/jquery-ui.js"></script>
<script type="text/javascript" src="js/bootstrap.js"></script>
<script src="js/responsive-tabs.js"></script>
<script type="text/javascript" src="js/jscode.js"></script>

<style>
  .custom-combobox {
    position: relative;
    display: inline-block;
  }
  .custom-combobox-toggle {
    position: absolute;
    top: 0;
    bottom: 0;
    margin-left: -1px;
    padding: 0;
  }
  .custom-combobox-input {
    margin: 1;
    padding: 5px 10px;
  }

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
			.s-2 {
				background-color: #E7D783;
			}
			.s-3 {
				background-color: #E99AE6;
			}
			.s-4 {
				background-color: #C4AFFF;
			}
			.s-5 {
				background-color: #91DEC5;
			}
			.s-6 {
				background-color: #CFE17F;
			}
			.s-7 {
				background-color: #E7BD83;
			}
			.s-8 {
				background-color: #FFC5C2;
			}
			.s-9 {
				background-color: #A5F09D;
			}

			.s-28
			{
				background-color: #E0B0FF;
			}

			.s-30
			{
				background-color: #FFA500;
			}
			.s-31
			{
				background-color: #00FFFF;
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

		
@media only screen and (max-width: 768px){
	.navbar-fixed-bottom{
		position: static;
	}
}
	
</style>

<script>
	

	$(document).ready(function(){

		//getTimetable(22);
		
		$( 'ul.nav.nav-tabs  a' ).click( function ( e ) {
			e.preventDefault();
			$( this ).tab( 'show' );
		  } );
		  
		  $( '.js-alert-test' ).click( function () {
            alert( 'Button Clicked: Event was maintained' );
          } );
          fakewaffle.responsiveTabs( [ 'xs', 'sm' ] );

	});
</script>

</head>
<body>
<div class="header" style="z-index: 100"><center>
	<?php
	$user_type = 0;
		if($user_type == 6)
			{
	?>
		<img src="images/schoollmslogo.png" alt="Sunward Park School LMS" />
		<?php
			}
			else{
				?>
				<img src="images/schoollmslogo.png" alt="Sunward Park School LMS" />
				<?php
			}
			?>
	</center><br/>
</div>

<div class="content">
<div id="content" style="margin-top: 100px">
	<ul class="nav nav-tabs responsive with-nav-tabs.panel-primary" id="myTab" style="margin-top: 50px;">	  
	  <li class="active"><a href="#reg">Registering Your Information</a></li>	  
	</ul>
	<div class="tab-content responsive" style="margin-top: 100px;" style="margin-top: 50px;">
		
		<div class="tab-pane active" id="reg" style="border: none">
			<?php
				$school_id = 0;
				$school_already_added = false;
				$sql = "select * from schoollms_schema_userdata_schools where replace(upper(school_name),'^',' ')= upper('$the_school_name') ";
				//echo  "$sql<br/>";
				$data->execSQL($sql);
				if($row = $data->getRow())
				{
					$school_id = $row->school_id;
					//$school_already_added = true;
					//echo "School Already There<br/>";
				}
				else{
					$sql = "insert into schoollms_schema_userdata_schools (school_name)  values ('$the_school_name')";	
					$data->execNonSql($sql);
					$school_id = $data->insertid;
					//echo "New School<br/>";
				}

				$user_id = 0;
				$sql = "select * from schoollms_schema_userdata_access_profile where access_id = '$email_address'";
				echo "<br/>$sql </br>";
				$data->execSQL($sql);
				if($row = $data->getRow())
				{
					//user exists
					$user_id  = $row->user_id;
					//echo "User Already There<br/>";
				}
				else{
					//add user
					$sql = "insert into schoollms_schema_userdata_access_profile (school_id, type_id, access_id, name, surname) 
					values ('$school_id','$install_type_id','$email_address','$name','$surname')";
					$data->execNonSql($sql);
					$user_id = $data->insertid;
					//echo "<br/>$sql </br>";
					//echo "New User<br/>";
				}

				$sql = "select * from schoollms_schema_userdata_school_user_register_user where school_id = '$school_id' and user_id = '$user_id'";
				$data->execSQL($sql);
				if($row = $data->getRow())
				{
					//person is already registered
					//redirect to a page with the relevant message.
					//echo "User already registered in this school<br/>";
				}
				else{
					$sql = "insert into schoollms_schema_userdata_school_user_register_user (school_id, user_id,registration_date,registered_from_device_type)
					values ('$school_id', '$user_id',now(),'$device_info')";
					$data->execNonSql($sql);
					//echo "New user registered in this school<br/>";
					//do we add the school info here or down there?
				}

				echo $school_already_added;

				if(!$school_already_added )
				{
					//echo "We are here now </br>".strtoupper($user_type);
					
					//or do we add the school information here?
					if(strtoupper($user_type) == "SUPPORT" or strtoupper($user_type) == "SCHOOL")
					{
						$target_dir = "uploads/";
						$target_file = $target_dir . $school_id."_".$the_school_name.".jpg";
						$uploadOk = 1;
						$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
						echo " $target_dir $imageFileType ";
						move_uploaded_file($_FILES["school_emblem"]["tmp_name"], $target_file);
												
						$sql = "update schoollms_schema_userdata_schools set school_type = '$school_type', longitude = '$logitude', 
							latitude = '$latitude', school_server_ip = '$school_ip', school_emblem =  '$target_file' where school_id = '$school_id'";
						$data->execNonSql($sql);
						//echo "<br/>Done $school_id <br/>";		
						
					}
				}

				alert("Thank you for registering. We will contact you with further information to get you up and running");
				redirect("index.php");
				
				
			?>
		</div>
		
	</div>
</div>

		<div class="navbar navbar-default navbar-custom navbar-fixed-bottom" style="margin-bottom: 0px">
		
		<div class="container">
			<div class="col-sm-2">
				<p>&copy School LMS 2016</p>
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
