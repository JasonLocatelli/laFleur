<?php
/** 
 * Classe d'accès aux données. 
 
 * Utilise les services de la classe PDO
 * pour l'application lafleur
 * Les attributs sont tous statiques,
 * les 4 premiers pour la connexion
 * $monPdo de type PDO 
 * $monPdoLafleur qui contiendra l'unique instance de la classe
 *
 * @package default
 * @author Patrice Grand
 * @version    1.0
 * @link       http://www.php.net/manual/fr/book.pdo.php
 */

class PdoLafleur
{   		
      	private static $serveur='mysql:host=localhost';
      	private static $bdd='dbname=lafleur';   		
      	private static $user='root' ;    		
      	private static $mdp='' ;	
	private static $monPdo;
	private static $monPdoLafleur = null;
/**
 * Constructeur privé, crée l'instance de PDO qui sera sollicitée
 * pour toutes les méthodes de la classe
 */				
	private function __construct()
	{
    		PdoLafleur::$monPdo = new PDO(PdoLafleur::$serveur.';'.PdoLafleur::$bdd, PdoLafleur::$user, PdoLafleur::$mdp); 
			PdoLafleur::$monPdo->query("SET CHARACTER SET utf8");
	}
	public function _destruct(){
		PdoLafleur::$monPdo = null;
	}
/**
 * Fonction statique qui crée l'unique instance de la classe
 *
 * Appel : $instancePdolafleur = PdoLafleur::getPdoLafleur();
 * @return l'unique objet de la classe PdoLafleur
 */
	public  static function getPdoLafleur()
	{
		if(PdoLafleur::$monPdoLafleur == null)
		{
			PdoLafleur::$monPdoLafleur= new PdoLafleur();
		}
		return PdoLafleur::$monPdoLafleur;  
	}
/**
 * Retourne toutes les catégories sous forme d'un tableau associatif
 *
 * @return le tableau associatif des catégories 
*/
	public function getLesCategories()
	{
		$req = "select * from categorie";
		$res = PdoLafleur::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		return $lesLignes;
	}

/**
 * Retourne sous forme d'un tableau associatif tous les produits de la
 * catégorie passée en argument
 * 
 * @param $idCategorie 
 * @return un tableau associatif  
*/

	public function getLesProduitsDeCategorie($idCategorie)
	{
	    $req="select * from produit where idCategorie = '$idCategorie'";
		$res = PdoLafleur::$monPdo->query($req);
		$lesLignes = $res->fetchAll();
		return $lesLignes; 
	}
/**
 * Retourne les produits concernés par le tableau des idProduits passée en argument
 *
 * @param $desIdProduit tableau d'idProduits
 * @return un tableau associatif 
*/
	public function getLesProduitsDuTableau($desIdProduit)
	{
		$nbProduits = count($desIdProduit);
		$lesProduits=array();
		if($nbProduits != 0)
		{
			foreach($desIdProduit as $unIdProduit)
			{
				$req = "select * from produit where id = '$unIdProduit'";
				$res = PdoLafleur::$monPdo->query($req);
				$unProduit = $res->fetch();
				$lesProduits[] = $unProduit;
			}
		}
		return $lesProduits;
	}
/**
 * Crée une commande 
 *
 * Crée une commande à partir des arguments validés passés en paramètre, l'identifiant est
 * construit à partir du maximum existant ; crée les lignes de commandes dans la table contenir à partir du
 * tableau d'idProduit passé en paramètre
 * @param $nom 
 * @param $rue
 * @param $cp
 * @param $ville
 * @param $mail
 * @param $lesIdProduit
 
*/
	public function creerCommande($nom,$rue,$cp,$ville,$mail, $lesIdProduit,$lesQuantites )
	{
		$req = "select max(id) as maxi from commande";
		echo $req."<br>";
		$res = PdoLafleur::$monPdo->query($req);
		$laLigne = $res->fetch();
		$maxi = $laLigne['maxi'] ;
		$maxi++;
		$idCommande = $maxi;
		echo $idCommande."<br>";
		echo $maxi."<br>";
		$date = date('Y/m/d');
		$req = "insert into commande values ('$idCommande','$date','$nom','$rue','$cp','$ville','$mail')";
		echo $req."<br>";
		$res = PdoLafleur::$monPdo->exec($req);
                $i = 0;
		foreach($lesIdProduit as $unIdProduit)
		{
			$req = "insert into contenir values ('$idCommande','$unIdProduit',$lesQuantites[$i])";
			echo $req."<br>";
			$res = PdoLafleur::$monPdo->exec($req);
                        $i++;
		}
	}
    /**
     * retourne 1 si l'administrateur a bien saisi son nom et son mot de passe, 0 sinon
     * @param  $user
     * @param  $mdp
     * @return le nombre d'utilisateurs ayant ce nom et ce mdp
     */
        public function validerAdmin($user,$mdp){
            $req = "select count(*) as nb from administrateur where nom = '$user' and mdp ='$mdp'";
            $res = PdoLafleur::$monPdo->query($req);
            $laLigne = $res->fetch();
            return $laLigne['nb'];            
        }
        /**
         * Retourne sous forme de tableau les informations d'un produit
         * dont l'Id est pasé en paramètre
         * @param type $leProduit
         */
        public function getUnProduit($unIdProduit){
            $req = "select * from produit where id = '$unIdProduit'";
            $res = PdoLafleur::$monPdo->query($req);
            $unProduit = $res->fetch();
            return $unProduit;
        }
        
        /**
         * modifier le produit dans la base de données dont l'ID est donné en paramètre
         * @param type $id
         * @param type $description
         * @param type $prix
         */
        public function modifierProduit($id, $description, $prix){
            $req = "UPDATE produit set description = '$description', prix = $prix WHERE id = '$id' ";
            $nbLignes = PdoLafleur::$monPdo->exec($req);
            return ($nbLignes);            
        }
        /**
         * Supprimer un produit dans la base de données
         * @param type $id
         * @return le nombre de lignes supprimées
         */
        public function supprimerProduit($id) {
            $req = "DELETE FROM produit WHERE id = '$id' ";
            $nbLignes = PdoLafleur::$monPdo->exec($req);
            return ($nbLignes);  
        }
        
        /**
         * Obtenir les infos de la catégorie
         * @return un tableau contenant les infos de la catégorie id et libelle
         *
         */
        public function getLaCategorie($id)
	{
		$req = "select * from categorie WHERE id = '$id' ";
		$res = PdoLafleur::$monPdo->query($req);
		$uneCateg = $res->fetch();
		return $uneCateg;
	}
        /**
         * 
         * @param type $idProduit
         * @param type $description
         * @param type $prix
         * @param type $idCateg
         * @return le nombre de lignes concernées par l'ajout
         */
         public function ajouterProduit($idProduit,$description, $prix, $idCateg ){
            $req = "INSERT INTO produit (id, description, prix, idCategorie) VALUES ('$idProduit', '$description', $prix, '$idCateg') ";
            var_dump($req);
            $nbLignes = PdoLafleur::$monPdo->exec($req);
            return ($nbLignes);            
        }
        
         /**
         * Retourne l'id du dernier produit de la catégorie $idCateg saisi en base de données
         * 
         * @param type $leProduit
         */
        public function getIdMaxProduit($idCateg){
            $req = "select MAX(id) as maxId from produit where idCategorie = '$idCateg'";
            $res = PdoLafleur::$monPdo->query($req);
            $laLigne = $res->fetch();
            return $laLigne['maxId'];
        }
}
?>