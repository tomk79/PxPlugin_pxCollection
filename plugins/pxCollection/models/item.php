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
		$this->category = $category;
		$this->item_name = $item_name;

		$this->path_data_cache_dir = $this->px->get_plugin_object('pxCollection')->get_data_cache_dir();

		// [UTODO]一旦の仮実装。データベースの本体をどこに置くか未決定
		if( !is_file($path_data_dir.'/db/'.$category.'/'.$item_name.'.json') ){
			if( !is_dir($this->path_data_cache_dir.'db/') ){ $this->px->dbh()->mkdir_all( $this->path_data_cache_dir.'db/' ); }
			if( !is_dir($this->path_data_cache_dir.'db/'.$category.'/') ){ $this->px->dbh()->mkdir_all( $this->path_data_cache_dir.'db/'.$category.'/' ); }
			if( !$this->px->dbh()->copy( dirname(__FILE__).'/../data/'.$category.'/'.$item_name.'.json' , $this->path_data_cache_dir.'db/'.$category.'/'.$item_name.'.json' ) ){
				return false;
			}
		}
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
	 * 更新日を取得する
	 */
	public function get_update_date(){
		return $this->px->dbh()->datetime2int( $this->data->update_date );
	}

}

?>