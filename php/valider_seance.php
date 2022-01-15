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


#Requête pour récupérer les noms, prénoms et notes (si elles ont déjà été saisies) des élèves inscrits à la séance choisie
$query = "SELECT eleves.ideleve, nom, prenom, note FROM inscription INNER JOIN eleves ON eleves.ideleve = inscription.ideleve WHERE idseance = $idseance";
#echo "<br> $query <br>";
$result = mysqli_query($connect, $query);
#Test pour débuggage
if (!$result){
  echo "Erreur rencontrée : ".mysqli_error($connect);
  exit;
}
#Calcul du nombre d'élèves ayant participé à la séance
$nb_inscrits = mysqli_num_rows($result);
#echo "<br> $nb_inscrits <br>";


#Si aucun élève inscrit à la séance choisie, message d'erreur
if ($nb_inscrits == 0){
  echo "<table>";
  echo "<tr> <td colspan='2' class='header'> <h1> Aucune saisie de notes possible </h1> </td> </tr>";
  echo "<tr> <td colspan='2'> Aucune saisie de notes n'est possible car aucun élève n'a été inscrit à cette séance.</td> </tr>";
  echo "<tr><td colspan='2'><br><br><a href='validation_seance.php'>Retour au choix de la séance</a><br>";
  echo "<br><a href='../html/accueil.html'>Retour à l'accueil </a><br>";
  echo "</td> </tr>";
  echo "</table>";
}
#Sinon, au moins 1 élève a participé à la séance, donc on peut générer un formulaire pour noter
else{
  echo "<form action='noter_eleves.php' method='post'>";
  echo "<table>";
  echo "<tr> <td colspan='2' class='header'> <h1> Noter les élèves </h1> </td> </tr>";
  #Champ caché contenant l'idseance de la séance sélectionnée
  echo "<input type='hidden' name='idseance' value='$idseance'";
  #Affichage de la liste des élèves ayant participé
  echo "<tr> <td> <b> Liste des élèves </b> </td>";
  echo "<td> <b> Nombre d'erreurs (/40) </b> </td> </tr>";
  #Pour chaque élève ayant assité à la séance
  while ($eleve = mysqli_fetch_array($result, MYSQLI_NUM)){
    #Affichage du nom et du prénom de l'élève
    echo "<tr> <td> $eleve[1] $eleve[2] </td>";
    #Calcul du nombre de fautes en fonction de la note
    $nb_fautes = 40 - $eleve[3]; #Remarque : si la note n'est pas saisie (-1 dans la BDD), alors le nb de fautes est > à 40

    #Affichage d'un champ pour rentrer le nombre d'erreurs

    #Si le nombre de fautes est supérieur à 40, cela signifie que la note est négative (donc pas encore saisie), donc pas de nombre de fautes à afficher dans le champ
    if ($nb_fautes > 40){
      echo "<td> <input type='number' name='$eleve[0]' min='0' max='40' step='1' pattern = '[0-9]{1,2}' placeholder='Valeur'> </td></tr>";

    }
    #Si le nombre de fautes est inférieur à 40, cela signifie que la note est positive (donc saisie par l'utilisateur), on affiche le nombre de fautes dans le champ
    else{
      echo "<td> <input type='number' name='$eleve[0]' min='0' max='40' step='1' value='$nb_fautes' pattern = '[0-9]{1,2}' placeholder='Valeur'> </td></tr>";
    }
  }
  echo "<tr> <td colspan='2' class='submit'> <input type='submit' value='Valider les notes'></input> </td> </tr>";
  echo "</table>";
  echo "</form>";
}


#Fermeture de la BDD
mysqli_close($connect);
?>
</body>
</html>
