<?php
$ql_layout = get_option('ql_layout');
?>
<!DOCTYPE html>
<html lang="<?php bloginfo('language') ?>">
<head>

	<meta charset="<?php bloginfo('charset') ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<<<<<<< HEAD
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
=======
>>>>>>> c1c43ac0780ad5ad743fee4203992e6359649afe
	<title><?php bloginfo('title') ?></title>
	
	<link rel="stylesheet" href="<?php bloginfo('stylesheet_url') ?>">
	<link rel="stylesheet" href="<?php bloginfo('template_url') ?>/css/flexslider.css">

	<!--[if lt IE 9]>
	<script src="<?php bloginfo('template_url') ?>/js/html5.js"></script>
	<![endif]-->
	
	<?php wp_head() ?>

</head>
<body>

	<!-- Page Wrapper -->
	<div id="wrap" class="pos-<?php echo $ql_layout['position']?$ql_layout['position']:'center'; ?>" >
		<div id="wrap-inner">
	
