<?php
/*
* @Created by: HSS
* @Author	 : nguyenduypt86@gmail.com
* @Date 	 : 06/2014
* @Version	 : 1.0
*/
class clsSeo{

	public static function SEO($title='', $img='', $meta_title='', $meta_keyword='', $meta_description=''){
		global $base_url;

		$url = $base_url.request_uri();

		//set link img
		if($img==''){
			$img = $base_url.'/uploads/images/default.jpg';
		}

		//set text meta
		if($meta_title==''){
			$meta_title=$title;
		}

		//set title page
		drupal_set_title($meta_title);

		if($meta_keyword==''){
			$meta_keyword=$title;
		}

		if($meta_description==''){
			$meta_description=$title;
		}

		//remove skyper_toolbar in website
		$skyper_toolbar = array(
			  '#tag' => 'meta',
			  '#attributes' => array(
			    'name' => 'SKYPE_TOOLBAR',
			    'content' => 'SKYPE_TOOLBAR_PARSER_COMPATIBLE',
			  ),
			);
		drupal_add_html_head($skyper_toolbar, 'skyper_toolbar');

		//<meta content="name site" name="copyright">
		$copyright = array(
			  '#tag' => 'meta',
			  '#attributes' => array(
			    'name' => 'copyright',
			    'content' => $base_url,
			  ),
			);
		drupal_add_html_head($copyright, 'copyright');

		//<meta content="18000" http-equiv="REFRESH">
		$refresh = array(
			  '#tag' => 'meta',
			  '#attributes' => array(
			  	'http-equiv' => 'REFRESH',
			   	'content' => '18000',
			  ),
			);
		drupal_add_html_head($refresh, 'refresh');

		//<meta name="robots" content="INDEX,FOLLOW">
		$robot = array(
			  '#tag' => 'meta',
			  '#attributes' => array(
			  	'name' => 'robots',
			 	'content' => 'INDEX,FOLLOW',
			  ),
			);
		drupal_add_html_head($robot, 'robot');
		/*-----------------------------------------------------seo google-----------------------------------------------------*/

		//<link rel="canonical" href="link post">
		$g_canonical = array(
			  '#tag' => 'meta',
			  '#attributes' => array(
			  	'rel' => 'canonical',
			  	'href' => $url,
			  ),
			);
		drupal_add_html_head($g_canonical, 'g canonical');

		//<meta content="link img" itemprop="image">
		$g_img = array(
			  '#tag' => 'meta',
			  '#attributes' => array(
			  	'itemprop' =>'image',
			  	'content' => $img,
			  ),
			);
		drupal_add_html_head($g_img, 'g img');

		//<meta content="meta keyword here" name="keywords">
		$g_keyword = array(
			  '#tag' => 'meta',
			  '#attributes' => array(
			  	'name' => 'keywords',
			  	'content' => $meta_keyword,
			  ),
			);
		drupal_add_html_head($g_keyword, 'g keyword');

		//<meta content="meta description here" name="description">
		$g_description = array(
			  '#tag' => 'meta',
			  '#attributes' => array(
			  	'name' => 'description',
			  	'content' => $meta_description,
			  ),
			);
		drupal_add_html_head($g_description, 'g description');

		/*-----------------------------------------------------seo facebook-----------------------------------------------------*/

		//<meta content="site name" property="og:site_name">
		$f_site_name = array(
			  '#tag' => 'meta',
			  '#attributes' => array(
			  	'property' => 'og:site_name',
			  	'content' => $base_url,
			  ),
			);
		drupal_add_html_head($f_site_name, 'f site name');

		//<meta content="article" property="og:type">
		$f_article = array(
			  '#tag' => 'meta',
			  '#attributes' => array(
			  	'property' => 'og:type',
			  	'content' => 'article',
			  ),
			);
		drupal_add_html_head($f_article, 'f article');

		//<meta content="title" property="og:title">
		$f_title = array(
			  '#tag' => 'meta',
			  '#attributes' => array(
			  	'property' => 'og:title',
			  	'content' => $title,
			  ),
			);
		drupal_add_html_head($f_title, 'f title');

		//<meta content="link site" property="og:url">
		$f_url = array(
			  '#tag' => 'meta',
			  '#attributes' => array(
			  	'property' => 'og:url',
			  	'content' => $url,
			  ),
			);
		drupal_add_html_head($f_url, 'f url');

		//<meta content="link img" property="og:image">
		$f_img = array(
			  '#tag' => 'meta',
			  '#attributes' => array(
			  	'property' => 'og:image',
			  	'content' => $img,
			  ),
			);
		drupal_add_html_head($f_img, 'f img');

		//<meta content="meta description here" property="og:description">
		$f_description = array(
			  '#tag' => 'meta',
			  '#attributes' => array(
			  	'property' => 'og:description',
			  	'content' => $meta_description,
			  ),
		);
	}
}