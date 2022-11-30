<?php 



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/core.css">
    <link rel="stylesheet" href="../CSS/dashboard.css">
    <title>User dashboard</title>
</head>
<body>

    <div class="container">
        <h1>USER DASHBOARD</h1>
        <?php 
            //require_once '../includes/customer/dashboard.inc.php';

            require_once '../includes/customer/functions.php';

            session_start();

            if(isset($_SESSION["customerID"])){
                echo 'LOGGED IN<br/>';
                echo $_SESSION["customerID"];
            }
            

            include '../admin/sql_connect.php';
            $conn = OpenConnDB();
            $customerID = $_SESSION["customerID"];

            $reservations = customerReservation($conn, $customerID);

            ?>

            <div class="reservations">
                <div class="reservations--header">
                    <div class="reservation">
                        <div class="reservation--carImg">
                            Zjdęcie
                        </div>
                        <div class="reservation--carName">
                            Marka i model
                        </div>
                        <div class="reservation--dateFrom">
                            od kiedy
                        </div>
                        <div class="reservation--dateTo">
                            do kiedy
                        </div>
                        <div class="reservation--cost">
                            całkowity koszt
                        </div>
                    </div>
                </div>
            <?php 
                foreach ($reservations as $reservation) { 
            ?>
                
                <div class="reservation">
                    <div class="reservation--carImg">
                        <img src="../assets/<?php echo $reservation['photo_url']; ?>" alt="">
                    </div>
                    <div class="reservation--carName">
                        <?php echo $reservation['name']; ?>
                    </div>
                    <div class="reservation--dateFrom">
                        <?php echo $reservation['from_date']; ?>
                    </div>
                    <div class="reservation--dateTo">
                        <?php echo $reservation['to_date']; ?>
                    </div>
                    <div class="reservation--cost">
                        <?php echo $reservation['cost']; ?>
                    </div>
                </div>
            <?php } ?>

            </div>
    </div>

</body>
</html>

<?php 


?>