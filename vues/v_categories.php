<ul id="categories">
<?php
foreach( $lesCategories as $uneCategorie) 
{
	$idCategorie = $uneCategorie['id'];
	$libCategorie = $uneCategorie['libelle'];
	?>
	<li>
		<a href=index.php?uc=voirProduits&categorie=<?php echo $idCategorie ?>&action=voirProduits><?php echo $libCategorie ?></a>
	</li>
<?php
}
?>
</ul>
