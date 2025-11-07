<?php

namespace App\Models\Biblio;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberType extends Model
{
    use HasFactory;
    
    protected $connection = 'mysql_opac';
    protected $table = 'mst_member_type';
    protected $primaryKey = 'member_type_id';
    public $timestamps = false;

    protected $fillable = [
        'member_type_name',
        'loan_limit',
        'loan_periode',
        'enable_reserve',
        'reserve_limit',
        'member_periode',
        'reborrow_limit',
        'fine_each_day',
        'grace_periode',
        'input_date',
        'last_update'
    ];

    protected $casts = [
        'member_type_id' => 'integer',
        'loan_limit' => 'integer',
        'loan_periode' => 'integer',
        'enable_reserve' => 'boolean',
        'reserve_limit' => 'integer',
        'member_periode' => 'integer',
        'reborrow_limit' => 'integer',
        'fine_each_day' => 'integer',
        'grace_periode' => 'integer',
        'input_date' => 'date',
        'last_update' => 'date'
    ];

    /**
     * Relasi dengan Members (One to Many)
     */
    public function members()
    {
        return $this->hasMany(Member::class, 'member_type_id', 'member_type_id');
    }

    /**
     * Relasi dengan Loan Rules (One to Many)
     */
    public function loanRules()
    {
        return $this->hasMany(LoanRules::class, 'member_type_id', 'member_type_id');
    }
}