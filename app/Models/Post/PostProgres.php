<?php

namespace App\Models\Post;

use App\Models\Views\Prodi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Query\JoinClause;

class PostProgres extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'post_progres';
    protected $primaryKey = 'postprogres_id';

    public $fillable = [
        'post_id',
        'status_progres',
        'keterangan_progres',
        'catatan_progres',
        'user_id_progres',
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
        'postprogres_id',
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
