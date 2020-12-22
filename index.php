<?php

#Include the Core

if(!file_exists('./config/coresettings.php')){
	die('This script is missing it\'s core settings, please reinstall them`');
}else{
	include('./config/coresettings.php');
}

#Start a session for the recapture
if(!session_id()) session_start(); 
## Blog Header
$headder = print_header("Blogs");
$BlogPage = ((empty($_GET['page']) == true) ? 0 : (int)$_GET['page']);
if(!($BlogPage>0)) $BlogPage = 1;
$PageOffset = ($BlogPage-1) * TCP_SITE_BLOG_LIMIT;
$file_comment_path = '';
function filter($source_input) {
    return preg_replace(
        array(
            '/&lt;(\/?)(b|blockquote|br|em|i|ins|mark|q|strong|u)&gt;/i',
            '/&amp;([a-zA-Z]+|\#[0-9]+);/'
        ),
        array(
            '<$1$2>',
            '&$1;'
        ),
    $source_input);
}

if(!empty($_GET['post'])){
	if(!$stop){
		if(empty($_GET['sec']) || $_GET['sec'] == ''){
			$folder = "blogs";
		}else{
			if(is_dir(__DIR__.'/content/' . $_GET['sec'])){
				$folder = $_GET['sec'];
				$headder = print_header($folder);
			}else{
				$folder = 'blogs';
			}
		}
		$post_name = filter_var($_GET['post'], FILTER_SANITIZE_NUMBER_INT);
		$file_path = __DIR__.'/content/' . $folder . '/'.$post_name;
		$file_comment_path = __DIR__.'/content/comments/' . $post_name . '-com';
		if(file_exists($file_comment_path)){
			$pullComments_old = file_get_contents($file_comment_path);
		}else{
			$pullComments_old = array();
		}
		if($_SERVER['REQUEST_METHOD'] == 'POST') {
			$CommentUsername = "Anonymous";
			if(TCP_SITE_COMMENTS_HIDE){
				$url = "Hide";
			}else{
				$url = "View";
			}
			$message = "";
			$timestamp = date('U');
			$Locked = "";
			$error = '';
			
			#Grab Message
			if(isset($_POST['message']) && ! empty($_POST['message'])) {
				$message = preg_replace(
					array(
						'/[\n\r]{4,}/', // [1]
						'/\n/',
						'/[\r\t]/',
						'/ {2}/',
						'/ &nbsp;|&nbsp; /',
						'/<a (.*?)?href=(\'|\")(.*?)(\'|\")(.*?)?>(.*?)<\/a>/i'
					),
					array(
						'<br><br>',
						'<br>',
						'',
						'&nbsp;&nbsp;',
						'&nbsp;&nbsp;',
						'$6'
					),
				$_POST['message']);
				$message = htmlentities($message, ENT_QUOTES, 'UTF-8');
			}else{
				$error .= "<p class=\"message-error\">Missing Message</p>";
			}
			if(!isset($_POST['hello']) || empty($_POST['hello']) || $_POST['hello'] != $_SESSION['hello']) {
				$error .= "<p class=\"message-error\">Invalid Capture</p>";
			}
			if(strlen($message) > TCP_SITE_COMMENTS_MAXMESSAGELIMIT) $error .= "<p class=\"message-error\">" . TCP_SITE_COMMENTS_MAXMESSAGELIMIT . "</p>";
			if($error === "") {
				$pushmsg = $CommentUsername. "\n" . $url . "\n" . $message . "\n" . $timestamp . "\n" . $Locked;
				if(!empty($pullComments_old)) {
					 updatecomment($file_comment_path, $pushmsg . "\n\n{NEXTCOMMENT}\n" . $pullComments_old);
				}else{
					 updatecomment($file_comment_path, $pushmsg);
				}
			}
		}
		$FirstN = mt_rand(1, 50);
		$LastN = mt_rand(1, 50);
		if($FirstN - $LastN > 0){
			$_SESSION['hello'] = $FirstN - $LastN;
			$recap_number = $FirstN . ' - ' . $LastN;
		}else{
			$_SESSION['hello'] = $FirstN + $LastN;
			$recap_number = $FirstN . ' + ' . $LastN;
		}
		if(file_exists($file_path)){
			$parsedown = new ParsedownExtraFix();
			if($folder !== "pages"){
				if(!file_exists($file_comment_path)){
					$myfile = fopen($file_comment_path, 'w');
					fwrite($myfile, "\n");
					fclose($myfile);
					//$pullComments = $parsedown->text(file_get_contents($file_comment_path));
					$pullComments = file_get_contents($file_comment_path);
				}else{
					//$pullComments = $parsedown->text(file_get_contents($file_comment_path));
					$pullComments = file_get_contents($file_comment_path);
				}
			}else{
				define('TCP_SITE_COMMENTS', false);
				$pullComments = "";
			}
			$file = fopen($file_path, 'r');
			$post_title = trim(fgets($file),'#');
			fclose($file);
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
		if($pullComments == ""){
			$contentfooter = '	<footer>
				There is no comments at this time.
			</footer>';
		}else{
			$contentfooter = '';			
		}
		if(!TCP_SITE_COMMENTS && $folder == 'blogs'){
			$contentfooter = '';
			
			$contentfooterS = '	<footer>
				There is no comments at this time.
			</footer>';
		}else{
			$contentfooter = '';			
		}
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
		$pages = ceil(count($GrabThis)/TCP_SITE_BLOG_LIMIT);		
		$GrabThisFF = array_slice(array_reverse($GrabThis), -($filec-$PageOffset), TCP_SITE_BLOG_LIMIT);
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
						if(strlen($filename_no_extShow) > 8 && strlen($filename_no_extShow) < 16){
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
			
			if($content == ""){
				$content = '
				<h2>No Blogs Found</h2>
				<p>There isn\'t any blogs yet posted or you are end of the line, time to create some or go back.</p>';
			}
		}else{
			$content = '
			<h2>No Blogs Found</h2>
			<p>There isn\'t any blogs yet posted or you are end of the line, time to create some or go back.</p>';
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
$Cpage = isset($_GET['cpage']) ? $_GET['cpage'] : 1;
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
		<?php
			if(TCP_THEME_TOPNAV){
				echo TCP_THEME_TOPNAV_BODY;
			}
		?>
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
			<?php 
			$CommentPages = '';
			$CommentsShow = '';
			echo $headder;
			echo $content;
			echo $contentfooter ;
			if(TCP_SITE_COMMENTS && file_exists($file_comment_path)){
				if($pullComments !== ""){
					$pullComments = explode("\n{NEXTCOMMENT}\n", $pullComments);
					$CommentTotal = ceil(count($pullComments) / TCP_SITE_COMMENTS_LIMIT);
					if($CommentTotal > 1) {
						for($i = 0; $i < $CommentTotal; $i++) {
							if($Cpage == ($i + 1)) {
								$CommentPages .= " <span>" . ($i + 1) . "</span>";
							} else {
								$CommentPages .= ' <a href="?post=' . $post_name . '&amp;cpage=' . ($i + 1) . (isset($_GET['data']) ? '&amp;data=' . $database : '') . '">' . ($i + 1) . '</a>';
							}
						}
					}else{
						$CommentPages = '';
					}
					for($i = 0; $i < count($pullComments); $i++) {
						$Data = explode("\n", $pullComments[$i]);
						if($Data[0] !== ""){
							if(isset($_GET['raw']) && preg_match('/[0-9]+/', $_GET['raw'])) {
								if($Data[3] == $_GET['raw']) {
									// do something
								}
							}else{
								if($i <= (TCP_SITE_COMMENTS_LIMIT * $Cpage) - 1 && $i > (TCP_SITE_COMMENTS_LIMIT * ($Cpage - 1)) -1) {
									$DataIMG = 'ann.png';
									if($Data[4] == "Locked"){
										$Data[0] = "[Deleted]";
										$Data[2] = "This content has been removed.";
										$Data[3] = 	-11676095400;
										$DataIMG = 'del.png';
									}
									if($Data[4] == "Admin"){
										$Data[0] = "Master Clicker";
										$DataIMG = 'admin.png';
									}
									$CommentsShow .= '<div class="be-comment">';
									$CommentsShow .= '		<div class="be-img-comment">	
									<a href="#">
										<img src="./media/' . $DataIMG . '" alt="[=]" class="be-ava-comment">
									</a>
									</div>
									<div class="be-comment-content">
										<span class="be-comment-name">
											<a href="#">';
									$CommentsShow .= $Data[0];
									$CommentsShow .= '</a>
									</span>
									<span class="be-comment-time">
										<i class="fas fa-calendar-alt"></i> 
									';
									$CommentsShow .= '&nbsp;' .date('d/m/Y H:i', $Data[3]);
									$CommentsShow .= '</span>
									<p class="be-comment-text">';
									if($Data[1] == "View" || !TCP_SITE_COMMENTS_HIDE){
										$CommentsShow .= filter($Data[2]) . '<br>';
									}else{
										$CommentsShow .= 'Comment is awaiting approval<br>';
									}
									$CommentsShow .= '</p>';
									$CommentsShow .= '</div>';
									$CommentsShow .= '</div>';
								}
							}
						}
					}
				}else{
					$CommentPages = '';
				}
				
				if($CommentsShow == '' && $folder == "blogs"){
					$CommentsShow = '
					<footer>
						There is no comments at this time.
					</footer>';
				}
				
				echo '<div class="container">
					<div class="">
					<h1 class="comments-title">' . TCP_SITE_COMMENTS_TITLE. '</h1>' 
					. $CommentsShow .	
				'
				<div>' . $CommentPages . '</div>
				<form method="post" class="form-block">
				<div><input type="hidden" name="name" value="Anonymous"></div>
				<label>' . TCP_SITE_COMMENTS_TITLE. '</label>
				<div><textarea name="message" style="width: 100%; max-width: 100%;"></textarea></div>
				<hr>
				<div>
					'. $recap_number . ' = <input type="text" name="hello" autocomplete="off"> 
					<button type="submit">
						Comment
					</button>
					
				</div>
				<span class="clear"></span>
			  </form>	</div>
				</div>';
			}
			if($showpage){?>
			<nav class="blog-pagination">
				Pages(<?php echo $BlogPage . '/' .$pages?>) With <?php echo $filec; ?> Blogs.
				<a class="btn btn-outline-secondarya <?php echo ($BlogPage == 1 || $BlogPage < $pages)? "disabled": "";?>" href="?page=<?php echo ($BlogPage-1);?>">Newer</a>
				<a class="btn btn-outline-secondarya <?php echo ($BlogPage == $pages)? "disabled": "";?>" href="?page=<?php echo ($BlogPage+1);?>">Older</a>
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
              <!-- li><a href="?post=0004&sec=pages">Cheat Sheets</a></li -->
              <li><a href="https://github.com/LexShadow/The-Connection-Point" title="GitHub">GitHub</a></li>
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
              <li><a href="http://i2dny75d77eukohjtzax6tkbbd53lcsnb4g4wrzj3dbpmayxuv4osdqd.onion/">Onion v3 Version</a> - Tor Network</li>
              <li><a href="http://g2dv4s6shqictlln.onion/">Onion v2 Version</a> - Tor Network</li>
              <li><a href="https://lexshadow.cribcraft.co.uk/blog">Clearnet</a> - Normal Website</li>
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