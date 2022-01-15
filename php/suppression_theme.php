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


#Requête pour récupérer l'ensemble des couples idthème / nom de chacun des thèmes
$query = "SELECT idtheme, nom FROM themes";
#"<br> $query <br>";
$result = mysqli_query($connect, $query);

if (!$result){
  echo "Erreur rencontrée : ".mysqli_error($connect);
}


#Création du formulaire
echo "<form action='supprimer_theme.php' method='post'>";
echo "<table>";
echo "<tr> <td colspan='2' class='header'> <h1> Suppression d'un thème </h1> </td> </tr>";
echo "<tr> <td> Choix du thème : </td>";
echo "<td> <select name='idtheme'>";
#Parcours de l'ensemble des séances
while ($theme = mysqli_fetch_array($result, MYSQLI_NUM)){
  echo "<option value='$theme[0]'> $theme[1] </option>";
}
echo "</select> </td> </tr>";
echo "<tr> <td colspan ='2' class='submit'> <input type ='submit' value ='Supprimer le thème'> </td> </tr>";
echo "</form>";
echo "</table>";


#Fermeture de la BDD
mysqli_close($connect);
?>

</body>
</html>
