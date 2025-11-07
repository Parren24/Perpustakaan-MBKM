<?php

namespace App\Models\Biblio;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gmd extends Model
{
    use HasFactory;

    protected $connection = 'mysql_opac';
    protected $table = 'mst_gmd';
    protected $primaryKey = 'gmd_id';
    public $timestamps = false;

    protected $fillable = [
        'gmd_code',
        'gmd_name',
        'icon_image'
    ];

    protected $casts = [
        'gmd_id' => 'integer',
        'input_date' => 'date',
        'last_update' => 'date'
    ];

    // Relasi dengan Biblio (One to Many)
    public function biblios()
    {
        return $this->hasMany(Biblio::class, 'gmd_id', 'gmd_id');
    }

    // Scope untuk pencarian
    public function scopeSearch($query, $keyword)
    {
        return $query->where('gmd_name', 'LIKE', "%{$keyword}%")
            ->orWhere('gmd_code', 'LIKE', "%{$keyword}%");
    }

    // Accessor untuk mendapatkan icon path lengkap
    public function getIconPathAttribute()
    {
        return $this->icon_image ? asset('images/gmd/' . $this->icon_image) : null;
    }

    // Accessor untuk mendapatkan nama dengan kode
    public function getFullNameAttribute()
    {
        return $this->gmd_name . ($this->gmd_code ? ' (' . $this->gmd_code . ')' : '');
    }
}
