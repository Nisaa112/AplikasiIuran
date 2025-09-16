<?php

namespace App\Http\Controllers;

use App\Models\histori;
use App\Models\Pengeluaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengeluaranController extends Controller
{
    private function findPengeluaranByIdAndUser($id)
    {
        return Pengeluaran::where('id_user', Auth::id())->findOrFail($id);
    }

    public function index(Request $request)
    {
        $data = Pengeluaran::where('id_user', Auth::id())->with('user')->get();

        if($request->expectsJson()) {
            return response()->json($data);
        }

        return view('pengeluaran/index', ['data' => $data]);
    }

    public function create()
    {
        // $user = User::all();
        return view('pengeluaran/form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            // 'id_user' => 'required|exists:users,id',
            'nama' => [
                'required',
                'max:100',
                'regex:/^[a-zA-Z0-9\s\-]+$/'
            ],
            'jumlah' => 'required|numeric|min:0',
            'tgl_pengeluaran' => 'required|date',
            'keterangan' => 'required|string',
        ]);

        $validated['id_user'] = Auth::id();

        $status = Pengeluaran::create($validated);

        if ($status) {
            // Ini yang akan mencatat history secara otomatis
            histori::create([
                'id_user' => Auth::id(),
                'keterangan' => 'Pengeluaran: ' . $validated['nama'],
                'jumlah' => $validated['jumlah'],
                'tgl_transaksi' => $validated['tgl_pengeluaran'],
                'jenis_transaksi' => 'pengeluaran', // Otomatis diset 'pengeluaran'
            ]);

            return redirect('/pengeluaran')->with('success', 'Data berhasil ditambahkan');
        } else {
            return redirect('/pengeluaran')->with('error', 'Data gagal ditambahkan');
        }
        
        if($request->expectsJson()) {
            return response()->json([
                'status' => $status ? true : false,
                'message' => $status ? 'berhasil ditambah' : 'gagal ditambah',
            ], $status ? 201 : 500);
        }

        if($status) return redirect('/pengeluaran')->with('success', 'Data berhasil ditambahkan');
        else return redirect('/pengeluaran')->with('error', 'Data gagal ditambahkan');
    }

    public function edit($id)
    {
        $data = $this->findPengeluaranByIdAndUser($id);
        // $user = User::all();
        return view('pengeluaran/form', ['data' => $data]);
    }

    public function update(Request $request, $id)
    {
        $pengeluaran = $this->findPengeluaranByIdAndUser($id);

        $validated = $request->validate([
            // 'id_user' => 'required|exists:users,id',
            'nama' => [
                'required',
                'max:100',
                'regex:/^[a-zA-Z0-9\s\-]+$/'
            ],
            'jumlah' => 'required|numeric|min:0',
            'tgl_pengeluaran' => 'required|date',
            'keterangan' => 'required|string',
        ]);

        $status = $pengeluaran->update($validated);

        if($request->expectsJson()) {
            return response()->json([
                'status' => $status ? true : false,
                'message' => $status ? 'berhasil diperbarui' : 'gagal diperbarui',
            ], $status ? 200 : 500);
        }

        if($status) return redirect('/pengeluaran')->with('success', 'Data berhasil diperbarui');
        else return redirect('/pengeluaran')->with('error', 'Data gagal diperbarui');
    }

    public function destroy(Request $request, $id)
    {
        $result = $this->findPengeluaranByIdAndUser($id);
        $status = $result->delete();

        if($request->expectsJson()) {
            return response()->json([
                'status' => $status ? true : false,
                'message' => $status ? 'berhasil dihapus' : 'gagal dihapus',
            ], $status ? 200 : 500);
        }

        if($status) return redirect('/pengeluaran')->with('success', 'Data berhasil dihapus');
        else return redirect('/pengeluaran')->with('error', 'Data gagal dihapus');
    }
}
