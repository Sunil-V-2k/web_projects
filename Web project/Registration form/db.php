<?php
// Database connection
$host = 'localhost';
$dbname = 'volunteer_applications'; // Replace with your database name
$username = 'root'; // Replace with your MySQL username
$password = ''; // Replace with your MySQL password

// Correct the variable name to $dbname
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error); // Connection error will be displayed
} else {
    echo "Thank you, your application has been submitted successfully!";
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $fullName = $_POST['fullName'];
    $birthDate = $_POST['birthDate'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $postalCode = $_POST['postalcode'];
    $cellPhone = $_POST['cellphone'];
    $email = $_POST['email'];
    $employer = isset($_POST['employer']) ? $_POST['employer'] : null;
    $allergies = isset($_POST['alergies']) ? $_POST['alergies'] : null;
    $currentPets = isset($_POST['currentPets']) ? $_POST['currentPets'] : null;
    $availability = isset($_POST['available']) ? implode(', ', $_POST['available']) : null;
    $emergencyContact = isset($_POST['emergency']) ? $_POST['emergency'] : null;

    try {
        // Insert data into database
        $sql = "INSERT INTO applications 
                (full_name, birth_date, address, city, state, postal_code, cell_phone, email, employer, allergies, current_pets, availability, emergency_contact) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Prepare the statement
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            throw new Exception("Failed to prepare statement: " . $conn->error);
        }

        // Bind parameters
        $stmt->bind_param(
            'sssssssssssss',
            $fullName,
            $birthDate,
            $address,
            $city,
            $state,
            $postalCode,
            $cellPhone,
            $email,
            $employer,
            $allergies,
            $currentPets,
            $availability,
            $emergencyContact
        );

        // Execute the statement
        if ($stmt->execute()) {
            echo "<script>
                    alert('Thank you $fullName, your application has been submitted successfully!');
                </script>";
            // echo "Thank you $fullName, your application has been submitted successfully!";
        } else {
            "<script>
                alert('Error: Unable to submit your application.');
            </script>";
            // throw new Exception("Failed to execute statement: " . $stmt->error);
        }

        $stmt->close();
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
}

$conn->close();
?>