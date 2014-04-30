<?php 
//If the form is submitted
if(isset($_POST['submitted'])) {

	//Check to see if the honeypot captcha field was filled in
	if(trim($_POST['checking']) !== '') {
		$captchaError = true;
	} else {
	
		//Check to make sure that the name field is not empty
		if(trim($_POST['senderName']) === '') {
			$nameError = 'You forgot to enter your name.';
			$hasError = true;
		} else {
			$name = trim($_POST['senderName']);
		}
		
		//Check to make sure sure that a valid email address is submitted
		if(trim($_POST['senderEmail']) === '')  {
			$emailError = 'You forgot to enter your email address.';
			$hasError = true;
		} else if (!eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$", trim($_POST['senderEmail']))) {
			$emailError = 'You entered an invalid email address.';
			$hasError = true;
		} else {
			$email = trim($_POST['senderEmail']);
		}
			
		//Check to make sure comments were entered	
		if(trim($_POST['senderMessage']) === '') {
			$commentError = 'You forgot to enter your comments.';
			$hasError = true;
		} else {
			if(function_exists('stripslashes')) {
				$comments = stripslashes(trim($_POST['senderMessage']));
			} else {
				$comments = trim($_POST['senderMessage']);
			}
		}
			
		//If there is no error, send the email
		if(!isset($hasError)) {

			$emailTo = 'johnshumon@gmail.com';
			$subject = 'Contact Form message from '.$name;
			$sendCopy = trim($_POST['sendCopy']);
			$body = "Name: $name \n\nEmail: $email \n\nComments: $comments";
			$headers = 'From: My Site <'.$emailTo.'>' . "\r\n" . 'Reply-To: ' . $email;
			
			mail($emailTo, $subject, $body, $headers);

			if($sendCopy == true) {
				$subject = 'You emailed Your Name';
				$headers = 'From: Your Name <noreply@somedomain.com>';
				mail($email, $subject, $body, $headers);
			}

			$emailSent = true;

		}
	}
} ?>

<!DOCTYPE html>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="js/infobox.js"></script>
<script type="text/javascript" src="js/contact-form.js"></script>

<html lang="en">
    <head>
		<meta charset="UTF-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
        <title>contact form by JohnShumon</title>
        <meta name="description" content="Contact Form Styling with CSS3" />
        <meta name="keywords" content="css3, contact, form, transparent, popup, pop-up, google, map, embed, on, marker, click, input, submit, button, html5, placeholder" />
        <meta name="author" content="johnshumon" />
        <link rel="stylesheet" type="text/css" href="css/style.css" />
		
		<script>
			function initialize() {
				var loc, map, marker, infobox;
				
				loc = new google.maps.LatLng(61.4443212,23.85643589999995);
				
				map = new google.maps.Map(document.getElementById("map-canvas"), {
					zoom: 12,
					center: loc,
					mapTypeId: google.maps.MapTypeId.ROADMAP
				});
				
				marker = new google.maps.Marker({
					map: map,
					position: loc,
					visible: true
				});

				infobox = new InfoBox({
					content: document.getElementById("infobox"),
					disableAutoPan: false,
					maxWidth: 150,
					pixelOffset: new google.maps.Size(-140, 0),
					zIndex: null,
					boxStyle: {
						background: "url('http://google-maps-utility-library-v3.googlecode.com/svn/trunk/infobox/examples/tipbox.gif') no-repeat",
						opacity: 0.80,
						width: "380px"
					},
					closeBoxMargin: "12px 4px 2px 2px",
					closeBoxURL: "http://www.google.com/intl/en_us/mapfiles/close.gif",
					infoBoxClearance: new google.maps.Size(1, 1)
				});
				
				google.maps.event.addListener(marker, 'click', function() {
					infobox.open(map, this);
					map.panTo(loc);
				});
			}
			google.maps.event.addDomListener(window, 'load', initialize);

		</script>

        <!--[if lte IE 7]><style>.main{display:none;} .support-note .note-ie{display:block;}</style><![endif] -->
		<style>	
			@import url(http://fonts.googleapis.com/css?family=Raleway:400,700);
			body {
				background: #7f9b4e url(images/bg3.jpg) no-repeat center top;
				-webkit-background-size: cover;
				-moz-background-size: cover;
				background-size: cover;
			}
			.container > header h1,
			.container > header h2 {
				color: #fff;
				text-shadow: 0 1px 1px rgba(0,0,0,0.7);
			}
		</style>
    </head>
    <body>

        <div class="container">

			<?php if(isset($emailSent) && $emailSent == true) { ?> 
				<div class="thanks">
					<h1>Thanks, <?=$name;?></h1>
					<p>Your email is on it's way! I will get back to you as soon as I can. </p>
				</div>					 
			<?php } else {?>
			
			<?php if($emailError != '') { ?>
				<span><?=$emailError;?></span>
			<?php } ?>
			
			<div id="map-canvas"></div>
			<div class="infobox-wrapper">
				<div id="infobox">
					<section class="main">
						<form class="form-4" action="index.php" method="post">							
							
								<label for="senderName">Sender</label>
								<input type="text" name="senderName" id="senderName" placeholder="What is your Name"  required="required">												
							
								<label for="senderEmail">Email</label>
								<input type="email" name="senderEmail" id="senderEmail" placeholder="What is your Email" required="required">													
								
								<label for="senderMessage">Message</label>								
								<textarea name="senderMessage" id="senderMessage" placeholder="Please enter your Message" required="required" cols="80" rows="10" maxlength="10000"></textarea>
							
								<input type="submit" name="submitted" id="submitted" value="Send">
								  
						</form>​
					</section>
				</div>
			</div>
							
					<?php }?>
        </div>

    </body>
</html>