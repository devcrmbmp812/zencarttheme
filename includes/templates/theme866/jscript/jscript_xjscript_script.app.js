/* Catalog list/grid script */
var nbItemsPerLine = 4;
var nbItemsPerLineMobile = 3;
var nbItemsPerLineTablet = 2;

function display(view)
{
	if (view == 'list')
	{

        $('.grid-desc').css('display','none');
        $('.list-desc').css('display','block');
		$('ul.product_list').removeClass('grid').addClass('list row');
		$('.product_list > li').removeClass('col-xs-12 col-sm-'+12/nbItemsPerLineTablet+' col-md-'+ 12/nbItemsPerLine).addClass('col-xs-12');
		$('.product_list > li').each(function(index, element) {
			html = '';
			html = '<div class="product-col list">';
				html += '<div class="row">';
					html += '<div class="img col-xs-3 col-md-4">' + $(element).find('.img').html() + '</div>';
					html += '<div class="center-block col-xs-5 col-md-4">';
						html += '<h5 itemprop="name">'+ $(element).find('h5').html() + '</h5>';
						html += '<div itemprop="description" class="text">'+ $(element).find('.text').html() + '</div>';
						html += '<ul class="options text"><li>'+ $(element).find('.model').html() +'</li><li>'+ $(element).find('.brand').html() +'</li><li>'+ $(element).find('.weight').html() +'</li></ul>';
					html += '</div>';	
					html += '<div class="right-block col-xs-4 col-md-4">';
						var price = $(element).find('.content_price').html();       // check : catalog mode is enabled
						if (price != null) { 
							html += '<div class="content_price">'+ price + '</div>';
						}
					html += '</div>';
				html += '</div>';
				
			html += '</div>';
		$(element).html(html);
		});		
		$('.listing_view').find('li#list').addClass('active');
		$('.listing_view').find('li#grid').removeAttr('class');
		$.totalStorage('display', 'list');
	}
	else 
	{

        $('.grid-desc').css('display','block');
        $('.list-desc').css('display','none');
		$('ul.product_list').removeClass('list').addClass('grid row');
		$('.product_list > li').removeClass('col-xs-12').addClass('col-xs-12 col-sm-'+12/nbItemsPerLineTablet+' col-md-' + 12/nbItemsPerLine);
		$('.product_list > li').each(function(index, element) {
		html = '';
		html += '<div class="product-col maxheight">';
			html += '<div class="img">' + $(element).find('.img').html() + '</div>';
			html += '<div class="prod-info">';
				html += '<h5 itemprop="name">'+ $(element).find('h5').html() + '</h5>';
				html += '<div itemprop="description" class="text">'+ $(element).find('.text').html() + '</div>';
				html += '<div class="clearfix">';
					html += '<div class="product-buttons">';
						var price = $(element).find('.content_price').html(); // check : catalog mode is enabled
						if (price != null) { 
							html += '<div class="content_price">'+ price + '</div>';
						}
					html += '</div>';
				html += '</div>';
		html += '</div>';		
		$(element).html(html);
		});
		$('.listing_view').find('li#grid').addClass('active');
		$('.listing_view').find('li#list').removeAttr('class');
		$.totalStorage('display', 'grid');
	}	
}
function bindGrid()
{
	var view = $.totalStorage('display');

	if (view && view != 'grid') {
		display(view);
    }
	else {
        display(view);
		$('.listing_view').find('li#grid').addClass('active');
    }    
	
	$(document).on('click', '#grid', function(e){
		e.preventDefault();
		display('grid');
	});

	$(document).on('click', '#list', function(e){
		e.preventDefault();
		display('list');
	});
}

$(document).ready(function(){
    bindGrid();
})