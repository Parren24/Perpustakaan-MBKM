<?php

/**
 * Helper functions untuk frontend.
 *
 * Secara umum, berikut sekilas fungsi-fungsi yang ada:
 * - `smartSplit`: Memecah string menjadi array secara cerdas (menggunakan panjang efektif).
 * - `getSmartLength`: Menghitung "panjang efektif" dari sebuah string dengan mengabaikan karakter spesial dan tag HTML.
 * - `extractFirstElement`: Untuk mengambil data dari model Konten yang hanya memiliki satu elemen.
 * - `extractEachFirstElement`: Untuk mengambil data dari model Konten yang lebih dari satu elemen.
 * - `kontenMapping`: Melakukan mapping data dari model Konten ke sebuah objek atau array secara efisien.
 */

/**
 * Memecah string menjadi larik baris berdasarkan aturan cerdas.
 *
 * @param string $text Teks masukan yang akan dipecah.
 * @param int $maxLength Panjang maksimum umum untuk setiap baris (panjang efektif, mengabaikan karakter spesial dan tag HTML). Default adalah 20.
 * @param array $delimiters Larik string pemisah. Jika ditemukan, teks akan dipecah pada pemisah ini, dan pemisah akan disertakan di akhir baris pertama.
 * @return array Larik string, di mana setiap string adalah baris yang dipecah.
 */
if (!function_exists('smartSplit')) {
    function smartSplit(string $text = "", int $maxLength = 20, array $delimiters = []): array
    {
        // Menginisialisasi larik untuk menyimpan baris-baris hasil.
        $lines = [];
        // Menghilangkan spasi di awal/akhir dari teks awal.
        $remainingText = trim($text);

        // Melakukan perulangan selama masih ada teks yang tersisa untuk diproses.
        while (strlen($remainingText) > 0) {
            $foundDelimiterSplit = false;

            // Memeriksa apakah ada pemisah yang ditentukan dalam teks yang tersisa.
            foreach ($delimiters as $delimiter) {
                // Mencari posisi pemisah pertama.
                $pos = strpos($remainingText, $delimiter);
                // Jika pemisah ditemukan.
                if ($pos !== false) {
                    // Mengambil segmen teks sebelum pemisah.
                    $segmentBeforeDelimiter = substr($remainingText, 0, $pos);
                    // Jika panjang efektif segmen tersebut sesuai dengan maxLength.
                    if (getSmartLength($segmentBeforeDelimiter) <= $maxLength) {
                        // Mengambil baris termasuk pemisah.
                        $line = substr($remainingText, 0, $pos + strlen($delimiter));
                        // Menambahkan baris ke hasil.
                        $lines[] = trim($line);
                        // Memperbarui teks yang tersisa.
                        $remainingText = substr($remainingText, $pos + strlen($delimiter));
                        // Menghilangkan spasi di awal teks yang tersisa.
                        $remainingText = ltrim($remainingText);
                        $foundDelimiterSplit = true;
                        break; // Keluar dari perulangan pemisah setelah menemukan dan memproses satu.
                    }
                }
            }

            // Jika pemisahan karena delimiter telah terjadi, lanjutkan ke iterasi berikutnya.
            if ($foundDelimiterSplit) {
                continue;
            }

            // --- Logic untuk pemisahan berdasarkan maxLength jika tidak ada delimiter yang ditemukan ---

            $currentLineEffectiveLength = 0;
            $potentialLineBreakPointRaw = 0;

            // Menghitung panjang efektif dan mencari titik potong potensial berdasarkan maxLength.
            for ($i = 0; $i < strlen($remainingText); $i++) {
                $char = $remainingText[$i];
                // Menentukan apakah karakter adalah karakter spesial (bukan alfanumerik atau spasi).
                // Atau jika karakter tersebut adalah bagian dari tag HTML yang harus diabaikan.
                // Untuk memastikan ini, kita akan memeriksa apakah karakter ini adalah bagian dari tag yang sedang terbuka atau tertutup.
                // Namun, cara yang lebih robust adalah dengan menggunakan getSmartLength untuk memeriksa panjang.
                // Di sini kita hanya mengabaikan karakter spesial dan mengandalkan getSmartLength untuk penghitungan total.

                // Kita perlu cara untuk melacak posisi di `remainingText` yang sesuai dengan panjang efektif.
                // Ini akan sedikit lebih kompleks karena `getSmartLength` bekerja pada string keseluruhan.
                // Pendekatan yang lebih baik adalah memecah string dengan cara yang "sadar HTML" terlebih dahulu,
                // atau menghitung panjang efektif secara iteratif.

                // Untuk saat ini, kita akan mempertahankan logika yang sudah ada untuk karakter spesial,
                // dan mengandalkan `getSmartLength` yang diperbarui untuk memfilter tag HTML.

                // Cek apakah karakter saat ini bukan bagian dari tag HTML yang valid saat dihitung secara efektif.
                // Karena `getSmartLength` akan membersihkan tag, kita perlu memastikan iterasi ini tidak salah hitung.
                // Ini adalah bagian yang paling rumit karena kita harus maju satu karakter pada satu waktu sambil
                // mengabaikan urutan tag HTML.

                // Ide: Ciptakan substring dari awal hingga karakter saat ini dan hitung panjang efektifnya.
                $tempSubstring = substr($remainingText, 0, $i + 1);
                $effectiveLengthAtIndex = getSmartLength($tempSubstring);

                // Jika panjang efektif mencapai maxLength, atau jika kita telah melewati batas raw yang diperlukan
                // untuk mencapai maxLength efektif.
                if ($effectiveLengthAtIndex >= $maxLength) {
                    $potentialLineBreakPointRaw = $i + 1;
                    break;
                }
                $potentialLineBreakPointRaw = $i + 1; // Terus maju bahkan jika belum mencapai maxLength
            }


            // Jika sisa teks lebih pendek dari atau sama dengan maxLength (setelah menghitung efektif),
            // tambahkan sebagai baris terakhir dan keluar dari perulangan.
            if ($potentialLineBreakPointRaw === strlen($remainingText)) {
                $lines[] = trim($remainingText);
                $remainingText = '';
                break;
            }

            // Menentukan titik potong baris awal (titik potong mentah).
            $lineBreakPoint = $potentialLineBreakPointRaw;
            // Mencari spasi terakhir sebelum titik potong potensial.
            // Kita perlu mencari spasi dalam string asli, bukan string efektif.
            $lastSpaceBeforePotential = strrpos(substr($remainingText, 0, $potentialLineBreakPointRaw), ' ');

            // --- Menentukan apakah ada kata yang terpotong di batas maxLength ---
            $isWordCutOff = false;
            // Memeriksa karakter di posisi sebelum titik potong potensial (jika ada dan bukan spasi).
            // Kita harus memeriksa karakter asli di `remainingText`.
            if (isset($remainingText[$potentialLineBreakPointRaw - 1]) && !ctype_space($remainingText[$potentialLineBreakPointRaw - 1])) {
                // Memeriksa karakter di posisi titik potong potensial (jika ada dan bukan spasi).
                if (isset($remainingText[$potentialLineBreakPointRaw]) && !ctype_space($remainingText[$potentialLineBreakPointRaw])) {
                    $isWordCutOff = true;
                }
            }

            // --- Logika untuk menangani kata yang terpotong ---
            if ($isWordCutOff) {
                // Mencari awal kata yang terpotong.
                $wordStartRaw = $potentialLineBreakPointRaw - 1;
                while ($wordStartRaw > 0 && isset($remainingText[$wordStartRaw - 1]) && !ctype_space($remainingText[$wordStartRaw - 1])) {
                    $wordStartRaw--;
                }

                // Mencari akhir kata yang terpotong.
                $wordEndRaw = $potentialLineBreakPointRaw;
                while ($wordEndRaw < strlen($remainingText) && isset($remainingText[$wordEndRaw]) && !ctype_space($remainingText[$wordEndRaw])) {
                    $wordEndRaw++;
                }

                // Mengambil bagian awalan (prefix) dan akhiran (suffix) dari kata yang terpotong.
                $prefix = substr($remainingText, $wordStartRaw, $potentialLineBreakPointRaw - $wordStartRaw);
                $suffix = substr($remainingText, $potentialLineBreakPointRaw, $wordEndRaw - $potentialLineBreakPointRaw);

                // Menghitung panjang efektif dari prefix dan suffix.
                $prefixEffectiveLength = getSmartLength($prefix);
                $suffixEffectiveLength = getSmartLength($suffix);

                // Menerapkan aturan "jalur terpendek":
                // Jika akhiran lebih pendek dari awalan, pindahkan seluruh kata ke baris berikutnya.
                if ($suffixEffectiveLength < $prefixEffectiveLength) {
                    // Baris harus berakhir tepat sebelum awal kata ini.
                    // Cari spasi terakhir sebelum 'wordStartRaw'.
                    if ($wordStartRaw > 0) {
                        $potentialSplitBeforeWord = strrpos(substr($remainingText, 0, $wordStartRaw), ' ');
                        $lineBreakPoint = ($potentialSplitBeforeWord !== false) ? $potentialSplitBeforeWord : $wordStartRaw;
                    } else {
                        // Kata dimulai dari indeks 0 dan lebih panjang dari maxLength.
                        // Dalam kasus ini, kita harus memecahnya di maxLength.
                        $lineBreakPoint = $potentialLineBreakPointRaw;
                    }
                } else {
                    // Jika akhiran tidak lebih pendek dari awalan, pecah di spasi terakhir dalam jendela,
                    // atau paksa pecah di maxLength jika tidak ada spasi ditemukan.
                    $lineBreakPoint = ($lastSpaceBeforePotential !== false) ? $lastSpaceBeforePotential : $potentialLineBreakPointRaw;
                }
            } else {
                // Tidak ada kata yang terpotong, atau karakter di maxLength adalah spasi.
                // Pecah di spasi terakhir yang ditemukan dalam jendela, atau paksa pecah di maxLength.
                $lineBreakPoint = ($lastSpaceBeforePotential !== false) ? $lastSpaceBeforePotential : $potentialLineBreakPointRaw;
            }

            // Memastikan lineBreakPoint yang dihitung valid dan dalam batas-batas teks yang tersisa.
            if ($lineBreakPoint < 0) {
                $lineBreakPoint = $potentialLineBreakPointRaw; // Fallback jika tidak ada spasi yang cocok ditemukan.
            }
            if ($lineBreakPoint > strlen($remainingText)) {
                $lineBreakPoint = strlen($remainingText); // Mencegah keluar batas.
            }

            // Mengekstrak baris saat ini berdasarkan titik potong yang ditentukan.
            $line = substr($remainingText, 0, $lineBreakPoint);
            // Menambahkan baris yang telah dirapikan ke larik hasil.
            $lines[] = trim($line);

            // Memperbarui teks yang tersisa dengan menghapus baris yang telah diekstrak.
            $remainingText = substr($remainingText, $lineBreakPoint);
            // Menghilangkan spasi di awal teks yang tersisa untuk iterasi berikutnya.
            $remainingText = ltrim($remainingText);
        }

        return $lines;
    }
}


/**
 * Menghitung "panjang efektif" dari sebuah string dengan mengabaikan karakter spesial dan tag HTML.
 * Karakter spesial didefinisikan sebagai apa pun yang bukan huruf, angka, atau spasi.
 *
 * @param string $str String yang akan dihitung panjang efektifnya.
 * @return int Panjang efektif dari string.
 */
if (!function_exists('getSmartLength')) {
    function getSmartLength(string $str): int
    {
        // Menghapus semua tag HTML dari string.
        $strippedHtml = strip_tags($str);
        // Menghapus semua karakter yang bukan alfanumerik atau spasi.
        $cleanStr = preg_replace('/[^a-zA-Z0-9\s]/', '', $strippedHtml);
        // Mengembalikan panjang string yang telah dibersihkan.
        return strlen($cleanStr);
    }
}

/**
 * Mengubah sebuah associative array yang memiliki data array sehingga mengambil data pertama dari setiap array.
 * @param array|object $array Array atau objek yang akan di-flatten.
 * @param bool $asObjects Jika true, mengembalikan hasil sebagai objek; jika false, mengembalikan sebagai array.
 * @return mixed Hasil flatten dari array atau objek.
 *
 * Contoh penggunaan:
 * ```php
 * $data = [
 *     'key1' => ['value1', 'value2'],
 *     'key2' => ['value3'],
 *     'key3' => 'value4',
 * ];
 * $flattened = flattenArray($data);
 * // $flattened akan menjadi [
 *     'key1' => 'value1',
 *     'key2' => 'value3',
 *     'key3' => 'value4',
 * ];
 * ```
 */
if (!function_exists('extractFirstElement')) {
    function extractFirstElement($array, $asObjects = true)
    {
        // Mempastikan bahwa input adalah array atau objek.
        if (!is_array($array) && !is_object($array)) {
            return $array; // Jika bukan array atau objek, kembalikan nilai aslinya.
        }

        $flattened = [];

        $array = is_object($array) ? (array) $array : $array;

        foreach ($array as $key => $value) {
            if (is_array($value) && count($value) > 0) {
                // Mengambil elemen pertama dari array jika ada.
                $flattened[$key] = $value[0];
            } else {
                // Jika bukan array atau kosong, tetap simpan nilai aslinya.
                $flattened[$key] = $value;
            }
        }

        if ($asObjects) {
            // Mengembalikan sebagai objek jika diminta.
            return (object) $flattened;
        }

        return $flattened;
    }
}

/**
 * Mengambil elemen pertama dari setiap array dalam array multidimensi.
 * @param array $array Array yang akan di-flatten.
 * @param bool $asObjects Jika true, mengembalikan hasil sebagai objek; jika false, mengembalikan sebagai array.
 * @return array|object Hasil flatten dari array atau objek.
 */
if (!function_exists('extractEachFirstElement')) {
    function extractEachFirstElement($array, $asObjects = true)
    {
        if (!is_array($array)) {
            return $array; // Jika bukan array, kembalikan nilai aslinya.
        }

        return array_map(fn($item) => extractFirstElement($item), $array);
    }
}

/**
 * Melakukan mapping data konten yang memiliki satu data dalam array secara efisien.
 *
 * Contoh Penggunaan:
 * ```php
 * $konten = new \stdClass();
 *
 * kontenMapping($konten, ['tentang program studi' => 'tentang', 'prospek karir' => 'prospek', 'daftar prospek karir' => 'daftar_prospek']);
 *
 * // Hasilnya akan mengisi properti dari objek $konten dengan nilai-nilai yang sesuai.
 * ```
 */
if (!function_exists('kontenMapping')) {
    function kontenMapping($konten, $values, $keys)
    {
        // Memastikan bahwa $konten adalah objek atau array.
        if (!is_object($konten) && !is_array($konten)) {
            return;
        }

        // Memastikan bahwa $keys adalah array.
        if (!is_array($keys) || empty($keys)) {
            return; // Jika $keys bukan array atau kosong, keluar dari fungsi.
        }

        // Melakukan iterasi pada setiap kunci yang diberikan.
        foreach ($keys as $sourceKey => $targetKey) {
            // Mengambil nilai dari model Konten berdasarkan kunci.

            if (is_object($konten)) {

                $konten->{$targetKey} = extractFirstElement($values->{$sourceKey}[0]) ?? null;
            } elseif (is_array($konten)) {
                $konten[$targetKey] = extractFirstElement($values[$sourceKey][0]) ?? null;
            }
        }

        return $konten; // Mengembalikan objek atau array yang telah dimapping.
    }
}

/**
 * Melakukan mapping data konten yang memiliki banyak data secara efisien.
 */
if (!function_exists('kontenMappingMany')) {
    function kontenMappingMany($konten, $values, $keys)
    {
        // Memastikan bahwa $konten adalah objek atau array.
        if (!is_object($konten) && !is_array($konten)) {
            return;
        }

        // Memastikan bahwa $keys adalah array.
        if (!is_array($keys) || empty($keys)) {
            return; // Jika $keys bukan array atau kosong, keluar dari fungsi.
        }

        // Melakukan iterasi pada setiap kunci yang diberikan.
        foreach ($keys as $sourceKey => $targetKey) {
            // Mengambil nilai dari model Konten berdasarkan kunci.
            if (is_object($konten)) {
                $konten->{$targetKey} = extractEachFirstElement($values->{$sourceKey}) ?? null;
            } elseif (is_array($konten)) {
                $konten[$targetKey] = extractEachFirstElement($values[$sourceKey]) ?? null;
            }
        }

        return $konten; // Mengembalikan objek atau array yang telah dimapping.
    }
}
