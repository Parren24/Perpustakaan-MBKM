<?php

/*
 * Author: @wahyudibinsaid
 * Created At: {{currTime}}
 */

namespace App\Models\Konten;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Facades\CauserResolver;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Konten extends Model
{
    use SoftDeletes;
    use LogsActivity;
    /**
     * definisi nama table
     *
     * @var string
     */
    public $table = 'konten';

    /**
     * set kolom primary key, default primary key kolom adalah id
     *
     * @var string
     */
    protected $primaryKey = 'konten_id';


    /**
     * kolom-kolom yang dapat di ubah data nya
     *
     * @var array
     */
    public $fillable = [
        'page_name',
        'page_id',
        'data_specification',
        'data_values',
    ];

    /**
     * kolom yang tipe data hasil nya perlu diubah
     *
     * @var array
     */
    protected $casts = [
        'data_specification' => 'object',
        'data_values' => 'object',
    ];

    public static array $exceptEdit = [
        'konten_id',
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

    /**
     * fungsi untuk query data
     */
    public static function queryData($pageName, $pageId = null, $getMany = false)
    {
        if (!$pageName) {
            return null;
        }

        $query = self::where('page_name', $pageName);

        if ($getMany) {
            return $query->get();
        }

        if ($pageId) {
            $query->where('page_id', $pageId);
        }

        $data = $query->first();

        if (!$data) {
            return null;
        }

        return $data;
    }

    /**
     * fungsi untuk memformat spesifikasi ke value
     *
     * @return object|array|null
     */
    public static function getSectionValues($pageName, $pageId = null, $asObjects = true, $getMany = false)
    {
        $data = self::queryData($pageName, $pageId, $getMany);

        if (!$data) {
            return null;
        }

        // Function mapping data
        $formatter = fn($item) => $asObjects ? (object) self::formatValues($item) : self::formatValues($item);

        // Melakukan mapping data
        $data = $getMany ? $data->map($formatter) : $formatter($data);

        return $data;
    }

    /**
     * Untuk melakukan mapping data values berdasarkan spesifikasi
     * @return array
     */
    private static function formatValues($data)
    {
        if (!$data) {
            return [];
        }

        $dataSpecification = $data->data_specification ?? (object) [];
        $dataValues = $data->data_values ?? (object) [];

        // Map dataSpecification ke dataValues
        // Selama spesifikiasi dan value memiliki index yang sama
        $formattedValues = [];

        foreach ($dataSpecification->section as $index => $specification) {
            $sectionData = $dataValues->section ?? [];

            $name = $specification->name ?? null;
            if ($name && isset($sectionData[$index])) {
                $formattedValues[\Illuminate\Support\Str::lower($name)] = $sectionData[$index];
            }
        }

        return (array) $formattedValues;
    }

    /**
     * fungsi untuk mendapatkan data header
     */
    public static function getHeader($pageName, $pageId = null)
    {
        $data = self::queryData($pageName, $pageId);

        return $data->data_values->header ?? (object) [];
    }
}
/* This model generate by @wahyudibinsaid laravel best practices snippets */
