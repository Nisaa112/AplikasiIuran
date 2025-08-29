<?php

namespace App\Http\Controllers;

use App\Models\PembayaranIuran;
use App\Models\TipeIuran;
use App\Models\User;
use Illuminate\Http\Request;

class PembayaranIuranController extends Controller
{
    public function index(Request $request)
    {
        $data = PembayaranIuran::with('tipe_iuran', 'user')->get();

        if($request->expectsJson()) {
            return response()->json($data);
        }

        return view('pembayaranIuran/index', ['data' => $data]);
    }

    public function create()
    {
        $tipeIuran = TipeIuran::all();
        $user = User::all();
        return view('pembayaranIuran/form', compact('tipeIuran', 'user'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_tipe_iuran' => 'required|exists:tipe_iuran,id',
            'id_user' => 'required|exists:users,id',
            'jumlah' => 'required|numeric|min:0',
            'catatan' => 'required|string|max:500',
            'tgl_bayar' => 'required|date',
        ]);

        $status = \App\Models\PembayaranIuran::create($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => $status ? true : false,
                'message' => $status ? "Berhasil ditambah" : "Gagal ditambah",
            ], $status ? 200 : 500);
        }

        if($status) return redirect('pembayaran_iuran')->with('success', 'Data berhasil ditambah');
        else return redirect('pembayaran_iuran')->with('error', 'Data gagal ditambah');
    }

    public function edit($id)
    {
        $data = PembayaranIuran::findOrFail($id);
        return view('pembayaranIuran/form', ['data' => $data]);
    }

    public function update(Request $request, $id) 
    {
        $validated = $request->validate([
            'id_tipe_iuran' => 'required|exists:tipe_iuran,id',
            'id_user' => 'required|exists:users,id',
            'jumlah' => 'required|numeric|min:0',
            'catatan' => 'required|string|max:500',
            'tgl_bayar' => 'required|date',
        ]);

        $pembayaran = PembayaranIuran::findOrFail($id);
        $status = $pembayaran->update($validated);
                
        if ($request->expectsJson()) {
            return response()->json([
                'status' => $status ? true : false,
                'message' => $status ? 'Update berhasil' : 'Update gagal',
            ], $status ? 200 : 500);
        }

        if($pembayaran) return redirect('pembayaran_iuran')->with('success', 'Data berhasil diubah');
        else return redirect('pembayaran_iuran')->with('error', 'Data gagal diubah');
    }

    public function destroy(Request $request, $id)
    {
        $result = PembayaranIuran::findOrFail($id);
        $status = $result->delete();

        if ($request->expectsJson()){
            return response()->json([
                'status' => $status ? true : false,
                'message' => $status ? 'Data berhasil dihapus' : 'Data gagal dihapus'
            ], $status ? 200 : 500);
        }

        if($status) return redirect('pembayaran_iuran')->with('success', 'Data berhasil dihapus');
        else return redirect('pembayaran_iuran')->with('error', 'Data gagal dihapus');
    }
}
