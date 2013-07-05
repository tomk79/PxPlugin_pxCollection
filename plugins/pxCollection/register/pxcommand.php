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

		print $this->html_template($src);
		exit;
	}

}

?>