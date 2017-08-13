<?php
$klocki = [1,2,5,4,5,3,3,7,2,1,6,7,4,6];

if (!empty($_GET['opcja'])) {
  switch ($_GET['opcja']) {
    case 'ile_klockow':
      echo count($klocki);
      break;

    case 'jaki_klocek':
      if(array_key_exists($_GET['numer_klocka'], $klocki)) {
        $id_klocka = $klocki[$_GET['numer_klocka']];
        echo json_encode([
          "idKlocka" => $id_klocka,
          "obrazek" => "images/obj".$id_klocka.".png",
        ]);
      } else {
        echo 'Błąd numeru klocka';
      }
      break;

    default:
      # code...
      break;
  }
} else {
  echo 'Błąd zmiennych';
}
