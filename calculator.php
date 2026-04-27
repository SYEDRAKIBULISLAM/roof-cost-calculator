<?php

/**
Plugin Name: Roof Calculator
Description: Calculator https://roofcosts.net/ 
Author: Mahmudul Hasan (mahmud.linked@gmail.com)
Version: 1.0
Author URI: mahmud.linked@gmail.com

**/

// Exit if accessed directly


if(!defined('ABSPATH')){
	exit;
}

//Load Scripts...
require_once(plugin_dir_path(__FILE__).'/includes/calculator-scripts.php');
require_once(plugin_dir_path(__FILE__).'/includes/leads.php');




function roof_calculator() {
	ob_start();
	?>

	<div class="calculator_area">

		
		<div class="calculator_area_inner">


			<div class="steps_wrap">

				<form class="calculator_form" action="">
					<input type="hidden" name="trustedform_cert_url" id="trustedform_cert_url">


				
					<div class="step step_1 active">
						<div class="step_header_title">Get your Roofing <b>Price Today!</b></div>
						<p><img class="map_icon" src="<?php echo plugins_url('img/map.gif', __FILE__); ?>" /></p>

						<div class="single_input">
							
							<div class="form_label" for="">
								<p class="label_text text-center" style="margin-bottom:10px;">What is your ZIP Code?</p>
							</div>
							<div class="form_input_wrap">
								<input class="input text-input zip_code center-input" name="zip_code" placeholder="Zip Code" type="text">
								<p class="error zip_error">Please enter a valid Zip code!</p>
							</div>

						</div>
						
					</div>
					
					<div class="step step_2">

						<p><img class="icon_section" src="<?php echo plugins_url('img/home-sq-ft.png', __FILE__); ?>" /></p>

						<div class="single_input ">
							<div class="form_label step_header" for="">
								<p class="step_title">What is the square footage of your home?</p>
							</div>
						</div>

						<div class="single_input">
							<div class="input-group btn_input">
								<input class="input text_input feefeeet" type="number" placeholder="Sq. Ft." id="square_footage" min="1"> 
								<p class="error" id="square_footage_error">Please enter square footage value!</p>
							</div>
							
						</div>



					</div>

					<div class="step step_3">

						<p><img class="icon_section" src="<?php echo plugins_url('img/home-type.svg', __FILE__); ?>" /></p>

						<div class="single_input ">
							<div class="form_label step_header" for="">
								<p class="step_title">What type of home do you live in?</p>
							</div>
						</div>
						
						<div class="area_group_wrapper">
							<div class="input-group radio">
								<input type="radio" name="home_type" id="single_family_home" value="Single-Family Home">
								<label class="area_label" for="single_family_home">
									<img class="desktop radio" src="<?php echo plugins_url('img/chk.png', __FILE__); ?>" alt="">
									<img class="desktop radio-checked" src="<?php echo plugins_url('img/chk-w.png', __FILE__); ?>" alt="">
									<img class="mobile radio" src="<?php echo plugins_url('img/chk.png', __FILE__); ?>" alt="">
									<img class="mobile radio-checked" src="<?php echo plugins_url('img/chk.png', __FILE__); ?>" alt="">									
									Single-Family Home
								</label>
							</div>

							<div class="input-group radio">
								<input type="radio" name="home_type" id="townhome_duplex" value="Townhome / Row Home / Duplex">
								<label class="area_label" for="townhome_duplex">
									<img class="desktop radio" src="<?php echo plugins_url('img/chk.png', __FILE__); ?>" alt="">
									<img class="desktop radio-checked" src="<?php echo plugins_url('img/chk-w.png', __FILE__); ?>" alt="">
									<img class="mobile radio" src="<?php echo plugins_url('img/chk.png', __FILE__); ?>" alt="">
									<img class="mobile radio-checked" src="<?php echo plugins_url('img/chk.png', __FILE__); ?>" alt="">			
									Townhome / Row Home / Duplex
								</label>
							</div>
							
							<div class="input-group radio">
								<input type="radio" name="home_type" id="manufactured_mobile_home" value="Manufactured / Mobile Home">
								<label class="area_label" for="manufactured_mobile_home">
									<img class="desktop radio" src="<?php echo plugins_url('img/chk.png', __FILE__); ?>" alt="">
									<img class="desktop radio-checked" src="<?php echo plugins_url('img/chk-w.png', __FILE__); ?>" alt="">
									<img class="mobile radio" src="<?php echo plugins_url('img/chk.png', __FILE__); ?>" alt="">
									<img class="mobile radio-checked" src="<?php echo plugins_url('img/chk.png', __FILE__); ?>" alt="">		
									Manufactured / Mobile Home
								</label>
							</div>
							
						</div>						




					</div>



					<div class="step step_4">
						<p><img class="icon_section" src="<?php echo plugins_url('img/story.png', __FILE__); ?>" /></p>

						<div class="single_input ">
							<div class="form_label step_header" for="">
								<p class="step_title">How many stories is your home?</p>
							</div>
						</div>					
						
						<div class="single_input ">


							<div class="area_group_wrapper">
								
								<div class="input-group radio">
									<input type="radio" name="story" id="one_story" value="One Story">
									<label class="area_label" for="one_story">
										<img class="desktop radio" src="<?php echo plugins_url('img/chk.png', __FILE__); ?>" alt="">
										<img class="desktop radio-checked" src="<?php echo plugins_url('img/chk-w.png', __FILE__); ?>" alt="">
										<img class="mobile radio" src="<?php echo plugins_url('img/chk.png', __FILE__); ?>" alt="">
										<img class="mobile radio-checked" src="<?php echo plugins_url('img/chk.png', __FILE__); ?>" alt="">	
										One Story
									</label>
								</div>
								
								<div class="input-group radio">
									<input type="radio" name="story" id="two_story" value="Two Stories">
									<label class="area_label" for="two_story">
										<img class="desktop radio" src="<?php echo plugins_url('img/chk.png', __FILE__); ?>" alt="">
										<img class="desktop radio-checked" src="<?php echo plugins_url('img/chk-w.png', __FILE__); ?>" alt="">
										<img class="mobile radio" src="<?php echo plugins_url('img/chk.png', __FILE__); ?>" alt="">
										<img class="mobile radio-checked" src="<?php echo plugins_url('img/chk.png', __FILE__); ?>" alt="">	
										Two Stories
									</label>
								</div>

								<div class="input-group radio">
									<input type="radio" name="story" id="three_story" value="Three Stories">
									<label class="area_label" for="three_story">
										<img class="desktop radio" src="<?php echo plugins_url('img/chk.png', __FILE__); ?>" alt="">
										<img class="desktop radio-checked" src="<?php echo plugins_url('img/chk-w.png', __FILE__); ?>" alt="">
										<img class="mobile radio" src="<?php echo plugins_url('img/chk.png', __FILE__); ?>" alt="">
										<img class="mobile radio-checked" src="<?php echo plugins_url('img/chk.png', __FILE__); ?>" alt="">	
										Three Stories
									</label>
								</div>

							</div>
							
						</div>
						
					</div>


					<div class="step step_5">

						<p><img class="icon_section" src="<?php echo plugins_url('img/pitch.png', __FILE__); ?>" /></p>

						<div class="single_input ">
							<div class="form_label step_header" for="">
								<p class="step_title">What is the slope or pitch of your roof?</p>
							</div>
						</div>
						
						<div class="area_group_wrapper">
							<div class="input-group radio">
								<input type="radio" name="pitch" id="flat_roof" value="Flat Roof">
								<label class="area_label" for="flat_roof">
									<img class="desktop radio" src="<?php echo plugins_url('img/chk.png', __FILE__); ?>" alt="">
									<img class="desktop radio-checked" src="<?php echo plugins_url('img/chk-w.png', __FILE__); ?>" alt="">
									<img class="mobile radio" src="<?php echo plugins_url('img/chk.png', __FILE__); ?>" alt="">
									<img class="mobile radio-checked" src="<?php echo plugins_url('img/chk.png', __FILE__); ?>" alt="">									
									Flat Roof
								</label>
							</div>
							
							<div class="input-group radio">
								<input type="radio" name="pitch" id="low_pitch" value="Low Pitch">
								<label class="area_label" for="low_pitch">
									<img class="desktop radio" src="<?php echo plugins_url('img/chk.png', __FILE__); ?>" alt="">
									<img class="desktop radio-checked" src="<?php echo plugins_url('img/chk-w.png', __FILE__); ?>" alt="">
									<img class="mobile radio" src="<?php echo plugins_url('img/chk.png', __FILE__); ?>" alt="">
									<img class="mobile radio-checked" src="<?php echo plugins_url('img/chk.png', __FILE__); ?>" alt="">		
									Low Pitch
								</label>
							</div>

							<div class="input-group radio">
								<input type="radio" name="pitch" id="high_pitch" value="High Pitch">
								<label class="area_label" for="high_pitch">
									<img class="desktop radio" src="<?php echo plugins_url('img/chk.png', __FILE__); ?>" alt="">
									<img class="desktop radio-checked" src="<?php echo plugins_url('img/chk-w.png', __FILE__); ?>" alt="">
									<img class="mobile radio" src="<?php echo plugins_url('img/chk.png', __FILE__); ?>" alt="">
									<img class="mobile radio-checked" src="<?php echo plugins_url('img/chk.png', __FILE__); ?>" alt="">			
									High Pitch
								</label>
							</div>
							
						</div>						




					</div>

	

					<div class="step step_6">

					<p><img class="icon_section" src="<?php echo plugins_url('img/roof.png', __FILE__); ?>" /></p>

						<div class="single_input ">
							<div class="form_label step_header" for="">
								<p class="step_title">What type of roofing are you interested in?</p>
							</div>
						</div>

						
						<div class="area_group_wrapper">
								
								
								
								<div class=" input-group radio" id="asphalt_shingle_roof_section">
									<input type="radio" name="roof_type" id="asphalt_shingle_roof" value="Asphalt Shingle Roof">
									<label class="area_label" for="asphalt_shingle_roof">
										<img class="desktop radio" src="<?php echo plugins_url('img/chk.png', __FILE__); ?>" alt="">
										<img class="desktop radio-checked" src="<?php echo plugins_url('img/chk-w.png', __FILE__); ?>" alt="">
										<img class="mobile radio" src="<?php echo plugins_url('img/chk.png', __FILE__); ?>" alt="">
										<img class="mobile radio-checked" src="<?php echo plugins_url('img/chk.png', __FILE__); ?>" alt="">	
										Asphalt Shingle Roof
									</label>
								</div>
		
								<div class="input-group radio" id="metal_roof_section">
									<input type="radio" name="roof_type" id="metal_roof" value="Metal Roof">
									<label class="area_label" for="metal_roof">
										<img class="desktop radio" src="<?php echo plugins_url('img/chk.png', __FILE__); ?>" alt="">
										<img class="desktop radio-checked" src="<?php echo plugins_url('img/chk-w.png', __FILE__); ?>" alt="">
										<img class="mobile radio" src="<?php echo plugins_url('img/chk.png', __FILE__); ?>" alt="">
										<img class="mobile radio-checked" src="<?php echo plugins_url('img/chk.png', __FILE__); ?>" alt="">		
										Metal Roof
									</label>
								</div>

								<div class="input-group radio" id="stone-coated_steel_roof_section">
									<input type="radio" name="roof_type" id="stone-coated_steel_roof" value="Stone-Coated Steel Roof">
									<label class="area_label" for="stone-coated_steel_roof">
										<img class="desktop radio" src="<?php echo plugins_url('img/chk.png', __FILE__); ?>" alt="">
										<img class="desktop radio-checked" src="<?php echo plugins_url('img/chk-w.png', __FILE__); ?>" alt="">
										<img class="mobile radio" src="<?php echo plugins_url('img/chk.png', __FILE__); ?>" alt="">
										<img class="mobile radio-checked" src="<?php echo plugins_url('img/chk.png', __FILE__); ?>" alt="">		
										Stone-Coated Steel Roof
									</label>
								</div>

								<div class="input-group radio" id="rubber_roof_section">
									<input type="radio" name="roof_type" id="rubber_roof" value="Rubber Roof">
									<label class="area_label" for="rubber_roof">
										<img class="desktop radio" src="<?php echo plugins_url('img/chk.png', __FILE__); ?>" alt="">
										<img class="desktop radio-checked" src="<?php echo plugins_url('img/chk-w.png', __FILE__); ?>" alt="">
										<img class="mobile radio" src="<?php echo plugins_url('img/chk.png', __FILE__); ?>" alt="">
										<img class="mobile radio-checked" src="<?php echo plugins_url('img/chk.png', __FILE__); ?>" alt="">		
										Rubber Roof
									</label>
								</div>

								<div class="input-group radio" id="liquid_applied_roof_section">
									<input type="radio" name="roof_type" id="liquid_applied_roof" value="Liquid Applied Roof">
									<label class="area_label" for="liquid_applied_roof">
										<img class="desktop radio" src="<?php echo plugins_url('img/chk.png', __FILE__); ?>" alt="">
										<img class="desktop radio-checked" src="<?php echo plugins_url('img/chk-w.png', __FILE__); ?>" alt="">
										<img class="mobile radio" src="<?php echo plugins_url('img/chk.png', __FILE__); ?>" alt="">
										<img class="mobile radio-checked" src="<?php echo plugins_url('img/chk.png', __FILE__); ?>" alt="">		
										Liquid Applied Roof
									</label>
								</div>
		
		
								
							</div>




					</div>

					<div class="step step_7">
						
						<p><img class="icon_section" src="<?php echo plugins_url('img/identify.png', __FILE__); ?>" /></p>

						<div class="single_input ">
							<div class="form_label step_header" for="">
								<p class="step_title">Almost Done!</p>
							</div>
						</div>
						
						
							
						<div class="form_row">
							<div class="form_group">
								<input type="text" class="input text_input form_input fname" id="first_name" name="first_name" placeholder="First Name" required>
							</div>
						</div>
						<div class="form_row">
							<div class="form_group">
								<input type="text" class="input text_input form_input lname" id="last_name" name="last_name" placeholder="Last Name" required>
							</div>
						</div>
						<div class="form_row">
							<div class="form_group">
								<input type="email" class="input text_input form_input email" id="email" name="email" placeholder="Email" required>
							</div>
						</div>
						<div class="form_row">
							<div class="form_group input_group_phone">
								<span class="input-group-text" id="input-group-text">+1</span>
								<input type="tel" class="input text_input form_input phone" id="phone" name="phone" placeholder="Phone Ex: (123) 456-7890" oninput="validateUSPhoneNumber()" required>
							</div>
						</div>

						<div class="form_row">
							<input class="consent" name="consent" id="consent" type="checkbox">
							<label for="consent">
								<img class="checked" src="<?php echo plugins_url('img/checkbox-checked.svg', __FILE__); ?>" />
								<img class="checkbox" src="<?php echo plugins_url('img/checkbox.svg', __FILE__); ?>" />
								<p class="consent_text">
								By submitting this form, you agree to receive marketing calls, automated follow-ups, and texts from Roof Costs Partners at the number provided. Consent is not a condition of purchase. Reply STOP to opt out. Message/data rates may apply. See our <a href="https://roofcosts.net/terms-of-services/">Terms</a> and <a href="https://roofcosts.net/privacy-policy/">Privacy Policy</a> for details.
</p>
							</label>
						</div>
							
						



					</div>




				</form>


			</div>

			<div class="progress_wrap">
				<div class="main_bar">
					<div class="progress">
						<p class="percentatge">17%</p>
					</div>
				</div>
				
			</div>

			<div class="navi_btn">
				<div>
					<button class="prev_button" onclick="goToPrevStep()">
						<img class="left-arrow-icon" src="<?php echo plugins_url('img/left-arrow.svg', __FILE__); ?>" alt="">
					<!-- <img class="white" src="/wp-content/uploads/2025/04/angle-left-white.svg" alt=""> -->
					<!-- <img class="red" src="/wp-content/uploads/2025/04/angle-left.svg" alt=""> -->
						Back
					</button>
				</div>
				<div>
					<button class="next_button" onclick="goToNextStep()">Next</button>
					<button class="submit_btn" id="submit_btn" disabled>See Estimate</button>
				</div>

			</div>
		</div>

		
		


		<div class="overlay sec_loader">
			<img class="label_icon" src="<?php echo plugins_url('img/loading.gif', __FILE__); ?>" alt="">
		</div>
	

	</div>

	<noscript>
	  <img src='https://api.trustedform.com/ns.gif' />
	</noscript>


	
	<?php return ob_get_clean(); 
		}
	?>
<?php
add_shortcode( 'roof_calculator', 'roof_calculator');