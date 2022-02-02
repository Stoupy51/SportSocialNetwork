<?php declare(strict_types=1);

spl_autoload_register(function($class_name) {
	$file = "src/".$class_name.".php";
	if (file_exists($file))
		include $file;
});
