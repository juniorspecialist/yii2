jQuery.noConflict();
/**
 * jQuery.include - script inclusion jQuery plugin
 * Based on idea from http://www.gnucitizen.org/projects/jquery-include/
 * @author Tobiasz Cudnik
 * @link http://meta20.net/.include_script_inclusion_jQuery_plugin
 * @license MIT
 */
// overload jquery's onDomReady
if ( jQuery.browser.mozilla || jQuery.browser.opera ) {
	document.removeEventListener( "DOMContentLoaded", jQuery.ready, false );
	document.addEventListener( "DOMContentLoaded", function(){ jQuery.ready(); }, false );
}
jQuery.event.remove( window, "load", jQuery.ready );
jQuery.event.add( window, "load", function(){ jQuery.ready(); } );
jQuery.extend({
	includeStates: {},
	include: function(url, callback, dependency){
		if ( typeof callback != 'function' && ! dependency ) {
			dependency = callback;
			callback = null;
		}
		url = url.replace('\n', '');
		jQuery.includeStates[url] = false;
		var script = document.createElement('script');
		script.type = 'text/javascript';
		script.onload = function () {
			jQuery.includeStates[url] = true;
			if ( callback )
				callback.call(script);
		};
		script.onreadystatechange = function () {
			if ( this.readyState != "complete" && this.readyState != "loaded" ) return;
			jQuery.includeStates[url] = true;
			if ( callback )
				callback.call(script);
		};
		script.src = url;
		if ( dependency ) {
			if ( dependency.constructor != Array )
				dependency = [dependency];
			setTimeout(function(){
				var valid = true;
				jQuery.each(dependency, function(k, v){
					if (! v() ) {
						valid = false;
						return false;
					}
				})
				if ( valid )
					document.getElementsByTagName('head')[0].appendChild(script);
				else
					setTimeout(arguments.callee, 10);
			}, 10);
		}
		else
			document.getElementsByTagName('head')[0].appendChild(script);
		return function(){
			return jQuery.includeStates[url];
		}
	},
	readyOld: jQuery.ready,
	ready: function () {
		if (jQuery.isReady) return;
		imReady = true;
		jQuery.each(jQuery.includeStates, function(url, state) {
			if (! state)
				return imReady = false;
		});
		if (imReady) {
			jQuery.readyOld.apply(jQuery, arguments);
		} else {
			setTimeout(arguments.callee, 10);
		}
	}
});

///// include js files ////////////
 jQuery.include('js/coin-slider.js');
  <!-- floatdialog block -->
	jQuery.include('js/floatdialog.js');
			   
  <!-- floatdialog block -->
  <!-- superfish menu begin -->
	jQuery.include('js/hoverIntent.js');
	jQuery.include('js/superfish.js');
  <!-- superfish menu end -->
  
  <!-- tooltip begin -->
	jQuery.include('js/atooltip.jquery.js');
  <!-- tooltip end -->
  
  
  <!-- contact form begin -->
	jQuery.include('js/runonload.js');
	jQuery.include('js/contact-form.js');
  <!-- contact form end -->
  
	jQuery.include('js/scrollTop.js');
	jQuery.include('js/flashobject.js');
	
  <!-- tabs begin -->
	jQuery.include('js/tabs.js');
  <!-- tabs end -->
  
  <!-- hover-image begin -->
	jQuery.include('js/hover-image.js');
  <!-- hover-image -->

  <!-- prettyPhoto begin -->
	jQuery.include('js/jquery.prettyPhoto.js');
  <!-- prettyPhoto end-->
  
  
  <!-- gallery -->
  jQuery.include('js/jquery.galleriffic.js');
  jQuery.include('js/jquery.opacityrollover.js');
  <!-- gallery -->

  
 	  <!-- twitter -->
	  jQuery.include('js/jquery.twitter.search.js');
	  <!-- twitter -->
	  
	  
	  jQuery.include('js/jquery.cycle.all.latest.js');
	 
	  <!-- tooltip begin -->
	  jQuery.include('js/kwicks-1.5.1.pack.js');
	  <!-- tooltip end -->  

	  
		
			
		jQuery(document).ready(function() {
		
		//superfish menu init
		jQuery('ul.sf-menu').superfish();
		
		// initiate tool tip

		// basic usage  
		jQuery('.normaltip').aToolTip();  
			
		// fixed tooltip  
		jQuery('.fixedtip').aToolTip({  
				fixed: true  
		});
		jQuery('.clicktip').aToolTip({  
				clickIt: true,  
				tipContent: 'Hello I am aToolTip with content from param'  
		}); 
		
		///// jumper ////////////
			
			
			jQuery(document).ready(function(){
				if (jQuery("#coin-slider").length) {
					jQuery('#coin-slider').coinslider({
						width: 909, // width of slider panel
						height: 463 // height of slider panel
					});
				}
			});
			
			jQuery('.hr a').click(
				function (e) {
					jQuery('html, body').animate({scrollTop: '0px'}, 800);
					return false;
				}
			);
			
			
			jQuery("#accordion dt").eq().addClass("active");
			jQuery("#accordion dd").eq().show();
		
			jQuery("#accordion dt").click(function(){
				jQuery(this).next("#accordion dd").slideToggle("slow")
				.siblings("#accordion dd:visible").slideUp("slow");
				jQuery(this).toggleClass("active");
				jQuery(this).siblings("#accordion dt").removeClass("active");
				return false;
			});
			
			// slideDown
			jQuery(".slideDown dt").click(function(){
				jQuery(this).toggleClass("active").parent(".slideDown").find("dd").slideToggle();
			})
			
			
			
			jQuery('.kwicks').kwicks({
				max : 600,
				spacing : 0,
				event : 'mouseover'
			});
			
			///// code grabber ////////////
			jQuery(".code a.code-icon").toggle(function(){
				jQuery(this).find("i").text("-");
				jQuery(this).next("div.grabber").slideDown().find("i").text("-");
			}, function(){
				jQuery(this).find("i").text("+");
				jQuery(this).next("div.grabber").slideUp();
			})
			
			
			
		});	
		jQuery(document).ready(function(){
			if (jQuery("#demo6").length) {
				jQuery(document).ready(function(){
					jQuery("#demo6").floatdialog("dialog6", {move: 'down', effect: false})
				})
			};
		});
	
		jQuery(function(){

			// Twitter
			
			jQuery(document).ready(function(){
				if (jQuery("#twitter").length) {
					jQuery("#twitter").getTwitter({
						userName: "lorem_ipsum_dol",
						numTweets: 3,
						loaderText: "Loading tweets...",
						slideIn: true,
						showHeading: false,
						headingText: "Latest Tweets",
						showProfileLink: true
					});
				}
			});
			
			
		});
		jQuery(document).ready(function(){
				if (jQuery("a[rel^='prettyPhoto']").length) {
				jQuery(document).ready(function() {
					// prettyPhoto
					jQuery("a[rel^='prettyPhoto']").prettyPhoto({theme:'facebook'});
					
					///// codegrabber ////////////
					jQuery(".code a.code-icon").toggle(function(){
						jQuery(this).addClass("minus").next("p").slideDown();
					}, function(){
						jQuery(this).removeClass("minus").next("p").slideUp();
					});
				});
		}
			});
			
				function onAfter(curr, next, opts, fwd) {
				//get the height of the current slide
				var jQueryht = jQuery(this).height();
				//set the container's height to that of the current slide
				jQuery(this).parent().animate({height: jQueryht});
				};
				
			jQuery(document).ready(function(){
				if (jQuery("#shuffle").length) {	
					jQuery.include('js/jquery.easing.1.3.js');
						jQuery('#shuffle').cycle({
							fx:     'shuffle',
							delay:  -4000
						});
				}
			});	
			jQuery(document).ready(function(){
				if (jQuery("#zoom").length) {
					jQuery('#zoom').cycle({
						fx:    'zoom',
						sync:  false,
						delay: -2000
					});
				}
			});	
			
			jQuery(document).ready(function(){
				if (jQuery("#fade").length) {
					jQuery('#fade').cycle();
				}
			});
			
			jQuery(document).ready(function(){
				if (jQuery("#curtainX").length) {
					jQuery('#curtainX').cycle({
						fx:    'curtainX',
						sync:  false,
						delay: -2000
					 });
				}
			});
			
			jQuery(document).ready(function(){
				if (jQuery("#scrollDown").length) {	
					jQuery('#scrollDown').cycle({ 
							fx:      'scrollDown', 
							speedIn:  2000, 
							speedOut: 500, 
							easeIn:  'bounceout', 
							easeOut: 'backin', 
							delay:   -2000 
						});
				}
			});
			
			jQuery(document).ready(function(){
				if (jQuery("#fade_nav").length) {
					jQuery('#fade_nav').cycle({ 
							fx:     'fade', 
							speed:  'fast', 
							timeout: 0, 
							next:   '#next', 
							prev:   '#prev' 
						});
				}
			});
			
			jQuery(document).ready(function(){
				if (jQuery("#text_slider").length) {	
					jQuery('#text_slider').cycle({ 
						fx:     'scrollHorz', 
						height: 'auto',
						speed:  'slow', 
						timeout: 0, 
						next:   '#next2', 
						prev:   '#prev2',
						after: onAfter
					});
				}
			});
			jQuery(document).ready(function(){
				if (jQuery("#multi_effects").length) {
					jQuery('#multi_effects').cycle({ 
						fx:      'all', 
						speed:  'slow',
						timeout: 2000
					});	
				}
			});
			
// We only want these styles applied when javascript is enabled
			
			jQuery(window).load(function(){
				if (jQuery("#thumbs").length) {
					jQuery('div.content').css('display', 'block');
					// Initially set opacity on thumbs and add
					// additional styling for hover effect on thumbs
					var onMouseOutOpacity = 1.0;
					jQuery('#thumbs ul.thumbs li').opacityrollover({
						mouseOutOpacity:   onMouseOutOpacity,
						mouseOverOpacity:  0.5,
						fadeSpeed:         'fast',
						exemptionSelector: '.selected'
					});
			
				// Initialize Advanced Galleriffic Gallery
				var gallery = jQuery('#thumbs').galleriffic({
					delay:                     2500,
					numThumbs:                 26,
					preloadAhead:              10,
					enableTopPager:            false,
					enableBottomPager:         false,
					maxPagesToShow:            7,
					imageContainerSel:         '#slideshow',
					controlsContainerSel:      '#controls',
					captionContainerSel:       '#caption',
					loadingContainerSel:       '#loading',
					renderSSControls:          false,
					renderNavControls:         false,
					playLinkText:              'Play Slideshow',
					pauseLinkText:             'Pause Slideshow',
					prevLinkText:              '&lsaquo; Previous Photo',
					nextLinkText:              'Next Photo &rsaquo;',
					nextPageLinkText:          'Next &rsaquo;',
					prevPageLinkText:          '&lsaquo; Prev',
					enableHistory:             false,
					autoStart:                 false,
					syncTransitions:           true,
					defaultTransitionDuration: 900,
					onSlideChange:             function(prevIndex, nextIndex) {
					// 'this' refers to the gallery, which is an extension of jQuery('#thumbs')
					this.find('ul.thumbs').children()
						.eq(prevIndex).fadeTo('fast', onMouseOutOpacity).end()
						.eq(nextIndex).fadeTo('fast', 0.5);
					},
					onPageTransitionOut:       function(callback) {
						this.fadeTo('fast', 0.0, callback);
					},
					onPageTransitionIn:        function() {
						this.fadeTo('fast', 1.0);
					}
				});
				}
			});
