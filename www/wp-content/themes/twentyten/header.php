<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<link rel="shortcut icon" href="http://funleaks.ru/wp-content/themes/twentyten/images/favicon.ico">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'twentyten' ), max( $paged, $page ) );
	?></title>


<link rel="stylesheet" href="http://funleaks.ru/wp-content/themes/twentyten/style.css" type="text/css" media="screen" />



<!--[if lt IE 7]>
  <link rel="stylesheet" href="http://funleaks.ru/wp-content/themes/twentyten/lt-ie7.css" type="text/css" media="screen" />
<![endif]-->
<!--[if lte IE 7]>
  <link rel="stylesheet" href="http://funleaks.ru/wp-content/themes/twentyten/lte-ie7.css" type="text/css" media="screen" />
<![endif]-->

<script type="text/javascript" src="http://funleaks.ru/wp-content/themes/twentyten/hover.js"></script>
<script src="http://vkontakte.ru/js/api/xd_connection.js?2" type="text/javascript"></script>



<?php
	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();
?>

</head>
<body>
<div id="header">
	<div class="top"></div>

	<div id="menu">
				<?php wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'primary' ) ); ?>
	</div> 
    <div class="title"><a href="http://funleaks.ru" title="FunLeaks.ru - народная молва">FunLeaks.ru - народная молва</a>
    <div class="feeds">
		<span class="rss"><a href="http://funleaks.ru/feed.xml"><img src="http://funleaks.ru/wp-content/themes/twentyten/images/rss.gif"></img></a></span>
	</div>
	</div>
<div id="container">

<div id="sidebar">
<a href="http://funleaks.ru" title="FunLeaks.ru - народная молва">
<div id="logo"></div></a>
<div class="ads">
</div>
<div id="sidebarwrap">
<div id="left_sidebar">
	<div id="primary" class="widget-area" role="complementary">
			<ul class="xoxo">
				<?php  dynamic_sidebar( 'primary-widget-area' ) ?>			
			</ul>
		</div>

<div id="right_sidebar">	    
</div>
</div>
</div>
</div>

