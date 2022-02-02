<?php declare(strict_types=1);
require_once "autoload.php";
$authentication = new SecureUserAuthentication();
$authentication->logoutIfRequested();

if (isset($_POST["code"])) {
	$user = $authentication->getUserFromAuth();
	http_response_code(308);
    header("Location: .");
    die();
}
else {
Session::start();
$challenge = Random::string(16);
$_SESSION[AbstractUserAuthentication::SESSION_KEY]["challenge"] = $challenge;

$page = new WebPage("Connexion");
$page->appendToHead(<<<HTML
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link href="css/connexion.css" rel="stylesheet">
	<script src="js/sha512.js" type="text/javascript"></script>
	<script>
function hashCode() {
	var login = CryptoJS.SHA512(document.querySelector("#login").value);
	var pass = CryptoJS.SHA512(document.querySelector("#pass").value);
	var challenge = document.querySelector("#challenge").value;
	var code = document.querySelector("#code");
	code.value = CryptoJS.SHA512(login + pass + challenge);
}
	</script>
HTML);
$page->appendContent(<<<HTML
<div id="page" class="d-flex flex-row p-2">
    <div class="d-flex flex-column p-2 mx-auto h-100 align-self-center">
        <div class="d-flex flex-column justify-content-center border p-2 login--form">
            <p class="d-flex justify-content-center GAKALL mb-5">Gakall <span class="Sport"> Sport</span></p>
            <form name="connexion" method="POST" action="connexion.php" onsubmit="hashCode();">
				<label class="mx-3 align-self-center">
					<input class="button mb-2 ml-3" id="login" type="text" placeholder="Nom d'utilisateur" style="width: 324px; height: 53px;" required>
					<input class="button ml-3" id="pass" type="password" placeholder="Mot de passe" style="width: 324px; height: 53px;" required>
					<input for="checkbox" id="checkbox" type="checkbox">
					<input hidden type='text' name="code" id="code" value="">
					<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
					<script type="text/javascript">
$(document).ready(function() {
	var checkbox = $("#checkbox");
	var password = $("#pass");
	checkbox.click(function() {
		if (checkbox.prop("checked")) {
			password.attr("type", "text");
		} else {
			password.attr("type", "password");
		}
	});
});
					</script>
				</label>
				<input hidden type='text' id="challenge" value="$challenge">
				<input hidden type='text' id="code" value="">
				<button class="mt-2 button mb-10" type="submit" style="margin-left: 35px; background-color: #FF6B00; width: 324px; height: 39px;">Connexion</button>
            </form>
        </div>
		<div class="d-flex justify-content-center border p-2 mt-2" style="height: 64px; width: 416px;">
            <p class="textnoire align-self-center mt-3">Pas de compte ? <a href="inscription.php" class="textorange">Inscrivez-vous</a></p>
        </div>
        <div class="d-flex flex-column p-2" style="width: 416px;">
            <p class="justify-content-center textnoire">Télécharger l'application</p>
            <div class="d-flex flex-row justify-content-center">
                <img src="img/app.png" alt="">
                <img src="img/google.png" alt="">
            </div>
        </div>
    </div>
</div>
HTML);

echo $page->toHTML();
}
