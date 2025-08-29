<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TipeIuranController extends Controller
{
    public function index(Request $request)
    {
        $data['result'] = \App\Models\TipeIuran::all();

        if ($request->expectsJson()) {
            return response()->json($data);
        }
        
        return view('tipeIuran/index')->with($data);
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

        if($status) return redirect('tipeIuran')->with('success', 'Data berhasil disimpan');
        else return redirect('tipeIuran')->with('error', 'Data gagal disimpan');
    }

    public function edit($id) 
    {
        $data['result'] = \App\Models\TipeIuran::where('id', $id)->first();
        return view('tipeIuran/form')->with($data);
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
        
        $tipeIuran = \App\Models\TipeIuran::where('id', $id)->first();

        $status = $tipeIuran->update($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => $status ? true : false,
                'message' => $status ? 'Update berhasil' : 'Update gagal',
            ], $status ? 200 : 500);
        }
        
        if($tipeIuran) return redirect('tipeIuran')->with('success', 'Data berhasil diubah');
        else return redirect('tipeIuran')->with('error', 'Data gagal diubah');
    }

    public function destroy(Request $request, $id)
    {
        $result = \App\Models\TipeIuran::where('id', $id)->first();
        $status = $result->delete();

        if ($request->expectsJson()){
            return response()->json([
                'status' => $status ? true : false,
                'message' => $status ? 'Data berhasil dihapus' : 'Data gagal dihapus'
            ], $status ? 200 : 500);
        }

        if($status) return redirect('tipeIuran')->with('success', 'Data berhasil dihapus');
        else return redirect('tipeIuran')->with('error', 'Data gagal dihapus');
    }
}
