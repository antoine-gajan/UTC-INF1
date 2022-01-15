<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../css/form-styles.css">
    <title> Choisir une séance </title>
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


#Requêtes pour récupérer toutes les séances ayant eu lieu jusqu'à aujourd'hui
$query = "SELECT idseance, nom, DateSeance FROM seances INNER JOIN themes on seances.Idtheme = themes.idtheme WHERE DateSeance < '$date' ORDER BY DateSeance DESC";
#echo "<br> $query <br>";
$result = mysqli_query($connect, $query);
#Test pour debuggage
if (!$result){
  echo "Erreur rencontrée : ".mysqli_error($connect);
  exit;
}


#Formulaire pour sélectionner une séance
echo "<form action='valider_seance.php' method='post'>";
echo "<table>";
echo "<tr> <td colspan='2' class='header'> <h1> Noter les élèves </h1> </td> </tr>";
echo "<tr> <td> Choix de la séance : </td>";
echo "<td> <select name='idseance'>";
#Parcours de l'ensemble des séances ayant eu lieu jusqu'à aujourd'hui
while ($seance = mysqli_fetch_array($result, MYSQLI_NUM)){
    echo "<option value='$seance[0]'> $seance[1] du $seance[2]</option>";
}
echo "</select> </td> </tr>";
echo "<tr> <td colspan='2' class='submit'> <input type='submit' value='Selectionner cette séance'></input> </td> </tr>";
echo "</table>";
echo "</form>";

#Fermeture de la BDD
mysqli_close($connect);
?>
</body>
</html>
