@extends('templates.header')

@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
      Manajemen User
      <small>Daftar akun yang bisa login</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">User</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
  <!-- Default box -->
  <div class="box">
    <div class="box-header with-border">
      {{-- Arahkan ke route untuk membuat user baru --}}
      <a href="{{ route('users.create') }}" class="btn btn-success"><i class="fa fa-plus-circle"></i> Tambah User Baru</a>
    </div>
    <div class="box-body">
      @if(session('success'))
        <div class="alert alert-success alert-dismissible" style="margin-top: 15px;">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            <h4><i class="icon fa fa-check"></i> Berhasil!</h4>
            {!! session('success') !!}
        </div>
      @endif

      <table class="table table-striped table-bordered">
        <thead>
          <tr>
            <th style="width: 5%">NO</th>
            <th>Nama</th>
            {{-- Tambahkan Kolom Email --}}
            <th>Email</th>
            <th>Serial Number</th>
            <th>Tanggal Dibuat</th>
            <th style="width: 10%">Aksi</th>
          </tr>
        </thead>

        <tbody>
          {{-- Gunakan @forelse untuk handle jika data kosong --}}
          @forelse ($users as $user)
          <tr>
            {{-- Gunakan pagination helper untuk penomoran yang benar --}}
            <td>{{ $loop->iteration + $users->firstItem() - 1 }}</td>
            <td>{{ $user->name }}</td>
            {{-- Tampilkan data Email --}}
            <td>{{ $user->email }}</td>
            <td>
              <span class="label label-primary">{{ $user->serial_number }}</span>
            </td>
            <td>{{ $user->created_at->format('d F Y, H:i') }}</td>
            <td>
                <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                </form>
            </td>
          </tr> 
          @empty
          {{-- Update colspan menjadi 6 --}}
          <tr>
            <td colspan="6" class="text-center">Belum ada data user. Silakan tambah user baru.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
      {{-- Ini adalah cara paling mudah untuk menampilkan pagination dari Laravel --}}
      {{ $users->links() }}
    </div>
    <!-- /.box-footer-->
  </div>
  <!-- /.box -->
</section>
<!-- /.content -->
@endsection