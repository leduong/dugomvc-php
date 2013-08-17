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
CREATE TABLE `articles` (
  `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  `category_id` int(11) DEFAULT '0',
  `name` varchar(255),
  `meta_keywords` varchar(255),
  `meta_description` varchar(255),
  `description` text,
  `slug` varchar(255),
  `sort_order` int(3) DEFAULT '0',
  `date_added` timestamp DEFAULT CURRENT_TIMESTAMP,
  `date_modified` timestamp DEFAULT '0000-00-00 00:00:00',
  `status` varchar(1) DEFAULT '1')
*/
class Model_Articles extends APCORM
{
	public static $t = 'articles';
	public static $f = 'category_id';

	public static $h = array(
		'entry' => 'Model_Entry',
		'category' => 'Model_Articles');
}
