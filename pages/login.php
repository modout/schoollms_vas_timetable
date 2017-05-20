<div style="margin-top: 95px; padding-top: 95px">	
	<section>
			<h1 style="color: #428bca "><center>Existing User Online Access</center></h1>

		</section>
<div class="main" style="margin-top: 0px">
	<form class="contact-forms" id="frmLogin" name="frmLogin">
		<!-- end /.header-->
                <input type="hidden" id="q" name="q" value="<?php echo $q; ?>">
			<!-- end city post code -->
			<div class="main-row">
				<center style="font-weight: bold">Enter Identity or Passport Number</center>
				<div class="input">
					
					<input type="password" id="access_id" placeholder="ID or Passport Number" name="access_id">
				</div>
			</div>
			<!-- start address -->
			<div class="main-row">
				<div class="input">
					<center style="font-weight: bold">TEACHERS - Enter Your Initials / OTHERS - Enter Firstname(s)</center>
					<input type="text" id="name" placeholder="TEACHERS - Initials / OTHERS - Firstname(s)" name="name">
				</div>
			</div>
			<!-- end address -->
			<!-- start address -->
			<div class="main-row">
				<div class="input">
					<center style="font-weight: bold">Enter Your Surname</center>
					<input type="text" id="surname" placeholder="Surname" name="surname">
				</div>
			</div>


		<div class="footer">
			<button type="button" class="primary-btn" id="btnLogin" name="btnLogin">Login</button>
			<button type="reset" class="secondary-btn">Reset</button>
		</div>
		<!-- end /.footer -->

	</form>
</div>

</div>
<script type="text/javascript" >
		$(document).ready(function () {
			
			
			$("#btnLogin").on('click', function() {
				//alert("Ek se");
				
					var param = "action=login&"+$("#frmLogin").serialize();
					//alert(param);
					var result = getJasonData(param);
					//alert(result);
					if(result == "FAILED")
					{
						alert("Invalid Login Details");
					}
					else{
						window.location = result;
					}
				
			});
		});
</script>
