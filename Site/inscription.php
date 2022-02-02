<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="description" content="">
	<meta name="author" content="">
	<title>Inscription</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
	<link href="css/inscription.css" rel="stylesheet">
</head>

<body class="w-100 h-100">
	<script type="text/javascript" src="js/ajaxrequest.js"></script>
	<script type="text/javascript" src="js/sha512.js"></script>
	<script type="text/javascript" src="js/inscription.js"></script>
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="js/inscription.js"></script>

	<div id='page' class="w-100 h-100">
		<div class="d-flex header flex-column align-items-center w-100 h-100">
			<p>Inscription</p>
		</div>
		<div class="d-flex header w-100 h-100 flex-row flex-grow-1 p-3 align-items-center">
			<div class="register flew-column flex-grow-1 align-items-center">
				<div class="d-flex align-self-start flex-column align-items-center">
					<p>1</p>
				</div>
				<div class="d-flex align-self-end flex-column align-items-center">
					<h6>Personel</h6>
				</div>
			</div>
			<div class="register flew-column flex-grow-1 align-items-center">
				<div class="d-flex align-self-start flex-column align-items-center">
					<p>2</p>
				</div>
				<div class="d-flex align-self-end flex-column align-items-center">
					<h6>Sport</h6>
				</div>
			</div>
			<div class="register flew-column flex-grow-1 align-items-center">
				<div class="d-flex align-self-start flex-column align-items-center">
					<p>3</p>
				</div>
				<div class="d-flex align-self-end flex-column align-items-center">
					<h6>Abilitées</h6>
				</div>
			</div>
			<div class="register flew-column flex-grow-1 align-items-center">
				<div class="d-flex align-self-start flex-column align-items-center">
					<p>4</p>
				</div>
				<div class="d-flex align-self-end flex-column align-items-center">
					<h6>Confirmation</h6>
				</div>
			</div>
		</div>

		<div class="d-flex flex-column align-items-center flex-row flex-grow-1">
			<form id="Inscription" name="Inscription">
				<div class="d-flex flex-row">
					<div class="text button d-flex flex-column align-items-center p-3">
						Nom d'utilisateur
						<label class="blanc button px-2">
							<input id="login" type="text" required>
						</label>
					</div>
					<div class="text button d-flex flex-column align-items-center p-3">
						Anniversaire
						<label class="blanc button px-2">
							<input id="birth" type="date" required>
						</label>
					</div>
				</div>
				<div class="d-flex flex-row">
					<div class="text button d-flex flex-column align-items-center p-3">
						Prénom
						<label class="blanc button px-2">
							<input id="firstname" type="text" required>
						</label>
					</div>
					<div class="text button d-flex flex-column align-items-center p-3">
						Nom
						<label class="blanc button px-2">
							<input id="lastname" type="text" required>
						</label>
					</div>
				</div>
				<div class="d-flex flex-row">
					<div class="text button d-flex flex-column align-items-center p-3">
						E-mail
						<label class="blanc button px-2">
							<input id="email" type="text" required>
						</label>
					</div>
					<div class="text button d-flex flex-column align-items-center p-3">
						Num de téléphone
						<label class="blanc button px-2">
							<input placeholder="Ex: 0654254789" id="phone" type="tel" pattern="[0-9]{10}" required>
						</label>
					</div>
				</div>
				<div class="d-flex flex-row">
					<div class="text button d-flex flex-column align-items-center p-3">
						Mot de passe
						<label class="blanc button px-2">
							<input id="password" type="password" required>
						</label>
					</div>
					<div class="text button d-flex flex-column align-items-center p-3">
						Confirmation mot de passe
						<label class="blanc button px-2">
							<input id="passwordConf" type="password" required>
						</label>
					</div>
				</div>

				<div class="container" id="container">
                    <div class="starter-template d-flex flex-row">

                        <?php if(!empty($error)):?>
                            <div class="alert alert-danger"><?=$error;?></div>
                        <?php endif;?>

                        <?php if(!empty($success)):?>
                            <div class="alert alert-success"><?= $success;?></div>
                        <?php endif;?>

                            <div class="form-group text button d-flex flex-column align-items-center p-3">
								Code Postal
								<label class="blanc button px-2" for="zipcode">
                                	<input type="number" name="zipcode" class="form-control" placeholder="Code postal" id="zipcode" required>
								</label>
                                <div style="display: none; color: #f55;" id="error-message"></div>
                            </div>

                            <div class="form-group text button d-flex flex-column align-items-center p-3">
                                <label class="blanc button px-2" for="city">Ville</label>
                                <select class="form-control" name="city" id="city"></select>
                            </div>

                    </div>
                </div>
                
				<div class="text button d-flex flex-column align-items-center p-3">
					Genre
					<div class="d-flex flex-row">
						<label class="p-2">
							<input id="genre" name="genre" type="radio" value="0" checked>
							Homme
						</label>
						<label class="p-2">
							<input id="genre" name="genre" type="radio" value="1">
							Femme
						</label>
						<label class="p-2">
							<input id="genre" name="genre" type="radio" value="2">
							Autres
						</label>
					</div>
				</div>
				<div class="mx-auto d-flex flex-column">
					<p id="registerConfirmation" class="message twrap text-center text-wrap"></p>
				</div>				
				<div class="register2 button d-flex flex-column align-items-center p-3">
					<button class="Inscriptionvalider p-1 align-items-center p-3" type="submit">REGISTER !</button>
				</div>
			</form>
		</div>
	</div>
</body>

</html>