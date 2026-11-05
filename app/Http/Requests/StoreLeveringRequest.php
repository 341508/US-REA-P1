<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeveringRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Valideer de twee invoervelden uit wireframe 04.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'aantal' => ['required', 'integer', 'min:1', 'max:1000000'],
            'datum_eerstvolgende_levering' => ['required', 'date'],
        ];
    }

    /**
     * Gebruik begrijpelijke Nederlandse validatiemeldingen in het formulier.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'aantal.required' => 'Vul het aantal producteenheden in.',
            'aantal.integer' => 'Het aantal moet een heel getal zijn.',
            'aantal.min' => 'Het aantal moet minimaal 1 zijn.',
            'datum_eerstvolgende_levering.required' => 'Vul de datum van de eerstvolgende levering in.',
            'datum_eerstvolgende_levering.date' => 'Vul een geldige datum in.',
        ];
    }
}
