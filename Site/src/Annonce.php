<?php declare(strict_types=1);

Class Annonce {
	protected int $idAnnonce;
	protected int $idUser;
	protected int $idImage;
	protected string $titre;
	protected string $description;
	protected string $dateAnnonce;

	public function __construct(array $data) {
		$this->idAnnonce		    = intval($data['idAnnonce']) ?? 0;
		$this->idUser				= intval($data['idUser']) ?? 0;
		$this->idImage				= intval($data['idImage']) ?? 0;
        $this->titre	            = $data['titre'] ?? "";
        $this->description			= $data['description'] ?? "";
		$this->dateAnnonce	        = $data['dateAnnonce'] ?? "";
	}

	public function generateHtmlPost() : string {
		$user = User::createFromId($this->idUser);
		$idAvatar = $user->getIdImage();
		$name = $user->getName();
		$login = $user->getLogin();
		$image = "";
		if ($this->idImage != 0)
			$image = <<<HTML
			<img class="card-img-bottom" src="getImage.php?id={$this->idImage}">
HTML;
		return <<<HTML
	<div class="post-user" style="border: 2px solid #ff6b00" id="{$this->idAnnonce}" onclick="afficheAnnonce(this);">
		<div class="card">
			<div class="card-header">
				<a href="profil.php?user={$login}"><img src="getImage.php?id=$idAvatar" class="card-avatar" placeholder="Avatar"></a>
				<a href="profil.php?user={$login}"><span class="username-post">$name</span></a>
				<div class="post-info">Ville : <span class="post-location">{$user->getVille()}</span></div>
				<div class="d-flex justify-content-between">
					<div class="post-info">Téléphone : {$user->getPhone()} <button onclick="nouvelleConvAnnonce({$this->idAnnonce})">Contacter</button></div>
					<div class="post-info">Date : <span class="post-date">{$this->dateAnnonce}</span></div>
				</div>
			</div>
			<h2 class="text-post">{$this->titre}</h2>
$image
			<p style="margin:2%;">{$this->description}</h2>
		</div>
	</div>

HTML;
	}

	/**
     * Cette méthode permet de récupérer les infos de la base de données
     * et de les transformer en objet de la classe Annonce
     * @param int $idAnnonce
     * @return Annonce
     * @throws Exception
     */
    public static function createFromId(int|string $idAnnonce) : self {
        $stmt = MyPDO::getInstance()->prepare(<<<SQL
    SELECT idAnnonce, idUser, idImage, titre, description, dateAnnonce
    FROM Annonce
    WHERE idAnnonce = :id
SQL);
        $stmt->execute([':id'=>intval($idAnnonce)]);
        if (($User = $stmt->fetch()) !== false)
            return new Annonce($User);
        else
            throw new Exception("L'User ne peut pas être trouvé dans la base de données");
    }


	/** Accesseurs **/
	public function getIdAnnonce() : int { return $this->idAnnonce; }
	public function getIdImage() : int { return $this->idImage; }
	public function getIdUser() : int { return $this->idUser; }
	public function getTitre() : string { return $this->titre; }
	public function getDateDePublication() : string { return $this->dateAnnonce; }
}
