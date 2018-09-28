<div id="creationCommande">
<form method="POST" action="index.php?uc=administrer&action=validSupprimerArticle">
   <fieldset>
     <legend>Suppression d'un produit </legend>
            <p>			
		<input type="text" name="txtId" size="30" maxlength="45"  hidden value = "<?php echo $idProduit; ?>" >
            </p>
		<p>
			Supprimer ? 
		</p>		
	<p>
            <input type="submit" value="Supprimer" name="valider">
            <input type="submit" value="Annuler" name="annuler"><!-- ou alors gestion de l'annulation en javascript -->
            
         </p>
</form>
</div>





