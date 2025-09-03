@extends('templates/header')

@section('content')   
   <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
          {{ empty($data) ? 'Tambah' : 'Edit' }} member
        </h1>
        <ol class="breadcrumb">
          <li><a href="{{ url('member') }}"><i class="fa fa-dashboard"></i> Home</a></li>
          <li>Member</li>
          <li class="active">{{ empty($data) ? 'Tambah' : 'Edit' }} member</li>
        </ol>
      </section>
  
      <!-- Main content -->
      <section class="content">
        
        <!-- Default box -->
        <div class="box">
          <div class="box-header with-border">
            <a href="{{ url('member') }}" class="btn bg-purple"><i class="fa fa-chevron-left"></i>Kembali</a>
          </div>
          <div class="box-body">
            @if ($errors->any())
              <div class="alert alert-danger">
                  <ul>
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
                  </ul>
              </div>
            @endif  

            <form 
              action="{{ empty($data) ? url('member') : url("member/$data->id/edit") }}" 
              method="POST" 
              enctype="multipart/form-data" 
              class="form-horizontal">
              
              @csrf
              @if (!empty($data))
                  @method('PATCH')
              @endif

              {{-- Nama --}}
              <div class="form-group">
                  <label class="control-label col-sm-2">Nama</label>
                  <div class="col-sm-10">
                      <input type="text" name="nama" class="form-control" placeholder="nama" value="{{ @$data->nama }}">
                  </div>
              </div>

              {{-- Alamat --}}
              <div class="form-group">
                  <label class="control-label col-sm-2">Alamat</label>
                  <div class="col-sm-10">
                      <input type="text" name="alamat" class="form-control" placeholder="alamat" value="{{ @$data->alamat }}">
                  </div>
              </div>

              {{-- no_hp --}}
              <div class="form-group">
                  <label class="control-label col-sm-2">No HP</label>
                  <div class="col-sm-10">
                      <input type="text" name="no_hp" class="form-control" placeholder="no_hp" value="{{ @$data->no_hp }}">
                  </div>
              </div>

              {{-- email --}}
              <div class="form-group">
                  <label class="control-label col-sm-2">Email</label>
                  <div class="col-sm-10">
                      <input type="text" name="email" class="form-control" placeholder="email" value="{{ @$data->email }}">
                  </div>
              </div>

              {{-- Foto --}}
              <div class="form-group">
                  <label class="control-label col-sm-2">Foto</label>
                  <div class="col-sm-10">
                      <input type="file" name="foto" />
                      @if (!empty($data->foto))
                          <br>
                          <img src="{{ asset('uploads/' . $data->foto) }}" width="100" />
                      @endif
                  </div>
              </div>

              {{-- Tombol Simpan --}}
              <div class="form-group">
                  <div class="col-sm-10 col-sm-offset-2">
                      <button type="submit" class="btn btn-primary">
                          <i class="fa fa-save"></i> Simpan
                      </button>
                  </div>
              </div>
          </form>
          </div>
          <!-- /.box-body -->
          <!-- /.box-footer-->
        </div>
        <!-- /.box -->
  
      </section>
      <!-- /.content -->
@endsection