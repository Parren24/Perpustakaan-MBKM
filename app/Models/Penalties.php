<?php

/*
 * Author: @wahyudibinsaid
 * Created At: {{currTime}}
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Activitylog\Facades\CauserResolver;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Penalties extends Model
{
    use SoftDeletes;
    use LogsActivity;
    /**
     * definisi nama table
     *
     * @var string
     */
    public $table = 'penalties';

    /**
     * set kolom primary key, default primary key kolom adalah id
     *
     * @var string
     */

    protected $connection = 'mysql_primary';
    protected $primaryKey = 'penalty_id';


    /**
     * kolom-kolom yang dapat di ubah data nya
     *
     * @var array
     */
    public $fillable = [
        'penalty_id',
        'loan_id',
        'user_id',
        'item_code',
        'loan_date',
        'due_date',
        'status',
        'approve_by',
        'approve_at',

        'amount',
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
        'penalty_id'   => 'integer',
        'loan_id'      => 'integer',
        'user_id'      => 'integer',
        'item_code'    => 'string',
        'loan_date'    => 'date',
        'due_date'     => 'date',
        'status'       => 'string',
        'approve_by'   => 'string',
        'approve_at'   => 'datetime',
        'amount'       => 'decimal:2',
    ];

    public static array $exceptEdit = [
        'penalty_id',
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
        $databasePeminjaman = config('database.connections.mysql_primary.database');
        $query = DB::connection('mysql_opac')
            ->table($databasePeminjaman . '.penalties as penalties')
           
            ->join($databasePeminjaman . '.users as u', 'penalties.user_id', '=', 'u.id')
            ->join('item as i', 'penalties.item_code', '=', 'i.item_code')
            ->join('biblio as b', 'i.biblio_id', '=', 'b.biblio_id')
            ->selectRaw('
                penalties.*,
                u.name,
                b.title as biblio_title
            ')
            ->whereRaw(withRaw($where), $whereBinding)
            ->whereNull('penalties.deleted_at');
        return $get ? $query->get() : $query;
    }

    public static function InsertDataPenalties($memberId, $itemCode, $loanDate, $dueDate, $amount, $loanId = null)
    {
        return self::create([
            'user_id'   => $memberId,
            'loan_id'   => $loanId,
            'item_code' => $itemCode,
            'loan_date' => $loanDate,
            'due_date'  => $dueDate,
            'status'    => 'PENDING',
            'amount'    => $amount,
        ]);
    }
}
/* This model generate by @wahyudibinsaid laravel best practices snippets */