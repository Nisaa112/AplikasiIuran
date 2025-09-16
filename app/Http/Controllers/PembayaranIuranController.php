<?php

namespace App\Http\Controllers;

use App\Models\histori;
use App\Models\Member;
use App\Models\PembayaranIuran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PembayaranIuranController extends Controller
{
    private function findPembayaranByIdAndUser($id)
    {
        return PembayaranIuran::where('id_user', Auth::id())->findOrFail($id);
    }

    public function index(Request $request)
    {
        $data = PembayaranIuran::where('id_user', Auth::id())->with('user', 'member')->get();

        if($request->expectsJson()) {
            return response()->json($data);
        }

        return view('pembayaranIuran/index', ['data' => $data]);
    }

    public function create()
    {
        // $user = User::all();
        $member = Member::where('id_user', Auth::id())->pluck('nama', 'id');
        return view('pembayaranIuran/form', compact('member'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // 'id_user' => 'required|exists:users,id',
            'id_member' => 'required|exists:members,id',
            'jumlah' => 'required|numeric|min:0',
            'catatan' => 'nullable|string|max:500',
            'metode_bayar' => ['required', 'in:cash,transfer'],
            'tgl_bayar' => 'required|date',
        ]);

        $validated['id_user'] = Auth::id();

        $status = \App\Models\PembayaranIuran::create($validated);

        if ($status) {
            // Ini yang akan mencatat history secara otomatis
            $member = \App\Models\Member::find($validated['id_member']);
            histori::create([
                'id_user' => Auth::id(),
                'keterangan' => 'Pemasukan dari ' . $member->nama,
                'jumlah' => $validated['jumlah'],
                'tgl_transaksi' => $validated['tgl_bayar'],
                'jenis_transaksi' => 'pemasukan', // Otomatis diset 'pemasukan'
            ]);

            return redirect('/pembayaran')->with('success', 'Data berhasil ditambah');
        } else {
            return redirect('/pembayaran')->with('error', 'Data gagal ditambah');
        }

        if ($request->expectsJson()) {
            return response()->json([
                'status' => $status ? true : false,
                'message' => $status ? "Berhasil ditambah" : "Gagal ditambah",
            ], $status ? 200 : 500);
        }

        if($status) return redirect('/pembayaran')->with('success', 'Data berhasil ditambah');
        else return redirect('/pembayaran')->with('error', 'Data gagal ditambah');
    }

    public function edit($id)
    {
        $data = $this->findPembayaranByIdAndUser($id);
        // $user = User::all();
        $member = Member::where('id_user', Auth::id())->pluck('nama', 'id');
        return view('pembayaranIuran/form', ['data' => $data, 'member' => $member]);
    }

    public function update(Request $request, $id) 
    {
        $pembayaran = $this->findPembayaranByIdAndUser($id);

        $validated = $request->validate([
            'id_member' => 'required|exists:members,id',
            'jumlah' => 'required|numeric|min:0',
            'catatan' => 'nullable|string|max:500',
            'metode_bayar' => ['required', 'in:cash,transfer'],
            'tgl_bayar' => 'required|date',
        ]);

        $status = $pembayaran->update($validated);
                
        if ($request->expectsJson()) {
            return response()->json([
                'status' => $status ? true : false,
                'message' => $status ? 'Update berhasil' : 'Update gagal',
            ], $status ? 200 : 500);
        }

        if($status) return redirect('/pembayaran')->with('success', 'Data berhasil diubah');
        else return redirect('/pembayaran')->with('error', 'Data gagal diubah');
    }

    public function destroy(Request $request, $id)
    {
        $result = $this->findPembayaranByIdAndUser($id);
        $status = $result->delete();

        if ($request->expectsJson()){
            return response()->json([
                'status' => $status ? true : false,
                'message' => $status ? 'Data berhasil dihapus' : 'Data gagal dihapus'
            ], $status ? 200 : 500);
        }

        if($status) return redirect('/pembayaran')->with('success', 'Data berhasil dihapus');
        else return redirect('/pembayaran')->with('error', 'Data gagal dihapus');
    }
}
