<?php 
session_start();
global $post;
if(preg_match("/wp-admin\/customize/",$_SERVER["HTTP_REFERER"])):
$glob_postid="";
if(isset($_SESSION["postid"]) && $_SESSION["postid"]!=""):
	$glob_postid=$_SESSION["postid"];
endif;
else:
	if(is_front_page())
		$glob_postid=0;
	else
		$glob_postid=$post->ID;
endif;
