<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../css/form-styles.css">
    <title> Ajouter une séance</title>
  </head>

  <body>

<?php

#Mise en place du fuseau horaire et de la date du jour
date_default_timezone_set('Europe/Paris');
$date = date("Y\-m\-d");
#echo "<br> la date est : ".$date."<br>";


#Connexion à la base de données
$dbhost = 'tuxa.sme.utc';
$dbuser = 'nf92a028';
$dbpass = 'gteQqX3Y';
$dbname = 'nf92a028';
$connect = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname) or die ('Error connecting to mysql');


#Les données envoyées vers mysql sont encodées en UTF-8
mysqli_set_charset($connect, 'utf8');


#Récupération des données et affichage pour débugger
$Idtheme = $_POST['Idtheme'];
$DateSeance = $_POST['DateSeance'];
$EffMax = $_POST['EffMax'];
#echo "<br> Id thème : $Idtheme <br>";
#echo "<br> Date séance : $DateSeance <br>";
#echo "<br> Effectif maximal : $Idtheme <br>";


#Requête pour récupérer le nom du thème choisi
$query1 = "SELECT nom FROM themes WHERE idtheme = $Idtheme";
#echo "<br> $query1 <br>";

#Requête pour récupérer les infos de la séance du même thème le même jour si elle existe
$query2 = "SELECT themes.nom, EffMax FROM seances INNER JOIN themes ON seances.Idtheme = themes.idtheme WHERE DateSeance = '$DateSeance' and seances.Idtheme = $Idtheme";
#echo "<br> $query2 <br>";

#Requête pour insérer la nouvelle séance dans la table séances
$query3 = "INSERT INTO seances VALUES (NULL, '$DateSeance', '$EffMax', '$Idtheme')";
#echo "<br> $query3 <br>";


$result1 = mysqli_query($connect, $query1);
$result2 = mysqli_query($connect, $query2);

#Test si les requêtes sont correctes
if (!$result1){
  echo "Erreur rencontrée : ".mysqli_error($connect);
  exit;
}
#Extraction du nom du thème sélectionné
$nom_theme = mysqli_fetch_array($result1, MYSQLI_NUM)[0];

if (!$result2){
  echo "Erreur rencontrée : ".mysqli_error($connect);
  exit;
}
#Nombre de séances du thème choisi ayant lieu le même jour
$nb_seances = mysqli_num_rows($result2);


#Si aucune autre séance ne possède les mêmes caractéristiques
if ($nb_seances == 0){
  #Ajoute la séance à la BDD
  $result3 = mysqli_query($connect, $query3);
  #Test si l'ajout s'est fait correctement
  if (!$result3){
    echo "Erreur rencontrée : ".mysqli_error($connect);
    exit;
  }

  #Tableau pour indiquer que la séance est ajoutée à la BDD
  echo "<table>";
  echo "<tr> <td colspan='2' class='header'> <h1> Séance ajoutée ! </h1> </td> </tr>";
  echo "<tr> <td> Thème de la séance : </td>";
  #Récapitulatif de la séance ajoutée
  echo "<td> $nom_theme </td> </tr>";
  echo "<tr> <td> Date : </td>";
  echo "<td> $DateSeance </td> </tr>";
  echo "<tr> <td> Effectif maximal : </td>";
  echo "<td> $EffMax </td> </tr>";
  echo "<tr> <td colspan='2'><br><a href='../html/accueil.html'>Retour à l'accueil </a><br></td></tr>";
  echo "</table>";
}

#Si une autre séance du même thème est programmée le même jour, on génère un message d'erreur
else{
  echo "<table>";
  #Extraction des informations de la séance programmée le même jour
  $seance = mysqli_fetch_array($result2, MYSQLI_NUM);
  echo "<tr> <td colspan='2' class='header'> <h1> Problème rencontré ! </h1> </td> </tr>";
  #Affichage du récapitulatif de la séance déjà programmée
  echo "<tr> <td colspan='2'>Une séance de ce thème est déjà organisé le $DateSeance ! <br><br> Récapitulatif de la séance : </td> </tr>";
  echo "<tr> <td> Thème de la séance : </td>";
  echo "<td> $seance[0] </td> </tr>";
  echo "<tr> <td> Date : </td>";
  echo "<td> $DateSeance </td> </tr>";
  echo "<tr> <td> Effectif maximal : </td>";
  echo "<td> $seance[1] </td> </tr>";
  echo "<tr> <td colspan='2'><a href='ajout_seance.php'>Retour à la page d'ajout de la séance</a><br>";
  echo "<br><a href='../html/accueil.html'>Retour à l'accueil </a><br></td></tr>";
  echo "</table>";
}


#Fermeture de la base de données
mysqli_close($connect);

?>
  </body>
</html>
