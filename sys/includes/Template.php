<?php 
class Template {
	private static $pattern = "/\{\{([\w]+)->([\w]+)\}\}/";
	private static $file = null;
	private static $app = null;
	private static function _parse() {
		global $config,$global;
		ob_start();
		include self::$file;
		$content = ob_get_clean();
		$app = self::$app;
		return preg_replace_callback(self::$pattern, function($e) use ($app,$config,$global){
			$data[] = array();
			if(is_object($app)){
				foreach ($app as $property => $value) {
					$data['app'][$property] = $value;
				}
			}
			foreach ($config as $property => $value) {
				$data['config'][$property] = $value;
			}
			foreach ($global as $property => $value) {
				$data['global'][$property] = $value;
			}
			switch($e[1]) {
				case "app":
				if (isset($data['app'][$e[2]])) {
					return $data['app'][$e[2]];
				}
				break;
				case "config":
				if (isset($data['config'][$e[2]])) {
					return $data['config'][$e[2]];
				}
				break;
				case "me":
					if (isset($data['global']['me'][$e[2]])) {
						return $data['global']['me'][$e[2]];
					}
					break;
				case "global":
					if (isset($data['global'][$e[2]])) {
						return $data['global'][$e[2]];
					}
				break;
			}
		}, $content);
	}	
	public static function render($file, $app = array(), $buffer = true) {
		$app_template = ROOT_PATH.DIRECTORY_SEPARATOR."app".DIRECTORY_SEPARATOR.$file.'.html';
		if (is_file($app_template)) {
			self::$app = $app;
			self::$file = $app_template;
			if ($buffer) {
				return self::_parse();
			} else {
				echo self::_parse();
			}
		}
	}
}
?>