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

		$command = $this->get_command();
		if( strlen( $command[2] ) && strlen( $command[3] ) ){
			if( $command[4] == 'install' ){
				// インストール
				print $this->html_template( $this->start_install( $command[2], $command[3] ) );
				exit;
			}elseif( $command[4] == 'uninstall' ){
				// アンインストール
				print $this->html_template( $this->start_uninstall( $command[2], $command[3] ) );
				exit;
			}

			// 詳細ページ
			print $this->html_template( $this->page_detail( $command[2], $command[3] ) );
			exit;

		}elseif( $command[2] == 'themes' ){
			// テーマの一覧ページ
			print $this->html_template( $this->page_list_category( $command[2] ) );
			exit;

		}elseif( $command[2] == 'plugins' ){
			// プラグインの一覧ページ
			print $this->html_template( $this->page_list_category( $command[2] ) );
			exit;

		}elseif( $command[2] == 'contents' ){
			// コンテンツの一覧ページ
			print $this->html_template( $this->page_list_category( $command[2] ) );
			exit;

		}

		print $this->html_template( $this->homepage() );
		exit;
	}

	/**
	 * ホームページを表示する。
	 */
	private function homepage(){
		$command = $this->get_command();

		$src = '';
		// $src .= '<p>このプラグイン pxCollection は開発中です。</p>'."\n";

		$obj = $this->px->get_plugin_object( 'pxCollection' );
		$model_collections = $obj->factory_model_collections();

		$src .= '<h2>themes</h2>'."\n";
		$src .= '<div class="unit">'."\n";
		$list = $model_collections->get_list_of('themes');
		if(!count($list)){
			$src .= '<p>nothing.</p>'."\n";
		}else{
			$src .= '<dl>'."\n";
			foreach($list as $key){
				$item_info = $model_collections->get_item_info('themes', $key);
				$src .= '<dt><a href="'.t::h($this->href(':themes.'.$item_info->get_item_name())).'">'.t::h($item_info->get_item_name()).'</a></dt>'."\n";
				$src .= '<dd>author: '.t::h($item_info->get_author()).'</dd>'."\n";
				$src .= '<dd>'.t::h($item_info->get_description()).'</dd>'."\n";
				$src .= '<dd><a href="'.t::h($item_info->get_url()).'" target="_blank">more...</a></dd>'."\n";
			}
			$src .= '</dl>'."\n";
		}
		$src .= '</div>'."\n";
		$src .= '<div class="more_links">'."\n";
		$src .= '	<ul>'."\n";
		$src .= '		<li><a href="'.$this->href(':themes').'">全部見る</a></li>'."\n";
		$src .= '	</ul>'."\n";
		$src .= '</div><!-- /.more_links -->'."\n";
		$src .= ''."\n";


		$src .= '<h2>plugins</h2>'."\n";
		$src .= '<div class="unit">'."\n";
		$list = $model_collections->get_list_of('plugins');
		if(!count($list)){
			$src .= '<p>nothing.</p>'."\n";
		}else{
			$src .= '<dl>'."\n";
			foreach($list as $key){
				$item_info = $model_collections->get_item_info('plugins', $key);
				$src .= '<dt><a href="'.t::h($this->href(':plugins.'.$item_info->get_item_name())).'">'.t::h($item_info->get_item_name()).'</a></dt>'."\n";
				$src .= '<dd>author: '.t::h($item_info->get_author()).'</dd>'."\n";
				$src .= '<dd>'.t::h($item_info->get_description()).'</dd>'."\n";
				$src .= '<dd><a href="'.t::h($item_info->get_url()).'" target="_blank">more...</a></dd>'."\n";
			}
			$src .= '</dl>'."\n";
		}
		$src .= '</div>'."\n";
		$src .= '<div class="more_links">'."\n";
		$src .= '	<ul>'."\n";
		$src .= '		<li><a href="'.$this->href(':plugins').'">全部見る</a></li>'."\n";
		$src .= '	</ul>'."\n";
		$src .= '</div><!-- /.more_links -->'."\n";
		$src .= ''."\n";


		$src .= '<h2>contents</h2>'."\n";
		$src .= '<div class="unit">'."\n";
		$list = $model_collections->get_list_of('contents');
		if(!count($list)){
			$src .= '<p>nothing.</p>'."\n";
		}else{
			$src .= '<dl>'."\n";
			foreach($list as $key){
				$item_info = $model_collections->get_item_info('contents', $key);
				$src .= '<dt><a href="'.t::h($this->href(':contents.'.$item_info->get_item_name())).'">'.t::h($item_info->get_item_name()).'</a></dt>'."\n";
				$src .= '<dd>author: '.t::h($item_info->get_author()).'</dd>'."\n";
				$src .= '<dd>'.t::h($item_info->get_description()).'</dd>'."\n";
				$src .= '<dd><a href="'.t::h($item_info->get_url()).'" target="_blank">more...</a></dd>'."\n";
			}
			$src .= '</dl>'."\n";
		}
		$src .= '</div>'."\n";
		$src .= '<div class="more_links">'."\n";
		$src .= '	<ul>'."\n";
		$src .= '		<li><a href="'.$this->href(':contents').'">全部見る</a></li>'."\n";
		$src .= '	</ul>'."\n";
		$src .= '</div><!-- /.more_links -->'."\n";
		$src .= ''."\n";

		return $src;
	}// homepage()


	/**
	 * カテゴリごとの一覧ページ
	 */
	private function page_list_category( $category ){
		$obj = $this->px->get_plugin_object( 'pxCollection' );
		$model_collections = $obj->factory_model_collections();

		$src = '';
		$src .= '<p>カテゴリ「'.t::h($category).'」の一覧ページは開発中です。</p>'."\n";
		$src .= '<div class="unit">'."\n";
		$list = $model_collections->get_list_of( $category );
		if(!count($list)){
			$src .= '<p>nothing.</p>'."\n";
		}else{
			$src .= '<dl>'."\n";
			foreach($list as $key){
				$item_info = $model_collections->get_item_info( $category, $key );
				$src .= '<dt><a href="'.t::h($this->href(':'.$category.'.'.$item_info->get_item_name())).'">'.t::h($item_info->get_item_name()).'</a></dt>'."\n";
				$src .= '<dd>author: '.t::h($item_info->get_author()).'</dd>'."\n";
				$src .= '<dd>'.t::h($item_info->get_description()).'</dd>'."\n";
				$src .= '<dd><a href="'.t::h($item_info->get_url()).'" target="_blank">more...</a></dd>'."\n";
			}
			$src .= '</dl>'."\n";
		}
		$src .= '</div>'."\n";

		$src .= '<form action="'.t::h($this->href(':')).'" method="post" class="inline">'."\n";
		$src .= '<div class="unit form_buttons">'."\n";
		$src .= '	<ul>'."\n";
		$src .= '		<li class="form_buttons-cancel"><input type="submit" name="" value="トップへ戻る" /></li>'."\n";
		$src .= '	</ul>'."\n";
		$src .= '</div><!-- /.form_buttons -->'."\n";
		$src .= '</form>'."\n";
		$src .= ''."\n";

		print $this->html_template($src);
		exit;
	}




	/**
	 * アイテムの詳細ページ
	 */
	private function page_detail( $category, $item_name ){
		$obj = $this->px->get_plugin_object( 'pxCollection' );
		$model_collections = $obj->factory_model_collections();

		$item_info = $model_collections->get_item_info( $category, $item_name );
		// test::var_dump($item_info);

		$this->set_title( $category.'「'.$item_name.'」' );//タイトルをセットする

		$src = '';

		$src .= '<table class="def" style="width:100%;">'."\n";
		$src .= '	<tbody>'."\n";
		$src .= '		<tr>'."\n";
		$src .= '			<th>name</th>'."\n";
		$src .= '			<td>'.t::h( $item_info->get_item_name() ).'</td>'."\n";
		$src .= '		</tr>'."\n";
		$src .= '		<tr>'."\n";
		$src .= '			<th>author</th>'."\n";
		$src .= '			<td>'.t::h( $item_info->get_author() ).'</td>'."\n";
		$src .= '		</tr>'."\n";
		$src .= '		<tr>'."\n";
		$src .= '			<th>description</th>'."\n";
		$src .= '			<td>'.t::text2html( $item_info->get_description() ).'</td>'."\n";
		$src .= '		</tr>'."\n";
		$src .= '		<tr>'."\n";
		$src .= '			<th>更新日</th>'."\n";
		$src .= '			<td>'.t::h( date( 'Y年m月d日', $item_info->get_update_date() ) ).'</td>'."\n";
		$src .= '		</tr>'."\n";
		$src .= '	</tbody>'."\n";
		$src .= '</table><!-- /table.def -->'."\n";
		$src .= ''."\n";
		$src .= '<h2>バージョン</h2>'."\n";
		$src .= '<div class="unit">'."\n";
		$versions = $item_info->get_versions();
		foreach( $versions as $version ){
			$src .= '<dl>'."\n";
			$src .= '<dt>version '.t::h($version['version']).'</dt>'."\n";
			$src .= '	<dd><a href="'.t::h($version['url']).'">DOWNLOAD</a> ('.t::h($version['type']).')</dd>'."\n";
			$src .= '	<dd>md5: '.t::h($version['md5_hash']).'</dd>'."\n";
			$src .= '	<dd>release: '.t::h( date( 'Y年m月d日', $version['release_date'] ) ).'</dd>'."\n";
			$src .= '</dl>'."\n";
		}
		$src .= '</div>'."\n";

		$src .= ''."\n";
		$src .= '<ul class="horizontal">'."\n";
		if( !is_dir( $this->px->get_conf('paths.px_dir').$category.'/'.$item_name.'/' ) ){
			$src .= '<li class="horizontal-li"><a href="'.t::h($this->href(':'.$category.'.'.$item_name.'.install')).'">インストール</a></li>'."\n";
		}else{
			$src .= '<li class="horizontal-li"><a href="'.t::h($this->href(':'.$category.'.'.$item_name.'.install')).'">アップデート</a></li>'."\n";
			$src .= '<li class="horizontal-li"><a href="'.t::h($this->href(':'.$category.'.'.$item_name.'.uninstall')).'">アンインストール</a></li>'."\n";
		}
		$src .= '</ul>'."\n";

		$src .= '<hr />'."\n";
		$src .= '<form action="'.t::h($this->href(':'.$category)).'" method="post" class="inline">'."\n";
		$src .= '<div class="unit form_buttons">'."\n";
		$src .= '	<ul>'."\n";
		$src .= '		<li class="form_buttons-cancel"><input type="submit" name="" value="一覧へ戻る" /></li>'."\n";
		$src .= '	</ul>'."\n";
		$src .= '</div><!-- /.form_buttons -->'."\n";
		$src .= '</form>'."\n";
		$src .= ''."\n";

		print $this->html_template($src);
		exit;
	}


	// -----------------------------------------------------------------------------------------

	/**
	 * アイテムのインストール
	 */
	private function start_install( $category, $item_name ){
		$this->set_title( $category.'「'.$item_name.'」をインストール' );//タイトルをセットする

		$obj = $this->px->get_plugin_object( 'pxCollection' );
		$model_collections = $obj->factory_model_collections();
		$item_info = $model_collections->get_item_info( $category, $item_name );
		$version_info = $item_info->get_current_version_info();

		$error = $this->check_install_check( $item_info, $version_info );
		if( $this->px->req()->get_param('mode') == 'thanks' ){
			return	$this->page_install_thanks( $item_info, $version_info );
		}elseif( $this->px->req()->get_param('mode') == 'execute' && !count( $error ) ){
			return	$this->execute_install_execute( $item_info, $version_info );
		}elseif( !strlen( $this->px->req()->get_param('mode') ) ){
			$error = array();
			// $this->px->req()->set_param( 'send_form_flg' , intval( $project_model->get_send_form_flg() ) );
		}
		return	$this->page_install_input( $item_info, $version_info, $error );
	}
	/**
	 * アイテムのインストール：入力
	 */
	private function page_install_input( $item_info, $version_info, $error ){

		$RTN = '';

		$RTN .= '<p>'."\n";
		$RTN .= '	次のアイテムをインストールしますか？<br />'."\n";
		$RTN .= '</p>'."\n";
		if( is_array( $error ) && count( $error ) ){
			$RTN .= '<p class="error">'."\n";
			$RTN .= '	入力エラーを検出しました。画面の指示に従って修正してください。<br />'."\n";
			if( strlen($error['common']) ){
				$RTN .= '	'.t::h($error['common']).'<br />'."\n";
			}
			$RTN .= '</p>'."\n";
		}
		$RTN .= '<form action="'.htmlspecialchars( $this->href() ).'" method="post" class="inline">'."\n";
		$RTN .= '<table style="width:100%;" class="form_elements">'."\n";
		$RTN .= '	<tr>'."\n";
		$RTN .= '		<th style="width:30%;"><div>種別</div></th>'."\n";
		$RTN .= '		<td style="width:70%;">'."\n";
		$RTN .= '			<div>'.t::h($item_info->get_category()).'</div>'."\n";
		$RTN .= '		</td>'."\n";
		$RTN .= '	</tr>'."\n";
		$RTN .= '	<tr>'."\n";
		$RTN .= '		<th style="width:30%;"><div>アイテム名</div></th>'."\n";
		$RTN .= '		<td style="width:70%;">'."\n";
		$RTN .= '			<div>'.t::h($item_info->get_item_name()).'</div>'."\n";
		$RTN .= '		</td>'."\n";
		$RTN .= '	</tr>'."\n";
		$RTN .= '	<tr>'."\n";
		$RTN .= '		<th style="width:30%;"><div>説明</div></th>'."\n";
		$RTN .= '		<td style="width:70%;">'."\n";
		$RTN .= '			<div>'.t::h($item_info->get_description()).'</div>'."\n";
		$RTN .= '		</td>'."\n";
		$RTN .= '	</tr>'."\n";
		$RTN .= '	<tr>'."\n";
		$RTN .= '		<th style="width:30%;"><div>URL</div></th>'."\n";
		$RTN .= '		<td style="width:70%;">'."\n";
		$RTN .= '			<div>'.t::h($item_info->get_url()).'</div>'."\n";
		$RTN .= '		</td>'."\n";
		$RTN .= '	</tr>'."\n";
		$RTN .= '	<tr>'."\n";
		$RTN .= '		<th style="width:30%;"><div>作者</div></th>'."\n";
		$RTN .= '		<td style="width:70%;">'."\n";
		$RTN .= '			<div>'.t::h($item_info->get_author()).'</div>'."\n";
		$RTN .= '		</td>'."\n";
		$RTN .= '	</tr>'."\n";
		$RTN .= '	<tr>'."\n";
		$RTN .= '		<th style="width:30%;"><div>更新日</div></th>'."\n";
		$RTN .= '		<td style="width:70%;">'."\n";
		$RTN .= '			<div>'.t::h($item_info->get_update_date()).'</div>'."\n";
		$RTN .= '		</td>'."\n";
		$RTN .= '	</tr>'."\n";
		$RTN .= '	<tr>'."\n";
		$RTN .= '		<th style="width:30%;"><div>バージョン</div></th>'."\n";
		$RTN .= '		<td style="width:70%;">'."\n";
		$RTN .= '			<div>'.t::h($version_info['version']).' ('.date('Y-m-d', $version_info['release_date']).')</div>'."\n";
		$RTN .= '			<div>'.t::h($version_info['url']).'</div>'."\n";
		$RTN .= '			<div>'.t::h($version_info['md5_hash']).'</div>'."\n";
		$RTN .= '		</td>'."\n";
		$RTN .= '	</tr>'."\n";
		$RTN .= '</table>'."\n";
		$RTN .= '<div class="unit form_buttons">'."\n";
		$RTN .= '	<ul>'."\n";
		$RTN .= '		<li class="form_buttons-submit"><input type="submit" value="インストールする" /></li>'."\n";
		$RTN .= '	</ul>'."\n";
		$RTN .= '</div><!-- /.form_buttons -->'."\n";
		$RTN .= '	<input type="hidden" name="mode" value="execute" />'."\n";
		$RTN .= '</form>'."\n";

		$RTN .= '<hr />'."\n";

		$RTN .= '<form action="'.htmlspecialchars( $this->href(':'.$item_info->get_category().'.'.$item_info->get_item_name()) ).'" method="post" class="inline">'."\n";
		$RTN .= '<div class="unit form_buttons">'."\n";
		$RTN .= '	<ul>'."\n";
		$RTN .= '		<li class="form_buttons-cancel"><input type="submit" value="キャンセル" /></li>'."\n";
		$RTN .= '	</ul>'."\n";
		$RTN .= '</div><!-- /.form_buttons -->'."\n";
		$RTN .= '</form>'."\n";
		$RTN .= ''."\n";

		return	$RTN;
	}
	/**
	 * アイテムのインストール：チェック
	 */
	private function check_install_check( $item_info, $version_info ){

		$RTN = array();

		// if( is_dir( $this->px->get_conf('paths.px_dir').$category.'/'.$item_name.'/' ) ){
		// 	$RTN['common'] = 'すでにインストールされています。';
		// }
		if( !strlen($item_info->get_item_name()) ){
			$RTN['common'] = '存在しないアイテムです。';
		}
		if( !strlen($version_info['url']) ){
			$RTN['common'] = 'インストールパッケージのURLが登録されていないアイテムです。';
		}

		return	$RTN;
	}
	/**
	 * アイテムのインストール：実行
	 */
	private function execute_install_execute( $item_info, $version_info ){

		$result = $item_info->install( $version_info['version'] );
		if( !$result ){
			$rtn = '';
			$rtn .= '<p>エラーが発生しました。</p>'."\n";
			$errors = $item_info->get_error_report();
			$rtn .= '<ul>'."\n";
			foreach( $errors as $error ){
				$rtn .= '<li>'.t::h( $error['message'] ).'</li>'."\n";
			}
			$rtn .= '</ul>'."\n";
			return $rtn;
		}


		return $this->px->redirect( $this->href().'&mode=thanks' );
	}
	/**
	 * アイテムのインストール：完了
	 */
	private function page_install_thanks( $item_info, $version_info ){
		$command = $this->get_command();
		$RTN = ''."\n";
		$RTN .= '<p>アイテムのインストールを完了しました。</p>'."\n";
		$RTN .= '<form action="'.htmlspecialchars( $this->href( ':'.$command[2].'.'.$command[3] ) ).'" method="post" class="inline">'."\n";
		$RTN .= '	<p><input type="submit" value="戻る" /></p>'."\n";
		$RTN .= '</form>'."\n";
		return	$RTN;
	}

	// -----------------------------------------------------------------------------------------

	/**
	 * アイテムのアンインストール
	 */
	private function start_uninstall( $category, $item_name ){
		$this->set_title( $category.'「'.$item_name.'」をアンインストール' );//タイトルをセットする

		$obj = $this->px->get_plugin_object( 'pxCollection' );
		$model_collections = $obj->factory_model_collections();
		$item_info = $model_collections->get_item_info( $category, $item_name );
		$version_info = $item_info->get_current_version_info();

		$error = $this->check_uninstall_check( $item_info, $version_info );
		if( $this->px->req()->get_param('mode') == 'thanks' ){
			return	$this->page_uninstall_thanks( $item_info, $version_info );
		}elseif( $this->px->req()->get_param('mode') == 'execute' && !count( $error ) ){
			return	$this->execute_uninstall_execute( $item_info, $version_info );
		}elseif( !strlen( $this->px->req()->get_param('mode') ) ){
			$error = array();
			// $this->px->req()->set_param( 'send_form_flg' , intval( $project_model->get_send_form_flg() ) );
		}
		return	$this->page_uninstall_input( $item_info, $version_info , $error );
	}
	/**
	 * アイテムのアンインストール：入力
	 */
	private function page_uninstall_input( $item_info, $version_info , $error ){
		$RTN = ''."\n";

		$RTN .= '<p>'."\n";
		$RTN .= '	'.t::h( $item_info->get_category() ).'「'.t::h( $item_info->get_item_name() ).'」をアンインストールします。<br />'."\n";
		$RTN .= '	問題なければ、「アンインストールする」ボタンを押してください。<br />'."\n";
		$RTN .= '</p>'."\n";
		if( is_array( $error ) && count( $error ) ){
			$RTN .= '<p class="error">'."\n";
			$RTN .= '	入力エラーを検出しました。画面の指示に従って修正してください。<br />'."\n";
			$RTN .= '</p>'."\n";
		}
		$RTN .= '<form action="'.htmlspecialchars( $this->href() ).'" method="post" class="inline">'."\n";
		$RTN .= '	<div class="center"><input type="submit" value="アンインストールする" /></div>'."\n";
		$RTN .= '	<input type="hidden" name="mode" value="execute" />'."\n";
		$RTN .= '</form>'."\n";
		return	$RTN;
	}
	/**
	 * アイテムのアンインストール：チェック
	 */
	private function check_uninstall_check( $item_info, $version_info ){
		$RTN = array();
		// if( !strlen( $this->px->req()->get_param('project_name') ) ){
		// 	$RTN['project_name'] = 'プロジェクト名は必須項目です。';
		// }elseif( preg_match( '/\r\n|\r|\n/' , $this->px->req()->get_param('project_name') ) ){
		// 	$RTN['project_name'] = 'プロジェクト名に改行を含めることはできません。';
		// }elseif( strlen( $this->px->req()->get_param('project_name') ) > 256 ){
		// 	$RTN['project_name'] = 'プロジェクト名が長すぎます。';
		// }
		return	$RTN;
	}
	/**
	 * アイテムのアンインストール：実行
	 */
	private function execute_uninstall_execute( $item_info, $version_info ){
		$result = $item_info->uninstall();
		if( !$result ){
			$rtn = '';
			$rtn .= '<p>エラーが発生しました。</p>'."\n";
			$errors = $item_info->get_error_report();
			$rtn .= '<ul>'."\n";
			foreach( $errors as $error ){
				$rtn .= '<li>'.t::h( $error['message'] ).'</li>'."\n";
			}
			$rtn .= '</ul>'."\n";
			return $rtn;
		}


		return $this->px->redirect( $this->href().'&mode=thanks' );
	}
	/**
	 * アイテムのアンインストール：完了
	 */
	private function page_uninstall_thanks( $item_info, $version_info ){
		$command = $this->get_command();
		$RTN = ''."\n";
		$RTN .= '<p>アイテムのアンインストールを完了しました。</p>'."\n";
		$RTN .= '<p>関連するライブラリ(libsディレクトリ)は、手動で削除するようにしてください。</p>'."\n";
		$RTN .= '<form action="'.htmlspecialchars( $this->href( ':'.$command[2].'.'.$command[3] ) ).'" method="post" class="inline">'."\n";
		$RTN .= '	<p><input type="submit" value="戻る" /></p>'."\n";
		$RTN .= '</form>'."\n";
		return	$RTN;
	}


	// -----------------------------------------------------------------------------------------


	/**
	 * コンテンツ内へのリンク先を調整する。
	 */
	private function href( $linkto = null ){
		if(is_null($linkto)){
			return '?PX='.implode('.',$this->get_command());
		}
		if($linkto == ':'){
			return '?PX=plugins.pxCollection';
		}
		$rtn = preg_replace('/^\:/','?PX=plugins.pxCollection.',$linkto);

		$rtn = $this->px->theme()->href( $rtn );
		return $rtn;
	}

	/**
	 * コンテンツ内へのリンクを生成する。
	 */
	private function mk_link( $linkto , $options = array() ){
		if( !strlen($options['label']) ){
			if( $this->local_sitemap[$linkto] ){
				$options['label'] = $this->local_sitemap[$linkto]['title'];
			}
		}
		$rtn = $this->href($linkto);

		$rtn = $this->px->theme()->mk_link( $rtn , $options );
		return $rtn;
	}


}

?>