<?php declare(strict_types = 1);
require_once "autoload.php";
$authentication = new SecureUserAuthentication();
$user = $authentication->getUser();

if(isset($_POST["message"]) && isset($_POST["conversation"]))
    if(!empty($_POST["message"]) && !empty($_POST["conversation"])){
        $texte = nl2br(htmlspecialchars($_POST["message"]));
        $insererTexte = MyPDO::getInstance()->prepare(<<<SQL
            INSERT INTO message (idUser, idConversation, texte, dateEnvoi) VALUES (:idUser, :idConversation, :texte, SYSDATE())
SQL);
        $insererTexte->execute([':texte'=>$texte,':idUser'=>$user->getIdUser(),':idConversation'=>$_POST["conversation"]]);
    }
