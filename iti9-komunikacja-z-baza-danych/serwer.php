<?php
// Ustawienia gry - możesz je dowolnie zmieniać. Pamiętaj jednak, że jeżeli ustawisz
// więcej niż 7 klocków, to musisz dodać kolejne obrazki do folderu images
$ustawienia['liczbaKlockow'] = 7;
$ustawienia['host']  = 'localhost';
$ustawienia['uzytkownik']  = 'root';
$ustawienia['haslo']  = '123456';
$ustawienia['bazadanych']  = 'zbiorka';

// Rozpoczęcie sesji
session_start();

// Połączenie z bazą danych
$mysqli = new mysqli($ustawienia['host'], $ustawienia['uzytkownik'],
  $ustawienia['haslo'], $ustawienia['bazadanych']);
if ($mysqli->connect_errno) {
  echo 'Nie udało się połączyć z bazą danych. Więcej informacji: '.$mysqli->connect_error;
  exit;
}

function losujKolejnosc ($liczbaKlockow) {
  $klocki = [];
  for ($i = 1; $i <= $liczbaKlockow; $i++) {
    // każde id_klocka od zera do $liczbaKlockow jest dodawane dwa razy, bo każdy klocek
    // musi występować dwa razy, aby można było znaleść parę
    $klocki[] = $i;
    $klocki[] = $i;
  }
  shuffle($klocki);

  // zwracamy zmienną $klocki z wymieszaną kolejnością klocków
  return $klocki;
}

// Jeżeli nie istnieje jeszcze zmienna klocki w sesji, to tworzymy ją
if (empty($_SESSION['klocki'])) {
  $_SESSION['klocki'] = losujKolejnosc($ustawienia['liczbaKlockow']);
}

// Przepisujemy kolejność klocków z sesjii do zmiennej lokalnej klocki
$klocki = $_SESSION['klocki'];

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

    case 'zresetuj_gre':
      // jeżeli ktoś wykona zapytanie serwer.php?opcja=zresetuj_gre, to usuniemy
      // $_SESSION['klocki'], dzięki czemu zostanie ona ponownie wylosowana
      unset($_SESSION['klocki']);
      echo json_encode([
        "status" => 'Kolejność została usunięta z sesji.',
      ]);
      break;

    case 'dodaj_wynik':
      // Na początek sprawdźmy, czy przekazane zostało imię oraz liczba błędów
      if(!empty($_GET['imie']) && !empty($_GET['liczba_bledow'])) {
        $imie = $mysqli->real_escape_string($_GET['imie']);
        $liczba_bledow = $mysqli->real_escape_string($_GET['liczba_bledow']);
        // Zapytanie SQL dodające wpis do tabeli memory_ranking
        $sql = "INSERT INTO `memory_ranking` (`imie`, `liczba_bledow`) VALUES ('$imie', '$liczba_bledow');";
        if ($mysqli->query($sql)) {
          echo json_encode([
            "status" => 'success',
          ]);
        } else {
          echo json_encode([
            "status" => 'failure',
          ]);
        }
      } else {
        echo 'Nie podano imienia i/lub liczby błędów.';
      }
      break;

    default:
      # code...
      break;
  }
} else {
  echo 'Błąd zmiennych';
}
