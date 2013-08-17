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

class Controller_News_Index extends Controller
{
	public function index()
	{
		$this->content = new View('news');
		$limit=$this->appsite['limit_per_page'];
		$page=((int)get('page')>1)?(int)get('page'):1;
		$offset=$limit*($page-1);
		$array = array('published' => 1);
		if ($slug = Model_Entry::fetch(array('slug' => get('news')),1)){

			$this->content->news_id = $slug[0];
			$this->content->appsite = $this->appsite;
			$this->content->title = $site_title = $slug[0]->title;
			$this->content->description = $slug[0]->description;
		}
		else{
			$total = Model_Entry::count($array);
			$pagination = new Pagination($total,HTTP_SERVER."/news/page/[[page]].html",$page,$limit);
			$this->content->entries = Model_Entry::fetch($array,$limit,$offset,array('id' => 'DESC'));
			$this->content->pagination = $pagination;
			$site_title = lang('page')." $page";
		}
		$this->appsite['site_title'] = $site_title.' - '.$this->appsite['site_title'];
		$this->appsite['meta_keywords'] = $site_title.', '.$this->appsite['meta_keywords'];
		$this->appsite['meta_description'] = $site_title.', '.$this->appsite['meta_description'];
	}
}