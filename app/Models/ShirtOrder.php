<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ShirtOrder extends Model
{
    protected $fillable = [
        'name', 'email', 'phone', 'model', 'color', 'size', 'quantity', 'note', 'ip',
    ];

    protected function casts(): array
    {
        return [
            'paid_at'      => 'datetime',
            'collected_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $order) {
            $order->unit_price ??= config('festival.shirt.price');
            $order->reference ??= self::nextReference();
        });
    }

    /**
     * Löpande ordernummer: NF-2026-0001. Körs i en transaktion med lås
     * så att två samtidiga beställningar inte kan få samma nummer.
     */
    protected static function nextReference(): string
    {
        $year = now()->year;

        $last = self::withoutGlobalScopes()
            ->where('reference', 'like', "NF-{$year}-%")
            ->lockForUpdate()
            ->orderByDesc('id')
            ->value('reference');

        $number = $last ? ((int) substr($last, -4)) + 1 : 1;

        return sprintf('NF-%d-%04d', $year, $number);
    }

    public function total(): int
    {
        return $this->quantity * $this->unit_price;
    }

    /**
     * Etiketterna slås upp på ett ställe i stället för i varje vy och mejl.
     * Fallbacken behövs: byter någon nyckel i config finns gamla ordrar kvar
     * med det gamla värdet, och då ska kvittot visa något i stället för att
     * krascha på en saknad arraynyckel.
     */
    public function modelLabel(): string
    {
        return config("festival.shirt.models.{$this->model}") ?? (string) $this->model;
    }

    public function colorLabel(): string
    {
        return config("festival.shirt.colors.{$this->color}.label") ?? (string) $this->color;
    }

    public function isPaid(): bool
    {
        return $this->paid_at !== null;
    }

    public function scopeUncollected(Builder $query): Builder
    {
        return $query->whereNull('collected_at');
    }

    /**
     * Underlag till tryckeriet: antal per modell, färg och storlek.
     *
     * Sorteringen görs i PHP mot configordningen, inte i SQL. Alfabetisk
     * ordning ger 110, 122, …, L, M, S, XL, XS, XXL — obrukbart för tryckeriet.
     */
    public static function printSummary(): Collection
    {
        $modeller = array_keys(config('festival.shirt.models'));
        $farger = array_keys(config('festival.shirt.colors'));
        $storlekar = array_merge(...array_values(config('festival.shirt.sizes')));

        $plats = function (array $ordning, ?string $varde): int {
            $index = array_search($varde, $ordning, true);

            return $index === false ? count($ordning) : $index;
        };

        return self::query()
            ->selectRaw('model, color, size, SUM(quantity) as total')
            ->groupBy('model', 'color', 'size')
            ->get()
            ->sortBy(fn (self $rad) => sprintf(
                '%02d%02d%02d',
                $plats($modeller, $rad->model),
                $plats($farger, $rad->color),
                $plats($storlekar, $rad->size),
            ))
            ->values();
    }

    public static function bookingOpen(): bool
    {
        return Carbon::parse(config('festival.shirt.deadline'))->endOfDay()->isFuture();
    }
}
