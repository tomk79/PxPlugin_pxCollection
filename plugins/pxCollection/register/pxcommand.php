<?php
$this->load_px_class('/bases/pxcommand.php');

/**
 * PX Plugin "pxCollection"
 */
class pxplugin_pxCollection_register_pxcommand extends px_bases_pxcommand{

	/**
	 * コンストラクタ
	 * @param $command = PXコマンド配列
	 * @param $px = PxFWコアオブジェクト
	 */
	public function __construct( $command , $px ){
		parent::__construct( $command , $px );
		$this->px = $px;

		$this->homepage();
	}

	/**
	 * ホームページを表示する。
	 */
	private function homepage(){
		$command = $this->get_command();

		$src = '';
		$src .= '<p>このプラグイン pxCollection は開発中です。</p>'."\n";

		$obj = $this->px->get_plugin_object( 'pxCollection' );
		$dao_collections = $obj->factory_dao_collections();

		$src .= '<h2>themes</h2>'."\n";
		$src .= '<div class="unit">'."\n";
		$list = $dao_collections->get_list_of('themes');
		if(!count($list)){
			$src .= '<p>nothing.</p>'."\n";
		}else{
			foreach($list as $row){
				ob_start();
				test::var_dump($row);
				$src .= ob_get_clean();
			}
		}
		$src .= '</div>'."\n";

		$src .= '<h2>plugins</h2>'."\n";
		$src .= '<div class="unit">'."\n";
		$list = $dao_collections->get_list_of('plugins');
		if(!count($list)){
			$src .= '<p>nothing.</p>'."\n";
		}else{
			$src .= '<dl>'."\n";
			foreach($list as $row){
				$src .= '<dt>'.t::h($row->name).'</dt>'."\n";
				$src .= '<dd>author: '.t::h($row->author).'</dd>'."\n";
				$src .= '<dd>'.t::h($row->description).'</dd>'."\n";
				$src .= '<dd><a href="'.t::h($row->url).'" target="_blank">more...</a></dd>'."\n";
				foreach($row->versions as $row_version){
					$src .= '<dd>version: <strong>'.t::h($row_version->version).'</strong></dd>'."\n";
					$src .= '<dd>md5: '.t::h($row_version->md5_hash).'</dd>'."\n";
					$src .= '<dd>url: '.t::h($row_version->url).'</dd>'."\n";
				}
			}
			$src .= '</dl>'."\n";
		}
		$src .= '</div>'."\n";

		$src .= '<h2>contents</h2>'."\n";
		$src .= '<div class="unit">'."\n";
		$list = $dao_collections->get_list_of('contents');
		if(!count($list)){
			$src .= '<p>nothing.</p>'."\n";
		}else{
			foreach($list as $row){
				ob_start();
				test::var_dump($row);
				$src .= ob_get_clean();
			}
		}
		$src .= '</div>'."\n";

		print $this->html_template($src);
		exit;
	}

}

?>