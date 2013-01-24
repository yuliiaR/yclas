

<?php 
	// get everything after last newline
	$text ="wlhdlf5cx0svut6nfjmk_200x200.jpg.html";
	
	$last = str_replace(".jpg", "", substr(strrchr($text, "_"), 1 ));
	echo $last;
?>
