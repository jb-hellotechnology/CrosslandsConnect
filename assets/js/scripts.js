/** NEWS SLIDER **/
$('.slick').slick({
  dots: false,
  infinite: false,
  speed: 300,
  slidesToShow: 2,
  slidesToScroll: 2,
  responsive: [
	{
	  breakpoint: 640,
	  settings: {
		slidesToShow: 1,
		slidesToScroll: 1
	  }
	}
	// You can unslick at a given breakpoint now by adding:
	// settings: "unslick"
	// instead of a settings object
  ]
});

/* MENU BUTTON */
$('#settings').click(function(){
	$('ul.settings').toggleClass('active');
});

/* USER FORM */
$("#user").submit(function(e){
	e.preventDefault();
	let first_name = $('#first_name').val();
	let last_name = $('#last_name').val();
	if(first_name!=='' && last_name!==''){
		$.post('/process/profile_create.php', $( "#user" ).serialize(), function(data){
			$('.result.profile').html('<p class="alert success">Profile created</p>');
			window.location.replace("/");
		});
	}else{
		alert('Please enter a first and last name');
	}
});

/* DETAILS FORM */
$("#details").submit(function(e){
	e.preventDefault();
	$.post('/process/details_update.php', $( "#details" ).serialize(), function(data){
		$('.result.details').html('<p class="alert success">Contact details updated</p>');
	});
});

/* PROFILE FORM */
$("#profile").submit(function(e){
	e.preventDefault();
	
	// Create a FormData object to send the file
	var formData = new FormData(this);
	
	$.ajax({
	  url: "/process/profile_update.php", // Replace with your server-side script URL
	  method: "POST",
	  data: formData,
	  processData: false,
	  contentType: false,
	  success: function (response) {
		// Display the uploaded image
		console.log(response);
		var data = JSON.parse(response);
		if(data.file){
			$("#image").html('<img src="' + data.file + '" alt="Uploaded Image">');
		}
		$('.result.profile').html(data.message);
	  },
	});
});

/* ORGANISATION SEARCH */
$("#q").on( "keyup", function(){
	$.post('/process/organisations_search.php', $( "#organisation" ).serialize(), function(data){
		$('#organisations').html(data);
	});
});

/* LOAD CHURCH - CONNECT */
$('body').on('click', '.connect', function(e) {
	e.preventDefault();
	let id = $(this).data('id');
	$.post('/process/organisation_connect.php', { organisation_id: id }, function(data){
		$('#organisation_connect').html(data);
		$([document.documentElement, document.body]).animate({
			scrollTop: $("#organisation_connect").offset().top-140
		}, 200);
	});
});

/* CONNECT REQUEST */
$('body').on('click', '#connect', function(e) {
	e.preventDefault();
	let id = $(this).data('id');
	$.post('/process/organisation_connect_request.php', { organisation_id: id }, function(data){
		$('#connect_action').html(data);
		$('#organisation_connect').html('');
	});
});

/* DIRECTORY FORM */
$("#directory").submit(function(e){
	e.preventDefault();
	$.post('/process/profile_directory_update.php', $( "#directory" ).serialize(), function(data){
		console.log(data);
		$('.result.directory').html('<p class="alert success">Preference updated</p>');
	});
});

/* ACCORDION */
$('.accordion h2,.accordion h3').click(function(){
	$(this).toggleClass('open');
	$(this).next('div').toggle();
})

/* TABS */
$('.tabs li').click(function(){
	$('.tabs .active').removeClass('active');
	$('.tab.active').removeClass('active');
	$(this).toggleClass('active');
	let tab = $(this).data('tab');
	$('.' + tab).addClass('active');
})

/* PWA */