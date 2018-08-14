<?php
    $name = $_GET['name'];
    $price = $_GET['price'];
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>POSPaper Price Match Request</title>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.1/jquery.min.js"></script>
    <script type="text/javascript">
        function addItem() {
            count = $('.item-row').length - 1;
            row = $('.item-row').get(count);
            position = (count + 2).toString();
            $(row).after('<tr class="item-row"><td class="count">'+position+'.</td><td class="name"><input type="text" class="name-field" name="name_'+position+'"  /></td><td class="price"><input type="text" class="price-field" name="price_'+position+'" onblur="calculateTotal();"  /></td></tr>');
            if($('#remove-item').is(':hidden')) {
                $('#remove-item').toggle();
            }
        }
        function removeItem() {
            count = $('.item-row').length - 1;
            row = $('.item-row').get(count);
            $(row).remove();
            if(count < 2) {
                $('#remove-item').toggle();
            }
            calculateTotal();
        }
        String.prototype.toMoney = function () {
          amount = parseFloat(this);
          amount = (Math.round(amount*100))/100;
          dollar = amount.toString();
          if(dollar.length > 3) {
            dollar = "$" + dollar.substring(0,dollar.length - 3) + "," + dollar.substring(dollar.length - 3);
          } else {
            dollar = "$" + dollar;
          }
          return (amount == Math.floor(amount)) ? dollar + '.00' : ( (amount*10 == Math.floor(amount*10)) ? amount + '0' : amount);
        }
        function calculateTotal() {
            value = 0;
            $('.price-field').each(function(i,e) {
                if($(this).val() == "") {
                    amount = 0;
                } else {
                    amount = parseFloat($(this).val());
                }
                value += amount;
            });
            if(!$('#shipping').val() == "") {
                shipping = parseFloat($('#shipping').val());
                value += shipping;
            }
            $("#total").val(value.toString().toMoney());
            $("#hidden-total").val(value.toString().toMoney());
        }
        function addShipping() {
            if(!$('#shipping').val() == "") {
                shipping = parseFloat($('#shipping').val());
                total = $("#total").val();
                total = total.replace(",", "");
                total = parseFloat(total.substring(1, total.length));
                total += shipping;
                $("#total").val(total.toString().toMoney());
                $("#hidden-total").val(value.toString().toMoney());
            }
        }
        function validateForm() {
            if($("#your-name").val() == "") {
                alert("You must provide us with your name.");
                return false;
            }
            if($("#your-email").val() == "") {
                alert("You must provide us with your email address.");
                return false;
            }
            if($("#your-zip").val() == "") {
                alert("You must provide us with your shipping zip code.");
                return false;
            }
            if($("#security_code").val() == "") {
                alert("You must Enter the Security Code.")
                document.pricematch.security_code.focus();
                return false;
            }
            return true;
        }
        $(document).ready(function() {
            $("#total").attr('disabled', 'disabled');
            calculateTotal();
        });
        $(document).ready(function() {
             document.getElementById('security_code').value = "";
        });
    </script>
    <link rel="stylesheet" href="price-match.css" type="text/css" />
      <link rel="stylesheet" type="text/css" href="https://p8.secure.hostingprod.com/@pospaper.com/ssl/css/stylesheet-2018.css">
    <link rel="stylesheet" type="text/css" href="https://p8.secure.hostingprod.com/@pospaper.com/ssl/css/mediaquery-2018.css">
</head>

<body>
    <div id="wrapper">
        <form action="pmcontact.php" method="post" name="pricematch" onsubmit="return validateForm();">
            <h2>POSPaper Price Match Request</h2>
            <table cellpadding="0" cellspacing="0" id="item-table">
                <tr class="head-row">
                    <th>&nbsp;</th>
                    <th><span class="red">*</span>Item Name</th>
                    <th><span class="red">*</span>Competitor's Price (ea)</th>
                </tr>
                <tr class="item-row">
                    <td class="count">1.</td>
                    <td class="name"><input type="text" class="name-field" name="name_1" value="<?= $name ?>" /></td>
                    <td class="price"><input type="text" class="price-field" name="price_1" onblur="calculateTotal();" /></td>
                </tr>
                <tr class="control-row">
                    <td>&nbsp;</td>
                    <td>
                        <div id="add-item"><a href="javascript:addItem();">[+] Add Item</a></div>
                        <div id="remove-item" style="display:none;"><a href="javascript:removeItem();">[-] Remove Item</a></div>
                    </td>
                    <td>&nbsp;</td>
                </tr>
                <tr class="ship-row">
                    <td>&nbsp;</td>
                    <td>Competitor's Delivery Charge:</td>
                    <td style="padding-right:6px"><input type="text" name="shipping" id="shipping" onblur="calculateTotal();"  /></td>
                </tr>
                <tr class="total-row">
                    <td>&nbsp;</td>
                    <td>Total Cost:</td>
                    <td style="padding-right:6px"><input type="text" id="total" value="0" /><input type="hidden" id="hidden-total" name="total"  /></td>
                </tr>
            </table>
            <div id="promos">
                <span class="heading">How did you see this price?</span><br />
                <input type="radio" name="promo-location" value="Website" checked="checked" onclick="$('.promo-fields').toggle();" />Website<br />
                <input type="radio" name="promo-location" value="Retail Store" onclick="$('.promo-fields').toggle();" />Retail Store
                <div id="website-promo" class="promo-fields">
                    <p>Enter the competitor's website address you found the item(s) at:<br />
                    <input type="text" name="website-field"  /></p>
                </div>
                <div id="retail-promo" class="promo-fields" style="display:none;">
                    <table>
                        <tr>
                            <td>Store Name:</td>
                            <td><input type="text" name="store-name"  /></td>
                        </tr>
                        <tr>
                            <td>Store City:</td>
                            <td><input type="text" name="store-city"  /></td>
                        </tr>
                        <tr>
                            <td>Store State:</td>
                            <td><input type="text" name="store-state"  /></td>
                        </tr>
                        <tr>
                            <td>Store Phone:</td>
                            <td><input type="text" name="store-phone"  /></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div id="contact-info">
                <span class="heading">Contact Information</span>
                <table cellpadding="0" cellspacing="0">
                    <tr>
                        <td><span class="red">*</span>Your Name:</td>
                        <td><input type="text" id="your-name" name="your-name"  /></td>
                    </tr>
                    <tr>
                        <td><span class="red">*</span>Email Address:</td>
                        <td><input type="text" id="your-email" name="your-email"  /></td>
                    </tr>
                    <tr>
                        <td>Phone Number:</td>
                        <td><input type="text" id="your-number" name="your-number"  /></td>
                    </tr>
                    <tr>
                        <td><span class="red">*</span>Shipping Zip Code:</td>
                        <td><input type="text" id="your-zip" name="your-zip"  /></td>
                    </tr>
                    <tr>
                        <td>Additional Comments:</td>
                        <td><textarea name="comments" cols="40" rows="10"></textarea></td>
                    </tr>
                    <tr>
                        <td><span class="red">*</span>Security Code:</td>
                        <td><img src="CaptchaSecurityImages.php?width=80&amp;height=30&amp;characters=5" alt="captcha" title="captcha" /><input id="security_code" name="security_code" type="text" class="cpt-input"/></td>
                    </tr>
                </table>
            </div>
            <p id="note">Check to make sure your email address is accurate so that we can respond to your request.  Include your telephone number if you would like us to call you.</p>
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
