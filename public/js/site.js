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
