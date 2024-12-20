<p align="center">
  <img src="docs/logo.jpg" alt="logo aplikacije Čtenije" />
</p>

## O projektu

Ovo je veb aplikacija za online prodavnicu knjiga pod nazivom "Čtenije".<br>
Aplikacija omogućava sve osnovne funkcionalnosti koje online prodavnica treba da ima:

- Registracija i prijava korisnika
- Verifikacija email adrese
- Ažuriranje ličnih podataka korisnika
- Brisanje korisničkog naloga
- Pregled proizvoda po kategorijama
- Dodavanje proizvoda u korpu za kupovinu
- Pregled proizvoda u korpi za kupovinu
- Povećavanje/smanjivanje količine proizvoda u korpi za kupovinu
- Uklanjanje proizvoda iz korpe za kupovinu
- Unos podataka za dostavu
- Unos podataka za plaćanje
- Otkazivanje plaćanja
- Email obavještenje sa detaljima porudžbine

Implementacija omogućava kompletan proces kupovine kako za registrovane, tako i za neregistrovane korisnike. Pored pomenutih funkcionalnosti namijenjenih kupcima i posjetiocima, aplikacija takođe omogućava zaposlenima da hijerarhijski prate i upravljaju radom online prodavnice. Kreiran je poseban dio sistema koji je dostupan samo ovlašćenim korisnicima(zaposlenima). Hijerarhija zaposlenih podrazumijeva dvije uloge - Administrator i Menadžer.
U administrativnom dijelu sistema omogućene su sledeće akcije:

- Pregled osnovnih informacija o poslovanju
- Pregled i brisanje korisnika
- Pregled, dodavanje i brisanje menadžera
- Pregled proizvoda
- Pregled pojedinačnih proizvoda
- Dodavanje, ažuriranje i brisanje proizvoda
- Pregled svih porudžbina
- Pregled detalja porudžbine

## Primijenjene tehnologije
Sledeće tehnologije su korištene za razvoj ove veb aplikacije:

- PHP
- Laravel
- MySQL
- Stripe
- HTML
- CSS
- TailWind CSS
- JavaScript
- AlpineJS

## ER Dijagram
Entiteti sa plavom pozadinom naslova predstavljaju tabele u bazi podataka koje su automatski generisane od strane Laravel-a.
![ER Diagram](docs/dijagram_er.jpg)

## Aplikativni GUI 
Prijava
![Login](docs/prijava.jpg)

Registracija
![Register](docs/registracija.jpg)

Aplikativno obavještenje za verifikaciju mejl adrese
![Verify email address notification](docs/info_verifikacija.jpg)

Početna stranica - sekcija "Knjiga godine"
![Main page - "Book of the year"](docs/knjiga_godine.jpg)

Knjige po kategorijama - Istorija
![Books about history](docs/knjige_kategorija_istorija.jpg)

Detalji knjige
![Book details](docs/detalji_knjige.jpg)

Korpa za kupovinu
![Shopping cart](docs/cart.jpg)

Plaćanje
![Payment](docs/placanje.jpg)

Potvrda plaćanja putem mejla, sa detaljima porudžbine.
![Payment confirmation with order details](docs/detalji_porudzbine_email.jpg)


## Problemi i izazovi
Ovo su neki od problema i izazova na koje sam naišao tokom razvoja:

- Dje sačuvati podatke koje neregistrovani korisnik unosi u formu za podatke dostave?<br>
**Rješavanjem ovog problema naučio sam upravljanje podacima pomoću sesije, za proces porudžbine.**

- Kada se artikal koji je povezan sa stavkom porudžbine obriše, ta stavka porudžbine se takođe obriše.<br>
**Ovo je čest izazov u razvoju e-commerce aplikacija. Rješavanjem ovog problema naučio sam o varijantama ograničenja(constraints) tabela u bazi podataka i o Laravel-ovoj metodi "soft-deletion".**

## Sledeća poboljšanja
Ovo su moguće naredne nove funkcionalnosti i poboljšanja:

- Redizajn baze podataka zbog redundancije podataka
- Sistem preporuke knjiga
- Stranice za autora i izdavača
- Prikazivanje knjiga po autoru, izdavaču, popularnosti i cijeni
- Pretraga unosom naziva knjige
- Opcioni unos koda za popust u korpi za kupovinu
- Više metoda za plaćanje
- Ocjene knjiga
- Komentari

## Licenca

[MIT licenca](https://opensource.org/licenses/MIT).
