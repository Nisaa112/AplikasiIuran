<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>AdminLTE 2 | Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="{{ asset('assets') }}/bootstrap/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('assets') }}/dist/css/AdminLTE.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ asset('assets') }}/plugins/iCheck/square/blue.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>Admin</b>IuranApp</a>
  </div>
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Sign in to start your session</p>

    {{-- ================= TAMBAHKAN BLOK INI ================= --}}
    @if ($errors->any())
        <div class="alert alert-danger" style="padding: 10px; margin-bottom: 15px;">
            <ul style="margin: 0; padding-left: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    {{-- ======================================================= --}}

    {{-- PERBAIKAN 1: Mengarahkan form ke rute 'login' yang benar --}}
    <form action="{{ route('login') }}" method="post">
      {{-- PERBAIKAN 2: Menambahkan CSRF Token untuk keamanan --}}
      @csrf

      {{-- PERBAIKAN 3: Menambahkan name="serial_number", mengubah type, dan menambahkan penanganan error --}}
      <div class="form-group has-feedback @error('serial_number') has-error @enderror">
        <input type="text" name="serial_number" class="form-control" placeholder="Serial Number" value="{{ old('serial_number') }}" required autofocus>
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
        @error('serial_number')
            <span class="help-block">{{ $message }}</span>
        @enderror
      </div>
      
      {{-- PERBAIKAN 4: Menambahkan name="password" dan penanganan error --}}
      <div class="form-group has-feedback @error('password') has-error @enderror">
        <input type="password" name="password" class="form-control" placeholder="Password" required>
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
         @error('password')
            <span class="help-block">{{ $message }}</span>
        @enderror
      </div>
      <div class="row">
        <!-- <div class="col-xs-8">
          <div class="checkbox icheck">
            <label>
              {{-- PERBAIKAN 5: Menambahkan name="remember" --}}
              <input type="checkbox" name="remember"> Remember Me
            </label>
          </div>
        </div> -->
        <!-- /.col -->
        <div class="col-xs-4 content-center">
          <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
        </div>
        <!-- /.col -->
      </div>
    </form>
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.2.3 -->
<script src="{{ asset('assets') }}/plugins/jQuery/jquery-2.2.3.min.js"></script>
<!-- Bootstrap 3.3.6 -->
<script src="{{ asset('assets') }}/bootstrap/js/bootstrap.min.js"></script>
<!-- iCheck -->
<script src="{{ asset('assets') }}/plugins/iCheck/icheck.min.js"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>
</html>