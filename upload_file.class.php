<?php
	class upload_file {
		
		public $prefix = '../'; // 路徑修正
		public $max_file_size = 4194304;
		
		function __construct() {
			require_once $_SERVER['CONTEXT_DOCUMENT_ROOT'].'\mixniz\core\db\mixniz.php';
			$this->db = $mixniz;
			require_once $_SERVER['CONTEXT_DOCUMENT_ROOT'].'\mixniz\core\class\platform\Secure.class.php';
			$this->Secure = new Secure();
		}
		
		/*
			Log欓紀錄
			
			傳入參數
			log(data);
		*/
		function log($data) {
			if ($file = fopen($this->$prefix.'log\upload_log', 'r+')) {
				fwrite($file, time()."\t."$data."\r\n");
				fclose($file);
			}
		}
		
		
		/*
			錯誤檢查
			
			傳入參數
			check_error($_FILES['name']['error']);
		*/
		function check_error ($error) {
			switch ($error) {
				case 0:
					return true;
				case 1:
					$this->log('The uploaded file exceeds the upload_max_filesize directive in php.ini.');
					return false;
				case 2:
					$this->log('The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.');
					return false;
				case 3:
					$this->log('The uploaded file was only partially uploaded.');
					return false;
				case 4:
					$this->log('No file was uploaded.');
					return false;
				case 6:
					$this->log('Missing a temporary folder.');
					return false;
				case 7:
					$this->log('Failed to write file to disk.');
					return false;
				case 8:
					$this->log('A PHP extension stopped the file upload.');
					return false;
				default :
					$this->log('Unknown upload error.');
					return false;
			}
		}
		
		/*
			檔案處理
			1. 檢查檔案大小是否大於 0 且小於上限值，如是則回傳 false，否則則繼續
			2. 建立 hash_key 值
			3. 建立檔案名稱
			4. 取得副檔名，如無副檔名，則為 null
			5. 於資料庫建立該筆檔案的資訊
			6. 移動檔案到上傳資料夾下
			
			傳入參數
			file_save($_FILES['name']);
		*/
		function file_save ($file) {
			if ($file['size'] == 0 or $file['size'] > $max_file_size) {
				return false;
			} else {
				while (true) {
					$hash = hash('sha384', rand());
					$sql = 'SELECT `hash_key` FROM `file_upload_info` WHERE `hash_key` = :hash_key  LIMIT 1';
					$hash_check = $this->db->prepare($sql);
					$hash_check->execute(array(':hash_key'=>$hash));
					if ($hash_check->rowCount() === 1){
						$hash_check->closeCursor();
						continue;
					} else {
						$hash_check->closeCursor();
						break;
					}
				}
				while (true) {
					$file_name = hash('sha384', rand());
					$sql = 'SELECT `file_name` FROM `file_upload_info` WHERE `file_name` = :file_name  LIMIT 1';
					$file_name_check = $this->db->prepare($sql);
					$file_name_check->execute(array(':file_name'=>$file_name));
					if ($file_name_check->rowCount() === 1){
						$file_name_check->closeCursor();
						continue;
					} else {
						$file_name_check->closeCursor();
						break;
					}
				}
				if (($tmp = strrpos($file['name'], '.')) === false) {
					$file_extension_name = null;
				} else {
					//$filter_extension_name = array('php', 'html', 'htm', 'asp', 'aspx', 'js', 'css', 'phtml');
					$file_extension_name = substr($file['name'], $tmp + 1);
					/*foreach ($filter_extension_name as $temp) {
						if (strcasecmp($temp, $file_extension_name) == 0) {
							$file_extension_name = 'txt';
							break;
						}
					}*/
				}
				$sql = 'INSERT INTO `file_upload_info` (`hash_key`, `file_upload_user`, `file_name`, `file_extension_name`, `file_size`, `file_type`, `file_upload_time`, `file_upload_ip`) VALUES (:hash_key, :file_upload_user, :file_name, :file_extension_name, :file_size, :file_type, :file_upload_time, :file_upload_ip)';
				$file_add = $this->db->prepare($sql);
				$file_add->execute(array(':hash_key'=>$hash,':file_upload_user'=>$_SESSION['u']['id'],':file_name'=>$file_name,':file_extension_name'=>$file_extension_name,':file_size'=>$file['size'],':file_type'=>$file['type'],':file_upload_time'=>time(),':file_upload_ip'=>$ip));
				if ($file_add->rowCount() != 1){
					return false;
				} else {
					$dir_path = '';
					for ($i=48;$i>2;$i/=2) {
						$dir_path .= substr($file_name, 0, $i).'/';
					}
					if (!file_exists($this->$prefix.'attach/uploads/'.$dir_path)) {
						if (!mkdir($this->$prefix.'attach/uploads/'.$dir_path, true)) {
							return false;
						}
					}
					move_uploaded_file($file['tmp_name'], $this->$prefix.'attach/uploads/'.$dir_path.$file_name);
					return true;
				}
			}
		}
	}
?>