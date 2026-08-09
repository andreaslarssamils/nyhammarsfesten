#!/usr/bin/env bash
#
# Deploy av nyhammarsfesten.se på Inleed.
# Körs på servern:  ~/nyhammarsfesten/deploy.sh
#
set -euo pipefail

PHP=php
COMPOSER=~/bin/composer.phar
APP=~/nyhammarsfesten
BRANCH=main

cd "$APP"

echo "→ Underhållsläge"
$PHP artisan down --retry=15 || true

# Se till att sidan alltid kommer upp igen, även om något nedan fallerar
trap '$PHP artisan up || true' EXIT

echo "→ Hämtar kod"
git fetch origin "$BRANCH"
git reset --hard "origin/$BRANCH"

echo "→ Composer"
$PHP "$COMPOSER" install --no-dev --optimize-autoloader --no-interaction --prefer-dist

echo "→ Migreringar"
$PHP artisan migrate --force

echo "→ Cachar om"
$PHP artisan optimize:clear
$PHP artisan optimize

echo "→ Klart"
