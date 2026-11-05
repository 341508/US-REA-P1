<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Allergeen extends Model
{
    protected $table = 'Allergeen';

    protected $primaryKey = 'Id';

    public $timestamps = false;

    /**
     * De producten die dit allergeen bevatten.
     */
    public function producten(): BelongsToMany
    {
        return $this->belongsToMany(
            Product::class,
            'ProductPerAllergeen',
            'AllergeenId',
            'ProductId',
            'Id',
            'Id'
        );
    }
}
