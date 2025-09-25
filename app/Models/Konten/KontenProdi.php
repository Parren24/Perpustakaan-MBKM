<?php

/*
 * Author: @wahyudibinsaid
 * Created At: {{currTime}}
 */

namespace App\Models\Konten;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Facades\CauserResolver;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class KontenProdi extends Model
{
    use SoftDeletes;
    use LogsActivity;
    /**
     * definisi nama table
     *
     * @var string
     */
    public $table = 'konten_prodi';

    /**
     * set kolom primary key, default primary key kolom adalah id
     *
     * @var string
     */
    protected $primaryKey = 'kontenprodi_id';


    /**
     * kolom-kolom yang dapat di ubah data nya
     *
     * @var array
     */
    public $fillable = [
        'alias_prodi',
        'header_prodi',
        'tentang_prodi',
        'prospek_karir_prodi',
        'milestone_prodi',
        'visi_prodi',
        'misi_prodi',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    /**
     * kolom yang tipe data hasil nya perlu diubah
     *
     * @var array
     */
    protected $casts = [
        'kontenprodi_id' => 'string',
        'tentang_prodi' => 'object',
        'sambutan_prodi' => 'object',
        'akreditasi_prodi' => 'object',
        'prospek_karir_prodi' => 'object',
        'milestone_prodi' => 'object',
        'visi_prodi' => 'object',
        'misi_prodi' => 'object',
    ];

    public static array $exceptEdit = [
        'kontenprodi_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

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
                return userInisial() . " {$aksi} table :subject.{{tableSubject}}";
            });
    }

    // mutator (setter and getter)

    /**
     * fungsi kustom, untuk proses insert multiple data row dari controller
     * proses insert dilakukan berulang agar event trigger dari ORM dijalankan
     * event trigger diperlukan untuk proses pencatatan logging model secara otomatis
     *
     * @param  mixed $data
     * @return void
     */
    public static function insertBatch($data = [])
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                self::create($value);
            }
        }
    }

    /**
     * fungsi kustom, untuk proses hapus data dengan kondisi (where option)
     * kondisi where akan melakukan hapus data belalui query builder
     * proses hapus akan dilakukan berulang untuk setiap data dengan looping
     * penghapusan dilakukan berulang agar event trigger dari ORM dijalankan
     * event trigger diperlukan untuk proses pencatatan logging model secara otomatis
     *
     * @param  mixed $where
     * @return void
     */
    public static function deleteDataWhere($where)
    {
        $dt = self::where($where)->get();
        if ($dt)
            foreach ($dt as $key => $value)
                $value->delete();
    }

    /**
     * fungsi kustom, untuk proses update data dengan kondisi (where option)
     * kondisi where akan melakukan update data belalui query builder
     * proses update akan dilakukan berulang untuk setiap data dengan looping
     * pembaruan data dilakukan berulang agar event trigger dari ORM dijalankan
     * event trigger diperlukan untuk proses pencatatan logging model secara otomatis
     *
     * @param  mixed $where
     * @param  mixed $data
     * @return void
     */
    public static function updateDataWhere($where, $data)
    {
        $dt = self::where($where)->get();
        if ($dt)
            foreach ($dt as $key => $value)
                $value->update($data);
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
        $where["p.alias not in ('TK','TE','TM','AKT','TT')"] = '';

        $query = DB::table('')
            ->selectRaw('*')
            ->from((new self)->table . ' as a')
            ->join('dm_prodi as p', function (JoinClause $join) {
                $join->on('p.alias_prodi', '=', 'a.alias_prodi');
            })
            ->where(notRaw($where))
            ->whereRaw(withRaw($where), $whereBinding)
            ->whereNull('a.deleted_at');
        return $get ? $query->get() : $query;
    }

    // Relasi Model
    public function prodi()
    {
        return $this->belongsTo(\App\Models\Dimension\Prodi::class, 'alias_prodi', 'alias_prodi');
    }

    public function jurusan()
    {
        return $this->hasOneThrough(
            \App\Models\Dimension\Jurusan::class,        // Final model
            \App\Models\Dimension\Prodi::class,          // Intermediate model
            'alias_prodi',                               // Foreign key on intermediate model
            'alias_jurusan',                             // Foreign key on final model
            'alias_prodi',                               // Local key on this model
            'alias_jurusan'                              // Local key on intermediate model
        );
    }
}
/* This model generate by @wahyudibinsaid laravel best practices snippets */
