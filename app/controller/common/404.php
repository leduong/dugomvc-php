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
// Sample 404 error page handling
class Controller_Common_404 extends Controller
{
	public function index()
	{
		if ($slug = Model_Info::fetch(array('slug' => str_replace('.html','',url())))){
			$t = $slug[0];
			$this->content = new View('box');
			$this->content->title = $this->appsite['site_title'] = $t->name;
			$this->content->content = $t->description;
		}
		elseif($slug = Model_Articles::fetch(array('slug' => str_replace('.html','',url())))){
			$t = $slug[0];
			$this->content = new View('entry');
			$this->content->title = $this->appsite['site_title'] = $t->name;
			$this->content->description = $t->description;
			$this->content->entries = Model_Entry::fetch(array('category_id' => $t->id),20);
		}
		elseif($slug = Model_Entry::fetch(array('slug' => str_replace('.html','',url())))){
			$t = $slug[0];
			$this->content = new View('entry');
			$this->content->title = $this->appsite['site_title'] = $t->title;
			$this->content->content = $t->description;
		}
		else $this->show_404();
	}
}