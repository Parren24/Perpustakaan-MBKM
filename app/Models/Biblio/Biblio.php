<?php

namespace App\Models\Biblio;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Biblio extends Model
{
    use HasFactory;
    protected $connection = 'mysql_opac';

    protected $table = 'biblio';
    protected $primaryKey = 'biblio_id';
    public $timestamps = false;

    protected $fillable = [
        'gmd_id',
        'title',
        'sor',
        'edition',
        'isbn_issn',
        'publisher_id',
        'publish_year',
        'collation',
        'series_title',
        'call_number',
        'language_id',
        'source',
        'publish_place_id',
        'classification',
        'notes',
        'image',
        'file_att',
        'opac_hide',
        'promoted',
        'labels',
        'frequency_id',
        'spec_detail_info',
        'content_type_id',
        'media_type_id',
        'carrier_type_id',
        'uid'
    ];

    protected $casts = [
        'biblio_id' => 'integer',
        'gmd_id' => 'integer',
        'publisher_id' => 'integer',
        'publish_place_id' => 'integer',
        'opac_hide' => 'boolean',
        'promoted' => 'boolean',
        'frequency_id' => 'integer',
        'content_type_id' => 'integer',
        'media_type_id' => 'integer',
        'carrier_type_id' => 'integer',
        'uid' => 'integer',
        'input_date' => 'datetime',
        'last_update' => 'datetime'
    ];

    public static array $exceptEdit = [
        'biblio_id',
        'input_date',
        'last_update'
    ];

    // Relasi dengan Authors (Many to Many)
    public function authors()
    {
        return $this->belongsToMany(
            Author::class,
            'biblio_author',
            'biblio_id',
            'author_id'
        )->withPivot('level');
    }

    // Relasi dengan Topics/Subjects (Many to Many)
    public function topics()
    {
        return $this->belongsToMany(
            Topic::class,
            'biblio_topic',
            'biblio_id',
            'topic_id'
        )->withPivot('level');
    }

    // Relasi dengan Items/Collections (One to Many)
    public function items()
    {
        return $this->hasMany(Item::class, 'biblio_id', 'biblio_id');
    }

    // Relasi dengan GMD (Many to One)
    public function gmd()
    {
        return $this->belongsTo(Gmd::class, 'gmd_id', 'gmd_id');
    }

    // Relasi dengan Publisher (Many to One)
    public function publisher()
    {
        return $this->belongsTo(Publisher::class, 'publisher_id', 'publisher_id');
    }

    // Relasi dengan Language (Many to One)
    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id', 'language_id');
    }

    // Relasi dengan Place (Many to One)
    public function place()
    {
        return $this->belongsTo(Place::class, 'publish_place_id', 'place_id');
    }

    // Relasi dengan Attachments
    public function attachments()
    {
        return $this->hasMany(BiblioAttachment::class, 'biblio_id', 'biblio_id');
    }

    // Relasi dengan Related Biblios
    public function relations()
    {
        return $this->hasMany(BiblioRelation::class, 'biblio_id', 'biblio_id');
    }

    // Method untuk mendapatkan total stok
    public function getTotalStockAttribute()
    {
        return $this->items()->count();
    }

    // Method untuk mendapatkan stok tersedia
    public function getAvailableStockAttribute()
    {
        return $this->items()
            ->whereNotIn('item_status_id', ['LO', 'R', 'D'])
            ->count();
    }

    // Method untuk mendapatkan author (dari sor atau dari relasi authors)
    public function getAuthorAttribute()
    {
        // Coba ambil dari relasi authors terlebih dahulu
        if ($this->relationLoaded('authors') && $this->authors->isNotEmpty()) {
            return $this->authors->pluck('author_name')->implode(', ');
        }
        
        // Jika tidak ada, gunakan field sor (Statement of Responsibility)
        return $this->sor ?? 'Tidak diketahui';
    }

    // Scope untuk pencarian
    // public function scopeSearch($query, $keyword)
    // {
    //     return $query->where(function ($q) use ($keyword) {
    //         $q->where('title', 'LIKE', "%{$keyword}%")
    //             ->orWhere('classification', 'LIKE', "%{$keyword}%")
    //             ->orWhere('isbn_issn', 'LIKE', "%{$keyword}%")
    //             ->orWhereHas('authors', function ($author) use ($keyword) {
    //                 $author->where('author_name', 'LIKE', "%{$keyword}%");
    //             });
    //     });
    // }

    // Scope untuk filter berdasarkan availability
    public function scopeAvailable($query)
    {
        return $query->whereHas('items', function ($item) {
            $item->whereNotIn('item_status_id', ['LO', 'R', 'D']);
        });
    }

    // Scope untuk filter tidak disembunyikan dari OPAC
    public function scopeVisible($query)
    {
        return $query->where('opac_hide', 0);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uid = 1; // Set default user ID
            $model->input_date = now();
        });

        static::updating(function ($model) {
            $model->last_update = now();
        });
    }

    public static function getDataDetail($where = [], $whereBinding = [], $get = true)
    {
        $query = static::query()
            ->with(['authors', 'publisher', 'gmd', 'language', 'place', 'items'])
            ->leftJoin('item as i', 'biblio.biblio_id', '=', 'i.biblio_id')
            ->selectRaw('
                biblio.*,
                COUNT(i.item_id) as total_items,
                COUNT(CASE WHEN i.item_status_id NOT IN ("LO", "R", "D") THEN i.item_id END) as available_items
            ')
            ->where(function ($query) use ($where) {
                foreach ($where as $key => $value) {
                    if (is_array($value)) {
                        $query->whereIn($key, $value);
                    } else {
                        $query->where($key, $value);
                    }
                }
            })
            ->where('opac_hide', 0)
            ->groupBy('biblio.biblio_id');

        if (!empty($whereBinding)) {
            $query->whereRaw(implode(' AND ', $whereBinding));
        }

        return $get ? $query->get() : $query;
    }

    // Method untuk pencarian advanced
    public static function searchAdvanced($params = [])
    {
        $query = static::query()
            ->with(['authors', 'publisher', 'gmd', 'language', 'items'])
            ->visible();

        // Filter berdasarkan title
        if (!empty($params['title'])) {
            $query->where('title', 'LIKE', '%' . $params['title'] . '%');
        }

        // Filter berdasarkan author
        if (!empty($params['author'])) {
            $query->whereHas('authors', function ($q) use ($params) {
                $q->where('author_name', 'LIKE', '%' . $params['author'] . '%');
            });
        }

        // Filter berdasarkan publisher
        if (!empty($params['publisher'])) {
            $query->whereHas('publisher', function ($q) use ($params) {
                $q->where('publisher_name', 'LIKE', '%' . $params['publisher'] . '%');
            });
        }

        // Filter berdasarkan subject/topic
        if (!empty($params['subject'])) {
            $query->whereHas('topics', function ($q) use ($params) {
                $q->where('topic', 'LIKE', '%' . $params['subject'] . '%');
            });
        }

        // Filter berdasarkan ISBN
        if (!empty($params['isbn'])) {
            $query->where('isbn_issn', 'LIKE', '%' . $params['isbn'] . '%');
        }

        // Filter berdasarkan classification
        if (!empty($params['classification'])) {
            $query->where('classification', 'LIKE', '%' . $params['classification'] . '%');
        }

        // Filter berdasarkan GMD
        if (!empty($params['gmd_id'])) {
            $query->where('gmd_id', $params['gmd_id']);
        }

        // Filter berdasarkan language
        if (!empty($params['language_id'])) {
            $query->where('language_id', $params['language_id']);
        }

        // Filter berdasarkan tahun terbit
        if (!empty($params['publish_year'])) {
            $query->where('publish_year', $params['publish_year']);
        }

        // Filter hanya yang tersedia
        if (!empty($params['available_only'])) {
            $query->available();
        }

        return $query;
    }
}
