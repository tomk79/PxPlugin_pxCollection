<?php
$this->load_px_class('/bases/dao.php');

/**
 * PX Plugin "pxCollection"
 */
class pxplugin_pxCollection_models_item extends px_bases_dao{

	private $category = null;
	private $item_name = null;
	private $path_data_cache_dir = null;
	private $data = null;
	private $versions = null;

	/**
	 * コンストラクタ
	 * @param $px = PxFWコアオブジェクト
	 * @param $category = アイテムカテゴリ
	 * @param $item_name = アイテム名
	 */
	public function __construct( $px, $category, $item_name ){
		parent::__construct( $px );

		$this->path_data_cache_dir = $this->px->get_plugin_object('pxCollection')->get_data_cache_dir();

		switch($category){
			case 'plugins':
			case 'themes':
			case 'contents':
				break;
			default:
				// 不明なカテゴリはNG
				return false;
				break;
		}
		if( !preg_match('/^[a-zA-Z][a-zA-Z0-9\_]*$/s', $item_name) ){
			// item_name が不正な形式の場合NG
			return false;
		}

		// [UTODO]一旦の仮実装。データベースの本体をどこに置くか未決定
		if( !is_file($path_data_dir.'/db/'.$category.'/'.$item_name.'.json') ){
			if( !is_dir($this->path_data_cache_dir.'db/') ){ $this->px->dbh()->mkdir_all( $this->path_data_cache_dir.'db/' ); }
			if( !is_dir($this->path_data_cache_dir.'db/'.$category.'/') ){ $this->px->dbh()->mkdir_all( $this->path_data_cache_dir.'db/'.$category.'/' ); }
			if( !$this->px->dbh()->copy( dirname(__FILE__).'/../data/'.$category.'/'.$item_name.'.json' , $this->path_data_cache_dir.'db/'.$category.'/'.$item_name.'.json' ) ){
				return false;
			}
		}
		$this->category = $category;
		$this->item_name = $item_name;
		$this->data = @json_decode( $this->px->dbh()->file_get_contents( $this->path_data_cache_dir.'/db/'.$category.'/'.$item_name.'.json') );

		$this->versions = array();
		foreach( $this->data->versions as $version ){
			$this->versions[$version->version] = array();
			$this->versions[$version->version]['version'] = $version->version;
			$this->versions[$version->version]['md5_hash'] = $version->md5_hash;
			$this->versions[$version->version]['type'] = $version->type;
			$this->versions[$version->version]['url'] = $version->url;
			$this->versions[$version->version]['release_date'] = $this->px->dbh()->datetime2int($version->release_date);
		}

	}

	/**
	 * アイテムカテゴリを取得する
	 */
	public function get_category(){
		return $this->category;
	}

	/**
	 * アイテム名を取得する
	 */
	public function get_item_name(){
		return $this->item_name;
	}

	/**
	 * 作者名を取得する
	 */
	public function get_author(){
		return $this->data->author;
	}

	/**
	 * 説明を取得する
	 */
	public function get_description(){
		return $this->data->description;
	}

	/**
	 * URLを取得する
	 */
	public function get_url(){
		return $this->data->url;
	}

	/**
	 * サムネイルのURLを取得する
	 */
	public function get_thumb(){
		return $this->data->thumb;
	}

	/**
	 * 登録画像URLの一覧を取得する
	 */
	public function get_images(){
		return $this->data->images;
	}

	/**
	 * フラグの一覧を取得する
	 */
	public function get_flags(){
		return $this->data->flags;
	}

	/**
	 * バージョンの一覧を取得する
	 */
	public function get_versions(){
		return $this->versions;
	}

	/**
	 * 最新のバージョンを取得する
	 */
	public function get_current_version_info(){
		foreach( $this->versions as $version ){
			return $version;
		}
		return false;
	}

	/**
	 * 指定したバージョンを取得する
	 */
	public function get_version_info( $version_num ){
		return $this->versions[$version_num];
	}

	/**
	 * 更新日を取得する
	 */
	public function get_update_date(){
		return $this->px->dbh()->datetime2int( $this->data->update_date );
	}


	/**
	 * バージョン番号を解析する
	 */
	public function parse_version_number( $version_string ){
		if( is_array($version_string) ){ return $version_string; }
		$rtn = array();
		preg_match('/^([0-9]+)\.([0-9]+)\.([0-9]+)(?:(a|b)([0-9]+))?(\-nb)?$/si', $matched);
		$rtn['major'] = $matched[1];
		$rtn['minor'] = $matched[2];
		$rtn['release'] = $matched[3];
		$rtn['status'] = $matched[4];
		$rtn['status_num'] = $matched[5];
		$rtn['nb'] = isset($matched[6]);
		return $rtn;
	}

}

?>