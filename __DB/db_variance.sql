-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 03, 2022 at 01:22 AM
-- Server version: 10.4.19-MariaDB
-- PHP Version: 7.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_variance`
--

-- --------------------------------------------------------

--
-- Table structure for table `file_uploaded`
--

CREATE TABLE `file_uploaded` (
  `id` int(11) NOT NULL,
  `file_type` text NOT NULL,
  `file_name` text NOT NULL,
  `banner` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `raw_gigalife`
--

CREATE TABLE `raw_gigalife` (
  `id` varchar(25) NOT NULL,
  `mid` text NOT NULL,
  `transaction_type` text NOT NULL,
  `merchant_reference_number` text NOT NULL,
  `settlement_amount` text NOT NULL,
  `gateway_reference_no` text NOT NULL,
  `masked_card_number` text NOT NULL,
  `auth_code` text NOT NULL,
  `transaction_reference_no` text NOT NULL,
  `blank_one` varchar(5) NOT NULL,
  `elp_reference_number` text NOT NULL,
  `merchant_reference_no` text NOT NULL,
  `original_currency_amount` text NOT NULL,
  `payment_reference_no` text NOT NULL,
  `multisys_status` text NOT NULL,
  `blank_two` varchar(5) NOT NULL,
  `blank_three` varchar(5) NOT NULL,
  `reference_nummber` text NOT NULL,
  `amount` text NOT NULL,
  `iload_status` text NOT NULL,
  `blank_four` varchar(5) NOT NULL,
  `variance_no` text NOT NULL,
  `paymaya_mrn` text NOT NULL,
  `iload_rn` text NOT NULL,
  `ern_rrn` text NOT NULL,
  `action` text NOT NULL,
  `remarks` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `raw_gigalife_formatted`
--

CREATE TABLE `raw_gigalife_formatted` (
  `id` int(11) NOT NULL,
  `mrns` text NOT NULL,
  `gateway_reference_no` text NOT NULL,
  `remarks` text NOT NULL,
  `tagging` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `raw_logs_elp`
--

CREATE TABLE `raw_logs_elp` (
  `id` varchar(25) NOT NULL,
  `file_id` varchar(25) NOT NULL,
  `type` text NOT NULL,
  `number` varchar(25) NOT NULL,
  `corporate_id` text NOT NULL,
  `branch_id` text NOT NULL,
  `request_reference_number` text NOT NULL,
  `plan_code` text NOT NULL,
  `amount` text NOT NULL,
  `retailer_deduct` text NOT NULL,
  `retailer_new_balance` text NOT NULL,
  `response_code` text NOT NULL,
  `response_description` text NOT NULL,
  `transaction_request_reference_number` text NOT NULL,
  `transaction_timestamp` text NOT NULL,
  `body` longtext NOT NULL,
  `created_at` varchar(50) NOT NULL,
  `updated_at` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `raw_logs_gigapay`
--

CREATE TABLE `raw_logs_gigapay` (
  `id` varchar(25) NOT NULL,
  `file_id` varchar(25) NOT NULL,
  `status` longtext NOT NULL,
  `transaction_digest` longtext NOT NULL,
  `number` varchar(25) NOT NULL,
  `main_number` varchar(25) NOT NULL,
  `brand` longtext NOT NULL,
  `transaction_date` varchar(45) NOT NULL,
  `transaction_type` longtext NOT NULL,
  `payment_method` longtext NOT NULL,
  `currency` varchar(25) NOT NULL,
  `amount` varchar(45) NOT NULL,
  `keyword` longtext NOT NULL,
  `action` longtext NOT NULL,
  `payment_reference_number` longtext NOT NULL,
  `app_transaction_number` longtext NOT NULL,
  `comment` longtext NOT NULL,
  `is_payment_status_updated` varchar(15) NOT NULL,
  `authentication_status_origin` longtext NOT NULL,
  `wallet_amount` varchar(25) NOT NULL,
  `wallet_fees` varchar(25) NOT NULL,
  `wallet_status` longtext NOT NULL,
  `wallet_request_reference_no` longtext NOT NULL,
  `wallet_merchant_value` longtext NOT NULL,
  `wallet_payment_token_id` longtext NOT NULL,
  `paymaya_checkout_id` longtext NOT NULL,
  `paymaya_void_id` longtext NOT NULL,
  `paymaya_void_reason` longtext NOT NULL,
  `last_four` longtext NOT NULL,
  `first_six` longtext NOT NULL,
  `card_type` longtext NOT NULL,
  `elp_transaction_number` longtext NOT NULL,
  `elp_corporation_id` longtext NOT NULL,
  `elp_branch_id` longtext NOT NULL,
  `elp_request_reference_number` longtext NOT NULL,
  `elp_plan_code` longtext NOT NULL,
  `elp_amount` longtext NOT NULL,
  `elp_retailer_deduct` longtext NOT NULL,
  `elp_retailer_new_balance` longtext NOT NULL,
  `elp_response_code` longtext NOT NULL,
  `elp_response_description` longtext NOT NULL,
  `elp_transaction_timestamp` longtext NOT NULL,
  `payment_method_tagged` text NOT NULL,
  `created_at` varchar(50) NOT NULL,
  `updated_at` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `raw_logs_splunk`
--

CREATE TABLE `raw_logs_splunk` (
  `id` varchar(25) NOT NULL,
  `_time` text NOT NULL,
  `file_id` text NOT NULL,
  `processor_ref_no` text NOT NULL,
  `app_transaction_number` text NOT NULL,
  `state` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `file_uploaded`
--
ALTER TABLE `file_uploaded`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `raw_gigalife_formatted`
--
ALTER TABLE `raw_gigalife_formatted`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `file_uploaded`
--
ALTER TABLE `file_uploaded`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `raw_gigalife_formatted`
--
ALTER TABLE `raw_gigalife_formatted`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
