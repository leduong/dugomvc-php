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

class Controller_Admin_User extends Controller
{
	public function index()
	{
		redirect(HTTP_SERVER.'/admin/user/lists');
	}
	public function delete()
	{
		if (false==controller_admin_index::checklogin()) redirect(HTTP_SERVER.'/admin/login');
		$selected = post('selected');
		if (isset($selected)) foreach($selected as $s){
			if($s>1){
				$c = new Model_User($s);
				$c->delete();
			}
		}
		redirect(HTTP_SERVER.'/admin/user/lists');
	}
	public function lists()
	{
		if (false==controller_admin_index::checklogin()) redirect(HTTP_SERVER.'/admin/login');
		$limit=100;
		$page=((int)get('page')>1)?(int)get('page'):1;
		$offset=$limit*($page-1);
		$total = Model_User::count();

		$this->content = new View('user');
		$this->content->error_warning = $this->content->form = NULL;

		$pagination = new Pagination($total,HTTP_SERVER."/admin/user/lists/page/[[page]].html",$page,$limit);
		$this->content->users = Model_User::fetch(NULL,$limit,$offset,array('id' => 'DESC'));
		$this->content->pagination = $pagination;
	}
	public function create()
	{
		if (false==controller_admin_index::checklogin()) redirect(HTTP_SERVER.'/admin/login');
		$this->content = new View('user_create');
		$this->content->error_warning = NULL;
		$rules = array(
		'full_name' => 'required|string|max_length[64]',
		'email' => 'required|valid_email|max_length[128]');
		$fields = array(
			'email' => array('required' => '*', 'description' => lang('required_valid_email')),
			'password' => array('required' => '*', 'type' => 'password'),
			'full_name' => array('required' => '*'),
			'company_name' => array(),
			'address' => array(),
			'mobile' => array(),
			'phone' => array(),
			'fax' => array(),
			'website' => array(),
			'avatar' => array('type' => 'file'),
			'submit' => array('type' => 'submit', 'value' => lang('register')));

		$validation = new Validation();

		if($validation->run($rules)){
			$find = array('username' => post('email'));
			$count = Model_User::count($find);
			if($count>0){
				$this->content->error_warning = lang('already_registered');
			}else{
				$u = new Model_User();
				$u->username = post('email');
				$u->password = md5(post('password'));
				$u->full_name = post('full_name');
				$u->company_name = post('company_name');
				$u->address = post('address');
				$u->mobile = post('mobile');
				$u->phone = post('phone');
				$u->fax = post('fax');
				$u->website = post('website');
				if($_FILES && !empty($_FILES['avatar'])) {
					$upload = upload::file($_FILES['avatar'], UPLOAD.'avatar/', TRUE);
					$thumbnail = GD::thumbnail($upload,128,128,80);
					$u->avatar = str_replace(ROOT_PATH,'',$thumbnail);
				}
				$u->save();
				$this->content->error_warning = lang('successfully_registered');
				$this->content->form = NULL;

				$message = sprintf(lang('mail_registered'), post('full_name'), post('email'), post('password'), DOMAIN);
				$mail = new Mail();
				$mail->setTo(post('email'));
				$mail->setFrom($this->appsite['email']);
				$mail->setSender(sprintf('Website %s', DOMAIN));
				$mail->setSubject(sprintf(lang('mail_registered_subject'), DOMAIN));
				$mail->setText(strip_tags(html_entity_decode($message, ENT_QUOTES, 'UTF-8')));
				//$mail->send();
				unset($_POST);
				return;
			}
		}
		$form = new Form($validation, array('id' => 'register', 'enctype' => 'multipart/form-data'));
		$form->fields($fields);
		$this->content->form = $form;
	}
	public function edit()
	{
		if (false==controller_admin_index::checklogin()) redirect(HTTP_SERVER.'/admin/login');
		$this->content = new View('user_form');
		$this->content->error_warning = NULL;
		$rules = array(
		'full_name' => 'required|string|max_length[64]',
		'email' => 'required|valid_email|max_length[128]');

		$validation = new Validation();

		if($validation->run($rules)){
			$u = new Model_User(post('key'));
			$u->username = post('email');
			if (post('password')) $u->password = md5(post('password'));
			$u->full_name = post('full_name');
			$u->company_name = post('company_name');
			$u->address = post('address');
			$u->mobile = post('mobile');
			$u->phone = post('phone');
			$u->fax = post('fax');
			$u->website = post('website');
			if($_FILES && !empty($_FILES['avatar'])) {
				$upload = upload::file($_FILES['avatar'], UPLOAD.'avatar/', TRUE);
				$thumbnail = GD::thumbnail($upload,128,128,80);
				//if (is_file(ROOT_PATH.$u->avatar)) unlink(ROOT_PATH.$u->avatar);
				$u->avatar = str_replace(ROOT_PATH,'',$thumbnail);
			}
			$u->save();
			$this->content->error_warning = lang('successfully_update');
			$this->content->form = NULL;

			$message = sprintf(lang('mail_registered'), post('full_name'), post('email'), post('password'), DOMAIN);
			$mail = new Mail();
			$mail->setTo(post('email'));
			$mail->setFrom($this->appsite['email']);
			$mail->setSender(sprintf('Website %s', DOMAIN));
			$mail->setSubject(sprintf(lang('mail_registered_subject'), DOMAIN));
			$mail->setText(strip_tags(html_entity_decode($message, ENT_QUOTES, 'UTF-8')));
			//$mail->send();
			unset($_POST);
			return;
		}
		$c = new Model_User(get('edit'));
		$fields = array(
			'key' => array('type' => 'hidden', 'value' => $c->id),
			'email' => array('required' => '*', 'value' => $c->username),
			'password' => array('type' => 'password', 'description' => lang('blank_is_no_change')),
			'full_name' => array('required' => '*', 'value' => $c->full_name),
			'company_name' => array('value' => $c->company_name),
			'address' => array('value' => $c->address),
			'mobile' => array('value' => $c->mobile),
			'phone' => array('value' => $c->phone),
			'fax' => array('value' => $c->fax),
			'website' => array('value' => $c->website),
			'avatar' => array('type' => 'file', 'description' => ($c->avatar)?'<img src="'.$c->avatar.'" alt="" />':''),
			'submit' => array('type' => 'submit', 'value' => lang('save')));

		$form = new Form($validation, array('id' => 'register', 'enctype' => 'multipart/form-data'));
		$form->fields($fields);
		$this->content->form = $form;
	}
}