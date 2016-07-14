<?php
	class Oracle{

		private $db;

		public function __construct(){
			$this->db = new DataBase();
			$this->db->connect();
		}

		public function get_item($id = null){
			$sql   = "SELECT * FROM item";
			$array = array();
			if(!empty($id)){
				$sql .= " WHERE id = ?";
			}
			$rs  = DataBase::$pdo->prepare($sql);
			$rs->bindParam(1, $id);
			try{
				if($rs->execute()){
					if($rs->rowCount() > 0){
						while($data = $rs->fetch(PDO::FETCH_OBJ)){
							$array[]['name'] = $data->name;
						}
					}
				}
			}
			catch (PDOException $e) {
				die(DataBase::error($e, $sql));
			}
			echo json_encode($array);
		}

		public function save_item($post, $id = null){
			$array = array();
			if(empty($id)){
				$sql = "INSERT INTO `item` (`id`, `name`) VALUES (NULL, ?)";
				$rs = DataBase::$pdo->prepare($sql);
				$rs->bindParam(1, $post['name']);
				try{
					$rs->execute();
				}
				catch (PDOException $e) {
					die(DataBase::error($e, $sql));
				}
			}
			else{
				$sql = "UPDATE `item` SET name = ? WHERE id = ?";
				$rs = DataBase::$pdo->prepare($sql);
				$rs->bindParam(1, $post['name']);
				$rs->bindParam(2, $id);
				try{
					$rs->execute();
				}
				catch (PDOException $e) {
					die(DataBase::error($e, $sql));
				}
			}
			$array['success'] = 'true';
			echo json_encode($array);
		}

		public function delete_item($id){
			$sql = "DELETE FROM `item` WHERE id = ?";
			$rs = DataBase::$pdo->prepare($sql);
			$rs->bindParam(1, $id);
			try{
				$rs->execute();
			}
			catch (PDOException $e) {
				die(DataBase::error($e, $sql));
			}
			$array['success'] = 'true';
			echo json_encode($array);
		}
	}
?>