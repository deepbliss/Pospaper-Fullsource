
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <title>POSPaper Price Match Request</title>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.1/jquery.min.js"></script>
    <script type="text/javascript" src="jquery.mCustomScrollbar.concat.min.js"></script>
    <link rel="stylesheet" href="price-match.css" type="text/css" />
    <link rel="stylesheet" href="jquery.mCustomScrollbar.css" type="text/css" />
    <script type="text/javascript">
        function addItem() {
            count = $('.item-row').length - 1;
            row = $('.item-row').get(count);
            position = (count + 2).toString(); 
            $(row).after('<div class="pmf-head-row item-row"><div class="pmf-head-row-left"><div class="count-num">'+position+'.</div><input type="text" class="name-field" name="name_'+position+'"  /></div><div class="pmf-head-row-right"><input type="text" class="price-field" name="price_'+position+'" onblur="calculateTotal();"  /></div></div>');
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
    
  </head>

<body>
    <div id="wrapper" class="price-match-form content">
    
        <form action="<?php echo "http://" . $_SERVER['SERVER_NAME'] . "/pospaper/price-match/pmcontact.php"; ?>" method="post" name="pricematch" onsubmit="return validateForm();">             
            <div class="pmf-head-section" id="item-table">
                <div class="padding">
                    <div class="pmf-head"><h1>POSPaper Price Match Request</h1></div>
                   <div class="pmf-head-row">
                    <div class="pmf-head-row-left"><span class="red">*</span>Item Name</div>
                    <div class="pmf-head-row-right"><span class="red">*</span>Competitor's Price (ea)</div>
                   </div>
                     <div class="pmf-head-body-section">
                    <div class="pmf-head-row item-row">
                     <div class="pmf-head-row-left"><div class="count-num">1.</div>
                      <input name="name_1" value="" type="text"></div>
                     <div class="pmf-head-row-right"><input name="price_1" class="price-field" onblur="calculateTotal();" type="text"></div>
                    </div>
                         <div class="pmf-head-body-row control-block">
                     <div id="add-item"><a href="javascript:addItem();"><span>+</span> Add Item</a></div>
                     <div id="remove-item"><a href="javascript:removeItem();"><span>-</span> Remove Item</a></div>
                    </div>
                   </div>
               </div>
                <div class="pmf-ship-section">
                <div class="pmf-ship-row padding">
                 <div class="pmf-ship-row-left"><label>Competitor's Delivery Charge:</label></div>
                 <div class="pmf-ship-row-right"><input name="shipping" id="shipping" onblur="calculateTotal();" type="text"></div>
                </div>
                <div class="pmf-ship-row total-row padding">
                 <div class="pmf-ship-row-left"><label>Total Cost:</label></div>
                 <div class="pmf-ship-row-right"><input id="total" value="0" disabled="" type="text"><input id="hidden-total" name="total" value="$4,888.00" type="hidden"></div>
                </div>
               </div>
          </div>
















           
            <div id="promos" class="pmf-howdid-section padding">
                <h2>How did you see this price?</h2>
                <div class="pmf-radio_list">
        <ul>
         <li><input type="radio" name="promo-location" checked="checked" value="2" /> <label>Website</label> </li>
         <li> <input type="radio" name="promo-location"  value="3"/> <label>Retail Store</label> </li>
        </ul>
       </div>
                
                <div id="promo2" class="promo-fields">
                    <label>Enter the competitor's website address you found the item(s) at:</label>
                    <input type="text" name="website-field"  />
                </div>
                  <div id="promo3" class="promo-fields" style="display: none;">
        <div class="retail-promo-row">
         <label>Store Name:</label>
         <input name="store-name" type="text">
        </div>
        <div class="retail-promo-row">
         <label>Store City:</label>
         <input name="store-city" type="text">
        </div>
        <div class="retail-promo-row">
         <label>Store State:</label>
         <input name="store-state" type="text">
        </div>
        <div class="retail-promo-row">
         <label>Store Phone:</label>
         <input name="store-phone" type="text">
        </div>
       </div>
            </div>

<script type="text/javascript">
  jQuery(document).ready(function() {
    jQuery("input[name$='promo-location']").click(function() {
        var test = jQuery(this).val();

        jQuery("div.promo-fields").hide();
        jQuery("#promo" + test).show();
    });
});
</script>





            <div class="padding">
            <div class="pmf-contact-info" id="contact-info">
                <h2>Contact Information</h2>

                  <div class="pmf-contact-wrap">
                    <div class="pmf-cnt-left">
                        <div class="pmf-contact-wrap-row">
                         <label>Your Name<span class="red">*</span></label>
                         <input type="text" id="your-name" name="your-name"  />
                        </div>
                        <div class="pmf-contact-wrap-row">
                         <label>Email Address<span class="red">*</span></label>
                         <input type="text" id="your-email" name="your-email"  />
                        </div>
                        <div class="pmf-contact-wrap-row">
                         <label>Phone Number</label>
                         <input type="text" id="your-number" name="your-number"  />
                        </div>
                        <div class="pmf-contact-wrap-row">
                         <label>Shipping Zip Code<span class="red">*</span></label>
                        <input type="text" id="your-zip" name="your-zip"  />
                        </div>
                        </div>
                        <div class="pmf-cnt-right">
                            <div class="pmf-contact-wrap-row">
                             <label>Additional Comments</label>
                            <textarea name="comments" cols="40" rows="10"></textarea>
                            </div>
                            <div class="pmf-contact-wrap-row captcha_code">
                             <label><span class="red">*</span>Security Code<span class="red">*</span></label>
                             <?php $actual_link = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";?>
                             <img src="<?php echo $actual_link?>/price-match/CaptchaSecurityImages.php?width=80&amp;height=30&amp;characters=5" alt="captcha" title="captcha">
                             <input id="security_code" name="security_code" type="text" class="cpt-input"/>
                            </div>
                        </div>
                    </div>
               
            </div>
            <p id="note">Check to make sure your email address is accurate so that we can respond to your request.  Include your telephone number if you would like us to call you.</p>
            <div class="button-div">
                <a class="privacy-link" href="<?php echo $actual_link?>/privacy-policy" target="_blank">Privacy Policy</a>
                <input type="submit" value="Submit" style="border:none" />
            </div>
        </div>
      </form>        
    </div>    
    <script>
    (function($){
      $(window).load(function(){
        
        /* all available option parameters with their default values */
        $(".content").mCustomScrollbar({
          setWidth:false,
          setHeight:false,
          setTop:0,
          setLeft:0,
          axis:"y",
          scrollbarPosition:"inside",
          scrollInertia:950,
          autoDraggerLength:true,
          autoHideScrollbar:false,
          autoExpandScrollbar:false,
          alwaysShowScrollbar:0,
          snapAmount:null,
          snapOffset:0,
          mouseWheel:{
            enable:true,
            scrollAmount:"auto",
            axis:"y",
            preventDefault:false,
            deltaFactor:"auto",
            normalizeDelta:false,
            invert:false,
            disableOver:["keygen","datalist"]
          },
          scrollButtons:{
            enable:false,
            scrollType:"stepless",
            scrollAmount:"auto"
          },
          keyboard:{
            enable:true,
            scrollType:"stepless",
            scrollAmount:"auto"
          },
          contentTouchScroll:25,
          advanced:{
            autoExpandHorizontalScroll:false,
            autoScrollOnFocus:"input,textarea,select,button,datalist,keygen,a[tabindex],area,object,[contenteditable='true']",
            updateOnContentResize:true,
            updateOnImageLoad:true,
            updateOnSelectorChange:false,
            releaseDraggableSelectors:false
          },
          theme:"minimal",
          callbacks:{
            onInit:false,
            onScrollStart:false,
            onScroll:false,
            onTotalScroll:false,
            onTotalScrollBack:false,
            whileScrolling:false,
            onTotalScrollOffset:0,
            onTotalScrollBackOffset:0,
            alwaysTriggerOffsets:true,
            onOverflowY:false,
            onOverflowX:false,
            onOverflowYNone:false,
            onOverflowXNone:false
          },
          live:false,
          liveSelector:null
        });
        
      });
    })(jQuery);
  </script>
  <style type="text/css">
    .content{height: 100vh;}
  </style>
</body>
</html>