<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\DB;
use App\Models\Biblio\Member;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        static::updated(function (User $user) {
            // Check if name has changed and nomor_induk is present
            if ($user->isDirty('name') && !empty($user->nomor_induk)) {
                try {
                    // Update member name in perpus_opac database
                    $member = Member::where('member_id', $user->nomor_induk)->first();
                    if ($member) {
                        $member->member_name = $user->name;
                        $member->last_update = now()->toDateString();
                        $member->save();
                    }
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Failed to sync member name: ' . $e->getMessage());
                }
            }
        });
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    public static array $exceptEdit = [
        'id',
        'email_verified_at',
        'password',
        'posisi',
        'inisial',
        'prodi',
        'remember_token',
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'name',
        'nomor_induk',
        'email',
        'password',
        'posisi',
        'inisial',
        'prodi',
        'google_id', // <-- Tambahkan ini
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public static function getLatestItemLoanedByUser($memberId)
    {
        if (empty($memberId)) {
            return null;
        }

        try {
            return DB::connection('mysql_opac')
                ->table('loan')
                ->join('item', 'loan.item_code', '=', 'item.item_code')
                ->join('biblio', 'item.biblio_id', '=', 'biblio.biblio_id')
                ->select('biblio.title', 'item.item_code', 'loan.loan_date', 'loan.due_date')
                ->where('loan.member_id', $memberId)
                // ->where('DATE(loan.loan_date)', DB::raw("(SELECT DATE(MAX(loan_date)) FROM loan WHERE member_id = '" . $memberId . "')"))
                ->orderBy('loan.loan_date', 'DESC')
                ->limit(5)
                ->get();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error fetching loan history: ' . $e->getMessage());
            return null;
        }
    }
}
