<?php

namespace App\Http\Controllers\Frontend\DEV;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MainController extends Controller
{
    public function __construct()
    {
        if (config('app.env') === 'production') {
            abort(404);
        }
    }

    public function changelog()
    {
        $file = Storage::get('frontend-changelog.md');

        if (!$file) {
            abort(404, 'Changelog file not found.');
        }

        $lastModified = Storage::lastModified('frontend-changelog.md');

        $htmlContent = Str::markdown($file);
        $modifiedHtmlContent = preg_replace('/<ul>\s*(<li>.*<\/li>\s*)+<\/ul>/sU', '<div class="history-description">$0</div>', $htmlContent);

        $lastModified = Carbon::createFromTimestamp($lastModified, 'Asia/Jakarta');

        // dd($modifiedHtmlContent);

        return view('contents.frontend.pages.dev.changelog', compact('modifiedHtmlContent', 'lastModified'));
    }
}
