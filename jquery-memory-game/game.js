$(document).ready(function() {
  // Definicja zmiennych gry
  var wybranyKlocek = 0;
  var liczbaZnalezionych = 0;
  var liczbaProb = 0;

  function aktualizujStatus () {
    $('#status-gry').html('Znaleziono: '+liczbaZnalezionych+', Próby: '+liczbaProb);
  }
  aktualizujStatus();

  // Przy kliknięciu w element o klasie przeslona, uruchamiamy poniższy kod
  $('.przeslona').click(function() {
    // Ktoś nakliknął przesłonę, więc ją ukrywamy (tutaj: this odnosi się do klikniętego elementu)
    $(this).hide();

    // Sprawdzamy jaki numer klocka został kliknięty. Pobieramy ojca przesłony, czyli element nad przesłoną
    var kliknietyKlocek = $(this).parent().attr('mem-number');
    console.log('Kliknięto klocek o numerze: ' + kliknietyKlocek);

    if (wybranyKlocek == 0) {
        // Jeżeli żaden klocek nie był jeszcze wybrany, to przypisujemy numer wybranego klocka
      wybranyKlocek = kliknietyKlocek;
    }
    else if (wybranyKlocek == kliknietyKlocek) {
      // Jeżeli wybrany wcześniej klocek ma ten sam numer, co kliknięty klocek, to znaleźliśmy parę
      liczbaZnalezionych++;
      liczbaProb++;

      $('#informacja-znaleziono').slideDown();
      setTimeout(function() {
        $('#informacja-znaleziono').slideUp();
        if (liczbaZnalezionych == 7) {
          alert('Udało Ci się znaleźć wszystkie pary, gratulacje!');
        }
      }, 2000);
      wybranyKlocek = 0;
    } else {
      liczbaProb++;
      var doZasloniecia1 = wybranyKlocek;
      var doZasloniecia2 = kliknietyKlocek;
      wybranyKlocek = 0;
      setTimeout(function() {
        $('[mem-number='+doZasloniecia1+'] .przeslona').fadeIn('slow');
        $('[mem-number='+doZasloniecia2+'] .przeslona').fadeIn('slow');
      }, 1000);
    }
    // Po każdym kliknięciu aktualizujemy status gry
    aktualizujStatus();
  });
});
