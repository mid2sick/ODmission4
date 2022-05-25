<?php
	// TODO
	// Assumption: user_id and doc_id are well defined in other tables,
	// and their datatypes are string and integer, respectively.
	class User {
		private $id;
		//private $session_id; future work
		
		function __construct($user_id) {
			$this->id = $user_id;
		}
	
		/**
		 * Add a dir to current user.
		 *
		 * @param   string  $dir_name
		 * @return  bool    $success_or_not
		 */
		function add_dir($dir_name) {
			$conn = get_connection();
			
			$stmt = $conn->prepare('
				INSERT INTO User_Dir (Owner_ID, Name)
				VALUES (:Owner_ID, :Dir_Name)
			');
			$stmt->bindParam(':Owner_ID', $this->id, PDO::PARAM_STR);
			$stmt->bindParam(':Dir_Name', $dir_name, PDO::PARAM_STR);
			$result = $stmt->execute();
			
			$stmt = null;
			$conn = null;
			
			return $result;
		}
		
		/**
		 * List all dirs under current user.
		 *	
		 * @return  string[]    $user_dirs
		 */
		function list_dirs() {
			$conn = get_connection();
			
			$stmt = $conn->prepare('
				SELECT Name
				FROM User_Dir
				WEHRE Owner_ID = :Owner_ID
			');
			$stmt->bindParam(':Owner_ID', $this->id, PDO::PARAM_STR);
			$stmt->execute();
			$result = $stmt->fetchAll();

			$stmt = null;
			$conn = null;		
			
			$user_dirs = array();
			foreach ($result as $row) {
				$user_dirs[] = $row['Name'];
			}
			
			return $user_dirs;
		}
		
		/**
		 * Remove a dir under current user.
		 *
		 * @param   string  $dir_name
		 * @return  bool    $success_or_not
		 */
		function remove_dir($dir_name) {
			$conn = get_connection();
			
			$stmt = $conn->prepare('
				DELETE FROM User_Dir
				WHERE Owner_ID = :Owner_ID
					AND Name = :Dir_Name
			');
			$stmt->bindParam(':Owner_ID', $this->id, PDO::PARAM_STR);
			$stmt->bindParam(':Dir_Name', $dir_name, PDO::PARAM_STR);
			$result = $stmt->execute();
			
			$stmt = null;
			$conn = null;
			
			return $result;
		}

		/**
		 * Add multiple docs to a dir.
		 *
		 * @param   string  $dir_name
		 * @param   int[]   $doc_ids
		 * @return  bool    $success_or_not
		 */
		function add_doc($dir_name, $doc_ids) {
			//TODO Deal with duplicate doc_ids
			$conn = get_connection();
			
			$stmt = $conn->prepare('
				SELECT ID
				FROM User_Dir
				WHERE Owner_ID = :Owner_ID
					AND Name = :Dir_Name
			');
			$stmt->bindParam(':Owner_ID', $this->id, PDO::PARAM_STR);
			$stmt->bindParam(':Dir_Name', $dir_name, PDO::PARAM_STR);
			$stmt->execute();
			$dir_id = $stmt->fetch();

			$stmt = null;

			$placeholders = function ($text, $count=0, $separator=",") {
				$result = array();
				
				for ($i = 0; $i < $count; $i++)
					$result[] = $text;

				return implode($separator, $result);
			};
			
			$insert_values = array();
			foreach ($doc_ids as $doc_id) {
				$record = array($dir_id, $doc_id);
				$question_marks[] = '(' . $placeholders('?', sizeof($record)) . ')';
				$insert_values = array_merge($insert_values, array_values($record));
			}

			$stmt = $conn->prepare('
				INSERT INTO Dir_Doc (Dir_ID, Doc_ID)
			 	VALUES ' . implode(',', $question_marks)
			);
			$result = $stmt->execute($insert_values);
			
			$stmt = null;
			$conn = null;
			
			return $result;
		}

		/**
		 * List all docs under a dir.
		 *
		 * @param   string  $dir_name
		 * @return  int[]   $doc_ids
		 */
		function list_docs($dir_name) {
			$conn = get_connection();
		
			$stmt = $conn->prepare('
				SELECT Dir_Doc.Doc_ID AS Doc_ID
				FROM User_Dir, Dir_Doc
				WHERE User_Dir.Owner_ID = :Owner_ID
					AND User_Dir.Name = :Dir_Name
					AND Dir_Doc.Dir_ID = User_Dir.ID
			');
			$stmt->bindParam(':Owner_ID', $this->id, PDO::PARAM_STR);
			$stmt->bindParam(':Dir_Name', $dir_name, PDO::PARAM_STR);
			$stmt->execute();
			$result = $stmt->fetchAll();

			$stmt = null;
			$conn = null;

			$docs = array();
			foreach ($result as $row) {
				$docs[] = $row['Doc_ID'];
			}
		
			return $docs;
		}
		
		/**
		 * Remove a doc from a dir.
		 *
		 * @param   string  $dir_name
		 * @param   int     $doc_id
		 * @return  bool    $success_or_not
		 */
		function remove_doc($dir_name, $doc_id) {
			$conn = get_connection();
		
			$stmt = $conn->prepare('
				SELECT ID
				FROM User_Dir
				WHERE Owner_ID = :Owner_ID
					AND Name = :Dir_Name
			');
			$stmt->bindParam(':Owner_ID', $this->id, PDO::PARAM_STR);
			$stmt->bindParam(':Dir_Name', $dir_name, PDO::PARAM_STR);
			$stmt->execute();
			$dir_id = $stmt->fetch();

			$stmt = null;

			$stmt = $conn->prepare('
				DELETE FROM Dir_Doc
				WHERE Dir_ID = :Dir_ID
					AND Doc_ID = :Doc_ID
			');
			$stmt->bindParam(':Dir_ID', $dir_id, PDO::PARAM_INT);
			$stmt->bindParam(':Doc_ID', $doc_id, PDO::PARAM_INT);
			$result = $stmt->execute();
				
			$stmt = null;
			$conn = null;
			
			return $result;
		}
	}
?>