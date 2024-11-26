{{-- 
VISION CUBE SOFTWARE CO. 

View: Deactivated
The page that would indicate that the account the user logged in with has been deactivated by the admin

It contains:
-Message indicating the deactivation
-Back button that would log out the user and sent back to the log in page, after pressing it

Contributor/s: 
Calulut, Joshua Miguel C. 
--}}

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
      
        {{-- Title --}}
        <title>Account Deactivated | Document Tracking System</title>
      
        {{-- Boxicons --}}
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
      
        {{-- JQuery --}}
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
      
        {{-- Bootstrap --}}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" 
        integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" 
        integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" 
        integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
      
        {{-- Assets --}}
        <link rel="icon" href="{{Vite::asset('resources/img/COE.png')}}" type="image/x-icon">
        @vite(['resources/css/register_login.css'])
      
        {{-- Scripts --}}
        @vite(['resources/js/deactivated.js'])
      </head>
<body>
    <x-deactivated/>
</body>

<script>    

    window.routes = {
        logout : "{{route('account.logout')}}"
    }
</script>
</html>