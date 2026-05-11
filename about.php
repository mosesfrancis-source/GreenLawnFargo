<?php
include "header.php";
?>
<div class="row mt-5">

    <div class="container my-5">

        <div class="card shadow-lg border-0">

            <div class="card-body p-5 text-center">

                <h1 class="display-5 fw-bold text-success mb-4">
                    About Green Lawn Fargo
                </h1>

                <p class="lead">
                    Green Lawn Fargo is a lawn care and landscaping web application
                    developed to simplify the process of requesting, estimating,
                    and booking lawn services online.
                </p>

                <p>
                    The system allows customers to browse available services,
                    create secure accounts, request service estimates,
                    and schedule lawn care appointments through a responsive
                    and user-friendly interface.
                </p>

                <p>
                    On the administrative side, the application provides tools
                    for managing services, customer requests, bookings,
                    and business operations efficiently.
                </p>

                <p>
                    The project was developed using
                    <strong>PHP</strong>,
                    <strong>MySQL</strong>,
                    <strong>Bootstrap</strong>,
                    <strong>HTML</strong>,
                    <strong>CSS</strong>,
                    and <strong>SQL</strong>,
                    while applying database normalization,
                    prepared statements, session authentication,
                    and password hashing for security and performance.
                </p>

            </div>

        </div>

    </div>

    <!-- Moses Card -->
    <div class="card" style="width: 18rem;">
        <img src="./images/Mojo.jpg" class="card-img-top" alt="...">
        <div class="card-body">
            <h5 class="card-title">Moses Francis</h5>
            <p class="card-text">Backend Developer & Database Administrator.</p>
            <a href="moses.php" class="btn btn-success">View Moses' Role</a>
        </div>
    </div>

    <div class="card" style="width: 18rem;">
        <img src="./images/female.jpg" class="card-img-top" alt="...">
        <div class="card-body">
            <h5 class="card-title">Jesleen Mulbah</h5>
            <p class="card-text">Frontend Developer & UI Designer.</p>
            <a href="jesleen.php" class="btn btn-success">View Jesleen's Role</a>
        </div>
    </div>
</div>
<?php include "footer.php"; ?>