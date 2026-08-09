<?php

namespace App\Http\Requests;

use App\Models\ShirtOrder;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreShirtOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return ShirtOrder::bookingOpen();
    }

    public function rules(): array
    {
        $sizes = array_merge(...array_values(config('festival.shirt.sizes')));

        return [
            'name'     => ['required', 'string', 'max:120'],
            // Inget dns-uppslag: det gör inskicket långsamt och avvisar
            // giltiga adresser när namnservern strular.
            'email'    => ['required', 'email:rfc', 'max:180'],
            'phone'    => ['nullable', 'string', 'max:40'],
            'model'    => ['required', Rule::in(array_keys(config('festival.shirt.models')))],
            'color'    => ['required', Rule::in(array_keys(config('festival.shirt.colors')))],
            'size'     => ['required', Rule::in($sizes)],
            'quantity' => ['required', 'integer', 'min:1', 'max:10'],
            'note'     => ['nullable', 'string', 'max:500'],

            // Honeypot – ska alltid vara tomt. Bottar fyller i den.
            'website'  => ['prohibited'],
        ];
    }

    public function messages(): array
    {
        return [
            'website.prohibited' => 'Något gick fel. Försök igen.',
            'size.in'            => 'Välj en storlek som finns för vald modell.',
        ];
    }

    /**
     * Barnstorlekar bara på barnmodellen, och tvärtom.
     *
     * Ligger i withValidator() och inte i passedValidation(): ett manuellt
     * kastat ValidationException går förbi getRedirectUrl() nedan, och då
     * hamnar besökaren överst på sidan i stället för vid formuläret.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            // Saknas något av fälten är det redan påpekat — lägg inte ett
            // andra, mer förvirrande, felmeddelande ovanpå.
            if ($validator->errors()->hasAny(['model', 'size'])) {
                return;
            }

            $childSizes = config('festival.shirt.sizes.barn');
            $isChildSize = in_array($this->input('size'), $childSizes, true);

            if (($this->input('model') === 'barn') !== $isChildSize) {
                $validator->errors()->add('size', 'Den storleken finns inte för vald modell.');
            }
        });
    }

    /**
     * Formuläret ligger långt ner på en lång startsida — utan fragmentet
     * landar besökaren i toppen och ser aldrig felrutan.
     */
    protected function getRedirectUrl(): string
    {
        return $this->redirector->getUrlGenerator()->previous().'#tshirt';
    }
}
