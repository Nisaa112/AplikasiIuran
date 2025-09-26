<?php

namespace App\Http\Controllers;

use App\Models\Profil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfilController extends Controller
{
    private function findProfilByIdAndUser($id)
    {
        return Profil::where('id_user', Auth::id())->findOrFail($id);
    }

    public function index(Request $request)
    {
        $data = Profil::where('id_user', Auth::id())->with('user')->get();

        if($request->expectsJson()) {
            return response()->json($data);
        }

        return view('profil/index', ['data' => $data]);
    }

    public function show(Request $request)
    {
        $data = Profil::where('id_user', Auth::id())->with('user')->first();

        if ($request->expectsJson()) {
            return response()->json($data);
        }

        if (!$data) {
            return view('profil/form');
        }
        
        return view('profil/show', ['data' => $data]);
    }

    public function create()
    {
        // $user = User::all();
        return view('profil/form');
    }

    public function save(Request $request)
    {
        $validated = $request->validate([
            'nama_kegiatan' => [
                'required',
                'max:100',
                'regex:/^[a-zA-Z0-9\s\-]+$/'
            ],
            'alamat' => 'required|string',
        ]);

        $status = Profil::updateOrCreate(
            ['id_user' => Auth::id()],
            $validated
        );
        
        if($request->expectsJson()) {
            return response()->json([
                'status' => $status ? true : false,
                'message' => $status ? 'Profil berhasil disimpan/diperbarui' : 'Profil gagal disimpan/diperbarui',
            ], $status ? 200 : 500); 
        }

        if($status) return redirect('/profil')->with('success', 'Profil berhasil disimpan/diperbarui');
        else return redirect('/profil')->with('error', 'Profil gagal disimpan/diperbarui');
    }

    public function edit($id)
    {
        $data = $this->findProfilByIdAndUser($id);
        // $user = User::all();
        return view('profil/form', ['data' => $data]);
    }


    public function destroy(Request $request, $id)
    {
        $result = $this->findProfilByIdAndUser($id);
        $status = $result->delete();

        if($request->expectsJson()) {
            return response()->json([
                'status' => $status ? true : false,
                'message' => $status ? 'berhasil dihapus' : 'gagal dihapus',
            ], $status ? 200 : 500);
        }

        if($status) return redirect('/profil')->with('success', 'Data berhasil dihapus');
        else return redirect('/profil')->with('error', 'Data gagal dihapus');
    }
}
