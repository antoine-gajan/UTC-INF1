<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="../css/form-styles.css">
    <title> Noter les élèves de la séance </title>
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
#echo "<br> Id seance : $idseance <br>";


#Requête pour récupérer l'ensemble des élèves à noter
$query1 = "SELECT eleves.ideleve, nom, prenom FROM inscription INNER JOIN eleves ON eleves.ideleve = inscription.ideleve WHERE idseance = $idseance";
#echo "<br> $query1 <br>";
$result1 = mysqli_query($connect, $query1);

#Test pour debugger
if (!$result1){
  echo "Erreur rencontrée : ".mysqli_error($connect);
  exit;
}


#Mise à jour des notes et affichage du récapitulatif
echo "<table>";
echo "<tr> <td colspan='2' class='header'> <h1> Notes mises à jour ! </h1> </td> </tr>";
echo "<tr> <td colspan='2'> Récapitulatif pour cette séance : </td> </tr>";
echo "<tr> <td> <b> Elève </b> </td>";
echo "<td> <b> Note (/40)</b> </td> </tr>";

#Parcours de chaque élève inscrit à la séance
while ($eleve = mysqli_fetch_array($result1, MYSQLI_NUM)){
  #Récupération de l'ideleve
  $ideleve = $eleve[0];
  #echo "<br> ideleve : $ideleve <br>";

  #Récupération du nombre de fautes de l'élève
  $nb_fautes = $_POST["$ideleve"];
  #echo "<br> Nombre de fautes : $nb_fautes <br>";

  #Si aucune note n'est saisie, alors on ajoute dans la BDD la valeur par défaut : -1
  if (empty($nb_fautes) and $nb_fautes != '0'){
    $query2 = "UPDATE inscription SET note = -1 WHERE idseance = $idseance AND ideleve = $ideleve";
    #echo "<br> $query2 <br>";
    #Modification de la note dans la BDD
    $result2 = mysqli_query($connect, $query2);
    #Test pour debugger
    if (!$result2){
      echo "Erreur rencontrée : ".mysqli_error($connect);
      exit;
    }
    #Affichage du récapitulatif de l'élève et de sa note non saisie
    echo "<tr> <td> $eleve[1] $eleve[2] </td> <td> Note non saisie </td> </tr>";
  }
  #Si l'utilisateur a saisi le nombre de fautes de l'élève
  else{
    #Calcul de la note en fonction du nombre de fautes rentré par l'utilisateur
    $note = 40 - $nb_fautes;
    #Requête pour modifier la note
    $query2 = "UPDATE inscription SET note = $note WHERE idseance = $idseance AND ideleve = $ideleve";
    #echo "<br> $query2 <br>";
    #Modification de la note dans la BDD
    $result2 = mysqli_query($connect, $query2);
    #Test pour debugger
    if (!$result2){
      echo "Erreur rencontrée : ".mysqli_error($connect);
      exit;
    }
    #Affichage du récapitulatif de l'élève et de sa note
    echo "<tr> <td> $eleve[1] $eleve[2] </td> <td> $note </td> </tr>";
  }
}
echo "<tr><td colspan='2'><a href='../html/accueil.html'>Retour à l'accueil </a><br>";
echo "</td> </tr>";
echo "</table>";


#Fermeture de la BDD
mysqli_close($connect);
?>
</body>
</html>
