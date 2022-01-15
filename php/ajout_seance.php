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


#Requête pour récupérer tous les thèmes actifs
$query = "SELECT * FROM themes WHERE supprime = 0";
#echo "$query";
$result = mysqli_query($connect,$query);

#Test si la requête est correcte
if (!$result){
  echo "Erreur rencontrée : ".mysqli_error($connect);
}


#Création du formulaire
echo "<form method='post' action='ajouter_seance.php' >";
echo "<table>";
echo "<tr> <td colspan='2' class='header'> <h1> Ajouter une séance </h1> </td> </tr>";
#Demande du thème
echo "<tr> <td> Thème : </td>";
echo "<td> <select name='Idtheme' required>";
#Parcours de l'ensemble des thèmes
while ($theme = mysqli_fetch_array($result, MYSQLI_NUM))
{
  #Ajout des différentes options (thèmes)
 echo "<option value='$theme[0]'> $theme[1] </option>";
}
echo "</td> </tr>";
#Demande de la date
echo "<tr> <td> Date de la séance </td>";
echo "<td> <input type='date' name='DateSeance' pattern='[0-9]{4}-[0-9]{2}/[0-9]{2}' min='$date' required> </td> </tr>";
#Demande de l'effectif maximal
echo "<tr> <td> Effectif maximal : </td>";
echo "<td> <input type='number' name='EffMax' placeholder='Effectif' pattern='[0-9]+' min='1' required> </td> </tr>";
#Validation du formulaire
echo "<tr> <td colspan='2' class='submit'> <input type='submit' value='Ajouter la séance'> </td> </tr>";
echo "</table>";
echo "</form>";


#Fermeture de la BDD
mysqli_close($connect);
?>

</body>
</html>
