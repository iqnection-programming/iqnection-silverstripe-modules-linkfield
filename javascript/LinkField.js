(function($){
	"use strict";
	$.entwine('ss',function($){
		$(".field.link").entwine({
			onmatch: function(){
				var fieldName = $(this).attr('data-name');
				updateLinkField(fieldName,$("input[name='"+fieldName+"[Location]']:checked").val());
				$("input[name='"+fieldName+"[Location]']").change(function(){
					updateLinkField(fieldName,$("input[name='"+fieldName+"[Location]']:checked").val());
				});
			}
		});
		if (typeof updateLinkField !== 'function'){
			var updateLinkField = function(fieldName,selected){
				var internalField = $("input[name='"+fieldName+"[Internal]']").parents('.field.linkfieldinternal').first();
				var externalField = $("input[name='"+fieldName+"[External]']").parents('.field.linkfieldexternal').first();
				switch(selected)
				{
					default:
					case 'External':
					{
						internalField.slideUp();
						externalField.slideDown();
						break;
					}
					case 'Internal':
					{
						internalField.slideDown();
						externalField.slideUp();
						break;
					}
				}
			};
		}
	});
}(jQuery));