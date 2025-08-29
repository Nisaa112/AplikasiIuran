<?php

namespace App\Http\Controllers;

use App\Models\TipeIuran;
use Illuminate\Http\Request;

class TipeIuranController extends Controller
{
    public function index(Request $request)
    {
        $data['result'] = \App\Models\TipeIuran::all();

        if ($request->expectsJson()) {
            return response()->json($data);
        }
        
        return view('tipeIuran/index', ['data' => $data]);
    }

    public function show($id)
    {
        $tipeIuran = TipeIuran::find($id);
        return view('tipeIuran/detail', compact('tipe_iuran'));
    }

    public function create()
    {
        return view('tipeIuran/form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'max:100',
                'regex:/^[a-zA-Z0-9\s\-\.,\/]+$/',
            ],
            'deskripsi' => 'required|string|max:500',
            'nominal' => 'required|numeric|min:0',
            'period' => 'required|in:bulanan,tahunan,sekali,lainnya',
        ]);

        $status = \App\Models\TipeIuran::create($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => $status ? true : false,
                'message' => $status ? 'Update berhasil' : 'Update gagal',
            ], $status ? 200 : 500);
        }

        if($status) return redirect('tipe_iuran')->with('success', 'Data berhasil disimpan');
        else return redirect('tipe_iuran')->with('error', 'Data gagal disimpan');
    }

    public function edit($id) 
    {
        $data = TipeIuran::findOrFail($id);
        return view('tipeIuran/form', ['data' => $data]);
    }

    public function update(Request $request, $id)
    {
       $validated = $request->validate([
            'name' => [
                'required',
                'max:100',
                'regex:/^[a-zA-Z0-9\s\-\.,\/]+$/',
            ],
            'deskripsi' => 'required|string|max:500',
            'nominal' => 'required|numeric|min:0',
            'period' => 'required|in:bulanan,tahunan,sekali,lainnya',
        ]);
        
        $tipeIuran = TipeIuran::findOrFail($id);

        $status = $tipeIuran->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => $status ? true : false,
                'message' => $status ? 'Update berhasil' : 'Update gagal',
            ], $status ? 200 : 500);
        }
        
        if($tipeIuran) return redirect('tipe_iuran')->with('success', 'Data berhasil diubah');
        else return redirect('tipe_iuran')->with('error', 'Data gagal diubah');
    }

    public function destroy(Request $request, $id)
    {
        $result = TipeIuran::findOrFail($id);
        $status = $result->delete();

        if ($request->expectsJson()){
            return response()->json([
                'status' => $status ? true : false,
                'message' => $status ? 'Data berhasil dihapus' : 'Data gagal dihapus'
            ], $status ? 200 : 500);
        }

        if($status) return redirect('tipe_iuran')->with('success', 'Data berhasil dihapus');
        else return redirect('tipe_iuran')->with('error', 'Data gagal dihapus');
    }
}
