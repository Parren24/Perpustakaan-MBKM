<?php

/*
 * Author: @wahyudibinsaid
 * Created At: {{currTime}}
 */

namespace App\Models\Post;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Facades\CauserResolver;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class PostSlugRedirect extends Model
{
    use LogsActivity;
    /**
     * definisi nama table
     *
     * @var string
     */
    public $table = 'post_slug_redirect';


    /**
     * kolom-kolom yang dapat di ubah data nya
     *
     * @var array
     */
    public $fillable = [
        'post_id',
        'old_slug',
    ];

    /**
     * Menonaktifkan timestamps (created_at dan updated_at)
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Menonaktifkan auto-incrementing ID karena menggunakan composite primary key.
     *
     * @var bool
     */
    protected $primaryKey = null;
    public $incrementing = false;

    /**
     * Define composite primary keys
     *
     * @var array
     */
    // protected $primaryKey = ['post_id', 'postlabel_id'];

    /**
     * Define the primary key column type
     *
     * @var string
     */
    protected $keyType = 'string';

    /**
     * fungsi yang di panggil setelah proses crud selesai dijalankan (event trigger) untuk proses pencatatan log
     * pencatatan log menggunakan spatie/activitylogging
     *
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        CauserResolver::setCauser(causerActivityLog());

        return LogOptions::defaults()
            ->logOnly($this->fillable)
            ->logOnlyDirty()
            ->useLogName(env('APP_NAME'))
            ->setDescriptionForEvent(function ($eventName) {
                $aksi = eventActivityLogBahasa($eventName);
                return userInisial() . " {$aksi} table :subject.{{tableSubject}}";
            });
    }
}
/* This model generate by @wahyudibinsaid laravel best practices snippets */
