<?php
# Basic Setup
define('TCP_APP_ID', 'The Connection Point');
define('TCP_APP_VERSION', '0.0.0.7');

# Basic Site Information
define('TCP_SITE_BASE_URL', './');

# Shows in the top part of the site.
define('TCP_SITE_TITLE', TCP_APP_ID);
define('TCP_SITE_SUB_TITLE', 'Random blog about random things');

# Webmaster Information
define('TCP_SITE_WEBMASTER_NAME', 'Master Clicker');
define('TCP_SITE_WEBMASTER_EMAIL', 'admin@anonymous.netweb');

# News section on the right hand side
define('TCP_SITE_BRAND', true);
define('TCP_SITE_NEWS', true);
define('TCP_SITE_NEWS_TITLE', 'Version');
define('TCP_SITE_NEWS_BODY', TCP_APP_ID . ' <span style="color: #12d212;">' . TCP_APP_VERSION . '</span>');
define('TCP_SITE_COMMENTS', true, true);
define('TCP_SITE_COMMENTS_TITLE', 'Comment');
define('TCP_SITE_COMMENTS_MAXMESSAGELIMIT', 1000);
define('TCP_SITE_COMMENTS_HIDE', true);

define('TCP_THEME_TOPNAV', true);
define('TCP_THEME_TOPNAV_BODY', '			<div class="nav-scroller py-1 mb-2">
				<nav class="nav d-flex justify-content-between">
					<a class="p-2 text-muted" href="http://i2dny75d77eukohjtzax6tkbbd53lcsnb4g4wrzj3dbpmayxuv4osdqd.onion/">Onion v3</a>
					<a class="p-2 text-muted" href="http://g2dv4s6shqictlln.onion/">Onion v2</a>
					<a class="p-2 text-muted" href="https://lexshadow.cribcraft.co.uk/blog">Clearnet</a>
					<a class="p-2 text-muted" href="https://lexshadow.cribcraft.co.uk/blog/">Blogs</a>
					<a class="p-2 text-muted" href="https://lexshadow.cribcraft.co.uk/blog/?post=0000&sec=pages">About Us</a>
					<!-- a class="p-2 text-muted" href="#">~</a>
					<a class="p-2 text-muted" href="#">~</a -->
					<a class="p-2 text-muted" href="https://lexshadow.cribcraft.co.uk/blog/?post=0001&sec=pages">Contact Us</a>
					<a class="p-2 text-muted" href="https://lexshadow.cribcraft.co.uk/blog/?post=0003&sec=pages">Terms Of Service</a>
					<a class="p-2 text-muted" href="?post=0003&sec=pages">Site Settings</a>
					<!-- a class="p-2 text-muted" href="?post=0004&sec=pages">Cheat Sheets</a -->
					<a class="p-2 text-muted" href="https://github.com/LexShadow/The-Connection-Point">Github</a>
				</nav>
			</div>
		<hr />');

# Blog and Comments limits
define('TCP_SITE_BLOG_LIMIT', 8);
define('TCP_SITE_COMMENTS_LIMIT', 5);

if(file_exists('./config/Parsedown.php')){
	$stop = false;
	include('./config/Parsedown.php');
}else{
	$stop = true;
}
if(file_exists('./config/ParsedownExtra.php')){
	$stop = false;
	include('./config/ParsedownExtra.php');
}else{
	$stop = true;
}
if(file_exists('./config/ParsedownExtraFix.php')){
	$stop = false;
	include('./config/ParsedownExtraFix.php');
}else{
	$stop = true;
}


# Stuff
function print_header($s_data){
	$h_return = '
		<h3 class="pb-3 mb-4 font-italic border-bottom">
			' . ucfirst($s_data) . '
		</h3>';

	return $h_return;
}

function updatecomment($f_path, $d_save) {
	$io_open = fopen($f_path, 'w') or die('Cannot open file: ' . $f_path);
	fwrite($io_open, $d_save);
}

?>