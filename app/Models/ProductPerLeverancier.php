<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductPerLeverancier extends Model
{
    protected $table = 'ProductPerLeverancier';

    protected $primaryKey = 'Id';

    public $timestamps = false;

    protected function casts(): array
    {
        return [
            'DatumLevering' => 'date',
            'DatumEerstVolgendeLevering' => 'date',
        ];
    }

    /**
     * De leverancier die deze levering heeft uitgevoerd.
     */
    public function leverancier(): BelongsTo
    {
        return $this->belongsTo(Leverancier::class, 'LeverancierId', 'Id');
    }

    /**
     * Het product waarop deze levering betrekking heeft.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'ProductId', 'Id');
    }
}
