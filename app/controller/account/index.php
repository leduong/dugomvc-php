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

class Controller_Account_Index extends Controller
{
	public function index()
	{
		if (FALSE==controller_account_index::checklogin()) return controller_account_index::login();
		$this->content = new View('account/index');
		$user = Model_User::fetch(unserialize(cookie::get('user')),1);
		$fields1 = array(
			'level' => 'thành viên',
			'total_purchases' => Model_CouponPurchase::count(array('user_id' => $user[0]->id)),
			'total_views' => 'đang cập nhật',
			'total_message' => 'đang cập nhật',
			'total_sent' => 'đang cập nhật',
			'last_day' => time::show($user[0]->date_last),
			'last_ip' => $user[0]->ip_last,
			);
		$fields2 = array(
			'company_name' => $user[0]->company_name,
			'email' => $user[0]->username.'<div>'.html::gravatar($user->username).'</div>'.'<div>'.html::link('http://gravatar.com/site/signup',lang('change_avatar'),array('rel' => "nofollow", 'target' => "_blank")).'</div>',
			'full_name' => $user[0]->full_name,
			'address' => $user[0]->address,
			'city' => $user[0]->city,
			'location' => $user[0]->location,
			'mobile' => $user[0]->mobile,
			'phone' => $user[0]->phone,
			'fax' => $user[0]->fax,
			'website' =>$user[0]->website,
			);
		$description = $stats = '';
		foreach ($fields2 as $k => $v) {
			$description .= html::tag('tr', html::tag('th', lang($k)).html::tag('td', $v));
		}
		foreach ($fields1 as $k => $v) {
			$stats .= html::tag('tr', html::tag('th', lang($k)).html::tag('td', $v));
		}
		$this->content->description = html::tag('table', $description);
		$this->content->stats = html::tag('table', $stats);
		$this->content->title = lang('account')." ".$user[0]->full_name;
		$this->content->purchases = Model_CouponPurchase::fetch(array('user_id' => $user[0]->id),0,0,array('coupon_id' => 'DESC', 'id' => 'DESC'));
		if ($ar = Model_Order::fetch()) foreach($ar as $a) $order[$a->id] = $a->name;
		$this->content->order = $order;

	}
	public function forget()
	{
		$this->content = new View('account/forget');
		$this->content->error_warning = NULL;
		$rules = array(
			'email' => 'required|valid_email|max_length[128]',
			'security_code' => 'required|captcha');
		$validation = new Validation();
		if($validation->run($rules))
		{
			$find = array('username' => post('email'));
			$count = Model_User::count($find);
			if($count==1){
				$newpw = substr(sha1(mt_rand()), 17, 6);
				$u = Model_User::fetch($find,1);
				$u = $u[0];
				$u->password = md5($newpw);
				$u->save();
				$message = sprintf(lang('mail_forget_password'), $newpw, DOMAIN);
				$mail = new Mail();
				$mail->setTo(post('email'));
		  		$mail->setFrom($this->appsite['email']);
		  		$mail->setSender(sprintf('Website %s', DOMAIN));
		  		$mail->setSubject(sprintf(lang('mail_forget_subject'), DOMAIN));
		  		$mail->setText(strip_tags(html_entity_decode($message, ENT_QUOTES, 'UTF-8')));
	      		$mail->send();

				$this->content->error_warning = lang('password_has_been_sent');
				$this->content->form = NULL;
				redirect(HTTP_SERVER.'/'.post('redirect'),302,'refresh');
				return;
			} else {$this->content->error_warning = lang('email_not_found');}
		}
		$fields = array(
			'email' => array(),
			'security_code' => array('description' => '<img src="/captcha" alt="captcha" /> ('.lang('enter_code').')'),
			'submit' => array('type' => 'submit', 'value' => lang('send_password')));
		$form = new Form($validation, array('id' => 'forget'));
		$form->fields($fields);
		$this->content->form = $form;
	}
	public function logout()
	{
		cookie::set('user',NULL);
		redirect(HTTP_SERVER);
	}
	public function login()
	{
		if (controller_account_index::checklogin()) redirect(HTTP_SERVER);
		$this->content = new View('account/login');
		$this->content->error_warning = NULL;
		$rules = array(
		'email' => 'required|valid_email|max_length[128]',
		'password' => 'required|string|min_length[6]');
		$validation = new Validation();
		if($validation->run($rules))
		{
			$find = array('username' => post('email'), 'password' => md5(post('password')));
			$count = Model_User::count($find);
			if($count==1){
				cookie::set('user',serialize($find));
				$this->content->error_warning = lang('login_success');
				$this->content->form = NULL;
				redirect(HTTP_SERVER.'/'.post('redirect'),302,'refresh');
				return;
			} else {$this->content->error_warning = lang('login_false');}
		}
		$fields = array(
			'email' => array(),
			'password' => array('type' => 'password'),
			'redirect' => array('type' => 'hidden', 'value' => url()),
			'submit' => array('type' => 'submit', 'value' => lang('login'))
		);
		$form = new Form($validation, array('id' => 'login'));
		$form->fields($fields);
		$this->content->form = $form;
	}
	public function edit()
	{
		if (FALSE==controller_account_index::checklogin()) redirect(HTTP_SERVER);
		if ($user = Model_User::fetch(unserialize(cookie::get('user')),1)){
			$u = $user[0];
			$this->content = new View('account/register');
			$this->content->error = $this->content->success = NULL;
			$fields = array(
				'full_name' => array('required' => '*', 'value' => $u->full_name),
				'company_name' => array('value' => $u->company_name),
				'address' => array('required' => '*', 'value' => $u->address),
				'mobile' => array('required' => '*', 'value' => $u->mobile),
				'phone' => array('required' => '*', 'value' => $u->phone),
				'fax' => array('value' => $u->fax),
				'website' => array('value' => $u->website),
				'submit' => array('type' => 'submit', 'value' => lang('save')));

			$rules = array(
			'full_name' => 'required|string|max_length[64]',
			'address' => 'required|max_length[128]',
			'mobile' => 'numeric|min_length[10]|max_length[11]',
			'phone' => 'numeric|min_length[10]',
			);

			$validation = new Validation();
			if($validation->run($rules)){
				$u->full_name = h(post('full_name'));
				$u->company_name = h(post('company_name'));
				$u->address = h(post('address'));
				$u->mobile = h(post('mobile'));
				$u->phone = h(post('phone'));
				$u->fax = h(post('fax'));
				$u->website = h(post('website'));
				$u->save();

				$this->content->success = lang('successfully_update');
				$this->content->form = NULL;
				unset($_POST);
				return;
			}
			$form = new Form($validation, array('id' => 'register', 'enctype' => 'multipart/form-data'));
			$form->fields($fields);
			$this->content->form = $form;
		}
	}
	public function password()
	{
		if (FALSE==controller_account_index::checklogin()) redirect(HTTP_SERVER);
		if ($user = Model_User::fetch(unserialize(cookie::get('user')),1)){
			$u = $user[0];
			$this->content = new View('account/register');
			$this->content->error = $this->content->success = NULL;
			$fields = array(
				'current_password' => array('required' => '*', 'type' => 'password'),
				'new_password' => array('required' => '*', 'type' => 'password'),
				're-password' => array('required' => '*', 'type' => 'password'),
				'submit' => array('type' => 'submit', 'value' => lang('save')));

			$rules = array(
			'current_password' => 'required|string|min_length[6]',
			'new_password' => 'required|matches[re-password]|min_length[6]',
			);

			$validation = new Validation();
			if($validation->run($rules)){
				if($u->password==md5(post('current_password'))){
					$u->password = md5(post('new_password'));
					$u->save();
					$this->content->success = lang('successfully_update');
					$this->content->form = NULL;

					$message = sprintf(lang('mail_change_password'), $u->full_name, $this->appsite['hotline'], $this->appsite['website'], DOMAIN);
					$mail = new Mail();
					$mail->setTo($u->username);
					$mail->setFrom($this->appsite['email']);
					$mail->setSender(sprintf('Website %s', DOMAIN));
					$mail->setSubject(sprintf(lang('mail_change_password_subject'), DOMAIN));
					$mail->setText(strip_tags(html_entity_decode($message, ENT_QUOTES, 'UTF-8')));
					$mail->send();
					unset($_POST);
					return;
				} else $this->content->success = lang('error');

			}
			$form = new Form($validation, array('id' => 'register', 'enctype' => 'multipart/form-data'));
			$form->fields($fields);
			$this->content->form = $form;
		}
	}
	public function checklogin(){
		$a = unserialize(cookie::get('user'));
		return (is_array($a)&&($c = Model_User::count($a))&&($c==1));
	}
}