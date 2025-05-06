CREATE TABLE orders (
        Order_ID SERIAL PRIMARY KEY,
        Client_id INT UNSIGNED NOT NULL,
        Product_ID INT,
        Creation_Date DATE,
        Employee_ID INT UNSIGNED,
        Perfomed_Date DATE,
        amount INT,
        status VARCHAR(50) CHECK (status IN ('Создан', 'В работе', 'Завершен', 'Отменен')
        FOREIGN KEY (Client_ID) REFERENCES Client(Client_ID),
        FOREIGN KEY (Product_ID) REFERENCES Product(Product_ID),
        FOREIGN KEY (Employee_ID) REFERENCES Employee(Employee_ID));
    );
   
CREATE TABLE Employee(
    employee_id SERIAL PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    Middle_name VARCHAR(50),
    email VARCHAR(50) NOT NULL,
    password VARCHAR(10) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    date_of_birth DATE NOT NULL);

create table Client(
	Client_ID SERIAL PRIMARY KEY,
	first_name VARCHAR(30) NOT NULL,
	last_name VARCHAR(30) NOT NULL,
	Middle_name VARCHAR(30),
	email VARCHAR(50) CHECK (email LIKE '%@%.%') NOT NULL,
	phone VARCHAR(12) NOT NULL,
	date_of_birth DATE NOT NULL,
	password VARCHAR(10) NOT NULL);

CREATE TABLE Product (
    Product_ID SERIAL PRIMARY KEY,
    Name VARCHAR(255) NOT NULL,
    Price DECIMAL(10, 2) NOT NULL,
    Total_Quantity INT NOT NULL
);

INSERT INTO product (Name, Price, Total_Quantity) VALUES
('T-Shirt', 25.00, 150),
('Jeans', 79.99, 80),
('Jacket', 120.50, 50),
('Dress', 95.75, 60),
('Sweater', 65.00, 100),
('Skirt', 45.25, 70),
('Socks', 10.00, 200),
('Hat', 20.00, 120),
('Shoes', 89.99, 90),
('Shorts', 35.00, 110);

CREATE TABLE employee_status (
    Em_Status_ID SERIAL PRIMARY KEY,
    Employee_ID INT UNSIGNED NOT NULL,
    Status_Message VARCHAR(255) NOT NULL,
    Status_Date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (Employee_ID) REFERENCES employees(Employee_ID)
);

CREATE TABLE client_status (
    Cl_Status_ID SERIAL PRIMARY KEY,
    Client_ID INT UNSIGNED NOT NULL,
    Status_Message VARCHAR(255) NOT NULL,
    Status_Date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (Client_ID) REFERENCES Client(Client_ID)
);

DELIMITER $$

CREATE TRIGGER AddEmployeeStatus
AFTER INSERT ON employee
FOR EACH ROW
BEGIN
    INSERT INTO employee_status (Employee_ID, Status_Message, Status_Date)
    VALUES (NEW.Employee_ID, 'Сотрудник успешно зарегистрирован в системе', NOW());
END$$

DELIMITER ;

DELIMITER $$

CREATE TRIGGER AddClientStatus
AFTER INSERT ON client
FOR EACH ROW
BEGIN
    INSERT INTO client_status (Client_ID, Status_Message, Status_Date)
    VALUES (NEW.Client_ID, 'Сотрудник успешно зарегистрирован в системе', NOW());
END$$

DELIMITER ;