@extends('templates/header')

@section('content')   
   <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
          {{ empty($data) ? 'Tambah' : 'Edit' }} pembayaran
        </h1>
        <ol class="breadcrumb">
          <li><a href="{{ url('pembayaran') }}"><i class="fa fa-dashboard"></i> Home</a></li>
          <li>pembayaran</li>
          <li class="active">{{ empty($data) ? 'Tambah' : 'Edit' }} pembayaran</li>
        </ol>
      </section>
  
      <!-- Main content -->
      <section class="content">
        
        <!-- Default box -->
        <div class="box">
          <div class="box-header with-border">
            <a href="{{ url('pembayaran') }}" class="btn bg-purple"><i class="fa fa-chevron-left"></i>Kembali</a>
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
              action="{{ empty($data) ? url('pembayaran') : url("pembayaran/$data->id/edit") }}" 
              method="POST" 
              enctype="multipart/form-data" 
              class="form-horizontal">
              
              @csrf
              @if (!empty($data))
                  @method('PATCH')
              @endif

              {{-- Anggota --}}
                <div class="form-group">
                    <label class="control-label col-sm-2">Anggota</label>
                    <div class="col-sm-10">
                        <select name="id_member" class="form-control">
                            {{-- Opsi default --}}
                            <option value="">Pilih Anggota</option>
                            {{-- Loop untuk menampilkan data anggota dari controller --}}
                            @foreach($members as $id => $nama)
                                <option value="{{ $id }}" {{ old('id_member', @$data->id_member) == $id ? 'selected' : '' }}>
                                    {{ $nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

              {{-- Jumlah --}}
              <div class="form-group">
                  <label class="control-label col-sm-2">Jumlah</label>
                  <div class="col-sm-10">
                      <input type="text" name="jumlah" class="form-control" placeholder="jumlah" value="{{ @$data->jumlah }}">
                  </div>
              </div>

              {{-- Catatan --}}
              <div class="form-group">
                  <label class="control-label col-sm-2">Catatan</label>
                  <div class="col-sm-10">
                      <textarea name="catatan" class="form-control" placeholder="catatan produk">{{ @$data->catatan }}</textarea>
                  </div>
              </div>

              {{-- Metode Pembayaran --}}
                <div class="form-group">
                    <label class="control-label col-sm-2">Metode Pembayaran</label>
                    <div class="col-sm-10">
                        <select name="metode_bayar" class="form-control">
                            @foreach(['cash', 'transfer'] as $option)
                                <option value="{{ $option }}" 
                                    {{ old('metode_bayar', @$data->metode_bayar) == $option ? 'selected' : '' }}>
                                    {{ ucfirst($option) }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Tgl Bayar --}}
              <div class="form-group">
                  <label class="control-label col-sm-2">Tgl Bayar</label>
                  <div class="col-sm-10">
                      <input type="date" name="tgl_bayar" class="form-control" placeholder="tgl_bayar" value="{{ @$data->tgl_bayar }}">
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