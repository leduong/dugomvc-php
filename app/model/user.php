<?php
/*
 *
 * Copyright 2013 Le Duong <du@leduong.com>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 *
 *
 */
/*
CREATE TABLE `user` (
  `id` INTEGER PRIMARY KEY AUTOINCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(32),
  `full_name` varchar(255),
  `approved` tinyint(1) DEFAULT '0',
  `package` int(11) DEFAULT '0',
  `company_name` varchar(255),
  `description` text,
  `address` varchar(255),
  `location` smallint(5) DEFAULT '0',
  `city` varchar(50),
  `zip` varchar(20),
  `phone` varchar(50),
  `fax` varchar(50),
  `mobile` varchar(50),
  `website` varchar(255),
  `rating` mediumint(8) DEFAULT '0',
  `votes` mediumint(8) DEFAULT '0',
  `date_added` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `date_last` datetime,
  `date_upgraded` varchar(10),
  `ip_added` tinytext,
  `ip_last` tinytext,
  `ip_upgraded` tinytext,
  `hits` int(11) DEFAULT '0',
  `avatar` varchar(255))
*/
class Model_User extends ORM
{
	public static $t = 'user';
	public static $f = 'user_id';

	public static $h = array(
		'purchase' => 'Model_ProductPurchase',
	);
}