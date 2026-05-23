<?php
$db = new mysqli('localhost', 'root', '', 'db_showroom_mobil');
$result = $db->query("SELECT * FROM pemesanan ORDER BY id_pemesanan DESC LIMIT 5");
while($row = $result->fetch_assoc()) {
    print_r($row);
}
if ($result->num_rows === 0) {
    echo "No records found.";
}
