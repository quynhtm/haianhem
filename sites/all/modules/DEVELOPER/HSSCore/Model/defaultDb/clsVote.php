<?php
/*
* @Created by: HSS
* @Author	 : nguyenduypt86@gmail.com
* @Date 	 : 06/2014
* @Version	 : 1.0
*/

class Vote extends DbBasic{

    function __construct(){
        $this->pkey = 'id';
        $this->table = 'hss_vote';
    }

    function check_vote($pid=0,$ip=0){
    	$arrItem = $this->getAll('*', "status=1 and pid=$pid and ip='$ip'", "", "id DESC",1);
    	return $arrItem;
    }
    
    function show_vote_result($pid=0){
    	$html='';
    	$arrItem = $this->getAll('star', "status=1 and pid=$pid", "", "id DESC");

    	$vote_star1=0;
    	$vote_star2=0;
    	$vote_star3=0;
    	$vote_star4=0;
    	$vote_star5=0;

    	$percent_star1 = 0;
		$percent_star2 = 0;
		$percent_star3 = 0;
		$percent_star4 = 0;
		$percent_star5 = 0;

    	foreach($arrItem as $v){
			if($v->star==1){
				$vote_star1 = $vote_star1 + 1;
			}elseif($v->star==2){
				$vote_star2 = $vote_star2 + 1;
			}elseif($v->star==3){
				$vote_star3 = $vote_star3 + 1;
			}elseif($v->star==4){
				$vote_star4 = $vote_star4 + 1;
			}elseif($v->star==5){
				$vote_star5 = $vote_star5 + 1;
			}
    	}
		if(count($arrItem)>0){
			$total_vote = $vote_star1 + $vote_star2 + $vote_star3 + $vote_star4 + $vote_star5;
			$percent_star1 = ($vote_star1*100)/$total_vote;
			$percent_star2 = ($vote_star2*100)/$total_vote;
			$percent_star3 = ($vote_star3*100)/$total_vote;
			$percent_star4 = ($vote_star4*100)/$total_vote;
			$percent_star5 = ($vote_star5*100)/$total_vote;
			
		}
    	$html .= '<ul class="precent_vote">';
			    $html .= '<li>
							<span class="label-star" >5 sao</span>
							<span class="percent-star" title="Chiếm tỷ lệ '.$percent_star5.'%"><span class="show-percent" style="width:'.$percent_star5.'%"></span></span>
							<span class="num-count-star">'.$vote_star5.'</span>
						  </li>';
			    $html .= '<li>
							<span class="label-star">4 sao</span>
							<span class="percent-star" title="Chiếm tỷ lệ '.$percent_star4.'%"><span class="show-percent" style="width:'.$percent_star4.'%"></span></span>
							<span class="num-count-star">'.$vote_star4.'</span>
						</li>'; 

				$html .= '<li>
							<span class="label-star">3 sao</span>
							<span class="percent-star" title="Chiếm tỷ lệ '.$percent_star3.'%"><span class="show-percent" style="width:'.$percent_star3.'%"></span></span>
							<span class="num-count-star">'.$vote_star3.'</span>
						</li>'; 

				$html .= '<li>
							<span class="label-star">2 sao</span>
							<span class="percent-star" title="Chiếm tỷ lệ '.$percent_star2.'%"><span class="show-percent" style="width:'.$percent_star2.'%"></span></span>
							<span class="num-count-star">'.$vote_star2.'</span>
						</li>'; 
				$html .= '<li>
							<span class="label-star">1 sao</span>
							<span class="percent-star" title="Chiếm tỷ lệ '.$percent_star1.'%"><span class="show-percent" style="width:'.$percent_star1.'%"></span></span>
							<span class="num-count-star">'.$vote_star1.'</span>
						</li>'; 		
		$html .= '</ul>';
		return $html;
    }

}

