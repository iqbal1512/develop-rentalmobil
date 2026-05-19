<?php
$password_user = "owner123";

// Membuat hash menggunakan algoritma BCRYPT (standar industri)
$hash_password = password_hash($password_user, PASSWORD_BCRYPT);

echo "Password asli: " . $password_user . "<br>";
echo "Hasil Hash: " . $hash_password;
?>