<?php

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Vinkla\Hashids\Facades\Hashids;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Request;

/**
 * fungsi untuk membuat route yang uumum secara otomatis,
 * agar line di file web route lebih sederhana
 *
 * @param  mixed $controllerClass : nama|path controller tujuan
 * @param  mixed $fiture : nama|prefix|method yang dituju dari controller yang di panggil (sekaligus untuk penamaan route)
 * @param  mixed $ajaxOnly : parameter apakah method2 yang dimaksud hanya diizinkan di akses melalui XMLHttpRequest
 * @return void
 */
function generalRoute($controllerClass, $fiture, $prefix = '', $ajaxOnly = true)
{
    $baseMethod = ['store', 'update', 'destroy', 'data']; //'index', 'show',
    $prefix     = $prefix ? $prefix . '.' . $fiture : $fiture;

    // routing untuk mengarahkan ke methode index
    if (method_exists($controllerClass, 'index')) {
        app('router')->any("$fiture/", $controllerClass . "@index")->name($prefix . ".index"); // index
    }

    // routing untuk methode 'store', 'update', 'destroy', 'data' dengan opsi middleware ajax
    foreach ($baseMethod as $key => $method) {
        if (method_exists($controllerClass, $method)) {
            app('router')->any("$fiture/$method/{param1?}/{param2?}/{param3?}/{param4?}", $controllerClass . "@$method")->middleware($ajaxOnly ? ['ajax'] : [])->name($prefix . ".$method");
        }
    }

    // routing untuk methode show, tidak perlu memanggil method nya show jika tidak termasuk di routing sebelumnya
    // misal user/show/detail menjadi user/detail -> sama dengan methode show dengan param1 detail
    if (method_exists($controllerClass, 'show')) {
        app('router')->any("$fiture/{param1?}/{param2?}/{param3?}/{param4?}", $controllerClass . "@show")->name($prefix . ".show"); // show
    }
}

/**
 * fungsi untuk mengencrypt id dengan hasid dari vinkla
 *
 * @param [type] $id
 * @return void
 */
function encid(string $id = ''): string
{
    return $id ? Hashids::encode($id) : null;
}

/**
 * fungsi untuk mendecrypt id dengan hashid dari vinkla
 *
 * @param [type] $id
 * @return integer
 */
function decid(string $id = ''): int
{
    $dec = Hashids::decode($id);
    return $dec ? $dec[0] : 0;
}

/**
 * untuk otomasi parameter array where di query builder
 *
 * @param  mixed $where
 * @return array
 */
function notRaw($where = []): array
{
    $notEmpty = [];

    foreach ($where as $key => $value) {
        if ($value !== '') {
            $notEmpty[$key] = $value;
        }
    }

    return $where ? $notEmpty : [];
}

/**
 * untuk otomasi parameter array where di query builder
 * mengakomodir param where ['key is not null'] => '';
 * mengakomodir param where ['key >= 5'] => '';
 *
 * @param  mixed $where
 * @return array
 */
function withRaw($where = []): string
{
    $empty = [];

    foreach ($where as $key => $value) {
        if ($value === '') {
            $empty[] = $key;
        }
    }

    return $empty ? implode(' and ', $empty) : '1=1';
}

function success($status = true, $message = '', $data = [])
{
    return response()->json([
        'status'  => $status,
        'message' => $message,
        'data'    => $data,
    ]);
}

function isSnap()
{
    return request()->query('snap') ? true : false;
}

function clean_post($key = '')
{
    $req = request();
    return $req->method() == 'POST' ? trim(strip_tags($req->input($key))) : '';
}

function clean_script($key = '')
{
    $req = request();
    return $req->method() == 'POST' ? trim(str_replace('script>', 's c r i p t >', $req->input($key))) : '';
}

function eventActivityLogBahasa($eventName)
{
    $ev = [
        'created'      => 'menambahkan data ',
        'updated'      => 'merubah data',
        'deleted'      => 'menghapus data',
        'restored'     => 'mengembalikan data',
        'forceDeleted' => 'menghapus permanen data',
    ];
    return isset($ev[$eventName]) ? $ev[$eventName] : 'melakukan aksi pada data';
}

function echo_array($dt)
{
    echo '<pre>';
    echo json_encode($dt);
    echo '</pre>';
}

function normalizeString($str = '')
{
    $str = strip_tags($str);
    $str = preg_replace('/[\r\n\t ]+/', ' ', $str);
    $str = preg_replace('/[\"\*\/\:\<\>\?\'\|]+/', ' ', $str);
    $str = strtolower($str);
    $str = html_entity_decode($str, ENT_QUOTES, "utf-8");
    $str = htmlentities($str, ENT_QUOTES, "utf-8");
    $str = preg_replace("/(&)([a-z])([a-z]+;)/i", '$2', $str);
    $str = str_replace([' ', '.'], ['-', '-'], $str);
    $str = rawurlencode($str);
    $str = str_replace('%', '-', $str);
    return $str;
}

function cut_words($phrase, $max_words)
{
    $phrase_array = explode(' ', $phrase);
    if (count($phrase_array) > $max_words && $max_words > 0)
        $phrase = implode(' ', array_slice($phrase_array, 0, $max_words)) . '...';
    return $phrase;
}

function cut_string($phrase, $max_words)
{
    if (strlen($phrase) > $max_words) {
        $outputString = substr($phrase, 0, $max_words);

        return $outputString . '...';
    } else {
        return $phrase;
    }
}

function textToStringMultipleList($string = '')
{
    $string   = preg_split('/(\r?\n)+/', $string);
    $inString = '';
    foreach ($string as $line) {
        $inString .= "'" . trim($line) . "',";
    }
    $inString = rtrim($inString, ",");

    return $inString;
}

function slug($text)
{
    // replace non letter or digits by -
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);

    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);

    // trim
    $text = trim($text, '-');

    // remove duplicate -
    $text = preg_replace('~-+~', '-', $text);

    // lowercase
    $text = strtolower($text);

    if (empty($text)) {
        return 'n-a';
    }

    return $text;
}

function time_history($tanggal)
{
    return \Carbon\Carbon::parse($tanggal)->diffForHumans();
}

function duration($start = "", $end = "")
{
    $strStart = $start;
    $strEnd   = $end;

    $dteStart = new DateTime($strStart);
    $dteEnd   = new DateTime($strEnd);

    $interval = date_diff($dteStart, $dteEnd);

    $DaysToSecconds   = $interval->format('%a') * ((60 * 60) * 24);
    $HoursToSeconds   = $interval->format('%H') * (60 * 60);
    $MinutesToSeconds = $interval->format('%I') * 60;
    $SecondsToSeconds = $interval->format('%S');

    $TotalMinutes = $DaysToSecconds + $HoursToSeconds + $MinutesToSeconds + $SecondsToSeconds;
    return $TotalMinutes;
}

function tanggal($date, $sparator = '-', $time = false)
{
    $dayDate  = date('Y-m-d', strtotime($date));
    $timeDate = date('H:i', strtotime($date));

    $bulan = array(
        1 => 'Januari',
        'Februari',
        'Maret',
        'April',
        'Mei',
        'Juni',
        'Juli',
        'Agustus',
        'September',
        'Oktober',
        'November',
        'Desember'
    );
    $split = explode('-', $dayDate);

    return $split[2] . $sparator . $bulan[(int) $split[1]] . $sparator . $split[0] . ($time ? ' ' . $timeDate : '');
}

function dateTime(string $date = '', string $format = 'Y-m-d H:i:s', string $modify = '', $as_object = false)
{
    $timezone = new DateTimeZone('Asia/Jakarta');
    $date     = $date ? Carbon::parse($date, 'Asia/Jakarta') : Carbon::now('Asia/Jakarta');

    // Extract the number and the unit from the modification string
    preg_match('/([+-]\d+)\s*(seconds|minutes|hours|days|weeks|months|years)/', $modify, $matches);

    if (count($matches) === 3) {
        $number = (int) $matches[1];
        $unit   = $matches[2];

        switch ($unit) {
            case 'seconds':
                $date->addSeconds($number);
                break;
            case 'minutes':
                $date->addMinutes($number);
                break;
            case 'hours':
                $date->addHours($number);
                break;
            case 'days':
                $date->addDays($number);
                break;
            case 'weeks':
                $date->addWeeks($number);
                break;
            case 'months':
                $date->addMonths($number);
                break;
            case 'years':
                $date->addYears($number);
                break;
        }
    }

    return $as_object ? $date : $date->format($format);

    // if ($date) {
    //     $date = Carbon::parse($date)->format($format);
    //     // Normalize input format
    //     $dateComponents = explode('T', $date);
    //     $datePart       = $dateComponents[0];
    //     $timePart       = isset($dateComponents[1]) ? $dateComponents[1] : '00:00:00';

    //     // Add seconds if only hours and minutes are given
    //     if (preg_match('/^\d{2}:\d{2}$/', $timePart)) {
    //         $timePart .= ':00';
    //     }

    //     // Add time if only date is given
    //     if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
    //         $timePart = '00:00:00';
    //     }

    //     // Combine back date and time
    //     $date = $datePart . ' ' . $timePart;

    //     // Create DateTime object from normalized string
    //     $dateTime = DateTime::createFromFormat('Y-m-d H:i:s', $date, $timezone);

    //     // Handle invalid date format input
    //     if (!$dateTime) {
    //         throw new Exception("Invalid date format provided: $date");
    //     }
    // } else {
    //     // Create new DateTime object for the current time
    //     $dateTime = new DateTime('now', $timezone);
    // }

    // // Apply modification if provided
    // if ($modify) {
    //     $dateTime->modify($modify);
    // }

    // // Return DateTime object or formatted string as requested
    // return $as_object ? $dateTime : $dateTime->format($format);
}

if (!function_exists('periodeStatus')) {
    /**
     * Determine the status of a period based on the current date.
     *
     * @param string $startDate The start date of the period.
     * @param string $endDate The end date of the period.
     * @param string $format The date format, default is 'Y-m-d'.
     * @return string Returns 'mendatang' (upcoming), 'berlangsung' (ongoing), or 'berlalu' (past).
     */
    function periodeStatus($startDate, $endDate, $format = 'Y-m-d H:i:s')
    {
        // Set the timezone to 'Asia/Jakarta'
        $timezone = new DateTimeZone('Asia/Jakarta');

        // Create DateTime objects for the start date, end date, and current date
        $start = DateTime::createFromFormat($format, $startDate, $timezone);
        $end   = DateTime::createFromFormat($format, $endDate, $timezone);
        $now   = new DateTime('now', $timezone);

        // Check if the current date is before the start date
        if ($now < $start) {
            return 'mendatang';
        }

        // Check if the current date is between the start date and end date
        if ($now >= $start && $now <= $end) {
            return 'berlangsung';
        }

        // If the current date is after the end date
        return 'berlalu';
    }
}

function defaultPassword($length = 10)
{
    $str = substr(str_shuffle('123456789ABCDEFGHJKLMNPQRSTUVWXYZ#@&%abcdehmnrst'), 0, $length);
    return $str;
}

//added by DZB
function convertFileSize($size = 0, $unit = 'KB')
{
    if ($size <= 0) {
        return "0 B";
    }

    $units     = ['B', 'KB', 'MB', 'GB', 'TB'];
    $unit      = strtoupper($unit); // Pastikan satuan dalam huruf besar
    $unitIndex = array_search($unit, $units);

    if ($unitIndex === false) {
        throw new InvalidArgumentException("Invalid unit provided: $unit");
    }

    // Konversi ukuran ke unit yang diinginkan
    while ($unitIndex > 0 && $size >= 1024) {
        $size /= 1024;
        $unitIndex--;
    }

    while ($unitIndex < 0 && $size < 1) {
        $size *= 1024;
        $unitIndex++;
    }

    // Format dengan maksimal 2 angka di belakang koma
    return number_format($size, 2) . ' ' . $unit;
}
