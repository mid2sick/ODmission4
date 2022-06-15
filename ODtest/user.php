<?php
require_once('conn.php');
require_once('doc.php');

class User
{
	private $id;
	private $last_err;
	private $production_mode; // TODO this should be in config file
	//private $session_id; future work: sync data among multiple user devices

	function __construct($user_name)
	{
		$this->id = $this->getUserId($user_name);
	}

	private function getUserId($user_name)
	{
		$user_id = null;

		try {
			$conn = getConnection();

			$stmt = $conn->prepare('
					INSERT IGNORE INTO OD_User (Username)
					VALUES (:Username)
				');
			$stmt->bindParam(':Username', $user_name, PDO::PARAM_STR);
			$stmt->execute();

			$stmt = null;

			$stmt = $conn->prepare('
					SELECT ID
					FROM OD_User
					WHERE Username = :Username
				');
			$stmt->bindParam(':Username', $user_name, PDO::PARAM_STR);
			$stmt->execute();
			$user_id = $stmt->fetch();

			$stmt = null;
			$conn = null;
		} catch (PDOException $e) {
			$this->setError('getUserID', $e);
		}

		return $user_id;
	}

	/**
	 * Add a dir to current user.
	 *
	 * @param   string  $dir_name
	 * @return  bool    $success_or_not
	 */
	function addDir($dir_name)
	{
		$result = false;

		try {
			$conn = getConnection();

			$stmt = $conn->prepare('
					INSERT INTO User_Dir (Owner_ID, Name)
					VALUES (:Owner_ID, :Dir_Name)
				');
			$stmt->bindParam(':Owner_ID', $this->id, PDO::PARAM_INT);
			$stmt->bindParam(':Dir_Name', $dir_name, PDO::PARAM_STR);
			$result = $stmt->execute();

			$stmt = null;
			$conn = null;
		} catch (PDOException $e) {
			$this->setError('addDir', $e);
		}

		return $result;
	}

	/**
	 * List all dirs under current user.
	 *
	 * @return  string[]    $dir_names
	 */
	function listDirs()
	{
		$dir_names = null;

		try {
			$conn = getConnection();

			$stmt = $conn->prepare('
					SELECT Name
					FROM User_Dir
					WHERE Owner_ID = :Owner_ID
				');
			$stmt->bindParam(':Owner_ID', $this->id, PDO::PARAM_INT);
			$stmt->execute();
			$dir_names = $stmt->fetchAll(PDO::FETCH_COLUMN);

			$stmt = null;
			$conn = null;
		} catch (PDOException $e) {
			$this->setError('listDirs', $e);
		}

		return $dir_names;
	}

	/**
	 * Remove a dir under current user.
	 *
	 * @param   string  $dir_name
	 * @return  bool    $success_or_not
	 */
	function removeDir($dir_name)
	{
		$result = false;

		try {
			$conn = getConnection();

			$stmt = $conn->prepare('
					DELETE FROM User_Dir
					WHERE Owner_ID = :Owner_ID
						AND Name = :Dir_Name
				');
			$stmt->bindParam(':Owner_ID', $this->id, PDO::PARAM_INT);
			$stmt->bindParam(':Dir_Name', $dir_name, PDO::PARAM_STR);
			$result = $stmt->execute();

			$stmt = null;
			$conn = null;
		} catch (PDOException $e) {
			$this->setError('removeDir', $e);
		}

		return $result;
	}

	/**
	 * Update a dir under current user.
	 *
	 * @param   string  $dir_name
	 * @param   array   $col_val_pairs
	 * @return  bool    $success_or_not
	 *
	 * col_val_pairs: array(
	 *     column_name_1 => value_1,
	 *     column_name_2 => value_2,
	 *     ...
	 * )
	 *
	 * This function should be wrapped and the caller is responsibile
	 * for determining whether the column is appropriate to be updated.
	 * For example, maintain a whitelist and never put ID inside.
	 */
	private function updateDir($dir_name, $col_val_pairs)
	{
		$result = false;

		if (!$this->production_mode) {
			$BLACKLIST = array('ID', 'Owner_ID');

			$update_cols = array_keys($col_val_pairs);
			foreach ($update_cols as $update_col) {
				foreach ($BLACKLIST as $e) {
					if ($update_col == $e) {
						echo '[User::updateDir] Error: ' .
							"improper column '$update_col'";
						return $result;
					}
				}
			}
		}

		$update_exps = array();
		$update_vals = array();
		foreach ($col_val_pairs as $col => $val) {
			$update_exps[] = $col . ' = ?';
			$update_vals[] = $val;
		}

		try {
			$conn = getConnection();

			$stmt = $conn->prepare('
					UPDATE User_Dir
					SET ' . implode(',', $update_exps) . '
					WHERE Owner_ID = :Owner_ID
						AND Name = :Dir_Name
				');
			$stmt->bindParam(':Owner_ID', $this->id, PDO::PARAM_INT);
			$stmt->bindParam(':Dir_Name', $dir_name, PDO::PARAM_STR);
			$result = $stmt->execute($update_vals);

			$stmt = null;
			$conn = null;
		} catch (PDOException $e) {
			$this->setError('updateDir', $e);
		}

		return $result;
	}

	/**
	 * Rename a dir under current user.
	 *
	 * @param   string  $dir_name
	 * @param   string  $new_name
	 * @return  bool    $success_or_not
	 *
	 * This also servse as an example to use update function.
	 */
	function renameDir($dir_name, $new_name)
	{
		return $this->updateDir($dir_name, array(
			'Name' => $new_name,
		));
	}

	/**
	 * Add multiple metadata to a dir as docs.
	 *
	 * @param   string  $dir_name
	 * @param   int[]   $metadata_ids
	 * @return  bool    $success_or_not
	 */
	function addDocs($dir_name, $metadata_ids)
	{
		$result = false;

		try {
			$conn = getConnection();

			$stmt = $conn->prepare('
					SELECT ' . Doc::getMetadataSelectExprs() . '
					FROM metadata2
					WHERE id IN (:IDs)
				');
			$stmt->bindParam(':IDs', $metadata_ids, PDO::PARAM_STR);
			$stmt->execute();
			$docs = $stmt->fetchAll();

			$stmt = null;

			$stmt = $conn->prepare('
					SELECT ID
					FROM User_Dir
					WHERE Owner_ID = :Owner_ID
						AND Name = :Dir_Name
				');
			$stmt->bindParam(':Owner_ID', $this->id, PDO::PARAM_INT);
			$stmt->bindParam(':Dir_Name', $dir_name, PDO::PARAM_STR);
			$stmt->execute();
			$dir_id = $stmt->fetch(PDO::FETCH_COLUMN);

			$stmt = null;

			$placeholders = function ($text, $count = 0, $separator = ',') {
				$result = array();

				for ($i = 0; $i < $count; $i++)
					$result[] = $text;

				return implode($separator, $result);
			};

			$question_marks = array();
			$insert_values = array();
			foreach ($docs as $doc) {
				$insert_value = array('DIR_ID' => $dir_id);
				$insert_value = array_merge($insert_value, $doc);
				$question_marks[] = '(' . $placeholders('?', sizeof($insert_value)) . ')';
				$insert_values = array_merge($insert_values, array_values($insert_value));
			}

			$stmt = $conn->prepare('
				INSERT INTO Dir_Doc (DIR_ID,' . implode(',', Doc::getFields()) . ')
				VALUES ' . implode(',', $question_marks)
			);
			$result = $stmt->execute($insert_values);

			$stmt = null;
			$conn = null;
		} catch (PDOException $e) {
			$this->setError('addDocs', $e);
		}

		return $result;
	}

	/**
	 * List all docs under a dir.
	 *
	 * @param   string  $dir_name
	 * @return  doc[]   $docs
	 *
	 * $doc = array(
	 *     'ID' => int
	 *     'Metadata_ID' => int
	 *     '來源系統' => string
	 *     '來源系統縮寫' => string
	 *     '題名' => string
	 *     '摘要' => string
	 *     '類目階層' => string
	 *     '原始時間記錄' => string
	 *     '西元年' => int
	 *     '起始時間' => date
	 *     '結束時間' => date
	 *     '典藏號' => string
	 *     '文件原系統頁面URL' => string
	 *     'Original' => string
	 *     '相關人員' => string
	 *     '相關地點' => string
	 *     '相關組織' => string
	 *     '關鍵詞' => string
	 * )
	 *
	 * Use `new DateTime($value)` and its methods if you want to
	 * manipulate `date` type data.
	 */
	function listDocs($dir_name)
	{
		$docs = null;

		try {
			$conn = getConnection();

			$stmt = $conn->prepare('
					SELECT Dir_Doc.*
					FROM User_Dir, Dir_Doc
					WHERE User_Dir.Owner_ID = :Owner_ID
						AND User_Dir.Name = :Dir_Name
						AND Dir_Doc.Dir_ID = User_Dir.ID
				');
			$stmt->bindParam(':Owner_ID', $this->id, PDO::PARAM_INT);
			$stmt->bindParam(':Dir_Name', $dir_name, PDO::PARAM_STR);
			$stmt->execute();
			$docs = $stmt->fetchAll();

			$stmt = null;
			$conn = null;
		} catch (PDOException $e) {
			$this->setError('listDocs', $e);
		}

		foreach ($docs as $doc) {
			unset($doc['Dir_ID']);
		}

		return $docs;
	}

	/**
	 * Remove a doc.
	 *
	 * @param   int     $doc_id
	 * @return  bool    $success_or_not
	 */
	function removeDoc($doc_id)
	{
		$result = false;

		try {
			$conn = getConnection();

			$stmt = $conn->prepare('
					DELETE FROM Dir_Doc
					WHERE ID = :ID
				');
			/* PDO does not support binding a BIGINT parameter. */
			$stmt->bindParam(':ID', $doc_id, PDO::PARAM_STR, 21);
			$result = $stmt->execute();

			$stmt = null;
			$conn = null;
		} catch (PDOException $e) {
			$this->setError('removeDoc', $e);
		}

		return $result;
	}

	/**
	 * Update a doc.
	 *
	 * @param   int     $doc_id
	 * @param   array   $col_val_pairs
	 * @return  bool    $success_or_not
	 *
	 * col_val_pairs: array(
	 *     column_name_1 => value_1,
	 *     column_name_2 => value_2,
	 *     ...
	 * )
	 *
	 * This function should be wrapped and the caller is responsibile
	 * for determining whether the column is appropriate to be updated.
	 * For example, maintain a whitelist and never put ID inside.
	 */
	private function updateDoc($doc_id, $col_val_pairs)
	{
		$result = false;

		if (!$this->production_mode) {
			$BLACKLIST = array('ID', 'Dir_ID', 'Metadata_ID');

			$update_cols = array_keys($col_val_pairs);
			foreach ($update_cols as $update_col) {
				foreach ($BLACKLIST as $e) {
					if ($update_col == $e) {
						echo '[User::updateDoc] Error: ' .
							"improper column '$update_col'";
						return $result;
					}
				}
			}
		}

		$update_exps = array();
		$update_vals = array();
		foreach ($col_val_pairs as $col => $val) {
			$update_exps[] = $col . ' = ?';
			$update_vals[] = $val;
		}

		try {
			$conn = getConnection();

			$stmt = $conn->prepare('
					UPDATE Dir_Doc
					SET ' . implode(',', $update_exps) . '
					WHERE ID = :ID
				');
			/* PDO does not support binding a BIGINT parameter. */
			$stmt->bindParam(':ID', $doc_id, PDO::PARAM_STR, 21);
			$result = $stmt->execute($update_vals);

			$stmt = null;
			$conn = null;
		} catch (PDOException $e) {
			$this->setError('updateDoc', $e);
		}

		return $result;
	}

	/**
	 * Sync the content of the document with original metadata.
	 * Note that this will overwrite the content.
	 *
	 * @param   int     $doc_id
	 * @return  bool    $success_or_not
	 *
	 * This also servse as an example to use update function.
	 */
	function syncDocWithMetadata($doc_id)
	{
		try {
			$conn = getConnection();

			$stmt = $conn->prepare('
					SELECT metadata2.*
					FROM metadata2, Dir_Doc
					WHERE Dir_Doc.ID = :Doc_ID
						AND metadata2.id = Dir_Doc.Metadata_ID
				');
			/* PDO does not support binding a BIGINT parameter. */
			$stmt->bindParam(':Doc_ID', $doc_id, PDO::PARAM_STR, 21);
			$stmt->execute();
			$metadata = $stmt->fetch();

			$stmt = null;
			$conn = null;
		} catch (PDOException $e) {
			$this->setError('syncDocWithMetadata', $e);
		}

		return $this->updateDoc($doc_id, Doc::metadata2doc($metadata));
	}

	private function setError($location, $exception)
	{
		/*
		 * For debugging only, when a method might fail for various reasons,
		 * the script is supposed to proceed based on error code instead.
		 */
		if (!$this->production_mode) {
			echo "[User::$location] Error " . $exception->getCode()
				. ": " . $exception->getMessage();
		}
		$this->last_err = (int) $exception->getCode();
	}

	function getError()
	{
		return $this->last_err;
	}
}
