<?php

// lang/fr/users.php

return [
  "store_failed" => "L'utilisateur n'a pas pu être enregistré.",
  "update_failed" => "L'utilisateur n'a pas pu être mis à jour.",
  "destroy_failed" => "L'utilisateur n'a pas pu être détruit.",
  "duplicate_failed" => "L'utilisateur n'a pas pu être dupliqué.",
  "destroy_success" => "Le(s) utilisateur(s) a/ont été détruit.",
  "cannot_destroy_self" => "Vous ne pouvez pas vous détruire vous-même.",
  "cannot_destroy" => "Vous ne pouvez pas détruire cet utilisateur.",
  "cannot_change_role" => "Vous ne pouvez pas changer le rôle de cet utilisateur.",
  "transform_failed" => "La transformation de l'utilisateur a échoué.",
  "rules" => [
    "lastname" => "Le nom de famille doit être une chaîne de caractères.",
    "firstname" => "Le prénom doit être une chaîne de caractères.",
    "username" => "Le nom d'utilisateur doit être une chaîne de caractères, obligatoire et unique.",
    "email" => "L'email doit être une adresse email valide et unique.",
    "password" =>
      "Le mot de passe doit être une chaîne de caractères d'une longueur minimale de 6 caractères et contenir au moins une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial.",
    "phone_number" =>
      "Le numéro de téléphone doit être une chaîne de caractères et commencer par un signe \"+\" optionnel suivi de 1 à 15 chiffres.",
    "enabled" => "Le champ activé doit être un booléen.",
    "has_newsletter" => "Le champ newsletter doit être un booléen.",
    "address_id" =>
      "L'identifiant de l'adresse doit être un entier et exister dans la table des adresses.",
    "roles" => "Le champ roles doit être un tableau avec au moins un élément.",
    "orderBy" =>
      "Le tri doit être l'une des suivants : lastname, firstname, username, email, phone_number, has_newsletter, address_id.",
  ],
];
