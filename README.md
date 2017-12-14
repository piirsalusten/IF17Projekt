IF17PROJEKT
IF17 Rühma ühisprojekt
greeny.cs.tlu.ee/~piirsten/IF17Projekt/index.php

Rühma liikmed: Sten Piirsalu, Rando Aljaste, Mihkel Mägi, Caspar Sepp
Liikmete panus rühmatöösse: Sten Piirsalu (32%) Mihkel Mägi(32%) Rando Aljaste(32%) Caspar Sepp(4%)

Eesmärk: Eesmärk oli teha veebilehestik, kus saaksime harjutada tunnis omandatud teadmisi. Soovisime teha töö sellises mahus, et peaksime eesmärkide saavutamiseks ka veebiprogrammeerimise kohta juurde õppima. Soov oli arendada ka tiimitööd.

kirjeldus:

funktsionaalsuse loetelu:
1. Saab teha kasutaja
2. Saab lehele sisse logida
3. Kui on sisse loginud, saab teha ise uue kuulutuse
4. Kuulutuse omanik saab vaadata enda postitatud kuulutusi
5. Kuulutuse omanik saab enda kuulutused ära kustutada
6. Pealehel on näha kõige värskemaid kuulutusi
7. Saab vaadata kuulutusi kategooriate kaupa
8. Külastajad saavad anda tagasisidet lehe kohta
9. Külastajad saavad tutvuda poe reeglitega

Andmebaasi skeem ja SQL Laused
Andmebaase, mida kasutasime oli ainult 2. Algselt oli plaanid kasutada kolme aga suutsime vajaliku info mahutada kahte andmebaasi, mis tegi töö natukene lihtsamaks.

Andmebaasi skeem: https://gyazo.com/8a688e10452a7a07ae00a31ca9876079

Järgnevad on pildid nendest andmebaasidest
https://gyazo.com/4ad93367ee00cb1fd309f2ea0b2d0b73
https://gyazo.com/01cc617bd2fe2fd7041e10f8f01c71d2
Tabelid tegime valmis phpmyadminis.

CREATE TABLE `epproducts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `epusers_id` int(11) DEFAULT NULL,
  `product_name` varchar(100) DEFAULT NULL,
  `Category` int(1) NOT NULL,
  `Price` int(11) NOT NULL,
  `productDesc` varchar(250) NOT NULL,
  `pictureName` varchar(50) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `sold` int(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `epusers_id` (`epusers_id`),
  CONSTRAINT `epproducts_ibfk_1` FOREIGN KEY (`epusers_id`) REFERENCES `epusers` (`id`)
)

CREATE TABLE `epusers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstname` varchar(30) CHARACTER SET utf8 COLLATE utf8_estonian_ci NOT NULL,
  `lastname` varchar(30) CHARACTER SET utf8 COLLATE utf8_estonian_ci NOT NULL,
  `birthday` date NOT NULL,
  `gender` int(1) NOT NULL,
  `pic` varchar(11) NOT NULL,
  `address` varchar(70) CHARACTER SET utf8 COLLATE utf8_estonian_ci NOT NULL,
  `phone_number` varchar(30) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(128) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted` int(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
)


Kokkuvõte:

Mihkel Mägi:  Õppisin juurde erinevaid andmebaaside, PHP ja html-i oskusi. Hakkan asjadest aina paremini aru saama. Otseselt ebaõnnestumisi ei olnudki. Eks ikka oli tihti vaja mitu korda katsedada, et asja nii toimima saaks, nagu tahan, aga ma saavutasin kõik endale püstitatud eesmärgid ära. Keeruline oli teha tiimitööd, sest kokkupandud asjad ei toiminud esialgu nii, nagu oli plaanitud.

Rando Aljaste: Õppisin juurde kõige rohkem CSS-i, ma ise kõike välja ei mõelnud muidugi aga sai väga palju ka ise tehtud ja asju katse-eksitus meetodil üles ehitatud ja korda sättida,
HTML-is õppisin juurde uusi asju ja testisin väga palju uusi asju, kuid kõiki kodulehele ei pannud. PHP ja andmebaasi oskused väga palju ei arenenud, suhteliselt sama oli kõik nagu siiamaani tunnis tehtud asjadega.
Ebaõnnsetumisi oli koguaeg(errorid) aga kõik sai lõpuks ilusti lahendatud.

Sten Piirsalu: Õppisin juurde githubi kasutamist ja rühma koostööd programmeerimise juures. Alguses ei saanud väga githubi branchide struktuurist aru ja esines olukordi, kus kirjutasime üksteise faile üle. Oleks pidanud githubiga enne selle kasutamist natukene rohkem tutvuma, see oleks vältinud ebameeldivaid olukordi, kus osadel kadus osa tehtud tööst ära. Omandasin ka erinevaid andmebaaside, PHP ja html-i oskusi. Saan andmebaasi ja veebilehe vahelisest sidemest ja süsteemidest palju paremini aru. Esines vahetevahel mõningaid vigu, nendele kõikidele leidsin õnneks lahenduse. Sain seda tööd tehes juurde palju teadmisi ja juurutasin õpitut.
