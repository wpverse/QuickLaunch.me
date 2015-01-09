<?php
global $post;
if($_SESSION["postid"])
	$glob_postid=$_SESSION["postid"];
else
	$glob_postid=$post->ID;
$ql_widgets = get_option('ql_widgets_'.$glob_postid);
$ql_title_tagline = get_option('ql_title_tagline_'.$glob_postid);
?>

</div>
<!-- End inner wrapper -->
</div>
<!-- End Page Wrapper -->

<?php wp_footer() ?>
<?php if ( ql_is_personalizing() ): get_template_part('template-admin-palette'); endif; ?>
	<script src="http://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js"></script>
	<script type="text/javascript">
		var mycoin2="";

		<?php if($ql_title_tagline['font']): ?>
		WebFont.load({
			google: {
				families: ['<?php echo $ql_title_tagline['font']; ?>']
			},
			active:function(){
				var style = 'header h1{ font-family: "<?php echo $ql_title_tagline['font']; ?>"; }';

				var sc=document.createElement('style')
				sc.setAttribute("type","text/css");
				sc.innerHTML= style;
				document.getElementsByTagName("head")[0].appendChild(sc);
			}
		});
	<?php endif; ?>

	<?php if($ql_widgets['slider']): ?>
	jQuery(function(){
		jQuery("document").ready(function(){
			mycoin2=jQuery('#coin-slider ul.slides').clone();
			jQuery('#coin-slider').flexslider({
				animation: "slide",
			});
		});

	});
<?php endif; ?>
</script>
</body>
</html>
