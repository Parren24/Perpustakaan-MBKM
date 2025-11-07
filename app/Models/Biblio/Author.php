<?php

namespace App\Models\Biblio;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    protected $connection = 'mysql_opac';
    protected $table = 'mst_author';
    protected $primaryKey = 'author_id';
    public $timestamps = false;

    protected $fillable = [
        'author_name',
        'author_year',
        'authority_type',
        'auth_list'
    ];

    protected $casts = [
        'author_id' => 'integer',
        'input_date' => 'date',
        'last_update' => 'date'
    ];

    // Relasi dengan Biblio (Many to Many)
    public function biblios()
    {
        return $this->belongsToMany(
            Biblio::class,
            'biblio_author',
            'author_id',
            'biblio_id'
        )->withPivot('level');
    }

    // Scope untuk filter berdasarkan tipe authority
    public function scopeByAuthorityType($query, $type)
    {
        return $query->where('authority_type', $type);
    }

    // Scope untuk pencarian
    public function scopeSearch($query, $keyword)
    {
        return $query->where('author_name', 'LIKE', "%{$keyword}%");
    }

    // Accessor untuk mendapatkan nama lengkap dengan tahun
    public function getFullNameAttribute()
    {
        return $this->author_name . ($this->author_year ? ' (' . $this->author_year . ')' : '');
    }
}
