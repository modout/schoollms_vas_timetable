<?php

require_once 'lib/Mobile_Detect.php';
$detect = new Mobile_Detect;

$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');
$scriptVersion = $detect->getScriptVersion();

switch($deviceType){

      case 'tablet':
      case 'phone':
	$install_file = "http://www.schoollms.net/vas/installer/android/SchoolLMS_Beta_1.apk";
       	break;

      case 'computer':
	$install_file = "http://www.schoollms.net/vas/installer/windows/SchoolLMS_Beta_1.exe";
	break;

}

$_SESSION['captcha2'] = simple_php_captcha();
//var_dump($_SESSION['captcha']["code"]);
$captchaimg = "lib/mycaptcha.php?code=".$_SESSION['captcha2']["code"];
//$captcha1 = getCaptcha();

$sql = "select * from schoollms_schema_userdata_school_type";
$data->execSQL($sql);
$schooltype = "<select name='school_type' id='school_type'>";
while($row = $data->getRow())
{
	$schooltype .= "<option value='$row->type_id'>$row->type_title</option>";
}
$schooltype .= "</select>";

$sql = "select * from schoollms_schema_userdata_schools";
$data->execSQL($sql);
$schools = "<select name='school_name' id='school_name'>";
$schools .= "<option value='-1'></option>";
while($row = $data->getRow())
{
	$schoolname = str_replace("^"," ", $row->school_name);
	$schools .= "<option value='$row->school_id'>$schoolname</option>";
}
$schools .= "</select>";

$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
//echo $actual_link;
//var_dump(parse_url($actual_link));
?>
<div style="margin-top: 95px; padding-top: 95px">
<h1 style="color: #428bca "><center>Enable Your Offline Access</center></h1>
<h2 style="font-size: 25px"><center>
In this page you will be able to register and get access<br/>
to the installation package for your current device. </center> 
</h2>


<div class="main">
	<form class="contact-forms" id="frmInstall" name="frmInstall" enctype="multipart/form-data" action="lib/doinstallation.php" method="post">
		<!-- end /.header-->
			<!-- start country -->
			
			
				<div class="first-line">
					<div class="span8 main-row">
						<div class="input">
							<center style="font-weight: bold">
							<p>You are working from a <b><?php echo $deviceType; ?></b>. Your UA is 
							<b class="<?php echo $deviceType; ?>"><?php echo htmlentities($_SERVER['HTTP_USER_AGENT']); ?></b></p></center>		
							<input type="hidden" id="device_info" name="device_info"  
								value="<?php echo "$deviceType $deviceType -- ".htmlentities($_SERVER['HTTP_USER_AGENT']); ?>"/>
						</div>
					</div>	
				</div>
			
			<div id="roleinfo" name="roleinfo">
				<div class="main-row">
					<center style="font-weight: bold">Select Your Role</center>
					<label class="input select">
						<?php echo  $installroles; ?><input type="hidden" id="user_type" name="user_type" />
						<i></i>
					</label>
				</div>
			</div>
		
			<div id="principalinfo" name="principalinfo">
				<div class="main-row">
                                	<div class="input ui-widget">
                                        	<center style="font-weight: bold">School Name</center>
                                        	<!-- input type="text" id="school_name" placeholder="School Name" name="school_name" -->
                                        	<?php echo  $schools; ?><input type="hidden" id="the_school_name" name="the_school_name" />
                                	</div>
                        	</div>
				<div class="main-row">
                                	<center style="font-weight: bold">Enter Identity or Passport Number</center>
                                	<div class="input">

                                       		<input type="password" id="access_id" placeholder="ID or Passport Number" name="access_id">
                               		</div>
					 <button type="button" class="primary-btn" id="btnSubmit" name="btnSubmit"  >Submit</button>
                        	</div>
			</div>
			<div id="confirminfo" name="confirminfo">
			</div>			
			<!-- div class="first-line">
				<div class="span8 main-row">
					<div class="input">
						<center style="font-weight: bold">Enter Initials</center>
						<input type="text" id="name" placeholder="Intials" name="name">
					</div>
				</div>
				
			</div>
			<div class="first-line">
				<div class="span8 main-row">
					<div class="input">
						<center style="font-weight: bold">Enter Surname</center>					
						<input type="text" id="surname" placeholder="Surname" name="surname">
					</div>
				</div>
				
			</div>
			<div class="main-row">
				<div class="input">
					<center style="font-weight: bold">Enter Email Address</center>
					<input type="text" id="email_address" placeholder="Email Address" name="email_address">
				</div>
			</div>
			<div class="main-row">
				<div class="input ui-widget">
					<center style="font-weight: bold">School Name</center>
					<?php echo  $schools; ?><input type="hidden" id="the_school_name" name="the_school_name" />
				</div>
			</div>
			
			<div id="schoolinfo" name="schoolinfo">
				<div class="main-row">
				<center style="font-weight: bold">School Type</center>
					<label class="input select">
						<?php echo  $schooltype; ?>
						<i></i>
					</label>
				</div>
				
				<div class="main-row">
					<div class="input">						
						<center><a href="mymap.php" target="_blank" >Click Here to get Longitude and Latitude values</a></center>
					</div>
				</div>				
				<div class="main-row">
					<div class="input">
						<center style="font-weight: bold">Longitude</center>
						<input type="text" id="logitude" placeholder="Longitude" name="logitude">
					</div>
				</div>
				<div class="main-row">
					<div class="input">
						<center style="font-weight: bold">Latitude</center>
						<input type="text" id="latitude" placeholder="Latitude" name="latitude">
					</div>
				</div>
				<div class="main-row">
					<div class="input">
						<center style="font-weight: bold">School Server Local IP</center>
						<input type="text" id="school_ip" placeholder="School Server IP" name="school_ip">
					</div>
				</div>	
				<div class="main-row">
					<div class="input">
						<center style="font-weight: bold">School Emblem</center>
						<input type="file" name="school_emblem" id="school_emblem" />
						<input type="hidden" id="emblem_type" name="emblem_type" />
					</div>
				</div>				
			</div -->			
			<div id="securityinfo" name="securityinfo">
				<div class="main-row">
                                	<div class="input">
                                        	<img src="<?php echo $captchaimg; ?>">
                                	</div>
                        	</div>
				<div class="main-row">
					<div class="input">
						<center style="font-weight: bold">Enter Security Code</center>
						<input type="text" id="sec_code" placeholder="Security Code" name="sec_code">
					</div>
				</div>
				<div class="footer">
					<button type="button" class="primary-btn" id="btnInstall" name="btnInstall"  >Start Installation</button>
					<button type="reset" class="secondary-btn">Reset</button>
				</div>
			</div>
			<div id="uploadinfo" name="uploadinfo">
				<div class="main-row">
                                        <div class="input">
                                                <center style="font-weight: bold">Profile Photo</center>
                                                <input type="file" name="profile_photo" id="profile_photo" />
                                                <input type="hidden" id="photo_type" name="photo_type" />
                                        </div>
                                </div> 
			</div>
			<div id="downloadinstaller" name="downloadinstaller">
				<button type="button" class="primary-btn" id="btnDownload" name="btnDownload"  >Download SchoolLMS Android Installer</button>
			</div>
		<!-- end /.footer -->
	<input type="hidden" class="form-control"  value="demoform" id="type" name="type">
	<input type="hidden" class="form-control"  value="1" id="school_id" name="school_id">
	<input type="hidden" class="form-control"  value="<?php echo $deviceType; ?>" id="device_type" name="device_type">
	<input type="hidden" class="form-control"  value="<?php echo $install_file; ?>" id="install_file" name="install_file">
	</form>
</div>
</div>
<script type="text/javascript" >
		$(document).ready(function () {
			var isSchoolSupport = false;
			$("#schoolinfo").hide()
			$("#principalinfo").hide()
			$("#confirminfo").hide()
			$("#securityinfo").hide()
			$("#uploadinfo").hide()
			$("#roleinfo").hide()
			$("#install_type_id").on('change', function() {
				switch($("#install_type_id option:selected").text()){

					case 'support':
						alert('This role installer is COMING SOON!!!');
                                               location.reload();

 						//HIDE
						$("#info").hide();
                                                isSchoolSupport = false;
						//SHOW					
						$("#supportinfo").show()
						isSchoolSupport = true;
						break;

					/*case 'teacher':
						alert('This role installer is COMING SOON!!!');
                                               location.reload();
				
						//HIDE
						$("#supportinfo").hide();
						isSchoolSupport = false;
						//SHOW
						$("#teacherinfo").hide();
                                                isSchoolTeacher = true;
						break;*/

					 case 'learner':
						alert('This role installer is COMING SOON!!!');
                                               location.reload();

                                                //HIDE
                                                $("#info").hide();
                                                isSchoolSupport = false;
                                                //SHOW                  
                                                $("#learnerinfo").show()
                                                isSchoolLearner = true;
                                                break;

                                        case 'parent':
						alert('This role installer is COMING SOON!!!');
                                               location.reload();

                                                //HIDE
                                                $("#supportinfo").hide();
                                                isSchoolSupport = false;
                                                //SHOW
                                                $("#parentinfo").hide();
                                                isSchoolParent = true;
                                                break;

					 case 'principal':
					 case 'teacher':
                                                //HIDE
                                                $("#info").hide();
                                                isSchoolSupport = false;
                                                //SHOW                  
                                                $("#principalinfo").show()
                                                isSchoolPrincipal = true;
                                                break;

                                        case 'mec':
						alert('This role installer is COMING SOON!!!');
                                               location.reload();

                                                //HIDE
                                                $("#supportinfo").hide();
                                                isSchoolSupport = false;
                                                //SHOW
                                                $("#mecinfo").hide();
                                                isMEC = true;
                                                break;

					default:	
					       alert('This role installer is COMING SOON!!!');
					       location.reload();
					       break;
						
				}
			});
			
			if ($("#device_type").val() == "tablet" || $("#device_type").val() == "phone"){
				 $('#downloadinstaller').show(); 
			} else if($("#device_type").val() == "computer" && ($("#install_type_id option:selected").text() == "learner" || $("#install_type_id option:selected").text() == "parent")) {
				$('#downloadinstaller').hide();	
				alert("Please contact your School Admin to update your info");
				location.reload();
			} else {
				 $('#downloadinstaller').hide();
				 $('#roleinfo').show(); 
			}
			
			$("#btnSubmit").on('click', function() {
				var access_id = $("#access_id").val();
                                //var surname = $("#surname").val();
				//alert(access_id);
				var userdetails = getJasonData("action=CONFIRMINSTALL&access_id="+access_id);
				if (userdetails !== 'none'){ 
					$('#confirminfo').html("Please confirm if the following details are yours<br><br><b>"+userdetails+"</b><br><br> <button type='button' class='secondary-btn' id='btnYes' name='btnYes'>YES, ITS ME</button><button type='button' class='primary-btn' id='btnNo' name='btnNo'  >NO, ITS NOT ME</button>");
					$('#confirminfo').show();	
				} else {
					alert('Your details are not found. Please contact your School Admin');
                                        location.reload();

				}
			});
		
			$("#btnYes").on('click', function() {
				$("#uploadinfo").show();	
			});
	
			 $("#btnNo").on('click', function() {
				alert("Please contact your School Admin to update your info");
                        });

			$("#btnDownload").on('click', function() {
				window.open($("#install_file").val());
			});

			$("#btnInstall").on('click', function() {
				var thecode = "<?php echo $_SESSION['captcha2']["code"] ?>";
				//alert(thecode);
				if(thecode != $("#sec_code").val())
				{
					alert("You have entered an incorrect security Code");
				}
				else{
					
					var param = "action=install&"+$("#frmInstall").serialize();
					//alert(param);
					
					var name = $("#name").val();
					var surname = $("#surname").val();
					var email = $("#email_address").val();
					var type_id = $("#install_type_id").val();
					var school_type = $("#school_type").val();
					var school_name_id = $("#school_name").val();
					var logitude = $("#logitude").val();
					var latitude = $("#latitude").val();
					var school_ip = $("#school_ip").val();
					var school_emblem = $("#school_emblem").val();
					$("#user_type").val($("#install_type_id option:selected").text());
					$("#the_school_name").val($("#school_name option:selected").text());
					var error = false;
					//alert(school_emblem);
					//return;
					var message = "Please Fix the following :  \n\n";
					
					if(type_id.trim() == "0")
					{
						message = message + "Select Role\n";
						error = true;
					}
					
					alert(email);
					if(email.trim() == "")
					{
						message = message + "Enter Email\n";
						error = true;
					}
					else{						
						if(!validateEmail(email))
						{
							message = message + "Enter valid Email Address\n";
							error = true;
						}
					}
					
					if(isSchoolSupport)
					{
						if(school_type.trim() == "" || school_ip.trim() == "" || latitude.trim() == "" || logitude.trim() == "" || school_emblem.trim() == "")
						{
							message = message + "Enter All School Information \n";
							error = true;
						}
						else{
							
							var filename = school_emblem;
							var extension = filename.replace(/^.*\./, '');
							if (extension == filename) {
								extension = '';
							} else {
								extension = extension.toLowerCase();
							}
							
							$("#emblem_type").val(extension);
							if(extension != "jpg" && extension != "jpeg" && extension != "png"  )
							{
								alert("The School Emblem must be an image with extension jpg, jpeg or png");
								return;
							}							
						}
					}
										
					
					//alert(param);
					if(error)
					{
						alert(message);
						return;
					}
					
					$("#frmInstall").submit();
                                        window.open($("#install_file").val());
					//var result = getJasonData(param);
					//alert(result);
					//result = result.split("-");
					//alert(result[1]);
					//window.location = result[1];
				}
			});
		
		$.widget( "custom.combobox", {
		  _create: function() {
			this.wrapper = $( "<span>" )
			  .addClass( "custom-combobox" )
			  .insertAfter( this.element );
	 
			this.element.hide();
			this._createAutocomplete();
			this._createShowAllButton();
		  },
	 
		  _createAutocomplete: function() {
			var selected = this.element.children( ":selected" ),
			  value = selected.val() ? selected.text() : "";
	 
			this.input = $( "<input>" )
			  .appendTo( this.wrapper )
			  .val( value )
			  .attr( "title", "" )
			  .addClass( "custom-combobox-input ui-widget ui-widget-content ui-state-default ui-corner-left" )
			  .autocomplete({
				delay: 0,
				minLength: 0,
				source: $.proxy( this, "_source" )
			  })
			  .tooltip({
				classes: {
				  "ui-tooltip": "ui-state-highlight"
				}
			  });
	 
			this._on( this.input, {
			  autocompleteselect: function( event, ui ) {
				ui.item.option.selected = true;
				this._trigger( "select", event, {
				  item: ui.item.option
				});
			  },
	 
			  autocompletechange: "_removeIfInvalid"
			});
		  },
	 
		  _createShowAllButton: function() {
			var input = this.input,
			  wasOpen = false;
	 
			$( "<a>" )
			  .attr( "tabIndex", -1 )
			  .attr( "title", "Show All Items" )
			  .tooltip()
			  .appendTo( this.wrapper )
			  .button({
				icons: {
				  primary: "ui-icon-triangle-1-s"
				},
				text: false
			  })
			  .removeClass( "ui-corner-all" )
			  .addClass( "custom-combobox-toggle ui-corner-right" )
			  .on( "mousedown", function() {
				wasOpen = input.autocomplete( "widget" ).is( ":visible" );
			  })
			  .on( "click", function() {
				input.trigger( "focus" );
	 
				// Close if already visible
				if ( wasOpen ) {
				  return;
				}
	 
				// Pass empty string as value to search for, displaying all results
				input.autocomplete( "search", "" );
			  });
		  },
	 
		  _source: function( request, response ) {
			var matcher = new RegExp( $.ui.autocomplete.escapeRegex(request.term), "i" );
			response( this.element.children( "option" ).map(function() {
			  var text = $( this ).text();
			  if ( this.value && ( !request.term || matcher.test(text) ) )
				return {
				  label: text,
				  value: text,
				  option: this
				};
			}) );
		  },
	 
		  _removeIfInvalid: function( event, ui ) {
	 
			// Selected an item, nothing to do
			if ( ui.item ) {
			  return;
			}
	 
			// Search for a match (case-insensitive)
			var value = this.input.val(),
			  valueLowerCase = value.toLowerCase(),
			  valid = false;
			this.element.children( "option" ).each(function() {
			  if ( $( this ).text().toLowerCase() === valueLowerCase ) {
				this.selected = valid = true;
				return false;
			  }
			});
	 
			// Found a match, nothing to do
			if ( valid ) {
			  return;
			}
	 
			// Remove invalid value
			this.input
			  .val( "" )
			  .attr( "title", value + " didn't match any item" )
			  .tooltip( "open" );
			this.element.val( "" );
			this._delay(function() {
			  this.input.tooltip( "close" ).attr( "title", "" );
			}, 2500 );
			this.input.autocomplete( "instance" ).term = "";
		  },
	 
		  _destroy: function() {
			this.wrapper.remove();
			this.element.show();
		  }
		});
	 
		$( "#school_name" ).combobox();
		
		
		});
		
		function validateEmail(email) {
			var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			return re.test(email);
		}
		
		
		
		
</script>
