<?php

namespace App\Models\Post;

use App\Models\Views\Prodi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Query\JoinClause;

class PostKategori extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'post_kategori';
    protected $primaryKey = 'postkategori_id';

    public $fillable = [
        'kode_kategori',
        'nama_kategori',
        'deskripsi_kategori',
        'nama_kategori_en',
        'deskripsi_kategori_en',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $casts = [
        'created_by' => 'string',
        'updated_by' => 'string',
        'deleted_by' => 'string'
    ];

    public static array $exceptEdit = [
        'created_by',
        'updated_by',
        'deleted_by',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_by = userInisial();
        });

        static::updating(function ($model) {
            $model->updated_by = userInisial();
        });

        static::deleting(function ($model) {
            $model->deleted_by = userInisial();
            $model->update();
            // $model->prodi()->destroy();
        });
    }

    public static function getDataDetail($where = [], $is_get = true)
    {
    }

    /**
     * Get the posts for the PostKategori.
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'postkategori_id', 'postkategori_id');
    }

    /**
     * Get the post labels for the PostKategori.
     */
    public function postLabels(): HasMany
    {
        return $this->hasMany(PostLabel::class, 'postkategori_id', 'postkategori_id');
    }
}
