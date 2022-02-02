<?php declare(strict_types=1);

Class Session {
	static public function start() {
		if (session_status() !== PHP_SESSION_ACTIVE) {
			if (headers_sent())
				throw new Exception("Impossible de modifier les entêtes HTTP");
			if (session_status() == PHP_SESSION_DISABLED)
				throw new Exception("Etat de la session incompatible ou incohérent");
			session_start();
		}
	}
}
