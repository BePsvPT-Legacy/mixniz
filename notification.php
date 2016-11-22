<?php
	class notification extends db {
		
		function __construct() {
			require_once $_SERVER['CONTEXT_DOCUMENT_ROOT'].'\mixniz\core\db\mixniz.php';
			$this->db = $mixniz;
			require_once $_SERVER['CONTEXT_DOCUMENT_ROOT'].'\mixniz\core\class\platform\Secure.class.php';
			$this->Secure = new Secure();
		}
		
		public function check() {
			$sql = "SELECT `hash`, `from_uid`, `content`, `time` FROM `notification` WHERE `to_uid` = :to_uid AND `is_read` = :is_read";
			$params = array(
				':to_uid' => $_SESSION['u']['id'],
				':is_read' => false
			);
			$stmt = $this->db->prepare($sql);
			$stmt->execute($params);
			return $stmt->fetchAll();
		}
		
		public function set_read($hash, $time) {
			$sql = "UPDATE `notification` SET `is_read` = :is_read WHERE `to_uid` = :to_uid AND `hash` = :hash";
			$params = array(
				':to_uid' => $_SESSION['u']['id'],
				':hash' => $hash
			);
			$stmt = $this->db->prepare($sql);
			$stmt->execute($params);
			return  true;
		}
		
		public function add($to_uid, $content) {
			$hash = hash('md5', rand());
			$sql = "INSERT INTO `notification` (`hash`, `from_uid`, `to_uid`, `content`, `time`) ";
			$sql .= "VALUES (:hash, :from_uid, :to_uid, :content, :time)";
			$params = array(
				':hash' => $hash,
				':from_uid' => $_SESSION['u']['id'],
				':to_uid' => $to_uid,
				':content' => $content,
				':time' => time()
			);
			$stmt = $this->db->prepare($sql);
			$stmt->execute($params);
			if ($stmt->rowCount() == 1){
				return true;
			}
			return false;
		}
	}
?>