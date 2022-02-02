<?php declare(strict_types=1);

Class Evenement extends Publication {
	private int $idEvenement;
	private int $nbParticipantsMax;
	private int $typeEvenement;
	private string $nom;
	private string $lieu;
	private string $dateEvenement;

	public function __construct(array $data) {
		Parent::__construct($data);
		$this->idEvenement			= intval($data['idEvenement']) ?? 0;
		$this->nbParticipantsMax	= intval($data['nbParticipantsMax']) ?? 0;
		$this->typeSport			= intval($data['typeSport']) ?? 0;
		$this->nom					= $data['nom'] ?? "";
		$this->lieu					= $data['lieu'] ?? "";
		$this->dateEvenement		= $data['dateEvenement'] ?? "";
	}

	public function generateHtmlPost() : string {
		$user = User::createFromId($this->idUser);
		$idAvatar = $user->getIdImage();
		$firstName = $user->getFirstName();
		$lastName = $user->getLastName();
		$login = $user->getLogin();
		$image = "";
		if ($this->idImage != NULL)
			$image = <<<HTML
			<img class="card-img-bottom" src="getImage.php?id={$this->idImage}">
HTML;
		$message = "";
		if (preg_replace("/ /i","",$this->message) != "")
			$message = <<<HTML
			<p class="post-message ml-3 mt-2">{$this->message}</p>
HTML;
		$stmt = MyPDO::getInstance()->prepare(<<<SQL
	SELECT COUNT(idUser) as "Count"
	FROM Identifier2
	WHERE idEvenement = :id
	AND idUser >= 0
	AND demandeAccept = 1
SQL);
		$stmt->execute([":id"=>$this->idEvenement]);
		$nbParticipants = $stmt->fetch()["Count"];

		$authentication = new SecureUserAuthentication();
		$idUser = $authentication->getUser()->getIdUser();
		$participer = "";
		if ($idUser != $this->idUser) {
			$stmt = MyPDO::getInstance()->prepare(<<<SQL
			SELECT idUser
			FROM Identifier2
			WHERE idEvenement = :id
			AND idUser = :idUser
	SQL);
			$stmt->execute([":id"=>$this->idEvenement, ":idUser"=>$idUser]);
			if ($stmt->fetch() == false) {
			$participer = <<<HTML
				 |<a href="#" onclick="joinEvent(this,{$this->idEvenement});"> Rejoindre</a>
HTML;
			}
			else {
			$participer = <<<HTML
				 |<a href="#" onclick="leaveEvent(this,{$this->idEvenement});"> Quitter</a>
HTML;
			}
		}
		return <<<HTML
	<div class="post-user" style="border: 2px solid #ff6b00" id="{$this->idPublication}" onclick="affichePublication(this);">
		<div class="card">
			<div class="card-header">
				<a href="profil.php?user={$login}"><img src="getImage.php?id=$idAvatar" class="card-avatar" placeholder="Avatar"></a>
				<a href="profil.php?user={$login}"><span class="username-post">$firstName $lastName</span></a>
				<div class="post-info">Lieu : <span class="post-location">{$this->lieu}</span></div>
				<div class="post-info">
					Participants : $nbParticipants/{$this->nbParticipantsMax}
$participer
				</div>
				<div class="d-flex justify-content-between">
					<div class="post-info">Date Événement : {$this->dateEvenement}</div>
					<div class="post-info">Date : {$this->dateDePublication}</div>
				</div>
			</div>
$message
$image
			<h2 class="text-post">{$this->nom}</h2>
		</div>
		<div class="comments"></div>
		<input class="comment-input" id="input" name="comment" placeholder="Ajouter un commentaire...">
		<div class="post-comment" id="btnn" onclick="ajouterCommentaire(this.parentNode.getElementsByTagName('input')[0], div, idMyThis)">Publier
			<svg class="svg-icon" id="icon-btn" viewBox="0 0 20 20">
				<path d="M17.218,2.268L2.477,8.388C2.13,8.535,2.164,9.05,2.542,9.134L9.33,10.67l1.535,6.787c0.083,0.377,0.602,0.415,0.745,0.065l6.123-14.74C17.866,2.46,17.539,2.134,17.218,2.268 M3.92,8.641l11.772-4.89L9.535,9.909L3.92,8.641z M11.358,16.078l-1.268-5.613l6.157-6.157L11.358,16.078z"></path>
			</svg>
		</div>
	</div>

HTML;
	}

	/** Accesseurs **/
	public function getIdEvenement() : int { return $this->idEvenement; }
	public function getNbParticipantsMax() : int { return $this->nbParticipantsMax; }
	public function getTypeEvenement() : int { return $this->typeEvenement; }
	public function getNom() : string { return $this->nom; }
	public function getLieu() : string { return $this->lieu; }
	public function getDateEvenement() : string { return $this->dateEvenement; }
}
