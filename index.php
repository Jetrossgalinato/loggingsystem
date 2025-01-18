<?php session_start(); ?>
<html>
    <head>
        <script type="text/javascript" src="js/adapter.min.js"></script>
        <script type="text/javascript" src="js/vue.min.js"></script>
        <script type="text/javascript" src="js/instascan.min.js"></script>
        <link rel="stylesheet" href="css/bootstrap.min.css">
    </head>

    <body>
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <video style="margin-top: 50px" id="preview" width="100%"></video>
                    <?php
                    if (isset($_SESSION['error'])) {
                        echo "<div class='alert alert-danger' id='error-message'>
                            <h4>Error!</h4>
                            " .
                            $_SESSION['error'] .
                            "
                            </div>";
                        unset($_SESSION['error']);
                    }

                    if (isset($_SESSION['success'])) {
                        echo "<div class='alert alert-success' id='success-message'>
                            <h4>Success!</h4>
                            " .
                            $_SESSION['success'] .
                            "
                            </div>";
                        unset($_SESSION['success']);
                    }
                    ?>
                </div>
                <div class="col-md-6">
                    <form action="insert.php" method="post" class="form-horizontal">
                        <label style="margin-top: 50px;">SCAN QR CODE</label>
                        <input type="text" name="text" id="text" readonly="" class="form-control">
                    </form>
                    <form method="GET" action="" class="form-inline" style="margin-bottom: 20px;">
                        <input type="text" name="search" class="form-control" placeholder="Search Student ID" 
                        value="<?php echo isset($_GET['search'])
                            ? $_GET['search']
                            : ''; ?>">
                        <button type="submit" class="btn btn-primary" style="margin-left: 10px;">Search</button>
                    </form>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <td>Student ID</td>
                                <td>Log Time</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $server = 'localhost';
                            $username = 'root';
                            $password = '';
                            $database = 'qrcodedb';

                            $conn = new mysqli(
                                $server,
                                $username,
                                $password,
                                $database
                            );

                            if ($conn->connect_error) {
                                die(
                                    'Connection failed: ' . $conn->connect_error
                                );
                            }
                            $search = isset($_GET['search'])
                                ? $conn->real_escape_string($_GET['search'])
                                : '';
                            $sql =
                                'SELECT student_id, time_in FROM table_attendance';

                            if (!empty($search)) {
                                $sql .= " WHERE student_id LIKE '%$search%'";
                            }

                            $query = $conn->query($sql);

                            while ($row = $query->fetch_assoc()) { ?>
                                    <tr>
                                        <td><?php echo $row[
                                            'student_id'
                                        ]; ?></td>
                                        <td><?php echo $row['time_in']; ?></td>
                                    </tr>
                            <?php }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <script>
            let Scanner = new Instascan.Scanner({ video: document.getElementById('preview') });
            Instascan.Camera.getCameras().then(function (cameras) {
                if (cameras.length > 0) {
                    Scanner.start(cameras[0]);
                } else {
                    alert('No cameras found.');
                }
            }).catch(function (e) {
                console.error(e);
            });

            Scanner.addListener('scan', function (c) {
                document.getElementById('text').value = c;
                document.forms[0].submit();
            });
            // Function to hide the message after 3 seconds
            function hideMessage(id) {
                setTimeout(function() {
                    var element = document.getElementById(id);
                    if (element) {
                        element.style.display = 'none';
                    }
                }, 3000); // 3000ms = 3 seconds
            }

            // Call hideMessage for both error and success messages
            window.onload = function() {
                if (document.getElementById('error-message')) {
                    hideMessage('error-message');
                }
                if (document.getElementById('success-message')) {
                    hideMessage('success-message');
                }
            };
        </script>
    </body>
</html>
