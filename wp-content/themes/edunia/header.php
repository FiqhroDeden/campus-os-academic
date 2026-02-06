<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="https://gmpg.org/xfn/11">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<div class="preloader-wrapper">
    <div class="preloader">
    <?php
    if(!empty(get_theme_mod('preload_animation'))){
    	if(get_theme_mod('preload_animation') == 'circle'){
    		echo '<div class="lds-circle"><div></div></div>';
    	}elseif(get_theme_mod('preload_animation') == 'dualring'){
    		echo '<div class="lds-dual-ring"></div>';
    	}elseif(get_theme_mod('preload_animation') == 'ellipsis'){
    		echo '<div class="lds-ellipsis"><div></div><div></div><div></div><div></div></div>';
    	}elseif(get_theme_mod('preload_animation') == 'facebook'){
    		echo '<div class="lds-facebook"><div></div><div></div><div></div></div>';
    	}elseif(get_theme_mod('preload_animation') == 'ring'){
    		echo '<div class="lds-ring"><div></div><div></div><div></div><div></div></div>';
    	}else{
    		echo '<div class="loading"><div>G</div><div>N</div><div>I</div><div>D</div><div>A</div><div>O</div><div>L</div></div>';
    	}
    }else{
    	echo '<div class="loading"><div>G</div><div>N</div><div>I</div><div>D</div><div>A</div><div>O</div><div>L</div></div>';
    }
    ?>
    </div>
</div>
<?php wp_body_open(); ?>
<div id="page" class="site">
	<a class="skip-link screen-reader-text" href="#primary"><?php esc_html_e( 'Skip to content', 'edunia' ); ?></a>

	<div class="header-wrapper">
	<?php
	if ( get_theme_mod( 'topbar_isactive', 0 ) == 1 ) {
	?>
	<div class="top-bar">
	<div class="container">
		<div class="nav-left">
			<?php
				echo ((!empty(get_theme_mod('topbar_contacttitle')))?'<div class="contact-label">'.get_theme_mod('topbar_contacttitle').'</div>':'');
				echo ((!empty(get_theme_mod('topbar_contactphone')))?'<div class="contact-phone"><a href="tel:'.get_theme_mod('topbar_contactphone').'"><span class="icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"></path><path d="M21 16.42v3.536a1 1 0 0 1-.93.998c-.437.03-.794.046-1.07.046-8.837 0-16-7.163-16-16 0-.276.015-.633.046-1.07A1 1 0 0 1 4.044 3H7.58a.5.5 0 0 1 .498.45c.023.23.044.413.064.552A13.901 13.901 0 0 0 9.35 8.003c.095.2.033.439-.147.567l-2.158 1.542a13.047 13.047 0 0 0 6.844 6.844l1.54-2.154a.462.462 0 0 1 .573-.149 13.901 13.901 0 0 0 4 1.205c.139.02.322.042.55.064a.5.5 0 0 1 .449.498z"></path></svg></span><span class="text">'.get_theme_mod('topbar_contactphone').'</span></a></div>':'');
				echo ((!empty(get_theme_mod('topbar_contactemail')))?'<div class="contact-phone"><a href="mailto:'.get_theme_mod('topbar_contactemail').'"><span class="icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"></path><path d="M3 3h18a1 1 0 0 1 1 1v16a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1zm9.06 8.683L5.648 6.238 4.353 7.762l7.72 6.555 7.581-6.56-1.308-1.513-6.285 5.439z"></path></svg></span><span class="text">'.get_theme_mod('topbar_contactemail').'</span></a></div>':'');
			?>
		</div>

		<div class="nav-right">

		<div class="top-menu">
		<?php
			wp_nav_menu(
				array(
					'theme_location' => 'top-menu',
					'menu_id'        => 'primary-menu',
					'menu_class'        => 'primary-menu',
				)
			);
		?>
		</div>

		<div class="topmenu-toggle"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M3 4h18v2H3V4zm0 7h18v2H3v-2zm0 7h18v2H3v-2z"/></svg></div>

		<?php
		if(!empty(get_theme_mod('topbar_idlang')) && !empty(get_theme_mod('topbar_enlang'))){
		?>
		<div class="language-toggle">
		<div class="language-btn"><span class="icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M18.5 10l4.4 11h-2.155l-1.201-3h-4.09l-1.199 3h-2.154L16.5 10h2zM10 2v2h6v2h-1.968a18.222 18.222 0 0 1-3.62 6.301 14.864 14.864 0 0 0 2.336 1.707l-.751 1.878A17.015 17.015 0 0 1 9 13.725a16.676 16.676 0 0 1-6.201 3.548l-.536-1.929a14.7 14.7 0 0 0 5.327-3.042A18.078 18.078 0 0 1 4.767 8h2.24A16.032 16.032 0 0 0 9 10.877a16.165 16.165 0 0 0 2.91-4.876L2 6V4h6V2h2zm7.5 10.885L16.253 16h2.492L17.5 12.885z"/></svg></span><span class="title"><?php echo ((get_locale()=='en_US')?'English':'Indonesia');?></span></div>
		<ul class="list-language">
		<li class="lang-id"><a href="<?php echo get_theme_mod('topbar_idlang');?>">Indonesia</a></li>
		<li class="lang-en"><a href="<?php echo get_theme_mod('topbar_enlang');?>">English</a></li>
		</ul>
		</div>
		<?php
		}
		?>

		</div>
	</div>
	</div>
	<?php
	}
	?>
	
	<header id="masthead" class="site-header">
	<div class="container">
		<div class="site-branding">
			<?php
			the_custom_logo();
			if ( is_front_page() && is_home() ) :
				?>
				<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<?php
			else :
				?>
				<p class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
				<?php
			endif;
			$edunia_description = get_bloginfo( 'description', 'display' );
			if ( $edunia_description || is_customize_preview() ) :
				?>
				<p class="site-description"><?php echo $edunia_description; ?></p>
			<?php endif; ?>
		</div><!-- .site-branding -->

		<nav id="site-navigation" class="main-navigation">
			<?php
			wp_nav_menu(
				array(
					'theme_location' => 'primary-menu',
					'menu_id'        => 'primary-menu',
					'menu_class'        => 'primary-menu',
				)
			);
			?>
			
			<button class="primarymenu-toggle"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M3 4h18v2H3V4zm0 7h12v2H3v-2zm0 7h18v2H3v-2z"/></svg></button>

			<button class="search-toggle"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24"><path fill="none" d="M0 0h24v24H0z"/><path d="M18.031 16.617l4.283 4.282-1.415 1.415-4.282-4.283A8.96 8.96 0 0 1 11 20c-4.968 0-9-4.032-9-9s4.032-9 9-9 9 4.032 9 9a8.96 8.96 0 0 1-1.969 5.617zm-2.006-.742A6.977 6.977 0 0 0 18 11c0-3.868-3.133-7-7-7-3.868 0-7 3.132-7 7 0 3.867 3.132 7 7 7a6.977 6.977 0 0 0 4.875-1.975l.15-.15z"/></svg></button>
		</nav><!-- #site-navigation -->
	</div><!-- .container -->
	</header><!-- #masthead -->
</div><!-- .header-wrapper -->