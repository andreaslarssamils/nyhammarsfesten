#!/usr/bin/env bash
# Bygger om public/favicon.ico och public/apple-touch-icon.png från de två
# källfilerna. Kör från repots rot: bash resources/ikoner/bygg-ikoner.sh
#
# Lokalt verktyg — inget deploy-steg. Resultatfilerna committas och följer med
# koden till servern, och Chrome-sökvägen nedan finns bara på macOS.
#
#   public/favicon.svg            — ikonen så som moderna webbläsare visar den
#   resources/ikoner/favicon-16.svg — samma apa förenklad för 16 px i .ico:n
#
# Rastreringen görs med headless Chrome eftersom macOS saknar både ImageMagick
# och rsvg-convert som standard. sips klarar inte SVG och inte heller .ico.
set -euo pipefail

CHROME="${CHROME:-/Applications/Google Chrome.app/Contents/MacOS/Google Chrome}"
[ -x "$CHROME" ] || { echo "Hittar inte Chrome: $CHROME" >&2; exit 1; }

TMP="$(mktemp -d)"
trap 'rm -rf "$TMP"' EXIT

# Chrome renderar en fristående .svg som ett dokument i sin egen storlek, inte
# skalad till fönstret — därför läggs den i en HTML-sida med satt bredd/höjd.
rendera() { # $1 = svg, $2 = px, $3 = bakgrund, $4 = ut-png
  printf '<style>html,body{margin:0;padding:0;background:%s}svg{display:block;width:%spx;height:%spx}</style>\n' "$3" "$2" "$2" > "$TMP/sida.html"
  cat "$1" >> "$TMP/sida.html"
  "$CHROME" --headless --disable-gpu --hide-scrollbars --force-device-scale-factor=1 \
    --default-background-color=00000000 --window-size="$2","$2" \
    --screenshot="$4" "file://$TMP/sida.html" >/dev/null 2>&1
}

rendera resources/ikoner/favicon-16.svg 16 transparent "$TMP/16.png"
rendera public/favicon.svg              32 transparent "$TMP/32.png"
rendera public/favicon.svg              48 transparent "$TMP/48.png"

# iOS rundar hörnen själv, så apple-touch-ikonen ritas fyrkantig och ogenomskinlig.
# Hörnradien plockas bort med sed, alltså på exakt textmatchning: ändras rx i
# favicon.svg gör sed ingenting alls och ikonen blir dubbelrundad utan att något
# klagar. Stanna hellre här.
grep -q ' rx="12"' public/favicon.svg || {
  echo 'public/favicon.svg saknar rx="12" — uppdatera sed-raden nedan' >&2
  exit 1
}
sed 's/ rx="12"//' public/favicon.svg > "$TMP/fyrkant.svg"
rendera "$TMP/fyrkant.svg" 180 '#1A3B57' public/apple-touch-icon.png

# .ico:n är en behållare med tre PNG:er — formatet som alla nutida webbläsare läser.
python3 - "$TMP" <<'PY'
import pathlib, struct, sys

tmp = pathlib.Path(sys.argv[1])
bilder = [(px, (tmp / f'{px}.png').read_bytes()) for px in (16, 32, 48)]

ut = struct.pack('<HHH', 0, 1, len(bilder))
offset = 6 + 16 * len(bilder)
for px, data in bilder:
    ut += struct.pack('<BBBBHHII', px, px, 0, 0, 1, 32, len(data), offset)
    offset += len(data)
ut += b''.join(data for _, data in bilder)

pathlib.Path('public/favicon.ico').write_bytes(ut)
PY

echo "public/favicon.ico och public/apple-touch-icon.png ombyggda"
