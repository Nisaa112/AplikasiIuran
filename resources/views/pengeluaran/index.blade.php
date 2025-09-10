@extends('templates.header')

@section('content')
 <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
          pengeluaran Iuran
        </h1>
        <ol class="breadcrumb">
          <li><a href="{{ url('/pengeluaran') }}"><i class="fa fa-dashboard"></i> Home</a></li>
        </ol>
      </section>
  
      <!-- Main content -->
      <section class="content">
  
        <!-- Default box -->
        <div class="box">
          <div class="box-header with-border">
            <a href="{{ url('pengeluaran/add') }}" class="btn btn-success"><i class="fa fa-plus-circle"></i>Tambah</a>
          <div class="box-body">
            <div class="row" style="margin-bottom: 10px;">
              <div class="col-md-6">
                <div class="input-group">
                  <input type="text" id="searchInput" class="form-control" placeholder="Search...">
                  <span class="input-group-btn">
                    <button class="btn btn-default" type="button" id="searchBtn"><i class="fa fa-search"></i></button>
                  </span>
                </div>
              </div>
            </div>
            <table class="table table-stripped">
              <thead>
                <tr>
                  <th>NO</th>
                  <th>Nama Pengeluaran</th>
                  <th>Jumlah</th>
                  <th>Tgl Pengeluaran</th>
                  <th>Keterangan</th>
                </tr>
              </thead>

              <tbody>
                @foreach ($data as $index => $row)
                <tr class="umkm-row" data-index="{{ $index }}">
                  <td class="row-no"></td>
                  <td>{{ $row->nama }}</td>
                  <td>@rupiah($row->jumlah)</td>
                  <td>{{ $row->tgl_pengeluaran }}</td>
                  <td>{{ $row->keterangan }}</td>
                  <td>
                    <a href="{{ url("pengeluaran/$row->id/edit") }}" class="btn btn-sm btn-warning"><i class="fa fa-pencil"></i></a>
                    <form action="{{ url("pengeluaran/$row->id/delete") }}" method="POST" style="display:inline;">
                      {{ csrf_field() }}
                      {{ method_field('DELETE') }}
                      <button class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                    </form>
                  </td>
                </tr> 
                  
                @endforeach
              </tbody>
            </table>
            <div class="row">
              <div class="col-md-12 text-right">
                <span id="pageInfo" class="label label-default" style="margin-right: 10px;"></span>
                <button class="btn btn-default" id="prevBtn">Previous</button>
                <button class="btn btn-primary" id="nextBtn">Next</button>
              </div>
            </div>
          </div>
          <!-- /.box-body -->
          <!-- /.box-footer-->
        </div>
        <!-- /.box -->
  
      </section>
      <!-- /.content -->
@endsection

@push('scripts')
<script>
  $(function () {
    const perPage = 5;
    let page = 1;

    function render() {
      const keyword = $('#searchInput').val().toLowerCase();
      const $rows = $('.umkm-row').hide().filter((_, el) =>
        $(el).text().toLowerCase().includes(keyword)
      );

      const total = $rows.length;
      const pages = Math.max(1, Math.ceil(total / perPage));
      page = Math.min(page, pages);

      $rows.slice((page - 1) * perPage, page * perPage).each((i, el) => {
        $(el).show();
        $(el).find('.row-no').text((page - 1) * perPage + i + 1);
      });

      $('#pageInfo').text(`Halaman ${page} dari ${pages}`);
      $('#prevBtn').prop('disabled', page === 1);
      $('#nextBtn').prop('disabled', page === pages);
    }

    $('#prevBtn').click(() => { page--; render(); });
    $('#nextBtn').click(() => { page++; render(); });

    // Trigger search saat tombol diklik
    $('#searchBtn').click(() => { page = 1; render(); });

    // Trigger juga saat Enter ditekan di input
    $('#searchInput').keypress(function (e) {
      if (e.which === 13) {
        page = 1;
        render();
      }
    });

    render();
  });
</script>
@endpush
