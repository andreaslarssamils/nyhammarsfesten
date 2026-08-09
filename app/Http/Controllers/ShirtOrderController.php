<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShirtOrderRequest;
use App\Mail\ShirtOrderConfirmation;
use App\Mail\ShirtOrderNotification;
use App\Models\ShirtOrder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ShirtOrderController extends Controller
{
    public function store(StoreShirtOrderRequest $request): RedirectResponse
    {
        // Transaktionen behövs för lockForUpdate() i ShirtOrder::nextReference().
        $order = DB::transaction(function () use ($request) {
            return ShirtOrder::create([
                ...$request->safe()->except('website'),
                'ip' => $request->ip(),
            ]);
        });

        // sync-drivern: mejlen skickas direkt, ingen kö behövs. Faller mejlet
        // ändå ska kunden inte mötas av ett fel — ordern ligger redan i
        // databasen, och kvittosidan visar både referens och swishnummer.
        try {
            Mail::to($order->email)->send(new ShirtOrderConfirmation($order));
            Mail::to(config('festival.contact.email'))->send(new ShirtOrderNotification($order));
        } catch (\Throwable $e) {
            report($e);
        }

        return redirect()->route('shirts.thanks', ['reference' => $order->reference]);
    }

    public function thanks(string $reference): View
    {
        $order = ShirtOrder::where('reference', $reference)->firstOrFail();

        return view('shirts.thanks', compact('order'));
    }
}
