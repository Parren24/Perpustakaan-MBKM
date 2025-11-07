<?php

namespace App\Models\Biblio;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Loan extends Model
{
    use HasFactory;
    
    protected $connection = 'mysql_opac';
    protected $table = 'loan';
    protected $primaryKey = 'loan_id';
    public $timestamps = false;

    protected $fillable = [
        'item_code',
        'member_id',
        'loan_date',
        'due_date',
        'renewed',
        'loan_rules_id',
        'actual',
        'is_lent',
        'is_return',
        'return_date',
        'input_date',
        'last_update',
        'uid'
    ];

    protected $casts = [
        'loan_id' => 'integer',
        'renewed' => 'integer',
        'loan_rules_id' => 'integer',
        'is_lent' => 'boolean',
        'is_return' => 'boolean',
        'uid' => 'integer',
        'loan_date' => 'date',
        'due_date' => 'date',
        'actual' => 'date',
        'return_date' => 'date',
        'input_date' => 'datetime',
        'last_update' => 'datetime'
    ];

    public static array $exceptEdit = [
        'loan_id',
        'input_date',
        'last_update'
    ];

    /**
     * Relasi dengan Member (Many to One)
     */
    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id', 'member_id');
    }

    /**
     * Relasi dengan Item (Many to One)
     */
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_code', 'item_code');
    }

    /**
     * Relasi dengan Loan Rules (Many to One)
     */
    public function loanRules()
    {
        return $this->belongsTo(LoanRules::class, 'loan_rules_id', 'loan_rules_id');
    }

    /**
     * Scope untuk peminjaman yang belum dikembalikan
     */
    public function scopeNotReturned($query)
    {
        return $query->where('is_return', 0);
    }

    /**
     * Scope untuk peminjaman yang sudah dikembalikan
     */
    public function scopeReturned($query)
    {
        return $query->where('is_return', 1);
    }

    /**
     * Scope untuk peminjaman yang terlambat (overdue)
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                    ->where('is_return', 0);
    }

    /**
     * Scope untuk peminjaman hari ini
     */
    public function scopeLoanToday($query)
    {
        return $query->whereDate('loan_date', today());
    }

    /**
     * Scope untuk pengembalian hari ini
     */
    public function scopeReturnToday($query)
    {
        return $query->whereDate('return_date', today());
    }

    /**
     * Accessor untuk status peminjaman
     */
    public function getStatusAttribute()
    {
        if ($this->is_return) {
            return 'returned';
        } elseif ($this->due_date < now()) {
            return 'overdue';
        } else {
            return 'on_loan';
        }
    }

    /**
     * Accessor untuk menghitung denda (jika terlambat)
     */
    public function getFineAmountAttribute()
    {
        if (!$this->is_return && $this->due_date < now()) {
            $daysLate = now()->diffInDays($this->due_date);
            // Asumsi denda per hari Rp 1000, bisa disesuaikan
            return $daysLate * 1000;
        }
        return 0;
    }

    /**
     * Mutator untuk set loan date
     */
    public function setLoanDateAttribute($value)
    {
        $this->attributes['loan_date'] = $value;
        if (empty($this->attributes['input_date'])) {
            $this->attributes['input_date'] = now();
        }
    }

    /**
     * Mutator untuk set return date
     */
    public function setReturnDateAttribute($value)
    {
        $this->attributes['return_date'] = $value;
        $this->attributes['is_return'] = 1;
        $this->attributes['last_update'] = now();
    }
}