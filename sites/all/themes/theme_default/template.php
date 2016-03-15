<?php
/*
* @Created by:
* @Author	: nguyenduypt86@gmail.com
* @Date 	: 2014
* @Version	: 1.0 
*/

function theme_default_preprocess_html(&$vars) {
  global $user;
	$vars['body_id'] = 'pid-' . drupal_clean_css_identifier(drupal_get_path_alias($_GET['q']));
	if (drupal_get_title()) {
		$head_title = array(
		  'title' => strip_tags(drupal_get_title()),
		  'name' => '',
		);
   }else{
		$head_title = array('name' => '');
  }
  $vars['head_title_array'] = $head_title;
  $vars['head_title'] = implode('', $head_title);
	
	
}