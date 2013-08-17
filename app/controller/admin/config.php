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

class Controller_Admin_Config extends Controller
{
	public function index()
	{
		if (false==controller_admin_index::checklogin()) redirect(HTTP_SERVER.'/admin/login');
		$this->content = new View('config');
		$this->content->error_warning = NULL;
		$validation = new Validation();

		$fields = array();
		$settings = Model_Setting::fetch(array('group' => 'config'));
		foreach($settings as $s){
			$rules[$s->key] = 'required|string';
			$fields[$s->key] = array('value' => $s->value, 'description' => $s->description);
		}
		$fields['submit'] = array('type' => 'submit', 'value' => 'Submit');

		if($validation->run($rules))
		{
			foreach($_POST as $k => $v){
				if(isset($fields[$k]) && !in_array($k,array("submit","token"))){
					Model_Setting::$db->update('setting', array('value' => $v), array('group' => 'config', 'key' => $k));
					$fields[$k] = array('value' => $v);
				}
			}
			$this->content->error_warning = 'Success!';
		}

		$form = new Form($validation, array('id' => 'form'));
		$form->fields($fields);
		$this->content->form = $form;
	}
}