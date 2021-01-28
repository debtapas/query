<?php
	$getParam = isset($_REQUEST['param'])?$_REQUEST['param']:'';

	if(!empty($getParam)){
		echo json_encode (array(
			"name"=>"Online web tutorial",
			"author"=>"Tapas Deb"
		));
	}