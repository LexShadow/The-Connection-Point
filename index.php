<?php
include('Parsedown.php');
include('ParsedownExtra.php');
include('ParsedownExtraFix.php');

$base_url = './'; // URL of the homepage. Can be absolute or relative.
$blog_title = 'The Connection Point';
$blog_sub_title = 'Random blog about random things';
$contact_email = 'noemail@nodomain.ltd';
$headder = '
<h3 class="pb-3 mb-4 font-italic border-bottom">
	Blogs
</h3>';
if(!empty($_GET['post'])){
	$post_name = filter_var($_GET['post'], FILTER_SANITIZE_NUMBER_INT);
	$file_path = __DIR__.'/content/'.$post_name.'.txt';
	if(file_exists($file_path)){
		$file = fopen($file_path, 'r');
		$post_title = trim(fgets($file),'#');
		fclose($file);
		// Process the Markdown
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
	}else{
		$content = '
			<h2>Not Found</h2>
			<p>Sorry, couldn\'t find a post with that name. Please try again, or go to the 
			<a href="' . $base_url . '">home page</a> to select a different post.</p>';
	}
	$contentfooter = '	<footer>
		This blog does not offer comment functionality. we are working on this.
	</footer>';
	$contentfooters = '	<footer>
		This blog does not offer comment functionality. If you\'d like to discuss any of the topics 
		written about here, you can <a href="mailto:' . $contact_email . '">send an email</a>.
	</footer>';	
} else {
	$content = "";
	$GrabThis = array_slice(scandir(__DIR__.'/content/', 1), 0, 6);
	foreach($GrabThis as $file) {
		if ($file[0] != '.') {
			if(!is_dir(__DIR__.'/content/' . $file)){
				
				$filename_no_ext = $file;
				$filename_no_extShow = str_replace(".txt", "", $file);
				if(strlen($filename_no_extShow) > 4){
					$file_path = __DIR__.'/content/' . $file;
					$files = fopen($file_path, 'r');
					//$post_title = trim(fgets($files),'#');
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
						<h2 class="blog-post-title"><a href="'.$base_url.'?post='.$filename_no_ext.'">'.$post_title.'</a></h2>
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
				}
			}
		}
	}
	$contentfooter = '';
}
?>
<!DOCTYPE html>
<html>
	<head>
		<title><?php if ( !empty($_GET['post']) ) { echo $post_title.' - '; } ?><?php echo $blog_title; ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="style.css?<?php echo rand(1000, 999999999); ?>" />
		<link rel="stylesheet" href="./css/bootstrap.css" />
		<link rel="stylesheet" href="./css/all.css" />
		<link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
		<link rel="manifest" href="/site.webmanifest">
		<link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
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
				<h1 class="display-4 font-italic"><a href="<?php echo $base_url; ?>"><?php echo $blog_title; ?></a></h1>
				<p class="lead my-3"><?php echo $blog_title; ?></p>
			</div>
		</div>	  
	</div>
    <main role="main" class="container">
      <div class="row">
        <div class="col-md-8 blog-main">
			<?php echo $headder;?>
			<?php echo $content;?>
			<?php echo $contentfooter ;?>
          <!-- nav class="blog-pagination">
            <a class="btn btn-outline-primary" href="#">Older</a>
            <a class="btn btn-outline-secondary disabled" href="#">Newer</a>
          </nav -->
        </div>

        <aside class="col-md-4 blog-sidebar">
          <div class="p-3 mb-3 bg-light rounded">
            <h4 class="font-italic">News</h4>
            <p class="mb-0">The Connection Point<br/> Version 0.0.0.2</p>
          </div>
          <div class="p-3">
            <h4 class="font-italic">Pages</h4>
            <ol class="list-unstyled mb-0">
              <li><a href="/">Blogs</a></li>
              <li><a href="?post=0000.txt">About Us</a></li>
              <li><a href="?post=0001.txt">Contact Us</a></li>
              <li><a href="?post=0002.txt">Terms Of Service</a></li>
            </ol>
          </div>
          <div class="p-3">
            <h4 class="font-italic">Archives</h4>
            <ol class="list-unstyled mb-0">
              <li><a href="#">Coming soon</a></li>
            </ol>
          </div>
          <!-- div class="p-3">
            <h4 class="font-italic">Our other sites</h4>
            <ol class="list-unstyled">
              <li><a href="#">Chat</a></li>
            </ol>
          </div -->
        </aside>
      </div>
    </main>
    <footer class="blog-footer">
      <p><i class="fas fa-copyright"></i> <?php echo $blog_title;?> 2020</p>
      <p>
        <a href="#">Back to top</a>
      </p>
    </footer>
	</body>
</html>