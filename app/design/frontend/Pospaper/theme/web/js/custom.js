jQuery(document).ready(function() {
  
  jQuery(".menu-div .ms-megamenu .ms-topmenu #submenu-7.ms-submenu .col-xs-12.col-level > .form-group").parent().addClass('active');
    jQuery('.menu-div .ms-megamenu .ms-topmenu #submenu-7.ms-submenu li > .form-group').has('.col-sub').addClass('hassub');
    jQuery('.menu-div .ms-megamenu .ms-topmenu #submenu-7.ms-submenu li').has('.col-sub').addClass('hassub');
  //jQuery('.menu-div .ms-megamenu .ms-topmenu #submenu-7.ms-submenu li.hassub .col-xs-12.col-level').append('<i class="arrow"></i>'); 

 
  jQuery(".menu-div .ms-megamenu .ms-topmenu #submenu-7.ms-submenu .col-xs-12.col-level .form-group").click(function(){  
       jQuery(this).toggleClass('minus'); 
       jQuery(this).parent().parent().siblings().find('.col-sub').hide();                        
      jQuery(this).parent().parent().siblings().find('.minus').removeClass('minus');                                                        
      jQuery(this).parent().children('.col-sub').slideToggle('slow');            
});

  jQuery(".menu-div .ms-megamenu .ms-topmenu #submenu-7.ms-submenu .col-xs-12.col-level > .minus").click(function(){  
       
       jQuery(this).removeClass('minus'); 
      jQuery(this).parent().parent().siblings().find('.col-sub').hide();                                
      jQuery(this).parent().children('.col-sub').slideToggle('slow');            
});
jQuery("li.has-sub").removeClass('minus');  
jQuery(".sitelist .sitemap-list li ul .sitemap-list li a").click(function(){
      jQuery(this).parent().toggleClass('minus');                  
       jQuery(this).parent().parent().siblings().find('ul').hide();       
       jQuery(this).parent().parent().parent().parent().parent().siblings().find('li.has-sub ul').hide();
      jQuery(this).parent().parent().siblings().find('.minus').removeClass('minus');                                                        
      jQuery(this).parent().parent().parent().parent().parent().siblings().find('.minus').removeClass('minus');                                                        
      jQuery(this).parent().children('ul').slideToggle('slow');            
});  


if (jQuery(window).innerWidth() <= 770) {
jQuery(".left-category-div h2").click(function(){  
       jQuery(this).parent().toggleClass('left-cat-active'); 
       jQuery(this).next().slideToggle();
});
}


/*if (jQuery(window).innerWidth() <= 770) {
jQuery("#layered-filter-block .block-title.filter-title").click(function(){  
       jQuery(this).parent().toggleClass('filter-title-active'); 
       jQuery(this).next().slideToggle();
});
}*/



function backToTop () {
              var back = jQuery('#back-to-top')
                  windowHeight = jQuery(window).height();
              
              if (back) {
                  jQuery(window).scroll(function() {
                      if (jQuery(window).scrollTop() >= windowHeight / 2) {
                          back.fadeIn();
                      }
                      else {
                          back.hide();
                      };
                  });
                  
                  back.on('click', function () {
                      jQuery('html, body').animate({scrollTop: '0px'}, 800);
                  });
              };
          };
          if (jQuery(window).innerWidth() >= 768) {
              var backToTop = new backToTop;
          };

if (jQuery(window).width() < 1024 ) {
  jQuery(".account .account-nav .account-nav-title").click(function() {
    jQuery(this).next('.account-nav-content').slideToggle(300).toggleClass('active');
    jQuery(this).toggleClass('active');
  });
 }

      
equalheight = function(container){

var currentTallest = 0,
     currentRowStart = 0,
     rowDivs = new Array(),
     $el,
     topPosition = 0;
 jQuery(container).each(function() {

   $el = jQuery(this);
   jQuery($el).height('auto')
   topPostion = $el.position().top;

   if (currentRowStart != topPostion) {
     for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
       rowDivs[currentDiv].height(currentTallest);
     }
     rowDivs.length = 0; // empty the array
     currentRowStart = topPostion;
     currentTallest = $el.height();
     rowDivs.push($el);
   } else {
     rowDivs.push($el);
     currentTallest = (currentTallest < $el.height()) ? ($el.height()) : (currentTallest);
  }
   for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
     rowDivs[currentDiv].height(currentTallest);
   }
 });
}

jQuery(window).load(function() {
  equalheight('.testi-row-cnt .testi-row');
  equalheight('.diff-ser-div ul li');
});


jQuery(window).resize(function(){
  equalheight('.testi-row-cnt .testi-row');
  equalheight('.diff-ser-div ul li');

});

if (jQuery.fn.owlCarousel) {
reseller_slider = jQuery('.reseller-slider');
//Hero_Slider_crsl

reseller_slider.owlCarousel({
nav: false,
dots: false,
autoplay: true,
margin: 0,
lazyLoad: true,
loop: true,
items: 1,
animation: true,
touchDrag:false,
mouseDrag:false,
animateIn: 'fadeIn',
smartSpeed: 1000,
autoplayTimeout: 7000
});
}
  
  
jQuery(".field .control input").after("<span class='bar'></span>");
    jQuery(".field .control input").focusin(function() {
        jQuery(this).next("span").css("width", "100%");
        jQuery(this).next("span").css("left", "0");
    })
    jQuery(".field .control input").focusout(function() {
        jQuery(this).next("span").css("width", "0");
        jQuery(this).next("span").css("left", "50%");
    })
    
jQuery(".field .control textarea").after("<span class='bar'></span>");
    jQuery(".field .control textarea").focusin(function() {
        jQuery(this).next("span").css("width", "100%");
        jQuery(this).next("span").css("left", "0");
    })
    jQuery(".field .control textarea").focusout(function() {
        jQuery(this).next("span").css("width", "0");
        jQuery(this).next("span").css("left", "50%");
    })
    

jQuery(window).load(function() {
    jQuery('.accrodian ul li').not('.active').children('div.content').hide(); 
});

jQuery('.accrodian ul li h3').on('click',function(e){
    e.preventDefault();  
    jQuery('.accrodian ul li.active').children('div.content').hide(); 
    jQuery('.accrodian ul li.active').removeClass('active');      
    jQuery(this).parent().addClass('active');
    var text = jQuery(this).next('div.content'); 
        if (text.is(':hidden')) {
            text.slideDown('200');
            //$(this).children('span').html('-');        
        } else {
            text.slideUp('200');
            //$(this).children('span').html('+');        
        }
        
    //jQuery(this).toggleClass('show').next().slideToggle();
});




});
jQuery(function() {
       jQuery('#menu').mmenu({
            extensions    : [ 'effect-slide-menu', 'pagedim-black','border-full','shadow-page','shadow-panels'],
            searchfield   : false,
            counters      : false,
            navbar        : {title: ""},
            navbars       : [
                {
                    position    : 'top',
                    type        : 'tabs',
      content        : ['<a href="#panel-menu" class="menu current">Menu</a>','<a href="#panel-account" class="account">Account</a>']
                },
                
               {
                 "position": "bottom",
                 "content": [
                    "<a class='mobile-call' href='tel:+18774697655'>1-877-469-7655 </a>"
                 ]
              },                        
                
            ]
        });


         jQuery('.menu').on('click', function(){
          jQuery('.account').removeClass('current');
          jQuery(this).addClass('current');
            });

          jQuery('.account').on('click', function(){
          jQuery('.menu').removeClass('current');
          jQuery(this).addClass('current');
            });


    });