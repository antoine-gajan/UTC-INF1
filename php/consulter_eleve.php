<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../css/form-styles.css">
    <title> Consulter un élève </title>
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


#Requête pour récupérer les informations essentielles de l'élève
$query1 = "SELECT nom, prenom, dateNaiss, dateInscription FROM eleves WHERE ideleve = $ideleve";
#echo "<br> $query1 <br>";
$result1 = mysqli_query($connect, $query1);

#Test pour débuggage
if (!$result1){
  echo "Erreur rencontrée : ".mysqli_error($connect);
  exit;
}
#Extraction des données de l'élève
$eleve = mysqli_fetch_array($result1, MYSQLI_NUM);


#Requête pour récupérer l'ensemble des couples (nom du thème / note) pour les séances auxquelles l'élève a participé jusqu'à aujourd'hui
$query2 = "SELECT themes.nom, DateSeance, inscription.note FROM inscription INNER JOIN seances ON seances.idseance = inscription.idseance INNER JOIN themes ON themes.idtheme = seances.Idtheme WHERE inscription.ideleve = $ideleve AND DateSeance < '$date' ORDER BY DateSeance DESC";
#echo "<br> $query2 <br>";
$result2 = mysqli_query($connect, $query2);
#Test pour débuggage
if (!$result2){
  echo "Erreur rencontrée : ".mysqli_error($connect);
  exit;
}
#Nombre de séances auxquelles l'utilisateur a participé
$nb_seances_effectuees = mysqli_num_rows($result2);


#Requête pour récupérer les séances auxquelles il est inscrit dans le futur
$query3 = "SELECT * FROM inscription INNER JOIN seances ON seances.idseance = inscription.idseance INNER JOIN themes ON themes.idtheme = seances.Idtheme WHERE inscription.ideleve = $ideleve AND DateSeance > '$date' ORDER BY DateSeance";
#echo "<br> $query3 <br>";
$result3 = mysqli_query($connect, $query3);
#Test pour débuggage
if (!$result3){
  echo "Erreur rencontrée : ".mysqli_error($connect);
  exit;
}
#Nombre de séances auxquelles l'utilisateur est inscrit dans le futur
$nb_seances_futur = mysqli_num_rows($result3);


#Tableau avec l'ensemble des informations de l'élève
echo "<table>";
echo "<tr> <td colspan='2' class='header'> <h1> Informations sur $eleve[1] $eleve[0] </h1> </td> </tr>";

#Première sous partie avec les informations essentielles de l'élève
echo "<tr> <td colspan='2'> <b> Informations essentielles de l'élève : </b> </td> </tr>";
echo "<tr> <td> Nom : </td>";
echo "<td> $eleve[0] </td> </tr>";
echo "<tr> <td> Prénom : </td>";
echo "<td> $eleve[1] </td> </tr>";
echo "<tr> <td> Date de naissance : </td>";
echo "<td> $eleve[2] </td> </tr>";
echo "<tr> <td> Date d'inscription : </td>";
echo "<td> $eleve[3] </td> </tr>";
echo "<tr> <td> Nombre de séances dans le futur : </td>";
echo "<td> $nb_seances_futur </td> </tr>";
echo "<tr> <td> Nombre de séances effectuées : <br> <br> </td>";
echo "<td> $nb_seances_effectuees <br> <br> </td> </tr>";


#Deuxième sous partie avec les notes de l'élève aux séances auxquelles il a assisté jusque maintenant (s'il a participé a au moins une séance jusque maintenant !)
if ($nb_seances_effectuees != 0){
  echo "<tr> <td colspan='2'> <b> Précédents résultats : </b> </td> </tr>";

  echo "<tr> <td> <b> Thème et date </b> </td>";
  echo "<td> <b> Note </b> </td>";
  #Parcours des séances effectuées jusqu'à aujourd'hui
  while($seance = mysqli_fetch_array($result2, MYSQLI_NUM)){
    echo "<tr> <td> $seance[0] du $seance[1] </td>";
    #Si la note pour la séance n'a pas encore été ajoutée dans la BDD, on mentionne "note non renseignée"
    if ($seance[2] == -1){
      echo "<td> Non renseignée </td> </tr>";
    }
    #Sinon, on indique la note
    else{
      echo "<td> $seance[2] </td> </tr>";
      }
  }
}
echo "<tr> <td colspan='2'><br><br><a href='./consultation_eleve.php'>Retour au choix d'élève </a> <br>";
echo "<br><a href='../html/accueil.html'>Retour à l'accueil </a><br> </td></tr>";
echo "</table>";


#Fermeture de la BDD
mysqli_close($connect);
?>

</body>
</html>
