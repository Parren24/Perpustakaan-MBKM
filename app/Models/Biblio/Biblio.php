<?php

namespace App\Models\Biblio;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Biblio extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'biblio';
    protected $primaryKey = 'biblio_id';

    protected $fillable = [
        'title',
        'author',
        'description',
        'year',
        'publisher',
        'stock',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $casts = [
        'year' => 'integer',
        'stock' => 'integer',
        'created_by' => 'string',
        'updated_by' => 'string',
        'deleted_by' => 'string'
    ];

    public static array $exceptEdit = [
        'biblio_id',
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

    public static function getDataDetail($where = [], $whereBinding = [], $get = true)
    {
        $query = DB::table((new self)->table . ' as a')
            ->selectRaw(
                'a.biblio_id,
                a.title,
                a.author,
                a.description,
                a.year,
                a.publisher,
                a.stock,
                a.created_at,
                a.updated_at'
            )
            ->where(notRaw($where))
            ->whereRaw(withRaw($where), $whereBinding)
            ->whereNull('a.deleted_at');

        return $get ? $query->get() : $query;
    }
}
