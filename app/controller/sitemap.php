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

class Controller_Sitemap extends Controller {
	public function index() {
		/*$dt = new Time();
		$datetime = str_replace(' ','T',$dt->format('Y-m-d H:i:s'))."+07:00";
		header("Content-Type: application/xml");
		echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
		echo "<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">";
		$ar = Model_Coupon::fetch(NULL,1000,0,array('id' => 'DESC'));
		foreach ($ar as $a) {
			echo "<url>";
			echo "<loc>http://" .DOMAIN."/coupon/".$a->slug.".html</loc>";
			echo "<lastmod>".$datetime."</lastmod>";
			echo "<changefreq>weekly</changefreq>";
			echo "<priority>1.0</priority>";
			echo "</url>";
		}
		echo "</urlset>";
		exit;*/
	}
}