@extends('templates/header')

@section('content')   
   <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
          {{ empty($data) ? 'Tambah' : 'Edit' }} pengeluaran
        </h1>
        <ol class="breadcrumb">
          <li><a href="{{ url('pengeluaran') }}"><i class="fa fa-dashboard"></i> Home</a></li>
          <li>pengeluaran</li>
          <li class="active">{{ empty($data) ? 'Tambah' : 'Edit' }} pengeluaran</li>
        </ol>
      </section>
  
      <!-- Main content -->
      <section class="content">
        
        <!-- Default box -->
        <div class="box">
          <div class="box-header with-border">
            <a href="{{ url('pengeluaran') }}" class="btn bg-purple"><i class="fa fa-chevron-left"></i>Kembali</a>
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
              action="{{ empty($data) ? url('pengeluaran') : url("pengeluaran/$data->id/edit") }}" 
              method="POST" 
              enctype="multipart/form-data" 
              class="form-horizontal">
              
              @csrf
              @if (!empty($data))
                  @method('PATCH')
              @endif

              {{-- Nama Pengeluaran --}}
              <div class="form-group">
                  <label class="control-label col-sm-2">Nama Pengeluaran</label>
                  <div class="col-sm-10">
                      <input type="text" name="nama" class="form-control" placeholder="nama" value="{{ @$data->nama }}">
                  </div>
              </div>

              {{-- Jumlah --}}
              <div class="form-group">
                  <label class="control-label col-sm-2">Jumlah</label>
                  <div class="col-sm-10">
                      <input type="text" name="jumlah" class="form-control" placeholder="jumlah" value="{{ @$data->jumlah }}">
                  </div>
              </div>
              
                {{-- Tgl Pengeluaran --}}
              <div class="form-group">
                  <label class="control-label col-sm-2">Tgl Pengeluaran</label>
                  <div class="col-sm-10">
                      <input type="date" name="tgl_pengeluaran" class="form-control" placeholder="tgl_pengeluaran" value="{{ @$data->tgl_pengeluaran }}">
                  </div>
              </div>

              {{-- Keterangan --}}
              <div class="form-group">
                  <label class="control-label col-sm-2">Keterangan</label>
                  <div class="col-sm-10">
                      <textarea name="keterangan" class="form-control" placeholder="keterangan">{{ @$data->keterangan }}</textarea>
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