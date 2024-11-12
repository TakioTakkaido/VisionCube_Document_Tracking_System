<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <title>Reset Password | COE Document Tracking System</title>

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
    @vite([
        'resources/js/forgotPassword.js',
        'resources/js/notification.js'
    ])
</head>
<body>
    <span id="passwordReset" data-token="{{$token}}"></span>

    <x-forms.reset-password/>

    <x-notification />
</body>
<script>
    window.routes = {
        resetPassword : "{{route('account.resetPassword')}}"
    }
</script>

</html>