<?php declare(strict_types=1);

Class Conversation {
	private int $idConversation;
	private int $idImage;
	private string $nomConversation;
	private array $users = [];

	public function __construct(int|string $idConversation) {
		$this->idConversation		= intval($idConversation);
		$renseignements = [':id'=>$this->idConversation];

		$stmt = MyPDO::getInstance()->prepare(<<<SQL
	SELECT idImage, nomConversation
	FROM Conversation
	WHERE idConversation = :id
SQL);
		$stmtUsers = MyPDO::getInstance()->prepare(<<<SQL
			SELECT idUser
			FROM tchatter
			WHERE idConversation = :id
			AND idUser >= 0
		SQL);

		$stmt->execute($renseignements);
		if (($Conversation = $stmt->fetch()) !== false) {
			$this->idImage = intval($Conversation["idImage"]);
			$this->nomConversation = $Conversation["nomConversation"];
			$stmtUsers->execute($renseignements);
			while (($user = $stmtUsers->fetch()) !== false)
				$this->users[] = User::createFromId($user["idUser"]);
		}
		else
			throw new Exception("Aucune conversation ne peut être trouvée dans la base de données");
	}

	public function getMessages() : array {
		$messages = [];
		$stmt = MyPDO::getInstance()->prepare(<<<SQL
	SELECT idMessage, idUser, idImage, texte, DATE_FORMAT(dateEnvoi,"%Y/%m/%d %Hh%i") as "dateEnvoi"
	FROM Message
	WHERE idConversation = :id
SQL);
		$stmt->execute([':id'=>$this->idConversation]);
		while (($message = $stmt->fetch()) !== false)
			$messages[] = [
				"user"=>User::createFromId($message["idUser"]),
				"idImage"=>$message["idImage"],
				"texte"=>$message["texte"],
				"dateEnvoi"=>$message["dateEnvoi"]
			];
		return $messages;
	}


	/** Accesseurs **/
	public function getIdConversation() : int { return $this->idConversation; }
	public function getIdImage() : int { return $this->idImage; }
	public function getNomConversation() : string { return $this->nomConversation; }
	public function getUsers() : array { return $this->users; }
}
