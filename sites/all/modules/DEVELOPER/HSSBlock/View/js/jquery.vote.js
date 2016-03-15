jQuery(document).ready(function($){
	VOTE.init();
});

VOTE = {
	init:function(){
		jQuery(".activeVote a").hover(
			function() {
				jQuery(this).prevAll().andSelf().addClass('act');
				jQuery(this).nextAll().removeClass('act');
			},
			function() {
				jQuery(this).prevAll().andSelf().removeClass('act');
			}
		);

		jQuery('.activeVote a').click(function(){
			jQuery('.activeVote a').removeClass('vote');
			jQuery(this).addClass('vote');
			var star_vote = jQuery('.activeVote a.vote').attr('rel');
			jQuery('.activeVote a').each(function(){
				var vote_less = jQuery(this).attr('rel');
				if(vote_less <= star_vote){
					jQuery(this).prevAll().andSelf().addClass('vote');
					jQuery(this).nextAll().removeClass('vote');
					jQuery(this).removeClass('act');
				}
			}).unbind();
			jQuery('body').find('div.activeVote').removeClass('activeVote');
			//ajax vote
			var url = BASEPARAMS.base_url + '/vote';
			var pid = jQuery('span#pid').text();
			jQuery.ajax({
				type: "POST",
				url: url,
				data: "vote="+encodeURI(star_vote) + "&pid="+encodeURI(pid) ,
				success: function(data){
					if(data!='vote exists' || data!='vote not ok'){
						jQuery('.line-vote-show-result').html(data);
						return false;
					}
				}
			});

		});

	}
}