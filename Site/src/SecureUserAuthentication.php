<?php declare(strict_types=1);

Class SecureUserAuthentication extends AbstractUserAuthentication {
	public function getUserFromAuth(): User {
		Session::start();
		if (!isset($_POST["code"]))
			throw new Exception("Aucun code trouvé");
		$stmt = MyPDO::getInstance()->prepare(<<<SQL
		SELECT idUser, idImage, login, role, genre, email, lastName, firstName, birth, phone, bio, banniere , ville FROM User
		WHERE :code = SHA2(CONCAT(SHA2(login,512),password,:challenge),512)
		AND idUser >= 0
SQL);
		$stmt->execute([
			':challenge' => $_SESSION[self::SESSION_KEY]["challenge"],
			':code' => $_POST["code"],			
		]);
		if (($user = $stmt->fetch()) !== false) {
			$this->setUser(new User($user));
			return new User($user);
		}
		else
			throw new Exception("Login/Mot de Passe Incorrect");
	}

	public function setUserFromRegister(int $id): void {
		Session::start();
		$stmt = MyPDO::getInstance()->prepare(<<<SQL
		SELECT idUser, idImage, login, role, genre, email, lastName, firstName, birth, phone, bio, banniere , ville
		FROM User
		WHERE idUser = :id
		AND idUser >= 0
SQL);
		$stmt->execute([':id'=>$id]);
		$user = $stmt->fetch();
		$this->setUser(new User($user));
	}
}
