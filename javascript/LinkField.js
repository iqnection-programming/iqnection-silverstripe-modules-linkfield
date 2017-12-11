(function($){
	"use strict";
	$.entwine('ss',function($){
		$(document).ready(function(){
			$(".field.link").entwine({
				onmatch: function(){
					var fieldName = $(this).data('name');
					updateLinkField(fieldName,$("input[name='"+fieldName+"[Location]']:checked").val());
					$("input[name='"+fieldName+"[Location]']").change(function(){
						updateLinkField(fieldName,$("input[name='"+fieldName+"[Location]']:checked").val());
					});
				}
			});
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
						internalField.hide();
						externalField.show();
						break;
					}
					case 'Internal':
					{
						internalField.show();
						externalField.hide();
						break;
					}
				}
			};
		}
	});
}(jQuery));
