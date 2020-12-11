<?php

# Basic Setup
define('TCP_APP_ID', 'The Connection Point');
define('TCP_APP_VERSION', '0.0.0.5');

# Basic Site Information
define('TCP_SITE_BASE_URL', './');

# Shows in the top part of the site.
define('TCP_SITE_TITLE', TCP_APP_ID);
define('TCP_SITE_SUB_TITLE', 'Random blog about random things');

# Webmaster Information
define('TCP_SITE_WEBMASTER_NAME', 'Admin');
define('TCP_SITE_WEBMASTER_EMAIL', 'noemail@nodomain.ltd');

# News section on the right hand side
define('TCP_SITE_BRAND', false);
define('TCP_SITE_NEWS', true);
define('TCP_SITE_NEWS_TITLE', 'Version');
define('TCP_SITE_NEWS_BODY', TCP_APP_ID . ' <span style="color: #12d212;">' . TCP_APP_VERSION . '</span>');

# include files
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
$headder = '
<h3 class="pb-3 mb-4 font-italic border-bottom">
	Blogs
</h3>';
$BlogLimit = 8;
$BlogPage = ((empty($_GET['page']) == true) ? 0 : (int)$_GET['page']);
if(!($BlogPage>0)) $BlogPage = 1;
$PageOffset = ($BlogPage-1)*$BlogLimit;


if(!empty($_GET['post'])){
	if(!$stop){
		if(empty($_GET['sec']) || $_GET['sec'] == ''){
			$folder = "blogs";
		}else{
			if(is_dir(__DIR__.'/content/' . $_GET['sec'])){
				$folder = $_GET['sec'];
				
				$headder = '
			<h3 class="pb-3 mb-4 font-italic border-bottom">
				' . ucfirst($folder) . '
			</h3>';
			}else{
				$folder = 'blogs';
			}
		}
		$post_name = filter_var($_GET['post'], FILTER_SANITIZE_NUMBER_INT);
		$file_path = __DIR__.'/content/' . $folder . '/'.$post_name;

		if(file_exists($file_path)){
			$file = fopen($file_path, 'r');
			$post_title = trim(fgets($file),'#');
			fclose($file);
			$parsedown = new ParsedownExtraFix();
			$content = $parsedown->text(file_get_contents($file_path));
			$fav = substr($content, strpos($content, '{fas}')+5);
			$fav = substr($fav, 0, strpos($fav, '{/fas}'));
			while($fav != ""){
				$favplace = ' <i class="fas ' . $fav . '"></i> ';
				$content = str_replace("{fas}" . $fav . "{/fas}", $favplace, $content);
				$fav = substr($content, strpos($content, '{fas}')+5);
				$fav = substr($fav, 0, strpos($fav, '{/fas}'));
			}
			$fav = substr($content, strpos($content, '{fab}')+5);
			$fav = substr($fav, 0, strpos($fav, '{/fab}'));
			while($fav != ""){
				$favplace = ' <i class="fab ' . $fav . '"></i> ';
				$content = str_replace("{fab}" . $fav . "{/fab}", $favplace, $content);
				$fav = substr($content, strpos($content, '{fab}')+5);
				$fav = substr($fav, 0, strpos($fav, '{/fab}'));
			}
			$showpage = false;
		}else{
			$content = '
				<h2>Not Found</h2>
				<p>Sorry, couldn\'t find a post with that name. Please try again, or go to the 
				<a href="' . TCP_SITE_BASE_URL . '">home page</a> to select a different post.</p>';
			$showpage = false;
		}
		$contentfooter = '	<footer>
			This blog does not offer comment functionality. we are working on this.
		</footer>';
		$contentfooters = '	<footer>
			This blog does not offer comment functionality. If you\'d like to discuss any of the topics 
			written about here, you can <a href="mailto:' . TCP_SITE_WEBMASTER_EMAIL . '">send an email</a>.
		</footer>';
	}else{
		$content = '
		<h2>Not Found</h2>
		<p>Missing files needed, please check the config files for Parsedown, ParsedownExtra & ParsedownExtraFix.</p>';	
	}
}else{
	if(!$stop){
		$content = "";
		$GrabThis = array_slice(scandir(__DIR__.'/content/blogs'), 2);
		$filec = count($GrabThis);
		$pages = ceil(count($GrabThis)/$BlogLimit);		
		$GrabThisFF = array_reverse(array_slice($GrabThis, $PageOffset, $BlogLimit));
		if($BlogPage <= $pages){
			$showpage = true;
		}else{
			$showpage = false;
		}
		if($filec !== 0){
			foreach($GrabThisFF as $file){
				if($file[0] != '.'){
					if(!is_dir(__DIR__.'/content/blogs/' . $file)){
						$filename_no_ext = $file;
						$filename_no_extShow = str_replace(".txt", "", $file);
						if(strlen($filename_no_extShow) > 8){
							$file_path = __DIR__.'/content/blogs/' . $file;
							$files = fopen($file_path, 'r');
							$line = 0;
							while(($buffer = fgets($files)) !== FALSE){
								if($line == 0){
									$post_title = trim($buffer,'#');
								}
								if($line == 1){
									$post_auther = $buffer;
									break;
								}
								$line++;
							}
							fclose($files);
							$content .= '<!-- #Blog post -->
							<div class="blog-post">
								<h2 class="blog-post-title"><a href="' . TCP_SITE_BASE_URL . '?post='.$filename_no_ext.'">'.$post_title.'</a></h2>
								<p class="blog-post-meta">' . $post_auther . '</p>
							</div>';
							$fav = substr($content, strpos($content, '{fas}')+5);
							$fav = substr($fav, 0, strpos($fav, '{/fas}'));
							while($fav != ""){
								$favplace = ' <i class="fas ' . $fav . '"></i> ';
								$content = str_replace("{fas}" . $fav . "{/fas}", $favplace, $content);
								$fav = substr($content, strpos($content, '{fas}')+5);
								$fav = substr($fav, 0, strpos($fav, '{/fas}'));
							}
							$fav = substr($content, strpos($content, '{fab}')+5);
							$fav = substr($fav, 0, strpos($fav, '{/fab}'));
							while($fav != ""){
								$favplace = ' <i class="fab ' . $fav . '"></i> ';
								$content = str_replace("{fab}" . $fav . "{/fab}", $favplace, $content);
								$fav = substr($content, strpos($content, '{fab}')+5);
								$fav = substr($fav, 0, strpos($fav, '{/fab}'));
							}
						}
					}
				}
			}
		}else{
		$content = '
			<h2>No Blogs found</h2>
			<p>There isn\'t any blogs yet posted, time to create some.</p>';
		}
	}else{
		$content = '
		<h2>Not Found</h2>
		<p>Missing files needed, please check the config files for Parsedown, ParsedownExtra & ParsedownExtraFix.</p>';	
	}
	$contentfooter = '';
}

if(TCP_SITE_BRAND){
	$CopyRightShow = '<p><i class="fas fa-copyright"></i> ' . TCP_SITE_TITLE . ' 2020, Powed By The Connection Point(<a href="https://github.com/LexShadow/The-Connection-Point">GitHub</a>)</p>';
}else{
	$CopyRightShow = '<p><i class="fas fa-copyright"></i> '. TCP_SITE_TITLE . ' 2020</p>';	
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php echo TCP_SITE_TITLE; ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="./css/style.css?<?php echo rand(1000, 999999999); ?>" />
		<link rel="stylesheet" href="./css/bootstrap.css" />
		<link rel="stylesheet" href="./fontawesome/css/all.css" />
		<link rel="apple-touch-icon" sizes="72x72" href="./favicon/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="./favicon/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="./favicon/favicon-16x16.png">
		<link rel="manifest" href="./favicon/site.webmanifest">
		<link rel="mask-icon" href="./favicon/safari-pinned-tab.svg" color="#5bbad5">
		<meta name="msapplication-TileColor" content="#da532c">
		<meta name="theme-color" content="#ffffff">
	</head>
	<body>
		<div class="container">
			<div class="nav-scroller py-1 mb-2">
				<nav class="nav d-flex justify-content-between">
					<a class="p-2 text-muted" href="#">~</a>
					<a class="p-2 text-muted" href="#">~</a>
					<a class="p-2 text-muted" href="#">~</a>
					<a class="p-2 text-muted" href="#">~</a>
					<a class="p-2 text-muted" href="#">~</a>
					<a class="p-2 text-muted" href="#">~</a>
					<a class="p-2 text-muted" href="#">~</a>
					<a class="p-2 text-muted" href="#">~</a>
					<a class="p-2 text-muted" href="#">~</a>
					<a class="p-2 text-muted" href="#">~</a>
					<a class="p-2 text-muted" href="#">~</a>
					<a class="p-2 text-muted" href="#">~</a>
				</nav>
			</div>
		<hr />
		<div class="jumbotron p-3 p-md-5 text-white rounded bg-dark">
			<div class="col-md-6 px-0">
				<h1 class="display-4 font-italic"><a href="<?php echo TCP_SITE_BASE_URL; ?>"><?php echo TCP_SITE_TITLE; ?></a></h1>
				<p class="lead my-3"><?php echo TCP_SITE_SUB_TITLE; ?></p>
			</div>
		</div>	  
	</div>
    <main role="main" class="container">
		<div class="row">
		<div class="col-md-8 blog-main">
			<?php echo $headder;?>
			<?php echo $content;?>
			<?php echo $contentfooter ;?>
			<?php if($showpage){?>
			<nav class="blog-pagination">
				Pages(<?php echo $BlogPage . '/' .$pages?>) With <?php echo $filec; ?> Blogs.
				<a class="btn btn-outline-primary <?php echo ($BlogPage == 1 || $BlogPage < $pages)? "disabled": "";?>" href="?page=<?php echo ($BlogPage-1);?>">Older</a>
				<a class="btn btn-outline-secondarya <?php echo ($BlogPage == $pages)? "disabled": "";?>" href="?page=<?php echo ($BlogPage+1);?>">Newer</a>
			</nav>
			<?php } ?>
		</div>

        <aside class="col-md-4 blog-sidebar">
		<?php if(TCP_SITE_NEWS){ ?>
				<div class="p-3 mb-3 bg-light rounded">
					<h4 class="font-italic"><?php echo TCP_SITE_NEWS_TITLE; ?></h4>
					<p class="mb-0"><?php echo TCP_SITE_NEWS_BODY; ?></p>
			</div>
		<?php } ?>
          <div class="p-3">
            <h4 class="font-italic">Pages</h4>
            <ol class="list-unstyled mb-0">
              <li><a href="<?php echo TCP_SITE_BASE_URL;  ?>">Blogs</a></li>
              <li><a href="?post=0000&sec=pages">About Us</a></li>
              <li><a href="?post=0001&sec=pages">Contact Us</a></li>
              <li><a href="?post=0002&sec=pages">Terms Of Service</a></li>
              <li><a href="?post=0003&sec=pages">Site Settings</a></li>
              <li><a href="?post=0004&sec=pages">Cheat Sheets</a></li>
            </ol>
          </div>
          <div class="p-3">
            <h4 class="font-italic">Archives</h4>
            <ol class="list-unstyled mb-0">
              <li><a href="#">Coming soon</a></li>
            </ol>
          </div>
          <div class="p-3">
            <h4 class="font-italic">Our other sites</h4>
            <ol class="list-unstyled">
              <li><a href="#">None</a></li>
            </ol>
          </div>
        </aside>
      </div>
    </main>
    <footer class="blog-footer">
      <?php echo $CopyRightShow; ?>
      <p>
        <a href="#">Back to top</a>
      </p>
    </footer>
	</body>
</html>