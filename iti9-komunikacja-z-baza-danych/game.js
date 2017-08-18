$(document).ready(function() {
  // Definicja zmiennych gry
  var wybranyKlocekId = 0;
  var wybranyKlocekNumer = 0;
  var liczbaZnalezionych = 0;
  var liczbaProb = 0;
  var liczbaKlockow = 0;

  function inicjalizacjaGry() {
    // Pierwszą czynnością inicjalizacji gry jest zresetowanie kolejności na serwerze
    $.get('serwer.php?opcja=zresetuj_gre');

    wybranyKlocekId = 0;
    wybranyKlocekNumer = 0;
    liczbaZnalezionych = 0;
    liczbaProb = 0;
    liczbaKlockow = 0;
    aktualizujStatus();
    $.get('serwer.php?opcja=ile_klockow', function( dane ) {
      liczbaKlockow = dane;
      var klocki = '';
      for (var i = 0; i < liczbaKlockow; i++) {
        klocki += '<div class="klocek" mem-number="'+i+'"><img src="images/loading_apple.gif" /><div class="przeslona"></div></div>'
      }
      $('#kontener-na-klocki').html(klocki);

      // Przy kliknięciu w element o klasie przeslona, uruchamiamy poniższy kod
      $('.przeslona').click(function() {
        // Ktoś nakliknął przesłonę, więc ją ukrywamy (tutaj: this odnosi się do klikniętego elementu)
        $(this).hide();
        var klocek = $(this).parent();


        // Sprawdzamy jaki numer klocka został kliknięty. Pobieramy ojca przesłony, czyli element nad przesłoną
        var kliknietyKlocekNumer = $(this).parent().attr('mem-number');
        $.get('serwer.php?opcja=jaki_klocek&numer_klocka='+kliknietyKlocekNumer, function (dane) {
          var odpowiedzSerwera = JSON.parse(dane);
          var kliknietyKlocekId = odpowiedzSerwera.idKlocka;
          klocek.find('img:first').attr('src', odpowiedzSerwera.obrazek);

          if (wybranyKlocekId == 0) {
              // Jeżeli żaden klocek nie był jeszcze wybrany, to przypisujemy numer wybranego klocka
            wybranyKlocekId = kliknietyKlocekId;
            wybranyKlocekNumer = kliknietyKlocekNumer;
          }
          else if (wybranyKlocekId == kliknietyKlocekId) {
            // Jeżeli wybrany wcześniej klocek ma ten sam numer, co kliknięty klocek, to znaleźliśmy parę
            liczbaZnalezionych++;
            liczbaProb++;

            $('#informacja-znaleziono').slideDown();
            setTimeout(function() {
              $('#informacja-znaleziono').slideUp();
              if (liczbaZnalezionych == liczbaKlockow/2) {
                var imie = prompt('Udało Ci się znaleźć wszystkie pary, gratulacje! Aby zapisać wynik, podaj swoje imię', 'Jan Kowalski');
                if (imie != null && imie != '') {
                  // Dodajemy wynik gracza do bazy danych
                  var liczba_bledow = (liczbaProb-liczbaZnalezionych);
                  $.get('serwer.php?opcja=dodaj_wynik&imie='+imie+'&liczba_bledow='+liczba_bledow, function (dane){
                    alert('Wynik został zapisany. Twój wynik to '+liczba_bledow+' błędów. Spróbuj ponownie :)');
                    // Po zapisaniu wyniku resetujemy grę
                    inicjalizacjaGry();
                  });
                } else {
                  // Jeżeli użytkownik nie podał imienia, to resetujemy grę bez zapisywania wyniku
                  inicjalizacjaGry();
                }
              }
            }, 2000);
            wybranyKlocekId = 0;
          } else {
            liczbaProb++;
            var doZasloniecia1 = wybranyKlocekNumer;
            var doZasloniecia2 = kliknietyKlocekNumer;
            wybranyKlocekId = 0;
            setTimeout(function() {
              $('[mem-number='+doZasloniecia1+'] .przeslona').fadeIn('slow');
              $('[mem-number='+doZasloniecia2+'] .przeslona').fadeIn('slow');
            }, 1000);
          }
          // Po każdym kliknięciu aktualizujemy status gry
          aktualizujStatus();

        });
      });

    });
  }
  inicjalizacjaGry();

  function aktualizujStatus () {
    $('#status-gry').html('Znaleziono: '+liczbaZnalezionych+', Próby: '+liczbaProb);
  }
});
