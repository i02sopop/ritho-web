/* This file is part of ritho-web.

   ritho-web is free software: you can redistribute it and/or modify
   it under the terms of the GNU Affero General Public License as 
   published by the Free Software Foundation, either version 3 of the
   License, or (at your option) any later version.

   ritho-web is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
   GNU Affero General Public License for more details.

   You should have received a copy of the GNU Affero General Public
   License along with Foobar. If not, see <http://www.gnu.org/licenses/>.
*/

$(document).ready(function() {
	$('article').bind('keyup focusout',function(event){
		alert($('article').html());
	});

	$('section').mousedown(function(event){
		if (event.which == 1)
			$('section').bind('mousemove',$('section').move());
	});

	$('section').mouseup(function(event){	
		if (event.which == 1)
			$('section').unbind('mousemove',$('section').move());
	});

	$('div.col21').ready(
		function(event) {
			if ($('div.col21').height() < $('div.col1').height() ) {
				$('div.col21').css("height", $('div.col1').height());
			}

			if ($('div.col21').height() < $('div.col22').height() ) {
				$('div.col21').css("height", $('div.col22').height());
			}

			if ($('div.col21').height() > $('div.col22').height() ) {
				$('div.col22').css("height", $('div.col21').height());
			}
		}
	);

	$('div.col22').ready(
		function(event) {
			if ($('div.col21').height() < $('div.col22').height() ) {
				$('div.col21').css("height", $('div.col22').height());
			}

			if ($('div.col21').height() > $('div.col22').height() ) {
				$('div.col22').css("height", $('div.col21').height());
			}
		}
	);
});

jQuery.fn.move = function() {
	this.addClass('floating');
};
