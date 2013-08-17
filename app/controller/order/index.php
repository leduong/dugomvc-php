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
class Controller_Order_Index extends Controller
{
	public function index()
	{
		if ($slug = Model_Coupon::fetch(array('slug' => get('order')),1)){
			$this->content = new View('order');
			$this->content->coupon_id = $coupon_id = $slug[0];
			$this->content->appsite = $this->appsite;
			$this->content->error_warning = $this->content->form = NULL;
			$array = array();for($i=1;$i<10;$i++) $array[$i] = $i;

			$rules = array(
			'full_name' => 'required|string|max_length[128]',
			'email' => 'required|valid_email|max_length[128]',
			'address' => 'required|string|min_length[12]|max_length[128]',
			'mobile' => 'required|numeric|min_length[10]',
			'security_code' => 'required|captcha');
			$validation = new Validation();
			if($validation->run($rules))
			{
				$message = sprintf(lang('mail_order_coupon'),post('full_name'),post('email'),post('address'),post('mobile'),post('coupon'),post('quantity'),post('url'),DOMAIN);
				$mail = new Mail();
				$mail->setTo($this->appsite['email']);
				$mail->setFrom(post('email'));
				$mail->setSender(post('full_name'));
				$mail->setSubject(sprintf('Đặt mua: %s đặt mua từ website.', post('full_name')));
				$mail->setText(html_entity_decode($message, ENT_QUOTES, 'UTF-8'));
				$mail->send();
				$mail->setFrom($this->appsite['email']);
				$mail->setTo(post('email'));
				$mail->send();
				if($fetch = Model_User::fetch(array('username' => post('email')),1)){
					$u = $fetch[0];
					}
				else {
					$newpw = substr(sha1(mt_rand()), 17, 6);
					$u = new Model_User();
					$u->password = md5($newpw);
					$u->username = post('email');
					$u->full_name = post('full_name');
					$u->address = post('address');
					$u->mobile = post('mobile');
					$u->save();
					$message = sprintf(lang('mail_registered'), post('full_name'), post('email'), $newpw, DOMAIN);
					$mail->setTo(post('email'));
					$mail->setFrom($this->appsite['email']);
					$mail->setSender(sprintf('Website %s', DOMAIN));
					$mail->setSubject(sprintf(lang('mail_registered_subject'), DOMAIN));
					$mail->setText(strip_tags(html_entity_decode($message, ENT_QUOTES, 'UTF-8')));
					$mail->send();
				}
				$quantity = post('quantity');
				for ($i=0; $i < $quantity; $i++) {
					$p = new Model_CouponPurchase();
					$p->user_id = $u->id;
					$p->coupon_id = $coupon_id->id;
					$p->quantity = 1;
					$p->save();
				}

				$price = $coupon_id->offer;
				$product = $coupon_id->name;
				$total_amount = (int)$quantity * (int)$price;
				$this->content->checkout = "<form method='post' action='https://www.nganluong.vn/advance_payment.<?<?php
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
/*' style='float: left; display: inline-block; width: 49%; min-height: 200px;'>
				<input type='hidden' name='receiver' value='thanhtoan@topmua.vn' />
				<input type='hidden' name='product' value='$product' />
				<input type='hidden' name='price' value='$total_amount' />
				<input type='hidden' name='return_url' value='http://topmua.vn/checkout/success' />
				<input type='hidden' name='comments' value='Topmua Coupon' />
				<input type='image' src='https://www.nganluong.vn/data/images/buttons/11.gif' />
				</form>
				<form method='get' action='https://www.baokim.vn/payment/customize_payment/product' style='float: left; display: inline-block; width: 49%; min-height: 200px;'>
				<input type='hidden' name='business' value='thanhtoan@topmua.vn' />
				<input type='hidden' name='product_name' value='$product' />
				<input type='hidden' name='product_price' value='$price' />
				<input type='hidden' name='product_quantity' value='$quantity' />
				<input type='hidden' name='total_amount' value='$total_amount' />
				<input type ='hidden' name='name_ctl' value='1' >
				<input type='image' src='https://www.baokim.vn/application/uploads/buttons/btn_pay_now_3.png' border='0' name='submit' alt='Thanh toán an toàn với Bảo Kim !' />
				</form>";
				unset($_POST);
				return;
			}

			$fields = array(
				'url' => array('type' => 'hidden', 'value' => HTTP_SERVER.'/coupon/'.$coupon_id->slug.'.html'),
				'coupon' => array('type' => 'hidden', 'value' => $coupon_id->name),
				'full_name' => array('description' => 'Vui lòng cho chúng tôi biết Họ tên của Quý khách, Thông tin này sẽ giúp chúng tôi liên hệ xác nhận giao hàng cho quý khách.'),
				'email' => array('description' => 'Vui lòng nhập chính xác địa chỉ email để nhận được thông báo mua hàng'),
				'mobile' => array('description' => 'Vui lòng nhập đúng số điện thoại di động để nhận mã số phiếu qua tin nhắn SMS'),
				'address' => array(),
				'quantity' => array('type' => 'select', 'options' => $array),
				'security_code' => array('description' => '<img src="/captcha" alt="captcha" /> ('.lang('enter_code').')'),
				'submit' => array('type' => 'submit', 'value' => lang('order'))
			);
			$form = new Form($validation, array('id' => 'order'));
			$form->fields($fields);
			$this->content->form = $form;
		}
	}
}