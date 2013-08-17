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
CREATE TABLE `products_purchase` (
  `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  `product_id` int(11) DEFAULT '0',
  `user_id` int(11) DEFAULT '0',
  `quantity` tinyint(4) DEFAULT '1',
  `purchased_date` timestamp DEFAULT CURRENT_TIMESTAMP,
  `status` tinyint(4) DEFAULT '1',
  `note` varchar(500))
*/
class Model_ProductPurchase extends APCORM
{
	public static $t = 'products_purchase';
}
