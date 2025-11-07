<?php

namespace App\Services\Frontend;

use App\Services\Frontend\SiteIdentityService;
use App\Models\Biblio\Item;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collections\Collection;
use Carbon\Carbon;

class ItemService
{
    /**
     * 
     * Get content for Peminjaman page
     * 
     * @return object
     */
    public static function getContent(): object
    {
        $identity = SiteIdentityService::getSiteIdentity();
        $loanInfo = self::getLoanInfo();

        return (object) [
            'identity' => $identity,
            'loan_info' => $loanInfo,
        ];
    }

    public static function getMetaData(): array
    {
        $content = self::getContent();
        return [
            'title'       => data_get($content, 'title', 'Peminjaman Buku'),
            'description' => data_get($content, 'description', 'Lakukan Peminjaman Buku di Perpustakaan '),
            'keywords'    => 'peminjaman, fasilitas, peminjaman fasilitas, politeknik caltex riau',
        ];
    }

    public static function getPageConfig(): array
    {
        $meta = self::getMetaData();
        $bg   = publicMedia('peminjaman-bg.webp');

        return [
            'background_image' => $bg, // Or a specific image for peminjaman page
            'seo'              => [
                'title'                      => data_get($meta, 'title'),
                'description'                => data_get($meta, 'description'),
                'keywords'                   => 'peminjaman, fasilitas, peminjaman fasilitas, politeknik caltex riau',
                'canonical'                  => route('frontend.item.index'),
                'og_image'                   => $bg,
                'og_type'                    => 'website',
                'structured_data'            => self::getStructuredData($bg),
                'breadcrumb_structured_data' => self::getBreadcrumbStructuredData()
            ]
        ];
    }

    public static function getStructuredData($bg): array
    {
        $identy      = SiteIdentityService::getSiteIdentity();
        $contactInfo = SiteIdentityService::getContactInfo();
        $metaData    = self::getMetaData();

        return [
            '@context'     => 'https://schema.org',
            '@type'        => 'WebPage',
            'headline'     => $metaData['title'],
            'description'  => $metaData['description'],
            'inLanguage'   => 'id-ID',
            'publisher'    => [
                '@type' => 'Organization',
                'name'  => $identy->name,
                'logo'  => [
                    '@type' => 'ImageObject',
                    'url'   => data_get($identy, 'logo_path')
                ]
            ],
            'image'        => [
                '@type' => 'ImageObject',
                'url'   => $bg
            ]
        ];
    }

    public static function getBreadcrumbStructuredData(): array
    {
        return [
            '@context'        => 'https://schema.org',
            '@type'           => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type'    => 'ListItem',
                    'position' => 1,
                    'name'     => 'Beranda',
                    'item'     => url('/')
                ],
                [
                    '@type'    => 'ListItem',
                    'position' => 2,
                    'name'     => 'Peminjaman',
                    'item'     => route('frontend.peminjaman.index')
                ]
            ]
        ];
    }

    // public static function tokenConfirmation($token)
    // {
    //     // Logic for token confirmation
    //     $sessionToken = Cache::get($token);

    //     if (!$sessionToken) {
    //         return view('peminjaman.error', ['message' => 'Sesi peminjaman tidak valid atau telah kedaluwarsa. Silakan scan ulang QR di kiosk.']);
    //     }

    //     $item = Item::findOrFail($sessionToken['item_id']);

    //     // Example response
    //     return view('peminjaman.confirmation', ['item' => $item, 'token' => $token]);
    // }

    public static function borrowItem($request)
    {
        $user = Auth::user();
        $cacheKey = 'biblio_token_' . $request->session_token;
        $sessionData = Cache::get($cacheKey);

        if (!$sessionData) {
            return response()->json([
                'status' => 'error',
                'message' => 'Sesi peminjaman tidak valid atau telah kedaluwarsa.'
            ], 400);
        }
        // Validasi user harus login
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda harus login terlebih dahulu untuk meminjam buku.'
            ], 401);
        }

        if (!$user->nomor_induk) {
            return response()->json([
                'status' => 'error',
                'message' => 'Nomor induk tidak ditemukan. Silakan lengkapi profil Anda.'
            ], 400);
        }

        // Validasi input
        $request->validate([
            'session_token' => 'required|string',
        ]);


        // Cari item berdasarkan item_code
        $item = Item::where('item_code', $sessionData['item_code'])->first();

        if (!$item) {
            return response()->json([
                'status' => 'error',
                'message' => 'Item dengan kode tersebut tidak ditemukan.'
            ], 404);
        }

        // Cek ketersediaan item - gunakan method is_available dari model
        if (!$item->is_available) {
            return response()->json([
                'status' => 'error',
                'message' => 'Item tidak tersedia untuk dipinjam. Status: ' . $item->status_name
            ], 400);
        }

        // Cek batas maksimal peminjaman
        $activeLoanCount = self::getActiveLoanCount();
        if ($activeLoanCount >= 2) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda telah mencapai batas maksimal peminjaman (2 item). Mohon kembalikan item yang sudah dipinjam terlebih dahulu.'
            ], 400);
        }

        // Cek apakah user sudah meminjam item yang sama
        $existingLoan = DB::connection('mysql_opac')
            ->table('loan')
            ->where('member_id', $user->nomor_induk)
            ->where('item_code', $item->item_code)
            ->where('is_return', 0)
            ->exists();

        if ($existingLoan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda sudah meminjam item ini sebelumnya.'
            ], 400);
        }

        // Proses peminjaman dengan transaction
        DB::beginTransaction();
        try {
            // Update status item menjadi dipinjam (1)
            $item->item_status_id = '1';
            $item->save();

            // Insert record ke table loan
            $loanId = DB::connection('mysql_opac')->table('loan')->insertGetId([
                'item_code'   => $item->item_code,
                'member_id'   => $user->nomor_induk,
                'loan_date'   => Carbon::now(),
                'due_date'    => Carbon::now()->addDays(7),
                'renewed'     => 0,
                'is_lent'     => 1,
                'is_return'   => 0,
                'input_date'  => Carbon::now(),
                'last_update' => Carbon::now(),
            ]);

            DB::commit();

            // Log activity
            Log::info('Item borrowed successfully', [
                'user_id' => $user->id,
                'member_id' => $user->nomor_induk,
                'item_code' => $item->item_code,
                'loan_id' => $loanId
            ]);

            // Get biblio info for response
            $biblio = $item->biblio;

            return response()->json([
                'status' => 'success',
                'message' => 'Peminjaman berhasil! Buku harus dikembalikan dalam 7 hari.',
                'item' => [
                    'item_code' => $item->item_code,
                    'title' => $biblio ? $biblio->title : 'N/A',
                    'author' => $biblio ? $biblio->author : 'N/A',
                    'due_date' => Carbon::now()->addDays(7)->format('d/m/Y'),
                ],
                'loan_info' => [
                    'loan_id' => $loanId,
                    'remaining_slots' => 2 - ($activeLoanCount + 1)
                ]
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error during borrowing item: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'item_code' => $request->item_code,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat memproses peminjaman. Silakan coba lagi.'
            ], 500);
        }
    }

    public static function initiateUserToken()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'message' => 'Anda harus login terlebih dahulu untuk meminjam buku.'
            ], 401);
        }

        // Generate unique token
        $token = Str::uuid()->toString();

        // Simpan data sesi di cache selama 10 menit
        $cacheKey = 'user_token_' . $token;
        Cache::put($cacheKey, [
            'user_id'   => $user->id,
            'name'      => $user->name,
            'nomor_induk' => $user->nomor_induk
        ], now()->addMinutes(10));

        $verifyToken = cache()->get($cacheKey);
        if (!$verifyToken) {
            Log::error('Failed to store token in cache', ['token' => $token]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan token. Silakan coba lagi.'
            ], 500);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Token peminjaman berhasil dibuat.',
            'token' => $token,
            'expires_in' => 600, // 10 menit dalam detik
            'expiration' => now()->addMinutes(10)->toDateTimeString()
        ], 200);
    }

    /**
     * Mendapatkan jumlah peminjaman aktif (belum dikembalikan) oleh user yang sedang login
     * 
     * @return int
     */
    public static function getActiveLoanCount(): int
    {
        $user = Auth::user();

        if (!$user || !$user->nomor_induk) {
            return 0;
        }

        return DB::connection('mysql_opac')
            ->table('loan')
            ->where('member_id', $user->nomor_induk)
            ->where('is_return', 0)
            ->count();
    }

    /**
     * Mendapatkan detail peminjaman aktif oleh user yang sedang login
     * 
     * @return \Illuminate\Support\Collection
     */
    public static function getActiveLoans(): \Illuminate\Support\Collection
    {
        $user = Auth::user();

        if (!$user || !$user->nomor_induk) {
            return collect();
        }

        return DB::connection('mysql_opac')
            ->table('loan')
            ->select([
                'loan.loan_id',
                'loan.item_code',
                'loan.loan_date',
                'loan.due_date',
                'loan.renewed',
                'item.title',
                'biblio.title as biblio_title',
                'biblio.author'
            ])
            ->leftJoin('item', 'loan.item_code', '=', 'item.item_code')
            ->leftJoin('biblio', 'item.biblio_id', '=', 'biblio.biblio_id')
            ->where('loan.member_id', $user->nomor_induk)
            ->where('loan.is_return', 0)
            ->orderBy('loan.loan_date', 'desc')
            ->get();
    }

    /**
     * Mengecek apakah user masih bisa meminjam (belum mencapai batas maksimal)
     * 
     * @return bool
     */
    public static function canBorrow(): bool
    {
        return self::getActiveLoanCount() < 2;
    }

    /**
     * Mendapatkan informasi lengkap peminjaman user
     * 
     * @return array
     */
    public static function getLoanInfo(): array
    {
        $activeLoanCount = self::getActiveLoanCount();
        $activeLoans = self::getActiveLoans();
        $canBorrow = self::canBorrow();

        return [
            'active_loan_count' => $activeLoanCount,
            'max_loan_limit' => 2,
            'remaining_slots' => max(0, 2 - $activeLoanCount),
            'can_borrow' => $canBorrow,
            'active_loans' => $activeLoans,
            'overdue_loans' => $activeLoans->filter(function ($loan) {
                return Carbon::parse($loan->due_date)->isPast();
            })
        ];
    }

}
