<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <title>Reset Password | COE Document Tracking System</title>

    {{-- Boxicons --}}
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'> 

    {{-- JQuery --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    {{-- Assets --}}
    <link rel="icon" href="{{Vite::asset('resources/img/COE.png')}}" type="image/x-icon">
    @vite(['resources/css/register_login.css'])

    {{-- Scripts --}}
    @vite(['resources/js/forgotPassword.js'])
</head>
<body>
    <span id="passwordReset" data-email="{{$email}}" data-token="{{$token}}"></span>

    <x-forms.reset-password/>
</body>
<script>
    window.routes = {
        resetPassword : "{{route('account.resetPassword')}}"
    }
</script>

</html>