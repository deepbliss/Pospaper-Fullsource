define([
        'jquery',
		'mage/validation'
        ],
    function($,mcs){
    return {
        mcsData:function(ajaxurl){

			$(document).on('click','.track-info',function (event){
                
				event.preventDefault();
                var data = $('#track_order').serialize();
				
				if ($('#track_order').validation() && $('#track_order').validation('isValid'))
				{
					$.ajax({
						url:ajaxurl,
						type:'POST',
						showLoader: true,
						data:data,
						success:function(response){
							$("#mcs-tracking-info").html(response);
						}
					});
				}
            });

        }
    }
});