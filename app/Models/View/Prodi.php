<?php

/*
 * Author: @wahyudibinsaid
 * Created At: {{currTime}}
 */

namespace App\Models\View;

use App\Models\ReferensiViews\JenisMahasiswa;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Facades\CauserResolver;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Prodi extends Model
{
    use SoftDeletes;
    use LogsActivity;
    /**
     * definisi nama table
     *
     * @var string
     */
    public $table = 'vw_referensi_prodi';

    /**
     * set kolom primary key, default primary key kolom adalah id
     *
     * @var string
     */
    protected $primaryKey = 'prodi_id';

    /**
     * fungsi yang di panggil saat event crud dijalankan
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {});

        static::updating(function ($model) {});

        static::deleting(function ($model) {});

        static::restoring(function ($model) {});
    }

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
                return userInisial() . " {$aksi} table :subject.vw_referensi_prodi";
            });
    }
}
/* This model generate by @wahyudibinsaid laravel best practices snippets */
