CREATE TABLE applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    full_name VARCHAR(255) NOT NULL,
    birth_date DATE NOT NULL,
    address TEXT NOT NULL,
    city VARCHAR(100) NOT NULL,
    state VARCHAR(100) NOT NULL,
    postal_code VARCHAR(20) NOT NULL,
    cell_phone VARCHAR(15) NOT NULL,
    email VARCHAR(255) NOT NULL,
    employer VARCHAR(255),
    allergies TEXT,
    current_pets TEXT,
    availability TEXT,
    emergency_contact TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
