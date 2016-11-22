<?php
	class vote {
		
		function __construct() {
			require_once $_SERVER['CONTEXT_DOCUMENT_ROOT'].'\mixniz\core\db\mixniz.php';
			$this->db = $mixniz;
			require_once $_SERVER['CONTEXT_DOCUMENT_ROOT'].'\mixniz\core\class\platform\Secure.class.php';
			$this->Secure = new Secure();
		}
		
		/*
			新增投票
			1.	建立 vote_hash_key(sh1)
			2.	建立 vote_option_id
			3.	新增資料
			
			參數說明
			add_vote(社團ID, 是否複選(true|false), 敘述, 截止時間(null|time));
		*/
		function add_vote ($bid, $multiple, $content, $deadline) {
			while (true) {
				$hash = hash('sha1', rand());
				$sql = 'SELECT `do_vote_id` FROM `do_vote` WHERE `vote_hash_key` = :vote_hash_key  LIMIT 1';
				$hash_check = $this->db->prepare($sql);
				$hash_check->execute(array(':vote_hash_key'=>$hash));
				if ($hash_check->rowCount() === 1){
					$hash_check->closeCursor();
					continue;
				} else {
					$hash_check->closeCursor();
					break;
				}
			}
			while (true) {
				$vote_option_id = rand(1, 2147483647);
				$sql = 'SELECT `id` FROM `do_vote_option` WHERE `vote_option_id` = :vote_option_id LIMIT 1';
				$id_check = $this->db->prepare($sql);
				$id_check->execute(array(':vote_option_id'=>$vote_option_id));
				if ($id_check->rowCount() === 1){
					$id_check->closeCursor();
					continue;
				} else {
					$id_check->closeCursor();
					break;
				}
			}
			$sql = 'INSERT INTO `do_vote` (`vote_hash_key`, `vote_bid`, `vote_multiple`, `vote_option_id`, `vote_content`, `vote_deadline`, `cvote_time`, `vote_ip`) VALUES (:vote_hash_key, :vote_bid, :vote_multiple, :vote_option_id, :vote_content, :vote_delete, :vote_deadline, :vote_last_modify_time, :vote_last_modify_ip, :cvote_time, :vote_ip)';
			$add_vote = $this->db->prepare($sql);
			$add_vote->execute(array(':vote_hash_key'=>$hash,':vote_bid'=>$bid,':vote_multiple'=>$multiple,':vote_option_id'=>$vote_option_id,':vote_content'=>$content,':vote_deadline'=>$deadline,':cvote_time'=>time(),':vote_ip'=>$ip));
			return ($add_vote->rowCount() === 1) ? true : false;
		}
		
		
		/*
			更新投票	// 只允許更改投票敘述
			1.	更新資料
			
			參數說明
			update_vote(社團ID, 投票 hash_key 值, 敘述);
		*/
		function update_vote ($bid, $vote_hash_key, $content) {
			$sql = 'UPDATE `do_vote` SET `vote_content` = :vote_content, `vote_last_modify_time` = :vote_last_modify_time, `vote_last_modify_ip` = :vote_last_modify_ip WHERE `vote_bid` = :vote_bid AND `vote_hash_key` = :vote_hash_key';
			$update_vote = $this->db->prepare($sql);
			$update_vote->execute(array(':vote_content'=>$content,':vote_last_modify_time'=>time(),':vote_last_modify_ip'=>$ip,':vote_bid'=>$bid,':vote_hash_key'=>$vote_hash_key));
			return ($update_vote->rowCount() === 1) ? true : false;
		}
		
		
		/*
			刪除投票
			1.	刪除資料
			
			參數說明
			delete_vote(社團ID, 投票 hash_key 值);
		*/
		function delete_vote ($bid, $vote_hash_key) {
			$sql = 'UPDATE `do_vote` SET `vote_delete` = :vote_delete, `vote_last_modify_time` = :vote_last_modify_time, `vote_last_modify_ip` = :vote_last_modify_ip WHERE `vote_bid` = :vote_bid AND `vote_hash_key` = :vote_hash_key';
			$delete_vote = $this->db->prepare($sql);
			$delete_vote->execute(array(':vote_delete'=>true,':vote_last_modify_time'=>time(),':vote_last_modify_ip'=>$ip,':vote_bid'=>$bid,':vote_hash_key'=>$vote_hash_key));
			return ($delete_vote->rowCount() === 1) ? true : false;
		}
		
		
		/*
			新增投票選項
			1. 查詢此投票之專屬選項ID
			2. 建立 vote_option_hash_key(md5)
			3. 新增資料
			
			參數說明
			add_vote_option(社團ID, 投票 hash_key 值, 選項內容);
		*/
		function add_vote_option ($bid, $vote_hash_key, $content) {
			$sql = 'SELECT `vote_option_id` FROM `do_vote` WHERE `vote_bid` = :vote_bid AND `vote_hash_key` = :vote_hash_key';
			$vote_option_id_get = $this->db->prepare($sql);
			$vote_option_id_get->execute(array(':vote_bid'=>$bid,':vote_hash_key'=>$vote_hash_key));
			if ($hash_check->rowCount() !== 1){
				return false;
			} else {
				$getVoteData = $vote_option_id_get->fetch();
				$vote_option_id_get->closeCursor();
			}
			while (true) {
				$hash = hash('md5', rand());
				$sql = 'SELECT `id` FROM `do_vote_option` WHERE `vote_option_id` = :vote_option_id AND `vote_option_hash_key` = :vote_option_hash_key  LIMIT 1';
				$hash_check = $this->db->prepare($sql);
				$hash_check->execute(array(':vote_option_id'=>$getVoteData['vote_option_id'],':vote_option_hash_key'=>$hash));
				if ($hash_check->rowCount() === 1){
					$hash_check->closeCursor();
					continue;
				} else {
					$hash_check->closeCursor();
					break;
				}
			}
			$sql = 'INSERT INTO `do_vote_option` (`vote_option_id`, `vote_option_hash_key`, `vote_option_content`) VALUES (:vote_option_id, :vote_option_hash_key, :vote_option_content)';
			$add_vote_option = $this->db->prepare($sql);
			$add_vote_option->execute(array(':vote_option_id'=>$getVoteData['vote_option_id'],':vote_option_hash_key'=>$hash,':vote_option_content'=>$content));
			return ($add_vote_option->rowCount() === 1) ? true : false;
		}
		
		
		/*
			更新投票選項
			1.	查詢此投票之專屬選項ID
			2.	更新資料
			
			參數說明
			update_vote_option(社團ID, 投票 vote_hash_key 值, 選項 vote_option_hash_key 值, 敘述);
		*/
		function update_vote_option ($bid, $vote_hash_key, $vote_option_hash_key, $content) {
			$sql = 'SELECT `vote_option_id` FROM `do_vote` WHERE `vote_bid` = :vote_bid AND `vote_hash_key` = :vote_hash_key';
			$vote_option_id_get = $this->db->prepare($sql);
			$vote_option_id_get->execute(array(':vote_bid'=>$bid,':vote_hash_key'=>$vote_hash_key));
			if ($hash_check->rowCount() !== 1){
				return false;
			} else {
				$getVoteData = $vote_option_id_get->fetch();
				$vote_option_id_get->closeCursor();
			}
			$sql = 'UPDATE `do_vote_option` SET `vote_option_content` = :vote_option_content WHERE `vote_option_id` = :vote_option_id AND `vote_option_hash_key` = :vote_option_hash_key';
			$update_vote_option = $this->db->prepare($sql);
			$update_vote_option->execute(array(':vote_option_content'=>$content,':vote_option_id'=>$getVoteData['vote_option_id'],':vote_option_hash_key'=>$vote_option_hash_key));
			return ($update_vote_option->rowCount() === 1) ? true : false;
		}
		
		
		/*
			刪除投票
			1.	查詢此投票之專屬選項ID
			2.	刪除資料
			
			參數說明
			delete_vote_option(社團ID, 投票 hash_key 值, 選項 vote_option_hash_key 值);
		*/
		function delete_vote_option ($bid, $vote_hash_key, $vote_option_hash_key) {
			$sql = 'SELECT `vote_option_id` FROM `do_vote` WHERE `vote_bid` = :vote_bid AND `vote_hash_key` = :vote_hash_key';
			$vote_option_id_get = $this->db->prepare($sql);
			$vote_option_id_get->execute(array(':vote_bid'=>$bid,':vote_hash_key'=>$vote_hash_key));
			if ($hash_check->rowCount() !== 1){
				return false;
			} else {
				$getVoteData = $vote_option_id_get->fetch();
				$vote_option_id_get->closeCursor();
			}
			$sql = 'UPDATE `do_vote_option` SET `vote_option_delete` = :vote_option_delete WHERE `vote_option_id` = :vote_option_id AND `vote_option_hash_key` = :vote_option_hash_key';
			$delete_vote_option = $this->db->prepare($sql);
			$delete_vote_option->execute(array(':vote_option_delete'=>true,':vote_option_id'=>$getVoteData['vote_option_id'],':vote_option_hash_key'=>$vote_option_hash_key));
			return ($delete_vote_option->rowCount() === 1) ? true : false;
		}
		
		
		/*
			投票選項處理
			1.	判斷此是否有此投票，如否則回傳 false，如是則繼續
			2.	判斷此投票是否已刪除，如是則回傳 false，如否則繼續
			3.	判斷此選項是否有時間限制，如有且已超過時間則回傳 false，否則繼續
			4.	判斷是否為複選投票，分開處理， 5 為複選 6 為單選
			5.1	判斷是否有此選項，如否則回傳 false，如是則繼續
			5.2	對選項做處理，如為空值，則直接增加；如無投過，則於尾端加上；如已投過，則取消之
			6.1	判斷是否存在選項，如否則回傳 false，如是則繼續
			6.2	以迴圈方式掃過所有選項，判斷先前是否有坄過，如有則取消之，並紀錄欲投選項之資料
			6.3	對選項做處理，如為空值，則直接增加；如無投過，則於尾端加上；如已投過，則取消之
			7.	進行處理並回傳投票結果，成功為 true，失敗為 false
			
			參數說明
			deal_vote_option(社團ID, 投票 hash_key 值, 選項 hash_key 值);
		*/
		function deal_vote_option ($bid, $vote_hash_key, $vote_option_hash_key) {
			$sql = 'SELECT `vote_multiple`, `vote_option_id`, `vote_delete`, `vote_deadline` FROM `do_vote` WHERE `vote_bid` = :bid AND `vote_hash_key` = :vote_hash_key';
			$vote = $this->db->prepare($sql);
			$vote->execute(array(':bid'=>$bid, ':vote_hash_key'=>$vote_hash_key));
			if ($vote->rowCount() !== 1){
				$vote->closeCursor();
				return false;
			} else {
				$getVoteData = $vote->fetch();
				$vote->closeCursor();
				if ($getVoteData['vote_delete'] == true) {
					return false;
				} else {
					if ($getVoteData['vote_deadline'] != null and time() > $getVoteData['vote_deadline']) {
						return false;
					} else {
						if ($getVoteData['vote_multiple']) {
							$sql = 'SELECT `vote_option_voted_user` FROM `do_vote_option` WHERE `vote_option_id` = :vote_option_id AND `vote_option_hash_key` = :vote_option_hash_key AND `vote_option_delete` = :vote_option_delete';
							$option = $this->db->prepare($sql);
							$option->execute(array(':vote_option_id'=>$getVoteData['vote_option_id'], ':vote_option_hash_key'=>$vote_option_hash_key, ':vote_option_delete'=>false));	
							if ($option->rowCount() !== 1) {
								return false;
							} else {
								$getOptionData = $option->fetch();
								$option->closeCursor();
								if ($getOptionData['vote_option_voted_user'] == null) {
									$stmt = $_SESSION['u']['id'].',';
								} else if (stripos($getOptionData['vote_option_voted_user'], $_SESSION['u']['id']) === false) {
									$stmt = $getOptionData['vote_option_voted_user'].$_SESSION['u']['id'].',';
								} else {
									$stmt = str_replace($_SESSION['u']['id'].',', '', $getOptionData['vote_option_voted_user']);
									if (strlen($stmt) == 0) {
										$stmt = null;
									}
								}
							}
						} else {
							$sql = 'SELECT `vote_option_voted_user`, `vote_option_hash_key` FROM `do_vote_option` WHERE `vote_option_id` = :vote_option_id AND `vote_option_delete` = :vote_option_delete';
							$option = $this->db->prepare($sql);
							$option->execute(array(':vote_option_id'=>$getVoteData['vote_option_id'], ':vote_option_delete'=>false));
							if (!($option->rowCount() > 0)) {
								return false;
							} else {
								$find_voted = false;
								while ($getOptionData = $option->fetch()) {
									if (stripos($getOptionData['vote_option_voted_user'], $_SESSION['u']['id']) !== false) {
										$stmt_old = $getOptionData['vote_option_voted_user'].$_SESSION['u']['id'].',';
										$find_voted = true;
									}
									if ($getOptionData['vote_option_hash_key'] == $vote_option_hash_key) {
										$stmt = $getOptionData['vote_option_voted_user'];
									}
									if ($find_voted and isset($stmt)) {
										break;
									}
								}
								$option->closeCursor();
								if ($find_voted) {
									$sql = 'UPDATE `do_vote_option` SET `vote_option_voted_user` = :add_or_delete WHERE `vote_option_id` = :vote_option_id AND `vote_option_hash_key` = :vote_option_hash_key';
									$vote_option = $this->db->prepare($sql);
									$vote_option->execute(array(':add_or_delete'=>$stmt_old, ':vote_option_id'=>$getVoteData['vote_option_id'], ':vote_option_hash_key'=>$getOptionData['vote_option_hash_key']));
									if ($vote_option->rowCount() !== 1) {
										return false;
									}
									$vote_option->closeCursor();
								}
								if (!isset($stmt)) {
									return false;
								} else {
									if ($stmt == null) {
										$stmt = $_SESSION['u']['id'].',';
									} else if (stripos($stmt, $_SESSION['u']['id']) === false) {
										$stmt .= $_SESSION['u']['id'].',';
									} else {
										$stmt = str_replace($_SESSION['u']['id'].',', '', $stmt);
										if (strlen($stmt) == 0) {
											$stmt = null;
										}
									}
								}
							}
						}
						$sql = 'UPDATE `do_vote_option` SET `vote_option_voted_user` = :add_or_delete WHERE `vote_option_id` = :vote_option_id AND `vote_option_hash_key` = :vote_option_hash_key';
						$vote_option = $this->db->prepare($sql);
						$vote_option->execute(array(':add_or_delete'=>$stmt, ':vote_option_id'=>$getVoteData['vote_option_id'], ':vote_option_hash_key'=>$vote_option_hash_key));
						return ($vote_option->rowCount() === 1) ? true : false;
					}
				}
			}
		}
	}
?>