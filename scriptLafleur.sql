#-- Base de données lafleur ---------------------------------------------------
# -----------------------------------------------------------------------------
#       TABLE : produit
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS produit
 (
   id CHAR (32) NOT NULL  ,
  description CHAR(50), 
   prix DECIMAL (10,2) NULL  ,
   image CHAR (32) NULL, 
  
    idCategorie CHAR (32) NOT NULL 
   , PRIMARY KEY (id) 
 ) 
 ENGINE=InnoDB;

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE produit
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_Produit_CATEGORIE
     ON Produit (idCategorie ASC);

# -----------------------------------------------------------------------------
#       TABLE : COMMANDE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS commande
 (
   id CHAR (32) NOT NULL  ,
   dateCommande DATE NULL  ,
   nomPrenomClient CHAR (32) NULL  ,
   adresseRueClient CHAR (32) NULL  ,
   cpClient CHAR (5) NULL  ,
   villeClient CHAR (32) NULL,
	mailClient CHAR(50)   
   , PRIMARY KEY (id) 
 ) 
ENGINE=InnoDB;

# -----------------------------------------------------------------------------
#       TABLE : CATEGORIE
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS categorie
 (
   id CHAR (32) NOT NULL  ,
   libelle CHAR (32) NULL  
   , PRIMARY KEY (id) 
 ) 
 ENGINE=InnoDB;

# -----------------------------------------------------------------------------
#       TABLE : CONTENIR
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS contenir
 (
	idCommande CHAR (32) NOT NULL ,
   idProduit CHAR (32) NOT NULL  
   
  
   , PRIMARY KEY (idCommande,idProduit) 
 ) 
 ENGINE=InnoDB;

# -----------------------------------------------------------------------------
#       INDEX DE LA TABLE CONTENIR
# -----------------------------------------------------------------------------


CREATE  INDEX I_FK_CONTENIR_COMMANDE
     ON contenir (idCommande ASC);

CREATE  INDEX I_FK_CONTENIR_Produit
     ON contenir (idProduit ASC);


# -----------------------------------------------------------------------------
#       CREATION DES REFERENCES DE TABLE
# -----------------------------------------------------------------------------


ALTER TABLE produit 
  ADD FOREIGN KEY FK_Produit_CATEGORIE (idCategorie)
      REFERENCES categorie (id) ;


ALTER TABLE contenir 
  ADD FOREIGN KEY FK_CONTENIR_COMMANDE (idCommande)
      REFERENCES commande (id) ;


ALTER TABLE contenir 
  ADD FOREIGN KEY FK_CONTENIR_Produit (idProduit)
      REFERENCES produit (id) ;

# -----------------------------------------------------------------------------
#       TABLE : ADMIN
# -----------------------------------------------------------------------------

CREATE TABLE IF NOT EXISTS administrateur
 (
	id CHAR (3) NOT NULL ,
    nom  CHAR (32) NOT NULL ,
    mdp CHAR(32) NOT NULL 	
    , PRIMARY KEY (id) 
 )
 ENGINE=InnoDB; 

	  
# -----------------------------------------------------------------------------
#       CREATION DES LIGNES DES TABLES
# -----------------------------------------------------------------------------
INSERT INTO categorie VALUES ('fle','Fleurs');
INSERT INTO categorie VALUES ('pla','Plantes');
INSERT INTO categorie VALUES ('com','Composition');

INSERT INTO produit VALUES ('f01','Bouquet de roses multicolores',58,'images/fleurs/comores.gif','fle');
INSERT INTO produit VALUES ('f02','Bouquet de roses rouges',50,'images/fleurs/grenadines.gif','fle');
INSERT INTO produit VALUES ('f03','Bouquet de roses jaunes',78,'images/fleurs/mariejaune.gif','fle');
INSERT INTO produit VALUES ('f04','Bouquet de petites roses jaunes',48,'images/fleurs/mayotte.gif','fle');
INSERT INTO produit VALUES ('f05','Fuseau de roses multicolores',63,'images/fleurs/philippines.gif','fle');
INSERT INTO produit VALUES ('f06','Petit bouquet de roses roses',43,'images/fleurs/pakopoka.gif','fle');
INSERT INTO produit VALUES ('f07','Panier de roses multicolores',78,'images/fleurs/seychelles.gif','fle');

INSERT INTO produit VALUES ('c01','Panier de fleurs variées',53,'images/compo/aniwa.gif','com');
INSERT INTO produit VALUES ('c02','Coup de charme jaune',38,'images/compo/kos.gif','com');
INSERT INTO produit VALUES ('c03','Bel arrangement de fleurs de saison',68,'images/compo/loth.gif','com');
INSERT INTO produit VALUES ('c04','Coup de charme vert',41,'images/compo/luzon.gif','com');
INSERT INTO produit VALUES ('c05','Très beau panier de fleurs précieuses',98,'images/compo/makin.gif','com');
INSERT INTO produit VALUES ('c06','Bel assemblage de fleurs précieuses',68,'images/compo/mosso.gif','com');
INSERT INTO produit VALUES ('c07','Présentation prestigieuse',128,'images/compo/rawaki.gif','com');

INSERT INTO produit VALUES ('p01','Plante fleurie',43,'images/plantes/antharium.gif','pla');
INSERT INTO produit VALUES ('p02','Pot de phalaonopsis',58,'images/plantes/galante.gif','pla');
INSERT INTO produit VALUES ('p03','Assemblage paysagé',103,'images/plantes/lifou.gif','pla');
INSERT INTO produit VALUES ('p04','Belle coupe de plantes blanches',128,'images/plantes/losloque.gif','pla');
INSERT INTO produit VALUES ('p05','Pot de mitonia mauve',83,'images/plantes/papouasi.gif','pla');
INSERT INTO produit VALUES ('p06','Pot de phalaonopsis blanc',58,'images/plantes/pionosa.gif','pla');
INSERT INTO produit VALUES ('p07','Pot de phalaonopsis rose mauve',58,'images/plantes/sabana.gif','pla');



INSERT INTO commande VALUES ('1101461660','2011-07-12','Dupont Jacques','12, rue haute','75001','Paris','dupont@wanadoo.fr');
INSERT INTO commande VALUES ('1101461665','2011-07-20','Durant Yves','23, rue des ombres','75012','Paris','durant@free.fr');

INSERT INTO contenir VALUES ('1101461660','p01');
INSERT INTO contenir VALUES ('1101461660','f03');
INSERT INTO contenir VALUES ('1101461665','p06');
INSERT INTO contenir VALUES ('1101461665','f05');

INSERT INTO administrateur VALUES ('1','toto','toto');
INSERT INTO administrateur VALUES ('2','titi','titi');