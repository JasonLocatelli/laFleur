<?php
//-------------------------------------------------------------------------------
// à améliorer : 
// les contrôles
// sur les saisies clavier
// --> exemple : ajout d'une chaine de caractères avec ' ex : bouquet d'hortensias
//------------------------------------------------------------------------------------
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
            include ('vues/v_categoriesAdmin.php');            
        }
        else {
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
            if ($nbLignes == 0) {
                // Erreur de requête
                // ou Injection SQL
                // ou produit ne pouvant être supprimé de la base de données car inclus dans une commande
                // => table contenir - pb de clés étrangères ==> le message devrait être :
                // "ce produit ne peut pas être supprimé ; il est inclus dans une commande"
                $msgErreurs[] = "Suppression impossible";
                include ("vues/v_erreurs.php");                                 
            }   
           else
            {
                $message = "suppression effectuée !!";
                include ("vues/v_message.php");         
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
            $nbLignes = $pdo->ajouterProduit($idProduit,$description, $prix, $idCateg );  
            if ($nbLignes == 0) {
                // Erreur de requête
                // ou Injection SQL             
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