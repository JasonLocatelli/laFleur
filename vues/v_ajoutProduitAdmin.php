<div id="creationCommande">
<form method="POST" action="index.php?uc=administrer&action=validAjouterArticle">
   <fieldset>

       <legend>Ajout d'un produit de la catégorie <?php echo $laCateg['libelle'] ?></legend>
		<p>			
			<input type="hidden" name="txtIdCateg" size="30" maxlength="45"  value = "<?php echo $laCateg['id']; ?>" >
		</p>
                
                <p>
			<!-- <label for="txtId">Id</label>
                        <input id="txtId" type="text" name="txtId" size="100" maxlength="150" placeholder ="Id du produit"  > -->
                         <input id="txtId" type="hidden" name="txtId" size="100" maxlength="150" value ="<?php echo $nouvelId ;?>" >
		</p>
                <p>
			<label for="txtDescription">Description*</label>
                        <input id="txtDescription" type="text" name="txtDescription" size="100" maxlength="150" placeholder ="Description du produit" >
		</p>
		<p>
			<label for="txtPrix">Prix*</label>
			 <input id="txtPrix" type="text" name="txtPrix" size="30" maxlength="45" placeholder ="Prix du produit" >
		</p>
                <p>* : <em>champs obligatoires</em> </p>  
	<p>
            <input type="submit" value="Valider" name="valider">
            <input type="reset" value="Annuler" name="annuler" onClick="window.location.href='index.php?uc=administrer'"> 
         </p>
</form>
</div>





