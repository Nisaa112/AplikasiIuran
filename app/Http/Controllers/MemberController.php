<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class MemberController extends Controller
{
    private function findMemberByIdAndUser($id)
    {
        return Member::where('id_user', Auth::id())->findOrFail($id);
    }

    public function index(Request $request)
    {
        $data = Member::where('id_user', Auth::id())->with('user')->get();

        if($request->expectsJson()) {
            return response()->json($data);
        }

        return view('member/index', ['data' => $data]);
    }

    public function create()
    {
        // $user = User::all();
        return view('member/form');
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
            'alamat' => 'required',
            'no_hp' => 'required|digits_between:10,20',
            'email' => 'nullable|email|unique:members,email',
            'foto' => 'nullable|mimes:jpeg,jpg,png,webp,svg|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $validated['foto'] = $filename;
        }

        // if ($request->hasFile('foto')) {
        //     $filename = $request->file('foto')->hashName();
        //     $request->file('foto')->storeAs('public/uploads', $filename);
        //     $validated['foto'] = $filename;
        // }

        $validated['id_user'] = Auth::id();

        $status = Member::create($validated);

        if ($request->expectsJson()) {
            return response()->json([
                'status' => $status ? true : false,
                'message' => $status ? 'Berhasil ditambah' : 'Gagal ditambah',
            ], $status ? 200 : 500);
        }

        if($status) return redirect('/member')->with('success', 'Data ini berhasil ditambahkan');
        else return redirect('/member')->with('error', 'Data ini gagal ditambahkan');
    }

    public function edit($id)
    {
        // dd('ID dari URL: ' . $id, 'ID User yang Login: ' . Auth::id());
        $data = $this->findMemberByIdAndUser($id);
        // $user = User::all();
        return view('member/form', ['data' => $data]);
    }

    public function update(Request $request, $id)
    {
        $member = $this->findMemberByIdAndUser($id);
        
        $validated = $request->validate([
            // 'id_user' => 'required|exists:users,id',
            'nama' => [
                'required',
                'max:100',
                'regex:/^[a-zA-Z0-9\s\-]+$/'
            ],
            'alamat' => 'required',
            'no_hp' => 'required|digits_between:10,20',
            'email' => ['nullable', 'email', Rule::unique('members')->ignore($member->id)],
            'foto' => 'nullable|image|mimes:jpeg,jpg,png,webp,svg|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            if ($member->foto && file_exists(public_path('uploads/' . $member->foto))) {
                unlink(public_path('uploads/' . $member->foto));
            }
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $validated['foto'] = $filename;

            // $filename = $request->file('foto')->hashName();
            // $request->file('foto')->storeAs('public/uploads', $filename);
            // $validated['foto'] = $filename;
        }

        $status = $member->update($validated);
        
        if ($request->expectsJson()) {
            return response()->json([
                'status' => $status ? true : false,
                'message' => $status ? 'Berhasil diupdate' : 'Gagal diupdate',
            ], $status ? 200 : 500);
        }

        if($status) return redirect('/member')->with('success', 'Data ini berhasil diupdate');
        else return redirect('/member')->with('error', 'Data ini gagal diupdate');
    }

    public function destroy(Request $request, $id)
    {
        $result = $this->findMemberByIdAndUser($id);

        if ($result->foto && file_exists(public_path('uploads/' . $result->foto))) {
            unlink(public_path('uploads/' . $result->foto));
        }

        $status = $result->delete();

        if ($request->expectsJson()) {
            return response()->json([
                'status' => $status ? true : false,
                'message' => $status ? 'Berhasil dihapus' : 'Gagal dihapus',
            ], $status ? 200 : 500);
        }

        if($status) return redirect('/member')->with('success', 'Data ini berhasil dihapus');
        else return redirect('/member')->with('error', 'Data ini gagal dihapus');
    }
}
