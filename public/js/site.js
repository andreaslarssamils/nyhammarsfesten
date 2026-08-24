// Nedräkning till festivalstart. Måldatumet kommer från config/festival.php
// via data-target på #countdown.
(function () {
    var el = document.getElementById('countdown');
    if (!el) return;

    var target = new Date(el.dataset.target).getTime();
    var pad = function (n) { return String(n).padStart(2, '0'); };

    var out = {
        dagar: document.getElementById('cdDagar'),
        timmar: document.getElementById('cdTimmar'),
        minuter: document.getElementById('cdMinuter'),
        sekunder: document.getElementById('cdSekunder')
    };

    function tick() {
        var d = Math.max(0, target - Date.now());
        var dagar = Math.floor(d / 86400000); d -= dagar * 86400000;
        var timmar = Math.floor(d / 3600000); d -= timmar * 3600000;
        var minuter = Math.floor(d / 60000); d -= minuter * 60000;
        var sekunder = Math.floor(d / 1000);

        out.dagar.textContent = String(dagar);
        out.timmar.textContent = pad(timmar);
        out.minuter.textContent = pad(minuter);
        out.sekunder.textContent = pad(sekunder);
    }

    tick();
    setInterval(tick, 1000);
})();

// Mobilmeny
(function () {
    var toggle = document.getElementById('navToggle');
    var links = document.getElementById('navLinks');
    if (!toggle || !links) return;

    toggle.addEventListener('click', function () {
        var open = links.classList.toggle('open');
        toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
        toggle.textContent = open ? 'Stäng ✕' : 'Meny ✶';
    });

    links.querySelectorAll('a').forEach(function (a) {
        a.addEventListener('click', function () {
            links.classList.remove('open');
            toggle.setAttribute('aria-expanded', 'false');
            toggle.textContent = 'Meny ✶';
        });
    });
})();

// Valideringsfel ligger långt ner på en lång startsida. Fragmentet #tshirt i
// redirecten räcker inte: webbläsaren hoppar över den scrollen medan typsnitt
// och maskotbilden fortfarande laddar och sidan växer under den. Därför
// scrollas felrutan fram här — en gång direkt, en gång när allt är inläst.
(function () {
    var fel = document.querySelector('.notis-fel');
    if (!fel) return;

    function visa() {
        fel.scrollIntoView({ block: 'center', behavior: 'instant' });
    }

    // tabindex -1 gör rutan fokuserbar utan att lägga den i tabbordningen,
    // så skärmläsaren hamnar på felen och inte i sidans topp.
    fel.setAttribute('tabindex', '-1');
    visa();
    fel.focus({ preventScroll: true });
    window.addEventListener('load', visa);
})();

// Storlekslistan i t-shirtformuläret följer vald modell
(function () {
    var model = document.getElementById('model');
    var size = document.getElementById('size');
    if (!model || !size) return;

    function sync() {
        var barn = model.value === 'barn';
        Array.prototype.forEach.call(size.querySelectorAll('optgroup'), function (group) {
            var isChildGroup = group.label === 'Barn';
            group.hidden = barn !== isChildGroup;
            group.disabled = barn !== isChildGroup;
        });
        var current = size.selectedOptions[0];
        if (!current || current.parentElement.disabled) {
            var first = size.querySelector('optgroup:not([disabled]) option');
            if (first) first.selected = true;
        }
    }

    model.addEventListener('change', sync);
    sync();
})();

// Klippet på huvudakten. autoplay-attributet sitter medvetet inte i markupen: det
// hade startat uppspelningen innan den här koden hunnit läsa mediefrågan, och
// @media (prefers-reduced-motion) i styles.css kan inte stoppa en video — bara
// animationer och övergångar.
//
// Uppspelningen knyts till att klippet syns, inte till sidladdningen. Sektionen
// ligger långt under vikningen, och Chrome pausar ett ljudlöst klipp som spelas
// utanför vyn — men startar det inte igen när man scrollar tillbaka, eftersom
// autoplay-attributet saknas. Utan observatören står klippet alltså stilla för
// nästan alla besökare. Att pausa på vägen ut sparar dessutom batteri och data.
(function () {
    var klipp = document.getElementById('videoKlipp');
    if (!klipp) return;
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;
    if (!('IntersectionObserver' in window)) return;

    var pausadAvObservator = false;

    var observator = new IntersectionObserver(function (traffar) {
        traffar.forEach(function (traff) {
            if (!traff.isIntersecting) {
                // pause() på ett redan pausat klipp ger ingen händelse — flaggan
                // sätts bara när en faktiskt är på väg, annars blir den kvar och
                // äter besökarens nästa tryck på pausknappen.
                pausadAvObservator = !klipp.paused;
                klipp.pause();
                return;
            }
            // play() avvisas i strömsparläge och i webbläsare som nekar automatisk
            // uppspelning. Då står klippet still med sina kontroller, vilket är rätt
            // utfall — felet ska inte hamna i konsolen som ett trasigt löfte.
            var spela = klipp.play();
            if (spela && spela.catch) spela.catch(function () {});
        });
    }, { threshold: 0.35 });

    // Pausar besökaren själv ska klippet förbli pausat. Utan det här startar
    // observatören om det så fort man scrollat bort och tillbaka, och pausknappen
    // blir verkningslös — vilket är hela poängen med att den finns. Flaggan
    // nollställs inne i lyssnaren, inte efter pause(): händelsen levereras som en
    // köad uppgift, så en nollställning på raden efter hade hunnit före.
    klipp.addEventListener('pause', function () {
        if (!pausadAvObservator) observator.disconnect();
        pausadAvObservator = false;
    });

    observator.observe(klipp);
})();
