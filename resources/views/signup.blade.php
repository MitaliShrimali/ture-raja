<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <meta name="description" content="Create a new account for {{ $type ?? 'user' }} on Tour Raja.">
    <style>
        body {font-family: 'Inter', sans-serif; background: linear-gradient(135deg, #f0f4ff, #e0e7ff); margin:0; display:flex; justify-content:center; align-items:center; height:100vh;}
        .container {background:#fff; padding:2rem 3rem; border-radius:12px; box-shadow:0 8px 24px rgba(0,0,0,0.1); max-width:400px; width:100%;}
        h2 {margin-bottom:1.5rem; text-align:center; color:#2c3e50;}
        label {display:block; margin-top:1rem; color:#34495e;}
        input[type=text], input[type=email], input[type=password] {width:100%; padding:0.5rem; border:1px solid #ccc; border-radius:6px; margin-top:0.3rem;}
        button {margin-top:1.5rem; width:100%; padding:0.7rem; background:#4a90e2; color:#fff; border:none; border-radius:6px; cursor:pointer; font-size:1rem;}
        button:hover {background:#357ab8;}
        .error {color:#e74c3c; margin-top:0.5rem;}
    </style>
</head>
<body>
<div class="container">
    <h2>Sign Up {{ ucfirst($type ?? 'User') }}</h2>
    @if ($errors->any())
        <div class="error">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <form method="POST" action="{{ route('signup.submit') }}">
        @csrf
        <input type="hidden" name="type" value="{{ $type ?? 'customer' }}">
        <label for="first_name">First Name</label>
        <input id="first_name" name="first_name" type="text" required>
        <label for="last_name">Last Name</label>
        <input id="last_name" name="last_name" type="text" required>
        <label for="email">Email</label>
        <input id="email" name="email" type="email" required>
        <label for="password">Password</label>
        <input id="password" name="password" type="password" required minlength="8">
        <button type="submit">Create Account</button>
    </form>
</div>
</body>
</html>
