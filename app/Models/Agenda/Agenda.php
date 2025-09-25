<?php

/*
 * Author: @wahyudibinsaid
 * Created At: {{currTime}}
 */

namespace App\Models\Agenda;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Facades\CauserResolver;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Agenda extends Model
{
    use SoftDeletes;
    use LogsActivity;
    /**
     * definisi nama table
     *
     * @var string
     */
    public $table = 'agenda';

    /**
     * set kolom primary key, default primary key kolom adalah id
     *
     * @var string
     */
    protected $primaryKey = 'agenda_id';


    /**
     * kolom-kolom yang dapat di ubah data nya
     *
     * @var array
     */
    public $fillable = [
        'agendakategori_id',
        'level',
        'level_id',
        'nama_agenda',
        'deskripsi_agenda',
        'tanggal_start_agenda',
        'tanggal_end_agenda',
        'waktu_start_agenda',
        'waktu_end_agenda',
        'lokasi_agenda',
        'url_agenda',
        'status_agenda',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    /**
     * kolom yang tipe data hasil nya perlu diubah
     *
     * @var array
     */
    protected $casts = [
        'agenda_id'    => 'string',

    ];

    public static array $exceptEdit = [
        'agenda_id',
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

        static::creating(function ($model) {
            $model->created_by = userInisial();
        });

        static::updating(function ($model) {
            $model->updated_by = userInisial();
        });

        static::deleting(function ($model) {
            $model->deleted_by = userInisial();
            $model->update();
        });

        static::restoring(function ($model) {
            $model->deleted_by = NULL;
        });
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
        $query = DB::table('')
            ->selectRaw('a.*,
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
                            select ahk.agendakategori_id, ak.nama_kategori, ak.kode_kategori
                            from agenda_has_kategori ahk
                            join agenda_kategori ak on ak.agendakategori_id = ahk.agendakategori_id
                            where ahk.agenda_id = a.agenda_id
                        )data
                        for json auto
                    ) as VARCHAR(MAX)
                ) as agenda_kategori')
            ->from((new self)->table . ' as a')
            ->where(notRaw($where))
            ->whereRaw(withRaw($where), $whereBinding)
            ->whereNull('a.deleted_at');
        return $get ? $query->get() : $query;
    }
}
/* This model generate by @wahyudibinsaid laravel best practices snippets */
