<?php //include 'new-conv.css';?>
<link rel="stylesheet" href="new-conv.css" type="text/css">
<script src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.3.1.min.js"></script>
<center>

   
<div class="convert-div">
<table cellspacing="0" cellpadding="5" border="0">
<tr>
<form method="POST">
<td NOwrap valign="middle"> 
  <input type="hidden" name="hiddenField">
  <div class="box inbox"><span class="bluetext"> Inches: </span><input type="text" name="inc" size="7" id="inch" class="filter"></div>
  <div class="box ftbox"><span class="bluetext"> Feet: </span><input type="text" name="ft" size="7" id="ft" class="filter">  </div>
  <div class="box ydsbox"><span class="bluetext"> Yards: </span><input type="text" name="yds" size="7" id="yd" class="filter"></div>
  <div class="box mmbox"><span class="bluetext"> Millimeters (mm): </span><input type="text" name="mm" size="7" id="mm" class="filter"></div>
  <div class="box mbox"><span class="bluetext"> Meters (m): </span><input type="text" name="m" size="7" id="met" class="filter"> </div>
  
  
  
</form>
</td></form></tr></table></div>
<p class="hint-text">(Please enter a value in any box above.)</p>
</center>
<script>
jQuery(".filter").keyup(function() {
    var value = this.value;
    var id = this.id;
    var mm = "";
    var met = "";
    var inch = "";
    var ft = "";
    var yd = "";

    switch (id) {
    case "mm":
        met = (value / 1000).toFixed(6);
        jQuery("#met").val(met);
        inch = (value / 25.4).toFixed(6); 
        jQuery("#inch").val(inch);
        ft = (value / 304.8).toFixed(6); 
        jQuery("#ft").val(ft);
        yd = (value / 914.4).toFixed(6); 
        jQuery("#yd").val(yd);
        break;

    case "met":
        mm = (value * 1000).toFixed(6);
        jQuery("#mm").val(mm);
        inch = (value / 0.0254).toFixed(6);
        jQuery("#inch").val(inch);
        ft = (value / 0.3048).toFixed(6);
        jQuery("#ft").val(ft);
        yd = (value * 1.0936132983).toFixed(6);
        jQuery("#yd").val(yd);
        break;

    case "inch":
    mm = (value * 25.4).toFixed(2);
    jQuery("#mm").val(mm);
    met = (value * 0.0254).toFixed(6);
    jQuery("#met").val(met);
    ft = (value * 0.0833333).toFixed(6);
    jQuery("#ft").val(ft);
    yd = (value * 0.0277778).toFixed(6);
    jQuery("#yd").val(yd);
    break;

    case "ft":
    mm = (value * 304.8).toFixed(2); 
    jQuery("#mm").val(mm);
    met = (value * 0.3048).toFixed(6);
    jQuery("#met").val(met);
    inch = (value * 12).toFixed(6);
    jQuery("#inch").val(inch);
    yd = (value * 0.333333).toFixed(6);
    jQuery("#yd").val(yd);
    break;

    case "yd":
    mm = (value * 914.4).toFixed(2); 
    jQuery("#mm").val(mm);
    met = (value * 0.9144).toFixed(6);
    jQuery("#met").val(met);
    inch = (value * 36).toFixed(6);
    jQuery("#inch").val(inch);
    ft = (value * 3).toFixed(6);
    jQuery("#ft").val(ft);
    break;

    default:
        alert('no input has been changed');
    }

   
    //some code here....
});
</script>
