<?php

namespace App\Models\Dimension;

use App\Models\Konten\KontenJurusan;
use App\Models\Konten\KontenProdi;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
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
    public $table = 'dm_prodi';

    /**
     * set kolom primary key, default primary key kolom adalah id
     *
     * @var string
     */
    protected $primaryKey = 'alias_prodi';

    /**
     * kolom-kolom yang dapat di ubah data nya
     *
     * @var array
     */

    /**
     * Menentukan apakah primary key adalah incrementing atau tidak.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * Tipe data dari primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';

    public $fillable = [
        'alias_prodi',
        'alias_jurusan',
        'nama_prodi',
        'deskripsi_prodi',
        'logo_prodi',
        'sync_log',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    /**
     * kolom yang tipe data hasil nya perlu diubah
     *
     * @var array
     */
    protected $casts = [];

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
                return userInisial() . " {$aksi} table :subject.dm_prodi";
            });
    }

    /**
     * fungsi kustom untuk menghasilkan data model secara detail (rinci) dengan seluruh kemungkinan join yang terjadi
     * fungsi ini akan menggunakan query builder secara langsung bukan ORM Eloquent
     * query builder pada data detail digunakan untuk optimasi hasil query yang lebih cepat
     * function detail ini biasa digunakan sebagai penyedia data untuk datatable
     *
     * @param  mixed $where
     */
    public static function getDataDetail($where = [], $whereBinding = [], $get = true)
    {
        $query = DB::table('')
            ->selectRaw('*')
            ->from((new self)->table . ' as a')
            ->where(notRaw($where))
            ->whereRaw(withRaw($where), $whereBinding)
            ->whereNull('a.deleted_at');
        return $get ? $query->get() : $query;
    }

    // Relasi Model
    public function jurusan()
    {
        return $this->hasOne(\App\Models\Dimension\Jurusan::class, 'alias_jurusan', 'alias_jurusan');
    }

    public function source()
    {
        return $this->belongsTo(\App\Models\View\Prodi::class, 'alias_prodi', 'alias');
    }
}
