<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../css/form-styles.css">
    <title> Supprimer un thème </title>
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


#Récupération de l'idtheme du formulaire
$idtheme = $_POST['idtheme'];
#echo "<br> $idtheme <br>";


#Requête pour récupérer les informations du thème sélectionné
$query1 = "SELECT nom, descriptif FROM themes WHERE idtheme = $idtheme";
#"<br> $query1 <br>";
$result1 = mysqli_query($connect, $query1);
if(!$result1){
  echo "Erreur rencontrée : ".mysqli_error($connect);
  exit;
 }
 #Ligne avec le nom et le descriptif du thème sélectionné
 $theme = mysqli_fetch_array($result1, MYSQLI_NUM);


#Requête pour mettre à jour le thème pour le marquer comme supprimé
$query2 = "UPDATE themes SET supprime = 1 WHERE idtheme = $idtheme";
#"<br> $query2 <br>";
$result2 = mysqli_query($connect, $query2);
if(!$result2){
  echo "Erreur rencontrée : ".mysqli_error($connect);
  exit;
 }


 #Affichage du récapitulatif
 echo "<table>";
 echo "<tr> <td colspan='2' class='header'> <h1> Thème supprimé ! </h1> </td> </tr>";
 echo "<tr> <td colspan='2'> <br> Le thème suivant a été supprimé : </td> </tr>";
 echo "<tr> <td> Nom : </td> <td> $theme[0] </td> </tr>";
 echo "<tr> <td> Descriptif : </td> <td> $theme[1] </td> </tr>";
 echo "<tr> <td colspan = '2'> <a href='../html/accueil.html'>Retour à l'accueil </a> </td> </tr>";
 echo "</table>";


#Fermeture de la BDD
mysqli_close($connect);
?>
</body>
</html>
