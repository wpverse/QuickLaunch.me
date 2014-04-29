<?php 

require_once("../../../wp-load.php");


echo apply_filters("the_content",stripslashes($_REQUEST["postcontent"]));
