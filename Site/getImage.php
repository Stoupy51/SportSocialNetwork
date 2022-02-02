<?php declare(strict_types=1);
require_once "autoload.php";
header('Content-Type: image');

$imgID = 0;
if (isset($_GET["id"]) && ctype_digit($_GET["id"]))
    $imgID = intval($_GET["id"]);

//Search image
$stmt = MyPDO::getInstance()->prepare(<<<SQL
    SELECT content
    FROM image
    WHERE idImage = :id
SQL);
$stmt->execute([':id'=>$imgID]);
if (($image = $stmt->fetch()) !== false) {
	$string = base64_decode($image["content"]);
	echo $string;
}
else
	throw new Exception("Aucune image correspondante");
