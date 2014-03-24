<?php get_header() ?>
<?php
$ql_widgets = get_option('ql_widgets');
$ql_footer = get_option('ql_footer');
$ql_layout = get_option('ql_layout');
$ql_social = get_option('ql_social');
$ql_content = get_option('ql_content');
$ql_emailsign = get_option('ql_emailsign');
$ql_title_tagline = get_option('ql_title_tagline');
?>
	
		<!-- Page Header -->
		<header>
		<?php if (strlen($ql_title_tagline['logo'] ) > 0 ){ ?>
			<div id="site-logo" class="">
		<!-- logo setting: <?php print_r($ql_title_tagline['logo']);
		echo 'strlen:';
		echo strlen($ql_title_tagline['logo']); ?> -->			
				<img src="<?php echo $ql_title_tagline['logo']; ?>" />
			</div>
		<?php } ?>
			<div id="site-title-and-desc" class="">
		<?php if ( strlen($ql_title_tagline['logo']) == 0  ){ ?>
		<!-- no logo -->
		<!-- logo setting: <?php print_r($ql_title_tagline['logo']);
		echo 'strlen:';
		echo strlen($ql_title_tagline['logo']); ?> -->
				<h1 id="site-title"><?php echo stripslashes(get_bloginfo('title')) ?></h1>
		<?php } ?>
				<h2 id="site-desc"><?php echo stripslashes(get_bloginfo('description')) ?></h2>
			</div>
		</header>
		<!-- End Page Header -->
		<div id="widgets" class="<?php echo $ql_widgets['wordpress']?'':'hidden' ?>" >
			<?php get_sidebar('top'); ?>
		</div>
		
		<?php if ( $ql_widgets['image'] || ! is_admin()): ?>
		<!-- Center Image -->
		<section id="center-image" class="<?php echo $ql_widgets['image']?'':'hidden' ?>" >
			<img src="<?php echo $ql_widgets['image'] ?>" style="width:100%;" />
		</section>
		<!-- End Center Image -->
		<?php endif; ?>
		
		
		<!-- Image slider -->
		<section id="image-slider" class="<?php echo $ql_widgets['slider']?'':'hidden'; ?>">
			<div id="coin-slider" class="mycoinslider">
				<ul class="slides">
				<?php if($ql_widgets['slider_image_1']): ?>
				<li><img id="slider-image-1" src="<?php echo $ql_widgets['slider_image_1'] ?>" style="width:100%;" /></li>
				<?php endif; ?>
				<?php if($ql_widgets['slider_image_2']): ?>
				<li><img id="slider-image-2" src="<?php echo $ql_widgets['slider_image_2'] ?>" style="width:100%;" /></li>
				<?php endif; ?>
				<?php if($ql_widgets['slider_image_3']): ?>
				<li><img id="slider-image-3" src="<?php echo $ql_widgets['slider_image_3'] ?>" style="width:100%;" /></li>
				<?php endif; ?>
				<?php if($ql_widgets['slider_image_4']): ?>
				<li><img id="slider-image-4" src="<?php echo $ql_widgets['slider_image_4'] ?>" style="width:100%;" /></li>
				<?php endif; ?>
				</ul>
			</div>
		</section>
			<?php if($ql_widgets['email'] || !is_admin()): ?>
			<div id="email" class="email-top <?php echo (($ql_widgets['email'] && !$ql_widgets['mailchimp']) && ($ql_emailsign['emailtipposition']=='belowslider' || $ql_emailsign['emailtipposition']=='both'))?'':'hidden'; ?>">
				<form action="" method="post" class="newsletter-form">
					<p>
						<input type="text" name="email" value="" placeholder="<?php echo $ql_emailsign['emailtiptext']?>" size="50" class="email"> 
						<input type="submit" name="submit" value="Submit" id="newsletter-submit" class="btn <?php echo $ql_widgets['email_submit_color']; ?>">
					</p>
				</form>
			</div>
			<div id="mailchimp" class="newsletter-form <?php echo ($ql_widgets['email'] && $ql_widgets['mailchimp'])?'':'hidden'; ?>">
				<?php the_widget('NS_Widget_MailChimp', array('signup_text'=>'Submit')); ?>
			</div>
			<?php endif; ?>
		<!-- End Image slider -->
		
		<?php
		if($ql_widgets['video'] || !is_admin()): 
			$urlParams = parse_url($ql_widgets['video']);
            if ( isset($urlParams['query']) ):
			parse_str($urlParams['query'], $youtubeParams);
		?>
		<!-- Video -->
		<section id="video" class="<?php echo $ql_widgets['video']?'':'hidden' ?>" >
			<object width="<?php echo $width = 460; ?>" height="<?php echo $height = floor($width * (3/4)); ?>">
			  <param name="movie"
					 value="http://www.youtube.com/v/<?php echo $youtubeParams['v']; ?>&version=3&autohide=1&showinfo=0"></param>
			  <param name="allowScriptAccess" value="always"></param>
			  <embed src="http://www.youtube.com/v/<?php echo $youtubeParams['v']; ?>&version=3&autohide=1&showinfo=0"
					 type="application/x-shockwave-flash"
					 allowscriptaccess="always"
					 width="<?php echo $width; ?>" height="<?php echo $height; ?>"></embed>
			</object>
		</section>
		<!-- End Video -->
		<?php endif; endif; ?>
		
		<!-- Main Content -->
		<section id="content">
		
			<div id="page-content"> 
				<?php echo apply_filters('the_content', stripslashes($ql_content['content']?$ql_content['content']:QL_CONTENT_CONTENT)) ?>
			</div>
			<?php if($ql_widgets['email'] || !is_admin()): ?>
			<div id="email" class="email-bottom <?php echo (($ql_widgets['email'] && !$ql_widgets['mailchimp']) && ($ql_emailsign['emailtipposition']=='bottom' || $ql_emailsign['emailtipposition']=='both'))?'':'hidden'; ?>">
				<form action="" method="post" class="newsletter-form">
					<p>
						<input type="text" name="email" value="" placeholder="<?php echo $ql_emailsign['emailtiptext']?>" size="50" class="email"> 
						<input type="submit" name="submit" value="Submit" id="newsletter-submit" class="btn <?php echo $ql_widgets['email_submit_color']; ?>">
					</p>
				</form>
			</div>
			<div id="mailchimp" class="newsletter-form <?php echo ($ql_widgets['email'] && $ql_widgets['mailchimp'])?'':'hidden'; ?>">
				<?php the_widget('NS_Widget_MailChimp', array('signup_text'=>'Submit')); ?>
			</div>
			<?php endif; ?>
		</section>
		<!-- End Main Content -->
		
		<!-- Footer -->
		<footer class="clearfix">
		
			<!-- Copyright -->
			<div id="copyright">
				<p><?php echo $ql_footer['content']?$ql_footer['content']:QL_FOOTER_CONTENT; ?></p>
			</div>
			<!-- End Copyright -->
			
			<!-- Footer Nav -->
			<!-- End Footer Nav -->
			
			<!-- Social Networks -->
			<nav id="social-networks">
				<ul>
					<li id="social-twitter" class="<?php echo $ql_social['twitter']?'':'hidden'; ?>">
						<a href="<?php echo $ql_social['twitter'] ?>" target="_blank">
							<img src="<?php bloginfo('template_url') ?>/images/twitter.png" alt="Twitter">
						</a>
					</li>
					<li id="social-facebook" class="<?php echo $ql_social['facebook']?'':'hidden'; ?>">
						<a href="<?php echo $ql_social['facebook'] ?>" target="_blank">
							<img src="<?php bloginfo('template_url') ?>/images/facebook.png" alt="Facebook">
						</a>
					</li>
					<li id="social-linkedin" class="<?php echo $ql_social['linkedin']?'':'hidden'; ?>">
						<a href="<?php echo $ql_social['linkedin'] ?>" target="_blank">
							<img src="<?php bloginfo('template_url') ?>/images/linkedin.png" alt="LinkedIn">
						</a>
					</li>
					<li id="social-googleplus" class="<?php echo $ql_social['googleplus']?'':'hidden'; ?>">
						<a href="<?php echo $ql_social['googleplus'] ?>" target="_blank">
							<img src="<?php bloginfo('template_url') ?>/images/googleplus.png" alt="Google+">
						</a>
					</li>
					<li id="social-youtube" class="<?php echo $ql_social['youtube']?'':'hidden'; ?>">
						<a href="<?php echo $ql_social['youtube'] ?>" target="_blank">
							<img src="<?php bloginfo('template_url') ?>/images/youtube.png" alt="YouTube">
						</a>
					</li>
				</ul>
			</nav>
			<!-- End Social Networks -->
			
		</footer>
		<!-- End Footer -->
		
<?php get_footer() ?>
