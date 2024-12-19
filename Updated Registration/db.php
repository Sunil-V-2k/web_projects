<?php
// Database connection
$host = 'localhost';
$dbname = 'volunteer_applications'; // Replace with your database name
$username = 'root'; // Replace with your MySQL username
$password = ''; // Replace with your MySQL password

$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$errorMessages = [];
$submittedData = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve and validate form data
    $fullName = trim($_POST['fullName'] ?? '');
    $birthDate = trim($_POST['birthDate'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $city = trim($_POST['city'] ?? '');
    $state = trim($_POST['state'] ?? '');
    $postalCode = trim($_POST['postalcode'] ?? '');
    $cellPhone = trim($_POST['cellphone'] ?? '');
    $email = trim($_POST['email'] ?? '');

    $employer = isset($_POST['employer']) ? trim($_POST['employer']) : null;
    $allergies = isset($_POST['alergies']) ? trim($_POST['alergies']) : null;
    $currentPets = isset($_POST['currentPets']) ? trim($_POST['currentPets']) : null;
    $availability = isset($_POST['available']) ? implode(', ', $_POST['available']) : null;
    $emergencyContact = isset($_POST['emergency']) ? trim($_POST['emergency']) : null;

    // Check required fields
    if (empty($fullName)) $errorMessages[] = 'Please fill out these fields...';

    if (empty($errorMessages)) {
        $submittedData = [
            'Full Name' => $fullName,
            'Birth Date' => $birthDate,
            'Address' => $address,
            'City' => $city,
            'State' => $state,
            'Postal Code' => $postalCode,
            'Cell Phone' => $cellPhone,
            'Email' => $email,
            'Employer' => $employer,
            'Allergies' => $allergies,
            'Current Pets' => $currentPets,
            'Availability' => $availability,
            'Emergency Contact' => $emergencyContact,
        ];
    
        try {
            // Insert data into database
            $sql = "INSERT INTO applications 
                    (full_name, birth_date, address, city, state, postal_code, cell_phone, email, employer, allergies, current_pets, availability, emergency_contact) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);

            if (!$stmt) {
                throw new Exception("Failed to prepare statement: " . $conn->error);
            }

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
            if (!$stmt->execute()) {
                throw new Exception("Execution failed: " . $stmt->error);
            }
            ?>
                <!DOCTYPE html>
                <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Submitted Details</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            /* background-color: #f4f4f9; */
                            background: linear-gradient(to right, #C4DFE6,  #66A5AD);
                            margin: 0;
                            padding: 20px;
                        }
                        .container {
                            max-width: 800px;
                            margin: auto;
                            /* background: #fff; */
                            background: linear-gradient(to left, #C4DFE6,  #66A5AD);
                            padding: 20px;
                            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
                            border-radius: 8px;
                            margin-top: 50px;
                        }
                        h2 {
                            text-align: center;
                        }
                        .error {
                            color: red;
                            margin: 10px 0;
                        }
                        ul.submitted-details {
                            list-style: none;
                            padding: 0;
                        }
                        ul.submitted-details li {
                            margin: 10px 0;
                            padding: 10px;
                            /* background-color: #f9f9f9; */
                            background: linear-gradient(to right, #C4DFE6,  #66A5AD);
                            border: 1px solid #ddd;
                            border-radius: 5px;
                        }
                        ul.submitted-details li span {
                            font-weight: bold;
                            display: inline-block;
                            min-width: 150px;
                        }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <?php if (!empty($submittedData)): ?>
                            <ul class="submitted-details">
                            <h2>Submitted Details</h2>
                                <?php foreach ($submittedData as $key => $value): ?>
                                    <li>
                                        <span><?= htmlspecialchars($key); ?>:</span> <?= htmlspecialchars($value ?? 'N/A'); ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </body>
                </html>
            <?php

            // echo "Data successfully inserted into the database.";
            $stmt->close();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    else
    {
            echo"<div style='background-color: #f8d7da; color: #721c24; padding: 20px; text-align: center; border: 1px solid #f5c6cb;
             border-radius: 8px; font-family: Arial, sans-serif;'>(Please fill in all required fields!)"; 
    }
}

$conn->close();
?>
