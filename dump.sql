-- -------------------------------------------------------------
-- Database: calls
-- Generation Time: 2022-05-15 01:33:40.2520
-- -------------------------------------------------------------


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


DROP TABLE IF EXISTS `calls`;
CREATE TABLE `calls` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `phone` varchar(20) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `date_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `call_duration` int(11) unsigned NOT NULL,
  `operator_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `operators`;
CREATE TABLE `operators` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `price_per_minute` double NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `operator_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO `calls` (`id`, `user_id`, `phone`, `type`, `date_time`, `call_duration`, `operator_id`) VALUES
('1', '1', '+7 (555) 000-00-01', '1', '2022-05-14 13:31:05', '10', '1'),
('2', '2', '+7 (555) 000-00-02', '0', '2022-05-14 13:31:12', '11', '2'),
('3', '1', '+7 (555) 000-00-03', '1', '2022-05-14 00:00:00', '1', '1'),
('4', '1', '+7 (555) 000-00-55', '1', '2022-05-14 00:05:00', '1', '1'),
('5', '1', '+7 (555) 000-00-12', '1', '2022-05-14 00:00:00', '111', '3'),
('6', '1', '+7 (555) 000-33-01', '1', '2022-05-15 00:00:00', '3', '1');

INSERT INTO `operators` (`id`, `name`, `price_per_minute`) VALUES
('1', 'Билайн', '1.211'),
('2', 'МТС', '1.35'),
('3', 'Мегафон', '1.23');

INSERT INTO `users` (`id`, `name`, `phone`, `operator_id`) VALUES
('1', 'Вася', '+7(925)506-00-06', '3'),
('2', 'Петя', '+7(910)500-00-00', '2'),
('3', 'Иван', '+7(906)500-99-99', '1');



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;