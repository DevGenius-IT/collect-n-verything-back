<?php

// lang/en/users.php

return [
  "store_failed" => "User could not be stored.",
  "update_failed" => "User could not be updated.",
  "destroy_failed" => "User could not be destroyed.",
  "duplicate_failed" => "User could not be duplicated.",
  "destroy_success" => "User(s) destroyed successfully.",
  "cannot_destroy_self" => "You cannot destroy yourself.",
  "cannot_destroy" => "You cannot destroy this user.",
  "cannot_change_role" => "You cannot change the role of this user.",
  "transform_failed" => "User transformation failed.",
  "rules" => [
    "lastname" => "The lastname must be a string.",
    "firstname" => "The firstname must be a string.",
    "username" => "The username must be a string, required and unique.",
    "email" => "The email must be a valid email address and unique.",
    "password" =>
      "The password must be a string with a minimum length of 6 characters and contain at least one uppercase letter, one lowercase letter, one number, and one special character.",
    "phone_number" =>
      "The phone_number must be a string and start with an optional \"+\" followed by 1 to 15 digits.",
    "enabled" => "The enabled must be a boolean.",
    "has_newsletter" => "The has_newsletter must be a boolean.",
    "address_id" => "The address_id must be an integer and exist in the addresses table.",
    "roles" => "The roles must be an array with at least one item.",
    "orderBy" =>
      "The orderBy must be one of the following: lastname, firstname, username, email, phone_number, has_newsletter, address_id.",
  ],
];
