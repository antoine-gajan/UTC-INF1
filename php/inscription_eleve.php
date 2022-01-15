<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../css/form-styles.css">
    <title> Inscrire un élève </title>
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


#Requête pour récupérer l'ensemble des séances ayant lieu dans le futur
$query1 = "SELECT idseance, nom, DateSeance FROM seances INNER JOIN themes ON seances.Idtheme=themes.idtheme WHERE DateSeance>='$date' ORDER BY nom, DateSeance";
#Requête pour récupérer l'ensemble des élèves
$query2 = "SELECT * FROM eleves ORDER BY nom, prenom";
#echo "<br> $query1 <br>";
#echo "<br> $query2 <br>";
$result1 = mysqli_query($connect, $query1);
$result2 = mysqli_query($connect, $query2);


#Test si la requête 1 est correcte
if (!$result1){
  echo "Erreur rencontrée : ".mysqli_error($connect);
  exit;
}
#Test si la requête 2 est correcte
if (!$result2){
  echo "Erreur rencontrée : ".mysqli_error($connect);
  exit;
}


#Création du formulaire pour inscrire l'élève à une séance
echo "<form action='inscrire_eleve.php' method='post'>";
echo "<table>";
echo "<tr> <td colspan='2' class='header'> <h1> Inscription d'un elève à une séance </h1> </td> </tr>";
echo "<tr> <td> Choix de la séance : </td>";
echo "<td> <select name='idseance'>";
#Parcours de l'ensemble des séances
while ($seance = mysqli_fetch_array($result1, MYSQLI_NUM)){
  echo "<option value='$seance[0]'> $seance[1] le $seance[2] </option>";
}
echo "</select> </td> </tr>";
echo "<tr> <td> Choix de l'elève : </td>";
echo "<td> <select name='ideleve'>";
#Parcours de l'ensemble des élèves
while ($eleve = mysqli_fetch_array($result2, MYSQLI_NUM)){
  echo "<option value='$eleve[0]'>$eleve[1] $eleve[2]</option>";
}
echo "</select> </td> </tr>";
echo "<tr> <td colspan ='2' class='submit'> <input type ='submit' value ='Inscrire'> </td> </tr>";
echo "</form>";
echo "</table>";


#Fermeture de la BDD
mysqli_close($connect);
?>
</body>
</html>
