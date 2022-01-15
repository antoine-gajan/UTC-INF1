<!DOCTYPE html>
<html lang="fr" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title> Ajouter un thème </title>
    <link rel="stylesheet" type="text/css" href="../css/form-styles.css">
  </head>

  <body>

<?php

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
#Test si les champs sont non vides et affichage des informations de l'utilisateur pour débuggage
if (empty($_POST["nom"]))
  {
    #Ajout d'une erreur (nom vide) au tableau
    $erreurs[]= 'Le champ "nom" ne peut être vide.';
  }
  else
  {
    $nom = $_POST["nom"];
    $nom = mysqli_real_escape_string($connect, $nom);
    #echo "<br>Le nom du thème est : ".$nom."<br>";
  }

if (empty($_POST["descriptif"]))
  {
    #Ajout d'une erreur (descriptif vide) au tableau
    $erreurs[] = 'Le champ "descriptif" ne peut être vide.';
  }
  else
  {
    $descriptif = $_POST["descriptif"];
    $descriptif = mysqli_real_escape_string($connect, $descriptif);
    #echo "Le descriptif du thème est : ".$descriptif;
  }


#Si une erreur a été rencontrée, on affiche la liste des erreurs et on quitte le programme php
if (!empty($erreurs))
  {
    echo "<table>";
    echo "<tr> <td colspan='2' class='header'> <h1> Problème(s) rencontré(s) ! </h1> </td> </tr>";
    echo "<tr> <td colspan='2'> L'ajout du thème ne peut être poursuivie car : <br> <br>";
    echo "<ul>";
    foreach ($erreurs as $erreur) {
      echo "<li> $erreur </li>";
    }
    echo "</ul>";
    echo "<br><br><a href='../html/ajout_theme.html'>Retour à la page d'ajout du thème </a><br>";
    echo "<br><a href='../html/accueil.html'>Retour à l'accueil </a><br>";
    echo "</td> </tr>";
    echo "</table>";
    exit;
  }


#Requête pour récupérer les thèmes ayant le même nom
$query1 = "SELECT * FROM themes WHERE nom='$nom'";
#Requête pour insérer le thème à la BDD
$query2 = "INSERT INTO themes VALUES (NULL, '$nom', 0, '$descriptif')";
#Requête pour réactiver un thème supprimé
$query3 = "UPDATE themes SET supprime = 0 WHERE nom='$nom'";
#echo "<br> $query1 <br>";
#echo "<br> $query2 <br>";
#echo "<br> $query3 <br>";

#On regarde si un thème ayant le même nom existe déjà
$result1 = mysqli_query($connect, $query1);

#Test si la requête 1 est correcte
if (!$result1){
  echo "Erreur rencontrée : ".mysqli_error($connect);
  exit;
}
#Nombre de lignes retourné par la requête 1, qui symbolise le nombre de thème ayant le même nom
$compteur1 = mysqli_num_rows($result1);
#echo "<br> $compteur1 <br>";


#Si aucun thème portant ce nom existe, on l'ajoute à la BDD
if ($compteur1 == 0){
  #Ajoute le thème à la BDD
  $result2 = mysqli_query($connect, $query2);

  #Test si la requête est correcte
  if (!$result2){
    echo "Erreur rencontrée : ".mysqli_error($connect);
    exit;
  }
  #Récapitulatif généré à l'utilisateur
  echo "<table>";
  echo "<tr> <td colspan='2' class='header'> <h1> Thème ajouté ! </h1> </td> </tr>";
  echo "<tr> <td> Nom du thème : </td>";
  echo "<td> $nom </td> </tr>";
  echo "<tr> <td> Descriptif : </td>";
  echo "<td> $descriptif </td> </tr>";
  echo "<tr> <td colspan='2'><br><a href='../html/accueil.html'>Retour à l'accueil </a><br></td></tr>";
  echo "</table>";
}

else{
  #Extraction des données du thème portant le même nom
  $theme = mysqli_fetch_array($result1, MYSQLI_NUM);
  #Si le thème existe déjà et est déjà activé
  if ($theme[2] == 0){
    echo "<table>";
    #Indique que le thème existe déjà et est actif
    echo "<tr> <td colspan='2' class='header'> <h1> Ce thème existe déjà ! </h1> </td> </tr>";
    #Affiche le récapitulatif du thème pour que l'utilisateur s'en rapelle et sache à quoi il correspond
    echo "<tr> <td> Nom du thème : </td>";
    echo "<td> $nom </td> </tr>";
    echo "<tr> <td> Descriptif : </td>";
    echo "<td> $theme[3] </td> </tr>";
    echo "<tr> <td colspan='2'><br><a href='../html/accueil.html'>Retour à l'accueil </a><br></td</tr>";
    echo "</table>";
  }

  else{
    #Si le thème a été supprimé, on le réactive
    $result3 = mysqli_query($connect, $query3);

    #Test si la requête est correcte
    if (!$result3){
      echo "Erreur rencontrée : ".mysqli_error($connect);
      exit;
    }
    #Récapitulatif
    echo "<table>";
    #Indique que le thème existe déjà et est réactivé
    echo "<tr> <td colspan='2' class='header'> <h1> Thème réactivé ! </h1> </td> </tr>";
    #Affiche le récapitulatif du thème pour que l'utilisateur s'en rapelle et sache à quoi il correspond
    echo "<tr> <td colspan='2'> Ce thème existait dans le passé et vient d'être réactivé. En voici un récapitulatif : </td> </tr>";
    echo "<tr> <td> Nom du thème : </td>";
    echo "<td> $theme[1] </td> </tr>";
    echo "<tr> <td> Descriptif : </td>";
    echo "<td> $theme[3] </td> </tr>";
    #Lien pour repartir à l'accueil
    echo "<tr> <td colspan='2'><br><a href='../html/accueil.html'>Retour à l'accueil </a><br></td></tr>";
    echo "</table>";
  }
}

#Fermeture de la base de données
mysqli_close($connect);

?>
  </body>
</html>
