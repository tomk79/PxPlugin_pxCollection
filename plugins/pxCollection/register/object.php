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

	/**
	 * キャッシュディレクトリのパスを得る
	 * ディレクトリが存在しない場合は、作成する。
	 */
	public function get_data_cache_dir(){
		$path_dir = $this->px->get_conf('paths.px_dir').'_sys/ramdata/plugins/pxCollection/';
		if( !$this->px->dbh()->is_dir($path_dir) ){
			$this->px->dbh()->mkdir_all($path_dir);
			clearstatcache();
		}
		if( !$this->px->dbh()->is_dir($path_dir) ){
			return false;
		}
		return $path_dir;
	}//get_data_cache_dir()

	/**
	 * コレクションオブジェクトを生成する
	 */
	public function factory_model_collections(){
		$class_name = $this->px->load_px_plugin_class('/pxCollection/models/collections.php');
		if(!$class_name){
			return false;
		}
		return new $class_name( $this->px );
	}

	/**
	 * アイテムオブジェクトを生成する
	 */
	public function factory_model_item( $category, $item_name ){
		$class_name = $this->px->load_px_plugin_class('/pxCollection/models/item.php');
		if(!$class_name){
			return false;
		}
		return new $class_name( $this->px, $category, $item_name );
	}

}

?>