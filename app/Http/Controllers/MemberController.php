<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Validation\Rule;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $data = Member::with('user')->get();

        if($request->expectsJson()) {
            return response()->json($data);
        }

        return view('member/index', ['data' => $data]);
    }

    public function create()
    {
        $user = User::all();
        return view('member/form', compact('user'));
    }

    public function store(Request $request, $id)
    {
        $validated = $request->validate([
            'id_user' => 'required|exists:users,id',
            'nama' => [
                'required',
                'max:100',
                'regex:/^[a-zA-Z0-9\s\-]+$/'
            ],
            'alamat' => 'required',
            'no_hp' => 'required|digits_between:10,20',
            'email' => ['nullable', 'email', Rule::unique('members')->ignore($id)],
            'foto' => 'nullable|mimes:jpeg,jpg,png,webp,svg|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $validated['foto'] = $filename;
        }

        $status = Member::create($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => $status ? true : false,
                'message' => $status ? 'Berhasil ditambah' : 'Gagal ditambah',
            ], $status ? 200 : 500);
        }

        if($status) return redirect('members')->with('success', 'Data ini berhasil ditambahkan');
        else return redirect('members')->with('error', 'Data ini gagal ditambahkan');
    }

    public function edit($id)
    {
        $data = Member::findOrFail($id);
        $user = User::all();
        return view('member/form', ['data' => $data, 'user' => $user]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'id_user' => 'required|exists:users,id',
            'nama' => [
                'required',
                'max:100',
                'regex:/^[a-zA-Z0-9\s\-]+$/'
            ],
            'alamat' => 'required',
            'no_hp' => 'required|digits_between:10,20',
            'email' => ['nullable', 'email', Rule::unique('members')->ignore($id)],
            'foto' => 'nullable|image|mimes:jpeg,jpg,png,webp,svg|max:2048',
        ]);
        

        $member = Member::findOrFail($id);

        if ($request->hasFile('foto')) {
            $oldFilePath = public_path('uploads/' . $member->foto);
            if(File::exists($oldFilePath)) {
                File::delete($oldFilePath);
            }
            // $file = $request->file('foto');
            // $filename = time() . '_' . $file->getClientOriginalName();
            // $file->move(public_path('uploads'), $filename);
            // $validated['foto'] = $filename;

            $filename = $request->file('foto')->hashName();
            $request->file('foto')->storeAs('public/uploads', $filename);
            $validated['foto'] = $filename;
        }

        $status = $member->update($validated);
        
        if ($request->expectsJson()) {
            return response()->json([
                'status' => $status ? true : false,
                'message' => $status ? 'Berhasil diupdate' : 'Gagal diupdate',
            ], $status ? 200 : 500);
        }

        if($status) return redirect('members')->with('success', 'Data ini berhasil diupdate');
        else return redirect('members')->with('error', 'Data ini gagal diupdate');
    }

    public function destroy(Request $request, $id)
    {
        $result = Member::findOrFail($id);

        if ($result->foto) {
            $oldFilePath = public_path('uploads/' . $result->foto);
            if (File::exists($oldFilePath)) {
                File::delete($oldFilePath);
            }
        }

        $status = $result->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'status' => $status ? true : false,
                'message' => $status ? 'Berhasil dihapus' : 'Gagal dihapus',
            ], $status ? 200 : 500);
        }

        if($status) return redirect('members')->with('success', 'Data ini berhasil dihapus');
        else return redirect('members')->with('error', 'Data ini gagal dihapus');
    }
}
