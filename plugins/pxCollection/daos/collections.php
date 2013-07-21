<?php
$this->load_px_class('/bases/dao.php');

/**
 * PX Plugin "pxCollection"
 */
class pxplugin_pxCollection_daos_collections extends px_bases_dao{

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
		$tmp_ary = @json_decode( $this->px->dbh()->file_get_contents(dirname(__FILE__).'/../data/data_'.$category.'.json') );

		$this->list[$category] = array();
		foreach( $tmp_ary as $row ){
			$tmp_data = $row;
			array_push( $this->list[$category], $tmp_data);
		}

		return true;
	}


	/**
	 * それぞれコレクションの全件を取得する
	 */
	public function get_list_of( $category ){
		$this->load_data($category);
		return $this->list[$category];
	}


	/**
	 * 指定のアイテムの情報を得る
	 */
	public function get_item_info( $category, $item_name ){
		$list = $this->get_list_of($category);
		if( !is_array($list) || !count($list) ){
			return null;
		}
		foreach( $list as $row ){
			if( $row->name == $item_name ){
				return $row;
			}
		}
		return null;
	}//get_item_info()


}

?>