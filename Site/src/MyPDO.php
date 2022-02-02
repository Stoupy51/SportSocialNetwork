<?php declare(strict_types=1);
require_once 'MyPDO.template.php';

MyPDO::setConfiguration(
	'mysql:host=127.0.0.1;dbname=social_media;charset=utf8',
	'ProjetS3',
	'MyFuckingProjetS3@!'
);
