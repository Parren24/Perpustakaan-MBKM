<?php

namespace App\Services\Frontend;

use App\Services\Frontend\SiteIdentityService;
use App\Models\Biblio\Item;
use App\Models\Biblio\Biblio;
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
    public static function getContenta(): object
    {
        $identity = SiteIdentityService::getSiteIdentity();

        $peminjamanSettings = data_get($identity, 'peminjaman_settings', []);

        return (object) [
            'title'       => data_get($peminjamanSettings, 'title', 'Peminjaman'),
            'description' => data_get($peminjamanSettings, 'description', 'Lakukan peminjaman buku.'),
            // Add more fields as necessary
        ];
    }

    public static function getMetaDataa(): array
    {
        $content = self::getContent();
        return [
            'title'       => data_get($content, 'title'),
            'description' => data_get($content, 'description'),
            'keywords'    => 'peminjaman, fasilitas, peminjaman fasilitas, politeknik caltex riau',
        ];
    }

    public static function getPageConfiga(): array
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

    public static function getStructuredDataa($bg): array
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

    public static function getBreadcrumbStructuredDataa(): array
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
            Log::info('Looking for item with code: ' . $item_code);

            // Validate input
            if (empty($item_code)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Kode item tidak boleh kosong.'
                ], 400);
            }

            // Test database connection first
            // try {
            //     DB::connection('mysql_opac')->getPdo();
            //     Log::info('Database OPAC connection successful');
            // } catch (\Exception $e) {
            //     Log::error('Database OPAC connection failed: ' . $e->getMessage());
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Koneksi database gagal. Silakan coba lagi nanti.'
            //     ], 500);
            // }

            // Ambil item dengan data lengkap menggunakan Eloquent
            $itemDetails = Item::where('item_code', $item_code)->first();

            if (!$itemDetails) {
                return response()->json([
                    'success' => false,
                    'message' => 'Buku dengan kode item "' . $item_code . '" tidak ditemukan di database.'
                ], 404);
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
                    $biblioInfo = DB::connection('mysql_opac')
                        ->table('biblio')
                        ->where('biblio_id', $itemDetails->biblio_id)
                        ->first();

                    Log::info('Biblio query result', [
                        'biblio_id' => $itemDetails->biblio_id,
                        'found' => $biblioInfo ? 'YES' : 'NO'
                    ]);

                    // Coba ambil author dari tabel terpisah jika ada
                    $authors = [];
                    try {
                        $authorRecords = DB::connection('mysql_opac')
                            ->table('mst_author')
                            ->join('biblio_author', 'mst_author.author_id', '=', 'biblio_author.author_id')
                            ->where('biblio_author.biblio_id', $itemDetails->biblio_id)
                            ->get(['author_name']);

                        $authors = $authorRecords->pluck('author_name')->toArray();
                        Log::info('Authors found: ' . count($authors));
                    } catch (\Exception $authorError) {
                        Log::warning('Author table not found or error: ' . $authorError->getMessage());
                    }

                    // Coba ambil publisher dari tabel terpisah jika ada
                    $publisherName = 'N/A';
                    try {
                        if (!empty($biblioInfo->publisher_id)) {
                            $publisher = DB::connection('mysql_opac')
                                ->table('mst_publisher')
                                ->where('publisher_id', $biblioInfo->publisher_id)
                                ->first(['publisher_name']);

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
                    Log::warning('No biblio information found for item: ' . $item_code);
                }
            } catch (\Exception $biblioError) {
                Log::error('Error fetching biblio info: ' . $biblioError->getMessage(), [
                    'item_code' => $item_code,
                    'biblio_id' => $itemDetails->biblio_id ?? null,
                    'trace' => $biblioError->getTraceAsString()
                ]);
                $responseData['biblio'] = [
                    'title' => 'Item: ' . $item_code,
                    'author' => 'Error loading info: ' . $biblioError->getMessage(),
                    'isbn_issn' => 'N/A',
                    'publisher' => 'N/A',
                    'publish_year' => 'N/A',
                ];
            }
            return response()->json([
                'success' => true,
                'data' => $responseData,
                'expires_in' => 180, // 3 menit dalam detik
                'message' => 'Data Buku ditemukan.'
            ]);
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database query error in getBiblioInformation: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan pada database. Silakan coba lagi.',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        } catch (\Exception $e) {
            Log::error('General error in getBiblioInformation: ' . $e->getMessage(), [
                'item_code' => $item_code,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mencari data buku.',
                'debug' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    public static function authorizeSession($request)
    {
        try {
            Log::info('authorizeSession: Method started');

            $token = $request->input('token');

            if (!$token) {
                Log::warning('authorizeSession: Empty token provided');
                return response()->json([
                    'success' => false,
                    'error' => 'Token tidak boleh kosong.'
                ], 400);
            }

            Log::info('authorizeSession: Processing token', ['token' => substr($token, 0, 10) . '...']);

            // Ambil session data dari cache (tidak menggunakan pull agar bisa dicek lagi)
            $cacheKey = 'user_token_' . $token;
            $sessionData = Cache::get($cacheKey);

            Log::info('authorizeSession: Cache lookup result', [
                'cache_key' => $cacheKey,
                'data_exists' => $sessionData ? 'YES' : 'NO',
                'data_structure' => $sessionData ? array_keys($sessionData) : 'NULL'
            ]);

            if (!$sessionData) {
                Log::warning('Invalid or expired token used', ['token' => substr($token, 0, 10) . '...']);
                return response()->json([
                    'success' => false,
                    'error' => 'Token tidak valid atau telah kedaluwarsa.'
                ], 400);
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
            try {
                DB::connection('mysql_opac')->getPdo();
                Log::info('authorizeSession: Database OPAC connection successful');
            } catch (\Exception $e) {
                Log::error('authorizeSession: Database OPAC connection failed', ['error' => $e->getMessage()]);
                return response()->json([
                    'success' => false,
                    'error' => 'Koneksi database gagal. Silakan coba lagi nanti.'
                ], 500);
            }

            // Validasi member di database OPAC
            Log::info('authorizeSession: Looking for member', ['nomor_induk' => $sessionData['nomor_induk']]);

            $member = DB::connection('mysql_opac')
                ->table('member')
                ->where('member_id', $sessionData['nomor_induk'])
                ->first();

            Log::info('authorizeSession: Member query result', [
                'member_id' => $sessionData['nomor_induk'],
                'member_found' => $member ? 'YES' : 'NO',
                'member_data' => $member ? ['id' => $member->member_id ?? 'N/A', 'name' => $member->member_name ?? 'N/A'] : 'NULL'
            ]);

            if (!$member) {
                Log::warning('Member not found or inactive', ['nomor_induk' => $sessionData['nomor_induk']]);
                return response()->json([
                    'success' => false,
                    'error' => 'Member tidak ditemukan atau tidak aktif.'
                ], 404);
            }

            // Cek status peminjaman member
            Log::info('authorizeSession: About to get member loan info', ['nomor_induk' => $sessionData['nomor_induk']]);
            try {
                $loanInfo = self::getMemberLoanInfo($sessionData['nomor_induk']);
                Log::info('authorizeSession: Loan info success', ['loan_info_keys' => array_keys($loanInfo)]);
            } catch (\Exception $e) {
                Log::error('authorizeSession: Error getting loan info', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'nomor_induk' => $sessionData['nomor_induk']
                ]);
                return response()->json([
                    'success' => false,
                    'error' => 'Gagal mengambil informasi peminjaman member: ' . $e->getMessage()
                ], 500);
            }

            // Cek apakah ada pinjaman yang overdue
            if (isset($loanInfo['overdue_count']) && $loanInfo['overdue_count'] > 0) {
                return response()->json([
                    'success' => false,
                    'error' => 'Anda memiliki ' . $loanInfo['overdue_count'] . ' buku yang terlambat dikembalikan. Silakan kembalikan terlebih dahulu.',
                    'overdue_books' => $loanInfo['overdue_loans'] ?? []
                ], 403);
            }

            // Cek apakah masih bisa meminjam
            if (isset($loanInfo['can_borrow']) && !$loanInfo['can_borrow']) {
                return response()->json([
                    'success' => false,
                    'error' => 'Anda sudah mencapai batas maksimal peminjaman harap dikembalikan terlebih dahulu(2 buku).',
                    'loan_info' => $loanInfo
                ], 403);
            }

            // Set session untuk biblio user
            Log::info('authorizeSession: About to set session');
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
                return response()->json([
                    'success' => false,
                    'error' => 'Gagal menyimpan session: ' . $e->getMessage()
                ], 500);
            }

            // // Hapus token dari cache untuk keamanan (one-time use)
            // Log::info('authorizeSession: About to forget cache token');
            // try {
            //     Cache::forget($cacheKey);
            //     Log::info('authorizeSession: Cache token forgotten successfully');
            // } catch (\Exception $e) {
            //     Log::warning('authorizeSession: Error forgetting cache token', ['error' => $e->getMessage()]);
            //     // Don't fail the whole process for this
            // }

            Log::info('authorizeSession: About to return success response');

            try {
                $responseData = [
                    'member_name' => $member->member_name ?? $sessionData['name'],
                    'nomor_induk' => $sessionData['nomor_induk'],
                    'loan_info' => $loanInfo,
                    'session_expires_at' => now()->addMinutes(30)->toISOString()
                ];

                Log::info('Member session authorized successfully', [
                    'member_id' => $sessionData['nomor_induk'],
                    'user_id' => $sessionData['user_id']
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Sesi berhasil diverifikasi. Silakan scan barcode buku.',
                    'data' => $responseData
                ], 200);
            } catch (\Exception $e) {
                Log::error('authorizeSession: Error creating response', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                return response()->json([
                    'success' => false,
                    'error' => 'Gagal membuat response: ' . $e->getMessage()
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Error in authorizeSession: ' . $e->getMessage(), [
                'token' => substr($token ?? '', 0, 10) . '...',
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan saat verifikasi sesi.'
            ], 500);
        }
    }

    public static function addBookToCartLoan($request)
    {
        try {
            // Periksa session member yang sudah ter-authorize
            $memberData = Session::get('biblio_user');
            if (!$memberData || !isset($memberData['user_id'])) {
                Log::warning('No valid user session found for cart addition');
                return response()->json([
                    'success' => false,
                    'error' => 'Sesi user tidak valid. Silakan scan QR code user terlebih dahulu.'
                ], 401);
            }

            // Validasi session harus punya nomor_induk (sudah ter-authorize)
            if (!isset($memberData['nomor_induk']) || empty($memberData['nomor_induk'])) {
                Log::warning('User session not authorized (no nomor_induk)', ['session' => $memberData]);
                return response()->json([
                    'success' => false,
                    'error' => 'Sesi user belum ter-otorisasi. Silakan scan QR code user terlebih dahulu.'
                ], 401);
            }

            $itemCode = $request->input('item_code');
            if (!$itemCode) {
                return response()->json([
                    'success' => false,
                    'error' => 'Kode item tidak boleh kosong.'
                ], 400);
            }

            // Cari item dengan validasi lengkap
            $itemBook = Item::with('biblio.authors')->where('item_code', $itemCode)->first();
            if (!$itemBook) {
                Log::warning('Item not found for cart addition', ['item_code' => $itemCode]);
                return response()->json([
                    'success' => false,
                    'error' => 'Buku dengan kode "' . $itemCode . '" tidak ditemukan.'
                ], 404);
            }

            // Cek ketersediaan buku
            if (!$itemBook->is_available) {
                return response()->json([
                    'success' => false,
                    'error' => 'Buku ini tidak tersedia untuk dipinjam. Status: ' . $itemBook->status_name
                ], 409);
            }

            // Cari atau buat cart untuk member
            $cart = CartLoan::firstOrCreate(
                ['member_id' => $memberData['user_id']],
                ['list_item' => []]
            );

            $currentItems = $cart->list_item ?? [];

            // Validasi batas maksimal
            if (count($currentItems) >= 2) {
                // return errResponse(400,'Batas maksimal 2 buku per peminjaman telah tercapai.');
                return response()->json([
                    'success' => false,
                    'error' => 'Batas maksimal 2 buku per peminjaman telah tercapai.'
                ], 400);
            }

            // Cek duplikasi item dalam cart
            foreach ($currentItems as $cartItem) {
                if ($cartItem['item_code'] === $itemCode) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Buku ini sudah ada dalam keranjang.'
                    ], 400);
                }
            }

            // Cek apakah user sudah meminjam item yang sama dan belum dikembalikan
            $existingLoan = DB::connection('mysql_opac')
                ->table('loan')
                ->where('member_id', $memberData['nomor_induk'])
                ->where('item_code', $itemCode)
                ->where('is_return', 0)
                ->exists();

            if ($existingLoan) {
                return response()->json([
                    'success' => false,
                    'error' => 'Anda sudah meminjam buku ini sebelumnya dan belum mengembalikannya.'
                ], 400);
            }

            // Tambahkan item ke cart
            $newItem = [
                'item_id' => $itemBook->item_id,
                'item_code' => $itemBook->item_code,
                'biblio_id' => $itemBook->biblio_id,
                'title' => $itemBook->biblio->title ?? 'N/A',
                'author' => $itemBook->biblio->author ?? 'N/A',
                'added_at' => now()->toISOString()
            ];

            $currentItems[] = $newItem;
            $cart->list_item = $currentItems;
            $cart->save();

            Log::info('Book added to cart successfully', [
                'member_id' => $memberData['user_id'],
                'item_code' => $itemCode,
                'cart_count' => count($currentItems)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Buku berhasil ditambahkan ke keranjang.',
                'data' => [
                    'cart_items' => $currentItems,
                    'total_items' => count($currentItems),
                    'remaining_slots' => 2 - count($currentItems)
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error adding book to cart: ' . $e->getMessage(), [
                'item_code' => $itemCode ?? null,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan saat menambahkan buku ke keranjang.'
            ], 500);
        }
    }

    public static function storeLoanTransaction($request)
    {
        DB::beginTransaction();

        try {
            // Validasi session member
            $memberData = Session::get('biblio_user');
            if (!$memberData || !isset($memberData['user_id'])) {
                return response()->json([
                    'success' => false,
                    'error' => 'Sesi member tidak valid atau telah kedaluwarsa.'
                ], 401);
            }

            // Validasi session harus sudah punya nomor_induk (ter-authorize)
            if (!isset($memberData['nomor_induk']) || empty($memberData['nomor_induk'])) {
                return response()->json([
                    'success' => false,
                    'error' => 'Session tidak valid. Nomor induk member tidak ditemukan.'
                ], 401);
            }

            // Ambil cart dan validasi
            $cart = CartLoan::where('member_id', $memberData['user_id'])->first();
            if (!$cart || empty($cart->list_item)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Keranjang peminjaman kosong.'
                ], 400);
            }

            $cartItems = $cart->list_item;

            // Validasi ulang ketersediaan semua item sebelum memproses
            $unavailableItems = [];
            foreach ($cartItems as $cartItem) {
                $item = Item::where('item_code', $cartItem['item_code'])->first();
                if (!$item || !$item->is_available) {
                    $unavailableItems[] = $cartItem['item_code'];
                }
            }

            if (!empty($unavailableItems)) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'error' => 'Beberapa buku tidak tersedia: ' . implode(', ', $unavailableItems)
                ], 400);
            }

            // Cek kembali batas maksimal peminjaman aktif
            $activeLoanCount = DB::connection('mysql_opac')
                ->table('loan')
                ->where('member_id', $memberData['nomor_induk'])
                ->where('is_return', 0)
                ->count();

            if ($activeLoanCount + count($cartItems) > 2) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'error' => 'Total peminjaman akan melebihi batas maksimal (2 buku).'
                ], 400);
            }

            $loanIds = [];
            $processedItems = [];

            // Proses setiap item dalam cart
            foreach ($cartItems as $cartItem) {
                // Update status item menjadi dipinjam
                $item = Item::where('item_code', $cartItem['item_code'])->first();
                $item->item_status_id = '1'; // Status dipinjam
                $item->save();

                // Insert ke table loan
                $loanId = DB::connection('mysql_opac')->table('loan')->insertGetId([
                    'item_code' => $cartItem['item_code'],
                    'member_id' => $memberData['nomor_induk'],
                    'loan_date' => Carbon::now(),
                    'due_date' => Carbon::now()->addDays(7),
                    'renewed' => 0,
                    'is_lent' => 1,
                    'is_return' => 0,
                    'input_date' => Carbon::now(),
                    'last_update' => Carbon::now(),
                ]);

                $loanIds[] = $loanId;
                $processedItems[] = [
                    'loan_id' => $loanId,
                    'item_code' => $cartItem['item_code'],
                    'title' => $cartItem['title'] ?? 'N/A',
                    'due_date' => Carbon::now()->addDays(7)->format('d/m/Y')
                ];

                Log::info('Loan transaction created', [
                    'loan_id' => $loanId,
                    'member_id' => $memberData['nomor_induk'],
                    'item_code' => $cartItem['item_code']
                ]);
            }

            // Hapus cart setelah berhasil
            $cart->forceDelete();

            // Clear session biblio user
            Session::forget('biblio_user');

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi peminjaman berhasil! Buku harus dikembalikan dalam 7 hari.',
                'data' => [
                    'loan_ids' => $loanIds,
                    'borrowed_items' => $processedItems,
                    'total_borrowed' => count($processedItems),
                    'due_date' => Carbon::now()->addDays(7)->format('d/m/Y'),
                    'return_reminder' => 'Jangan lupa mengembalikan buku tepat waktu untuk menghindari denda.'
                ]
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error in storeLoanTransaction: ' . $e->getMessage(), [
                'member_id' => $memberData['user_id'] ?? null,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan saat memproses peminjaman. Silakan coba lagi.'
            ], 500);
        }
    }

    /**
     * Ambil cart items untuk member yang sedang login
     */
    public static function getCartItems()
    {
        try {
            $memberData = Session::get('biblio_user');
            if (!$memberData || !isset($memberData['user_id'])) {
                return response()->json([
                    'success' => false,
                    'error' => 'Sesi member tidak valid atau telah kedaluwarsa.'
                ], 401);
            }

            $cart = CartLoan::where('member_id', $memberData['user_id'])->first();
            $items = $cart ? ($cart->list_item ?? []) : [];

            return response()->json([
                'success' => true,
                'data' => [
                    'cart_items' => $items,
                    'total_items' => count($items),
                    'remaining_slots' => 2 - count($items),
                    'can_add_more' => count($items) < 2
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error getting cart items: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan saat mengambil data keranjang.'
            ], 500);
        }
    }

    /**
     * Hapus item dari cart
     */
    public static function removeFromCart($request)
    {
        try {
            $memberData = Session::get('biblio_user');
            if (!$memberData || !isset($memberData['user_id'])) {
                return response()->json([
                    'success' => false,
                    'error' => 'Sesi member tidak valid atau telah kedaluwarsa.'
                ], 401);
            }

            $itemCode = $request->input('item_code');
            if (!$itemCode) {
                return response()->json([
                    'success' => false,
                    'error' => 'Kode item tidak boleh kosong.'
                ], 400);
            }

            $cart = CartLoan::where('member_id', $memberData['user_id'])->first();
            if (!$cart) {
                return response()->json([
                    'success' => false,
                    'error' => 'Keranjang tidak ditemukan.'
                ], 404);
            }

            $currentItems = $cart->list_item ?? [];
            $filteredItems = array_filter($currentItems, function ($item) use ($itemCode) {
                return $item['item_code'] !== $itemCode;
            });

            // Reindex array
            $cart->list_item = array_values($filteredItems);
            $cart->save();

            return response()->json([
                'success' => true,
                'message' => 'Item berhasil dihapus dari keranjang.',
                'data' => [
                    'cart_items' => $cart->list_item,
                    'total_items' => count($cart->list_item),
                    'remaining_slots' => 2 - count($cart->list_item)
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error removing item from cart: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan saat menghapus item dari keranjang.'
            ], 500);
        }
    }

    /**
     * Clear semua items dari cart
     */
    public static function clearCart()
    {
        try {
            $memberData = Session::get('biblio_user');
            if (!$memberData || !isset($memberData['user_id'])) {
                return response()->json([
                    'success' => false,
                    'error' => 'Sesi member tidak valid atau telah kedaluwarsa.'
                ], 401);
            }

            $cart = CartLoan::where('member_id', $memberData['user_id'])->first();
            if ($cart) {
                $cart->delete();
            }

            return response()->json([
                'success' => true,
                'message' => 'Keranjang berhasil dikosongkan.'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error clearing cart: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'Terjadi kesalahan saat mengosongkan keranjang.'
            ], 500);
        }
    }

    /**
     * Mendapatkan informasi peminjaman aktif member
     */
    public static function getMemberLoanInfo($nomorInduk)
    {
        try {
            $activeLoans = DB::connection('mysql_opac')
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
                ->where('loan.member_id', $nomorInduk)
                ->where('loan.is_return', 0)
                ->orderBy('loan.loan_date', 'desc')
                ->get();

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
            Log::error('Error getting member loan info: ' . $e->getMessage());
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
