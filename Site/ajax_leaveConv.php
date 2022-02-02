<?php declare(strict_types = 1);
require_once "autoload.php";
$authentication = new SecureUserAuthentication();
$user = $authentication->getUser();

if (isset($_POST["conversation"]))
{
    //Se suppr de la conv
    $sql = MyPDO::getInstance()->prepare(<<<SQL
DELETE FROM Tchatter 
WHERE idUser = :user 
AND idConversation = :idConversation;
SQL);
    $sql->execute([':user'=>$user->getIdUser(),':idConversation'=>$_POST["conversation"]]);




    //Avoir le nombre de personnes restante dans la conv
    $sql2 = MyPDO::getInstance()->prepare(<<<SQL
SELECT COUNT(DISTINCT idUser) as 'nbUser'
FROM Tchatter
WHERE idConversation = :idConversation
AND idUser >= 0
SQL);
    $sql2->execute([':idConversation'=>$_POST["conversation"]]);
    $nbUser = $sql2->fetch();

    //Si il reste 1 utilisateur
    if($nbUser['nbUser'] <= 1){
        //Suppr de tous les msg
        $deleteMSG = MyPDO::getInstance()->prepare(<<<SQL
DELETE FROM message
WHERE idConversation = :idConversation;
SQL);
        $deleteMSG->execute([':idConversation'=>$_POST["conversation"]]);

        //Recup le dernier User
        $getLastUser = MyPDO::getInstance()->prepare(<<<SQL
SELECT idUser 
FROM Tchatter
WHERE idConversation = :idConversation
AND idUser >= 0
SQL);
        $getLastUser->execute([':idConversation'=>$_POST["conversation"]]);
        $lastUser = $getLastUser->fetch();
        
        //Suppr le dernier User
        $deleteLastUser = MyPDO::getInstance()->prepare(<<<SQL
DELETE FROM Tchatter
WHERE idUser = :idLastUser
AND idConversation = :idConversation;
SQL);
        $deleteLastUser->execute([':idLastUser'=>$lastUser['idUser'],':idConversation'=>$_POST["conversation"]]);

        //Suppr de la conv
        $deleteConv = MyPDO::getInstance()->prepare(<<<SQL
DELETE FROM Conversation
WHERE idConversation = :idConversation;
SQL);
        $deleteConv->execute([':idConversation'=>$_POST["conversation"]]);
    }
}
