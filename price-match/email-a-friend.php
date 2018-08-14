<?php
$name=$_POST['name'];
$name=str_replace('\"','"',$name);
$name=str_replace("\'","'",$name);
$id=$_POST['id'];
$code=$_POST['item'];
$price=$_POST['price'];
$saleprice=$_POST['saleprice'];
$image=$_POST['image'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>POSPaper Price Match Request</title>
    <link rel="stylesheet" href="http://site.pospaper.com/ystore/price-match/price-match.css" type="text/css" />

</head>

<body>
	<div id="wrapper">
    	<form action="pmcontact.php" method="post" onsubmit="return validateForm();">
            <h2>Email a friend</h2>
            <div id="promos">
              <div id="website-promo" class="promo-fields">
					<table width="90%" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td width="33%" align="center" valign="top">
	<img src="http://pospaper-store.us-dc1-edit.store.yahoo.net<? echo $image; ?>"  style="border:1px solid #DEC1EC; padding:2px;"/>
	</td>
    <td width="3%">&nbsp;</td>
    <td width="64%" valign="top" align="left">
	<?
	if($name){echo "<b style=\"color:#411B58; font-size:13px;\">$name; </b><br/>";}
	echo "<span style=\"padding:10px; color:#424242\">";
	if($code){echo "<b>Item No.</b> $code<br/>";}
	if($price){echo "<b>Price:</b> $price<br/>";}
	if($saleprice){echo "<b>Sale Price:</b> $saleprice<br/>";}
	echo "</span>";
	?>
	</td>
  </tr>
</table>

              </div>
                <div id="retail-promo" class="promo-fields" style="display:none;">
                    <table>
                        <tr>
                            <td>Store Name:</td>
                            <td><input type="text" name="store-name" value="" /></td>

                        </tr>
                        <tr>
                            <td>Store City:</td>
                            <td><input type="text" name="store-city" value="" /></td>
                        </tr>
                        <tr>
                            <td>Store State:</td>
                            <td><input type="text" name="store-state" value="" /></td>

                        </tr>
                        <tr>
                            <td>Store Phone:</td>
                            <td><input type="text" name="store-phone" value="" /></td>
                        </tr>
                    </table>
                </div>
          </div>

            <div id="contact-info">
                <span class="heading">Contact Information</span>
               <table border="0" cellpadding="2" cellspacing="1" align="center">
<tr>
 <td>&nbsp;</td>
 <td class="inputName">
 Name:<br><input type="text" value="" name="name" style="width:200px;">
 <br>
 Email:<br><input type="text" value="" name="email" style="width:200px;">
 </td>

</tr>
<tr><td colspan="2"><img src="http://site.pospaper.com/ystore/images/spacer.gif" height="10"></td></tr>
<tr><td>&nbsp;</td><td class="inputName">Enter Up To 5 Email Addresses:</td></tr>
<tr><td class="inputName">(1) </td><td><input value="" type="text" name="emailTo1" size="30" class="textInput" style="width:200px;"></td></tr>
<tr><td class="inputName">(2) </td><td><input value="" type="text" name="emailTo2" size="30" class="textInput" style="width:200px;"></td></tr>
<tr><td class="inputName">(3) </td><td><input value="" type="text" name="emailTo3" size="30" class="textInput" style="width:200px;"></td></tr>
<tr><td class="inputName">(4) </td><td><input value="" type="text" name="emailTo4" size="30" class="textInput" style="width:200px;"></td></tr>
<tr><td class="inputName">(5) </td><td><input value="" type="text" name="emailTo5" size="30" class="textInput" style="width:200px;"></td></tr>
<tr><td colspan="2"><img src="http://site.pospaper.com/ystore/images/spacer.gif" height="10"></td></tr>
<tr><td colspan="2" bgcolor="#FFFFFF"><img src="http://site.pospaper.com/ystore/images/spacer.gif" height="1" width="250"></td></tr>
<tr><td colspan="2"><img src="http://site.pospaper.com/ystore/images/spacer.gif" height="8"></td></tr>

<tr><td>&nbsp;</td><td class="inputName">Include a Short Message:</td></tr>
<tr><td>&nbsp;</td><td><textarea name="message" class="textareaInput" rows="4" cols="30" style="width:200px;"></textarea></td></tr>
<tr><td colspan="2"><img src="http://site.pospaper.com/ystore/images/spacer.gif" height="10"></td></tr>
<tr><td align="center" colspan="2"><input type="image" src="send.gif" border="0"></td></tr>
</table>
				
				
            </div>
         
            <div class="button"><input type="image" src="http://site.pospaper.com/ystore/images/btn-submit.jpg" value="Submit" style="border:none" /></div>
        </form>
	</div>

  <div class="close">
    	<div class="btn-close"><a href="javascript:window.close();"><img src="http://site.pospaper.com/ystore/images/btn-close.jpg" border="0" alt="" /></a></div>
        <br /><br />
        <span style="width:100%; float:left; text-align:center; font-size:14px; color:#000;"><a href="http://www.pospaper.com/orderinginfo.html" target="_blank">Privacy Policy</a></span>
        <br /><br />

</body>
</html>
