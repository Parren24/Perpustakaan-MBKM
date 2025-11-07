<?php

namespace App\Models\Biblio;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiblioRelation extends Model
{
    use HasFactory;

    protected $connection = 'mysql_opac';
    protected $table = 'biblio_relation';
    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'biblio_id',
        'rel_biblio_id',
        'rel_type'
    ];

    protected $casts = [
        'biblio_id' => 'integer',
        'rel_biblio_id' => 'integer',
        'rel_type' => 'integer'
    ];

    // Relasi dengan Biblio utama
    public function biblio()
    {
        return $this->belongsTo(Biblio::class, 'biblio_id', 'biblio_id');
    }

    // Relasi dengan Biblio yang terkait
    public function relatedBiblio()
    {
        return $this->belongsTo(Biblio::class, 'rel_biblio_id', 'biblio_id');
    }

    // Scope untuk filter berdasarkan tipe relasi
    public function scopeByRelationType($query, $type)
    {
        return $query->where('rel_type', $type);
    }

    // Scope untuk mendapatkan relasi dari biblio tertentu
    public function scopeFromBiblio($query, $biblioId)
    {
        return $query->where('biblio_id', $biblioId);
    }

    // Konstanta tipe relasi
    const RELATION_TYPES = [
        1 => 'Related',
        2 => 'Series',
        3 => 'Translation',
        4 => 'Edition',
        5 => 'Supplement'
    ];

    // Accessor untuk mendapatkan nama tipe relasi
    public function getRelationTypeNameAttribute()
    {
        return self::RELATION_TYPES[$this->rel_type] ?? 'Unknown';
    }
}
