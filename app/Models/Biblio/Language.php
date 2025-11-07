<?php

namespace App\Models\Biblio;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;

    protected $connection = 'mysql_opac';
    protected $table = 'mst_language';
    protected $primaryKey = 'language_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'language_id',
        'language_name'
    ];

    protected $casts = [
        'input_date' => 'date',
        'last_update' => 'date'
    ];

    // Relasi dengan Biblio (One to Many)
    public function biblios()
    {
        return $this->hasMany(Biblio::class, 'language_id', 'language_id');
    }

    // Scope untuk pencarian
    public function scopeSearch($query, $keyword)
    {
        return $query->where('language_name', 'LIKE', "%{$keyword}%")
            ->orWhere('language_id', 'LIKE', "%{$keyword}%");
    }

    // Method untuk mendapatkan jumlah buku dalam bahasa ini
    public function getBooksCountAttribute()
    {
        return $this->biblios()->count();
    }

    // Konstanta bahasa umum
    const COMMON_LANGUAGES = [
        'id' => 'Indonesian',
        'en' => 'English',
        'ar' => 'Arabic',
        'zh' => 'Chinese',
        'fr' => 'French',
        'de' => 'German',
        'ja' => 'Japanese',
        'es' => 'Spanish'
    ];
}
