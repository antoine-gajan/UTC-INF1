<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../css/form-styles.css">
    <title> Désinscrire un élève </title>
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
$idseance = $_POST['idseance'];
$ideleve = $_POST['ideleve'];
#echo "<br> Id seance : $idseance <br>";
#echo "<br> Id eleve : $ideleve <br>";


#Requête pour récupérer les informations de l'élève et de la séance
$query1 = "SELECT eleves.nom, eleves.prenom, themes.nom, seances.DateSeance FROM inscription INNER JOIN eleves ON eleves.ideleve = inscription.ideleve INNER JOIN seances ON seances.idseance = inscription.idseance INNER JOIN themes ON themes.idtheme = seances.Idtheme WHERE inscription.idseance = $idseance AND inscription.ideleve = $ideleve";
#echo "<br> $query1 <br>";
$result1 = mysqli_query($connect, $query1);
if (!$result1){
  echo "Erreur rencontrée : ".mysqli_error($connect);
  exit;
}
#Extraction des données souhaitées
$data = mysqli_fetch_array($result1, MYSQLI_NUM);


#Requête pour désinscrire l'élève de la séance
$query2 = "DELETE FROM inscription WHERE ideleve = $ideleve AND idseance = $idseance";
#echo "<br> $query2 <br>";
$result2 = mysqli_query($connect, $query2);
if (!$result2){
  echo "Erreur rencontrée : ".mysqli_error($connect);
  exit;
}

#Affiche le récapitulatif de la désinscription
echo "<table>";
echo "<tr> <td colspan='2' class='header'> <h1> Elève désinscrit ! </h1> </td> </tr>";
echo "<tr> <td colspan='2'> $data[1] $data[0] a été désinscrit de la séance du $data[3] dont le thème est : $data[2].</td> </tr>";
echo "<tr><td colspan='2'><br><a href='desinscription_seance.php'>Retour au choix de la séance</a><br>";
echo "<br><a href='../html/accueil.html'>Retour à l'accueil </a><br>";
echo "</td> </tr>";
echo "</table>";


#Fermeture de la BDD
mysqli_close($connect);
?>
</body>
</html>
