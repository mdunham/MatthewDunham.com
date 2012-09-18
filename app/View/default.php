<?php

	/**
	 * MatthewDunham.com Primary View
	 * 
	 * This is the default view for the entire application.
	 * 
	 * @author Matthew Dunham <matt@matthewdunham.com>
	 * @copyright 2012 all rights reserved. 
	 */

?><!DOCTYPE html>
<html class="no-js" lang="en"> 
	<head>
		<meta charset="utf-8">

		<title><?php echo $title; ?></title>
		
		<meta name="keywords" content="matthew,dunham,web,site,development,coffeyville,kansas,php,javascript,cakephp,jquery,enterprise,application,engineer" />
		<meta name="description" content="Professional software developer born and raised in a small town. With over a decade of experience I believe in hard work and respect." />
		<meta name="copyright" content="2012 Matthew Dunham, all rights reserved." />
		<meta name="viewport" content="width=1024, user-scalable=no" />
		<meta http-equiv="X-UA-Compatible" content="IE=9" />
		
		<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:600|Noticia+Text:400italic,400' rel='stylesheet' type='text/css' />
		<link rel="stylesheet" href="<?php echo $this->url('/css/styles.css'); ?>" />
		<script src="<?php echo $this->url('/js/vendor/modernizr-2.6.2.min.js'); ?>"></script>
		
	</head>
	<body>
		<header>
			<h1><a href="http://www.matthewdunham.com/"><span>Mat</span>thew Dunham</a></h1>
			<nav><a href="http://linkedin.com/in/matthewdunham">LinkedIn</a><a href="<?php echo $this->url('/MatthewDunham.pdf'); ?>">Resume</a><a href="mailto:matt@matthewdunham.com">Contact</a></nav>
		</header>
		<section id="bio">
			<div class="face"></div>
			<h2>Software is my <span class="passionate">passion</span></h2>
			<article class="bio">
				Professional software developer born and raised in a small town. <br />
				With over a decade of experience I believe in hard work and respect. <br />
				A devoted husband and a father of two. <br />
				Enjoy hiking, camping, fishing, and just being outdoors. <br />
				Developing software has been my passion for almost a decade.
			</article>
			<div class="heart-container">
				<div class="left">I</div>
				<div class="heart"></div>
				<div class="right">PHP</div>
			</div>
		</section>
		
		<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
		<script>window.jQuery || document.write('<script src="<?php echo $this->url('/js/vendor/jquery-1.8.1.min.js'); ?>"><\/script>')</script>
		
		<script type="text/javascript">
			var URL = '<?php echo $this->url('/'); ?>';
		</script>
		
		<script type="text/javascript" src="<?php echo $this->url('/js/compiled.min.js'); ?>"></script>
		
		 <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <script>
            var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
        </script>
	</body>
</html>