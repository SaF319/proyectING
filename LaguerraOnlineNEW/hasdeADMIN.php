<?php
$hashedPassword = password_hash('1234', PASSWORD_BCRYPT);

// Imprime el hash para que puedas usarlo en tu consulta
echo $hashedPassword;
?>
