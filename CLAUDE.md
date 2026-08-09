# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Projektet

Kampanjsajt för Nyhammarsfesten (26 september 2026) — en one-pager plus ett
förhandsbokningsflöde för festival-t-shirts. Laravel 13 / PHP 8.3. Ingen inloggning,
inga användare i praktiken: `User`-modellen och `routes/api.php` är kvar från
Laravel-skelettet och används inte.

## Kommandon

```bash
composer setup          # install + .env + key:generate + migrate + npm install + build
composer dev            # allt på en gång: artisan serve, queue:listen, pail, vite
composer test            # config:clear följt av artisan test
php artisan test --filter=ExampleTest    # enskild testklass eller -metod
vendor/bin/pint          # formatera (Laravel-preset + pint.json)
vendor/bin/pint --test   # kontrollera formatering utan att skriva — ska vara grön
```

`composer test` kör `artisan config:clear` först — hoppa inte över det, cachad config
ger annars falska resultat. Testerna körs mot sqlite `:memory:` och `MAIL_MAILER=array`
enligt `phpunit.xml`, oavsett vad `.env` säger.

## Arkitektur

### Frontend byggs inte med Vite

Det viktigaste att veta innan man rör en stilmall: **den publika sajten använder inte
Vite/Tailwind-pipelinen.** `resources/views/components/layout.blade.php` hämtar
handskriven CSS från `public/css/` med `asset()` och vanlig JS från `public/js/site.js`.
Typsnitt laddas direkt från Google Fonts i `<head>`.

`@vite(...)` förekommer bara i `resources/views/welcome.blade.php`, som ingen route
längre serverar. `resources/css/app.css` och `resources/js/app.js` är alltså död kod
från skelettet — `npm run build` producerar inget som sajten läser.

Stiländringar görs i `public/css/styles.css` (sidan) och `public/css/t-shirt.css`
(bokningsformulär, tacksida, admin). Designsystemet ligger som CSS-variabler i
`:root` överst i `styles.css` — affischens palett (`--navy`, `--cream`, `--olive`,
`--gold`, `--terracotta`) med kontrastförhållandena dokumenterade i kommentarerna.
Stjärnfältet är en SVG-mask i en variabel, inte en bildfil.

### config/festival.php är innehållshanteringen

All redaktionell text — lineup, program, biljetter, resvägar, FAQ, sponsorer,
kontaktuppgifter, t-shirtens pris/modeller/färger/storlekar/deadline — bor i
`config/festival.php`. Partialerna under `resources/views/partials/` är rena
renderare av den arrayen. Innehållsändringar är därför configändringar: ingen databas,
ingen kod, inga nya vyer.

Några fält styr beteende, inte bara text:

- `date` (ISO 8601 med tidszon) matas till `#countdown[data-target]` och driver
  nedräkningen i `site.js`.
- `shirt.deadline` avgör om bokningen är öppen (se nedan).
- `shirt.payment` (`prepay` / `on_pickup`) växlar texten i både formuläret och tacksidan.
- `lineup[].size` (1–4) och `lineup[].color` samt `program[].stage_class`
  (`null` / `alt` / `hot`) mappar till CSS-klasser.
- `shirt.colors` är nästlad: `['label' => 'Svart', 'bild' => 'assets/svart-nf.webp']`.
  Bilden visas som färgprov i bokningsformuläret. En ny färg behöver alltså både
  etikett och bildfil — inget mer, ingen ny CSS. `ShirtOrderTest` kontrollerar att
  varje färgs bildfil faktiskt finns.

Kom ihåg: `php artisan config:clear` efter ändringar om config är cachad.

### T-shirtflödet

Ett sammanhängande flöde spritt över flera filer:

1. `resources/views/shirts/_form.blade.php` inkluderas i `home.blade.php` och visar
   antingen formuläret eller ett "stängt"-meddelande beroende på
   `ShirtOrder::bookingOpen()` (deadline från config, `endOfDay()`).
2. `StoreShirtOrderRequest::authorize()` gör samma `bookingOpen()`-kontroll på
   serversidan — vyn ensam skyddar ingenting.
3. Spamskydd: dolt honeypot-fält `website` validerat som `prohibited`, plus
   `throttle:6,1` på routen.
4. `withValidator()` korsvaliderar modell mot storlek — barnstorlekar bara på
   modellen `barn`, och tvärtom. **Inte** `passedValidation()`: ett manuellt kastat
   `ValidationException` går förbi `getRedirectUrl()`, och då tappas `#tshirt`-
   fragmentet. `site.js` speglar samma regel i UI:t genom att dölja fel `<optgroup>`.
5. `ShirtOrder::booted()` stämplar `unit_price` från config och genererar referensen
   `NF-ÅÅÅÅ-NNNN` via `nextReference()`, som använder `lockForUpdate()`. **Därför**
   ligger `ShirtOrder::create()` i `DB::transaction()` i controllern — låset kräver en
   transaktion för att fungera.
6. Två mejl skickas med `Mail::send`, alltså synkront i requesten, trots att
   `QUEUE_CONNECTION=database`. Bekräftelse till kunden, notis till
   `festival.contact.email` med kundens adress som `replyTo`. Båda ligger i
   `try`/`catch` — ordern är redan committad när mejlen går, så ett SMTP-fel får
   inte bli ett 500 som lockar kunden att beställa igen.
   Mailklasserna använder `Content(text: …)`, inte `view:` — mejlvyerna är ren text
   och kollapsar till en enda klump om de renderas som HTML.
7. Redirect till `shirts.thanks` som slår upp ordern på `reference` — referensen är
   den enda nyckeln till kvittot, det finns ingen ägarkontroll. Sidan sätter
   `:noindex="true"` på layouten eftersom den visar kundens namn.

Slå aldrig upp etiketterna direkt i vyerna. `ShirtOrder::modelLabel()` och
`colorLabel()` gör det på ett ställe och faller tillbaka på råvärdet — byts en nyckel
i config finns gamla ordrar kvar med det gamla värdet, och kvitto, mejl och adminvy
ska visa något i stället för att krascha på en saknad arraynyckel.

Bilder: `public/assets/*.webp` är de optimerade versionerna (~15 KB styck) och är de
enda som webben läser. Originalen på flera MB ligger i
`storage/app/private/tshirt-original/` — gitignorerat och utanför `public/`, så de
varken serveras eller hamnar i repohistoriken. Nya tröjbilder körs genom
`cwebp -q 80 -resize 600 0 in.png -o ut.webp`.

Valideringsfelen är svenska via `lang/sv/validation.php`. Bara de regler som faktiskt
används ligger där; `APP_FALLBACK_LOCALE` står kvar på `en` så att en regel som saknas
ger läsbar engelska i stället för den råa nyckeln `validation.xxx`.

`ShirtOrder::printSummary()` aggregerar antal per modell, färg och storlek och är
underlaget tryckeriet får via adminvyn. Sorteringen görs i PHP mot configordningen —
`orderBy('size')` i SQL ger `110, 122, …, L, M, S, XL, XS, XXL`.

### Admin

`/admin/bestallningar` är en closure-route i `routes/web.php` utan auth-middleware.
Den jämför query-parametern `?nyckel` med `config('festival.admin_key')`, som läses ur
`FESTIVAL_ADMIN_KEY`.

Routen felar stängt: `filled($nyckel) && hash_equals(...)`. Ta inte bort
`filled()`-kontrollen. Utan den jämför `hash_equals()` två tomma strängar när
`FESTIVAL_ADMIN_KEY` är osatt, och då räcker den nakna adressen
`/admin/bestallningar` — utan query-parameter — för att lista varje beställares
namn, e-post, telefon och IP.

### Databas

`.env` pekar på MariaDB (`DB_CONNECTION=mariadb`, databas `nyhammarsfesten`).
`database/database.sqlite` ligger kvar från skelettet och används inte av dev-miljön —
bara testerna kör sqlite, och då i minnet.

## Konventioner

Kodbasen är svenskspråkig rakt igenom, inte bara i copy: routepaths
(`/admin/bestallningar`, `/tshirt/tack/{reference}`), CSS-klasser (`.falt`, `.honung`,
`.stjarnfalt`, `.notis-fel`, `.admin-svep`), kommentarer och valideringsmeddelanden.
Ny kod ska följa samma mönster. Undantaget är PHP-symboler som ramverket äger —
modellattribut, kolumnnamn och metodnamn är engelska.

Partialerna under `resources/views/partials/` använder två mellanslags indrag,
PHP-koden fyra (`.editorconfig` sätter fyra globalt).

Arrayer radar upp sina `=>` i kolumn (`config/festival.php`, `$casts`, `rules()`).
`pint.json` sätter därför `binary_operator_spaces.operators` till `null` för `=>`
så att pint lämnar uppradningen ifred — resten av Laravel-presetet gäller som
vanligt. Uppradning av vanliga tilldelningar (`=`) hör inte till stilen och rättas
av pint.

Utvecklingen sker på macOS med skiftlägesokänsligt filsystem, driften på Linux.
Katalognamn, filnamn och länkade assets måste därför matcha namnrymd, klassnamn och
faktiska filnamn exakt — annars fungerar det lokalt och går sönder vid deploy.
Repot har redan haft tre fel av den sorten, alla åtgärdade: `app/Http/requests/` mot
namnrymden `App\Http\Requests`, `ShirtOrderController` i en fil som hette
`Controller.php`, och en `<link>` till `tshirt.css` när filen heter `t-shirt.css`.
Bara det sista syntes lokalt. `php artisan route:list` fångar klassnamnsfel direkt.

Vid namnbyte på en katalog: `git mv` kan tyst misslyckas när bara skiftläget ändras.
Gå via ett mellansteg och kontrollera med `git ls-files` efteråt.
