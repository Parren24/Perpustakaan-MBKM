<?php

namespace App\Models\Biblio;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Member extends Model
{
    use HasFactory;
    
    protected $connection = 'mysql_opac';
    protected $table = 'member';
    protected $primaryKey = 'member_id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'member_id',
        'member_name',
        'gender',
        'birth_date',
        'member_type_id',
        'member_address',
        'member_mail_address',
        'member_email',
        'postal_code',
        'inst_name',
        'is_new',
        'member_image',
        'pin',
        'member_phone',
        'member_fax',
        'member_since_date',
        'register_date',
        'expire_date',
        'member_notes',
        'is_pending',
        'mpasswd',
        'last_login',
        'last_login_ip',
        'input_date',
        'last_update'
    ];

    protected $casts = [
        'gender' => 'integer',
        'member_type_id' => 'integer',
        'is_new' => 'boolean',
        'is_pending' => 'boolean',
        'birth_date' => 'date',
        'member_since_date' => 'date',
        'register_date' => 'date',
        'expire_date' => 'date',
        'input_date' => 'date',
        'last_update' => 'date',
        'last_login' => 'datetime'
    ];

    protected $hidden = [
        'mpasswd',
        'pin'
    ];

    public static array $exceptEdit = [
        'input_date',
        'last_update',
        'last_login',
        'last_login_ip'
    ];

    /**
     * Relasi dengan Member Type (Many to One)
     */
    public function memberType()
    {
        return $this->belongsTo(MemberType::class, 'member_type_id', 'member_type_id');
    }

    /**
     * Relasi dengan Loans (One to Many)
     */
    public function loans()
    {
        return $this->hasMany(Loan::class, 'member_id', 'member_id');
    }

    /**
     * Relasi dengan Active Loans (One to Many) - Peminjaman yang belum dikembalikan
     */
    public function activeLoans()
    {
        return $this->hasMany(Loan::class, 'member_id', 'member_id')
                    ->where('is_return', 0);
    }

    /**
     * Relasi dengan Reservations (One to Many)
     */
    public function reservations()
    {
        return $this->hasMany(Reserve::class, 'member_id', 'member_id');
    }

    /**
     * Relasi dengan Fines (One to Many)
     */
    public function fines()
    {
        return $this->hasMany(Fine::class, 'member_id', 'member_id');
    }

    /**
     * Scope untuk member aktif (belum expired)
     */
    public function scopeActive($query)
    {
        return $query->where('expire_date', '>=', today())
                    ->where('is_pending', 0);
    }

    /**
     * Scope untuk member yang expired
     */
    public function scopeExpired($query)
    {
        return $query->where('expire_date', '<', today());
    }

    /**
     * Scope untuk member yang pending
     */
    public function scopePending($query)
    {
        return $query->where('is_pending', 1);
    }

    /**
     * Scope untuk member baru (new member)
     */
    public function scopeNewMembers($query)
    {
        return $query->where('is_new', 1);
    }

    /**
     * Scope untuk pencarian member berdasarkan nama
     */
    public function scopeSearchByName($query, $name)
    {
        return $query->where('member_name', 'like', '%' . $name . '%');
    }

    /**
     * Scope untuk filter berdasarkan gender
     */
    public function scopeByGender($query, $gender)
    {
        return $query->where('gender', $gender);
    }

    /**
     * Accessor untuk status member
     */
    public function getStatusAttribute()
    {
        if ($this->is_pending) {
            return 'pending';
        } elseif ($this->expire_date < today()) {
            return 'expired';
        } elseif ($this->is_new) {
            return 'new';
        } else {
            return 'active';
        }
    }

    /**
     * Accessor untuk gender label
     */
    public function getGenderLabelAttribute()
    {
        return $this->gender == 1 ? 'Laki-laki' : 'Perempuan';
    }

    /**
     * Accessor untuk age (umur)
     */
    public function getAgeAttribute()
    {
        return $this->birth_date ? $this->birth_date->age : null;
    }

    /**
     * Accessor untuk full address
     */
    public function getFullAddressAttribute()
    {
        $address = $this->member_address;
        if ($this->postal_code) {
            $address .= ' ' . $this->postal_code;
        }
        return $address;
    }

    /**
     * Accessor untuk days until expiry
     */
    public function getDaysUntilExpiryAttribute()
    {
        return today()->diffInDays($this->expire_date, false);
    }

    /**
     * Check if member can borrow (tidak ada denda, belum expired, dll)
     */
    public function canBorrow()
    {
        return $this->status === 'active' && 
               $this->fines()->where('debet', '>', 0)->count() === 0;
    }

    /**
     * Get total fine amount
     */
    public function getTotalFineAttribute()
    {
        return $this->fines()->sum('debet') - $this->fines()->sum('kredit');
    }

    /**
     * Mutator untuk member_name (capitalize)
     */
    public function setMemberNameAttribute($value)
    {
        $this->attributes['member_name'] = ucwords(strtolower($value));
    }

    /**
     * Mutator untuk member_email (lowercase)
     */
    public function setMemberEmailAttribute($value)
    {
        $this->attributes['member_email'] = strtolower($value);
    }
}