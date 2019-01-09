
var suggestionAjax = false;
var timeoutID = false;
var keywords = '';
var page = 1;

jQuery(document).ready(function(){
	var keywordInput = jQuery("input[name='keyword']","form[name='quick_find_header']");
	jQuery('<div id="suggestions"></div>').insertAfter(keywordInput);
	
	var leftPos = jQuery('#suggestions').width() - keywordInput.width();
	jQuery('#suggestions').css('left','-'+leftPos+'px');
	
	jQuery('#suggestionNext').live('click',function(){ page++; doSearch(); });
	jQuery('#suggestionPrev').live('click',function(){ page--; doSearch(); });
	
	keywordInput.keyup(function(){
		page = 1;
		keywords = this.value;
		doSearch();
	}).blur(function(){jQuery('#suggestions').fadeOut();}).attr('autocomplete','off');
});

function doSearch() {
	jQuery('#suggestions').fadeOut();
	if (suggestionAjax != false) { suggestionAjax.abort(); }
	if (timeoutID != false) { window.clearTimeout(timeoutID); }
	if (keywords != '') {
		timeoutID = window.setTimeout(function() {
			suggestionAjax = jQuery.get("search_api.php", {action: 'suggestion.search', keyword: keywords, page: page}, function(data) { 
				jQuery('#suggestions').html(data);
				if (data != '') { jQuery('#suggestions').fadeIn(); }
			});		
		}, 250);
	}
}