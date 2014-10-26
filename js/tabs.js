/* ------------------------------------------------------------------------
	Do it when you're ready dawg!
------------------------------------------------------------------------- */

	jQuery(function(){
		tabs.init();
	});
	
	tabs = {
		init : function(){
			jQuery('.tabs').each(function(){
				jQuery(this).find('.tab-content:gt(0)').hide();

				jQuery(this).find('ul.nav a').click(function(){
					jQuery(this).parents('div.tabs').find('.tab-content').hide();
					jQuery(jQuery(this).attr('href')).show();

					jQuery(this).parent().addClass('selected').siblings().removeClass('selected');

					return false;
				});
			});
		}
	}