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
$ideleve = $_POST['ideleve'];
#echo "<br> Id eleve : $ideleve <br>";


#Requête pour récupérer les idseance, noms des thèmes et date des séances auxquelles l'élève choisi est inscrit dans le futur
$query1 = "SELECT inscription.idseance, themes.nom, seances.DateSeance FROM inscription INNER JOIN seances ON inscription.idseance = seances.idseance INNER JOIN themes ON seances.Idtheme = themes.idtheme WHERE inscription.ideleve = $ideleve AND '$date' < seances.DateSeance ORDER BY seances.DateSeance";
#echo "<br> $query1 <br>";
$result1 = mysqli_query($connect, $query1);
if (!$result1){
  echo "Erreur rencontrée : ".mysqli_error($connect);
  exit;
}
#Calcul du nombre de séances auxquelles est inscrit l'élève dans le futur
$nb_seances = mysqli_num_rows($result1);
#echo "<br> $nb_seances <br>";


#Requête pour récupérer le nom et prénom de l'élève sélectionné
$query2 = "SELECT nom, prenom FROM eleves WHERE ideleve = $ideleve";
#echo "<br> $query2 <br>";
$result2 = mysqli_query($connect, $query2);
if (!$result2){
  echo "Erreur rencontrée : ".mysqli_error($connect);
  exit;
}
#Extraction des données de l'élève
$eleve = mysqli_fetch_array($result2, MYSQLI_NUM);


#Si l'élève n'a aucune séance de prévue dans le futur, message d'erreur
if ($nb_seances == 0){
  echo "<table>";
  echo "<tr> <td colspan='2' class='header'> <h1> Aucune désinscription possible ! </h1> </td> </tr>";
  echo "<tr> <td colspan='2'> Aucune désinscription n'est possible car $eleve[1] $eleve[0] n'est inscrit à aucune séance dans le futur.</td> </tr>";
  echo "<tr><td><br><br><a href='desinscription_seance.php'>Retour à la page du choix de l'élève </a><br>";
  echo "<br><a href='../html/accueil.html'>Retour à l'accueil </a><br>";
  echo "</td> </tr>";
  echo "</table>";
}

#Sinon, il peut être désinscrit d'une séance à laquelle il est inscrit
else{
  echo "<form action='enlever_eleve.php' method='post'>";
  echo "<table>";
  echo "<tr> <td colspan='2' class='header'> <h1> Désinscrire un élève d'une séance </h1> </td> </tr>";
  echo "<tr> <td> Elève choisi : </td>";
  echo "<td> $eleve[1] $eleve[0]";
  #Champ caché contenant l'ideleve de l'élève choisi précédemment
  echo "<input type='hidden' name='ideleve' value='$ideleve'> </td> </tr>";
  echo "<tr> <td> Choix de la séance : </td>";
  echo "<td> <select name='idseance'>";
  #Parcours des séances auxquelles est inscrit l'élève
  while ($seance = mysqli_fetch_array($result1, MYSQLI_NUM)){
    #Affichage du nom du thème et de la date de la séance
    echo "<option value='$seance[0]'> $seance[1] du $seance[2] </option>";
    }
  echo "</select> </td> </tr>";
  echo "<tr> <td colspan='2' class='submit'> <input type='submit' value='Désinscrire $eleve[1] $eleve[0] de la séance'></input> </td> </tr>";
  echo "</table>";
  echo "</form>";
}


#Fermeture de la BDD
mysqli_close($connect);
?>
</body>
</html>
