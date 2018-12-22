<?php

class Language {

	public function __construct($params) {
		// I18N support information here
		//$language = "es_SV";
		$language = $params['language'];
		putenv("LANG=" . $language);
		setlocale(LC_ALL, $language);
		// Set the text domain as "messages"
		$domain = $language;
		$filename = APPPATH."language/locale/$domain/LC_MESSAGES/$domain.mo";
		$mtime = filemtime($filename);
		$filename_new = APPPATH."language/locale/$domain/LC_MESSAGES/{$domain}_{$mtime}.mo";

		if (!file_exists($filename_new)){
			$dir = scandir(dirname($filename));
			foreach ($dir as $file) {
				if (in_array($file, array('.','..', "{$domain}.po", "{$domain}.mo"))) continue;
				unlink(dirname($filename).DIRECTORY_SEPARATOR.$file);
			}
			copy($filename, $filename_new);
		}
		$domain_new = "{$domain}_{$mtime}";
		bindtextdomain($domain_new, APPPATH."language/locale");
		bind_textdomain_codeset($domain_new, 'UTF-8');

		textdomain($domain_new);

	}

}
