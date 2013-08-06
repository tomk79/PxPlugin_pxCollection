<?php
$this->load_px_class('/bases/dao.php');

/**
 * PX Plugin "pxCollection"
 */
class pxplugin_pxCollection_models_collections extends px_bases_dao{

	private $list = array(
		'themes'=>null,
		'plugins'=>null,
		'contents'=>null
	);

	/**
	 * 実データをロードする。
	 */
	private function load_data( $category ){
		if( is_array( $this->list[$category] ) ){
			return true;
		}

		// [UTODO]一旦の仮実装。データベースの本体をどこに置くか未決定
		$path_data_dir = $this->px->get_plugin_object('pxCollection')->get_data_cache_dir();
		if( !is_dir($path_data_dir.'db/') ){ $this->px->dbh()->mkdir_all( $path_data_dir.'db/' ); }
		if( !$this->px->dbh()->copy( dirname(__FILE__).'/../data/item_index.json' , $path_data_dir.'db/item_index.json' ) ){
			return false;
		}
		$tmp_ary = @json_decode( $this->px->dbh()->file_get_contents( $path_data_dir.'/db/item_index.json') );

		$this->list = array();

		foreach( array('themes','plugins','contents') as $tmp_category ){
			$this->list[$tmp_category] = array();
			foreach( $tmp_ary->$tmp_category as $row ){
				$this->list[$tmp_category][$row] = array();
			}
		}

		return true;
	}


	/**
	 * それぞれコレクションの全件を取得する
	 */
	public function get_list_of( $category ){
		$this->load_data($category);
		return array_keys( $this->list[$category] );
	}


	/**
	 * 指定のアイテムの情報を得る
	 */
	public function get_item_info( $category, $item_name ){
		if( is_object($this->list[$category][$item_name]) ){
			return $this->list[$category][$item_name];
		}
		$obj = $this->px->get_plugin_object( 'pxCollection' );
		$item = $obj->factory_model_item( $category, $item_name );
		$this->list[$category][$item_name] = $item;
		return $item;
	}//get_item_info()

}

?>