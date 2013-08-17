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

class Controller_Contact_Index extends Controller
{
	public function index()
	{
		$smtp = config('smtp');
		$this->content = new View('contact');
		$this->content->message = NULL;
		$rules = array(
		'full_name' => 'required|string|max_length[128]',
		'email' => 'required|valid_email|max_length[128]',
		'address' => 'required|string|min_length[12]|max_length[128]',
		'message' => 'required|string|min_length[30]',
		'phone' => 'required|numeric|min_length[10]',
		'security_code' => 'required|captcha');
		$validation = new Validation();
		if($validation->run($rules))
		{
			$message = sprintf("Họ tên: %s\nEmail: %s\nĐịa chỉ: %s\nĐiện thoại: %s\n\nNội dung:\n%s\n\n\n--\n%s",post('full_name'),post('email'),post('address'),post('phone'),post('message'),DOMAIN);
			$mail = new Mail();
			if(is_array($smtp)){
				$mail->protocol = 'smtp';
				$mail->hostname = $smtp['smtp_hostname'];
				$mail->username = $smtp['smtp_username'];
				$mail->password = $smtp['smtp_password'];
			}
			$mail->setTo($this->appsite['email']);
			$mail->setFrom(post('email'));
			$mail->setSender(post('full_name'));
			$mail->setSubject(sprintf('Liên hệ: %s gởi thông tin từ website.', post('full_name')));
			$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
			$mail->send();
			$this->content->message = sprintf('Cám ơn bạn "%s" đã gởi thông tin cho chúng tôi.', post('full_name'));
			unset($_POST);
		}

		$fields = array(
			'full_name' => array(),
			'email' => array(),
			'phone' => array(),
			'address' => array(),
			'message' => array('type' => 'textarea'),
			'security_code' => array('description' => '<img src="/captcha.html" alt="captcha" /> ('.lang('enter_code').')'),
			'submit' => array('type' => 'submit', 'value' => lang('send'))
		);
		$form = new Form($validation, array('id' => 'contact'));
		$form->fields($fields);
		$this->content->form = $form;
		$this->content->appsite = $this->appsite;
	}
}