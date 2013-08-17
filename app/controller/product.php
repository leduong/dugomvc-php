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
class Controller_Product extends Controller {
	public function category() {
		$this->content = new View('category');
		if ($slug = Model_ProductCategory::fetch(array('slug' => str_replace('.html','',get('category'))))){
			$t = $slug[0];
			$this->content->title = $t->name;
			$this->content->content = $t->description;
			$this->content->products = Model_Product::fetch(array('category'=>$t->id));
			$this->content->categories = Model_ProductCategory::fetch(array('category_id'=>$t->id));

			$this->appsite['breadcrumb'] = $t->category_id;
			// SEO
			$this->appsite['site_title'] = $t->name;
			$this->appsite['meta_keywords'] = $t->name.', '.$this->appsite['meta_keywords'];
			$this->appsite['meta_description'] = $t->name.', '.$this->appsite['meta_description'];
		} else {
			$this->content->products = Model_Product::fetch(NULL,10);
		}
	}
	public function index() {
		$this->content = new View('product');
		if ($slug = Model_Product::fetch(array('slug' => str_replace('.html','',get('product'))))){
			$this->content->product = $t = end($slug);
			$this->content->title = $t->name;
			$this->content->content = $t->description;

			$this->appsite['breadcrumb'] = $t->category;
			// SEO
			$this->appsite['site_title'] = $t->name;
			$this->appsite['meta_keywords'] = $t->name.', '.$this->appsite['meta_keywords'];
			$this->appsite['meta_description'] = $t->name.', '.$this->appsite['meta_description'];
		} else {
			$this->content->products = Model_Product::fetch(NULL,10);
		}
	}
}