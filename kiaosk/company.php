<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Company Registration Form</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>

    <style>
        .card {
            background-color: #f8f9fa;
            padding: 20px;
            margin-top: 20px;
            width: 350px; /* Reduced card width */
        }

        .input-group-text {
            background-color: #007bff;
            color: white;
        }

        .logo {
            display: block;
            margin: 0 auto 20px; /* Center the logo and add space below */
            width: 100px; /* Set the logo width */
            cursor: pointer; /* Show pointer cursor to indicate click */
        }

        .hidden-input {
            display: none;
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="card bg-light mx-auto">
            <article class="card-body mx-auto">
                <!-- Logo Image -->
                <img src="" alt="Company Logo" class="logo" id="company-logo">
                <input type="file" class="hidden-input" id="logo-upload" accept="image/*"> <!-- Hidden input for file upload -->

                <h4 class="card-title mt-3 text-center">Company Registration</h4>

                <!-- Registration Form -->
                <form id="registration-form">
                    <div class="form-group input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fa fa-user"></i> </span>
                        </div>
                        <input id="company-name" class="form-control" placeholder="Company Name" type="text" required>
                    </div>

                    <div class="form-group input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fa fa-phone"></i> </span>
                        </div>
                        <input id="phone-number" class="form-control" placeholder="Phone Number" type="text" required>
                    </div>

                    <div class="form-group input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"> <i class="fa fa-map-marker"></i> </span>
                        </div>
                        <input id="address" class="form-control" placeholder="Address" type="text" required>
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary btn-block">Register</button>
                    </div>
                </form>
            </article>
        </div>
    </div>

    <script>
        // Load stored company logo from sessionStorage if available
        window.onload = function () {
            const storedLogo = sessionStorage.getItem('companyLogo');
            if (storedLogo) {
                document.getElementById('company-logo').src = storedLogo;
            } else {
                document.getElementById('company-logo').src = 'default-logo.png'; // Use a default image if none uploaded
            }
        };

        // Handle logo click to trigger file upload
        document.getElementById('company-logo').addEventListener('click', function () {
            document.getElementById('logo-upload').click();
        });

        // Handle image upload and save the image URL in sessionStorage
        document.getElementById('logo-upload').addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function (event) {
                    const logoSrc = event.target.result;
                    document.getElementById('company-logo').src = logoSrc;
                    sessionStorage.setItem('companyLogo', logoSrc); // Store the image data in sessionStorage
                };
                reader.readAsDataURL(file); // Convert file to Data URL for storing
            }
        });

        // Form submission handling
        document.getElementById('registration-form').addEventListener('submit', function (e) {
            e.preventDefault(); // Prevent default form submission

            // Get values from the form
            const companyName = document.getElementById('company-name').value;
            const phoneNumber = document.getElementById('phone-number').value;
            const address = document.getElementById('address').value;

            // Store the values in sessionStorage
            sessionStorage.setItem('companyName', companyName);
            sessionStorage.setItem('phoneNumber', phoneNumber);
            sessionStorage.setItem('address', address);

            // Redirect to demo.php
            window.location.href = 'admin1.php';
        });
    </script>

</body>

</html>
