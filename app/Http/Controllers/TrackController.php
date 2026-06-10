<?php

namespace App\Http\Controllers;

use App\Models\Track;
use Illuminate\Contracts\View\View;

class TrackController extends Controller
{
    public function index(): View
    {
        $tracks = Track::with(['artists', 'albums', 'genres', 'copyrightHolder'])
            ->orderByDesc('play_count')
            ->paginate(15);

        return view('tracks', compact('tracks'));
    }
}
