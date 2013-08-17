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
class Controller_Facebook extends Controller
{
	public function index()
	{
		$coupon_id = (int)get('coupon_id');
		$coupon_id=($coupon_id>1)?$coupon_id:(int)$this->appsite['facebook_post_id'];
		$access_token = Model_Setting::fetch(array('key' => 'facebook_access_token'),1);
		$access_token = end($access_token);
		$page_id = $this->appsite['facebook_page_id'];

		$fb = new Facebook(array(
			'appId'	 => $this->appsite['facebook_appId'],
			'secret' => $this->appsite['facebook_secret'],
		));
		//$fb->setAccessToken($access_token->value);

		$user = $fb->getUser();
		if ($user) {
			if ((time()-strtotime($access_token->timestamp))>(24*3600*30)){//
				$page_info = $fb->api("/$page_id?fields=access_token");
				if(!empty($page_info['access_token'])) {
						$access_token->value = $page_info['access_token'];
						$access_token->save();
				}
			}
			try {
				$coupon = new Model_Coupon($coupon_id);
				$last_coupon = Model_Coupon::fetch(array(),1,0,array('id' => 'DESC'));
				$last_coupon_id = $last_coupon[0]->id;
				while (!$coupon&&($coupon_id <= $last_coupon_id)) {
					$coupon_id++;
					$coupon = new Model_Coupon($coupon_id);
				}
				if ($coupon){
					$next_coupon_id = $coupon_id+1;
					// set next post_id
					$fb_post_id = Model_Setting::fetch(array('key' => 'facebook_post_id'),1);
					$fb_post_id = end($fb_post_id);
					$fb_post_id->value = $next_coupon_id;
					$fb_post_id->save();

					$city = new Model_CouponsCities($coupon->city);
					$args = array(
						'access_token'  => $access_token->value,
	          'message' => html_entity_decode(trim($coupon->excerpt),ENT_COMPAT, 'UTF-8'),
						'link' => HTTP_SERVER."/coupon/".$coupon->slug.".html",
						'name' => $city->name.": ".$coupon->name." (chỉ với ".number_format($coupon->offer, 0, '', '.')." đồng)",
						'picture' => $coupon->image,
						'caption' => $city->name,
					);
					$post_id = $fb->api("/$page_id/feed","post",$args);
					header("Refresh:5;url=http://opendeal.info/facebook/index/coupon_id/$next_coupon_id.html",TRUE,303);
					if ($post_id) echo "$post_id\n\n";
					echo "<a href=\"http://opendeal.info/facebook/index/coupon_id/$next_coupon_id.html\">Tiếp tục [$coupon_id]</a>";
				}

			} catch (FacebookApiException $e) {error_log($e);$user = null;}
		}

		// Login or logout url will be needed depending on current user state.
		if ($user) {
			echo '<br /><br /><a href="'.$logoutUrl = $fb->getLogoutUrl().'">Logout</a>';
		} else {
			echo '<br /><br /><a href="'.$loginUrl = $fb->getLoginUrl(array('scope'=>'manage_pages,publish_stream,offline_access')).'">Login with Facebook</a>';
		}
		exit;
	}
}