<?php

namespace App\Models\Biblio;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Fine extends Model
{
    use HasFactory;
    
    protected $connection = 'mysql_opac';
    protected $table = 'fines';
    protected $primaryKey = 'fines_id';
    public $timestamps = false;

    protected $fillable = [
        'fines_date',
        'member_id',
        'debet',
        'credit',
        'loan_id',
        'count_overdue',
        'description'
    ];

    protected $casts = [
        'fines_id' => 'integer',
        'debet' => 'integer',
        'credit' => 'integer',
        'loan_id' => 'integer',
        'count_overdue' => 'integer',
        'fines_date' => 'date'
    ];

    /**
     * Relasi dengan Member (Many to One)
     */
    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'member_id');
    }

    /**
     * Scope untuk denda yang belum lunas (debet > 0)
     */
    public function scopeUnpaid($query)
    {
        return $query->where('debet', '>', 0);
    }

    /**
     * Scope untuk pembayaran denda (credit > 0)
     */
    public function scopePayments($query)
    {
        return $query->where('credit', '>', 0);
    }

    /**
     * Scope untuk denda hari ini
     */
    public function scopeToday($query)
    {
        return $query->whereDate('fines_date', today());
    }

    /**
     * Scope untuk denda berdasarkan member
     */
    public function scopeByMember($query, $memberId)
    {
        return $query->where('member_id', $memberId);
    }

    /**
     * Accessor untuk total amount (debet - credit)
     */
    public function getAmountAttribute()
    {
        return $this->debet - $this->credit;
    }

    public static function insertBatch($data = [])
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                self::create($value);
            }
        }
    }

    public static function deleteDataWhere($where)
    {
        $dt = self::where($where)->get();
        if ($dt)
            foreach ($dt as $key => $value)
                $value->delete();
    }

    public static function updateDataWhere($where, $data)
    {
        $dt = self::where($where)->get();
        if ($dt)
            foreach ($dt as $key => $value)
                $value->update($data);
    }

    /**
     * getDataDetail menggunakan query builder murni tanpa softdeletes
     */
    public static function getDataDetail($where = [], $whereBinding = [], $get = true)
    {
        $databasePeminjaman = config('database.connections.mysql_primary.database');
        
        $query = DB::connection('mysql_opac')
            ->table('fines')
            ->leftJoin('member as m', 'fines.member_id', '=', 'm.member_id')
            ->leftJoin('loan as l', 'fines.loan_id', '=', 'l.loan_id')
            ->leftJoin('item as i', 'l.item_code', '=', 'i.item_code')
            ->leftJoin('biblio as b', 'i.biblio_id', '=', 'b.biblio_id')
            ->selectRaw('
                fines.*,
                m.member_name as name,
                m.member_id as id,
                l.item_code,
                l.loan_date,
                l.due_date,
                b.title as biblio_title
            ')
            ->whereRaw(withRaw($where), $whereBinding);
            
        return $get ? $query->get() : $query;
    }

    public static function insertFine($memberId, $amount, $description, $loanId, $countOverdue)
    {
        return self::create([
            'fines_date' => now(),
            'member_id' => $memberId,
            'debet' => $amount,
            'credit' => 0,
            'loan_id' => $loanId,
            'description' => $description,
            'count_overdue' => $countOverdue
        ]);
    }
}