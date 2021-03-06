<?php
require_once('conn.php');
require_once('doc.php');

class User
{
	private $id;
	private $last_err;
	private $production_mode; // TODO This should be set
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
		} catch (PDOException $e) {
			$this->setError('getUserID', $e);
		} finally {
			$stmt = null;
			$conn = null;
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
		} catch (PDOException $e) {
			$this->setError('addDir', $e);
		} finally {
			$stmt = null;
			$conn = null;
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
		} catch (PDOException $e) {
			$this->setError('listDirs', $e);
		} finally {
			$stmt = null;
			$conn = null;
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
		} catch (PDOException $e) {
			$this->setError('removeDir', $e);
		} finally {
			$stmt = null;
			$conn = null;
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
						echo '[User::updateDir] Warning: ',
							"improper column '{$update_col}'";
						break;
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

			$stmt = $conn->prepare('
					UPDATE User_Dir
					SET ' . implode(',', $update_exps) . '
					WHERE ID = ' . $dir_id
				);
			$result = $stmt->execute($update_vals);
		} catch (PDOException $e) {
			$this->setError('updateDir', $e);
		} finally {
			$stmt = null;
			$conn = null;
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

		$question_mark = function ($val_arr) {
			return implode(',', array_fill(0, count($val_arr), '?'));
		};

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

			$fields = array_merge(array('Dir_ID'), Doc::getFields());
			$question_marks = array();
			$insert_values = array();
			foreach ($docs as $doc) {
				$insert_value = array('Dir_ID' => $dir_id);
				$insert_value = array_merge($insert_value, $doc);
				$question_marks[] = '(' . $question_mark($insert_value) . ')';
				$insert_values = array_merge($insert_values, array_values($insert_value));
			}

			$stmt = $conn->prepare('
					INSERT INTO Dir_Doc (' . implode(',', $fields) . ')
					VALUES ' . implode(',', $question_marks)
				);
			$result = $stmt->execute($insert_values);
		} catch (PDOException $e) {
			$this->setError('addDocs', $e);
		} finally {
			$stmt = null;
			$conn = null;
		}

		return $result;
	}

	/**
	 * Add multiple metadata, by their digital IDs, to a dir as docs.
	 *
	 * @param   string      $dir_name
	 * @param   string      $system_abbr
	 * @param   string[]    $digital_ids
	 * @return  bool        $success_or_not
	 */
	function addDocsByDigitalIds($dir_name, $system_abbr, $digital_ids)
	{
		$result = false;

		$question_mark = function ($val_arr) {
			return implode(',', array_fill(0, count($val_arr), '?'));
		};

		try {
			$conn = getConnection();

			$stmt = $conn->prepare('
					SELECT ' . Doc::getMetadataSelectExprs() . '
					FROM metadata2
					WHERE `??????????????????` = ?
						AND `?????????` IN (' . $question_mark($digital_ids) . ')
				');
			$stmt->execute(array_merge(array($system_abbr), $digital_ids));
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

			$fields = array_merge(array('Dir_ID'), Doc::getFields());
			$question_marks = array();
			$insert_values = array();
			foreach ($docs as $doc) {
				$insert_value = array('Dir_ID' => $dir_id);
				$insert_value = array_merge($insert_value, $doc);
				$question_marks[] = '(' . $question_mark($insert_value) . ')';
				$insert_values = array_merge($insert_values, array_values($insert_value));
			}

			$stmt = $conn->prepare('
					INSERT INTO Dir_Doc (' . implode(',', $fields) . ')
					VALUES ' . implode(',', $question_marks)
				);
			$result = $stmt->execute($insert_values);
		} catch (PDOException $e) {
			$this->setError('addDocsByDigitalIds', $e);
		} finally {
			$stmt = null;
			$conn = null;
		}

		return $result;
	}

	/**
	 * Copy a doc to a dir.
	 *
	 * @param   int     $doc_id
	 * @param   string  $dir_name
	 * @return  bool    $success_or_not
	 */
	function copyDoc($doc_id, $dir_name)
	{
		$result = false;

		$question_mark = function ($val_arr) {
			return implode(',', array_fill(0, count($val_arr), '?'));
		};

		try {
			$conn = getConnection();

			/* Make sure user has the permission to view the doc. */
			$stmt = $conn->prepare('
					SELECT Dir_Doc.ID
					FROM Dir_Doc, User_Dir
					WHERE Dir_Doc.ID = :Doc_ID
						AND User_Dir.ID = Dir_Doc.Dir_ID
						AND User_Dir.Owner_ID = :Owner_ID
				');
			/* PDO does not support binding a BIGINT parameter. */
			$stmt->bindParam(':Doc_ID', $doc_id, PDO::PARAM_STR, 21);
			$stmt->bindParam(':Owner_ID', $this->id, PDO::PARAM_INT);
			$stmt->execute();
			$doc_id = $stmt->fetch(PDO::FETCH_COLUMN);

			$stmt = null;

			$stmt = $conn->prepare('
					SELECT ' . Doc::getFields() . '
					FROM Dir_Doc
					WHERE ID = ' . $doc_id
				);
			$stmt->execute();
			$doc = $stmt->fetch();

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

			$fields = array_merge(array('Dir_ID'), Doc::getFields());
			$insert_value = array_merge(array($dir_id), array_values($doc));

			$stmt = $conn->prepare('
					INSERT INTO Dir_Doc (' . implode(',', $fields) . ')
					VALUES (' . $question_mark($doc) . ')
				');
			$result = $stmt->execute($insert_value);
		} catch (PDOException $e) {
			$this->setError('copyDoc', $e);
		} finally {
			$stmt = null;
			$conn = null;
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
	 *     '????????????' => string
	 *     '??????????????????' => string
	 *     '??????' => string
	 *     '??????' => string
	 *     '????????????' => string
	 *     '??????????????????' => string
	 *     '?????????' => int
	 *     '????????????' => date
	 *     '????????????' => date
	 *     '?????????' => string
	 *     '?????????????????????URL' => string
	 *     'Original' => string
	 *     '????????????' => string
	 *     '????????????' => string
	 *     '????????????' => string
	 *     '?????????' => string
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
		} catch (PDOException $e) {
			$this->setError('listDocs', $e);
		} finally {
			$stmt = null;
			$conn = null;
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

			/* Make sure user has the permission to view the doc. */
			$stmt = $conn->prepare('
					DELETE Dir_Doc
					FROM Dir_Doc
						INNER JOIN User_Dir ON Dir_Doc.Dir_ID = User_Dir.ID
					WHERE Dir_Doc.ID = :Doc_ID
						AND User_Dir.Owner_ID = :Owner_ID
				');
			/* PDO does not support binding a BIGINT parameter. */
			$stmt->bindParam(':Doc_ID', $doc_id, PDO::PARAM_STR, 21);
			$stmt->bindParam(':Owner_ID', $this->id, PDO::PARAM_INT);
			$result = $stmt->execute();
		} catch (PDOException $e) {
			$this->setError('removeDoc', $e);
		} finally {
			$stmt = null;
			$conn = null;
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
						echo '[User::updateDoc] Warning: ',
							"improper column '{$update_col}'";
						break;
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

			/* Make sure user has the permission to view the doc. */
			$stmt = $conn->prepare('
					SELECT Dir_Doc.ID
					FROM Dir_Doc, User_Dir
					WHERE Dir_Doc.ID = :Doc_ID
						AND User_Dir.ID = Dir_Doc.Dir_ID
						AND User_Dir.Owner_ID = :Owner_ID
				');
			/* PDO does not support binding a BIGINT parameter. */
			$stmt->bindParam(':Doc_ID', $doc_id, PDO::PARAM_STR, 21);
			$stmt->bindParam(':Owner_ID', $this->id, PDO::PARAM_INT);
			$stmt->execute();
			$doc_id = $stmt->fetch(PDO::FETCH_COLUMN);

			$stmt = null;

			$stmt = $conn->prepare('
					UPDATE Dir_Doc
					SET ' . implode(',', $update_exps) . '
					WHERE ID = ' . $doc_id
				);
			$result = $stmt->execute($update_vals);
		} catch (PDOException $e) {
			$this->setError('updateDoc', $e);
		} finally {
			$stmt = null;
			$conn = null;
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
		$metadata = null;

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
		} catch (PDOException $e) {
			$this->setError('syncDocWithMetadata', $e);
		} finally {
			$stmt = null;
			$conn = null;
		}

		if (!$metadata)
			return false;

		return $this->updateDoc($doc_id, Doc::metadata2doc($metadata));
	}

	/**
	 * Move a doc from one dir to another.
	 *
	 * @param   int     $doc_id
	 * @param   string  $dir_name
	 * @return  bool    $success_or_not
	 *
	 * This also servse as an example to use update function.
	 */
	function moveDoc($doc_id, $dir_name)
	{
		$dir_id = null;

		try {
			$conn = getConnection();

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
		} catch (PDOException $e) {
			$this->setError('moveDoc', $e);
		} finally {
			$stmt = null;
			$conn = null;
		}

		if (!$dir_id)
			return false;

		return $this->updateDoc($doc_id, array(
			'Dir_ID' => $dir_id,
		));
	}

	private function setError($location, $exception)
	{
		/*
		 * For debugging only, when a method might fail for various reasons,
		 * the script is supposed to proceed based on error code instead.
		 */
		if (!$this->production_mode) {
			echo "[User::{$location}] Error {$exception->getCode()}: ",
				$exception->getMessage();
		}
		$this->last_err = (int) $exception->getCode();
	}

	function getError()
	{
		return $this->last_err;
	}
}
