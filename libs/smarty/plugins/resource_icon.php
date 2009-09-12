<?
	/**
	* Add pretty icons to each upload reference. The icons reflects the file type.
	*
	* @param content [string] The wikipage's content data.
	*
	* @return [string] The new wikipage's content data.
	*/
	private function plugin_upload($content)
	{
		$filetypes = array();
		$filetypes['.pdf'] = "1";
		$filetypes['.htm'] = "2";
		$filetypes['.html'] = "2";
		$filetypes['.doc'] = "3";
		$filetypes['.ppt'] = "4";
		$filetypes['.zip'] = "5";

		foreach (array_keys($filetypes) as $i) {
			$pattern = "/(<upl)(\s*)(file=)(.*" . $i . ")(\s*)(\/?>)/i";
			$replacement = "$1$2$3$4 id=\"" . $filetypes[$i] . "\" $5$6";
			$content = preg_replace($pattern, $replacement, $content);
		}

		return $content;
	}
?>