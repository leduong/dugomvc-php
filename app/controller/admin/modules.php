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

class Controller_Admin_Modules extends Controller
{
	public function index()
	{
		redirect(HTTP_SERVER.'/admin/modules/lists');
	}

	public function lists()
	{
		if (false==controller_admin_index::checklogin()) redirect(HTTP_SERVER.'/admin/login');
		$this->content = new View('modules');
		$this->content->message = $this->content->form = NULL;
		$this->content->lists = Model_Setting::fetch(array('group' => 'module'));
	}
	public function edit()
	{
		if (false==controller_admin_index::checklogin()) redirect(HTTP_SERVER.'/admin/login');
		$this->content = new View('modules');
		$this->content->message = NULL;
		$rules = array('key' => 'required|numeric');
		$validation = new Validation();
		if($validation->run($rules))
		{
			$c = new Model_Setting(post('key'));
			$c->value = post('description');
			$c->save();
			unset($_POST);
			$this->content->message = lang('success');
		}

		$c = new Model_Setting(get('edit'));
		$fields = array(
			'key' => array('type' => 'hidden', 'value' => $c->id),
			'name' => array('value' => lang($c->key), 'attributes' => array('disabled' => 'disabled')),
			'description' => array('type' => 'textarea', 'value' => $c->value),
			'submit' => array('type' => 'submit', 'value' => lang('save'))
		);
		$form = new Form($validation, array('id' => 'form'));
		$form->fields($fields);
		$this->content->form = $form;
	}
}