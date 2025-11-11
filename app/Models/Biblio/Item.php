<?php

namespace App\Models\Biblio;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $connection = 'mysql_opac';
    protected $table = 'item';
    protected $primaryKey = 'item_id';
    public $timestamps = false;

    protected $fillable = [
        'biblio_id',
        'call_number',
        'coll_type_id',
        'item_code',
        'inventory_code',
        'received_date',
        'supplier_id',
        'order_no',
        'location_id',
        'order_date',
        'item_status_id',
        'site',
        'source',
        'invoice',
        'price',
        'price_currency',
        'invoice_date',
        'uid'
    ];

    protected $casts = [
        'item_id' => 'integer',
        'biblio_id' => 'integer',
        'coll_type_id' => 'integer',
        'received_date' => 'date',
        'order_date' => 'date',
        'source' => 'integer',
        'price' => 'integer',
        'invoice_date' => 'date',
        'input_date' => 'datetime',
        'last_update' => 'datetime',
        'uid' => 'integer'
    ];

    // Relasi dengan Biblio (Many to One)
    public function biblio()
    {
        return $this->belongsTo(Biblio::class, 'biblio_id', 'biblio_id');
    }

    // Scope untuk item yang tersedia
    public function scopeAvailable($query)
    {
        return $query->where(function($q) {
            $q->where('item_status_id', 0)
              ->orWhere('item_status_id', '0')
              ->orWhereNull('item_status_id')
              ->orWhere('item_status_id', '');
        });
    }

    // Scope untuk item yang sedang diperbaiki
    public function scopeOnRepair($query)
    {
        return $query->where('item_status_id', 'R');
    }

    // Scope untuk item yang tidak bisa dipinjam
    public function scopeNoLoan($query)
    {
        return $query->where('item_status_id', 'NL');
    }

    // Scope untuk item yang hilang
    public function scopeMissing($query)
    {
        return $query->where('item_status_id', 'MIS');
    }

    // Scope untuk item berdasarkan lokasi
    public function scopeByLocation($query, $locationId)
    {
        return $query->where('location_id', $locationId);
    }

    // Scope untuk item berdasarkan status
    public function scopeByStatus($query, $status)
    {
        return $query->where('item_status_id', $status);
    }

    // Scope untuk pencarian berdasarkan kode item
    public function scopeSearch($query, $keyword)
    {
        return $query->where('item_code', 'LIKE', "%{$keyword}%")
            ->orWhere('inventory_code', 'LIKE', "%{$keyword}%")
            ->orWhere('call_number', 'LIKE', "%{$keyword}%");
    }

    // Konstanta status item
    const STATUS_AVAILABLE = 0;
    const STATUS_REPAIR = 'R';
    const STATUS_NO_LOAN = 'NL';
    const STATUS_MISSING = 'MIS';

    const ITEM_STATUSES = [
        0 => 'Tersedia',
        '0' => 'Tersedia', // Handle string '0' from database
        'R' => 'Diperbaiki',
        'NL' => 'Tidak Untuk Dipinjam',
        'MIS' => 'Hilang'
    ];

    // Accessor untuk mendapatkan nama status
    public function getStatusNameAttribute()
    {
        try {
            // Handle both integer 0 and string '0' as available status
            $status = $this->item_status_id;
            if ($status === 0 || $status === '0' || $status === null || $status === '') {
                $status = 0;
            }
            return self::ITEM_STATUSES[$status] ?? 'Status Tidak Diketahui';
        } catch (\Exception $e) {
            return 'Error Loading Status';
        }
    }

    // Accessor untuk check apakah item tersedia
    public function getIsAvailableAttribute()
    {
        try {
            $status = $this->item_status_id;
            // Available jika status = 0, '0', null, atau empty
            if ($status === 0 || $status === '0' || $status === null || $status === '') {
                return true;
            }
            // Tidak tersedia jika status = R, NL, atau MIS
            return !in_array($status, ['R', 'NL', 'MIS']);
        } catch (\Exception $e) {
            return false;
        }
    }

    // Accessor untuk format harga
    public function getFormattedPriceAttribute()
    {
        try {
            if ($this->price && $this->price > 0) {
                return ($this->price_currency ?? 'Rp') . ' ' . number_format($this->price, 0, ',', '.');
            }
            return 'Harga tidak tersedia';
        } catch (\Exception $e) {
            return 'Error loading price';
        }
    }

    public static function getItemCode($itemCode)
    {
        return self::where('item_code', $itemCode)->first();
    }

}
