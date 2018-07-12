-- CREATE USER 'newuser'@'localhost' IDENTIFIED BY 'password';
-- CREATE USER IF NOT EXISTS gatechuser@localhost IDENTIFIED BY 'gatech123';

DROP DATABASE IF EXISTS `cs6400_fa17_team082`;
SET default_storage_engine=InnoDB;
SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci;

CREATE DATABASE IF NOT EXISTS cs6400_fa17_team082
    DEFAULT CHARACTER SET utf8mb4
    DEFAULT COLLATE utf8mb4_unicode_ci;
USE cs6400_fa17_team082;

GRANT SELECT, INSERT, UPDATE, DELETE, FILE ON *.* TO 'gatechUser'@'localhost';
GRANT ALL PRIVILEGES ON `gatechuser`.* TO 'gatechUser'@'localhost';
GRANT ALL PRIVILEGES ON `cs6400_fa17_team082`.* TO 'gatechUser'@'localhost';
FLUSH PRIVILEGES;

-- Tables

CREATE TABLE User(
	username VARCHAR(50) NOT NULL,
	password VARCHAR(50) NOT NULL,
	email VARCHAR(250) NOT NULL,
	first_name VARCHAR(100) NOT NULL,
	middle_name VARCHAR(100) NOT NULL,
	last_name VARCHAR(50) NOT NULL,
	PRIMARY KEY (username),
	UNIQUE KEY username (username)
);
CREATE TABLE Customer(
	username VARCHAR(50) NOT NULL,
	customer_id INT NOT NULL AUTO_INCREMENT,
	state VARCHAR(50) NULL,
	street_address VARCHAR(50) NULL,
	city VARCHAR(50) NULL,
	zip_code INT NULL,
	total_reservations INT NULL,
	total_tool_rented INT NULL,
	card_number VARCHAR(50) NOT NULL,
	UNIQUE KEY username (username),
	KEY customer_id (customer_id),
	KEY card_number (card_number),
	PRIMARY KEY (username),
	FOREIGN KEY (username)
		REFERENCES User(username)
);
CREATE TABLE CreditCard(
	card_number VARCHAR(50) NOT NULL,
	name VARCHAR(50) NOT NULL,
	cvc INT NOT NULL,
	exp_year INT NOT NULL,
	exp_month INT NOT NULL,
	PRIMARY KEY (card_number),
	FOREIGN KEY (card_number)
		REFERENCES Customer(card_number) ON DELETE CASCADE ON UPDATE CASCADE
);
CREATE TABLE Phone(
	username VARCHAR(50) NOT NULL,
	area_code INT NOT NULL,
	exchange INT NOT NULL,
	number INT NOT NULL,
	extension INT NULL,
    phone_type VARCHAR(50) NOT NULL,
	is_primary BOOLEAN NOT NULL,
	PRIMARY KEY (username,phone_type),
  	FOREIGN KEY (username)
    	REFERENCES Customer(username)
);
CREATE TABLE Clerk(
	username VARCHAR(50) NOT NULL,
	clerk_id INT NOT NULL AUTO_INCREMENT,
	hire_date DATE NOT NULL,
	num_of_dropoffs INT NULL,
	num_of_pickups INT NULL,
	combined_total INT NULL,
	is_first_time_login BOOLEAN NOT NULL,
	UNIQUE KEY username (username),
	KEY clerk_id (clerk_id),
	PRIMARY KEY (username),
	FOREIGN KEY (username)
		REFERENCES User(username)
);
CREATE TABLE Reservation(
	reservation_id INT NOT NULL AUTO_INCREMENT,
	customer_username VARCHAR(50) NOT NULL,
	start_date DATE NOT NULL,
	end_date DATE NOT NULL,
	pick_up_clerk_username VARCHAR(50) NULL,
	drop_off_clerk_username VARCHAR(50) NULL,
	card_number VARCHAR(50) NOT NULL,
	UNIQUE KEY reservation_id (reservation_id),
	PRIMARY KEY (reservation_id),
	FOREIGN KEY (customer_username)
		REFERENCES Customer(username),
	FOREIGN KEY (pick_up_clerk_username)
		REFERENCES Clerk(username),
	FOREIGN KEY (drop_off_clerk_username)
		REFERENCES Clerk(username),
	FOREIGN KEY (card_number)
		REFERENCES CreditCard(card_number) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE Tool(
	tool_number INT NOT NULL AUTO_INCREMENT, 
	type VARCHAR(50) NOT NULL,
	sub_type VARCHAR(50) NOT NULL,
	sub_option VARCHAR(50) NOT NULL,
	manufacturer VARCHAR(50) NOT NULL,
    power_source VARCHAR(50) NOT NULL,
	material VARCHAR(50) NULL,
	current_status VARCHAR(50) NOT NULL,
	width_diameter VARCHAR(50) NOT NULL,
	length VARCHAR(50) NOT NULL,
	weight VARCHAR(50) NOT NULL,
	purchase_price FLOAT NOT NULL,
	number_of_rent_time INT NULL,
	UNIQUE KEY tool_number (tool_number),
	PRIMARY KEY (tool_number)
);
CREATE TABLE ReservationDetail(
	reservation_id INT NOT NULL,
	tool_number INT NOT NULL,
	num_rented INT NULL,
	PRIMARY KEY (reservation_id,tool_number),
	FOREIGN KEY (reservation_id)
		REFERENCES Reservation(reservation_id),
	FOREIGN KEY (tool_number)
		REFERENCES Tool(tool_number)
);

CREATE TABLE SaleStatus(
	sale_id INT NOT NULL AUTO_INCREMENT,
	tool_number INT NOT NULL,
	clerk_username VARCHAR(50) NOT NULL,
	customer_username VARCHAR(50) NOT NULL,
	for_sale_date DATE NULL,
	sale_date DATE NULL,
	UNIQUE KEY sale_id (sale_id),
	PRIMARY KEY (sale_id),
	FOREIGN KEY (tool_number)
		REFERENCES Tool(tool_number),
	FOREIGN KEY (clerk_username)
		REFERENCES Clerk(username),
	FOREIGN KEY (customer_username)
		REFERENCES Customer(username)
);
CREATE TABLE ServiceStatus(
	service_id INT NOT NULL AUTO_INCREMENT,
	tool_number INT NOT NULL,
	clerk_username VARCHAR(50) NOT NULL,
    description VARCHAR(250) NOT NULL,
	start_date DATE NOT NULL,
	end_date DATE NULL,
	repair_cost FLOAT NOT NULL,
	UNIQUE KEY service_id (service_id),
	PRIMARY KEY (service_id),
	FOREIGN KEY (tool_number)
		REFERENCES Tool(tool_number),
	FOREIGN KEY (clerk_username)
		REFERENCES Clerk(username)
);

-- GardenTool

CREATE TABLE GardenTool(
	tool_number INT NOT NULL,
	handle_material VARCHAR(50) NOT NULL,
	UNIQUE KEY tool_number (tool_number),
	PRIMARY KEY (tool_number),
	FOREIGN KEY (tool_number)
		REFERENCES Tool(tool_number)
);
CREATE TABLE Pruner(
	tool_number INT NOT NULL,
	blade_length VARCHAR(50) NOT NULL,
	blade_material VARCHAR(50) NULL,
	UNIQUE KEY tool_number (tool_number),
	PRIMARY KEY (tool_number),
	FOREIGN KEY (tool_number)
		REFERENCES GardenTool(tool_number)
);
CREATE TABLE Striking(
	tool_number INT NOT NULL,
	head_weight FLOAT NOT NULL,
	UNIQUE KEY tool_number (tool_number),
	PRIMARY KEY (tool_number),
	FOREIGN KEY (tool_number)
		REFERENCES GardenTool(tool_number)
);
CREATE TABLE Digger(
	tool_number INT NOT NULL,
	blade_length VARCHAR(50) NOT NULL,
	blade_width VARCHAR(50) NULL,
	UNIQUE KEY tool_number (tool_number),
	PRIMARY KEY (tool_number),
	FOREIGN KEY (tool_number)
		REFERENCES GardenTool(tool_number)
);
CREATE TABLE Rakes(
	tool_number INT NOT NULL,
	tine_count INT NOT NULL,
	UNIQUE KEY tool_number (tool_number),
	PRIMARY KEY (tool_number),
	FOREIGN KEY (tool_number)
		REFERENCES GardenTool(tool_number)
);
CREATE TABLE Wheelbarrows(
	tool_number INT NOT NULL,
	bin_material VARCHAR(50) NOT NULL,
	wheel_count INT NOT NULL,
	bin_volume FLOAT NOT NULL,
	UNIQUE KEY tool_number (tool_number),
	PRIMARY KEY (tool_number),
	FOREIGN KEY (tool_number)
		REFERENCES GardenTool(tool_number)
);

-- LadderTool

CREATE TABLE Ladder(
	tool_number INT NOT NULL,
	step_count INT NOT NULL,
	weight_capacity INT NULL,
	UNIQUE KEY tool_number (tool_number),
	PRIMARY KEY (tool_number),
	FOREIGN KEY (tool_number)
		REFERENCES Tool(tool_number)
);
CREATE TABLE Straight(
	tool_number INT NOT NULL,
	rubber_feet BOOLEAN NULL,
	UNIQUE KEY tool_number (tool_number),
	PRIMARY KEY (tool_number),
	FOREIGN KEY (tool_number)
		REFERENCES Ladder(tool_number)
);
CREATE TABLE Step(
	tool_number INT NOT NULL,
	pail_shelf BOOLEAN NULL,
	UNIQUE KEY tool_number (tool_number),
	PRIMARY KEY (tool_number),
	FOREIGN KEY (tool_number)
		REFERENCES Ladder(tool_number)
);

-- HandTool

CREATE TABLE HandTool(
	tool_number INT NOT NULL,
	UNIQUE KEY tool_number (tool_number),
	PRIMARY KEY (tool_number),
	FOREIGN KEY (tool_number)
		REFERENCES Tool(tool_number)
);
CREATE TABLE Screwdriver(
	tool_number INT NOT NULL,
	screw_size VARCHAR(50) NOT NULL,
	UNIQUE KEY tool_number (tool_number),
	PRIMARY KEY (tool_number),
	FOREIGN KEY (tool_number)
		REFERENCES HandTool(tool_number)
);
CREATE TABLE Socket(
	tool_number INT NOT NULL,
	drive_size VARCHAR(50) NOT NULL,
	sae_size VARCHAR(50) NOT NULL,
	UNIQUE KEY tool_number (tool_number),
	PRIMARY KEY (tool_number),
	FOREIGN KEY (tool_number)
		REFERENCES HandTool(tool_number)
);
CREATE TABLE Ratchet(
	tool_number INT NOT NULL,
	drive_size VARCHAR(50) NOT NULL,
	UNIQUE KEY tool_number (tool_number),
	PRIMARY KEY (tool_number),
	FOREIGN KEY (tool_number)
		REFERENCES HandTool(tool_number)
);

CREATE TABLE Wrench(
	tool_number INT NOT NULL,
	drive_size VARCHAR(50) NOT NULL,
	UNIQUE KEY tool_number (tool_number),
	PRIMARY KEY (tool_number),
	FOREIGN KEY (tool_number)
		REFERENCES HandTool(tool_number)
);

CREATE TABLE Plier(
	tool_number INT NOT NULL,
	adjustable BOOLEAN NOT NULL,
	UNIQUE KEY tool_number (tool_number),
	PRIMARY KEY (tool_number),
	FOREIGN KEY (tool_number)
		REFERENCES HandTool(tool_number)
);
CREATE TABLE Gun(
	tool_number INT NOT NULL,
	capacity INT NOT NULL,
	gauge_rating INT NULL,
	UNIQUE KEY tool_number (tool_number),
	PRIMARY KEY (tool_number),
	FOREIGN KEY (tool_number)
		REFERENCES HandTool(tool_number)
);
CREATE TABLE Hammer(
	tool_number INT NOT NULL,
	anti_viberation BOOLEAN NOT NULL,
	UNIQUE KEY tool_number (tool_number),
	PRIMARY KEY (tool_number),
	FOREIGN KEY (tool_number)
		REFERENCES HandTool(tool_number)
);

-- PowerTool

CREATE TABLE PowerTool(
	tool_number INT NOT NULL,
	volt_rating INT NOT NULL,
	amp_rating FLOAT NOT NULL,
	min_rpm_rating INT NOT NULL,
	max_rpm_rating INT NULL,
	UNIQUE KEY tool_number (tool_number),
	PRIMARY KEY (tool_number),
	FOREIGN KEY (tool_number)
		REFERENCES Tool(tool_number)
);
CREATE TABLE Drill(
	tool_number INT NOT NULL,
	adjustable_clutch BOOLEAN NOT NULL,
	min_torque_rating INT NOT NULL,
	max_torque_rating INT NULL,
	UNIQUE KEY tool_number (tool_number),
	PRIMARY KEY (tool_number),
	FOREIGN KEY (tool_number)
		REFERENCES PowerTool(tool_number)
);
CREATE TABLE Saw(
	tool_number INT NOT NULL,
	blade_size VARCHAR(50) NOT NULL,
	UNIQUE KEY tool_number (tool_number),
	PRIMARY KEY (tool_number),
	FOREIGN KEY (tool_number)
		REFERENCES PowerTool(tool_number)
);
CREATE TABLE Sander(
	tool_number INT NOT NULL,
	dust_bag BOOLEAN NOT NULL,
	UNIQUE KEY tool_number (tool_number),
	PRIMARY KEY (tool_number),
	FOREIGN KEY (tool_number)
		REFERENCES PowerTool(tool_number)
);
CREATE TABLE AirCompressor(
	tool_number INT NOT NULL,
	tank_size INT NOT NULL,
	pressure_rating INT NULL,
	UNIQUE KEY tool_number (tool_number),
	PRIMARY KEY (tool_number),
	FOREIGN KEY (tool_number)
		REFERENCES PowerTool(tool_number)
);
CREATE TABLE Mixer(
	tool_number INT NOT NULL,
	motor_rating VARCHAR(50) NOT NULL,
	drum_size INT NOT NULL,
	UNIQUE KEY tool_number (tool_number),
	PRIMARY KEY (tool_number),
	FOREIGN KEY (tool_number)
		REFERENCES PowerTool(tool_number)
);
CREATE TABLE Generator(
	tool_number INT NOT NULL,
	power_rating INT NOT NULL,
	UNIQUE KEY tool_number (tool_number),
	PRIMARY KEY (tool_number),
	FOREIGN KEY (tool_number)
		REFERENCES PowerTool(tool_number)
);
CREATE TABLE CordlessPowerTool(
  	tool_number INT NOT NULL,
  	battery_type VARCHAR(50) NOT NULL,
  	battery_quantity INT NOT NULL,
  	dc_volt_rating INT NOT NULL,
  	PRIMARY KEY (tool_number,battery_type),
  	FOREIGN KEY (tool_number)
    	REFERENCES PowerTool(tool_number)
);
CREATE TABLE Accessory(
	accessory_description VARCHAR(50) NOT NULL,
	tool_number INT NOT NULL,
    quantity INT NOT NULL,
	PRIMARY KEY (accessory_description,tool_number),
	FOREIGN KEY (tool_number)
		REFERENCES PowerTool(tool_number)
);

# ALTER TABLE Customer
#   ADD CONSTRAINT fk_Customer_customer_username_User_username FOREIGN KEY (username) REFERENCES User (username);
#
# ALTER TABLE CreditCard
#   ADD CONSTRAINT fk_CreditCard_card_number_Customer_card_number FOREIGN KEY (card_number) REFERENCES Customer (card_number);
#
# ALTER TABLE Clerk
#   ADD CONSTRAINT fk_Clerk_username_User_username FOREIGN KEY (username) REFERENCES User (username);
#
# ALTER TABLE Reservation
#   ADD CONSTRAINT fk_Reservation_customer_username_Customer_customer_username FOREIGN KEY (username) REFERENCES Customer (username);
#   ADD CONSTRAINT fk_Reservation_pickupclerk_Clerk_clerkusername FOREIGN KEY (pick_up_clerk) REFERENCES Clerk (username);
#   ADD CONSTRAINT fk_Reservation_dropoffclerk_Clerk_clerkusername FOREIGN KEY (drop_off_clerk) REFERENCES Clerk (username);
#   ADD CONSTRAINT fk_Reservation_cardnum_CreditCard_cardnum FOREIGN KEY (card_number) REFERENCES CreditCard (card_number);
#
# ALTER TABLE ReservationDetail
#   ADD CONSTRAINT fk_ReservationDetail_reservation_id_Reservation_reservation_id FOREIGN KEY (reservation_id) REFERENCES Reservation (reservation_id);
#   ADD CONSTRAINT fk_ReservationDetail_toolnumber_Tool_toolnumber FOREIGN KEY (tool_number) REFERENCES Tool (tool_number);
#
# ALTER TABLE SaleStatus
#   ADD CONSTRAINT fk_SaleStatus_customer_username_Customer_customer_username FOREIGN KEY (username) REFERENCES Customer (username);
#   ADD CONSTRAINT fk_SaleStatus_clerkusername_Clerk_clerkusername FOREIGN KEY (username) REFERENCES Clerk (username);
#
# ALTER TABLE ServiceStatus
#   ADD CONSTRAINT fk_ServiceStatus_clerk_username_Clerk_clerk_username FOREIGN KEY (username) REFERENCES Clerk (username);
#
#
# ALTER TABLE GardenTool
#   ADD CONSTRAINT fk_GardenTool_tool_number_Tool_tool_number FOREIGN KEY (tool_number) REFERENCES Tool (tool_number);
#
# ALTER TABLE Pruning
#   ADD CONSTRAINT fk_Pruning_tool_number_GardenTool_tool_number FOREIGN KEY (tool_number) REFERENCES GardenTool (tool_number);
#
# ALTER TABLE Striking
#   ADD CONSTRAINT fk_Striking_tool_number_GardenTool_tool_number FOREIGN KEY (tool_number) REFERENCES GardenTool (tool_number);
#
# ALTER TABLE Digging
#   ADD CONSTRAINT fk_Digging_tool_number_GardenTool_tool_number FOREIGN KEY (tool_number) REFERENCES GardenTool (tool_number);
#
# ALTER TABLE Rake
#   ADD CONSTRAINT fk_Rake_tool_number_GardenTool_tool_number FOREIGN KEY (tool_number) REFERENCES GardenTool (tool_number);
#
# ALTER TABLE Wheelbarrow
#   ADD CONSTRAINT fk_Wheelbarrow_tool_number_GardenTool_tool_number FOREIGN KEY (tool_number) REFERENCES GardenTool (tool_number);
#
#

