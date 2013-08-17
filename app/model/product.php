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
/*
CREATE TABLE `products` (
  `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  `name` varchar(255),
  `excerpt` varchar(500),
  `description` text,
  `start_date` datetime,
  `end_date` datetime,
  `hits` int(10) DEFAULT '0',
  `likes` int(10) DEFAULT '0',
  `image` varchar(255),
  `image_background` varchar(255),
  `slug` varchar(255),
  `created_by` int(3) DEFAULT '0',
  `created_date` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `offer` int(10) DEFAULT '0',
  `status` VARCHAR(1) DEFAULT '1',
  `min_limit` int(11) DEFAULT '0',
  `max_limit` int(11) DEFAULT '0',
  `real_value` int(11) DEFAULT '0',
  `type` smallint(3) DEFAULT '0',
  `sub_type` smallint(3) DEFAULT '0',
  `shop` int(11) DEFAULT '0',
  `country` int(3) DEFAULT '0',
  `city` int(3) DEFAULT '0',
  `address` varchar(500),
  `extra_link` varchar(255))
*/
class Model_Product extends APCORM
{
	public static $t = 'products';
	public static $f = 'product_id';
	public static $h = array('purchase' => 'Model_ProductPurchase');
}
