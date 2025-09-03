@extends('templates.header')

@section('content')   
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
      Tambah User Baru
    </h1>
    <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="{{ route('users.index') }}">User</a></li>
      <li class="active">Tambah Baru</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
  <div class="box">
    <div class="box-header with-border">
      <a href="{{ route('users.index') }}" class="btn bg-purple"><i class="fa fa-chevron-left"></i> Kembali</a>
    </div>
    <div class="box-body">
      @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> Ada beberapa masalah dengan input Anda.<br><br>
            <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
            </ul>
        </div>
      @endif  

      {{-- Form mengarah ke route 'users.store' dengan method POST --}}
      <form action="{{ route('users.store') }}" method="POST" class="form-horizontal">
        @csrf
        
        {{-- Input Nama --}}
        <div class="form-group">
            <label for="name" class="control-label col-sm-2">Nama Lengkap</label>
            <div class="col-sm-10">
                <input type="text" name="name" id="name" class="form-control" placeholder="Masukkan nama lengkap user" value="{{ old('name') }}">
            </div>
        </div>

        {{-- TAMBAHKAN: Input Email --}}
        <div class="form-group">
            <label for="email" class="control-label col-sm-2">Alamat Email</label>
            <div class="col-sm-10">
                <input type="email" name="email" id="email" class="form-control" placeholder="Masukkan alamat email yang valid" value="{{ old('email') }}">
            </div>
        </div>

        {{-- Input Password --}}
        <div class="form-group">
            <label for="password" class="control-label col-sm-2">Password</label>
            <div class="col-sm-10">
                <input type="password" name="password" id="password" class="form-control" placeholder="Masukkan password (minimal 6 karakter)">
            </div>
        </div>
        
        {{-- Input Konfirmasi Password --}}
        <div class="form-group">
            <label for="password_confirmation" class="control-label col-sm-2">Konfirmasi Password</label>
            <div class="col-sm-10">
                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Ketik ulang password">
            </div>
        </div>

        <div class="form-group">
          <div class="col-sm-10 col-sm-offset-2">
            <div class="alert alert-info" style="padding: 10px;">
              <i class="icon fa fa-info-circle"></i> 
              <strong>Serial Number</strong> akan dibuat secara otomatis oleh sistem.
            </div>
          </div>
        </div>

        {{-- Tombol Simpan --}}
        <div class="form-group">
            <div class="col-sm-10 col-sm-offset-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fa fa-save"></i> Simpan User Baru
                </button>
            </div>
        </div>
    </form>
    </div>
    <!-- /.box-body -->
  </div>
  <!-- /.box -->
</section>
<!-- /.content -->
@endsection