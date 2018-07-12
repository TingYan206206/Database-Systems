-- Insert Test (seed) Data
USE cs6400_fa17_team082;
-- Insert into Reservation
INSERT INTO `Reservation` (customer_username, start_date, end_date, pick_up_clerk_username, drop_off_clerk_username, card_number) VALUES ('cbratton@gmail.com', '2017-11-07', '2017-11-13', NULL, NULL, '3456472354673392');

-- Insert into ReservationDetail
INSERT INTO `ReservationDetail` (reservation_id, tool_number) VALUES (7,1);
INSERT INTO `ReservationDetail` (reservation_id, tool_number) VALUES (7,2);
INSERT INTO `ReservationDetail` (reservation_id, tool_number) VALUES (7,3);

