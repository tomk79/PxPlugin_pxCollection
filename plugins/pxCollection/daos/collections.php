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
	private function load_data( $type ){
		if( is_array( $this->list[$type] ) ){
			return true;
		}

		// [UTODO]一旦の仮実装。データベースの本体をどこに置くか未決定
		$tmp_ary = @json_decode( $this->px->dbh()->file_get_contents(dirname(__FILE__).'/../data/data_'.$type.'.json') );

		$this->list[$type] = array();
		foreach( $tmp_ary as $row ){
			$tmp_data = $row;
			array_push( $this->list[$type], $tmp_data);
		}

		return true;
	}


	/**
	 * それぞれコレクションの全件を取得する
	 */
	public function get_list_of( $type ){
		$this->load_data($type);
		return $this->list[$type];
	}

}

?>