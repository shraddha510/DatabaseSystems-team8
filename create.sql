-- STAFF TABLE
CREATE TABLE Staff (
    Staff_ID INT PRIMARY KEY AUTO_INCREMENT,
    SName VARCHAR(100) NOT NULL,
    Salary_rate DECIMAL(10,2),
    Phone VARCHAR(15) NOT NULL UNIQUE,
    Role VARCHAR(50) NOT NULL
);

-- CUSTOMER TABLE
CREATE TABLE Customer (
    Customer_ID INT PRIMARY KEY AUTO_INCREMENT,
    PhoneNumber VARCHAR(15) NOT NULL UNIQUE,
    Name VARCHAR(100) NOT NULL,
    Email VARCHAR(100) UNIQUE
);

-- MEAL TABLE
CREATE TABLE Meal (
    Meal_ID INT PRIMARY KEY AUTO_INCREMENT,
    Name VARCHAR(100) NOT NULL,
    Description TEXT,
    Price DECIMAL(10,2) NOT NULL
);

-- DINING TABLE
CREATE TABLE DiningTable (
    Table_ID INT PRIMARY KEY AUTO_INCREMENT,
    Capacity INT NOT NULL,
    Status VARCHAR(20) NOT NULL DEFAULT 'Available'
);

-- ORDERS TABLE
CREATE TABLE Orders (
    Order_ID INT PRIMARY KEY AUTO_INCREMENT,
    OrderType ENUM('Togo', 'DineIn') NOT NULL,
    Server INT,
    OrderStatus VARCHAR(20) NOT NULL DEFAULT 'Pending',
    TotalPrice DECIMAL(10,2) NOT NULL,
    Time DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (Server) REFERENCES Staff(Staff_ID) ON DELETE SET NULL
);

-- DINEIN TABLE
CREATE TABLE DineIn (
    Order_ID INT PRIMARY KEY,
    Table_ID INT NOT NULL,
    FOREIGN KEY (Order_ID) REFERENCES Orders(Order_ID) ON DELETE CASCADE,
    FOREIGN KEY (Table_ID) REFERENCES DiningTable(Table_ID)
);

-- TOGO TABLE
CREATE TABLE ToGo (
    Order_ID INT PRIMARY KEY,
    Customer_ID INT,
    FOREIGN KEY (Order_ID) REFERENCES Orders(Order_ID) ON DELETE CASCADE,
    FOREIGN KEY (Customer_ID) REFERENCES Customer(Customer_ID) ON DELETE SET NULL
);

-- ORDERDETAIL TABLE
CREATE TABLE OrderDetail (
    Meal_ID INT,
    Order_ID INT,
    Quantity INT NOT NULL DEFAULT 1,
    PRIMARY KEY (Meal_ID, Order_ID),
    FOREIGN KEY (Meal_ID) REFERENCES Meal(Meal_ID) ON DELETE CASCADE,
    FOREIGN KEY (Order_ID) REFERENCES Orders(Order_ID) ON DELETE CASCADE
);

-- SUPPLIER TABLE
CREATE TABLE Supplier (
    Supplier_ID INT PRIMARY KEY AUTO_INCREMENT,
    Name VARCHAR(100) NOT NULL,
    Address VARCHAR(200),
    Phone VARCHAR(20)
);

-- INVENTORY TABLE
CREATE TABLE Inventory (
    Item_ID INT PRIMARY KEY AUTO_INCREMENT,
    Name VARCHAR(100) NOT NULL,
    Quantity INT NOT NULL DEFAULT 0,
    Unit VARCHAR(20) NOT NULL,
    Supplier_ID INT NOT NULL,
    Price DECIMAL(10,2) NOT NULL,
    LastUpdated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (Supplier_ID) REFERENCES Supplier(Supplier_ID)
);

-- MEALDETAIL TABLE
CREATE TABLE MealDetail (
    Meal_ID INT,
    Item_ID INT,
    ItemQuantity DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (Meal_ID, Item_ID),
    FOREIGN KEY (Meal_ID) REFERENCES Meal(Meal_ID) ON DELETE CASCADE,
    FOREIGN KEY (Item_ID) REFERENCES Inventory(Item_ID) ON DELETE CASCADE
);

-- TRANSACTION TABLE
CREATE TABLE TransactionInfo (
    TransactionNum INT NOT NULL,
    Order_ID INT NOT NULL,
    Tax DECIMAL(10,2) NOT NULL,
    Tips DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    Discount DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    AmountPaid DECIMAL(10,2) NOT NULL,
    PaymentMethod VARCHAR(50) NOT NULL,
    PaymentStatus VARCHAR(20) NOT NULL DEFAULT 'Pending',
    TransactionDate DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (TransactionNum, Order_ID),
    FOREIGN KEY (Order_ID) REFERENCES Orders(Order_ID) ON DELETE RESTRICT
);

-- WORKLOG TABLE
CREATE TABLE WorkLog (
    Staff_ID INT NOT NULL,
    LoginTime DATETIME NOT NULL,
    LogoutTime DATETIME,
    PRIMARY KEY (Staff_ID, LoginTime),
    BreakDuration TIME,
    FOREIGN KEY (Staff_ID) REFERENCES Staff(Staff_ID) ON DELETE RESTRICT
);

-- PAYCHECK TABLE
CREATE TABLE Paycheck (
    Staff_ID INT NOT NULL,
    PeriodStart DATE NOT NULL,
    PeriodEnd DATE NOT NULL,
    TotalHours DECIMAL(6,2) NOT NULL DEFAULT 0,
    Bonus DECIMAL(10,2),
    Amount DECIMAL(10,2) NOT NULL,
    PRIMARY KEY (Staff_ID, PeriodStart),
    FOREIGN KEY (Staff_ID) REFERENCES Staff(Staff_ID) ON DELETE RESTRICT
);
