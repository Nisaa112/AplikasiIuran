<?php

namespace App\Http\Controllers;

use App\Models\histori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class historyController extends Controller
{
    public function index(Request $request)
    {
        $histories = histori::where('id_user', Auth::id())
                            ->orderBy('tgl_transaksi', 'desc')
                            ->orderBy('created_at', 'desc')
                            ->get();

        if ($request->expectsJson()) {
            return response()->json($histories);
        }
        
        return view('histories.index', compact('histories'));
    }
}
