<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        h1 {
            font-family: "Roboto", serif;
            font-weight: 400;
            font-style: italic;
            font-size: 4rem; /* Adjusted for responsive design */
            margin-bottom: 20px;
        }

        body {
            background-color: #fff;
            color: black;
            font-family: 'Arial', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 20px; /* Added padding for mobile */
        }

        .welcome-container {
            background-color: #fff;
            padding: 40px;
            text-align: center;
        }

        p {
            font-size: 1.2rem;
        }

        .btn {
            margin-top: 20px;
            font-size: 1.2rem;
            padding: 10px 20px;
            border-radius: 50px;
        }

        svg {
            width: 48px;
            height: 48px;
        }

        /* Responsive adjustments */
        @media (min-width: 768px) {
            h1 {
                font-size: 5rem;
            }

            svg {
                width: 64px;
                height: 64px;
            }
        }

        @media (min-width: 992px) {
            h1 {
                font-size: 6rem;
            }

            svg {
                width: 88px;
                height: 88px;
            }
        }
    </style>
</head>

<body>

    <div class="welcome-container">
        <h1>Welcome to Redrio</h1>
        <p>Your one-stop solution for managing products and sales!</p>
        <p>Explore the features and enjoy a seamless experience.</p>
        <a href="company.php" class="btn btn-outline-primary btn-lg" style="padding:0%;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" class="bi bi-arrow-right-circle" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0M4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5z"/>
            </svg>
        </a>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
