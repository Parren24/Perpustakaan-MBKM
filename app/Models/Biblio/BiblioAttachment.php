<?php

namespace App\Models\Biblio;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiblioAttachment extends Model
{
    use HasFactory;

    protected $connection = 'mysql_opac';
    protected $table = 'biblio_attachment';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'biblio_id',
        'file_id',
        'access_limit'
    ];

    protected $casts = [
        'biblio_id' => 'integer',
        'file_id' => 'integer'
    ];

    // Relasi dengan Biblio (Many to One)
    public function biblio()
    {
        return $this->belongsTo(Biblio::class, 'biblio_id', 'biblio_id');
    }

    // Scope untuk filter berdasarkan akses
    public function scopePublicAccess($query)
    {
        return $query->whereNull('access_limit')
            ->orWhere('access_limit', '')
            ->orWhere('access_limit', 'public');
    }

    // Scope untuk filter berdasarkan biblio
    public function scopeByBiblio($query, $biblioId)
    {
        return $query->where('biblio_id', $biblioId);
    }
}
