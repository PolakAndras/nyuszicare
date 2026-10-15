-- NyusziCare adatbázis: táblák és mintaadatok
-- Importálás: hozz létre egy üres adatbázist (pl. "nyuszicare", utf8mb4_unicode_ci kódolással),
-- majd importáld ezt a fájlt phpMyAdminban vagy parancssorból:
--   mysql -u root nyuszicare < database/nyuszicare.sql
--
-- Demó fiókok:
--   Felhasználó: demo@nyuszicare.hu  / Demo1234
--   Admin:       admin@nyuszicare.hu / Admin1234

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `cikkek`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `adminok`;

-- Regisztrált felhasználók
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `newsletter` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Adminisztrátorok (külön táblában a felhasználóktól)
CREATE TABLE `adminok` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `adminok_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Cikkek, három kategóriában
CREATE TABLE `cikkek` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cim` varchar(255) NOT NULL,
  `szerzo` varchar(40) NOT NULL,
  `bevezeto` varchar(400) NOT NULL,
  `tartalom` text NOT NULL,
  `kategoria` enum('Nyultartas','Betegsegek','Etetes') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `cikkek_kategoria_index` (`kategoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Demó fiókok (jelszavak bcrypt hash-sel)
INSERT INTO `users` (`name`, `email`, `password`, `newsletter`) VALUES
('Demó Felhasználó', 'demo@nyuszicare.hu', '$2y$10$8Bejb6eWM0X89uuVvcIaEeH6FHyWnhhTlVaBCyYYvanpv8/VZCz/6', 0);

INSERT INTO `adminok` (`email`, `password`) VALUES
('admin@nyuszicare.hu', '$2y$10$cJhyGfV0Y6eV/pcFJWxMK.2tZxP2reOWAX.FBmaWqliQL6omuiOUq');

-- Cikkek
INSERT INTO `cikkek` (`id`, `cim`, `szerzo`, `bevezeto`, `tartalom`, `kategoria`) VALUES (2,'Mit tegyünk, ha a nyúl unatkozik?','Tóth Bence','A nyuszik rendkívül intelligensek és igénylik a napi elfoglaltságot...','A nyulak unatkozása viselkedésbeli problémákhoz vezethet, például rágcsálás, ketrec-rugdosás. Játékokkal, interaktív foglalkozással, valamint szabad mozgással lehet megelőzni. Fontos a napi pár óra ketrecen kívüli mozgás lehetősége is.','Nyultartas');
INSERT INTO `cikkek` (`id`, `cim`, `szerzo`, `bevezeto`, `tartalom`, `kategoria`) VALUES (3,'Alomhasználat tanítása házi nyúlnak','Dr. Nyúlka Éva','Meglepően sok nyuszi gyorsan megtanulja az alomtálcát használni...','A nyulak természetes ösztöneik alapján egy helyre végzik a dolgukat. Ezt kihasználva, egy jól elhelyezett alomtálcával, egy kis odafigyeléssel és jutalmazással szobatisztaságra nevelhetjük őket. Fontos a rendszeres takarítás és pozitív megerősítés.','Nyultartas');
INSERT INTO `cikkek` (`id`, `cim`, `szerzo`, `bevezeto`, `tartalom`, `kategoria`) VALUES (4,'Napirend és etetési rutin a nyulaknál','Szabó Dániel','A rendszeresség biztonságot ad a nyuszinak, és megkönnyíti az emésztését...','A nyulak emésztése érzékeny, ezért napi többszöri, kis adagokban történő etetés javasolt. Reggel széna, kevés zöldség, este újra széna és egy kevés pellet. A víz mindig legyen friss és elérhető. A rutin segít elkerülni az emésztési problémákat.','Nyultartas');
INSERT INTO `cikkek` (`id`, `cim`, `szerzo`, `bevezeto`, `tartalom`, `kategoria`) VALUES (5,'Nyuszi-barát szoba kialakítása','Horváth Nóra','Ha a nyuszink gyakran van szabadon, fontos a biztonságos tér kialakítása...','A szobában lévő kábeleket el kell rejteni, veszélyes növényeket és kis tárgyakat el kell távolítani. A nyuszi szeret alá- és mögé bújni, így ilyen lehetőségeket is biztosítani kell. A padlón csúszásgátló vagy szőnyeg legyen, hogy kényelmesen mozoghasson.','Nyultartas');
INSERT INTO `cikkek` (`id`, `cim`, `szerzo`, `bevezeto`, `tartalom`, `kategoria`) VALUES (6,'A nyúl ketrecének berendezése','Kovács Nóra','Hogyan alakítsuk ki ideálisan a nyúl életterét?','A nyúl ketrecének kényelmesnek, biztonságosnak és tisztán tarthatónak kell lennie. Fontos a megfelelő méret, elhelyezés és almozás is.','Nyultartas');
INSERT INTO `cikkek` (`id`, `cim`, `szerzo`, `bevezeto`, `tartalom`, `kategoria`) VALUES (7,'A nyúl játékigénye','Tóth Ádám','Mitől boldog egy nyuszi a mindennapokban?','A nyulak játékos állatok, szükségük van napi mozgásra, felfedezésre. Mutatjuk, mivel lehet lefoglalni őket!','Nyultartas');
INSERT INTO `cikkek` (`id`, `cim`, `szerzo`, `bevezeto`, `tartalom`, `kategoria`) VALUES (8,'Társas nyulak: tarthatunk-e több nyulat együtt?','Fekete Zsuzsa','Mikor érdemes több nyuszit tartani?','A nyulak társas lények, de a nem megfelelő bemutatás vagy ivartalanítás hiánya konfliktushoz vezethet. Olvasd el, hogyan párosítsd őket helyesen!','Nyultartas');
INSERT INTO `cikkek` (`id`, `cim`, `szerzo`, `bevezeto`, `tartalom`, `kategoria`) VALUES (9,'A szobatisztaság nyulaknál','Varga Márton','Megtanítható-e a nyúl alomra?','Igen! A nyulak könnyen rászoktathatók az alomtálcára, főleg ha következetesek vagyunk. Tippek és trükkök a tanításhoz.','Nyultartas');
INSERT INTO `cikkek` (`id`, `cim`, `szerzo`, `bevezeto`, `tartalom`, `kategoria`) VALUES (10,'Milyen nyúlfajta illik hozzád?','Szabó Dóra','Népszerű fajták és temperamentumuk','Törpenyúl, holland, kosorrú – minden nyúlfajta más. Tudj meg többet, hogy jól válassz!','Nyultartas');
INSERT INTO `cikkek` (`id`, `cim`, `szerzo`, `bevezeto`, `tartalom`, `kategoria`) VALUES (11,'A nyúl ivartalanítása – előnyök és veszélyek','Balogh Ákos','Miért fontos és mikor érdemes?','Az ivartalanítás nemcsak a szaporulat elkerülése, hanem viselkedésjavítás és egészségügyi okok miatt is javasolt. Ismerd meg a tényeket!','Nyultartas');
INSERT INTO `cikkek` (`id`, `cim`, `szerzo`, `bevezeto`, `tartalom`, `kategoria`) VALUES (12,'A nyúl körömvágása','Molnár Petra','Szükséges, de sokan félnek tőle','Megmutatjuk, mikor és hogyan kell biztonságosan levágni a nyuszi körmeit.','Nyultartas');
INSERT INTO `cikkek` (`id`, `cim`, `szerzo`, `bevezeto`, `tartalom`, `kategoria`) VALUES (13,'Nyúlbarát otthon kialakítása','Kiss András','Hogyan tegyük a lakást nyúlkompatibilissé?','Nyúlbarát szoba: veszélyes zsinórok eltüntetése, biztonságos sarokvédelem, kényelmes búvóhelyek – minden részlet számít.','Nyultartas');
INSERT INTO `cikkek` (`id`, `cim`, `szerzo`, `bevezeto`, `tartalom`, `kategoria`) VALUES (14,'A nyúl napi rutinja','Pintér Zsófi','Mit csinál egy boldog nyuszi egy átlagos napon?','Etetés, mozgás, alvás, játék – a nyúl életét a rendszeresség és a biztonságérzet határozza meg.','Nyultartas');
INSERT INTO `cikkek` (`id`, `cim`, `szerzo`, `bevezeto`, `tartalom`, `kategoria`) VALUES (15,'Mik a nyúl stressz jelei?','Nagy Sándor','Hogyan ismerd fel, ha baj van?','A nyuszik érzékenyek. Hangos zajok, hirtelen változások vagy helytelen bánásmód is stresszt okozhatnak. Jelek és megoldások.','Nyultartas');
INSERT INTO `cikkek` (`id`, `cim`, `szerzo`, `bevezeto`, `tartalom`, `kategoria`) VALUES (16,'A nyulak alapvető étrendje','Kovács Lili','Mit és mennyit egyen egy nyuszi?','A széna a legfontosabb! Emellett friss zöldség, kevés pellet és mindig tiszta víz.','Etetes');
INSERT INTO `cikkek` (`id`, `cim`, `szerzo`, `bevezeto`, `tartalom`, `kategoria`) VALUES (17,'Tilos ételek nyulaknak','Varga Bence','Mit NE adjunk a nyuszinak?','Cukros, keményítős, puffasztó ételek károsak. Listázzuk, mit kerülj el mindenképp!','Etetes');
INSERT INTO `cikkek` (`id`, `cim`, `szerzo`, `bevezeto`, `tartalom`, `kategoria`) VALUES (18,'Milyen zöldséget kaphat a nyúl?','Nagy Laura','Nem minden zöld egészséges','Spenót, répa, endívia – de csak mértékkel! Megmutatjuk, mit kaphat és mit kerülj el.','Etetes');
INSERT INTO `cikkek` (`id`, `cim`, `szerzo`, `bevezeto`, `tartalom`, `kategoria`) VALUES (19,'Gyümölcs – nyusziknál csak ritkán!','Horváth Zoltán','Nasi vagy veszély?','Bár szeretik az édeset, a gyümölcs cukros. Csak ritkán és keveset adjunk!','Etetes');
INSERT INTO `cikkek` (`id`, `cim`, `szerzo`, `bevezeto`, `tartalom`, `kategoria`) VALUES (20,'Etetési rutin nyusziknál','Szalai Anna','Napirend a kiegyensúlyozott táplálkozáshoz','Reggeli széna, délelőtti zöldség, esti pellet – egy minta napi etetési terv.','Etetes');
INSERT INTO `cikkek` (`id`, `cim`, `szerzo`, `bevezeto`, `tartalom`, `kategoria`) VALUES (21,'Szénatípusok nyulaknak','Fazekas Gábor','Lucerna vagy réti?','Melyik széna mire jó? Fajták, előnyök és mikor melyiket érdemes használni.','Etetes');
INSERT INTO `cikkek` (`id`, `cim`, `szerzo`, `bevezeto`, `tartalom`, `kategoria`) VALUES (22,'Ivóvíz fontossága','Kis Orsolya','Nem csak kaja kell!','A tiszta, friss víz állandó jelenléte alapvető – kulacs vagy tál a jobb megoldás?','Etetes');
INSERT INTO `cikkek` (`id`, `cim`, `szerzo`, `bevezeto`, `tartalom`, `kategoria`) VALUES (23,'A pellet szerepe','Kovács Eszter','Jó-e, ha mindig elérhető?','A pellet koncentrált, de nem alap – hogyan és mikor adjuk?','Etetes');
INSERT INTO `cikkek` (`id`, `cim`, `szerzo`, `bevezeto`, `tartalom`, `kategoria`) VALUES (24,'Táplálkozási problémák jelei','Tóth Ákos','Mikor gyanakodjunk?','Ha nem eszik, vagy válogat – ezek lehetnek figyelmeztető jelek. Nézzük a tüneteket.','Etetes');
INSERT INTO `cikkek` (`id`, `cim`, `szerzo`, `bevezeto`, `tartalom`, `kategoria`) VALUES (25,'Kölyöknyuszi etetése','Bognár Rita','Miben más?','A kicsiknek más igényeik vannak – széna, víz, speciális pellet szükséges.','Etetes');
INSERT INTO `cikkek` (`id`, `cim`, `szerzo`, `bevezeto`, `tartalom`, `kategoria`) VALUES (26,'Hízás vagy fogyás?','Jakab Kriszta','Mi számít normálisnak?','A nyúl súlya nem csak esztétikai kérdés – etetési hiba is állhat a háttérben.','Etetes');
INSERT INTO `cikkek` (`id`, `cim`, `szerzo`, `bevezeto`, `tartalom`, `kategoria`) VALUES (27,'Tavaszi vitaminpótlás','Hegedűs Marci','Kell-e plusz táp?','Természetes forrásból pótolhatjuk a tavaszi hiányokat, mutatjuk hogyan.','Etetes');
INSERT INTO `cikkek` (`id`, `cim`, `szerzo`, `bevezeto`, `tartalom`, `kategoria`) VALUES (28,'Fogproblémák és táplálkozás','Barna Dorina','A széna nem csak táplálék!','A folyamatos rágás segíti a fogak természetes kopását – ha nem eszik, baj lehet.','Etetes');
INSERT INTO `cikkek` (`id`, `cim`, `szerzo`, `bevezeto`, `tartalom`, `kategoria`) VALUES (29,'Rágcsálnivalók házilag','Mészáros Niki','Készítsd el te is!','Egyszerű, természetes és egészséges nyuszicsalik otthonról.','Etetes');
INSERT INTO `cikkek` (`id`, `cim`, `szerzo`, `bevezeto`, `tartalom`, `kategoria`) VALUES (30,'Étkezési tévhitek nyulaknál','Kerekes Balázs','Ne dőlj be mindennek!','Nem, a nyuszi nem ehet kenyeret! Népszerű, de veszélyes tévhitek az étrendről.','Etetes');
INSERT INTO `cikkek` (`id`, `cim`, `szerzo`, `bevezeto`, `tartalom`, `kategoria`) VALUES (31,'A nyulak hőgutája','Kovács Bálint','Nyáron veszélyes lehet','A nyuszik nem bírják jól a meleget. Árnyék, hűtés, sok víz – a megelőzés a kulcs!','Betegsegek');
INSERT INTO `cikkek` (`id`, `cim`, `szerzo`, `bevezeto`, `tartalom`, `kategoria`) VALUES (32,'Megfázás nyulaknál','Varga Réka','Tüsszög, taknyos?','Lehet megfázás is. Fontos, hogy ne huzatos helyen tartsuk a nyuszit!','Betegsegek');
INSERT INTO `cikkek` (`id`, `cim`, `szerzo`, `bevezeto`, `tartalom`, `kategoria`) VALUES (33,'A fogtúltengés tünetei','Pintér József','Mi történik, ha nem kopik a fog?','Nem eszik, fogy, nyálzik – sürgősen állatorvoshoz kell vinni!','Betegsegek');
INSERT INTO `cikkek` (`id`, `cim`, `szerzo`, `bevezeto`, `tartalom`, `kategoria`) VALUES (34,'Fülrühesség nyulaknál','Kiss Mariann','Vakarással kezdődik','A fülrüh könnyen terjed és fájdalmas lehet – nézz bele a fülébe!','Betegsegek');
INSERT INTO `cikkek` (`id`, `cim`, `szerzo`, `bevezeto`, `tartalom`, `kategoria`) VALUES (35,'Bélleállás (ileus)','Nagy Attila','Azonnali állatorvos kell','Ha nem eszik, nem kakil, fáj a hasa – a bélleállás halálos is lehet!','Betegsegek');
INSERT INTO `cikkek` (`id`, `cim`, `szerzo`, `bevezeto`, `tartalom`, `kategoria`) VALUES (36,'Hasmenés nyulaknál','Tóth Kata','Gyorsan kiszáradhat','Diéta, sok széna és állatorvosi felügyelet szükséges!','Betegsegek');
INSERT INTO `cikkek` (`id`, `cim`, `szerzo`, `bevezeto`, `tartalom`, `kategoria`) VALUES (37,'Bolhásság és paraziták','Molnár Tamás','Külső élősködők nyuszin','Vakarózik? Nemtetszik a bundája? Lehet, hogy bolha vagy atkák támadták meg.','Betegsegek');
INSERT INTO `cikkek` (`id`, `cim`, `szerzo`, `bevezeto`, `tartalom`, `kategoria`) VALUES (38,'Kötőhártya-gyulladás','Borbás Noémi','A szem nem hazudik','Ha piros, váladékozik a szeme, valószínűleg gyulladt. Szemcsepp és állatorvos kell.','Betegsegek');
INSERT INTO `cikkek` (`id`, `cim`, `szerzo`, `bevezeto`, `tartalom`, `kategoria`) VALUES (39,'Légzőszervi problémák','Jakus Károly','Nehéz légzés, orrfolyás?','Fertőzés vagy allergia is okozhatja – ki kell vizsgálni!','Betegsegek');
INSERT INTO `cikkek` (`id`, `cim`, `szerzo`, `bevezeto`, `tartalom`, `kategoria`) VALUES (40,'Puffadás','Fodor Ilona','Miért veszélyes?','A gázok felgyülemlése fájdalmas lehet és életveszélyes, ha nem múlik gyorsan.','Betegsegek');
INSERT INTO `cikkek` (`id`, `cim`, `szerzo`, `bevezeto`, `tartalom`, `kategoria`) VALUES (41,'Sebek a lábon (pododermatitis)','Zentai Fruzsi','Vérző, gyulladt tappancsok','Puha aljzat, gyógykenőcs – ez a nyulak gyakori betegsége!','Betegsegek');
INSERT INTO `cikkek` (`id`, `cim`, `szerzo`, `bevezeto`, `tartalom`, `kategoria`) VALUES (42,'Fogszuvasodás és táplálkozás','Szekeres Endre','Túl sok pellet?','A nem megfelelő étrend rontja a fogakat – széna kell, nem nasi.','Betegsegek');
INSERT INTO `cikkek` (`id`, `cim`, `szerzo`, `bevezeto`, `tartalom`, `kategoria`) VALUES (43,'Lúdtalp nyulaknál','Vámosi Nóra','Lábtartási rendellenesség','Sérülés vagy genetika okozhatja – figyeljük a járását!','Betegsegek');
INSERT INTO `cikkek` (`id`, `cim`, `szerzo`, `bevezeto`, `tartalom`, `kategoria`) VALUES (44,'Szőrlenyelés és bélprobléma','Halász Luca','Vedléskor gyakori','Túl sok lenyelt szőr okozhat emésztési gondokat – fésülés és rost!','Betegsegek');
INSERT INTO `cikkek` (`id`, `cim`, `szerzo`, `bevezeto`, `tartalom`, `kategoria`) VALUES (45,'Vakbélgyulladás nyulaknál','Rácz Ákos','Ritka, de súlyos','Hasfájás, étvágytalanság – ha nem múlik, irány az állatorvos!','Betegsegek');

SET FOREIGN_KEY_CHECKS = 1;
