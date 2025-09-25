<?php

namespace App\Models\Post;

use App\Models\User;
use App\Models\Media;
use App\Models\Post\PostLabel;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'post';
    protected $primaryKey = 'post_id';

    public $fillable = [
        'bahasa',
        'level',
        'level_id',
        'postkategori_id',
        'judul_post',
        'isi_post',
        'tanggal_post',
        'slug_post',
        'user_id_author',
        'status_post',
        'meta_desc_post',
        'meta_keyword_post',
        'filename_post',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $casts = [
        'tanggal_post' => 'datetime',
        'created_by' => 'string',
        'updated_by' => 'string',
        'deleted_by' => 'string'
    ];

    public static array $exceptEdit = [
        'post_id',
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
        $query = DB::table(DB::raw((new self())->table . ' as p'))
            ->join((new PostKategori())->getTable() . ' as pk', function (JoinClause $join) {
                $join->on('pk.postkategori_id', '=', 'p.postkategori_id')
                    ->where('pk.deleted_at', '=', NULL);
            })
            ->selectRaw(
                'p.*, pk.nama_kategori, pk.kode_kategori,
                CASE
                    WHEN level = \'main-site\' THEN NULL
                    WHEN level = \'jurusan-site\' THEN (select alias from vw_referensi_jurusan where jurusan_id = level_id)
                    WHEN level = \'prodi-site\' THEN (select jenjang_pendidikan+\'-\'+alias from vw_referensi_prodi where prodi_id = level_id)
                    ELSE NULL
                END as level_alias,
                CAST(
                    (
                        select *
                        from (
                            select phl.postlabel_id, pl.nama_label, pl.kode_label
                            from post_has_label phl
                            join post_label pl on pl.postlabel_id = phl.postlabel_id
                            where phl.post_id = p.post_id
                        )data
                        for json auto
                    ) as VARCHAR(MAX)
                ) as post_label
                '
            )
            ->where([
                'p.deleted_at' => NULL
            ])
            ->where($where);
        return $is_get ? $query->get() : $query;
    }

    public function post_kategori()
    {
        return $this->belongsTo(PostKategori::class, 'postkategori_id', 'postkategori_id');
    }

    public function media()
    {
        return $this->belongsTo(Media::class, 'media_id_post', 'media_id');
    }
    /**
     * Get all of the labels for the Post.
     */
    public function postLabels(): BelongsToMany
    {
        return $this->belongsToMany(PostLabel::class, 'post_has_label', 'post_id', 'postlabel_id');
    }

    /**
     * Get the author that owns the Post.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id_author', 'user_id');
    }
}
