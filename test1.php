<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
		<link rel="stylesheet" href="style1.css" />
        <title>Test</title>
    </head>
 
    <body>
    	<?php // Constantes et variables utiles
    		$mysize = 200; //Nombre de barres pour le graph
    		$color = "#FF0000"; //Color pour les barres
    		$c = 0; //Pour count dans les boucles
    		$bool = 0; //Pour booléen dans les boucles 
    	?>

		<?php // Get webpage, mise de la page dans un array
			$freepage = file_get_contents("http://mafreebox.freebox.fr/pub/fbx_info.txt");

			/* Met freepage dans un array */
			$tab = explode (" ", $freepage);

			/* supr ligne vide de l'array */
			foreach ($tab as $k => $v) 
			{
		        if (empty($v))
				unset($tab[$k]);
			}

			/* re index array */
			$tab = array_merge($tab);

			/* Affichage tableau entier */ 
			// ini_set('xdebug.var_display_max_depth', 5);
			// ini_set('xdebug.var_display_max_children', 1024);
			// ini_set('xdebug.var_display_max_data', 1024);
			// var_dump(array_filter($tab));
		?>

		<?php // Connexion à la BDD, insertion data en BDD
            try /* Test de connexion à la BDD */ 
            {
                $bdd = new PDO('mysql:host=localhost;dbname=test', 'root', '', 
                array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
            }
            catch (Exception $e) /* Execption */
            {
                    die('Erreur : ' . $e->getMessage());
            }

            // insertion de la data dans la BDD
            $requete = $bdd->prepare('INSERT INTO debit_free(id, d_in, d_out, d_date) 
            	VALUES (:id, :d_in, :d_out, NOW())');
    				$requete->execute(array(
        				'id' => '',
        				'd_in' => $tab[383],
        				'd_out' => $tab[385],
        			));
        ?>

 		<?php // Affichage Débit Entrant/Sortant et Affichage GRAPH 
 
        	/* Debit Entrant */
	        	$c = 1;
	        	$bool = 0;
	        	while ($c < 10) // La valeur est elle un nombre
	        	{
	        		if ($tab[383][0] == $c)
	        			$bool = 1;
	        		$c++;
	        	};
	        	if ($bool == 0) // SI valeur != nombre ALORS valeur = 0
	        		$tab[383] = 0;
        	// Affichage Débit Entrant
				echo "<p>" . "Débit" . " " . "entrant" . " = " 
				. $tab[383] . " ko/s<br />" . "</p>";

        	//GRAPH Débit Entrant
				// requete pour select un nombre (my_size) d'info en BDD 
				$req = $bdd->prepare('SELECT id, d_in, d_out FROM debit_free 
                    ORDER BY ID DESC LIMIT :my_size OFFSET 0');
                $req->bindValue(':my_size', $mysize, PDO::PARAM_INT);
                $req->execute();

                echo('<div class="conteneur">');
                while ($donnees = $req->fetch()) 
                {
                	// color en fonction de id pair / impair
	                	if($donnees['id']%2 == 0)
	                	{
	                		$color = "#4F545F";
	                	}
	                	else
	                		$color = "#6B799F";

                	// affichage div d'un hauteur "d_in"
                	// et d'une largeur en fonction de mysize
	                    echo '<div class="element" style="height: ' 
	                    . htmlspecialchars($donnees['d_in']) 
	                    . 'px; width: ' 
	                    . htmlspecialchars(100 / $mysize) 
	                    . '%; background-color: ' 
	                    . htmlspecialchars($color) 
	                    . '";"></div>';
                }
                echo('</div>');
		
       		/* Débit Sortant*/
	        	$c = 1;
	        	$bool = 0;
	        	while ($c < 10) // La valeur est elle un nombre
	        	{
	        		if ($tab[385][0] == $c)
	        			$bool = 1;
	        		$c++;
	        	};
	        	if ($bool == 0) // SI valeur != nombre ALORS valeur = 0
	        		$tab[385] = 0;
        	// Affichage Débit Sortant
				echo "<p>" . "Débit" . " " . "sortant" . " = " 
				. $tab[385] . " ko/s<br />" . "</p>";

			//GRAPH Débit Sortant
				// requete pour select un nombre (my_size) d'info en BDD 
				$req = $bdd->prepare('SELECT id, d_in, d_out FROM debit_free 
                    ORDER BY ID DESC LIMIT :my_size OFFSET 0');
                $req->bindValue(':my_size', $mysize, PDO::PARAM_INT);
                $req->execute();

                echo('<div class="conteneur">');
                while ($donnees = $req->fetch()) 
                {
                	// color en fonction de id pair / impair
	                	if($donnees['id']%2 == 0)
	                	{
	                		$color = "#4F545F";
	                	}
	                	else
	                		$color = "#6B799F";

                	// affichage div d'un hauteur "d_in"
                	// et d'une largeur en fonction de mysize
	                    echo '<div class="element" style="height: ' 
	                    . htmlspecialchars($donnees['d_out']) 
	                    . 'px; width: ' 
	                    . htmlspecialchars(100 / $mysize) 
	                    . '%; background-color: ' 
	                    . htmlspecialchars($color) 
	                    . '";"></div>';
                }
                echo('</div>');

		?>

		</div>	

    </body>
</html>