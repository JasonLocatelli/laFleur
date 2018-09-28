<div id="produits">
    <a href=index.php?uc=administrer&categorie=<?php echo $lesProduits[0]['idCategorie']; ?>&action=ajouterArticle >
        Ajouter un produit dans cette catégorie </a> 
<?php

	
foreach( $lesProduits as $unProduit) 
{
    $idProduit = $unProduit['id'];
    $description = $unProduit['description'];   
    ?>	
    <ul>             
        <li><?php echo $idProduit ?>
        <li><?php echo $description ?></li>
        <li><a href=index.php?uc=administrer&idProduit=<?php echo $idProduit ?>&action=modifierArticle> 
            <img src="images/modifier.jpg" title="Modifier" alt ="Modifier" </li></a>	
        <li><a href=index.php?uc=administrer&idProduit=<?php echo $idProduit ?>&action=supprimerArticle> 
            <img src="images/supprimer.png" title="Supprimer" alt ="Supprimer" </li></a>	
    </ul>	
<?php			
}
?>
</div>
