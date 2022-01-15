<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../css/form-styles.css">
    <title> Ajouter un élève </title>
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


#Récupération des données cachées et affichage pour débugger
$nom = $_POST['nom'];
$prenom = $_POST['prenom'];
$dateNaiss = $_POST['dateNaiss'];
#echo "<br> Nom : $nom <br>";
#echo "<br> Prénom : $prenom <br>";
#echo "<br> Date naissance : $dateNaiss <br>";


#Requête pour insérer l'élève à la BDD
$query = "INSERT INTO eleves VALUES (NULL, '$nom', '$prenom', '$dateNaiss', '$date')";


#Si l'utilisateur a décidé de poursuivre l'inscription
if (($_POST['verif'] == 'oui')){
  #Inscrit l'élève à la BDD
  $result = mysqli_query($connect, $query);
  #Test pour debuggage
  if (!$result){
    echo "Erreur rencontrée : ".mysqli_error($connect);
    exit;
  }
  echo "<table>";
  #Indique que l'élève est inscrit
  echo "<tr> <td colspan='2' class='header'> <h1> Eleve inscrit ! </h1> </td> </tr>";
  echo "<tr> <td> Nom : </td>";
  echo "<td> $nom </td> </tr>";
  echo "<tr> <td> Prénom : </td>";
  echo "<td> $prenom </td> </tr>";
  echo "<tr> <td> Date de naissance : </td>";
  echo "<td> $dateNaiss </td> </tr>";
  echo "<tr><td><a href='../html/accueil.html'>Retour à l'accueil </a><br></td></tr>";
  echo "</table>";
}
else{
  echo "<table>";
  #Indique que l'inscription est annulée
  echo "<tr> <td colspan='2' class='header'> <h1> Inscription annulée ! </h1> </td> </tr>";
  echo "<tr> <td> Vous avez choisi d'annuler l'inscrption de l'élève. <br> <br>";
  echo "<br><a href='../html/accueil.html'>Retour à l'accueil </a><br></td></tr>";
  echo "</table>";
}

#Fermeture de la base de données
mysqli_close($connect);

?>
  </body>
</html>
