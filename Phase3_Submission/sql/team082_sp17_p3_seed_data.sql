-- Insert Test (seed) Data
USE cs6400_fa17_team082;

-- Insert into User
INSERT INTO `User` (username,password,email,first_name,middle_Name,last_name) VALUES ( 'kmalone@gmail.com','kmalone123','kmalone@gmail.com','Kevin','F','Malone');
INSERT INTO `User` (username,password,email,first_name,middle_Name,last_name) VALUES ( 'amartin@gmail.com','amartin123','amartin@gmail.com','Angela','G','Martin');
INSERT INTO `User` (username,password,email,first_name,middle_Name,last_name) VALUES ( 'pvance@gmail.com','pvance123','pvance@gmail.com','Phyllis','H','Vance');
INSERT INTO `User` (username,password,email,first_name,middle_Name,last_name) VALUES ( 'mpalmer@gmail.com','mpalmer123','mpalmer@gmail.com','Meredith','I','Palmer');
INSERT INTO `User` (username,password,email,first_name,middle_Name,last_name) VALUES ( 'cbratton@gmail.com','cbratton123','cbratton@gmail.com','Creed','J','Bratton');
INSERT INTO `User` (username,password,email,first_name,middle_Name,last_name) VALUES ( 'mscott@gmail.com','mscott123','mscott@gmail.com','Mitch','H','Scott');
INSERT INTO `User` (username,password,email,first_name,middle_Name,last_name) VALUES ( 'jhalpert@gmail.com','jhalpert123','jhalpert@gmail.com','Jone','I','Halpert');
INSERT INTO `User` (username,password,email,first_name,middle_Name,last_name) VALUES ( 'dschrute@gmail.com','dschrute123','dschrute@gmail.com','Didi','J','Schrute');
INSERT INTO `User` (username,password,email,first_name,middle_Name,last_name) VALUES ( 'mingLu@gmail.com','minglu123','mingLu@gmail.com','Ming','','Lu');
INSERT INTO `User` (username,password,email,first_name,middle_Name,last_name) VALUES ( 'beric@gmail.com','beric123','beric@gmail.com','Eric','B','Berry');
INSERT INTO `User` (username,password,email,first_name,middle_Name,last_name) VALUES ( 'hmatt@gmail.com','hmatt123','hmatt@gmail.com','Matt','A','Hull');
INSERT INTO `User` (username,password,email,first_name,middle_Name,last_name) VALUES ( 'lmeng@gmail.com','lmeng123','lmeng@gmail.com','Meng','','Liu');

-- Insert into Clerk
INSERT INTO `Clerk` (username,clerk_id,hire_date,num_of_dropoffs, num_of_pickups, combined_total, is_first_time_login) VALUES ('beric@gmail.com','435435','2017-06-15',1,0,1,False);
INSERT INTO `Clerk` (username,clerk_id,hire_date,num_of_dropoffs, num_of_pickups, combined_total, is_first_time_login) VALUES ('hmatt@gmail.com','234324','2017-03-15',2,1,3,False);
INSERT INTO `Clerk` (username,clerk_id,hire_date,num_of_dropoffs, num_of_pickups, combined_total, is_first_time_login) VALUES ('lmeng@gmail.com','456456','2017-02-15',0,0,0,True);

-- Insert into Customer
INSERT INTO `Customer` (username, customer_id, state, street_address, city, zip_code, total_reservations, total_tool_rented, card_number) VALUES ( 'kmalone@gmail.com', 1, 'PA', '123 Main St','Scranton','72700', 1, 2, '8791038372906789');
INSERT INTO `Customer` (username, customer_id, state, street_address, city, zip_code, total_reservations, total_tool_rented, card_number) VALUES ( 'amartin@gmail.com', 2,'PA','456 Grant St','Wexford','19769', 1, 1, '2897898889028297');
INSERT INTO `Customer` (username, customer_id, state, street_address, city, zip_code, total_reservations, total_tool_rented, card_number) VALUES ( 'pvance@gmail.com', 3, 'PA','987 Ohio Blv','Pittsburgh','87426',1, 1, '2348709384747438');
INSERT INTO `Customer` (username, customer_id, state, street_address, city, zip_code, total_reservations, total_tool_rented, card_number) VALUES ( 'mpalmer@gmail.com',4, 'IL', '7598 W Ina Rd','Napersville','65678',2, 2,'5677893924708479');
INSERT INTO `Customer` (username, customer_id, state, street_address, city, zip_code, total_reservations, total_tool_rented, card_number) VALUES ( 'cbratton@gmail.com',5, 'OH','769 Englewood Ave','Dayton','76575',1, 1, '3456472354673392');
INSERT INTO `Customer` (username, customer_id, state, street_address, city, zip_code, total_reservations, total_tool_rented, card_number) VALUES ( 'mscott@gmail.com', 6, 'PA','286 hignland Blv','Malvern','19382',0, 0, '8273649356572434');
INSERT INTO `Customer` (username, customer_id, state, street_address, city, zip_code, total_reservations, total_tool_rented, card_number) VALUES ( 'jhalpert@gmail.com',7, 'PA', '380 W Em Rd','Philly','19567',0, 0,'2348709235523468');
INSERT INTO `Customer` (username, customer_id, state, street_address, city, zip_code, total_reservations, total_tool_rented, card_number) VALUES ( 'dschrute@gmail.com',8, 'PA','23 Park Ave','Admore','19837',0, 0, '5677897925678998');
INSERT INTO `Customer` (username, customer_id, state, street_address, city, zip_code, total_reservations, total_tool_rented, card_number) VALUES ( 'mingLu@gmail.com',9, 'PA','2 Melli Ave','Downingtown','19847',0, 0, '3456478924354762');

-- Insert into Phone
INSERT INTO `Phone` (username, area_code, exchange, number, extension, phone_type, is_primary) VALUES ('kmalone@gmail.com','278','643', '3446','2773','Home Phone',TRUE);
INSERT INTO `Phone` (username, area_code, exchange, number, extension, phone_type, is_primary) VALUES ('amartin@gmail.com','311','276', '8943','5689','Work Phone',TRUE);
INSERT INTO `Phone` (username, area_code, exchange, number, extension, phone_type, is_primary) VALUES ('pvance@gmail.com','423','534', '2564','5686','Cell Phone',TRUE);
INSERT INTO `Phone` (username, area_code, exchange, number, extension, phone_type, is_primary) VALUES ('mpalmer@gmail.com','667','676', '8953',NULL,'Home Phone',TRUE);
INSERT INTO `Phone` (username, area_code, exchange, number, extension, phone_type, is_primary) VALUES ('cbratton@gmail.com','345','323', '6785',NULL,'Work Phone',TRUE);
INSERT INTO `Phone` (username, area_code, exchange, number, extension, phone_type, is_primary) VALUES ('mscott@gmail.com','215','019', '8940',NULL,'Work Phone',TRUE);
INSERT INTO `Phone` (username, area_code, exchange, number, extension, phone_type, is_primary) VALUES ('jhalpert@gmail.com','215','510', '8569','8729','Cell Phone',TRUE);
INSERT INTO `Phone` (username, area_code, exchange, number, extension, phone_type, is_primary) VALUES ('dschrute@gmail.com','215','916', '8978',NULL,'Work Phone',TRUE);
INSERT INTO `Phone` (username, area_code, exchange, number, extension, phone_type, is_primary) VALUES ('mingLu@gmail.com','215','089', '6701',NULL,'Work Phone',TRUE);

-- Insert into CreditCard
INSERT INTO `CreditCard` (card_number, name, cvc, exp_year, exp_month) VALUES  ('8791038372906789', 'kmalone@gmail.com', 324, 2019, 03);
INSERT INTO `CreditCard` (card_number, name, cvc, exp_year, exp_month) VALUES  ('2897898889028297', 'amartin@gmail.com', 567, 2020, 06);
INSERT INTO `CreditCard` (card_number, name, cvc, exp_year, exp_month) VALUES  ('2348709384747438', 'pvance@gmail.com', 321, 2018,11);
INSERT INTO `CreditCard` (card_number, name, cvc, exp_year, exp_month) VALUES  ('5677893924708479', 'mpalmer@gmail.com',778, 2020,12);
INSERT INTO `CreditCard` (card_number, name, cvc, exp_year, exp_month) VALUES  ('3456472354673392', 'cbratton@gmail.com', 432, 2019,10);
INSERT INTO `CreditCard` (card_number, name, cvc, exp_year, exp_month) VALUES  ('8273649356572434', 'mscott@gmail.com', 298, 2021, 02);
INSERT INTO `CreditCard` (card_number, name, cvc, exp_year, exp_month) VALUES  ('2348709235523468', 'jhalpert@gmail.com', 301, 2018,12);
INSERT INTO `CreditCard` (card_number, name, cvc, exp_year, exp_month) VALUES  ('5677897925678998', 'dschrute@gmail.com',789, 2021,11);
INSERT INTO `CreditCard` (card_number, name, cvc, exp_year, exp_month) VALUES  ('3456478924354762', 'mingLu@gmail.com', 428, 2018,09);

-- Insert into Tool
INSERT INTO `Tool` (tool_number, type, sub_type, sub_option, manufacturer, power_source, material, current_status, width_diameter,length,weight,purchase_price, number_of_rent_time) VALUES (1,'Hand Tool','Screwdriver','phillips(cross)','Makita','Manual','metal', 'for rent','3-1/2','5-1/2', '0.5', 100, 1);
INSERT INTO `Tool` (tool_number, type, sub_type, sub_option, manufacturer, power_source, material, current_status, width_diameter,length,weight,purchase_price, number_of_rent_time) VALUES (2,'Hand Tool','Socket','deep','Dewalt','Manual','metal', 'for rent','2-1/32','9-1/16', '0.4', 200, 0);
INSERT INTO `Tool` (tool_number, type, sub_type, sub_option, manufacturer, power_source, material, current_status, width_diameter,length,weight,purchase_price, number_of_rent_time) VALUES (3,'Hand Tool','Ratchet','fixed','Makita','Manual','metal', 'Rented','2-3/8','9-1/2','0.3', 50, 1);
INSERT INTO `Tool` (tool_number, type, sub_type, sub_option, manufacturer, power_source, material, current_status, width_diameter,length,weight,purchase_price, number_of_rent_time) VALUES (4,'Hand Tool','Plier','cutting','Dewalt','Manual','metal', 'for rent','2-1/2','5-1/2', '0.7',30, 0);
INSERT INTO `Tool` (tool_number, type, sub_type, sub_option, manufacturer, power_source, material, current_status, width_diameter,length,weight,purchase_price, number_of_rent_time) VALUES (5,'Hand Tool','Gun','nail','Makita','Manual','metal', 'for rent','2','9', '1.2',60, 0);
INSERT INTO `Tool` (tool_number, type, sub_type, sub_option, manufacturer, power_source, material, current_status, width_diameter,length,weight,purchase_price, number_of_rent_time) VALUES (6,'Hand Tool','Hammer','claw','Makita','Manual','metal', 'for rent','2-7/8','8','1.0', 80, 0);
INSERT INTO `Tool` (tool_number, type, sub_type, sub_option, manufacturer, power_source, material, current_status, width_diameter,length,weight,purchase_price, number_of_rent_time) VALUES (7,'Garden Tool','Digger','pointed shovel','Makita','Manual','metal', 'for rent','3-1/8','11-1/2', '3.0',200, 1);
INSERT INTO `Tool` (tool_number, type, sub_type, sub_option, manufacturer, power_source, material, current_status, width_diameter,length,weight,purchase_price, number_of_rent_time) VALUES (8,'Garden Tool','Pruner','sheer','Dewalt','Manual','metal', 'for rent','5-3/8','6-3/32', '3.5', 100, 1);
INSERT INTO `Tool` (tool_number, type, sub_type, sub_option, manufacturer, power_source, material, current_status, width_diameter,length,weight,purchase_price, number_of_rent_time) VALUES (9,'Garden Tool','Rakes','leaf','Dewalt','Manual','metal', 'for rent','5-1/4','10-1/2', '7.8',150, 0);
INSERT INTO `Tool` (tool_number, type, sub_type, sub_option, manufacturer, power_source, material, current_status, width_diameter,length,weight,purchase_price, number_of_rent_time) VALUES (10,'Garden Tool','Wheelbarrows','wheel', 'Makita','Manual','metal', 'for rent','30','50','20', 1000, 0);
INSERT INTO `Tool` (tool_number, type, sub_type, sub_option, manufacturer, power_source, material, current_status, width_diameter,length,weight,purchase_price, number_of_rent_time) VALUES (11,'Garden Tool','Striking','tamper','Makita','Manual','metal', 'for rent','3-1/4','9-1/2', '4.7', 60, 0);
INSERT INTO `Tool` (tool_number, type, sub_type, sub_option, manufacturer, power_source, material, current_status, width_diameter,length,weight,purchase_price, number_of_rent_time) VALUES (12,'Ladder','Straight','rigid','Makita','Manual','metal', 'for rent','20','120', '5.6', 300, 0);
INSERT INTO `Tool` (tool_number, type, sub_type, sub_option, manufacturer, power_source, material, current_status, width_diameter,length,weight,purchase_price, number_of_rent_time) VALUES (13,'Ladder','Step','folding','Makita','Manual','metal', 'for rent','28','200','48', 200, 0);
INSERT INTO `Tool` (tool_number, type, sub_type, sub_option, manufacturer, power_source, material, current_status, width_diameter,length,weight,purchase_price, number_of_rent_time) VALUES (14,'Ladder','Step','folding','Dewalt','Manual','metal', 'for rent','18','159','35', 260, 0);
INSERT INTO `Tool` (tool_number, type, sub_type, sub_option, manufacturer, power_source, material, current_status, width_diameter,length,weight,purchase_price, number_of_rent_time) VALUES (15,'Ladder','Step','folding','Makita','Manual','metal', 'Rented','26','139', '50', 300, 0);
INSERT INTO `Tool` (tool_number, type, sub_type, sub_option, manufacturer, power_source, material, current_status, width_diameter,length,weight,purchase_price, number_of_rent_time) VALUES (16,'Power Tool','Drill','driver','Dewalt','Electric','metal', 'Rented','4-1/4','15-3/8','5.4', 300, 0);
INSERT INTO `Tool` (tool_number, type, sub_type, sub_option, manufacturer, power_source, material, current_status, width_diameter,length,weight,purchase_price, number_of_rent_time) VALUES (17,'Power Tool','Saw','jig','Makita','Electric','metal', 'Rented','15','30', '2.1', 300, 0);
INSERT INTO `Tool` (tool_number, type, sub_type, sub_option, manufacturer, power_source, material, current_status, width_diameter,length,weight,purchase_price, number_of_rent_time) VALUES (18,'Power Tool','Mixer','concrete','Dewalt','Cordless','metal', 'for rent','20','39', '19.2', 400, 0);
INSERT INTO `Tool` (tool_number, type, sub_type, sub_option, manufacturer, power_source, material, current_status, width_diameter,length,weight,purchase_price, number_of_rent_time) VALUES (19,'Power Tool','Sander','belt','Dewalt','Cordless','metal', 'for rent','5-1/2','13-1/2', '15.9', 100, 0);
INSERT INTO `Tool` (tool_number, type, sub_type, sub_option, manufacturer, power_source, material, current_status, width_diameter,length,weight,purchase_price, number_of_rent_time) VALUES (20,'Power Tool','Sander','sheet','Dewalt','Cordless','metal', 'for rent','5','10', '10.9',10, 0);

-- Insert into sub-type tool Tables (handtool, garden tool, ladder and power tool)
INSERT INTO `HandTool` (tool_number) VALUES (1);
INSERT INTO `HandTool` (tool_number) VALUES (2);
INSERT INTO `HandTool` (tool_number) VALUES (3);
INSERT INTO `HandTool` (tool_number) VALUES (4);
INSERT INTO `HandTool` (tool_number) VALUES (5);
INSERT INTO `HandTool` (tool_number) VALUES (6);
INSERT INTO `GardenTool` (tool_number,handle_material) VALUES (7,'metal');
INSERT INTO `GardenTool` (tool_number,handle_material) VALUES (8,'wood');
INSERT INTO `GardenTool` (tool_number,handle_material) VALUES (9,'wood');
INSERT INTO `GardenTool` (tool_number,handle_material) VALUES (10,'wood');
INSERT INTO `GardenTool` (tool_number,handle_material) VALUES (11,'wood');
INSERT INTO `Ladder` (tool_number,step_count,weight_capacity) VALUES (12,10,200);
INSERT INTO `Ladder` (tool_number,step_count,weight_capacity) VALUES (13,20,200);
INSERT INTO `Ladder` (tool_number,step_count,weight_capacity) VALUES (14,16,200);
INSERT INTO `Ladder` (tool_number,step_count,weight_capacity) VALUES (15,12,200);
INSERT INTO `PowerTool` (tool_number,volt_rating,amp_rating,min_rpm_rating,max_rpm_rating) VALUES (16,100,200,120,220);
INSERT INTO `PowerTool` (tool_number,volt_rating,amp_rating,min_rpm_rating,max_rpm_rating) VALUES (17,200,230,150,230);
INSERT INTO `PowerTool` (tool_number,volt_rating,amp_rating,min_rpm_rating,max_rpm_rating) VALUES (18,120,300,190,290);
INSERT INTO `PowerTool` (tool_number,volt_rating,amp_rating,min_rpm_rating,max_rpm_rating) VALUES (19,100,230,110,200);
INSERT INTO `PowerTool` (tool_number,volt_rating,amp_rating,min_rpm_rating,max_rpm_rating) VALUES (20,120,230,100,220);

-- Insert into tool Tables
INSERT INTO `Screwdriver` (tool_number, screw_size) VALUES (1, '1/8');
INSERT INTO `Socket` (tool_number, drive_size, sae_size) VALUES (2, '1/8','1');
INSERT INTO `Ratchet` (tool_number, drive_size) VALUES (3, '3/64');
INSERT INTO `Plier` (tool_number, adjustable) VALUES (4, FALSE);
INSERT INTO `Gun` (tool_number, capacity, gauge_rating) VALUES (5, 20,10);
INSERT INTO `Hammer` (tool_number, anti_viberation) VALUES (6, FALSE);
INSERT INTO `Digger` (tool_number, blade_length, blade_width) VALUES (7, '9-3/4', '6-1/2');
INSERT INTO `Pruner` (tool_number, blade_length, blade_material) VALUES (8, '9-3/4', 'metal');
INSERT INTO `Rakes` (tool_number, tine_count) VALUES (9, 20);
INSERT INTO `Wheelbarrows` (tool_number, bin_material, wheel_count, bin_volume) VALUES (10, 'metal', 2,2);
INSERT INTO `Striking` (tool_number, head_weight) VALUES (11, 30);
INSERT INTO `Straight` (tool_number, rubber_feet) VALUES (12, FALSE);
INSERT INTO `Step` (tool_number, pail_shelf) VALUES (13, FALSE);
INSERT INTO `Step` (tool_number, pail_shelf) VALUES (14, TRUE);
INSERT INTO `Step` (tool_number, pail_shelf) VALUES (15, TRUE);
INSERT INTO `Drill` (tool_number, adjustable_clutch, min_torque_rating, max_torque_rating) VALUES (16, FALSE, 3500, 7500);
INSERT INTO `Saw` (tool_number, blade_size) VALUES (17, '9-3/4');
INSERT INTO `Mixer` (tool_number, motor_rating, drum_size) VALUES (18, '9', 5);
INSERT INTO `Sander` (tool_number, dust_bag) VALUES (19, TRUE);
INSERT INTO `Sander` (tool_number, dust_bag) VALUES (20, FALSE);

-- Insert into accessory Tables
INSERT INTO `Accessory` (accessory_description, tool_number, quantity) VALUES ('Drill Bits', 16, 1);
INSERT INTO `Accessory` (accessory_description, tool_number, quantity) VALUES ('Saw Blade', 17, 2);
INSERT INTO `Accessory` (accessory_description, tool_number, quantity) VALUES ('Carrying cases', 18, 2);
-- Insert into cordless power tool accessaries
INSERT INTO `CordlessPowerTool` (tool_number, battery_type, battery_quantity, dc_volt_rating) VALUES (18, 'Li-Ion', 2, 30);
INSERT INTO `CordlessPowerTool` (tool_number, battery_type, battery_quantity, dc_volt_rating) VALUES (19, 'Li-Ion', 1, 15);
INSERT INTO `CordlessPowerTool` (tool_number, battery_type, battery_quantity, dc_volt_rating) VALUES (20, 'Li-Ion', 3, 10);
-- Insert into Reservation
INSERT INTO `Reservation` (customer_username, start_date, end_date, pick_up_clerk_username, drop_off_clerk_username, card_number) VALUES ('kmalone@gmail.com', CONCAT(NOW()-INTERVAL 40 DAY), CONCAT(NOW()-INTERVAL 36 DAY), 'hmatt@gmail.com', 'hmatt@gmail.com', '8791038372906789');
INSERT INTO `Reservation` (customer_username, start_date, end_date, pick_up_clerk_username, drop_off_clerk_username, card_number) VALUES ('amartin@gmail.com', CONCAT(NOW()-INTERVAL 7 DAY), CONCAT(NOW()+INTERVAL 10 DAY), 'hmatt@gmail.com', NULL, '2897898889028297');
INSERT INTO `Reservation` (customer_username, start_date, end_date, pick_up_clerk_username, drop_off_clerk_username, card_number) VALUES ('pvance@gmail.com', CONCAT(NOW()-INTERVAL 5 DAY), CONCAT(NOW()), 'beric@gmail.com', NULL, '2348709384747438');
INSERT INTO `Reservation` (customer_username, start_date, end_date, pick_up_clerk_username, drop_off_clerk_username, card_number) VALUES ('mpalmer@gmail.com', CONCAT(NOW()), CONCAT(NOW()+INTERVAL 8 DAY), NULL, NULL, '5677893924708479');
INSERT INTO `Reservation` (customer_username, start_date, end_date, pick_up_clerk_username, drop_off_clerk_username, card_number) VALUES ('mpalmer@gmail.com', CONCAT(NOW()+INTERVAL 1 DAY), CONCAT(NOW()+INTERVAL 5 DAY), NULL, NULL, '5677893924708479');
INSERT INTO `Reservation` (customer_username, start_date, end_date, pick_up_clerk_username, drop_off_clerk_username, card_number) VALUES ('cbratton@gmail.com', CONCAT(NOW()+INTERVAL 3 DAY), CONCAT(NOW()+INTERVAL 7 DAY), NULL, NULL, '3456472354673392');

-- Insert into ReservationDetail
INSERT INTO `ReservationDetail` (reservation_id, tool_number) VALUES (1,1);
INSERT INTO `ReservationDetail` (reservation_id, tool_number) VALUES (1,7);
INSERT INTO `ReservationDetail` (reservation_id, tool_number) VALUES (2,3);
INSERT INTO `ReservationDetail` (reservation_id, tool_number) VALUES (3,8);
INSERT INTO `ReservationDetail` (reservation_id, tool_number) VALUES (4,15);
INSERT INTO `ReservationDetail` (reservation_id, tool_number) VALUES (5,16);
INSERT INTO `ReservationDetail` (reservation_id, tool_number) VALUES (6,17);
