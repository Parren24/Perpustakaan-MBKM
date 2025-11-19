<?php

namespace App\Models\Biblio;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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

    public static function existingLoan($itemCode, $memberData)
    {
        return DB::connection('mysql_opac')
            ->table('loan')
            ->where('member_id', $memberData)
            ->where('item_code', $itemCode)
            ->where('is_return', 0)
            ->first();
    }

    public static function activeLoanCount($memberData)
    {
        return DB::connection('mysql_opac')
            ->table('loan')
            ->where('member_id', $memberData)
            ->where('is_return', 0)
            ->count();
    }

    public static function InsertDataTableLoan($cartItems, $memberData, $duedate)
    {
        return DB::connection('mysql_opac')->table('loan')->insertGetId([
            'member_id' => $memberData,
            'item_code' => $cartItems,
            'loan_date' => Carbon::now(),
            'due_date' => $duedate,
            'renewed' => 0,
            'is_lent' => 1,
            'is_return' => 0,
            'input_date' => Carbon::now(),
            'last_update' => Carbon::now(),
        ]);
    }

    public static function getMemberActiveLoans($memberId)
    {
        return DB::connection('mysql_opac')
            ->table('loan')
            ->select([
                'loan.loan_id',
                'loan.item_code',
                'loan.loan_date',
                'loan.due_date',
                'loan.renewed',
                'biblio.title',
                'biblio.sor as author'
            ])
            ->leftJoin('item', 'loan.item_code', '=', 'item.item_code')
            ->leftJoin('biblio', 'item.biblio_id', '=', 'biblio.biblio_id')
            ->where('loan.member_id', $memberId)
            ->where('loan.is_return', 0)
            ->orderBy('loan.loan_date', 'desc')
            ->get();
    }

    public static function getDataDetail($where = [], $get = true)
    {
        $user = Auth::user();
        $query = DB::connection('mysql_opac')
            ->table(DB::raw((new self())->table . ' as l'))
            ->leftJoin('member as m', 'l.member_id', '=', 'm.member_id')
            ->leftJoin('item as i', 'l.item_code', '=', 'i.item_code')
            ->leftJoin('biblio as b', 'i.biblio_id', '=', 'b.biblio_id')

            ->selectRaw('
                l.*,
                m.member_name,
                i.biblio_id,
                b.title
            ')
            ->where(function ($query) use ($where) {
                foreach ($where as $key => $value) {
                    if (is_array($value)) {
                        $query->whereIn($key, $value);
                    } else {
                        $query->where($key, $value);
                    }
                }
            });

        return $get ? $query->get() : $query;
    }
}
