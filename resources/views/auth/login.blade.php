@extends('layouts.auth')

@section('auth-content')
<meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="card">
        <div class="card-body">
            @include('auth.partials.logo')


           

             <!-- Google Sign-In Button -->
       

             <div id="g_id_onload" 
             data-client_id="290674372837-ulmmj031763gm3vhg0ueeps5eobloooe.apps.googleusercontent.com" 
             data-callback="onSignIn"></div>
             <div class="g_id_signin form-control" data-type="standard"></div>

            <!-- Error message display -->
            <div id="error-message" class="text-danger mt-2"></div>

            <div id="countdown" class="text-danger mt-2"></div>

        </div>
        
    </div>
    

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://accounts.google.com/gsi/client" async defer></script>


    <script>

        function decodeJwtResponse(token) {
        let base64Url = token.split('.')[1];
        let base64 = base64Url.replace(/-/g, '+').replace(/_/g, '/');
        let jsonPayload = decodeURIComponent(atob(base64).split('').map(function(c) {
            return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);
        }).join(''));
        return JSON.parse(jsonPayload);
    }

    window.onSignIn = googleUser => {
        var user = decodeJwtResponse(googleUser.credential);
        console.log(user);
        if (user) {
            $.ajax({
                url: "{{ route('auth.google.login') }}",
                method: 'POST',
                data: {
                    email: user.email,
                    first_name: user.given_name,
                    last_name: user.family_name,
                    google_id: user.sub,
                    _token: $('meta[name="csrf-token"]').attr('content'), // CSRF token

                },
                beforeSend: function() {
                    toastr.info('Logging in with Google, please wait...');
                },
                success: function(response) {
                toastr.success(response.message);
                if (response.redirect_url) {
                    setTimeout(function() {
                        window.location.href = response.redirect_url;
                    }, 1000);
                }
            },
            error: function(xhr, status, error) {
            console.log(xhr.responseText); // This will provide more detail about the 500 error.
            toastr.error('Google login failed. Please try again.');
                }
            });
        } else {
            toastr.error('Google login failed. Please try again.');
        }
    };
        




</script>
@endsection
