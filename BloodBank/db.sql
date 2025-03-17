CREATE database bloodbank;

SELECT bloodbank;

-- CREATE table users(
--     id INT Auto_Increment primary key,
--     name varchar(250) not null,
--     uname varchar(15) unique not null,
--     passwd varchar(255) not null,
--     email varchar(50) unique not null,
--     btype ENUM('A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-') DEFAULT NULL,
--     utype ENUM('User','Org','Admin') NOT NULL
-- )

-- CREATE TABLE donors (
--     user_id INT auto_increment PRIMARY KEY,
--     name VARCHAR(255) NOT NULL,
--     dob DATE NOT NULL,
--     gender ENUM('Male', 'Female', 'Transgender') NOT NULL,
--     btype varchar(3) NOT NULL, 
--     age INT NOT NULL,
--     weight FLOAT NOT NULL,
--     last_donation DATE NULL,
--     recent_procedures TEXT NULL,
--     procedures TEXT NULL,
--     diseases TEXT NULL,
--     surgery TEXT NULL,
--     eligibility VARCHAR(50) NOT NULL,
--     reason TEXT NULL,
--     eligibility_date VARCHAR(50) NOT NULL,
--     submission_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     CONSTRAINT fk_donors_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
-- )

CREATE TABLE orgs(
org_id int primary key auto_increment,
  profile_image VARCHAR(255),
  org_name varchar(255) not null,
  org_email varchar(255) unique not null,
  passwd varchar(255) not null,
  licence varchar(255),
  org_phone int(10) unique not null,
  org_addr text not null,
  org_city varchar(255) not null,
  org_state varchar(255) not null,
time timestamp default current_timestamp
)

CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    profile_image VARCHAR(255) NULL,
    fname VARCHAR(100) NOT NULL,
    uname VARCHAR(100) UNIQUE NOT NULL,
    email varchar(100) UNIQUE NOT NULL,
    passwd VARCHAR(255) NOT NULL,
    dob DATE NOT NULL,
    age INT NOT NULL,
    gender ENUM('Male', 'Female', 'Other') NOT NULL,
    btype ENUM('A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-') DEFAULT NULL,
    contact VARCHAR(15) UNIQUE NOT NULL,
    address TEXT NOT NULL,
    city VARCHAR(50) NOT NULL,
    state VARCHAR(50) NOT NULL,
    occupation VARCHAR(50),
    weight INT NOT NULL,
    last_donation_date DATE,
    next_donation_date DATE,
    recent_procedures TEXT,
    recent_procedures_date DATE,
    eligibility_date DATE,
    diseases TEXT,
    surgery TEXT,
    surgery_date DATE,
    surgery_eligibility_date DATE,
    eligibility ENUM('Eligible', 'Not Eligible') NOT NULL,
    reason TEXT,
    eligible_after DATE,
    form_filled int,
    time timestamp default current_timestamp
);
