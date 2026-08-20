<nav class="nav">
  <a href="#hem" class="nav-logo">{{ config('festival.name') }}</a>
  <button class="nav-toggle" id="navToggle" aria-expanded="false" aria-controls="navLinks">Meny ✶</button>
  <div class="nav-links" id="navLinks">
    <a href="#artister">Artister</a>
    <a href="#program">Program</a>
    <a href="#biljetter">Biljetter</a>
    <a href="#tshirt">T-shirt</a>
    <a href="#hitta">Hitta hit</a>
    <a href="#faq">FAQ</a>
    <a href="#kontakt">Kontakt</a>
    <x-forkop class="nav-cta-mobile" label="26 sept ✶ Förköp" fallback="#biljetter" />
  </div>
  <x-forkop class="nav-cta" label="26 sept ✶ Förköp" fallback="#biljetter" />
</nav>
