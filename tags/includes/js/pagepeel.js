 /*
 * Copyright (c) 2008-2009 John McMullen (http://www.smple.com)
 * This is licensed under GPL (http://www.opensource.org/licenses/gpl-license.php) licenses.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
*/ 
 
 (function($){
	$.fn.pagePeel = function(options){
	
		var defaults = {
			
			// SET WIDTH / HEIGHT OF CORNER AD WHEN SMALL
			smallWidth: 120,
			smallHeight: 120,
			smallBG: 'pp-src/smallBG.png',
			
			// SET WIDTH / HEIGHT OF CORNER AD INTRO
			introWidth: 300,
			introHeight:300,
			
			// SET WIDTH / HEIGHT OF BIG AD (SAME AS .SWF)
			bigWidth: 500,
			bigHeight: 500,
			bigBG: 'pp-src/bigBG.png',
			bigAd: 'pp-src/bigAd.jpg',
			bigSWF: 'pp-src/page-peel-big.swf',
			
			// POSITION WILL REMAIN THE SAME FOR BOTH
			hPosition: 'right', // can use 'left' or 'right'
			vPosition: 'top', // can use 'top' or 'bottom'
			
			// CHOOSE WHETHER TO USE FLASH OR NOT
			flash: true,
			
			// WHETHER TO USE INTRO ANIMATION AND WHAT FILE TO USE
			introAnim: false,
			bigSWFIntro: 'pp-src/page-peel-big-intro.swf',
			
			// ADD LINK IF LEFT 'BLANK', NO ADD LINK WILL BE APPLIED
			adLink: 'blank',
			adLinkTarget: '_blank'
		};
		
		var options = $.extend(defaults, options);
		
		var element = this;
		
	return this.each(function(){
		
		if (options.flash === true){
			if (options.introAnim === true){
				$(element).after("<div class='page-peel-adjuster' style='overflow:hidden; width:" + options.introWidth + "px; height: " + options.introHeight + "px; display:none; position:absolute; " + options.vPosition + ":0px; " + options.hPosition + ":0px;'><div class='page-peel-holder' style='width:" + options.bigWidth + "px; height: " + options.bigHeight + "px; display:block; position:absolute; " + options.vPosition + ":0px; " + options.hPosition + ":0px; z-index:9999;'><embed style='display: block;' src='" + options.bigSWFIntro + "' flashvars='linkURL=" + options.adLink + "&linkURLTarget=" + options.adLinkTarget + "&bigURL=" + options.bigAd + "' type='application/x-shockwave-flash' pluginspage='http://www.adobe.com/go/getflashplayer' wmode='transparent' autoplay='false' width='" + options.bigWidth + "' height='" + options.bigHeight + "'></div></div>");
			}
			if (options.introAnim === false){
				$(element).after("<div class='page-peel-adjuster' style='overflow:hidden; width:" + options.smallWidth + "px; height: " + options.smallHeight + "px; display:none; position:absolute; " + options.vPosition + ":0px; " + options.hPosition + ":0px;'><div class='page-peel-holder' style='width:" + options.bigWidth + "px; height: " + options.bigHeight + "px; display:block; position:absolute; " + options.vPosition + ":0px; " + options.hPosition + ":0px; z-index:9999;'><embed style='display: block;' src='" + options.bigSWF + " 'flashvars='linkURL=" + options.adLink + "&linkURLTarget=" + options.adLinkTarget + "&bigURL=" + options.bigAd + "' type='application/x-shockwave-flash' pluginspage='http://www.adobe.com/go/getflashplayer' wmode='transparent' autoplay='false' width='" + options.bigWidth + "' height='" + options.bigHeight + "'></div></div>");
			}
		
			$('.page-peel-adjuster').hover(
				function(){
					var w = $(this).width();
					if (w === 300){
						$(this).stop().css({
							width: options.smallWidth,
							height: options.smallHeight
						});
					} else {
						$(this).css('z-index','9999');
						$(this).stop().css({
							width: options.bigWidth,
							height: options.bigHeight
						});
					}
				},
				function(){
					$(this).animate({width:options.smallWidth, height:options.smallHeight},1000)
					$(this).css('z-index','1000');
				}
			);
			
			$('.page-peel-adjuster').css('display','block');
		}
		
		if (options.flash !== true){
			$(element).after("<div class='page-peel-adjuster' style='width:" + options.smallWidth + "px; height: " + options.smallHeight + "px; display:block; position:absolute; " + options.vPosition + ":0px; " + options.hPosition + ":0px; background:url(" + options.smallBG + ") " + options.vPosition + " " + options.hPosition + " no-repeat;'");
			
			$('.page-peel-adjuster').hover(
				function(){
					$(this).css('z-index','9999');
					$(this).css({
						width: options.bigWidth,
						height: options.bigHeight,
						background: 'url(' + options.bigBG + ') top right no-repeat'
					});
				},
				function(){
					$(this).css({
						width: options.smallWidth,
						height: options.smallHeight,
						background: 'url(' + options.smallBG + ') top right no-repeat'
					});
					$(this).css('z-index','1000');
				}
			);
		}
	});
		
	};
})(jQuery);