<?php declare(strict_types=1);

Class SportUtilities {
	public static function getHeaderPage(User $user) : string {
		$search = "";
		if (isset($_GET["search"]))
			$search = $_GET["search"];	
		$idImage = $user->getIdImage();
		return <<<HTML
<input hidden id="hiddenUser" value="{$user->getName()}">
<input hidden id="hiddenIdUser" value="{$user->getIdUser()}">
<input hidden id="hiddenIdImage" value="{$user->getIdImage()}">
<script type="text/javascript" src="js/search.js"></script>
<div class="d-flex flex-row flex-grow-1 justify-content-center mb-4 p-2 main-bar sticky-top">
	<a href="." class="header-title link">
		<h4 style="color: #ff6b00;">GAKALL <span class="header-span">Sport</span> </h4>
	</a>
	<form method="GET" id="formSearch" style="display:inline; margin-bottom: -5px;">
		<input name="search" placeholder="Search" type="text" id="header-searchbar" class="searchbar" value="$search">
	</form>
	<svg id="newPostIcon" class="header-svg-search" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 1000 1000" enable-background="new 0 0 1000 1000" xml:space="preserve" width="30px" height="30px">
		<g><path d="M984.1,122.3C946.5,84.5,911.4,42.1,867.8,11c-40-8.3-59.2,34.9-86.7,55.1c46.4,53.9,100.5,101.5,150.4,152.5C954.1,191.7,1007.7,164.1,984.1,122.3z M959.3,325.9c-31.7,31.8-64.5,62.8-95.1,95.8c-0.8,127.5,0.3,255-0.4,382.6c-0.6,47-41.8,88.7-88.8,90.3c-193.4,0.8-387,0.8-580.4,0.1c-52.2-1.4-94-51.4-89.9-102.7c-0.1-184.6-0.1-369.1,0-553.5c-4-51.1,38-100.3,89.6-102.1c128.1-1.7,256.3,0.1,384.3-0.9c33.2-30,63.9-62.9,95.7-94.5c-170.6,0-341-0.9-511.6,0.5c-79.6,1.4-151,71-152.4,151C10.1,407.7,9.5,622.8,10.7,838c0.3,77.5,66.1,144.7,142.4,152h670.2c72.3-12.7,134.3-75.8,135.2-150.9C960.7,668.1,959,496.9,959.3,325.9z M908.2,242.2C858,191.7,807.4,141.5,756.6,91.5C645.4,201.9,534,312,423.4,423c50.1,50.4,100.4,100.6,151.3,150.3C686,463.1,797.2,352.6,908.2,242.2z M341.2,654.6c68.1-18.5,104.4-30.2,172.5-48.5c18.2-5.8,30.3-9.3,39.7-13c-48.2-45.9-103.6-102.5-151.7-148.8C381.4,514.4,361.4,584.5,341.2,654.6z"></path></g>
	</svg>
	<a href="map.php" class="d-flex align-items-center px-2">
		<img src="img/icons/map.png" id="searchIcon" class="header-svg-search" height="30">
	</a>
	<!--<svg width="42" height="24" viewBox="0 0 42 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="header-svg-user">
		<path d="M14 9.5H8.75V4.25H5.25V9.5H0V13H5.25V18.25H8.75V13H14V9.5ZM31.5 11.25C34.405 11.25 36.7325 8.905 36.7325 6C36.7325 3.095 34.405 0.75 31.5 0.75C30.94 0.75 30.3975 0.8375 29.9075 0.995C30.905 2.4125 31.4825 4.1275 31.4825 6C31.4825 7.8725 30.8875 9.57 29.9075 11.005C30.3975 11.1625 30.94 11.25 31.5 11.25ZM22.75 11.25C25.655 11.25 27.9825 8.905 27.9825 6C27.9825 3.095 25.655 0.75 22.75 0.75C19.845 0.75 17.5 3.095 17.5 6C17.5 8.905 19.845 11.25 22.75 11.25ZM34.335 15.03C35.7875 16.3075 36.75 17.935 36.75 20V23.5H42V20C42 17.305 37.8525 15.6425 34.335 15.03ZM22.75 14.75C19.25 14.75 12.25 16.5 12.25 20V23.5H33.25V20C33.25 16.5 26.25 14.75 22.75 14.75Z" fill="black" />
	</svg>-->
	<a href='message.php' style="margin-left:-6px; margin-right:-6px;"><svg width="44" height="44" viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" class="header-svg-messages">
		<g filter="url(#filter0_d)">
			<rect x="4" y="5" width="32" height="31" fill="url(#pattern0)" shape-rendering="crispEdges" />
		</g>
		<rect x="29" y="29" width="15" height="15" fill="url(#pattern1)" />
		<defs>
			<filter id="filter0_d" x="0" y="5" width="40" height="39" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
				<feFlood flood-opacity="0" result="BackgroundImageFix" />
				<feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha" />
				<feOffset dy="4" />
				<feGaussianBlur stdDeviation="2" />
				<feComposite in2="hardAlpha" operator="out" />
				<feColorMatrix type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0" />
				<feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow" />
				<feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow" result="shape" />
			</filter>
			<pattern id="pattern0" patternContentUnits="objectBoundingBox" width="1" height="1">
				<use xlink:href="#image0" transform="translate(0.015625) scale(0.0322917 0.0333333)" />
			</pattern>
			<pattern id="pattern1" patternContentUnits="objectBoundingBox" width="1" height="1">
				<use xlink:href="#image1" transform="scale(0.0333333)" />
			</pattern>
			<image id="image0" width="30" height="30" xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAAAxElEQVRIie3WMWpCURBG4Q8FSRrtbC2SPhvICmzchVuwtXQLbsE2pVUIkjqQHVgqNmIj6EvxGHhFQAIvcxt/OPVhhrlzh3sKZ4MvTLLFVYNPvJYQB294LiGucMYSw2xxcMQcj9niYIsputni4BvjEuJgjZcS4goXrDDKFgcnLDDIFgd7zNDLFgfvTUHnL23ISJuV7iS3Ooarn1VxkeeUvkDSV2b6J3FQT+pDW8Jb4vRD4Kqe1Kf/Ev4mTj32PhQ6b+9pPT+XHgysHrPM6QAAAABJRU5ErkJggg==" />
			<image id="image1" width="30" height="30" xlink:href="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAAAeCAYAAAA7MK6iAAAABmJLR0QA/wD/AP+gvaeTAAABVklEQVRIie3Uv04bQRDH8U9IhJCQKKiQ8ANYCrxDJNpQkQ6JAjroaPIKFJTpSKrQpQsVFS0lFKGCFjAPwB/x5ygYk/XZvjPm3Fj+SaPbm5md787e3jLSSMOsGRwhG5CdoJaHLsRzGocDgB5G7ZQF7vAtxpPYrxB6gKmovYibFJzhAavxPo4/FUD/YiJqLuM+/C3gDE/YDN9H/HwH9Dc+Ra0NPCaxNnDTtsL/Adt9QH9gLGp87xDvCu5lcjfrZdGF4Ay7yXat57Yrb/nP9KsgtxScYSfJW/H/gKR2H7GmdkpqloKvMK9VX3Gd5NxiKZdTx3m/4AbmIj7n5ddoXgJfcIbTGIvYHj4ncxpvBaed1nER/n+Y1a4ZHHeZ26nzjuB8p/lVX3q5aGphax1yGiWdt4G7ddqPFXXeAq4SWgZ/1SCgRfBXFX3TKix/blpUdadFnY800hDqGSg1f1dR0xmAAAAAAElFTkSuQmCC" />
		</defs>
	</svg>
	</a>
	<a href="achat.php" class="d-flex align-items-center mx-3">
		<img height="32" src="img/icons/panier.png">
	</a>
	<a href="profil.php" class="d-flex flex-column header-user-item">
		<img src="getImage.php?id=$idImage" class="header-img" alt="">
	</a>
	<a href="connexion.php?disconnect" class="d-flex align-items-center mx-3">
		<img height="32" src="img/icons/disconnect.png">
	</a>
</div>
HTML;
	}

	public static function getPublications(string $search = "", string $searchIdUser = "") : string {
		if ($searchIdUser != "")
			$searchIdUser = "AND u.idUser = $searchIdUser";
		else
			$searchIdUser = "";
		$stmtEvenement = MyPDO::getInstance()->prepare(<<<SQL
SELECT e.idPublication, idImage, idUser, DATE_FORMAT(dateDePublication,"%d/%m/%Y à %Hh%i") as "dateDePublication", message, isEvent, idEvenement, nbParticipantsMax, nom, lieu, DATE_FORMAT(dateEvenement,'%d/%m/%Y à %Hh%i') as "dateEvenement", typeSport
FROM Evenement e, Publication p
WHERE e.idPublication = p.idPublication
AND e.idPublication = :id
AND idUser >= 0

SQL);
		$stmt = MyPDO::getInstance()->prepare(<<<SQL
SELECT idPublication, p.idImage, p.idUser, DATE_FORMAT(dateDePublication,"%d/%m/%Y à %Hh%i") as "dateDePublication", message, isEvent
FROM Publication p, User u
WHERE u.idUser = p.idUser
AND u.idUser >= 0
$searchIdUser
AND (
	message LIKE :search
	OR login LIKE :search
	OR lastName LIKE :search
	OR firstName LIKE :search
	OR CONCAT(firstName," ",lastName) LIKE :search
	OR idPublication IN(SELECT idPublication
						FROM Evenement
						WHERE nom LIKE :search
						OR lieu LIKE :search
						OR typesport LIKE :search)
)
ORDER BY idPublication DESC
LIMIT 100
SQL);
		$stmt->execute([":search" => "%".$search."%"]);
		$stmt = $stmt->fetchAll();

		$posts = "";
		foreach ($stmt as $k) {
			$id = $k["idPublication"];
			if ($k["isEvent"] == true) {
				$stmtEvenement->execute([':id'=>$id]);
				if (($line = $stmtEvenement->fetch()) !== false)
					$post = new Evenement($line);
				else
					throw new Exception("Aucun événement correspondant");
			}
			else
				$post = new Publication($k);
			$posts .= $post->generateHtmlPost();
		}
		return $posts;
	}

	public static function getAnnonce(string $search = "", string $searchIdUser = "") : string {
		if ($searchIdUser != "")
			$searchIdUser = "AND u.idUser = $searchIdUser";
		else
			$searchIdUser = "";
		$stmt = MyPDO::getInstance()->prepare(<<<SQL
SELECT idAnnonce, a.idUser, a.idImage, titre, description, DATE_FORMAT(dateAnnonce,"%d/%m/%Y à %Hh%i") as "dateAnnonce"
FROM Annonce a, User u
WHERE u.idUser = a.idUser
AND u.idUser >= 0
$searchIdUser
AND (
	login LIKE :search
	OR lastName LIKE :search
	OR firstName LIKE :search
	OR CONCAT(firstName," ",lastName) LIKE :search
)
ORDER BY idAnnonce DESC
LIMIT 100
SQL);
		$stmt->execute([":search" => "%".$search."%"]);
		$stmt = $stmt->fetchAll();

		$posts = "";
		foreach ($stmt as $k) {
			$post = new Annonce($k);
			$posts .= $post->generateHtmlPost();
		}
		return $posts;
	}

	public static function getUsers(string $search = "") : string {
		$stmt = MyPDO::getInstance()->prepare(<<<SQL
SELECT login, idImage, lastName, firstName FROM User
WHERE (login LIKE :search
OR lastName LIKE :search
OR firstName LIKE :search
OR CONCAT(firstName," ",lastName) LIKE :search
OR ville LIKE :search)
AND idUser >= 0


SQL);
		$stmt->execute([":search" => "%".$search."%"]);
		$stmt = $stmt->fetchAll();
		if (!isset($stmt[0]))
			return "";
		$posts = "";
		foreach ($stmt as $k) {
			$idImage = $k["idImage"];
			$login = $k["login"];
			$name = $k["firstName"]." ".$k["lastName"];
			$posts .= <<<HTML
	<a class="d-flex flex-column scroll-item m-3" href="profil.php?user=$login">
		<img src="getImage.php?id=$idImage" style="height:100px; width:100px;" class="mx-auto">
		<div class="text-center">$name</div>
	</a>
HTML;
		}
		return <<<HTML
<div class="d-flex flex-row mb-5 w-100 align-self-center justify-content-between">
$posts
</div>
HTML;
	}

	public static function getUserFriends(int|string $idUser) : string {
		$stmt = MyPDO::getInstance()->prepare(<<<SQL
	SELECT login, idImage, lastName, firstName
	FROM User u, avoir_en_ami a
	WHERE a.idUser = :id
	AND a.use_idUser = u.idUser
	AND a.use_idUser >= 0
	SQL);
		$stmt->execute([":id" => intval($idUser)]);
		$stmt = $stmt->fetchAll();
		if (!isset($stmt[0]))
			return "";
		$friends = "";
		foreach ($stmt as $k) {
			$idImage = $k["idImage"];
			$login = $k["login"];
			$name = $k["firstName"]." ".$k["lastName"];
			$friends .= <<<HTML
	<a class="d-flex flex-column scroll-item profile m-3" href="profil.php?user=$login">
		<img src="getImage.php?id=$idImage" style="height:96px; width:96px;">
		<div class="text-center">$name</div>
	</a>
	HTML;
		}
		return <<<HTML
	<div class="d-flex flex-row mb-5 w-100 align-self-center justify-content-center">
	$friends
	</div>
	HTML;
	}

	public static function getUserPublications(int|string $idUser) : string {
		$stmtEvenement = MyPDO::getInstance()->prepare(<<<SQL
	SELECT e.idPublication, idImage, idUser, DATE_FORMAT(dateDePublication,"%d/%m/%Y à %Hh%i") as "dateDePublication", message, isEvent, idEvenement, nbParticipantsMax, nom, lieu, DATE_FORMAT(dateEvenement,"%d/%m/%Y à %Hh%i") as "dateEvenement", typeSport
	FROM Evenement e, Publication p
	WHERE e.idPublication = p.idPublication
	AND e.idPublication = :id
	SQL);
		$stmt = MyPDO::getInstance()->prepare(<<<SQL
	SELECT idPublication, p.idImage, p.idUser, DATE_FORMAT(dateDePublication,"%d/%m/%Y à %Hh%i") as "dateDePublication", message, isEvent
	FROM Publication p, User u
	WHERE u.idUser = p.idUser
	AND u.idUser = :id
	ORDER BY idPublication DESC
	LIMIT 15
	SQL);
		$stmt->execute([":id" => intval($idUser)]);
		$stmt = $stmt->fetchAll();
		
		$posts = "";
		foreach ($stmt as $k) {
			$id = $k["idPublication"];
			if ($k["isEvent"] == true) {
				$stmtEvenement->execute([':id'=>$id]);
				if (($line = $stmtEvenement->fetch()) !== false)
					$post = new Evenement($line);
				else
					throw new Exception("Aucun événement correspondant");
			}
			else
				$post = new Publication($k);
			$posts .= $post->generateHtmlPost();
		}
		return $posts;
	}

	public static function getUserVideos(int|string $idUser) : string {
		$stmt = MyPDO::getInstance()->prepare(<<<SQL
			SELECT lien, titreVideo, DATE_FORMAT(dateVideo,"%d/%m/%Y à %Hh%i") as "date"
			FROM Video p, User u
			WHERE u.idUser = p.idUser
			AND u.idUser = :id
			ORDER BY idVideo DESC
			LIMIT 15
SQL);
		$stmt->execute([":id" => intval($idUser)]);
		$stmt = $stmt->fetchAll();
		
		$videos = '<div id="videos-link" class="d-flex flex-column justify-content-center">';
		foreach ($stmt as $vids) {
			$videos .= <<<HTML
	<div style="margin-left: auto; margin-right: auto;">
		<div class="d-flex justify-content-between">
			<p>{$vids["titreVideo"]}</p>
			<p>{$vids["date"]}</p>
		</div>
		<iframe width="560" height="315" src="{$vids['lien']}" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
	</div>
HTML;
		}
		$videos .= '</div>';
		return $videos;
	}
}
