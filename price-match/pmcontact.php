<?php
session_start();
if($_POST['security_code'])
{
if(($_SESSION['security_code'] == $_POST['security_code']) && (!empty($_SESSION['security_code'])) )
{
	$x = 0;
	$item_names = array();
	$item_prices = array();
	while(isset($_POST['name_'.($x +1)])) {
		$item_names[$x] = clean($_POST['name_'.($x +1)]);
		$x++;
	}
	$x = 0;
	while(isset($_POST['price_'.($x +1)])) {
		$item_prices[$x] = clean($_POST['price_'.($x +1)]);
		$x++;
	}

	if (isset($_POST['shipping'])) {$shipping = clean($_POST['shipping']);}
	if (isset($_POST['total'])) {$total = clean($_POST['total']);}
	if (isset($_POST['promo-location'])) {$promo_location = clean($_POST['promo-location']);}
	if (isset($_POST['website-field'])) {$website_field = clean($_POST['website-field']);}
	if (isset($_POST['store-name'])) {$store_name = clean($_POST['store-name']);}
	if (isset($_POST['store-city'])) {$store_city = clean($_POST['store-city']);}
	if (isset($_POST['store-state'])) {$store_state = clean($_POST['store-state']);}
	if (isset($_POST['store-phone'])) {$store_phone = clean($_POST['store-phone']);}
	if (isset($_POST['your-name'])) {$your_name = clean($_POST['your-name']);}
	if (isset($_POST['your-email'])) {$your_email = clean($_POST['your-email']);}
	if (isset($_POST['your-number'])) {$your_number = clean($_POST['your-number']);}
	if (isset($_POST['your-zip'])) {$your_zip = clean($_POST['your-zip']);}
	if (isset($_POST['comments'])) {$comments = clean($_POST['comments']);}

	$to1 = 'support@pospaper.com';
	$to2 = 'sales@pospaper.com';
	$header = 'thank-you.html';

	$subject = "Price Match Submission - $your_name";
	$ip = $_SERVER['REMOTE_ADDR'];
	$hostaddress = gethostbyaddr($ip);
	$browser = $_SERVER['HTTP_USER_AGENT'];
	$referred = $_SERVER['HTTP_REFERER']; // a quirky spelling mistake that stuck in php
	$items = "Items: ";
	for($i = 0; $i < count($item_names); $i++) {
		$items .= $item_names[$i]." - ".$item_prices[$i]."\n";
	}
	$last_part = "\nCompetitor's Delivery Charge: $shipping\n".
					 "\nTotal: $total\n".
					 "Price found at: $promo_location\n".
					 "Website: $website_field\n".
					 "Store Name: $store_name\n".
					 "Store City, State: $store_city, $store_state\n".
					 "Store Phone Number: $store_phone\n".
					 "\nCustomer Name: $your_name\n".
					 "Customer Email: $your_email\n".
					 "Customer Phone Number: $your_number\n".
					 "Customer Zip: $your_zip\n".
					 "\nAdditional Comments: $comments\n";
	$email_message = $items.$last_part;
	$headers  = "MIME-Version: 1.0\n";
	$headers .= "Content-type: text/html; charset=iso-8859-1\n";
	$headers .= "From: pricematch@pospaper.com";
	$mail1 = @mail($to1, $subject, $email_message, $headers);
	$mail2 = @mail($to2, $subject, $email_message, $headers);
	
	if($mail1==1 && $mail2==1)
	{
		header('Location: ' . $header);
	}
	else
	{?>
		<script type="text/javascript">
		//window.location.href = document.referrer;
			history.back();
		</script>
		<?php
		}
	
}
else
{?>
<script type="text/javascript">
alert("Security code is invalid, please try again");
//window.location.href = document.referrer;
	history.back();
	//document.getElementById('captcha').src='captcha.php?'+Math.random();
	
</script>
<?php
}
}

function clean($string) {
  $string = stripslashes($string);
  $string = htmlentities($string);
  $string = strip_tags($string);
  return $string;
}

?>