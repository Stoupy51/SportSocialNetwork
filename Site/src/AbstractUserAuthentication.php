<?php declare(strict_types=1);

Abstract Class AbstractUserAuthentication {
	public const SESSION_KEY = '__UserAuthentication__';
	public const SESSION_USER_KEY = 'user';
	public const LOGOUT_INPUT_NAME = 'disconnect';
	private ?User $user = null;

	public function __construct() {
		try { $this->user = $this->getUserFromSession(); }
		catch (Exception) {}
	}

	public abstract function getUserFromAuth() : User;

	protected function setUser(User $user) {
		$this->user = $user;
		Session::start();
		$_SESSION[self::SESSION_KEY][self::SESSION_USER_KEY] = $this->user;
	}

	public function isUserConnected() : bool {
		Session::start();
        return isset($_SESSION[self::SESSION_KEY][self::SESSION_USER_KEY])
		&& $_SESSION[self::SESSION_KEY][self::SESSION_USER_KEY] instanceof User;
	}

	public function logoutForm(string $action, string $text = 'OK') : string {
        $logout = self::LOGOUT_INPUT_NAME;
		return <<<HTML
<form name='logout' action='{$action}' method='POST'>
	<input type='submit' name='{$logout}' value='{$text}'>
</form>
HTML;
	}

	public function logoutIfRequested() {
		Session::start();
		if (isset($_GET[self::LOGOUT_INPUT_NAME])) {
			unset($_SESSION[self::SESSION_KEY]);
        	$this->user = null;
			header("Location: connexion.php");
		}
	}

	protected function getUserFromSession() : User {
		if (!$this->isUserConnected())
			throw new Exception();
		return $_SESSION[self::SESSION_KEY][self::SESSION_USER_KEY];
	}

	public function getUser() : User {
		if (is_null($this->user))
			throw new Exception();
		return $this->user;
	}
}
