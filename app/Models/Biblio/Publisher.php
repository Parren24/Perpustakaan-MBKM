<?php

namespace App\Models\Biblio;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publisher extends Model
{
    use HasFactory;

    protected $connection = 'mysql_opac';
    protected $table = 'mst_publisher';
    protected $primaryKey = 'publisher_id';
    public $timestamps = false;

    protected $fillable = [
        'publisher_name'
    ];

    protected $casts = [
        'publisher_id' => 'integer',
        'input_date' => 'date',
        'last_update' => 'date'
    ];

    // Relasi dengan Biblio (One to Many)
    public function biblios()
    {
        return $this->hasMany(Biblio::class, 'publisher_id', 'publisher_id');
    }

    // Scope untuk pencarian
    public function scopeSearch($query, $keyword)
    {
        return $query->where('publisher_name', 'LIKE', "%{$keyword}%");
    }

    // Method untuk mendapatkan jumlah buku yang diterbitkan
    public function getBooksCountAttribute()
    {
        return $this->biblios()->count();
    }

    // Method untuk mendapatkan buku terbaru dari penerbit ini
    public function getLatestBooksAttribute()
    {
        return $this->biblios()
            ->orderBy('input_date', 'desc')
            ->limit(5)
            ->get();
    }
}
