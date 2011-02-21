$(document).ready(function() {
    $('article').bind('keyup focusout',function(event){
	alert($('article').html());
    });

    $('section').mousedown(function(event){
	if(event.which == 1)
	    $('section').bind('mousemove',$('section').move());
    });

    $('section').mouseup(function(event){	
	if(event.which == 1)
	    $('section').unbind('mousemove',$('section').move());
    });

    $('div.col21').ready(
	function(event) {
	    if($('div.col21').height() < $('div.col1').height() ) {
		$('div.col21').css("height", $('div.col1').height());
	    }

	    if($('div.col21').height() < $('div.col22').height() ) {
		$('div.col21').css("height", $('div.col22').height());
	    }

	    if($('div.col21').height() > $('div.col22').height() ) {
		$('div.col22').css("height", $('div.col21').height());
	    }
	}
    );

    $('div.col22').ready(
	function(event) {
	    if($('div.col21').height() < $('div.col22').height() ) {
		$('div.col21').css("height", $('div.col22').height());
	    }

	    if($('div.col21').height() > $('div.col22').height() ) {
		$('div.col22').css("height", $('div.col21').height());
	    }
	}
    );
});

jQuery.fn.move = function() {
    this.addClass('floating');
};
