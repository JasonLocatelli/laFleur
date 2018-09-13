<?php
if (isset($_REQUEST['action'])) {
    $action = $_REQUEST['action'];
}
else {
    $action = 'connexion';    
}

switch ($action) {   
    case 'connexion': {
        if (!estConnecte()) {
            include('vues/v_connexion.php');
        }
        else {
            $lesCategories = $pdo->getLesCategories();
            include ('vues/v_deconnexion.php');
            include ('vues/v_categoriesAdmin.php');
        }    
    break;
    }
    case 'validConnexion': {
        $user = $_REQUEST['user'];
        $mdp = $_REQUEST['mdp'];
        $n = $pdo->validerAdmin($user,$mdp);
        if ($n == 1) {
            enregAdmin();
            $lesCategories = $pdo->getLesCategories();
            include ('vues/v_deconnexion.php');
            include ('vues/v_categoriesAdmin.php');            
        }
        else {
            $message = "Erreur d'identification ou de mot de passe !";
            include('vues/v_message.php');
            include('vues/v_connexion.php');
        }
    break;
    }
    case 'deconnexion': {
        quitterAppli();
        include("vues/v_accueil.php");
    break;
    }
        
       
        
}

    
?>