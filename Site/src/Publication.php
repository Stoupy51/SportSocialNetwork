<?php declare(strict_types=1);

Class Publication {
	protected int $idPublication;
	protected int $idImage;
	protected int $idUser;
	protected string $dateDePublication;
	protected string $message;

	public function __construct(array $data) {
		$this->idPublication		= intval($data['idPublication']) ?? 0;
		$this->idImage				= intval($data['idImage']) ?? 0;
		$this->idUser				= intval($data['idUser']) ?? 0;
		$this->dateDePublication	= $data['dateDePublication'] ?? "";
		$this->message				= $data['message'] ?? "";
	}

	public function generateHtmlPost() : string {
		$user = User::createFromId($this->idUser);
		$idAvatar = $user->getIdImage();
		$firstName = $user->getFirstName();
		$lastName = $user->getLastName();
		$login = $user->getLogin();
		$image = "";
		if ($this->idImage != 0)
			$image = <<<HTML
			<img class="card-img-bottom" src="getImage.php?id={$this->idImage}">
HTML;
		$message = "";
		if (preg_replace("/ /i","",$this->message) != "")
			$message = <<<HTML
			<p class="post-message ml-3 mt-2">{$this->message}</p>
HTML;
		return <<<HTML
	<div class="post-user" style="border: 2px solid #ff6b00" id="{$this->idPublication}" onclick="affichePublication(this);">
		<div class="card">
			<div class="card-header">
				<a href="profil.php?user={$login}"><img src="getImage.php?id=$idAvatar" class="card-avatar" placeholder="Avatar"></a>
				<a href="profil.php?user={$login}"><span class="username-post">$firstName $lastName</span></a>
				<div class="post-info">Date : <span class="post-date">{$this->dateDePublication}</span></div>
			</div>
$message
$image
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
	public function getIdPublication() : int { return $this->idPublication; }
	public function getIdImage() : int { return $this->idImage; }
	public function getIdUser() : int { return $this->idUser; }
	public function getDateDePublication() : string { return $this->dateDePublication; }
	public function getMessage() : string { return $this->message; }
}
