<?php
// TODO
// Assumption: user_id and doc_id are well defined in other tables,
// and their datatypes are string and integer, respectively.
class User
{
	private $id;
	private $last_err;
	private $production_mode; // TODO this should be in config file
	//private $session_id; future work

	function __construct($user_id)
	{
		$this->id = $user_id;
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
			$stmt->bindParam(':Owner_ID', $this->id, PDO::PARAM_STR);
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
	 * @return  string[]    $user_dirs
	 */
	function listDirs()
	{
		$user_dirs = null;

		try {
			$conn = getConnection();

			$stmt = $conn->prepare('
					SELECT Name
					FROM User_Dir
					WEHRE Owner_ID = :Owner_ID
				');
			$stmt->bindParam(':Owner_ID', $this->id, PDO::PARAM_STR);
			$stmt->execute();
			$user_dirs = $stmt->fetchAll(PDO::FETCH_COLUMN);

			$stmt = null;
			$conn = null;
		} catch (PDOException $e) {
			$this->setError('listDirs', $e);
		}

		return $user_dirs;
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
			$stmt->bindParam(':Owner_ID', $this->id, PDO::PARAM_STR);
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
	 * Add multiple docs to a dir. If a doc found existing, its addition
	 * will be discarded. 
	 *
	 * @param   string  $dir_name
	 * @param   int[]   $doc_ids
	 * @return  bool    $success_or_not
	 */
	function addDocs($dir_name, $doc_ids)
	{
		$result = false;

		try {
			$conn = getConnection();

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

			$placeholders = function ($text, $count = 0, $separator = ',') {
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

			$stmt = $conn->prepare(
				'
				INSERT IGNORE INTO Dir_Doc (Dir_ID, Doc_ID)
				VALUES ' . implode(',', $question_marks)
			);
			$result = $stmt->execute($insert_values);

			$stmt = null;
			$conn = null;
		} catch (PDOException $e) {
			$this->setError('addDoc', $e);
		}

		return $result;
	}

	/**
	 * List all docs under a dir.
	 *
	 * @param   string  $dir_name
	 * @return  int[]   $doc_ids
	 */
	function listDocs($dir_name)
	{
		$docs = null;

		try {
			$conn = getConnection();

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
			$docs = $stmt->fetchAll(PDO::FETCH_COLUMN);

			$stmt = null;
			$conn = null;
		} catch (PDOException $e) {
			$this->setError('listDocs', $e);
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
	function removeDoc($dir_name, $doc_id)
	{
		$result = false;

		try {
			$conn = getConnection();

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
		} catch (PDOException $e) {
			$this->setError('removeDoc', $e);
		}

		return $result;
	}

	private function setError($location, $exception)
	{
		/*
		 * For debugging only, when a method might fail for various reasons,
		 * the script is supposed to proceed based on error code.
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
