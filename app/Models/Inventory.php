<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    //
    protected $fillable = [
        'name_barang',
        'category_id',   
        'member_id',     
        'serial_number',
        'spesification',
        'status',
        'quantity',
        'department'
    ];

    public function getKodeInventarisAttribute()
    {
        return 'INV-' . $this->id;
    }
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }
}
