<?php declare(strict_types=1);
require_once "autoload.php";

if(isset($_POST["recherche"])) {
    // SQL
    $ville = $_POST["recherche"];
    $recupUser = MyPDO::getInstance()->prepare(<<<SQL
        SELECT idUser
        FROM User
        WHERE ville = :ville
		AND idUser >= 0
SQL);

    $userVille->execute([":ville" => $ville]);
    $page = "";
    foreach($userVille->fetchAll() as $k){
        $UserSearch = User::createFromId($k['id']);
        $image = $UserSearch->getIdImage();
        $nom = $UserSearch->getName();
        $page .= <<<HTML
            <div class="flex-fill p-2 border mx-1 my-1" id="{$UserSearch->getIdUser() }">
                <img src="getImage.php?id=$image" width="70" height="70" title="$nom"> $nom
            </div>
HTML;
    }

echo $page;
}
