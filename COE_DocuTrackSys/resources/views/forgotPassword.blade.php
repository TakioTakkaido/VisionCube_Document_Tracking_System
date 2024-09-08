<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Forgot Password</title>
  <link rel="stylesheet" href="{{asset('css/register_login.css')}}">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
  <div class="wrapper">
    <form action="{{route('account.forgotPassword')}}" id="forgotPasswordForm" method="POST">
        @csrf
        @method('POST')
        <h1>Forgot Password</h1>
        <div class="input-box">
            <input type="email" id="email" name="email" placeholder="Email" required>
            <i class='bx bx-mail-send'></i>
        </div>
        <button type="submit" class="btn">Send Verification</button>
        <div class="register-link">
            <p>Remembered your password? <a href="{{route('account.showLogIn')}}">Login</a></p>
        </div>
    </form>
  </div>

    {{-- Add html box for error, temporary html --}}
    @if ($errors->any())
    <div class="alert">
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif
</body>
</html>
