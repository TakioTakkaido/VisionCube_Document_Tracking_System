{{-- 
VISION CUBE SOFTWARE CO. 

View: Forgot Password
The page that would change the user's password by entering their respective email
It contains:
-Form to fill up email used in the system
-Sending of verification link to their respective emails for changing their password

Contributor/s: 
Calulut, Joshua Miguel C. 
Sanchez, Shane David U.
--}}

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  {{-- Title --}}
  <title>Forgot Password | Document Tracking System</title>

  {{-- Boxicons --}}
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

  {{-- JQuery --}}
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

  {{-- Bootstrap --}}
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" 
  integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" 
  integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>

  {{-- Assets --}}
  <link rel="icon" href="{{Vite::asset('resources/img/COE.png')}}" type="image/x-icon">
  @vite([
    'resources/css/register_login.css',
    'resources/css/notification.css',
  ])

  {{-- Scripts --}}
  @vite(['resources/js/forgotPassword.js'])
</head>

<body>
  <x-forms.forgot-password/>

  <x-notification />
</body>

{{-- Routes for AJAX --}}
<script>
  window.onload = function() {
      history.pushState(null, '', location.href);
  };

  window.addEventListener('popstate', function(event) {
      history.pushState(null, '', location.href);
  });

  window.routes = {
    sendResetPasswordLink : "{{route('account.sendResetPasswordLink')}}"
  }
</script>
</html>
