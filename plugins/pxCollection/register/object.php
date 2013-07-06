<?php

/**
 * PX Plugin "pxCollection"
 */
class pxplugin_pxCollection_register_object{
	private $px;

	/**
	 * コンストラクタ
	 * @param $px = PxFWコアオブジェクト
	 */
	public function __construct($px){
		$this->px = $px;
	}

	public function factory_dao_collections(){
		$class_name = $this->px->load_px_plugin_class('/pxCollection/daos/collections.php');
		if(!$class_name){
			return false;
		}
		return new $class_name( $this->px );
	}

}

?>