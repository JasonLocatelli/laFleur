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
?>
