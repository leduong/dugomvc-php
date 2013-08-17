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
class Controller_Common_Filemanager extends Controller {
	private $error=array();
	public function index() {
		if (false==controller_admin_index::checklogin()) redirect(HTTP_SERVER.'/admin/login');
		require THEME."/default/filemanager".EXT;
		exit;
	}
	public function image() {
		if (false==controller_admin_index::checklogin()) redirect(HTTP_SERVER.'/admin/login');
		if(post('image'))
			echo HTTP_SERVER.post('image');
		exit;
	}
	public function directory() {
		if (false==controller_admin_index::checklogin()) redirect(HTTP_SERVER.'/admin/login');
		$json=array();
		if(isset ($_POST['directory'])) {
			$directories=glob(rtrim(UPLOAD.str_replace('../','',post('directory')),'/').'/*',GLOB_ONLYDIR);
			if($directories) {
				$i=0;
				foreach($directories as $directory) {
					$json[$i]['data']=basename($directory);
					$json[$i]['attributes']['directory']=substr($directory,strlen(UPLOAD));
					$children=glob(rtrim($directory,'/').'/*',GLOB_ONLYDIR);
					if($children)
						$json[$i]['children']=' ';
					$i++;
				}
			}
		}
		echo json_encode($json);
		exit;
	}
	public function files() {
		if (false==controller_admin_index::checklogin()) redirect(HTTP_SERVER.'/admin/login');
		$json=array();
		$directory=UPLOAD.((post('directory'))?str_replace('../','',post('directory')):'');
		$allowed=array('.jpg','.jpeg','.png','.gif');
		$files=glob(rtrim($directory,'/').'/*');
		foreach($files as $file) {
			$ext=(is_file($file))?strrchr($file,'.'):'';
			if(in_array(strtolower($ext),$allowed)) {
				$size=filesize($file);
				$i=0;
				$suffix=array('B','KB','MB','GB','TB','PB','EB','ZB','YB');
				while(($size/1024)>1) {
					$size=$size/1024;
					$i++;
				}
				$json[]=array('file' => substr($file,strlen(UPLOAD)),'filename' => basename($file),'size' => round(substr($size,0,strpos($size,'.')+4),2).$suffix[$i],'thumb' => str_replace(ROOT_PATH,HTTP_SERVER.'/',$file));
			}
		}
		echo json_encode($json);
		exit;
	}
	public function create() {
		if (false==controller_admin_index::checklogin()) redirect(HTTP_SERVER.'/admin/login');
		$json=array();
		if(isset ($_POST['directory'])) {
			if(post('name')) {
				$directory=rtrim(UPLOAD.str_replace('../','',post('directory')),'/');
				if(!is_dir($directory))
					$json['error']=lang('error_directory');
				if(file_exists($directory.'/'.str_replace('../','',post('name'))))
					$json['error']=lang('error_exists');
			}
			else
				$json['error']=lang('error_name');
		}
		else{
			$json['error']=lang('error_directory');
		}
		if(!isset ($json['error'])) {
			@ mkdir($directory.'/'.str_replace('../','',post('name')),0777);
			$json['success']=lang('text_create');
		}
		echo json_encode($json);
		exit;
	}
	public function delete() {
		if (false==controller_admin_index::checklogin()) redirect(HTTP_SERVER.'/admin/login');
		$json=array();
		if(isset ($_POST['path'])) {
			$path=rtrim(UPLOAD.str_replace('../','',post('path')),'/');
			if(!file_exists($path))
				$json['error']=lang('error_select');
			if($path==rtrim(UPLOAD,'/'))
				$json['error']=lang('error_delete');
		}
		else
			$json['error']=lang('error_select');
		if(!isset ($json['error'])) {
			if(is_file($path)) {
				unlink($path);
			}
			elseif(is_dir($path)) {
				$this->recursiveDelete($path);
			}
			$json['success']=lang('text_delete');
		}
		echo json_encode($json);
		exit;
	}
	protected function recursiveDelete($directory) {
		if (false==controller_admin_index::checklogin()) redirect(HTTP_SERVER.'/admin/login');
		if(is_dir($directory))
			$handle=opendir($directory);
		if(!$handle)
			return FALSE;
		while(false!==($file=readdir($handle))) {
			if($file!='.'&&$file!='..') {
				if(!is_dir($directory.'/'.$file)) {
					@ unlink($directory.'/'.$file);
				}
				else{
					$this->recursiveDelete($directory.'/'.$file);
				}
			}
		}
		closedir($handle);
		@ rmdir($directory);
		return TRUE;
	}
	public function move() {
		if (false==controller_admin_index::checklogin()) redirect(HTTP_SERVER.'/admin/login');
		$json=array();
		if(post('from')&&post('to')) {
			$from=rtrim(UPLOAD.str_replace('../','',post('from')),'/');
			if(!file_exists($from)) {
				$json['error']=lang('error_missing');
			}
			if($from==UPLOAD) {
				$json['error']=lang('error_default');
			}
			$to=rtrim(UPLOAD.str_replace('../','',post('to')),'/');
			if(!file_exists($to))
				$json['error']=lang('error_move');
			if(file_exists($to.'/'.basename($from)))
				$json['error']=lang('error_exists');
		}
		else
			$json['error']=lang('error_directory');
		if(!isset ($json['error'])) {
			@ rename($from,$to.'/'.basename($from));
			$json['success']=lang('text_move');
		}
		echo json_encode($json);
		exit;
	}
	public function copy() {
		if (false==controller_admin_index::checklogin()) redirect(HTTP_SERVER.'/admin/login');
		$json=array();
		if(post('path')&&post('name')) {
			if((strlen(utf8_decode(post('name')))<3)||(strlen(utf8_decode(post('name')))>255)) {
				$json['error']=lang('error_filename');
			}
			$old_name=rtrim(UPLOAD.str_replace('../','',post('path')),'/');
			if(!file_exists($old_name)||$old_name==UPLOAD) {
				$json['error']=lang('error_copy');
			}
			$ext=(is_file($old_name))?strrchr($old_name,'.'):'';
			$new_name=dirname($old_name).'/'.str_replace('../','',post('name').$ext);
			if(file_exists($new_name))
				$json['error']=lang('error_exists');
		}
		else{
			$json['error']=lang('error_select');
		}
		if(!isset ($json['error'])) {
			if(is_file($old_name)) {
				copy($old_name,$new_name);
			}
			else{
				$this->recursiveCopy($old_name,$new_name);
			}
			$json['success']=lang('text_copy');
		}
		echo json_encode($json);
		exit;
	}
	function recursiveCopy($source,$destination) {
		if (false==controller_admin_index::checklogin()) redirect(HTTP_SERVER.'/admin/login');
		$directory=opendir($source);
		@ mkdir($destination);
		while(false!==($file=readdir($handle))) {
			if(($file!='.')&&($file!='..')) {
				if(is_dir($source.'/'.$file)) {
					$this->recursiveCopy($source.'/'.$file,$destination.'/'.$file);
				}
				else{
					copy($source.'/'.$file,$destination.'/'.$file);
				}
			}
		}
		closedir($directory);
	}
	public function folders() {
		if (false==controller_admin_index::checklogin()) redirect(HTTP_SERVER.'/admin/login');
		echo $this->recursiveFolders(UPLOAD);
		exit;
	}
	protected function recursiveFolders($directory) {
		$output='';
		$output.='<option value="'.substr($directory,strlen(UPLOAD)).'">'.substr($directory,strlen(UPLOAD)).'</option>';
		$directories=glob(rtrim(str_replace('../','',$directory),'/').'/*',GLOB_ONLYDIR);
		foreach($directories as $directory)
			$output.=$this->recursiveFolders($directory);
		return $output;
	}
	public function rename() {
		if (false==controller_admin_index::checklogin()) redirect(HTTP_SERVER.'/admin/login');
		$json=array();
		if(post('path')&&post('name')) {
			if((strlen(utf8_decode(post('name')))<3)||(strlen(utf8_decode(post('name')))>255)) {
				$json['error']=lang('error_filename');
			}
			$old_name=rtrim(UPLOAD.str_replace('../','',post('path')),'/');
			if(!file_exists($old_name)||$old_name==UPLOAD) {
				$json['error']=lang('error_rename');
			}
			$ext=(is_file($old_name))?strrchr($old_name,'.'):'';
			$new_name=dirname($old_name).'/'.str_replace('../','',post('name').$ext);
			if(file_exists($new_name))
				$json['error']=lang('error_exists');
		}
		if(!isset ($json['error'])) {
			rename($old_name,$new_name);
			$json['success']=lang('text_rename');
		}
		echo json_encode($json);
		exit;
	}
	public function upload() {
		if (false==controller_admin_index::checklogin()) redirect(HTTP_SERVER.'/admin/login');
		$json=array();
		if(isset ($_POST['directory'])) {
			if(isset ($_FILES['image'])&&$_FILES['image']['tmp_name']) {
				if((strlen(utf8_decode($_FILES['image']['name']))<3)||(strlen(utf8_decode($_FILES['image']['name']))>255)) {
					$json['error']=lang('error_filename');
				}
				$directory=rtrim(UPLOAD.str_replace('../','',post('directory')),'/');
				if(!is_dir($directory))
					$json['error']=lang('error_directory');
				if($_FILES['image']['size']>500000)
					$json['error']=lang('error_file_size');
				$allowed=array('image/jpeg','image/pjpeg','image/png','image/x-png','image/gif','application/x-shockwave-flash');
				if(!in_array($_FILES['image']['type'],$allowed)) {
					$json['error']=lang('error_file_type');
				}
				$allowed=array('.jpg','.jpeg','.gif','.png','.flv');
				if(!in_array(strtolower(strrchr($_FILES['image']['name'],'.')),$allowed))
					$json['error']=lang('error_file_type');
				if($_FILES['image']['error']!=UPLOAD_ERR_OK)
					$json['error']='error_upload_'.$_FILES['image']['error'];
			}
			else{
				$json['error']=lang('error_file');
			}
		}
		else{
			$json['error']=lang('error_directory');
		}
		if(!isset ($json['error'])) {
			if(@ move_uploaded_file($_FILES['image']['tmp_name'],$directory.'/'.basename($_FILES['image']['name']))) {
				$json['success']=lang('text_uploaded');
			}
			else{
				$json['error']=lang('error_uploaded');
			}
		}
		echo json_encode($json);
		exit;
	}
}