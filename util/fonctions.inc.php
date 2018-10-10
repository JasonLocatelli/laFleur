<?php
/**
 * Initialise le panier
 *
 * Crée une variable de type session dans le cas
 * où elle n'existe pas 
*/
function initPanier()
{
        
	if(!isset($_SESSION['produits']))
	{
		$_SESSION['produits']= array();
	}
       
            
}
/**
 * Supprime le panier
 *
 * Supprime la variable de type session 
 */
function supprimerPanier()
{
	unset($_SESSION['produits']);
       
}
/**
 * Ajoute un produit au panier
 *
 * Teste si l'identifiant du produit est déjà dans la variable session 
 * ajoute l'identifiant à la variable de type session dans le cas où
 * où l'identifiant du produit n'a pas été trouvé
 * sinon ajoute 1 à la quantité présente
 * @param $idProduit : identifiant de produit
 * @return vrai si le produit n'était pas dans la variable, faux sinon 
*/
function ajouterAuPanier($idProduit)
{
	
	$ok = true;
	if(array_key_exists($idProduit,$_SESSION['produits']))
	{
		//$ok = false;
                $_SESSION['produits'][$idProduit]++;
	}
	else
	{		
            // ajout d'une paire clé-valeur dans le tableau    
            $_SESSION['produits'][$idProduit] = 1;
            
	}
	return $ok;
}
/**
 * Retourne les produits du panier
 *
 * Retourne le tableau des identifiants de produit
 * @return : le tableau
*/
function getLesIdProduitsDuPanier()
{
	return array_keys($_SESSION['produits']) ;
}
/**
 * Retourne le nombre de produits du panier
 *
 * Teste si la variable de session existe
 * et retourne le nombre d'éléments de la variable session
 * @return : le nombre 
*/
function nbProduitsDuPanier()
{
	$n = 0;
	if(isset($_SESSION['produits']))
	{
	$n = count($_SESSION['produits']);
	}
	return $n;
}
/**
 * récupère la collection de quantités du panier
 * 
 * @return : la collection de quantité
 */
function getLesQuantites(){
    return array_values($_SESSION['produits']);
}
/**
 * Retire un de produits du panier
 *
 * Recherche l'index de l'idProduit dans la variable session
 * et détruit la valeur à ce rang
 * @param $idProduit : identifiant de produit 
*/
function retirerDuPanier($idProduit)
{
      if(array_key_exists($idProduit,$_SESSION['produits']))
	{
            $_SESSION['produits'][$idProduit]--;
            if ($_SESSION['produits'][$idProduit] == 0) {
                $index =array_search($idProduit,$_SESSION['produits']);                
                unset($_SESSION['produits'][$index]);
            }
	}
			
}
/**
 * teste si une chaîne a un format de code postal
 *
 * Teste le nombre de caractères de la chaîne et le type entier (composé de chiffres)
 * @param $codePostal : la chaîne testée
 * @return : vrai ou faux
*/
function estUnCp($codePostal)
{
   
   return strlen($codePostal)== 5 && estEntier($codePostal);
}
/**
 * teste si une chaîne est un entier
 *
 * Teste si la chaîne ne contient que des chiffres
 * @param $valeur : la chaîne testée
 * @return : vrai ou faux
*/

function estEntier($valeur) 
{
	return preg_match("/[^0-9]/", $valeur) == 0;
}
/**
 * Teste si une chaîne a le format d'un mail
 *
 * Utilise les expressions régulières
 * @param $mail : la chaîne testée
 * @return : vrai ou faux
*/
function estUnMail($mail)
{
return  preg_match ('#^[\w.-]+@[\w.-]+\.[a-zA-Z]{2,6}$#', $mail);
}
/**
 * Retourne un tableau d'erreurs de saisie pour une commande
 *
 * @param $nom : chaîne
 * @param $rue : chaîne
 * @param $ville : chaîne
 * @param $cp : chaîne
 * @param $mail : chaîne 
 * @return : un tableau de chaînes d'erreurs
*/
function getErreursSaisieCommande($nom,$rue,$ville,$cp,$mail)
{
	$lesErreurs = array();
	if($nom=="")
	{
		$lesErreurs[]="Il faut saisir le champ nom";
	}
	if($rue=="")
	{
	$lesErreurs[]="Il faut saisir le champ rue";
	}
	if($ville=="")
	{
		$lesErreurs[]="Il faut saisir le champ ville";
	}
	if($cp=="")
	{
		$lesErreurs[]="Il faut saisir le champ Code postal";
	}
	else
	{
		if(!estUnCp($cp))
		{
			$lesErreurs[]= "erreur de code postal";
		}
	}
	if($mail=="")
	{
		$lesErreurs[]="Il faut saisir le champ mail";
	}
	else
	{
		if(!estUnMail($mail))
		{
			$lesErreurs[]= "erreur de mail";
		}
	}
	return $lesErreurs;
}
/**
 * Teste si un administrateur est connecté
 * crée une nouvelle entité dans le tableau des variables de session
 */
function enregAdmin(){
    $_SESSION['admin'] = 1;
}

/**
 * retourne un booléen, vrai si l'administrateur est connecté
 * @return bool
 */
function estConnecte(){
    return isset($_SESSION['admin']);
}

/**
 * supprime la variable de session admin
 */
function quitterAppli(){
    unset($_SESSION['admin']);
}
/**
 * 
 * retourne un nouvel Id pour le produit à créer
 * @param type $maxId : l'Id max du produit de la catégorie
 * @return un nouvel ID
 */
function creeNouvelId($maxId){
    $lettre = $maxId[0];
    $num = substr ( $maxId ,1);
    $num++;
    if ($num<10) {
        $num = '0'.$num;            
    }
    $nouvelId = $lettre.$num;
    return $nouvelId;
}
/**
 * 
 */
function chargeImage($idProduit){
     $nomDestination = "";
     $hasError = false;
    /////----------------------------------------------------
    //----------------Traitement de l'ajout de l'image
    //-------------------------------------------------------          
    // Je peux faire plusieurs vérifications
    // 
    // $_FILES['icone']['name'] //Le nom original du fichier, comme sur le disque du visiteur (exemple : mon_icone.png).
    // $_FILES['icone']['type'] //Le type du fichier. Par exemple, cela peut être « image/png ».
    // $_FILES['icone']['size'] //La taille du fichier en octets.
    // $_FILES['icone']['tmp_name'] //L'adresse vers le fichier uploadé dans le répertoire temporaire.
    // $_FILES['icone']['error'] //Le code d'erreur, qui permet de savoir si le fichier a bien été uploadé.

    //-----------------------------------------Tests des possibilités d'erreur ------------------------------------------------
    // Erreur de transfert     
    var_dump($_FILES);
    if ($_FILES['imageProduit']['error'] > 0) {
        $hasError = true;
        echo "Erreur lors du transfert de l'image";
        //$msgErreurs[] = "Erreur lors du transfert de l'image";
    } else {
        /*  Je peux si je veux, détailler les erreurs
         *  UPLOAD_ERR_NO_FILE : fichier manquant.
            UPLOAD_ERR_INI_SIZE : fichier dépassant la taille maximale autorisée par PHP.
            UPLOAD_ERR_FORM_SIZE : fichier dépassant la taille maximale autorisée par le formulaire.
            UPLOAD_ERR_PARTIAL : fichier transféré partiellement.
         */
        //vérifier la taiille maximale 
        $maxSize = $_REQUEST['MAX_FILE_SIZE']; 
        if ($_FILES['imageProduit']['size'] > $maxSize) {
            $hasError = true;
            echo "Le fichier est trop gros";
            $msgErreurs[] = "Le fichier est trop gros";
        } 
        else {             
            // vérifier les types acceptés
            //$extensions_valides = array( 'jpg' , 'jpeg' , 'gif' , 'png' );
            $extensions_valides = array( 'gif' );
            //1. strrchr renvoie l'extension avec le point (« . »).
            //2. substr(chaine,1) ignore le premier caractère de chaine.
            //3. strtolower met l'extension en minuscules.
            $extension_upload = strtolower( substr(strrchr($_FILES['imageProduit']['name'], '.') ,1) );
            if (!in_array($extension_upload,$extensions_valides) ) {
                $hasError = true;
                echo "Type de fichier incorrect";
                $msgErreurs[] = "Type de fichier incorrect";
            } 
            else {          
                // contrôler les dimensions d'une image
                $maxwidth = 200;
                $maxheight = 200;
                $image_sizes = getimagesize($_FILES['imageProduit']['tmp_name']); // fonction php
                if ($image_sizes[0] > $maxwidth OR $image_sizes[1] > $maxheight) {
                    $hasError = true;
                    echo "Image trop grande";
                    $msgErreurs[] = "Image trop grande";
                }
            }
        }
    }  
    if (!$hasError) {
        // déplacer le fichier
        //1. Idéalement, il faut renommer le fichier de façon automatique afin de ne pas avoir de doublon dans les noms de fichiers
        //On peut utiliser une incrémentation automatique dans le dossier
        // Ici, pour simplifier, je garde le nom et l'extension
        
        // copier l'image dans le dossier correspondant à la catégorie
        $codeCat = $idProduit[0];
        switch ($codeCat) {
            case 'c' : {
                $categorie = "compo";             
            break;
            }
            case 'f' : {
                $categorie = "fleurs";               
            break;
            }
            case 'p' : {
                $categorie = "plantes";               
            break;
            }            
        }
        // adresse du fichier dans le dossier temporaire de téléchargement
        $nomOrigine = $_FILES['imageProduit']['tmp_name'];
        // Placement du fichier dans le dossier images du site, correspondant à la catégorie
        $nomDestination = "images/".$categorie."/".$_FILES['imageProduit']['name'];  
        var_dump($nomOrigine);
        var_dump($nomDestination);
        // déplacement du fichier
        $resultat = move_uploaded_file($nomOrigine,$nomDestination);
        if (!$resultat) {
            $msgErreurs[] = "Echec de transfert";
            echo "Echec de transfert";
            $hasError = true;
        }          
    }
    return $nomDestination;   
}

    function interrogeCapcha($response,$remoteip) {
    // Ma clé privée 
    $secret = "6Lc0snIUAAAAAAn9y54-WWlMPOb5md-8dPFZQsKj";
      //Utilisation de l'API
    $api_url = "https://www.google.com/recaptcha/api/siteverify?secret=" 
        . $secret
        . "&response=" . $response
        . "&remoteip=" . $remoteip ;

    // réponse en json de l'API GOOGLE
    $decode = json_decode(file_get_contents($api_url), true);	
    //test
    return $decode['success'];
}

?>
