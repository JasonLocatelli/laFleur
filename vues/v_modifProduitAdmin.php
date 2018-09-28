<div id="creationCommande">
<form method="POST" action="index.php?uc=administrer&action=validModifierArticle">
   <fieldset>

       <legend>Modification d'un produit</legend>
		<p>			
			<input type="text" name="txtId" size="30" maxlength="45"  hidden value = "<?php echo $leProduit['id']; ?>" >
		</p>
                <p>
			<label for="txtDescription">Description</label>
                        <input id="txtDescription" type="text" name="txtDescription" size="100" maxlength="150" value = "<?php echo $leProduit['description']; ?>" required>
		</p>
		<p>
			<label for="txtPrix">Prix</label>
			 <input id="txtPrix" type="text" name="txtPrix" size="30" maxlength="45" value = "<?php echo $leProduit['prix']; ?>" required>
		</p>
	<p>
            <input type="submit" value="Valider" name="valider">
            <input type="submit" value="Annuler" name="annuler">
            <!-- ou alors en javascript => pas de traitement du coté serveur -->
           <!--  <input type="reset" value="Annuler" name="annuler" onClick="window.location.href='index.php?uc=administrer'"> -->
         </p>
</form>
</div>





