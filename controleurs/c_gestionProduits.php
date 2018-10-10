<?php

if (isset($_REQUEST['action'])) {
    $action = $_REQUEST['action'];
    // test de vérification que l'administrateur est bien connecté pour toutes les autres actions
    if ($action != 'validConnexion' && !estConnecte()) {
        $msgErreurs[] = "Action interdite, réservée à l'administrateur";
        include ("vues/v_erreurs.php");
        // Retour à l'affichage des catégories pour tous           
        $lesCategories = $pdo->getLesCategories();
	include("vues/v_categories.php");
        exit();
    }      
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
            include ('vues/v_categoriesAdmin.php');
        }    
    break;
    }
    case 'validConnexion': {
        $ok = false;
        if (isset($_REQUEST['user']) && isset($_REQUEST['mdp']) ) {
            $user = htmlspecialchars($_REQUEST['user']);
            $mdp = htmlspecialchars($_REQUEST['mdp']);
            $ok = true;
        }  
        
        //---------------------------------------------------
        //Vérification capcha 
        //---------------------------------------------------
        // Paramètre renvoyé par le recaptcha
	$response = $_POST['g-recaptcha-response'];
	// On récupère l'IP de l'utilisateur
	$remoteip = $_SERVER['REMOTE_ADDR'];
        $ok  = $ok & interrogeCapcha($response,$remoteip);
        
        if ($ok ) {
            $n = $pdo->validerAdmin($user,$mdp);
            if ($n == 1) {
                enregAdmin();
                $lesCategories = $pdo->getLesCategories();
                include ('vues/v_categoriesAdmin.php');            
            }
            else {	
                $ok = false;
            }
        }
        if (!$ok ) {
            $message = "Erreur d'identification ou de mot de passe !";
            include('vues/v_message.php');
            include('vues/v_connexion.php');
        }
    break;
    }
    case 'validDeconnexion': {
        quitterAppli();
        include("vues/v_accueil.php");
    break;
    }
    case 'deconnexion': {
        include ('vues/v_deconnexion.php'); 
    break;
    }
    case 'voirProduits' : {
        $lesCategories = $pdo->getLesCategories();
        include ('vues/v_categoriesAdmin.php'); 
        $categorie = $_REQUEST['categorie'];
        $lesProduits = $pdo->getLesProduitsDeCategorie($categorie);
        include("vues/v_produitsAdmin.php");
        break;
    }
    case 'modifierArticle' :{       
        $idProduit = $_REQUEST['idProduit'];
        // récupère toutes les infos d'un produit donné
        $leProduit = $pdo->getUnProduit($idProduit);
        // affiche la vue de modification du produit
        include("vues/v_modifProduitAdmin.php");
        break;
    }
    case 'validModifierArticle' :{
        if(isset($_POST['annuler']))
        {
            $message = "Modification annulée !!";
            include ("vues/v_message.php");
            $lesCategories = $pdo->getLesCategories();
            include ('vues/v_categoriesAdmin.php'); 
        }
        else {
            $idProduit = $_POST['txtId'];
            if (empty($_POST['txtDescription'])|| empty($_POST['txtPrix'])) {
                $msgErreurs[] = "Veuillez remplir correctement tous les champs";
                $leProduit = $pdo->getUnProduit($idProduit);
                include ("vues/v_erreurs.php");
                include("vues/v_modifProduitAdmin.php");                
            }
            else {           
                // les zones sont correctement remplies
                // modification de l'article dans la base de données
                $description = $_POST['txtDescription'];
                $prix = $_POST['txtPrix'];
                $nbLignes = $pdo->modifierProduit($idProduit, $description, $prix);  
                if ($nbLignes == 0) {
                    $msgErreurs[] = "Erreur de modification";
                    include ("vues/v_erreurs.php");
                    $leProduit = $pdo->getUnProduit($idProduit);
                    include("vues/v_modifProduitAdmin.php");                    
                }   
               else
                {
                    $message = "Modification effectuée !!";
                    include ("vues/v_message.php");
                    $lesCategories = $pdo->getLesCategories();
                    include ('vues/v_categoriesAdmin.php');           
                }
            }
        }
        break;
    }
    case 'supprimerArticle' :{
        $idProduit = $_REQUEST['idProduit'];
        include ("vues/v_suppressionProduitAdmin.php");        
        break;
    }
    case 'validSupprimerArticle' : {
        if(isset($_POST['annuler']))
        {
            $message = "Suppression annulée !!";
            include ("vues/v_message.php");
        }
        else {
            $idProduit = $_REQUEST['txtId'];
            $nbLignes = $pdo->supprimerProduit($idProduit);  
            switch ($nbLignes) {
                case -1: {
                    $msgErreurs[] = "Suppression impossible, produit utilisé dans une autre table";
                    include ("vues/v_erreurs.php");  
                break;
                }
                case 0 : {
                    $msgErreurs[] = "Suppression impossible";
                    include ("vues/v_erreurs.php");    
                break;
                }
                case 1 : {
                    $message = "suppression effectuée !!";
                include ("vues/v_message.php");       
                break;
                }
            }                    
        }
        $lesCategories = $pdo->getLesCategories();
        include ('vues/v_categoriesAdmin.php');    
        break;
    }
    case 'ajouterArticle' : {
        $idCateg = $_REQUEST['categorie'];
        
        //--------------------------------------------------------
        // rajout du calcul du numéro automatique de l'ID
        $maxId = $pdo->getIdMaxProduit($idCateg);       
        $nouvelId = creeNouvelId($maxId);
        //--------------------------------------------------------
        $laCateg = $pdo->getLaCategorie($idCateg);
        include("vues/v_ajoutProduitAdmin.php");          
        break ;
    }
    case 'validAjouterArticle' : {
        $idCateg = $_REQUEST['txtIdCateg'];
        //--------------------------------------------------------
        // rajout du calcul du numéro automatique de l'ID
        $maxId = $pdo->getLaCategorie($idCateg);
        
        if (empty($_POST['txtId'])|| empty($_POST['txtDescription']) || empty($_POST['txtPrix'])) {
                $msgErreurs[] = "Veuillez remplir correctement tous les champs";
                include ("vues/v_erreurs.php");
                $laCateg = $pdo->getLaCategorie($idCateg);
                //--------------------------------------------------------
                // rajout du calcul du numéro automatique de l'ID
                $maxId = $pdo->getIdMaxProduit($idCateg);       
                $nouvelId = creeNouvelId($maxId);
                include("vues/v_ajoutProduitAdmin.php");               
            }
        else {
            $idProduit = $_REQUEST['txtId'];            
            $description = $_REQUEST['txtDescription'];
            $prix = $_REQUEST['txtPrix']; 
            $image = chargeImage($idProduit);  
            $nbLignes = $pdo->ajouterProduit($idProduit,$description, $prix, $image, $idCateg );  
            if ($nbLignes == 0) {                         
                $msgErreurs[] = "Ajout impossible";
                include ("vues/v_erreurs.php");              
            }
            else {
                $message = "ajout effectué !!";
                include ("vues/v_message.php");               
            }
            $lesCategories = $pdo->getLesCategories();
            include ('vues/v_categoriesAdmin.php');   
        }
        break ;
    }
    
}

    
?>