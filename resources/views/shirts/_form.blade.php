@php
    $shirt = config('festival.shirt');
    $deadline = \Illuminate\Support\Carbon::parse($shirt['deadline']);
@endphp

<section id="tshirt" class="section tshirt">
  <div class="wrap">
    <div class="tshirt-head">
      <h2 class="display h2">Festival-t-shirt</h2>
      <span class="tshirt-price">{{ $shirt['price'] }} kr ✶ Förhandsboka</span>
    </div>

    @if (! \App\Models\ShirtOrder::bookingOpen())
      <p class="tshirt-closed">
        Förhandsbokningen är stängd. Ett begränsat antal tröjor säljs i entrén den 26 september —
        först till kvarn.
      </p>
    @else
      <p class="tshirt-lead">
        Tryckt lokalt, hämtas i entrén på festivaldagen. Boka senast
        <span class="tshirt-deadline">{{ $deadline->translatedFormat('j F') }}</span> —
        efter det går tröjorna i tryck.
      </p>

      @if ($errors->any())
        <div class="notis notis-fel" role="alert">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form method="POST" action="{{ route('shirts.store') }}" class="shirt-form">
        @csrf

        <div class="honung" aria-hidden="true">
          <label>Webbplats <input type="text" name="website" tabindex="-1" autocomplete="off"></label>
        </div>

        <div class="falt">
          <label for="name">Namn</label>
          <input id="name" type="text" name="name" value="{{ old('name') }}" required maxlength="120"
                 autocomplete="name" @error('name') aria-invalid="true" @enderror>
        </div>

        <div class="falt">
          <label for="email">E-post</label>
          <input id="email" type="email" name="email" value="{{ old('email') }}" required
                 autocomplete="email" @error('email') aria-invalid="true" @enderror>
        </div>

        <div class="falt">
          <label for="phone">Telefon <span class="hint">(valfritt)</span></label>
          <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" autocomplete="tel">
        </div>

        <div class="falt">
          <label for="quantity">Antal</label>
          <input id="quantity" type="number" name="quantity" value="{{ old('quantity', 1) }}"
                 min="1" max="10" required inputmode="numeric">
        </div>

        <div class="falt">
          <label for="model">Modell</label>
          <select id="model" name="model" required>
            @foreach ($shirt['models'] as $value => $label)
              <option value="{{ $value }}" @selected(old('model') === $value)>{{ $label }}</option>
            @endforeach
          </select>
        </div>

        <div class="falt falt-bred">
          <span class="falt-rubrik" id="fargLabel">Färg</span>
          <div class="fargval" role="radiogroup" aria-labelledby="fargLabel">
            @foreach ($shirt['colors'] as $value => $farg)
              <input type="radio" id="farg-{{ $value }}" name="color"
                     value="{{ $value }}" @checked(old('color', array_key_first($shirt['colors'])) === $value)>
              <label class="fargprov" for="farg-{{ $value }}">
                {{-- alt är tomt med flit: färgnamnet står redan i etiketten under bilden --}}
                <img class="fargprov-bild" src="{{ asset($farg['bild']) }}" alt=""
                     width="600" height="706" loading="lazy" decoding="async">
                <span class="fargprov-namn">{{ $farg['label'] }}</span>
              </label>
            @endforeach
          </div>
        </div>

        <div class="falt">
          <label for="size">Storlek</label>
          <select id="size" name="size" required @error('size') aria-invalid="true" @enderror>
            <optgroup label="Vuxen">
              @foreach ($shirt['sizes']['vuxen'] as $size)
                <option value="{{ $size }}" @selected(old('size') === $size)>{{ $size }}</option>
              @endforeach
            </optgroup>
            <optgroup label="Barn">
              @foreach ($shirt['sizes']['barn'] as $size)
                <option value="{{ $size }}" @selected(old('size') === $size)>{{ $size }} cl</option>
              @endforeach
            </optgroup>
          </select>
        </div>

        <div class="falt falt-bred">
          <label for="note">Meddelande <span class="hint">(valfritt)</span></label>
          <textarea id="note" name="note" rows="3" maxlength="500">{{ old('note') }}</textarea>
        </div>

        <button type="submit" class="shirt-submit">Boka tröja ✶</button>

        <p class="shirt-fine">
          @if ($shirt['payment'] === 'prepay')
            Du får ett ordernummer och swishar sedan summan med numret som meddelande.
          @else
            Du betalar när du hämtar tröjan i entrén.
          @endif
          Vi sparar dina uppgifter tills festivalen är genomförd, sedan raderas de.
        </p>
      </form>
    @endif
  </div>
</section>
