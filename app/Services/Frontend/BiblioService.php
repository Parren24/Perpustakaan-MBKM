<?php

namespace App\Services\Frontend;

use App\Services\Frontend\SiteIdentityService;
use App\Models\Biblio\Item;
use App\Models\Biblio\Biblio;
use App\Models\Biblio\Loan;
use App\Models\CartLoan;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class BiblioService
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

        $peminjamanSettings = data_get($identity, 'peminjaman_settings', []);

        return (object) [
            'title'       => data_get($peminjamanSettings, 'title', 'Peminjaman'),
            'description' => data_get($peminjamanSettings, 'description', 'Lakukan peminjaman buku.'),
            // Add more fields as necessary
        ];
    }

    public static function getMetaData(): array
    {
        $content = self::getContent();
        return [
            'title'       => data_get($content, 'title'),
            'description' => data_get($content, 'description'),
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
                'canonical'                  => route('frontend.biblio.index'),
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

    public static function getBiblioInformation($item_code)
    {
        try {

            // Validate input
            if (empty($item_code)) {
                return errResponse(400, 'Kode item tidak boleh kosong.');
            }

            // Ambil item dengan data lengkap menggunakan Eloquent
            $itemDetails = Item::getItemCode($item_code);

            if (!$itemDetails) {
                return errResponse(404, 'Buku dengan kode item "' . $item_code . '" tidak ditemukan di database.');
            }

            Log::info('Item found via Eloquent: ', ['item_id' => $itemDetails->item_id]);

            // Format data untuk respons dengan pengecekan yang lebih aman
            $responseData = [
                'item_code' => $itemDetails->item_code ?? 'N/A',
                'item_id' => $itemDetails->item_id ?? null,
                'barcode' => $itemDetails->item_code ?? 'N/A',
                'inventory_code' => $itemDetails->inventory_code ?? 'N/A',
                'call_number' => $itemDetails->call_number ?? 'N/A',
                'loan_status' => $itemDetails->item_status_id ?? '0',
                'status_name' => $itemDetails->status_name ?? 'Tersedia',
                'is_available' => $itemDetails->is_available ?? true,
                'site_detail' => $itemDetails->site ?? 'N/A',
                'input_date' => $itemDetails->input_date ?? null,
                'received_date' => $itemDetails->received_date ? $itemDetails->received_date->format('Y-m-d') : null,
                'price' => $itemDetails->formatted_price ?? 'N/A',
                'biblio_id' => $itemDetails->biblio_id ?? null,
            ];

            // Ambil informasi biblio dengan query yang disesuaikan dengan struktur database
            try {
                $biblioInfo = null;

                if ($itemDetails->biblio_id) {
                    // Query simple dulu untuk mendapatkan biblio basic info

                    $biblioInfo = Biblio::getBiblioInformation($itemDetails->biblio_id);

                    // Coba ambil author dari tabel terpisah jika ada
                    $authors = [];
                    try {

                        $authorRecords = Biblio::getAuthorRecords($itemDetails->biblio_id);

                        $authors = $authorRecords->pluck('author_name')->toArray();
                    } catch (\Exception $authorError) {
                    }

                    // Coba ambil publisher dari tabel terpisah jika ada
                    $publisherName = 'N/A';
                    try {
                        if (!empty($biblioInfo->publisher_id)) {

                            $publisher = Biblio::getPublisherName($biblioInfo->publisher_id);

                            $publisherName = $publisher ? $publisher->publisher_name : 'N/A';
                        }
                    } catch (\Exception $publisherError) {
                        Log::warning('Publisher table not found or error: ' . $publisherError->getMessage());
                    }
                }

                if ($biblioInfo) {
                    $responseData['biblio'] = [
                        'title' => $biblioInfo->title ?? 'N/A',
                        'author' => !empty($authors) ? implode(', ', $authors) : ($biblioInfo->sor ?? 'N/A'),
                        'isbn_issn' => $biblioInfo->isbn_issn ?? 'N/A',
                        'publisher' => $publisherName,
                        'publish_year' => $biblioInfo->publish_year ?? 'N/A',
                        'edition' => $biblioInfo->edition ?? 'N/A',
                        'series_title' => $biblioInfo->series_title ?? 'N/A',
                    ];
                } else {
                    // Set default biblio info jika tidak ditemukan
                    $responseData['biblio'] = [
                        'title' => 'Item: ' . $item_code,
                        'author' => 'Informasi tidak tersedia',
                        'isbn_issn' => 'N/A',
                        'publisher' => 'N/A',
                        'publish_year' => 'N/A',
                    ];
                }
            } catch (\Exception $biblioError) {
                $responseData['biblio'] = [
                    'title' => 'Item: ' . $item_code,
                    'author' => 'Error loading info: ' . $biblioError->getMessage(),
                    'isbn_issn' => 'N/A',
                    'publisher' => 'N/A',
                    'publish_year' => 'N/A',
                ];
            }

            return successResponse($responseData, 'Data Buku ditemukan.');
        } catch (\Illuminate\Database\QueryException $e) {
            return errResponse(500, 'Terjadi kesalahan pada database. Silakan coba lagi.');
        } catch (\Exception $e) {
            return errResponse(500, 'Terjadi kesalahan saat mencari data buku.');
        }
    }

    public static function authorizeSession($request)
    {
        try {
            $token = $request->input('token');

            if (!$token) {
                return errResponse(400, 'Token tidak boleh kosong.');
            }
            // Ambil session data dari cache (tidak menggunakan pull agar bisa dicek lagi)
            $cacheKey = 'user_token_' . $token;
            $sessionData = Cache::get($cacheKey);

            if (!$sessionData) {
                Log::warning('Invalid or expired token used', ['token' => substr($token, 0, 10) . '...']);

                return errResponse(400, 'Token tidak valid atau telah kedaluwarsa.');
            }

            // Validasi struktur data session
            // if (!isset($sessionData['nomor_induk']) || empty($sessionData['nomor_induk'])) {
            //     Log::error('authorizeSession: Invalid session data structure', [
            //         'session_data' => $sessionData,
            //         'missing_field' => 'nomor_induk'
            //     ]);
            //     return response()->json([
            //         'success' => false,
            //         'error' => 'Data token tidak valid. Nomor induk tidak ditemukan.'
            //     ], 400);
            // }

            // Test database connection first
            // try {
            //     DB::connection('mysql_opac')->getPdo();
            //     Log::info('authorizeSession: Database OPAC connection successful');
            // } catch (\Exception $e) {
            //     Log::error('authorizeSession: Database OPAC connection failed', ['error' => $e->getMessage()]);

            //     return errResponse(500, 'Koneksi database gagal. Silakan coba lagi nanti.');
            // }

            // Validasi member di database OPAC
            Log::info('authorizeSession: Looking for member', ['nomor_induk' => $sessionData['nomor_induk']]);

            $member = Biblio::getMemberId($sessionData['nomor_induk']);

            if (!$member) {
                return errResponse(404, 'Member tidak ditemukan atau tidak aktif.');
            }

            // Cek status peminjaman member
            try {
                $loanInfo = self::getMemberLoanInfo($sessionData['nomor_induk']);
            } catch (\Exception $e) {
                return errResponse(500, 'Gagal mengambil informasi peminjaman member: ' . $e->getMessage());
            }

            // Cek apakah ada pinjaman yang overdue
            if (isset($loanInfo['overdue_count']) && $loanInfo['overdue_count'] > 0) {
                return errResponse(403, 'Anda memiliki ' . $loanInfo['overdue_count'] . ' buku yang terlambat dikembalikan. Silakan kembalikan terlebih dahulu.');
            }

            // Cek apakah masih bisa meminjam
            if (isset($loanInfo['can_borrow']) && !$loanInfo['can_borrow']) {
                return errResponse(403, 'Anda sudah mencapai batas maksimal peminjaman harap dikembalikan terlebih dahulu(2 buku).');
            }

            // Set session untuk biblio user
            try {
                Session::put('biblio_user', [
                    'user_id' => $sessionData['user_id'],
                    'name' => $sessionData['name'],
                    'nomor_induk' => $sessionData['nomor_induk'],
                    'member_name' => $member->member_name ?? $sessionData['name'],
                    'authorized_at' => now()->toISOString(),
                    'session_expires_at' => now()->addMinutes(10)->toISOString()
                ]);
                Log::info('authorizeSession: Session set successfully');
            } catch (\Exception $e) {
                Log::error('authorizeSession: Error setting session', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);

                return errResponse(500, 'Gagal menyimpan session: ' . $e->getMessage());
            }

            // Hapus token dari cache untuk keamanan (one-time use)
            // try {
            //     Cache::forget($cacheKey);
            // } catch (\Exception $e) {
            //     Log::warning('authorizeSession: Error forgetting cache token', ['error' => $e->getMessage()]);
            //     // Don't fail the whole process for this
            // }

            try {
                $responseData = [
                    'member_name' => $member->member_name ?? $sessionData['name'],
                    'nomor_induk' => $sessionData['nomor_induk'],
                    'loan_info' => $loanInfo,
                    'session_expires_at' => now()->addMinutes(30)->toISOString()
                ];

                return successResponse($responseData, 'Sesi berhasil diverifikasi. Silakan scan barcode buku.');
            } catch (\Exception $e) {
                Log::error('authorizeSession: Error creating response', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return errResponse(500, 'Gagal membuat response: ' . $e->getMessage());
            }
        } catch (\Exception $e) {
            Log::error('Error in authorizeSession: ' . $e->getMessage(), [
                'token' => substr($token ?? '', 0, 10) . '...',
                'trace' => $e->getTraceAsString()
            ]);

            return errResponse(500, 'Terjadi kesalahan saat verifikasi sesi.');
        }
    }
    /**
     * Mendapatkan informasi peminjaman aktif member
     */
    public static function getMemberLoanInfo($nomorInduk)
    {
        try {
            $activeLoans = Loan::getMemberActiveLoans($nomorInduk);

            $activeLoanCount = $activeLoans->count();
            $overdueLoans = $activeLoans->filter(function ($loan) {
                return Carbon::parse($loan->due_date)->isPast();
            });

            return [
                'active_loan_count' => $activeLoanCount,
                'max_loan_limit' => 2,
                'remaining_slots' => max(0, 2 - $activeLoanCount),
                'can_borrow' => $activeLoanCount < 2,
                'active_loans' => $activeLoans,
                'overdue_count' => $overdueLoans->count(),
                'overdue_loans' => $overdueLoans
            ];
        } catch (\Exception $e) {
            return [
                'active_loan_count' => 0,
                'max_loan_limit' => 2,
                'remaining_slots' => 2,
                'can_borrow' => true,
                'active_loans' => collect(),
                'overdue_count' => 0,
                'overdue_loans' => collect(),
                'error' => 'Gagal mengambil informasi peminjaman'
            ];
        }
    }
}
