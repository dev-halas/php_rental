<?php
include 'admin/sql_connect.php';

function get_cars($type)
{
    
    global $mysqli;


    switch ($type) {
        case 'available':
            $sql = "SELECT id,name,photo_url,type,price FROM cars WHERE available = 1";
            break;
        case 'unavailable':
            $sql = "SELECT cars.id,cars.name,cars.photo_url,cars.type,cars.price,reservations.to_date FROM cars INNER JOIN reservations ON cars.id = reservations.car_id
        WHERE cars.available = 0";
            break;
        case 'select':
            $sql = "SELECT id,name FROM cars WHERE available = 1";
            break;
    }

    $result = $mysqli->query($sql);

    echo $result;

    $rows = $result->fetch_all(MYSQLI_ASSOC);
    return $rows;
    
}

function generate_dashboard()
{

    $sql = "SELECT cars.name, customers.customerName, customers.customerSurname, reservations.cost, reservations.to_date FROM reservations INNER JOIN cars ON reservations.car_id = cars.id INNER JOIN customers ON customers.customerID = reservations.customer_id;";

    $mysqli = OpenConnDB();
    $result = $mysqli->query($sql);

    if ($result->num_rows > 0) {
        $rows = $result->fetch_all(MYSQLI_ASSOC);
        return $rows;
    }

    CloseConDB($mysqli);
}

function reserve($name, $surname, $phone_number, $car_id, $termin, $days, $hours)
{
    global $mysqli;

    $from_date = $termin;

    $to_date = date('Y-m-d H:i', strtotime($from_date . '+ ' . $days . ' days + ' . $hours . ' hours'));

    $sql = "SELECT price FROM cars WHERE id = $car_id";

    $result = $mysqli->query($sql);
    $row = $result->fetch_row();

    $price = $row[0];


    $cost = ($days * 24 + $hours) * $price;

    $sql_2 = "INSERT INTO clients (`name`,`surname`,`phone_number`) VALUES (?,?,?)";


    if ($statement = $mysqli->prepare($sql_2)) {
        if ($statement->bind_param('sss', $name, $surname, $phone_number)) {
            $statement->execute();

            $client_id = $mysqli->insert_id;
            $sql_3 = "INSERT INTO reservations(`client_id`, `car_id`,`from_date`,`to_date`,`cost`) VALUES (?,?,?,?,?)";
            if ($statement_2 = $mysqli->prepare($sql_3)) {
                if ($statement_2->bind_param('iissi', $client_id, $car_id, $from_date, $to_date, $cost)) {
                    $statement_2->execute();
                    $mysqli->query("UPDATE cars SET available = 0 WHERE id = $car_id");

                    header("Location:index.php");
                }
            }
        }
    } else {
        die('Niepoprawne zapytanie');
        //die('Niepoprawne zapytanie' . $mysqli->errno);????
    }
}

function getAvaliableCars(){
    $sql = "SELECT id,name,photo_url,type,price FROM cars WHERE available >= 1";
    $mysqli = OpenConnDB();
    $result = $mysqli->query($sql);

    if ($result->num_rows > 0) {
        $avaliableCars = $result->fetch_all(MYSQLI_ASSOC);
        return $avaliableCars;
    }

    CloseConDB($mysqli);
}

function getUnavaliableCars(){
    $sql = "SELECT id, name, photo_url, type, price FROM cars WHERE available < 1";
    $mysqli = OpenConnDB();
    $result = $mysqli->query($sql);

    if ($result->num_rows > 0) {
        $unavaliableCars = $result->fetch_all(MYSQLI_ASSOC);
        return $unavaliableCars;
    }

    CloseConDB($mysqli);
}

function selectAvaliableCars(){
    $sql = "SELECT id,name FROM cars WHERE available >= 1";
    $mysqli = OpenConnDB();
    $result = $mysqli->query($sql);

    if ($result->num_rows > 0) {
        $selectCars = $result->fetch_all(MYSQLI_ASSOC);
        return $selectCars;
    }

    CloseConDB($mysqli);
}



function reservation($customerID, $car_id, $termin, $days, $hours) {
    $mysqli = OpenConnDB();
    
    $from_date = $termin;

    $to_date = date('Y-m-d H:i', strtotime($from_date . '+ ' . $days . ' days + ' . $hours . ' hours'));

    $sql = "SELECT price FROM cars WHERE id = $car_id";

    $result = $mysqli->query($sql);
    $row = $result->fetch_row();

    $price = $row[0];
    $cost = ($days * 24 + $hours) * $price;

    $reservation_querry = "INSERT INTO reservations (customer_id, car_id , from_date, to_date, cost) VALUES (?, ?, ?, ?, ?)";
    $statement = mysqli_stmt_init($mysqli);

    if(!mysqli_stmt_prepare($statement, $reservation_querry)) {
        header("location: ../../user/index.php?error=statement_failed");
        exit();
    }

    mysqli_stmt_bind_param($statement, "iissi", $customerID, $car_id, $from_date, $to_date, $cost);
    mysqli_stmt_execute($statement);
    mysqli_stmt_close($statement);

    header("Location: index.php");

    exit();

    //$reservation_querry = "INSERT INTO reservations (customer_id, car_id , from_date, to_date, cost) VALUES ('$customerID', '$car_id', '$from_date', '$to_date', '$cost')";

    //$mysqli->query($reservation_querry);

    /*
        $customerID = $mysqli->insert_id;
        $sql_3 = "INSERT INTO reservations(`customerID`, `car_id`,`from_date`,`to_date`,`cost`) VALUES (?,?,?,?,?)";
        if ($statement_2 = $mysqli->prepare($sql_3)) {
            if ($statement_2->bind_param('iissi', $customerID, $car_id, $from_date, $to_date, $cost)) {
                $statement_2->execute();
                $mysqli->query("UPDATE cars SET available = 0 WHERE id = $car_id");

                
            }
        }
    */
    
    CloseConDB($mysqli);
}


