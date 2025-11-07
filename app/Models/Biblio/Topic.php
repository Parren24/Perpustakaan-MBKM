<?php

namespace App\Models\Biblio;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;

    protected $connection = 'mysql_opac';
    protected $table = 'mst_topic';
    protected $primaryKey = 'topic_id';
    public $timestamps = false;

    protected $fillable = [
        'topic',
        'topic_type',
        'auth_list',
        'classification'
    ];

    protected $casts = [
        'topic_id' => 'integer',
        'input_date' => 'date',
        'last_update' => 'date'
    ];

    // Relasi dengan Biblio (Many to Many)
    public function biblios()
    {
        return $this->belongsToMany(
            Biblio::class,
            'biblio_topic',
            'topic_id',
            'biblio_id'
        )->withPivot('level');
    }

    // Scope untuk filter berdasarkan tipe topic
    public function scopeByTopicType($query, $type)
    {
        return $query->where('topic_type', $type);
    }

    // Scope untuk pencarian
    public function scopeSearch($query, $keyword)
    {
        return $query->where('topic', 'LIKE', "%{$keyword}%")
            ->orWhere('classification', 'LIKE', "%{$keyword}%");
    }

    // Accessor untuk mendapatkan topic dengan klasifikasi
    public function getFullTopicAttribute()
    {
        return $this->topic . ($this->classification ? ' (' . $this->classification . ')' : '');
    }

    // Konstanta untuk topic types
    const TOPIC_TYPES = [
        't' => 'Topical',
        'g' => 'Geographic',
        'n' => 'Name',
        'tm' => 'Temporal',
        'gr' => 'Genre/Form',
        'oc' => 'Occupation'
    ];

    // Accessor untuk mendapatkan nama tipe topic
    public function getTopicTypeNameAttribute()
    {
        return self::TOPIC_TYPES[$this->topic_type] ?? 'Unknown';
    }
}
