(function($) {
	Drupal.behaviors.HSSPopupMessages = {
		attach: function (context) {
			var HSSPopupMessages = Drupal.settings.HSSPopupMessages; 
			var message_box = $('#pop-messages-wrapper');
			/* jQuery UI Enhancements */
			if (HSSPopupMessages.jquery_ui != null) {
				if (HSSPopupMessages.jquery_ui.draggable == '1') { message_box.draggable(); }
			}
			
			/* Popup Message handling */
			if (!message_box.hasClass("pop-messeges-processed")) {			
				/* Functions to determine the popin/popout animation */
				HSSPopupMessages.open = function() {
					switch (HSSPopupMessages.popin.effect) {
						case 'fadeIn': message_box.fadeIn(HSSPopupMessages.popin.duration);
							break;
						case 'slideDown': message_box.slideDown(HSSPopupMessages.popin.duration);
							break;
						default: message_box.fadeIn(HSSPopupMessages.popin.duration);
							break;
					}
				}
				HSSPopupMessages.close = function() {
					$('.simplemodal-overlay').css('display','none');
					switch (HSSPopupMessages.popout.effect) {
						case 'fadeOut':	message_box.fadeOut(HSSPopupMessages.popout.duration);
							break;
						case 'slideUp':	message_box.slideUp(HSSPopupMessages.popout.duration);
							break;
						default: message_box.fadeOut(HSSPopupMessages.popout.duration);
							break;
					}
					message_box.addClass("pop-messeges-processed");
				}
				/* Function to determine closing count */
				HSSPopupMessages.countDownClose = function(seconds) {
					if(seconds > 0) {
						seconds--;
						if (HSSPopupMessages.show_countdown == '1') {
							$('.message-timer').text(Drupal.t('Closing in' + ' ' + seconds + ' ' + Drupal.t('seconds')));
						}
			      if(seconds > 0) {
			      	HSSPopupMessages.countDown = setTimeout( function() {HSSPopupMessages.countDownClose(seconds);}, 1000 );
			      }
			      else {
							HSSPopupMessages.close();
						}
					}
				}
			
			$('<div></div>')
				.addClass('simplemodal-overlay')
				.css($.extend({}, {
					display: 'block',
					opacity: 0.5,
					height: $(window).height(),
					width: $(window).width(),
					position: 'fixed',
					left: 0,
					top: 0,
					background: '#333333',
					zIndex: 200 + 1
				}))
				.appendTo('body');
				/* Determine Popup Message position */
				message_box.css('width', HSSPopupMessages.width);
				var vertical = HSSPopupMessages.vertical;	var horizontal = HSSPopupMessages.horizontal;
				switch (HSSPopupMessages.position) {
					case 'center':
						vertical = ( $(window).height() - message_box.height() ) / 2;
						horizontal = ( $(window).width() - message_box.width() ) / 2;
						message_box.css({"top":vertical + 'px', "left":horizontal + 'px'});
						break;
					case 'tl':
						message_box.css({"top":vertical + 'px', "left":horizontal + 'px'});
						break;
					case 'tr':
						message_box.css({"top":vertical + 'px', "right":horizontal + 'px'});
						break;
					case 'bl':
						message_box.css({"bottom":vertical + 'px', "left":horizontal + 'px'});
						break;
					case 'br':
						message_box.css({"bottom":vertical + 'px', "right":horizontal + 'px'});
						break;
				}
			
				/* Here we control closing and opeing effects and controls */
				if (HSSPopupMessages.opendelay != 0) { 
					setTimeout( function() {HSSPopupMessages.open()}, HSSPopupMessages.opendelay * 1000 );
				} else { HSSPopupMessages.open(); }
				if (HSSPopupMessages.autoclose != 0) {
					HSSPopupMessages.countDownClose(HSSPopupMessages.autoclose);
				}
				if (HSSPopupMessages.hover_autoclose == '1') {
					message_box.hover(function() {
						clearTimeout(HSSPopupMessages.countDown);
						$('.message-timer').fadeOut('slow');
						}, function() {
							/* Suggest something to do here! */
						}
					);
				}
				$('a.message-close').click(function() { HSSPopupMessages.close();	return false; });
				/* Esc key handler for closing the message. This doesn't work on Safari or Chrome
					 See the issue here: http://code.google.com/p/chromium/issues/detail?id=14635
				*/
				$(document).keypress(function(e){
					if(e.keyCode==27){  
						HSSPopupMessages.close();
						return false; 
					}
				});
			
				/* Determine Popup Message position for IE6 bug with fixed display */
				
				/*if (HSSPopupMessages.fixed == '1' && !($.browser.msie && $.browser.version == '6.0')) {
					message_box.css({"position":"fixed"});
				}
				else { //IE6 handing
					message_box.css({"position":"absolute"});
					$(window).scroll(function() { message_box.stop().css({top:($(window).scrollTop() + vertical) + 'px'});});
				}*/
			}
		}
	}
})(jQuery);
