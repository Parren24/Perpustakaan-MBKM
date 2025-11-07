<?php

namespace App\Models\Biblio;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'description'
    ];

    protected $casts = [
        'fines_id' => 'integer',
        'debet' => 'integer',
        'credit' => 'integer',
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
}