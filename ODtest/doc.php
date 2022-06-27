<?php

class Doc
{
	private static $metadata2doc_mapping = array(
		'id' => 'Metadata_ID',
		'來源系統' => '來源系統',
		'來源系統縮寫' => '來源系統縮寫',
		'題名' => '題名',
		'摘要' => '摘要',
		'類目階層' => '類目階層',
		'原始時間記錄' => '原始時間記錄',
		'西元年' => '西元年',
		'起始時間' => '起始時間',
		'結束時間' => '結束時間',
		'典藏號' => '典藏號',
		'文件原系統頁面URL' => '文件原系統頁面URL',
		'爬蟲Original' => 'Original',
		'相關人員' => '相關人員',
		'相關地點' => '相關地點',
		'相關組織' => '相關組織',
		'關鍵詞' => '關鍵詞',
	);

	/**
	 * Adjust names of the fields and ignore the fields we don't care about.
	 *
	 * @param   array   $metadata
	 * @return  array   $doc
	 *
	 * $metadata is the raw query result from table `metadata2`.
	 */
	static function metadata2doc($metadata)
	{
		$doc = array();

		foreach (Doc::$metadata2doc_mapping as $metadata_key => $doc_key) {
			$doc[$doc_key] = $metadata[$metadata_key];
		}

		return $doc;
	}

	/**
	 * Get field names, specifically mapped from original metadata, of the
	 * document.
	 *
	 * @return  string[]    $field_names
	 */
	static function getFields()
	{
		return array_values(Doc::$metadata2doc_mapping);
	}

	/**
	 * Get expressions for SELECT clause of SQL when querying metadata table.
	 *
	 * @return  string  $query_metadata_select_exprs
	 */
	static function getMetadataSelectExprs()
	{
		$select_expr = array();

		foreach (Doc::$metadata2doc_mapping as $metadata_key => $doc_key) {
			if ($metadata_key == $doc_key)
				$select_expr[] = $doc_key;
			else
				$select_expr[] = $metadata_key . ' AS ' . $doc_key;
		}

		return implode(',', $select_expr);
	}
}
