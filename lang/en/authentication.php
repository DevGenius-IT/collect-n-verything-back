<?php

// lang/en/authentication.php

return [
  "sign_out" => "Logged out successfully",
  "invalid_credentials" => "Invalid credentials",
  "invalid_oauth" => "Invalid OAuth provider",
  "invalid_recovery" => "Invalid recovery method",
  "failed_recovery" => "Recovery failed",
  "recovery_sent" => "Recovery link sent successfully",
  "failed_to_send_recovery" => "Failed to send recovery link",
  "reset_password_waits" => "Please wait a :time before requesting a new password reset link",
  "failed_to_reset_password" => "Failed to reset password",
  "invalid_token" => "Invalid token",
  "invalid_role" => "Invalid role",
  "password_reset" => "Password reset successfully",
  "sign_up_data_failed" => "Missing user email address",
  "rules" => [
    "email" => "The email must be a valid email address.",
    "password" =>
      "The password must be a string with a minimum length of 6 characters and contain at least one uppercase letter, one lowercase letter, one number, and one special character.",
    "password_confirmation" => "The password confirmation must match the password.",
  ],
  "emails" => [
    "recovery_subject" => "Password recovery",
    "reset_password" => [
      "subject" => "Password Reset",
      "title" => "Password Reset",
      "intro" => "To reset your password, click the button below.",
      "button" => "Reset your password",
      "footer" => [
        "paragraph" => "We remain at your disposal for any questions or suggestions.",
        "kind" => "Best regards,",
        "team" => "The FichesPédagogiques Team",
      ],
      "contact" => [
        "info" => "For any questions regarding the ",
        "write" => " site, write to us at ",
      ],
    ],
  ],
];
