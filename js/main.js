jQuery(document).ready(function($){
  

  // let zip_code, floor_area, floor_type, sq_footage, floor_removal, consent, first_name, last_name, email, phone_number;
  let zip_code, square_footage, home_type, story, pitch, roof_type, consent, first_name, last_name, email, phone_number, trustedform_cert_url;




  

// ======================

 let currentStep = 1;
 $('.next_button').text('Get Started')
 let totalSteps = 7;


$(document).on('click', 'input[name="home_type"]', function () {
  home_type = $(this).val();
  goToNextStep();
});

$(document).on('click', 'input[name="story"]', function () {
  story = $(this).val();
  goToNextStep();
});


$(document).on('click', 'input[name="pitch"]', function () {
  pitch = $(this).val();

  if (pitch === "Flat Roof") {
    $("#asphalt_shingle_roof_section").hide();
    $("#metal_roof_section").hide();
    $("#stone-coated_steel_roof_section").hide();
    $("#rubber_roof_section").show();
    $("#liquid_applied_roof_section").show();
  } else if (pitch === "Low Pitch"){
    $("#asphalt_shingle_roof_section").show();
    $("#metal_roof_section").show();
    $("#stone-coated_steel_roof_section").show();
    $("#rubber_roof_section").show();
    $("#liquid_applied_roof_section").show();
  } else if(pitch === "High Pitch"){
    $("#asphalt_shingle_roof_section").show();
    $("#metal_roof_section").show();
    $("#stone-coated_steel_roof_section").show();
    $("#rubber_roof_section").hide();
    $("#liquid_applied_roof_section").hide();
  }
  goToNextStep();
});

$(document).on('click', 'input[name="roof_type"]', function () {
  roof_type = $(this).val();
  goToNextStep();
});

// $('input[name="floor_removal"]').change(function() {
//   // floor_type = $(this).val();
//   goToNextStep();
// });


// $('.sq_feeet').on('focusout', function () {
//   setTimeout(goToNextStep(),1000)
  
  
// });


// let typingTimer;
// const delay = 1500;


// $('.sq_feeet').on('input', function () {
//   clearTimeout(typingTimer);
//   const input = $(this);
  

//   typingTimer = setTimeout(function () {
//     input.blur();
//     setTimeout(() => {

//       //goToNextStep();
//     }, 10);
//   }, delay);
// });







let percentage;

const doPercentage = (currentStep) => {
  percentage = (currentStep/totalSteps)*100;
  percentage= Math.ceil(percentage)
  $('.percentatge').text(`${percentage}%`)
  $('.progress').css('width',`${percentage}%`)
}

doPercentage(currentStep)

$('.zip_code').on('keydown', function () {
  $('.error').hide(); // or .css('display', 'none')
});




function goToPrevStep() {

  $('.next_button').css('display','none')

  if(currentStep == 2){
    $("#calculator-modal").removeClass("is-active")
    $('.prev_button').removeClass('visible')
    $('.calculator_area').removeClass('showbig')
    $('.navi_btn').css('justify-content','center')
    $('.calculator_area').removeClass('innersteps')
    $('.next_button').css('display','block')
    $('.calculator_form').css('height','310px')
    unhideOtherSections();
  } else if(currentStep == 3){
    $('.next_button').css('display','block')
    $('.calculator_form').css('height','310px')
  } else if(currentStep == 4){
    $('.next_button').css('display','none')
    $('.calculator_form').css('height','500px')
  } else if(currentStep == 5){
    $('.next_button').css('display','none');
    $('.calculator_form').css('height','400px')
  } else if(currentStep == 6){
    $('.next_button').css('display','none');
    $('.calculator_form').css('height','400px')
  } else if(currentStep == 7){
    $('.next_button').text('Next')
    $('.submit_btn').removeClass('visible')
    $('.next_button').css('display','none')
    $('.calculator_area.innersteps').removeClass('lastStep');
    $('.calculator_form').css('height','500px')
    // $('.calculator_form').css('height','460px');

    // if (floor_area === "Driveway" || floor_area === "Walkway" || floor_area === "Pool Deck") {
    //   $('.next_button').css('display','none');
    //   currentStep = 5;
      
    // }

  }

  const steps = document.querySelectorAll('.calculator_form .step');
  const current = document.querySelector(`.step_${currentStep}`);
  const previous = document.querySelector(`.step_${currentStep-1}`);



  // Slide out current step
  current.classList.remove('active');
  current.classList.add('slide-out-right');

  // Prepare next step position
  previous.classList.add('active');


  // Allow browser to paint before transition
  setTimeout(() => {
  }, 10);

  // Clean up after transition
  setTimeout(() => {
    steps.forEach(s => s.classList.remove('slide-out-left', 'slide-out-right'));
  }, 50);

  // current.classList.remove('active', 'slide-out-right');
  // previous.classList.remove('slide-in-left');


  currentStep --;
  doPercentage(currentStep)
  
  if(currentStep == 1){
    $('.progress_wrap').removeClass('visible');
    $('.next_button').text('Get Started')
    $('.next_button').css('display','block')
  }
}

function goToNextStep() {
  if(currentStep == 1){
    $("#calculator-modal").addClass("is-active");

    zip_code = $('.zip_code').val();
    if(!zip_code || zip_code.length != 5){
      $('.error').css('display','block');
      return;
    }
    $('.next_button').css('display','block')
    $('.calculator_area').addClass('showbig');
    $('.calculator_form').css('height','310px')
    hideOtherSections();
  }
  $('.navi_btn').css('justify-content','space-between')
  $('.calculator_area').addClass('innersteps')

  if(currentStep == 2){
    square_footage = $('#square_footage').val();
    
    if(!square_footage){
      $('#square_footage_error').css('display','block');
      return;
    }
    $('.next_button').css('display','none')
    $('.calculator_form').css('height','500px')
    
  } else if(currentStep == 3){
    $('.next_button').css('display','none');
    $('.calculator_form').css('height','400px')
  } else if(currentStep == 4){
    $('.next_button').css('display','none');
    $('.calculator_form').css('height','400px')
  } else if(currentStep == 5){
    $('.next_button').css('display','none')
    roofFormSize();
    // $('.calculator_form').css('height','460px')
  } else if(currentStep == 6){
    $('.next_button').css('display','none');
    $('.calculator_form').css('height','500px')
    $('.calculator_area.innersteps').addClass('lastStep')
  }

  $('.next_button').text('Next')



  if(currentStep >= 1){
    $('.prev_button').addClass('visible')
  }




  const steps = document.querySelectorAll('.calculator_form .step');
  const current = document.querySelector(`.step_${currentStep}`);
  const next = document.querySelector(`.step_${currentStep+1}`);


  // Slide out current step
  current.classList.remove('active');
  current.classList.add('slide-out-left');

  // Prepare next step position
  next.classList.add('active');

  // Allow browser to paint before transition

  // Clean up after transition
  setTimeout(() => {
    steps.forEach(s => s.classList.remove('slide-out-left', 'slide-out-right'));
    // current.classList.remove('active', 'slide-out-right','slide-in-left');
    // next.classList.remove('slide-in-left','slide-out-right');

  }, 500);

  
  currentStep++;
  doPercentage(currentStep)
  $('.progress_wrap').addClass('visible');

  if(currentStep == 7){
    $('.submit_btn').addClass('visible')
    $('.next_button').css('display','none');
  }
  
}

function roofFormSize() {
  if (pitch === "Flat Roof") {
    $('.calculator_form').css('height','350px')
  } else if (pitch === "Low Pitch"){
    $('.calculator_form').css('height','535px')
  } else if(pitch === "High Pitch"){
    $('.calculator_form').css('height','400px')
  }

}


window.goToNextStep = goToNextStep;
window.goToPrevStep = goToPrevStep;



// ========



function setCookie(cname,cvalue,exdays) {
  var d = new Date();
  d.setTime(d.getTime() + (exdays*24*60*60*1000)); // 1 day
  var expires = "expires=" + d.toGMTString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/;SameSite=Lax";
}

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
  for(var i = 0; i < ca.length; i++) {
      var c = ca[i];
      while (c.charAt(0) == ' ') {
        c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
        return c.substring(name.length, c.length);
      }
  }
  return "";
}


const runOnResult = () => {
  let square_footage=getCookie("square_footage");
  let home_type=getCookie("home_type");
  let story=getCookie("story");
  let pitch=getCookie("pitch");
  let roof_type=getCookie("roof_type");
  let zip_code =getCookie("zip_code");
  let first_name =getCookie("first_name");
  let last_name=getCookie("last_name");
  let email =getCookie("email");
  let phone_number=getCookie("phone_number");
  let final_price=getCookie("final_price");
  let low_price=getCookie("low_price");
  let high_price=getCookie("high_price");
  let trustedform_cert_url=getCookie("trustedform_cert_url")

  
  $('#square_footage').text(square_footage)
  $('#story').text(story)
  $('#pitch').text(pitch)
  $('#roof_type').text(roof_type)
  $('#first_name').text(first_name)
  $('#low_price').text(low_price)
  $('#high_price').text(high_price)
  $('#final_price').text(final_price)
}

const resutlPage = document.querySelector('.result_page')
if(resutlPage){
  runOnResult();
  resetCookies();
}

const ifHomePage = document.querySelector('.calculator_form')
if(ifHomePage){

  resetCookies();
}

$('#submit_btn').click((e) => {
  e.preventDefault();
  disableUnloadWarning();

  // $('.overlay').addClass('visible')
  // square_footage = $('#square_footage').val();
  first_name = $('#first_name').val()
  last_name = $('#last_name').val()
  email = $('#email').val()
  phone_number = $('#phone').val()
  consent = $('input[name="consent"]').is(':checked');
  trustedform_cert_url = $('input[name="xxTrustedFormCertUrl"]').val()

  if(!first_name || !last_name || !email || !phone_number ){
    alert("All fields are required!")
    return;
  }
    

  let storyPrice;
  switch (story) {
    case 'One Story':
      storyPrice = 1;
      break;
    case 'Two Stories':
      storyPrice = 2;
      break;
    case 'Three Stories':
      storyPrice = 3;
      break;
    default:
      break;
  }

  let pitchPrice;
  switch (pitch) {
    case 'Flat Roof':
      pitchPrice = 1.00;
      break;
    case 'Low Pitch':
      pitchPrice = 1.07;
      break;
    case 'High Pitch':
      pitchPrice = 1.25;
      break;
    default:
      break;
  }

  let materialPrice;
  switch (roof_type) {
    case 'Asphalt Shingle Roof':
      materialPrice = 12.00;
      break;
    case 'Metal Roof':
      materialPrice = 17.00;
      break;
    case 'Stone-Coated Steel Roof':
      materialPrice = 18.00;
      break;
    case 'Rubber Roof':
      materialPrice = 16.00;
      break;
    case 'Liquid Applied Roof':
      materialPrice = 13.00;
      break;
    default:
      break;
  }

  let roof_area = ( square_footage / storyPrice ) * pitchPrice;
  let estimated_cost = roof_area * materialPrice;


  function formatNumberWithCommas(num, decimals = 2) {
    return Number(num)
      .toFixed(decimals)
      .replace(/\B(?=(\d{3})+(?!\d))/g, ",");
  }
  

  let low_price = estimated_cost - ( (estimated_cost * 20) /100 )
  let high_price = estimated_cost + ( (estimated_cost * 20) /100 )

  let final_price = formatNumberWithCommas(estimated_cost);
  low_price = formatNumberWithCommas(low_price);
  high_price = formatNumberWithCommas(high_price);

  setCookie("square_footage", square_footage, 1);
  setCookie("home_type", home_type, 1);
  setCookie("story", story, 1);
  setCookie("pitch", pitch, 1);
  setCookie("roof_type", roof_type, 1);
  setCookie("zip_code", zip_code, 1);
  setCookie("first_name", first_name, 1);
  setCookie("last_name", last_name, 1);
  setCookie("email", email, 1);
  setCookie("phone_number", phone_number, 1);
  setCookie("final_price", final_price, 1);
  setCookie("low_price", low_price, 1);
  setCookie("high_price", high_price, 1);
  setCookie("trustedform_cert_url", trustedform_cert_url, 1);

  $('.overlay').removeClass('visible');

  // Create a form to submit data via POST (more reliable than cookies for guest users)
  const form = document.createElement('form');
  form.method = 'POST';
  form.action = '/crm.php';

  // Add all data as hidden fields
  const formData = {
    square_footage: square_footage,
    home_type: home_type,
    story: story,
    pitch: pitch,
    roof_type: roof_type,
    zip_code: zip_code,
    first_name: first_name,
    last_name: last_name,
    email: email,
    phone_number: phone_number,
    final_price: final_price,
    low_price: low_price,
    high_price: high_price,
    trustedform_cert_url: trustedform_cert_url
  };

  for (const key in formData) {
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = key;
    input.value = formData[key];
    form.appendChild(input);
  }

  // Submit the form
  document.body.appendChild(form);
  form.submit();
}) //End submit button



$('.header-button').click(function(){
  $('.zip_code').focus();
})


  // Number input
  // if(document.querySelector('.btn-minus')){
  //   document.querySelector('.btn-minus').addEventListener('click', function() {
  //     let input = document.getElementById('square_footage');
  //     let value = parseInt(input.value);
  //     if (value > 0) {
  //       input.value = value - 1;
  //     }
  //   });
  // }
 
  // if(document.querySelector('.btn-plus')){
  //   document.querySelector('.btn-plus').addEventListener('click', function() {
  //     let input = document.getElementById('square_footage');
  //     let value = parseInt(input.value);
  //     input.value = value + 1;
  //   });

  // }


  function hideOtherSections(){
    if (window.matchMedia("(max-width: 991px)").matches) {
      $(".calculator_area_inner").css({"margin": "auto",});
      $("body").css({"display": "flex", "flex-direction": "column", "min-height": "100vh"});
      $("#inner_content-2-35").css({"flex": "1 1 auto", "display": "flex", "flex-flow": "column", "justify-content": "center"});
      // $("section#section-4-36").css({"min-height": "700px", "background": "#ffffff"});
      // $("footer").css({"position": "fixed", "width": "100%"});
      $("#section-41-36").css("display", "none");
      $("#section-30-36").css("display", "none");
      $("#section-90-36").css("display", "none");
      $("#div_block-7-36").css("display", "none");
      $(".ct-section-inner-wrap").css("padding", "0");
    }
    enableUnloadWarning();
  }

  function unhideOtherSections(){
    if (window.matchMedia("(max-width: 991px)").matches) {
      $(".calculator_area_inner").css({"margin": "",});
      $("body").css({"display": "", "flex-direction": "", "min-height": ""});
      $("#inner_content-2-35").css({"flex": "", "display": "", "flex-flow": "", "justify-content": ""});
      // $("section#section-4-36").css({"min-height": "", "background": ""});
      // $("footer").css({"position": "", "width": ""});
      $("#section-41-36").css("display", "");
      $("#section-30-36").css("display", "");
      $("#section-90-36").css("display", "");
      $("#div_block-7-36").css("display", "");
      $(".ct-section-inner-wrap").css("padding", "");
    }
    disableUnloadWarning();
  }

 



}) // End document ready

function enableUnloadWarning() {
  window.onbeforeunload = function (e) {
    e.preventDefault();
    e.returnValue = "Your roof estimate isn’t finished yet — are you sure you want to leave?";
  };
}

function disableUnloadWarning() {
  window.onbeforeunload = null;
}

function validateUSPhoneNumber() {
  const phoneInput = document.getElementById('phone');
  const inputGroupText = document.getElementById('input-group-text');
  const submitButton = document.getElementById('submit_btn');
  let value = phoneInput.value.replace(/\D/g, ''); // Remove all non-digit characters
  const usPhonePattern = /^\(\d{3}\) \d{3}-\d{4}$/; // Defines the US phone number pattern

  

  // Automatically format the input as (XXX) XXX-XXXX
  if (value.length > 3 && value.length <= 6) {
    // Format as (XXX) XXX
    phoneInput.value = `(${value.slice(0, 3)}) ${value.slice(3)}`;
  } else if (value.length > 6) {
    // Format as (XXX) XXX-XXXX
    phoneInput.value = `(${value.slice(0, 3)}) ${value.slice(3, 6)}-${value.slice(6, 10)}`;
  } else if (value.length <= 3) {
    // Format as (XXX
    phoneInput.value = `(${value}`;
  }

  // phoneInput.value = value.substring(0, 14);


  if (usPhonePattern.test(phoneInput.value)) {
    phoneInput.style.borderColor = ''
    inputGroupText.style.borderColor = ''
    submitButton.disabled = ''; 
  }
  else {
    phoneInput.style.borderColor = '#B20000'
    inputGroupText.style.borderColor = '#B20000'
    submitButton.disabled = true;
  }
}

function resetCookies(){
  document.cookie = "square_footage=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;SameSite=Lax";
  document.cookie = "home_type=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;SameSite=Lax";
  document.cookie = "story=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;SameSite=Lax";
  document.cookie = "pitch=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;SameSite=Lax";
  document.cookie = "roof_type=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;SameSite=Lax";
  document.cookie = "zip_code=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;SameSite=Lax";
  document.cookie = "first_name=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;SameSite=Lax";
  document.cookie = "last_name=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;SameSite=Lax";
  document.cookie = "email=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;SameSite=Lax";
  document.cookie = "phone_number=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;SameSite=Lax";
  document.cookie = "final_price=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;SameSite=Lax";
  document.cookie = "low_price=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;SameSite=Lax";
  document.cookie = "high_price=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;SameSite=Lax";
  document.cookie = "trustedform_cert_url=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;SameSite=Lax";
}


// Trusted form code
(function() {
  var tf = document.createElement('script');
  tf.type = 'text/javascript';
  tf.async = true;
  tf.src = ("https:" == document.location.protocol ? 'https' : 'http') +
    '://api.trustedform.com/trustedform.js?field=xxTrustedFormCertUrl&use_tagged_consent=true&l=' +
    new Date().getTime() + Math.random();
  var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(tf, s);
})();