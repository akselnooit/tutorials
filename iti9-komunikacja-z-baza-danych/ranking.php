<?php
// Pamiętaj o zmianie danych połączenia na twoje!
$ustawienia['host']  = 'localhost';
$ustawienia['uzytkownik']  = 'root';
$ustawienia['haslo']  = '';
$ustawienia['bazadanych']  = 'mojabaza';

// Połączenie z bazą danych
$mysqli = new mysqli($ustawienia['host'], $ustawienia['uzytkownik'],
  $ustawienia['haslo'], $ustawienia['bazadanych']);
if ($mysqli->connect_errno) {
  echo 'Nie udało się połączyć z bazą danych. Więcej informacji: '.$mysqli->connect_error;
  exit;
}

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="UTF-8">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
    <title>Gra Memory - Ranking</title>
  </head>
  <body>
    <div id="pasek-tlo">
      <div id="pasek">
        <div id="powitanie"><a href="index.html">Powrót do gry</a> - Ranking graczy</div>
        <div class="clear"></div>
      </div>
    </div>

    <div class="wrapper">
      <div class="table">
        <div class="row header">
          <div class="cell">
            Msc
          </div>
          <div class="cell">
            Imię
          </div>
          <div class="cell">
            Liczba błędów
          </div>
          <div class="cell">
            Data
          </div>
        </div>

        <?php
        // Pobieramy wszystkie wpisy w kolejności od najmniejszej liczby błędów i wyświetlamy je.
        $sql = "SELECT imie, liczba_bledow, data FROM memory_ranking ORDER BY liczba_bledow ASC";
        $result = $mysqli->query($sql);
        $miejsce = 1;
        while ($wpis = $result->fetch_assoc()) {
          echo '<div class="row">
            <div class="cell">
              '.$miejsce++.'
            </div>
            <div class="cell">
              '.$wpis['imie'].'
            </div>
            <div class="cell">
              '.$wpis['liczba_bledow'].'
            </div>
            <div class="cell">
              '.$wpis['data'].'
            </div>
          </div>';
        }
        ?>
      </div>
    </div>
  </body>
</html>
