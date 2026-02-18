# BEGIN
#     DECLARE new_code_package VARCHAR(13);
# DECLARE last_code_package varchar(13);
# SELECT COUNT(*) INTO last_code_package FROM `package`;
# if NEW.`fragile` = 1 THEN
# set NEW.`prix` = NEW.`prix` +12;
# END IF;
# IF NEW.`cache_en_delivery` = 1 THEN
# set NEW.`prix` = NEW.`prix` +15;
# END IF;
# IF NEW.type = "colis" THEN
#     IF NEW.code_package = 0 THEN
# SET new_code_package = CONCAT("CL", LPAD(last_code_package, 11-length(last_code_package), '0'), "MA");
#
# else
# set new_code_package = CONCAT("CL", LPAD(NEW.code_package, 11, '0'), "MA");
# END IF;
# ELSEIF NEW.type = "courier" THEN
#     IF NEW.code_package = 0 THEN
# SET new_code_package = CONCAT("CR", LPAD(last_code_package, 11-length(last_code_package), '0'), "MA");
# else
# set new_code_package = CONCAT("CL", LPAD(NEW.code_package, 11, '0'), "MA");
# END IF;
# ELSE
#     SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = "Invalid package type";
# END IF;
# SET NEW.code_package = new_code_package;
# END;