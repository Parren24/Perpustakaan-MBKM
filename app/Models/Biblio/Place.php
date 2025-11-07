<?php

namespace App\Models\Biblio;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    use HasFactory;

    protected $connection = 'mysql_opac';
    protected $table = 'mst_place';
    protected $primaryKey = 'place_id';
    public $timestamps = false;

    protected $fillable = [
        'place_name'
    ];

    protected $casts = [
        'place_id' => 'integer',
        'input_date' => 'date',
        'last_update' => 'date'
    ];

    // Relasi dengan Biblio (One to Many)
    public function biblios()
    {
        return $this->hasMany(Biblio::class, 'publish_place_id', 'place_id');
    }

    // Scope untuk pencarian
    public function scopeSearch($query, $keyword)
    {
        return $query->where('place_name', 'LIKE', "%{$keyword}%");
    }

    // Method untuk mendapatkan jumlah buku yang diterbitkan di tempat ini
    public function getBooksCountAttribute()
    {
        return $this->biblios()->count();
    }

    // Method untuk mendapatkan penerbit yang beroperasi di tempat ini
    public function getPublishersAttribute()
    {
        return Publisher::whereHas('biblios', function ($query) {
            $query->where('publish_place_id', $this->place_id);
        })->get();
    }
}
