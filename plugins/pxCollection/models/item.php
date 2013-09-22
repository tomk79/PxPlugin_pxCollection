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
	private $errors = array();

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
		if( !strlen($this->data->thumb) ){
			return 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAQAAAAEACAMAAABrrFhUAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyJpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMC1jMDYwIDYxLjEzNDc3NywgMjAxMC8wMi8xMi0xNzozMjowMCAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNSBNYWNpbnRvc2giIHhtcE1NOkluc3RhbmNlSUQ9InhtcC5paWQ6QzA0NUFFRTAxQkExMTFFM0I1MzRGRkU1ODQ1Q0I2NzYiIHhtcE1NOkRvY3VtZW50SUQ9InhtcC5kaWQ6QzA0NUFFRTExQkExMTFFM0I1MzRGRkU1ODQ1Q0I2NzYiPiA8eG1wTU06RGVyaXZlZEZyb20gc3RSZWY6aW5zdGFuY2VJRD0ieG1wLmlpZDo2NDIyNTA2MTFCOUUxMUUzQjUzNEZGRTU4NDVDQjY3NiIgc3RSZWY6ZG9jdW1lbnRJRD0ieG1wLmRpZDo2NDIyNTA2MjFCOUUxMUUzQjUzNEZGRTU4NDVDQjY3NiIvPiA8L3JkZjpEZXNjcmlwdGlvbj4gPC9yZGY6UkRGPiA8L3g6eG1wbWV0YT4gPD94cGFja2V0IGVuZD0iciI/PuqWEyYAAAAYUExURePj49XV1fLy8t3d3fr6+uzs7P///8nJyY+plrkAAALOSURBVHja7NiJitswEABQ3f7/P651ed11oFAo1O0TbMgaiXieNDNOwvGfjwAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAP5NgJjyfJPiulBLKzXe5+T5uanu29hrbtfWylbG3JzGiG8AaGW+afNuc6s55tTy/WPTnLmmHKXtG8mlXUHWvjJOrFBCH68AKHPPFkBcsaUSnwA1rKjTvpEayn77YcU7UqCFGfp8TWm71CdAmIel1gug5boW5BaPlwIcJV0A8Tr6tTwB4gyy5J35uV2Bp59CfhfAPPYjkK99vO/oDCecVHVGvaOt5/8rZ1p4L8BM3xHwGeR1/QFQjpEDZ9T73PfzUtP36WNubwLhLQAj4X8NkGaGnH9rg3O7jsqantpsFbML5LcAHL0OfgPInwB6DuRyHLsajn0eh38VjxinxMtSoCfB9yIYHkWwJ/55tef9cjr74jlWDb0awysBzsBXhd/d79kGe+nvnSBvgLVkttGra7wToFetFc08AqE9HmtGTSup7OTfpTCuHKivBjj2M23qj3YxfHgUHs98YUUbb6d+NsXcxjeEXQPiGC8CuIpeOL/RtJKfXf0W+nj9WtHW16rWR5nFdIzwAoBPJr+9c39gz/0eAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA8HePHwIMAJuP9+SJOrnaAAAAAElFTkSuQmCC';
		}
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

	/**
	 * アイテムのインストールを実行する
	 */
	public function install( $version_num = null ){
		if( !strlen($version_num) ){
			$version_info = $this->get_current_version_info();
		}else{
			$version_info = $this->get_version_info( $version_num );
		}
		if( !$version_info ){
			$this->error('対象とするバージョンを特定できませんでした。');
			return false;
		}
		$item_info = $this;

		$obj = $this->px->get_plugin_object( 'pxCollection' );

		$basename_dl_file = $item_info->get_item_name().'-'.$version_info['version'].'-'.$version_info['md5_hash'].'.'.strtolower($version_info['type']);
		$path_dl_file = $obj->get_data_cache_dir().'db/'.$item_info->get_category().'/'.$basename_dl_file;
		if( !$this->px->dbh()->mkdir_all( dirname($path_dl_file) ) ){
			$this->error('キャッシュ用ディレクトリ '.$this->px->dbh()->get_realpath(dirname($path_dl_file)).'/ の作成に失敗しました。');
			return false;
		}

		// アーカイブをダウンロード
		if( is_file($path_dl_file) ){
			// すでにアーカイブがあったらダウンロードは省略
		}else{
			set_time_limit(0);
			$httpaccess = $obj->factory_httpaccess();
			$httpaccess->clear_request_header();//初期化
			$httpaccess->set_url( $version_info['url'] );//ダウンロードするURL
			$httpaccess->set_method( 'GET' );//メソッド
			$httpaccess->set_user_agent( 'pxCollection/'.$obj->factory_info()->get_version().'(PicklesFramework)' );//HTTP_USER_AGENT
			$httpaccess->save_http_contents( $path_dl_file );//ダウンロードを実行する
			clearstatcache();

			if( !is_file($path_dl_file) ){
				$this->error('ダウンロードに失敗しました。');
				return false;
			}

			$result = $httpaccess->get_status_cd();
			if( $result != 200 ){
				$this->px->dbh()->rm($path_dl_file);
				$this->error('ダウンロードに失敗しました。(status = '.$result.')');
				return false;
			}

		}

		$md5_dlfile = md5_file($path_dl_file);
		if( $md5_dlfile != $version_info['md5_hash'] ){
			$this->px->dbh()->rm($path_dl_file);
			$this->error('MD5ハッシュ値が一致しません。('.$md5_dlfile.'<=>'.$version_info['md5_hash'].')');
			return false;
		}
		set_time_limit(30);


		// アーカイブを解凍
		$path_tmp_dir = $obj->get_data_cache_dir().'tmp/'.$md5_dlfile.'/';
		if( !$this->px->dbh()->mkdir_all( $path_tmp_dir ) ){
			$this->error('アーカイブ解凍用ディレクトリ '.$this->px->dbh()->get_realpath($path_tmp_dir).'/ の作成に失敗しました。');
			return false;
		}
		$archiver = $obj->factory_archiver(strtolower($version_info['type']));
		if( !$archiver ){
			$this->error('アーカイバの生成に失敗しました。');
			return false;
		}
		set_time_limit(0);
		if( !$archiver->unzip($path_dl_file, $path_tmp_dir) ){
			$this->error('アーカイブ解凍に失敗しました。');
			return false;
		}
		set_time_limit(30);

		// 解凍したアーカイブ内を確認
		// ルートディレクトリを調べる
		$path_root_dir = $path_tmp_dir;
		if( !is_dir( $path_root_dir.$item_info->get_category().'/'.$item_info->get_item_name() ) ){
			$tmp_list = $this->px->dbh()->ls($path_tmp_dir);
			if( is_array($tmp_list) && count($tmp_list) == 1 && is_dir( $path_tmp_dir.$tmp_list[0].'/'.$item_info->get_category().'/'.$item_info->get_item_name() ) ){
				$path_root_dir = $path_tmp_dir.$tmp_list[0].'/';
			}
		}
		clearstatcache();
		if( !is_dir( $path_root_dir.$item_info->get_category().'/'.$item_info->get_item_name() ) ){
			$this->px->dbh()->rm($path_tmp_dir);
			$this->error('アーカイブを解凍しましたが、格納形式が不正なようです。処理を中止します。');
			return false;
		}

		// 一旦アンインストールする
		if( $this->is_installed() ){
			if( !$this->uninstall() ){
				$this->px->dbh()->rm($path_tmp_dir);
				$this->error('インストール前のアンインストールに失敗しました。');
				return false;
			}
		}

		// インストールを実行する
		set_time_limit(0);
		$path_px_dir = $this->px->get_conf('paths.px_dir');
		if( $item_info->get_category() == 'plugins' ){
			if( !$this->px->dbh()->copy_all( $path_root_dir.'plugins/'.$item_info->get_item_name(), $path_px_dir.'plugins/'.$item_info->get_item_name() ) ){
				$this->px->dbh()->rm($path_tmp_dir);
				$this->error('プラグインのインストールに失敗しました。');
				return false;
			}
		}elseif( $item_info->get_category() == 'themes' ){
			if( !$this->px->dbh()->copy_all( $path_root_dir.'themes/'.$item_info->get_item_name(), $path_px_dir.'themes/'.$item_info->get_item_name() ) ){
				$this->px->dbh()->rm($path_tmp_dir);
				$this->error('テーマのインストールに失敗しました。');
				return false;
			}
		}

		// 同梱ライブラリをインストールする
		if( is_dir( $path_root_dir.'libs' ) ){
			$liblist = $this->px->dbh()->ls( $path_root_dir.'libs' );
			foreach( $liblist as $libName ){
				if( !$this->px->dbh()->copy_all( $path_root_dir.'libs/'.$libName, $path_px_dir.'libs/'.$libName ) ){
					$this->px->dbh()->rm($path_tmp_dir);
					$this->error('ライブラリ '.$libName.' のインストールに失敗しました。');
					return false;
				}
			}
		}
		set_time_limit(30);

		// 後処理
		$this->px->dbh()->rm($path_tmp_dir);

		return true;
	}// install()

	/**
	 * アイテムのアンインストールを実行する
	 */
	public function uninstall(){
		$item_info = $this;
		if( !strlen($item_info->get_item_name()) ){
			return false;
		}

		$obj = $this->px->get_plugin_object( 'pxCollection' );

		// インストールを実行する
		$path_px_dir = $this->px->get_conf('paths.px_dir');
		if( $item_info->get_category() == 'plugins' || $item_info->get_category() == 'themes' ){
			if( !$this->px->dbh()->rm( $path_px_dir.$item_info->get_category().'/'.$item_info->get_item_name() ) ){
				$this->error($item_info->get_category().' '.$item_info->get_item_name().' のアンインストールに失敗しました。');
				return false;
			}
		}

		return true;
	}// uninstall()

	/**
	 * インストール済みかどうか確認する。
	 */
	public function is_installed(){
		$item_info = $this;
		$path_root_dir = $this->px->get_conf('paths.px_dir');
		if( $item_info->get_category() == 'plugins' ){
			if( $this->px->dbh()->is_dir( $path_root_dir.'plugins/'.$item_info->get_item_name() ) ){
				return true;
			}
		}elseif( $item_info->get_category() == 'themes' ){
			if( $this->px->dbh()->is_dir( $path_root_dir.'themes/'.$item_info->get_item_name() ) ){
				return true;
			}
		}
		return false;
	}//is_installed()

	/**
	 * 内部エラーを記録する
	 */
	private function error( $error_message ){
		array_push( $this->errors,
			array(
				'message'=>$error_message ,
			)
		);
		return true;
	}// error()

	/**
	 * 実行時エラーを取得する
	 */
	public function get_error_report(){
		return $this->errors;
	}

}

?>