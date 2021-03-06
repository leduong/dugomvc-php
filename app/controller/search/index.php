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
class Controller_Search_Index extends Controller
{
	public function index()
	{
		return $this->keyword();
	}
	public function keyword()
	{
		$limit=36;
		$page=((int)get('page')>1)?(int)get('page'):1;
		$offset=$limit*($page-1);
		$keyword = $site_title = urldecode(trim(get('keyword')));
		$search = string::sanitize_url($keyword);
		$this->content = new View('product');
		$array = array("slug LIKE '%$search%'");

		if (($total = Model_Product::count($array))&&($total>$offset)){
			$ar = Model_Product::fetch($array,$limit,$offset);
			$pagination = new Pagination($total,HTTP_SERVER."/search/keyword/".$keyword."/page/[[page]].html",$page,$limit);
			$this->content->coupons = Model_Product::fetch($array,$limit,$offset,array('id' => 'DESC'));
			$this->content->pagination = $pagination;
		}
		$this->appsite['site_title'] = $site_title;
		$this->appsite['meta_keywords'] = $site_title.', '.$this->appsite['meta_keywords'];
		$this->appsite['meta_description'] = $site_title.', '.$this->appsite['meta_keywords'];
	}
}