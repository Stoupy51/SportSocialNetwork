<?php declare(strict_types=1);

Class User {
	private int $idUser;
	private int $idImage;
	private string $login;
	private int $role;
	private int $genre;
	private string $email;
	private string $lastName;
	private string $firstName;
	private string $birth;
	private string $phone;
	private string $bio;
	private string $banniere;
	private string $ville;
	private string $coordonnees;

	public function __construct(array $data) {
		$this->idUser		= intval($data['idUser']) ?? 0;
		$this->idImage		= intval($data['idImage']) ?? 0;
		$this->login		= $data['login'] ?? "";
		$this->role			= intval($data['role']) ?? 0;
		$this->genre		= intval($data['genre']) ?? 0;
		$this->email		= $data['email'] ?? "";
		$this->lastName		= $data['lastName'] ?? "";
		$this->firstName	= $data['firstName'] ?? "";
		$this->birth		= $data['birth'] ?? "";
		$this->phone		= $data['phone'] ?? "";
		$this->bio			= $data['bio'] ?? "";
		$this->banniere		= $data['banniere'] ?? "";
		$this->ville        = $data['ville'] ?? "";
		$this->coordonnees   = $data['coordonnees'] ?? "";
	}

	/**
     * Cette méthode permet de récupérer les infos de la base de données
     * et de les transformer en objet de la classe User
     * @param int $idUser
     * @return User
     * @throws Exception
     */
    public static function createFromId(int|string $idUser) : self {
        $stmt = MyPDO::getInstance()->prepare(<<<SQL
    SELECT idUser, idImage, login, role, genre, email, lastName, firstName, birth, phone, bio, banniere,ville, coordonnees
    FROM User
    WHERE idUser = :id
SQL);
        $stmt->execute([':id'=>intval($idUser)]);
        if (($User = $stmt->fetch()) !== false)
            return new User($User);
        else
            throw new Exception("L'User ne peut pas être trouvé dans la base de données");
    }


	/** Accesseurs **/
	public function getIdUser() : int { return $this->idUser; }
	public function getIdImage() : int { return $this->idImage; }
	public function getLogin() : string { return $this->login; }
	public function getRole() : int { return $this->role; }
	public function getGenre() : int { return $this->genre; }
	public function getEmail() : string { return $this->email; }
	public function getLastName() : string { return $this->lastName; }
	public function getFirstName() : string { return $this->firstName; }
	public function getBirth() : string { return $this->birth; }
	public function getPhone() : string { return $this->phone; }
	public function getName() : string { return $this->firstName." ".$this->lastName; }
	public function getBio() : string { return $this->bio; }
	public function getBanniere() : string { return $this->banniere; }
	public function getVille() : string {return $this->ville;}
}
