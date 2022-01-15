<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title> Ajouter un élève </title>
    <link rel="stylesheet" type="text/css" href="../css/form-styles.css">
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


#Initialisation d'un tableau contenant la liste des erreurs rencontrées
$erreurs = array();
#Test si les champs sont non vides et affichage des informations de l'utilisateur pour débugger
if (empty($_POST["nom"]))
  {
    #Ajout de l'erreur (nom vide) dans le tableau des erreurs
    $erreurs[] = 'Le champ "nom" ne peut être vide.';
  }
  else
  {
    #Convertir le nom en majuscule
    $nom = strtoupper($_POST["nom"]);
    $nom = mysqli_real_escape_string($connect, $nom);
    #echo "<br>Votre nom est : ".$nom."<br>";
  }

if (empty($_POST["prenom"]))
  {
    #Ajout de l'erreur (prénom vide) dans le tableau des erreurs
    $erreurs[] = 'Le champ "prénom" ne peut être vide.';
  }
  else
  {
    #Toutes les lettres du prénom sont converties en minuscule sauf la première qui est une majuscule
    $prenom = strtolower($_POST["prenom"]);
    $prenom = ucfirst($prenom);
    $prenom = mysqli_real_escape_string($connect, $prenom);
    #echo "<br>Votre prénom est : ".$prenom."<br>";
  }

if(empty($_POST["dateNaiss"]))
  {
    #Ajout de l'erreur (date de naissance vide) dans le tableau des erreurs
    $erreurs[] = 'Le champ "Date de naissance" ne peut être vide.';
  }
  elseif ($_POST["dateNaiss"] > $date) {
    #Ajout d'une erreur car la personne doit être déjà née
    $erreurs[] = 'Votre date de naissance est supérieure à la date d\'aujourd\'hui !';
  }
  else
  {
    $dateNaiss = $_POST["dateNaiss"];
    $dateNaiss = mysqli_real_escape_string($connect, $dateNaiss);
    #echo "<br>Votre date de naissance est : ".$dateNaiss."<br>";
  }


#Si une erreur a été rencontrée, on affiche la liste des erreurs et on quitte le programme php
if (!empty($erreurs))
  {
    echo "<table>";
    echo "<tr> <td colspan='2' class='header'> <h1> Problème(s) rencontré(s) ! </h1> </td> </tr>";
    echo "<tr> <td colspan='2'> L'inscription de l'élève ne peut être poursuivie car : <br> <br>";
    echo "<ul>";
    foreach ($erreurs as $erreur) {
      echo "<li> $erreur </li>";
    }
    echo "</ul>";
    #Liens pour repartir vers l'accueil uo la page d'inscription de l'élève
    echo "<br><br><a href='../html/ajout_eleve.html'>Retour à la page d'inscription de l'élève</a><br>";
    echo "<br><a href='../html/accueil.html'>Retour à l'accueil </a><br>";
    echo "</td> </tr>";
    echo "</table>";
    exit;
  }


#Requête pour voir s'il y a des possibles homonymies dans la BDD
$query1 = "SELECT * FROM eleves WHERE nom='$nom' and prenom='$prenom'";

#Requête pour insérer l'élève dans la BDD
$query2 = "INSERT INTO eleves VALUES (NULL, '$nom', '$prenom', '$dateNaiss', '$date')";
#echo "<br>$query1<br>";
#echo "<br>$query2<br>";

#On regarde si un utilisateur ayant les mêmes caractéristiques existe déjà pour éviter les doublons par erreur de l'utilisateur
$result1 = mysqli_query($connect, $query1);

#Test si la requête 1 est correcte
if (!$result1){
  echo "Erreur rencontrée : ".mysqli_error($connect);
  exit;
}

#On compte le nombre de lignes retournées par le résultat : si un utilisateur ayant les mêmes caractéristiques existe déjà, alors la valeur obtenue sera différente de 0
$compteur = mysqli_num_rows($result1);

#On regarde si aucun élève portant le même nom n'est déjà inscrit
if ($compteur == 0){
  #Inscrit l'élève à la BDD
  $result2 = mysqli_query($connect, $query2);
  #Test si la requête est correcte
  if (!$result2){
    echo "Erreur rencontrée : ".mysqli_error($connect);
    exit;
  }

  #Tableau pour indiquer que l'élève est inscrit
  echo "<table>";
  echo "<tr> <td colspan='2' class='header'> <h1> Eleve inscrit ! </h1> </td> </tr>";
  #Récapitulatif de l'inscription
  echo "<tr> <td> Nom : </td>";
  echo "<td> $nom </td> </tr>";
  echo "<tr> <td> Prénom : </td>";
  echo "<td> $prenom </td> </tr>";
  echo "<tr> <td> Date de naissance : </td>";
  echo "<td> $dateNaiss </td> </tr>";
  echo "<tr> <td colspan='2'><a href='../html/accueil.html'>Retour à l'accueil </a><br></td></tr>";
  echo "</table>";
}

#S'il existe un élève ayant les mêmes caractéristiques, alors on demande à l'utilisateur de confirmer l'inscription
else{
  #On demande à l'utilisateur s'il ne s'est pas trompé et n'a pas rentré 2 fois la même personne
  echo "<form action='valider_eleve.php' method='post'>";
  echo "<table>";
  echo "<tr> <td colspan='2' class='header'> <h1> Attention ! </h1> </td> </tr>";
  echo "<tr> <td colspan='2'>Un élève comportant le même nom et le même prénom est déjà enregistré.";
  echo "<br> <br> Souhaitez-vous poursuivre l'inscription ? ";

  #Champs cachés pour récupérer les infos par la suite
  echo "<input type='hidden' name='nom' value = '$nom'>";
  echo "<input type='hidden' name='prenom' value = '$prenom'>";
  echo "<input type='hidden' name='dateNaiss' value = '$dateNaiss'> </tr> </td>";

  #Radio bouton pour savoir si l'utilisateur souhaite continuer ou non l'inscription
  echo "<tr> <td> <input type ='radio' name='verif' value = 'oui' required> Oui </td>";
  echo "<td> <input type ='radio' name='verif' value = 'non' checked> Non </td> </tr>";
  echo "<tr> <td colspan ='2' class='submit'> <input type ='submit' value = 'Confirmer'> </td> </tr>";
  echo "</form>";
  echo "</table>";
}

#Fermeture de la base de données
mysqli_close($connect);

?>
  </body>
</html>
