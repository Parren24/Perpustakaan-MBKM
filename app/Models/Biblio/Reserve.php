<?php

namespace App\Models\Biblio;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserve extends Model
{
    use HasFactory;
    
    protected $connection = 'mysql_opac';
    protected $table = 'reserve';
    protected $primaryKey = 'reserve_id';
    public $timestamps = false;

    protected $fillable = [
        'member_id',
        'biblio_id',
        'item_code',
        'reserve_date'
    ];

    protected $casts = [
        'reserve_id' => 'integer',
        'biblio_id' => 'integer',
        'reserve_date' => 'datetime'
    ];

    /**
     * Relasi dengan Member (Many to One)
     */
    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'member_id');
    }

    /**
     * Relasi dengan Biblio (Many to One)
     */
    public function biblio()
    {
        return $this->belongsTo(Biblio::class, 'biblio_id', 'biblio_id');
    }

    /**
     * Relasi dengan Item (Many to One)
     */
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_code', 'item_code');
    }

    /**
     * Scope untuk reservasi hari ini
     */
    public function scopeToday($query)
    {
        return $query->whereDate('reserve_date', today());
    }

    /**
     * Scope untuk reservasi berdasarkan member
     */
    public function scopeByMember($query, $memberId)
    {
        return $query->where('member_id', $memberId);
    }
}