<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../css/form-styles.css">
    <title> Visualiser le calendrier d'un élève </title>
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


#Requête pour sélectionner l'ensemble des élèves
$query = "SELECT ideleve, nom, prenom FROM eleves ORDER BY nom, prenom";
#echo "<br> $query <br>";
$result = mysqli_query($connect, $query);

#Test pour debuggage
if (!$result){
  echo "Erreur rencontrée : ".mysqli_error($connect);
  exit;
}


#Formulaire
echo "<form action='visualiser_calendrier_eleve.php' method='post'>";
echo "<table>";
echo "<tr> <td colspan='2' class='header'> <h1> Visualiser le calendrier d'un élève </h1> </td> </tr>";
echo "<tr> <td> Choix de l'élève : </td>";
echo "<td> <select name='ideleve'>";

#Parcours de l'ensemble des élèves
while ($row = mysqli_fetch_array($result, MYSQLI_NUM)){
    echo "<option value='$row[0]'> $row[1] $row[2]</option>";
}
echo "</select> </td> </tr>";
echo "<tr> <td colspan='2' class='submit'> <input type='submit' value='Choisir cet élève'></input> </td> </tr>";
echo "</table>";
echo "</form>";

#Fermeture de la BDD
mysqli_close($connect);
?>

</body>
</html>
