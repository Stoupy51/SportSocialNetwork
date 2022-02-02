<?php declare(strict_types = 1);
require_once "autoload.php";

$authentication = new SecureUserAuthentication();
if (!$authentication->isUserConnected()) {
	http_response_code(401);
    header("Location: connexion.php");
    die();
}
$user = $authentication->getUser();
if ($user->getRole() != 2) {
	http_response_code(403);
    header("Location: .");
    die();
}

$stmt = MyPDO::getInstance()->prepare(<<<SQL
SELECT e.idPublication, idImage, idUser, dateDePublication, message, isEvent, idEvenement, nbParticipantsMax, nom, lieu, DATE_FORMAT(dateEvenement,'%d/%m/%Y à %hh%i') as "dateEvenement", typeSport
FROM Evenement e, Publication p
WHERE e.idPublication = p.idPublication
AND dateEvenement > STR_TO_DATE(:startingDate,'%Y-%m-%d')
AND dateEvenement < STR_TO_DATE(:endingDate,'%Y-%m-%d')
AND idUser >= 0
SQL);
$stmt->execute([":startingDate" => $_GET['startingDate'],":endingDate" => $_GET['endingDate']]);
$activities = $stmt->fetchAll();
	
$posts = "";
foreach ($activities as $k) {
	$post = new Evenement($k);
	$posts .= $post->generateHtmlPost();
}
echo $posts.'<div class="post-user">Plus aucune publication</div>';
