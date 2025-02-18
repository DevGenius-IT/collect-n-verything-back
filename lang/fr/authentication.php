<?php

// lang/fr/authentication.php

return [
  "sign_out" => "Déconnexion réussie",
  "invalid_credentials" => "Identifiants invalides",
  "invalid_oauth" => "Fournisseur OAuth invalide",
  "invalid_recovery" => "Méthode de récupération invalide",
  "failed_recovery" => "Récupération échouée",
  "recovery_sent" => "Lien de récupération envoyé avec succès",
  "failed_to_send_recovery" => "Échec de l'envoi du lien de récupération",
  "reset_password_waits" =>
    "Veuillez attendre :time avant de demander un nouveau lien de réinitialisation de mot de passe",
  "failed_to_reset_password" => "Échec de la réinitialisation du mot de passe",
  "invalid_token" => "Jeton invalide",
  "invalid_role" => "Rôle invalide",
  "password_reset" => "Réinitialisation du mot de passe réussie",
  "sign_up_data_failed" => "L'adresse e-mail de l'utilisateur manquante",
  "rules" => [
    "email" => "L'adresse e-mail doit être une adresse e-mail valide.",
    "password" =>
      "Le mot de passe doit être une chaîne de caractères d'une longueur minimale de 6 caractères et contenir au moins une lettre majuscule, une lettre minuscule, un chiffre et un caractère spécial.",
    "password_confirmation" => "La confirmation du mot de passe doit correspondre au mot de passe.",
  ],
  "emails" => [
    "recovery_subject" => "Récupération de mot de passe",
    "reset_password" => [
      "subject" => "Réinitialisation de mot de passe",
      "title" => "Réinitialisation de mot de passe",
      "intro" => "Pour réinitialiser votre mot de passe, cliquez sur le bouton ci-dessous.",
      "button" => "Réinitialisation votre mot de passe",
      "footer" => [
        "paragraph" => "Nous restons à votre disposition pour toutes vos questions ou suggestions.",
        "kind" => "Très cordialement,",
        "team" => "L'équipe de FichesPédagogiques",
      ],
      "contact" => [
        "info" => "Pour toute question relative au site ",
        "write" => ", écrivez-nous à ",
      ],
    ],
  ],
];
