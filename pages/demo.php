<?php

$roles = "<select name='type_id' id='type_id' class='form-control' >";
$installroles = "<select name='install_type_id' id='install_type_id' class='form-control' >";

$sql = "select * from schoollms_schema_userdata_user_type";
$roles .= "<option value='0'>Select Role</option>";			
$data->execSQL($sql);
while($row = $data->getRow())
{
	$roles .= "<option value='$row->type_id'>$row->type_title</option>";	
	$installroles .= "<option value='$row->type_id'>$row->type_title</option>";		
}
$roles .= "</select>";
$installroles .= "</select>";

$conferencename = "<select  name='conferencename' id='conferencename' class='form-control'>";
$sql = "select * from schoollms_schema_userdata_conference";
$data->execSQL($sql);
while($row = $data->getRow())
{
	$conferencename .= "<option value='$row->conference_id'>$row->conference_name</option>";			
}
$conferencename .= "</select>";

$_SESSION['captcha'] = simple_php_captcha();
$captchaimg = "lib/mycaptcha.php?code=".$_SESSION['captcha']["code"];
//$captcha1 = getCaptcha();

$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
//echo $actual_link;
//var_dump(parse_url($actual_link));
?>
<div style="margin-top: 95px; padding-top: 95px;">
<h1 style="color: #428bca "><center>Experience Our Solution</center></h1>
<div class="main">
	<form class="contact-forms" id="frmDemo" name="frmDemo">
		<!-- end /.header-->
		

			<!-- start country -->
			<div class="main-row">
				<center style="font-weight: bold">Select Your Role</center>
				<label class="input select">
					<?php echo  $roles; ?>
					<i></i>
				</label>
			</div>
			
			<div class="main-row" id="conferencenamerow" name="conferencenamerow">
			Select Conference
				<label class="input select">
					<?php echo  $conferencename; ?>
					<i></i>
				</label>
			</div>
			
			<div class="first-line">
				<div class="span8 main-row">
					<center style="font-weight: bold">Enter First Name</center>
					<div class="input">						
						<input type="text" id="name" placeholder="First Name" name="name">
					</div>
				</div>
				
			</div>
			<div class="first-line">
				<div class="span8 main-row">
				<center style="font-weight: bold">Enter Surname</center>
					<div class="input">						
						<input type="text" id="surname" placeholder="Surname" name="surname">
					</div>
				</div>
				
			</div>
    <!--			<div class="main-row" id="rwmaritalstatus" name="rwmaritalstatus">
                                    Select Marital Status
                                    <label class="input select">
                                            <select id="marital_status" name="marital_status" class="form-control">
                                                    <option value="0">Select Marital Status</option>
                                                    <option value="Married">Married</option>
                                                    <option value="Single">Single</option>
                                                    <option value="Divorced">Divorced</option>					
                                                    <option value="Engaged">Engaged</option>
                                            </select>
                                            <i></i>
                                    </label>
                            </div>-->
			<!-- end city post code -->

			<!-- start address -->
			<div class="main-row">
				<div class="input">
					<center style="font-weight: bold">Enter Email Address</center>
					<input type="text" id="email" placeholder="Email Address" name="email">
				</div>
			</div>
			<div class="main-row">
				<center style="font-weight: bold">Confirm Email Address</center>
				<div class="input">					
					<input type="text" id="confirmemail" placeholder="Confirm Email Address" name="confirmemail">
				</div>
			</div>
			<div class="main-row">
				<div class="input">
					
					<img src="<?php echo $captchaimg; ?>">
				</div>
			</div>
			
			<div class="main-row">
				<div class="input">
					<center style="font-weight: bold">Enter Security Code</center>
					<input type="text" id="code" placeholder="Security Code" name="code">
				</div>
			</div>
			

	
		<!-- end /.content -->

		<div class="footer">
			<button type="button" class="primary-btn" id="btnDemo" name="btnDemo"  >Register</button>
			<button type="reset" class="secondary-btn">Reset</button>
		</div>
		<!-- end /.footer -->
	<input type="hidden" class="form-control"  value="demoform" id="type" name="type">
	<input type="hidden" class="form-control"  value="1" id="school_id" name="school_id">
	</form>
</div>
</div>
<script type="text/javascript" >
		$(document).ready(function () {
			
			$("#rwmaritalstatus").hide();
			$("#conferencenamerow").hide();
			$("#type_id").on('change', function() {
				if( $("#type_id option:selected").text() == "Conference User")
				{					
					$("#conferencenamerow").show();
					$("#rwmaritalstatus").show();
					
				}
				else{					
					$("#conferencenamerow").hide();
					$("#rwmaritalstatus").hide();
				}
			});
			
			/*$("#type_id").on('change', function() {
				//alert($("#type_id option:selected").text());
				if( $("#type_id option:selected").text() == "Conference Demo User")
				{
					$("#rwmaritalstatus").show();
					$("#rwname").hide();
					$("#name").val("Conference Demo");
					$("#rwsurname").hide();
				}
				else{
					$("#rwmaritalstatus").hide();
					$("#rwname").show();
					$("#name").val("");
					$("#rwsurname").show();
				}
			});*/
			$("#btnDemo").on('click', function() {
				if($("#email").val() != $("#confirmemail").val())
				{
					alert("Email and Confirm Email are not the same");
					return;
				}
				
				var thecode = "<?php echo $_SESSION['captcha']["code"]; ?>";
				
				//alert(thecode);
				if(thecode != $("#code").val())
				{
					alert("You have entered an incorrect security Code");
				}
				else{
					
					var param = "action=register&"+$("#frmDemo").serialize();
					//alert(param);
					
					var name = $("#name").val();
					var surname = $("#surname").val();
					var email = $("#email").val();
					var type_id = $("#type_id").val();
					var error = false;
					//alert(name);
					//alert(surname);
					
					var message = "Please Fix the following :  \n\n";
					/*if(name.trim() == "")
					{
						message = message + "Enter Name\n";
						error = true;
					}*/
					
					/*if(surname.trim() == "")
					{
						message = message + "Enter Surname\n";
						error = true;
					}*/
					
					if(email.trim() == "")
					{
						message = message + "Enter Email\n";
						error = true;
					}
					
					if(type_id.trim() == "0")
					{
						message = message + "Select Role\n";
						error = true;
					}
					
					if(!validateEmail(email))
					{
						message = message + "Enter valid Email Address\n";
						error = true;
					}
					//alert(param);
					if(error)
					{
						alert(message);
						return;
					}
					var result = getJasonData(param);
					//alert(result);
					result = result.split("-");
					//alert(result[1]);
					window.location = result[1];
				}
			});
		});
		
		function validateEmail(email) {
			var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			return re.test(email);
		}
</script>
