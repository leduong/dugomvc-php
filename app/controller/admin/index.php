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

class Controller_Admin_Index extends Controller
{
	public function index()
	{
		redirect(HTTP_SERVER.'/admin/login');
	}
	public static function checklogin(){
		$a = unserialize(cookie::get('auth')); $d = array('username' => $a['username']);
		return (is_array($a)&&($c = Model_User::count($a))&&($b = Model_Admin::count($d))&&($c==$b));
	}
	public function login()
	{
		$this->content = new View('login','admin');
		$this->content->error = NULL;
		$r = array(
			'username' => 'required|string|min_length[4]|max_length[64]',
			'password' => 'required|string|min_length[6]');
		$v = new Validation();
		if($v->run($r))
		{
			$a = array('username' => post('username'), 'password' => md5(post('password')));
			if (is_array($a)&&($c = Model_User::count($a))&&($c==1)){
				cookie::set('auth',serialize($a));
				redirect(HTTP_SERVER);
				return;
			} else {$this->content->error = lang('login_false');}
		}
	}
	public function logout()
	{
		cookie::set('auth',NULL);
		redirect(HTTP_SERVER);
	}
}