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


#Récupération de l'ideleve du formulaire
$ideleve = $_POST['ideleve'];
#echo "<br> $ideleve <br>";

#Requête pour récupérer le nom et le prénom de l'élève sélectionné
$query1 = "SELECT nom, prenom FROM eleves WHERE ideleve = $ideleve";
#echo "<br> $query1 <br>";

$result1 = mysqli_query($connect, $query1);
#Test pour debuggage
if (!$result1){
  echo "Erreur rencontrée : ".mysqli_error($connect);
}
#Récupération des informations de l'élève
$eleve = mysqli_fetch_array($result1, MYSQLI_NUM);


#Requête pour récupérer l'ensemble des séances futures auxquelles est inscrit l'élève
$query2 = "SELECT themes.nom, seances.DateSeance FROM inscription INNER JOIN seances ON inscription.idseance = seances.idseance INNER JOIN themes ON seances.Idtheme = themes.idtheme WHERE inscription.ideleve = $ideleve AND '$date' < seances.DateSeance ORDER BY seances.DateSeance";
#echo "<br> $query2 <br>";

$result2 = mysqli_query($connect, $query2);
#Test pour debuggage
if (!$result2){
  echo "Erreur rencontrée : ".mysqli_error($connect);
}
#Nombre de séances futures
$nb_seances = mysqli_num_rows($result2);
#echo "<br> $nb_seances <br>";


#Tableau avec l'ensemble des séances futures de l'élève
echo "<table>";
echo "<tr> <td colspan='2' class='header'> <h1> Prochaines séances de $eleve[1] $eleve[0] </h1> </td> </tr>";

#Si l'utilisateur a des séances auxquelles il est inscrit dans le futur, on les affiche
if ($nb_seances != 0){
  echo "<tr> <td> <b> Thème </b> </td> <td> <b> Date </b> </td> </tr>";
  #Parcours de l'ensemble des séances futures de l'élève
  while ($seance = mysqli_fetch_array($result2, MYSQLI_NUM)){
    #Nom du thème
    echo "<tr> <td> $seance[0] </td>";
    #Date de la séance
    echo "<td> $seance[1] </td> </tr>";
  }
}

#Sinon, on retourne un message indiquant qu'aucune séance n'est prévue
else{
  echo "<tr> <td> $eleve[1] $eleve[0] n'a aucune séance prévue dans le futur.</td> </tr>";
}

echo "<tr> <td colspan='2'> <a href='./visualisation_calendrier_eleve.php'>Retour au choix d'élève </a> <br>";
echo "<br><a href='../html/accueil.html'>Retour à l'accueil </a><br></td></tr>";
echo "</table>";



#Fermeture de la BDD
mysqli_close($connect);
?>

</body>
</html>
