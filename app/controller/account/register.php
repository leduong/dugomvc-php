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

class Controller_Account_Register extends Controller
{
	public function index()
	{
		$this->content = new View('account/register');
		$this->content->error = NULL;
		$rules = array(
			'full_name' => 'required|string|max_length[64]',
			'email' => 'required|valid_email|max_length[128]',
			'mobile' => 'required|numeric|min_length[10]',
			'security_code' => 'required|captcha'
		);
		$fields = array(
			'email' => array('required' => '*', 'description' => lang('required_valid_email')),
			'full_name' => array('required' => '*'),
			'mobile' => array('required' => '*', 'description' => lang('phone_or_mobile_for_contact')),
			'security_code' => array('required' => '*', 'description' => '<img src="/captcha" alt="captcha" /> ('.lang('enter_code').')'),
			'submit' => array('type' => 'submit', 'value' => lang('register')));

		$validation = new Validation();

		if($validation->run($rules)){
			$find = array('username' => post('email'));
			$count = Model_User::count($find);
			if($count>0){
				$this->content->error = lang('already_registered');
			}else{
				$password = substr(sha1(mt_rand()), 17, 6);
				$u = new Model_User();
				$u->username = post('email');
				$u->password = md5($password);
				$u->full_name = post('full_name');
				$u->mobile = post('mobile');
				$u->save();
				$this->content->success = lang('successfully_registered');
				$this->content->form = NULL;

				$message = sprintf(
					lang('mail_registered'),
					post('full_name'),
					post('email'),
					post('password'), DOMAIN);
				$mail = new Mail();
				$mail->setTo(post('email'));
				$mail->setFrom($this->appsite['email']);
				$mail->setSender(sprintf('Website %s', DOMAIN));
				$mail->setSubject(sprintf(lang('mail_registered_subject'), DOMAIN));
				$mail->setText(strip_tags(html_entity_decode($message, ENT_QUOTES, 'UTF-8')));
				$mail->send();
				unset($_POST);
				return;
			}
		}

		$form = new Form($validation, array('id' => 'register'));
		$form->fields($fields);
		$this->content->form = $form;
	}
}