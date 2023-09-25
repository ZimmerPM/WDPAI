# LibraApp

[Wersja po angielsku :uk:](README.md)

## Wprowadzenie
LibraApp to aplikacja służąca do zarządzania zbiorami bibliotecznymi. Umożliwia użytkownikom wypożyczanie książek, zarządzanie kontem oraz przeglądanie dostępnych pozycji. Projekt został stworzony w celu nauki i praktyki z technologiami webowymi.

### Widoki aplikacji
![Widok logowania aplikacji](/public/img/screenshots/login-screen.JPG)
![Widok logowania aplikacji - wersja mobilna](/public/img/screenshots/login-screen-mobile.JPG)
![Widok katalogu książek](/public/img/screenshots/catalog-view.jpg)
![Widok katalogu książek - wersja mobilna](/public/img/screenshots/catalog-view-mobile.JPG)
![Widok zarządzania wypożyczeniami](/public/img/screenshots/loans-view.JPG)
![Widok zarządzania wypożyczeniami - wersja mobilna](/public/img/screenshots/loans-view-mobile.JPG)

## Technologie
- PHP 7.4.3
- PostgreSQL 15.4
- HTML 5
- CSS
- JavaScript
- Docker 24.0.5

## Uruchomienie
Aby uruchomić projekt lokalnie, postępuj zgodnie z poniższymi krokami:
1. Pobierz Dockera ze strony [Docker](https://www.docker.com/) i zainstaluj go.
2. Sklonuj repozytorium.
3. Ustaw połączenie z bazą danych w pliku `config.php`.
4. Ustaw w terminalu katalog projektu jako bieżący i uruchom kontener Docker za pomocą komendy: `docker-compose up`.
5. Uruchom przeglądarkę i wpisz adres: http://localhost:8080
6. Aby zakończyć pracę aplikacji, użyj w terminalu komendy: `docker-compose down`.

## Zakres Funkcjonalności
### Konto czytelnika:
- Rejestracja i logowanie użytkowników z możliwością zmiany hasła w wybranym momencie.
- Przeglądanie katalogu dostępnych książek wraz z możliwością wyszukiwania.
- Rezerwacja książek do wypożyczenia.
- Dostęp do historii własnych wypożyczeń.

### Konto administratora:
- Zarządzanie katalogiem książek - możliwość dodawania pozycji, ich usuwania i edycji.
- Zarządzanie procesem wypożyczenia książek przez czytelników - zatwierdzanie bądź anulowanie rezerwacji, wypożyczeń i zwrotów.
- Zarządzanie użytkownikami aplikacji - możliwość edycji danych użytkowników i ich usuwania.

## Dodatkowe informacje
Autor: Piotr Zimirski  
Link do repozytorium: [GitHub](https://github.com/ZimmerPM/)

Dziękuję za zainteresowanie projektem LibraApp!
