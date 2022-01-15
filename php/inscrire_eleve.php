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


#Récupération des données et affichage pour débugger
$idseance = $_POST["idseance"];
$ideleve = $_POST["ideleve"];
#echo "<br> $idseance <br>";
#echo "<br> $ideleve <br>";



#Requête pour savoir si l'élève est déjà inscrit à cette séance
$query1 = "SELECT * FROM inscription WHERE idseance=$idseance AND ideleve=$ideleve";
#echo "<br> $query1 <br>";
$result1 = mysqli_query($connect, $query1);
#Test pour débuggage
if (!$result1){
  echo "Erreur rencontrée : ".mysqli_error($connect);
}
#Nombre de lignes retourné par la requête 1
$nb = mysqli_num_rows($result1);


#Requête pour récupérer les données de l'élève
$query2 = "SELECT nom, prenom FROM eleves WHERE ideleve = $ideleve";
#echo "<br> $query2 <br>";
$result2 = mysqli_query($connect, $query2);
#Test pour débuggage
if (!$result2){
  echo "Erreur rencontrée : ".mysqli_error($connect);
}
#Extraction des données de l'élève
$eleve = mysqli_fetch_array($result2, MYSQLI_NUM);


#Requête pour récupérer les informations de la séance (nom du thème, date et effectif maximal)
$query3 = "SELECT nom, DateSeance, EffMax FROM seances INNER JOIN themes ON seances.Idtheme = themes.idtheme WHERE idseance = $idseance";
#echo "<br> $query3 <br>";
$result3 = mysqli_query($connect, $query3);
if (!$result3){
  echo "Erreur rencontrée : ".mysqli_error($connect);
}
#Extraction des données de la séance
$seance = mysqli_fetch_array($result3, MYSQLI_NUM);


#Requête pour récupérer les personnes inscrites à la séance
$query4 = "SELECT * FROM inscription WHERE idseance = $idseance";
#echo "<br> $query4 <br>";
$result4 = mysqli_query($connect, $query4);
if (!$result4){
  echo "Erreur rencontrée : ".mysqli_error($connect);
}
#Extraction du nombre d'inscrits à la séance sélectionnée
$nb_inscrits = mysqli_num_rows($result4);


#Requête pour ajouter l'élève à la séance
$query5 = "INSERT INTO inscription VALUES($idseance, $ideleve, -1)";
#echo "<br> $query5 <br>";


#Si le nombre de résultat de la requête 1 est différent de 0, l'élève est déjà inscrit à la séance : il y a un problème
if ($nb != 0){
  echo "<table>";
  echo "<tr> <td colspan='2' class='header'> <h1> Problème rencontré ! </h1> </td> </tr>";
  echo "<tr> <td colspan='2'> <br> Cet élève est déjà inscrit à cette séance.<br>";
  echo "<br> L'inscription ne peut donc être poursuivie.";
  echo "<br><br><a href='inscription_eleve.php'>Retour à la page d'inscription de l'élève</a><br>";
  echo "<br><a href='../html/accueil.html'>Retour à l'accueil </a><br>";
  echo "</td> </tr>";
  echo "</table>";
}
#Si le nombre d'inscrits à la séance est inférieur à l'effectif maximal, c'est bon
elseif ($nb_inscrits < $seance[2]){
  #Inscription de l'élève
  mysqli_query($connect, $query5);
  #Affichage du récapitulatif
  echo "<table>";
  echo "<tr> <td colspan='2' class='header'> <h1> Eleve inscrit ! </h1> </td> </tr>";
  echo "<tr> <td colspan='2'> <br> L'élève $eleve[1] $eleve[0] vient d'être inscrit à la séance";
  echo " $seance[0] du $seance[1].<br>";
  echo "<br><a href='../html/accueil.html'>Retour à l'accueil </a><br>";
  echo "</td> </tr>";
  echo "</table>";
}
#Sinon, la séance est déjà complète
else{
  echo "<table>";
  echo "<tr> <td colspan='2' class='header'> <h1> Problème rencontré ! </h1> </td> </tr>";
  echo "<tr> <td colspan='2'> <br> Cette séance est déjà complète !<br>";
  echo "<br> L'inscription ne peut donc être poursuivie.";
  echo "<br><br><a href='inscription_eleve.php'>Retour à la page d'inscription de l'élève</a><br>";
  echo "<br><a href='../html/accueil.html'>Retour à l'accueil </a><br>";
  echo "</td> </tr>";
  echo "</table>";
}

#Fermeture de la BDD
mysqli_close($connect);
?>
</body>
</html>
