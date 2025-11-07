<?php

namespace App\Models\Biblio;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanRules extends Model
{
    use HasFactory;
    
    protected $connection = 'mysql_opac';
    protected $table = 'mst_loan_rules';
    protected $primaryKey = 'loan_rules_id';
    public $timestamps = false;

    protected $fillable = [
        'member_type_id',
        'coll_type_id',
        'gmd_id',
        'loan_limit',
        'loan_periode',
        'reborrow_limit',
        'fine_each_day',
        'grace_periode',
        'input_date',
        'last_update'
    ];

    protected $casts = [
        'loan_rules_id' => 'integer',
        'member_type_id' => 'integer',
        'coll_type_id' => 'integer',
        'gmd_id' => 'integer',
        'loan_limit' => 'integer',
        'loan_periode' => 'integer',
        'reborrow_limit' => 'integer',
        'fine_each_day' => 'integer',
        'grace_periode' => 'integer',
        'input_date' => 'date',
        'last_update' => 'date'
    ];

    /**
     * Relasi dengan Member Type (Many to One)
     */
    public function memberType()
    {
        return $this->belongsTo(MemberType::class, 'member_type_id', 'member_type_id');
    }

    /**
     * Relasi dengan GMD (Many to One)
     */
    public function gmd()
    {
        return $this->belongsTo(Gmd::class, 'gmd_id', 'gmd_id');
    }

    /**
     * Relasi dengan Collection Type (Many to One)
     */
    // public function collectionType()
    // {
    //     return $this->belongsTo(CollectionType::class, 'coll_type_id', 'coll_type_id');
    // }

    /**
     * Relasi dengan Loans (One to Many)
     */
    public function loans()
    {
        return $this->hasMany(Loan::class, 'loan_rules_id', 'loan_rules_id');
    }
}