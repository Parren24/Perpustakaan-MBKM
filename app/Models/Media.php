<?php

namespace App\Models;

use App\Models\Views\Prodi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Query\JoinClause;

class Media extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'media';
    protected $primaryKey = 'media_id';

    public $fillable = [
        'mimetype_media',
        'nama_media',
        'filepath_media',
        'thumb_media',
        'ext_media',
        'filetype_media',
        'filesize_media',
        'info_media',
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

    public static function getDataDetail($where = [], $is_get = true) {}
}
