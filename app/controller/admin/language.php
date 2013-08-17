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

class Controller_Admin_Language extends Controller
{
	public function index()
	{
		redirect(HTTP_SERVER.'/admin/language/lists.html');
	}

	public function lists()
	{
		if (false==controller_admin_index::checklogin()) redirect(HTTP_SERVER.'/admin/login.html');
		$this->content = new View('language');
		$this->content->error_warning = $this->content->form = NULL;

		$limit=100;
		$page=((int)get('page')>1)?(int)get('page'):1;
		$offset=$limit*($page-1);
		$total = Model_Language::count();

		$pagination = new Pagination($total,HTTP_SERVER."/admin/language/lists/page/[[page]].html",$page,$limit);
		$this->content->lists = Model_Language::fetch(NULL,$limit,$offset,array('id' => 'DESC'));
		$this->content->pagination = $pagination;
	}
	public function edit()
	{
		if (false==controller_admin_index::checklogin()) redirect(HTTP_SERVER.'/admin/login.html');
		$this->content = new View('language');
		$this->content->error_warning = NULL;
		$rules = array('key' => 'required|numeric');
		$validation = new Validation();
		if($validation->run($rules))
		{
			$c = new Model_Language(post('key'));
			$c->en = post('en');
			$c->vi = post('vi');
			$c->zh = post('zh');
			$c->ko = post('ko');
			$c->ja = post('ja');
			$c->save();
			unset($_POST);
			$this->content->error_warning = lang('success');
		}

		$c = new Model_Language(get('edit'));
		$fields = array(
			'key' => array('type' => 'hidden', 'value' => $c->id),
			'name' => array('value' => $c->name, 'attributes' => array('disabled' => 'disabled')),
			'en' => array('type' => 'textarea', 'value' => $c->en),
			'vi' => array('type' => 'textarea', 'value' => $c->vi),
			'zh' => array('type' => 'textarea', 'value' => $c->zh),
			'ko' => array('type' => 'textarea', 'value' => $c->ko),
			'ja' => array('type' => 'textarea', 'value' => $c->ja),
			'submit' => array('type' => 'submit', 'value' => lang('save'))
		);
		$form = new Form($validation, array('id' => 'form'));
		$form->fields($fields);
		$this->content->form = $form;
	}
}