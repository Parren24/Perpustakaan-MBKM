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

class KontenTipe extends Model
{
    use SoftDeletes;
    use LogsActivity;
    /**
     * definisi nama table
     *
     * @var string
     */
    public $table = 'konten_tipe';

    /**
     * set kolom primary key, default primary key kolom adalah id
     *
     * @var string
     */
    protected $primaryKey = 'kontentipe_id';


    /**
     * kolom-kolom yang dapat di ubah data nya
     *
     * @var array
     */
    public $fillable = [
        'kode_tipe',
        'nama_tipe',
        'deskripsi_tipe',
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
        'kontentipe_id'    => 'string',
    ];

    public static array $exceptEdit = [
        // 'kontentipe_id',
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
            ->join('vw_referensi_prodi as p', function (JoinClause $join) {
                $join->on('p.prodi_id', '=', 'a.prodi_id');
            })
            ->where(notRaw($where))
            ->whereRaw(withRaw($where), $whereBinding)
            ->whereNull('a.deleted_at');
        return $get ? $query->get() : $query;
    }
}
/* This model generate by @wahyudibinsaid laravel best practices snippets */
