<div class="wrapper">
    {{-- Create Account Form --}}
    <form id="createAccountForm" method="POST"  autocomplete="off">
        @csrf
        @method('POST')

        {{-- Title --}}
        <h1>Create Account</h1>

        {{-- CSRF Token --}}
        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">

        {{-- Username Input --}}
        <div class="input-box">
            <input type="text" name="name" id="name" placeholder="Username" required>
            <i class='bx bxs-user'></i>
        </div>

        {{-- Email Input --}}
        <div class="input-box">
            <input type="email" name="email" id="email" placeholder="Email" required>
            <i class='bx bx-mail-send'></i>
        </div>

        {{-- Password Input --}}
        <div class="input-box">
            <input type="password" name="password" id="password" placeholder="Password" required>
            <i class='bx bxs-lock-alt'></i>
        </div>

        {{-- Password Confirmation Input --}}
        <div class="input-box">
            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password" required>
            <i class='bx bxs-lock-alt'></i>
        </div>

        {{-- Submit Button --}}
        <button type="submit" class="btn">Register</button>
    </form>
    <div class="register-link">
        {{-- Redirect users with account to the login page --}}
        <p>Already have an account? <a href="{{ route('show.login') }}">Login</a></p>
    </div>
</div>