<h1>School Lms</h1>
<div class="main">
	<form class="contact-forms">
		<!-- end /.header-->

			<!-- start name -->
			<div class="first-line">
				<div class="span6 main-row">
					<div class="input">
						
						<input type="text" id="first_name" name="first_name" placeholder="First name">
					</div>
				</div>
				<div class="span6 main-row">
					<div class="input">
						
						<input type="text" id="last_name" name="last_name" placeholder="Last name">
					</div>
				</div>
			</div>
			<!-- end name -->

			<!-- start email phone -->
			<div class="first-line">
				<div class="span6 main-row">
					<div class="input">
						
						<input type="email" placeholder="Email" id="email" name="email">
					</div>
				</div>
				<div class="span6 main-row">
					<div class="input">
						
						<input type="text" placeholder="Phone" id="phone" name="phone">
					</div>
				</div>
			</div>
		

			<!-- start country -->
			<div class="main-row">
				<label class="input select">
					<select name="country">
						<option value="none" selected="" disabled="">Select country</option>
						
						<option value="Philippines">Philippines</option>
						<option value="Portugal">Portugal</option>
						<option value="South Africa">South Africa</option>
						<option value="Spain">Spain</option>
						<option value="Switzerland">Switzerland</option>
					</select>
					<i></i>
				</label>
			</div>
			<!-- end country -->

			<!-- start city post code -->
			<div class="first-line">
				<div class="span8 main-row">
					<div class="input">
						
						<input type="text" id="city" placeholder="City" name="city">
					</div>
				</div>
				
			</div>
			<!-- end city post code -->

			<!-- start address -->
			<div class="main-row">
				<div class="input">
					
					<input type="text" id="address" placeholder="Address" name="address">
				</div>
			</div>
			<!-- end address -->


			<!-- start position -->
			<div class="main-row">
				<label class="input select">
					<select name="position">
						<option value="none" selected disabled="">Choose desired position</option>
						<option value="tech lead">Operator</option>
						<option value="product manager"> Manager</option>
						<option value="senior developer"> Developer</option>
						<option value="QA specialist">Designer</option>
					</select>
					<i></i>
				</label>
			</div>
			<!-- end position -->

			<!-- start message -->
			<div class="main-row">
				<div class="input">
					<textarea placeholder="Additional info" spellcheck="false" name="message"></textarea>
						<span class="tooltip tooltip-right-top">Key Skills</span>
				</div>
			</div>
			<!-- end message -->

			<!-- start files -->
			
				<div class=" main-row">
					<label class="input append-small-btn">
						<div class="upload-btn">
							Browse
							<input type="file" name="file1" onchange="document.getElementById('file1_input').value = this.value;">
						</div>
						<input type="text" id="file1_input" readonly="" placeholder="Add your CV">
						<span class="hint">Only: pdf / doc Size: lessthan 1 Mb</span>
					</label>
				</div>
				
			
			<!-- end files -->

	
		<!-- end /.content -->

		<div class="footer">
			<button type="submit" class="primary-btn">Send</button>
			<button type="reset" class="secondary-btn">Reset</button>
		</div>
		<!-- end /.footer -->

	</form>
</div>
