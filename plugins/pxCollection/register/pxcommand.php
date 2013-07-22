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
			}elseif( $command[4] == 'update' ){
				// アップデート
				print $this->html_template( $this->start_update( $command[2], $command[3] ) );
				exit;
			}

			// 詳細ページ
			print $this->html_template( $this->page_detail( $command[2], $command[3] ) );
			exit;

		}elseif( $command[2] == 'themes' ){
			// テーマの一覧ページ
			print $this->html_template( $this->page_list_themes() );
			exit;

		}elseif( $command[2] == 'plugins' ){
			// プラグインの一覧ページ
			print $this->html_template( $this->page_list_plugins() );
			exit;

		}elseif( $command[2] == 'contents' ){
			// コンテンツの一覧ページ
			print $this->html_template( $this->page_list_contents() );
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
		$dao_collections = $obj->factory_dao_collections();

		$src .= '<h2>themes</h2>'."\n";
		$src .= '<div class="unit">'."\n";
		$list = $dao_collections->get_list_of('themes');
		if(!count($list)){
			$src .= '<p>nothing.</p>'."\n";
		}else{
			$src .= '<dl>'."\n";
			foreach($list as $row){
				$src .= '<dt><a href="'.t::h($this->href(':themes.'.$row->name)).'">'.t::h($row->name).'</a></dt>'."\n";
				$src .= '<dd>author: '.t::h($row->author).'</dd>'."\n";
				$src .= '<dd>'.t::h($row->description).'</dd>'."\n";
				$src .= '<dd><a href="'.t::h($row->url).'" target="_blank">more...</a></dd>'."\n";
				$src .= '<dd>version: <strong>'.t::h($row->version).'</strong></dd>'."\n";
				$src .= '<dd>md5: '.t::h($row->md5_hash).'</dd>'."\n";
				$src .= '<dd>url: '.t::h($row->url).'</dd>'."\n";
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
		$list = $dao_collections->get_list_of('plugins');
		if(!count($list)){
			$src .= '<p>nothing.</p>'."\n";
		}else{
			$src .= '<dl>'."\n";
			foreach($list as $row){
				$src .= '<dt><a href="'.t::h($this->href(':plugins.'.$row->name)).'">'.t::h($row->name).'</a></dt>'."\n";
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
		$src .= '<div class="more_links">'."\n";
		$src .= '	<ul>'."\n";
		$src .= '		<li><a href="'.$this->href(':plugins').'">全部見る</a></li>'."\n";
		$src .= '	</ul>'."\n";
		$src .= '</div><!-- /.more_links -->'."\n";
		$src .= ''."\n";


		$src .= '<h2>contents</h2>'."\n";
		$src .= '<div class="unit">'."\n";
		$list = $dao_collections->get_list_of('contents');
		if(!count($list)){
			$src .= '<p>nothing.</p>'."\n";
		}else{
			$src .= '<dl>'."\n";
			foreach($list as $row){
				$src .= '<dt><a href="'.t::h($this->href(':contents.'.$row->name)).'">'.t::h($row->name).'</a></dt>'."\n";
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
		$src .= '<div class="more_links">'."\n";
		$src .= '	<ul>'."\n";
		$src .= '		<li><a href="'.$this->href(':contents').'">全部見る</a></li>'."\n";
		$src .= '	</ul>'."\n";
		$src .= '</div><!-- /.more_links -->'."\n";
		$src .= ''."\n";

		return $src;
	}// homepage()


	/**
	 * テーマの一覧ページ
	 */
	private function page_list_themes(){
		$obj = $this->px->get_plugin_object( 'pxCollection' );
		$dao_collections = $obj->factory_dao_collections();

		$src = '';
		$src .= '<p>テーマの一覧ページは開発中です。</p>'."\n";
		$src .= '<div class="unit">'."\n";
		$list = $dao_collections->get_list_of('themes');
		if(!count($list)){
			$src .= '<p>nothing.</p>'."\n";
		}else{
			$src .= '<dl>'."\n";
			foreach($list as $row){
				$src .= '<dt><a href="'.t::h($this->href(':themes.'.$row->name)).'">'.t::h($row->name).'</a></dt>'."\n";
				$src .= '<dd>author: '.t::h($row->author).'</dd>'."\n";
				$src .= '<dd>'.t::h($row->description).'</dd>'."\n";
				$src .= '<dd><a href="'.t::h($row->url).'" target="_blank">more...</a></dd>'."\n";
				$src .= '<dd>version: <strong>'.t::h($row->version).'</strong></dd>'."\n";
				$src .= '<dd>md5: '.t::h($row->md5_hash).'</dd>'."\n";
				$src .= '<dd>url: '.t::h($row->url).'</dd>'."\n";
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
	 * プラグインの一覧ページ
	 */
	private function page_list_plugins(){
		$obj = $this->px->get_plugin_object( 'pxCollection' );
		$dao_collections = $obj->factory_dao_collections();

		$src = '';
		$src .= '<p>プラグインの一覧ページは開発中です。</p>'."\n";
		$src .= '<div class="unit">'."\n";
		$list = $dao_collections->get_list_of('plugins');
		if(!count($list)){
			$src .= '<p>nothing.</p>'."\n";
		}else{
			$src .= '<dl>'."\n";
			foreach($list as $row){
				$src .= '<dt><a href="'.t::h($this->href(':plugins.'.$row->name)).'">'.t::h($row->name).'</a></dt>'."\n";
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
	 * コンテンツの一覧ページ
	 */
	private function page_list_contents(){
		$obj = $this->px->get_plugin_object( 'pxCollection' );
		$dao_collections = $obj->factory_dao_collections();

		$src = '';
		$src .= '<p>コンテンツの一覧ページは開発中です。</p>'."\n";
		$src .= '<div class="unit">'."\n";
		$list = $dao_collections->get_list_of('contents');
		if(!count($list)){
			$src .= '<p>nothing.</p>'."\n";
		}else{
			$src .= '<dl>'."\n";
			foreach($list as $row){
				$src .= '<dt><a href="'.t::h($this->href(':contents.'.$row->name)).'">'.t::h($row->name).'</a></dt>'."\n";
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
	 * 詳細ページ
	 */
	private function page_detail( $category, $item_name ){
		$obj = $this->px->get_plugin_object( 'pxCollection' );
		$dao_collections = $obj->factory_dao_collections();

		$item_info = $dao_collections->get_item_info( $category, $item_name );
		// test::var_dump($item_info);

		$this->set_title( $category.'「'.$item_name.'」' );//タイトルをセットする

		$src = '';

		$src .= '<table class="def" style="width:100%;">'."\n";
		$src .= '	<tbody>'."\n";
		$src .= '		<tr>'."\n";
		$src .= '			<th>name</th>'."\n";
		$src .= '			<td>'.t::h( $item_info->name ).'</td>'."\n";
		$src .= '		</tr>'."\n";
		$src .= '		<tr>'."\n";
		$src .= '			<th>author</th>'."\n";
		$src .= '			<td>'.t::h( $item_info->author ).'</td>'."\n";
		$src .= '		</tr>'."\n";
		$src .= '		<tr>'."\n";
		$src .= '			<th>description</th>'."\n";
		$src .= '			<td>'.t::text2html( $item_info->description ).'</td>'."\n";
		$src .= '		</tr>'."\n";
		$src .= '		<tr>'."\n";
		$src .= '			<th>バージョン</th>'."\n";
		$src .= '			<td>'.t::h( $item_info->version ).'</td>'."\n";
		$src .= '		</tr>'."\n";
		$src .= '		<tr>'."\n";
		$src .= '			<th>更新日</th>'."\n";
		$src .= '			<td>'.t::h( $item_info->update_date ).'</td>'."\n";
		$src .= '		</tr>'."\n";
		$src .= '		<tr>'."\n";
		$src .= '			<th>パッケージ</th>'."\n";
		$src .= '			<td><a href="'.t::h( $item_info->url ).'">ダウンロード</a> ('.t::h( $item_info->type ).'/MD5: '.t::h( $item_info->md5_hash ).')</td>'."\n";
		$src .= '		</tr>'."\n";
		$src .= '	</tbody>'."\n";
		$src .= '</table><!-- /table.def -->'."\n";
		$src .= ''."\n";
		$src .= '<p class="center"><a href="'.t::h($this->href(':'.$category.'.'.$item_name.'.install')).'">インストール</a></p>'."\n";
		$src .= '<p class="center"><a href="'.t::h($this->href(':'.$category.'.'.$item_name.'.uninstall')).'">アンインストール</a></p>'."\n";
		$src .= '<p class="center"><a href="'.t::h($this->href(':'.$category.'.'.$item_name.'.update')).'">アップデート</a></p>'."\n";

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
	private function start_install(){
		$error = $this->check_install_check();
		if( $this->px->req()->get_param('mode') == 'thanks' ){
			return	$this->page_install_thanks();
		}elseif( $this->px->req()->get_param('mode') == 'confirm' && !count( $error ) ){
			return	$this->page_install_confirm();
		}elseif( $this->px->req()->get_param('mode') == 'execute' && !count( $error ) ){
			return	$this->execute_install_execute();
		}elseif( !strlen( $this->px->req()->get_param('mode') ) ){
			$error = array();
			// $this->px->req()->set_param( 'send_form_flg' , intval( $project_model->get_send_form_flg() ) );
		}
		return	$this->page_install_input( $error );
	}
	/**
	 * アイテムのインストール：入力
	 */
	private function page_install_input( $error ){
		$RTN = ''."\n";

		$RTN .= '<p>'."\n";
		$RTN .= '	プロジェクトの情報を入力して、「確認する」ボタンをクリックしてください。<span class="must">必須</span>印の項目は必ず入力してください。<br />'."\n";
		$RTN .= '</p>'."\n";
		if( is_array( $error ) && count( $error ) ){
			$RTN .= '<p class="error">'."\n";
			$RTN .= '	入力エラーを検出しました。画面の指示に従って修正してください。<br />'."\n";
			$RTN .= '</p>'."\n";
		}
		$RTN .= '<form action="'.htmlspecialchars( $this->href() ).'" method="post" class="inline">'."\n";
		$RTN .= '<table style="width:100%;" class="form_elements">'."\n";
		$RTN .= '	<tr>'."\n";
		$RTN .= '		<th style="width:30%;"><div>プロジェクト名 <span class="must">必須</span></div></th>'."\n";
		$RTN .= '		<td style="width:70%;">'."\n";
		$RTN .= '			<div><input type="text" name="project_name" value="'.htmlspecialchars( $this->px->req()->get_param('project_name') ).'" style="width:80%;" /></div>'."\n";
		if( strlen( $error['project_name'] ) ){
			$RTN .= '			<div class="error">'.$error['project_name'].'</div>'."\n";
		}
		$RTN .= '		</td>'."\n";
		$RTN .= '	</tr>'."\n";
		$RTN .= '</table>'."\n";
		$RTN .= '	<div class="center"><input type="submit" value="確認する" /></div>'."\n";
		$RTN .= '	<input type="hidden" name="mode" value="confirm" />'."\n";
		$RTN .= '</form>'."\n";
		return	$RTN;
	}
	/**
	 * アイテムのインストール：確認
	 */
	private function page_install_confirm(){
		$command = $this->get_command();
		$RTN = ''."\n";
		$HIDDEN = ''."\n";

		$RTN .= '<p>'."\n";
		$RTN .= '	入力した内容を確認してください。<br />'."\n";
		$RTN .= '</p>'."\n";

		$RTN .= '<table style="width:100%;" class="form_elements">'."\n";
		$RTN .= '	<tr>'."\n";
		$RTN .= '		<th style="width:30%;"><div>プロジェクト名</div></th>'."\n";
		$RTN .= '		<td style="width:70%;">'."\n";
		$RTN .= '			<div>'.htmlspecialchars( $this->px->req()->get_param('project_name') ).'</div>'."\n";
		$HIDDEN .= '<input type="hidden" name="project_name" value="'.htmlspecialchars( $this->px->req()->get_param('project_name') ).'" />';
		$RTN .= '		</td>'."\n";
		$RTN .= '	</tr>'."\n";
		$RTN .= '</table>'."\n";

		$RTN .= '<div class="unit">'."\n";
		$RTN .= '<div class="center">'."\n";
		$RTN .= '<form action="'.htmlspecialchars( $this->href() ).'" method="post" class="inline">'."\n";
		$RTN .= '	<input type="hidden" name="mode" value="execute" />'."\n";
		$RTN .= $HIDDEN;
		$RTN .= '	'.''."\n";
		$RTN .= '	<input type="submit" value="インストールする" />'."\n";
		$RTN .= '</form>'."\n";
		$RTN .= '<form action="'.htmlspecialchars( $this->href() ).'" method="post" class="inline">'."\n";
		$RTN .= '	<input type="hidden" name="mode" value="input" />'."\n";
		$RTN .= $HIDDEN;
		$RTN .= '	'.''."\n";
		$RTN .= '	<input type="submit" value="訂正する" />'."\n";
		$RTN .= '</form>'."\n";
		$RTN .= '</div>'."\n";
		$RTN .= '</div>'."\n";
		$RTN .= '<hr />'."\n";
		$RTN .= '<div class="unit">'."\n";
		$RTN .= '<form action="'.htmlspecialchars( $this->href(':'.$command[2].'.'.$command[3]) ).'" method="post" class="inline">'."\n";
		$RTN .= '	<div class="center"><input type="submit" value="キャンセル" /></div>'."\n";
		$RTN .= '</form>'."\n";
		$RTN .= '</div>'."\n";
		return	$RTN;
	}
	/**
	 * アイテムのインストール：チェック
	 */
	private function check_install_check(){
		$RTN = array();
		if( !strlen( $this->px->req()->get_param('project_name') ) ){
			$RTN['project_name'] = 'プロジェクト名は必須項目です。';
		}elseif( preg_match( '/\r\n|\r|\n/' , $this->px->req()->get_param('project_name') ) ){
			$RTN['project_name'] = 'プロジェクト名に改行を含めることはできません。';
		}elseif( strlen( $this->px->req()->get_param('project_name') ) > 256 ){
			$RTN['project_name'] = 'プロジェクト名が長すぎます。';
		}
		return	$RTN;
	}
	/**
	 * アイテムのインストール：実行
	 */
	private function execute_install_execute(){

		return $this->px->redirect( $this->href().'&mode=thanks' );
	}
	/**
	 * アイテムのインストール：完了
	 */
	private function page_install_thanks(){
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
	private function start_uninstall(){
		$error = $this->check_uninstall_check();
		if( $this->px->req()->get_param('mode') == 'thanks' ){
			return	$this->page_uninstall_thanks();
		}elseif( $this->px->req()->get_param('mode') == 'confirm' && !count( $error ) ){
			return	$this->page_uninstall_confirm();
		}elseif( $this->px->req()->get_param('mode') == 'execute' && !count( $error ) ){
			return	$this->execute_uninstall_execute();
		}elseif( !strlen( $this->px->req()->get_param('mode') ) ){
			$error = array();
			// $this->px->req()->set_param( 'send_form_flg' , intval( $project_model->get_send_form_flg() ) );
		}
		return	$this->page_uninstall_input( $error );
	}
	/**
	 * アイテムのアンインストール：入力
	 */
	private function page_uninstall_input( $error ){
		$RTN = ''."\n";

		$RTN .= '<p>'."\n";
		$RTN .= '	プロジェクトの情報を入力して、「確認する」ボタンをクリックしてください。<span class="must">必須</span>印の項目は必ず入力してください。<br />'."\n";
		$RTN .= '</p>'."\n";
		if( is_array( $error ) && count( $error ) ){
			$RTN .= '<p class="error">'."\n";
			$RTN .= '	入力エラーを検出しました。画面の指示に従って修正してください。<br />'."\n";
			$RTN .= '</p>'."\n";
		}
		$RTN .= '<form action="'.htmlspecialchars( $this->href() ).'" method="post" class="inline">'."\n";
		$RTN .= '<table style="width:100%;" class="form_elements">'."\n";
		$RTN .= '	<tr>'."\n";
		$RTN .= '		<th style="width:30%;"><div>プロジェクト名 <span class="must">必須</span></div></th>'."\n";
		$RTN .= '		<td style="width:70%;">'."\n";
		$RTN .= '			<div><input type="text" name="project_name" value="'.htmlspecialchars( $this->px->req()->get_param('project_name') ).'" style="width:80%;" /></div>'."\n";
		if( strlen( $error['project_name'] ) ){
			$RTN .= '			<div class="error">'.$error['project_name'].'</div>'."\n";
		}
		$RTN .= '		</td>'."\n";
		$RTN .= '	</tr>'."\n";
		$RTN .= '</table>'."\n";
		$RTN .= '	<div class="center"><input type="submit" value="確認する" /></div>'."\n";
		$RTN .= '	<input type="hidden" name="mode" value="confirm" />'."\n";
		$RTN .= '</form>'."\n";
		return	$RTN;
	}
	/**
	 * アイテムのアンインストール：確認
	 */
	private function page_uninstall_confirm(){
		$command = $this->get_command();
		$RTN = ''."\n";
		$HIDDEN = ''."\n";

		$RTN .= '<p>'."\n";
		$RTN .= '	入力した内容を確認してください。<br />'."\n";
		$RTN .= '</p>'."\n";

		$RTN .= '<table style="width:100%;" class="form_elements">'."\n";
		$RTN .= '	<tr>'."\n";
		$RTN .= '		<th style="width:30%;"><div>プロジェクト名</div></th>'."\n";
		$RTN .= '		<td style="width:70%;">'."\n";
		$RTN .= '			<div>'.htmlspecialchars( $this->px->req()->get_param('project_name') ).'</div>'."\n";
		$HIDDEN .= '<input type="hidden" name="project_name" value="'.htmlspecialchars( $this->px->req()->get_param('project_name') ).'" />';
		$RTN .= '		</td>'."\n";
		$RTN .= '	</tr>'."\n";
		$RTN .= '</table>'."\n";

		$RTN .= '<div class="unit">'."\n";
		$RTN .= '<div class="center">'."\n";
		$RTN .= '<form action="'.htmlspecialchars( $this->href() ).'" method="post" class="inline">'."\n";
		$RTN .= '	<input type="hidden" name="mode" value="execute" />'."\n";
		$RTN .= $HIDDEN;
		$RTN .= '	'.''."\n";
		$RTN .= '	<input type="submit" value="アンインストールする" />'."\n";
		$RTN .= '</form>'."\n";
		$RTN .= '<form action="'.htmlspecialchars( $this->href() ).'" method="post" class="inline">'."\n";
		$RTN .= '	<input type="hidden" name="mode" value="input" />'."\n";
		$RTN .= $HIDDEN;
		$RTN .= '	'.''."\n";
		$RTN .= '	<input type="submit" value="訂正する" />'."\n";
		$RTN .= '</form>'."\n";
		$RTN .= '</div>'."\n";
		$RTN .= '</div>'."\n";
		$RTN .= '<hr />'."\n";
		$RTN .= '<div class="unit">'."\n";
		$RTN .= '<form action="'.htmlspecialchars( $this->href(':'.$command[2].'.'.$command[3]) ).'" method="post" class="inline">'."\n";
		$RTN .= '	<div class="center"><input type="submit" value="キャンセル" /></div>'."\n";
		$RTN .= '</form>'."\n";
		$RTN .= '</div>'."\n";
		return	$RTN;
	}
	/**
	 * アイテムのアンインストール：チェック
	 */
	private function check_uninstall_check(){
		$RTN = array();
		if( !strlen( $this->px->req()->get_param('project_name') ) ){
			$RTN['project_name'] = 'プロジェクト名は必須項目です。';
		}elseif( preg_match( '/\r\n|\r|\n/' , $this->px->req()->get_param('project_name') ) ){
			$RTN['project_name'] = 'プロジェクト名に改行を含めることはできません。';
		}elseif( strlen( $this->px->req()->get_param('project_name') ) > 256 ){
			$RTN['project_name'] = 'プロジェクト名が長すぎます。';
		}
		return	$RTN;
	}
	/**
	 * アイテムのアンインストール：実行
	 */
	private function execute_uninstall_execute(){

		return $this->px->redirect( $this->href().'&mode=thanks' );
	}
	/**
	 * アイテムのアンインストール：完了
	 */
	private function page_uninstall_thanks(){
		$command = $this->get_command();
		$RTN = ''."\n";
		$RTN .= '<p>アイテムのアンインストールを完了しました。</p>'."\n";
		$RTN .= '<form action="'.htmlspecialchars( $this->href( ':'.$command[2].'.'.$command[3] ) ).'" method="post" class="inline">'."\n";
		$RTN .= '	<p><input type="submit" value="戻る" /></p>'."\n";
		$RTN .= '</form>'."\n";
		return	$RTN;
	}

	// -----------------------------------------------------------------------------------------

	/**
	 * アイテムのアップデート
	 */
	private function start_update(){
		$error = $this->check_update_check();
		if( $this->px->req()->get_param('mode') == 'thanks' ){
			return	$this->page_update_thanks();
		}elseif( $this->px->req()->get_param('mode') == 'confirm' && !count( $error ) ){
			return	$this->page_update_confirm();
		}elseif( $this->px->req()->get_param('mode') == 'execute' && !count( $error ) ){
			return	$this->execute_update_execute();
		}elseif( !strlen( $this->px->req()->get_param('mode') ) ){
			$error = array();
			// $this->px->req()->set_param( 'send_form_flg' , intval( $project_model->get_send_form_flg() ) );
		}
		return	$this->page_update_input( $error );
	}
	/**
	 * アイテムのアップデート：入力
	 */
	private function page_update_input( $error ){
		$RTN = ''."\n";

		$RTN .= '<p>'."\n";
		$RTN .= '	プロジェクトの情報を入力して、「確認する」ボタンをクリックしてください。<span class="must">必須</span>印の項目は必ず入力してください。<br />'."\n";
		$RTN .= '</p>'."\n";
		if( is_array( $error ) && count( $error ) ){
			$RTN .= '<p class="error">'."\n";
			$RTN .= '	入力エラーを検出しました。画面の指示に従って修正してください。<br />'."\n";
			$RTN .= '</p>'."\n";
		}
		$RTN .= '<form action="'.htmlspecialchars( $this->href() ).'" method="post" class="inline">'."\n";
		$RTN .= '<table style="width:100%;" class="form_elements">'."\n";
		$RTN .= '	<tr>'."\n";
		$RTN .= '		<th style="width:30%;"><div>プロジェクト名 <span class="must">必須</span></div></th>'."\n";
		$RTN .= '		<td style="width:70%;">'."\n";
		$RTN .= '			<div><input type="text" name="project_name" value="'.htmlspecialchars( $this->px->req()->get_param('project_name') ).'" style="width:80%;" /></div>'."\n";
		if( strlen( $error['project_name'] ) ){
			$RTN .= '			<div class="error">'.$error['project_name'].'</div>'."\n";
		}
		$RTN .= '		</td>'."\n";
		$RTN .= '	</tr>'."\n";
		$RTN .= '</table>'."\n";
		$RTN .= '	<div class="center"><input type="submit" value="確認する" /></div>'."\n";
		$RTN .= '	<input type="hidden" name="mode" value="confirm" />'."\n";
		$RTN .= '</form>'."\n";
		return	$RTN;
	}
	/**
	 * アイテムのアップデート：確認
	 */
	private function page_update_confirm(){
		$command = $this->get_command();
		$RTN = ''."\n";
		$HIDDEN = ''."\n";

		$RTN .= '<p>'."\n";
		$RTN .= '	入力した内容を確認してください。<br />'."\n";
		$RTN .= '</p>'."\n";

		$RTN .= '<table style="width:100%;" class="form_elements">'."\n";
		$RTN .= '	<tr>'."\n";
		$RTN .= '		<th style="width:30%;"><div>プロジェクト名</div></th>'."\n";
		$RTN .= '		<td style="width:70%;">'."\n";
		$RTN .= '			<div>'.htmlspecialchars( $this->px->req()->get_param('project_name') ).'</div>'."\n";
		$HIDDEN .= '<input type="hidden" name="project_name" value="'.htmlspecialchars( $this->px->req()->get_param('project_name') ).'" />';
		$RTN .= '		</td>'."\n";
		$RTN .= '	</tr>'."\n";
		$RTN .= '</table>'."\n";

		$RTN .= '<div class="unit">'."\n";
		$RTN .= '<div class="center">'."\n";
		$RTN .= '<form action="'.htmlspecialchars( $this->href() ).'" method="post" class="inline">'."\n";
		$RTN .= '	<input type="hidden" name="mode" value="execute" />'."\n";
		$RTN .= $HIDDEN;
		$RTN .= '	'.''."\n";
		$RTN .= '	<input type="submit" value="アップデートする" />'."\n";
		$RTN .= '</form>'."\n";
		$RTN .= '<form action="'.htmlspecialchars( $this->href() ).'" method="post" class="inline">'."\n";
		$RTN .= '	<input type="hidden" name="mode" value="input" />'."\n";
		$RTN .= $HIDDEN;
		$RTN .= '	'.''."\n";
		$RTN .= '	<input type="submit" value="訂正する" />'."\n";
		$RTN .= '</form>'."\n";
		$RTN .= '</div>'."\n";
		$RTN .= '</div>'."\n";
		$RTN .= '<hr />'."\n";
		$RTN .= '<div class="unit">'."\n";
		$RTN .= '<form action="'.htmlspecialchars( $this->href(':'.$command[2].'.'.$command[3]) ).'" method="post" class="inline">'."\n";
		$RTN .= '	<div class="center"><input type="submit" value="キャンセル" /></div>'."\n";
		$RTN .= '</form>'."\n";
		$RTN .= '</div>'."\n";
		return	$RTN;
	}
	/**
	 * アイテムのアップデート：チェック
	 */
	private function check_update_check(){
		$RTN = array();
		if( !strlen( $this->px->req()->get_param('project_name') ) ){
			$RTN['project_name'] = 'プロジェクト名は必須項目です。';
		}elseif( preg_match( '/\r\n|\r|\n/' , $this->px->req()->get_param('project_name') ) ){
			$RTN['project_name'] = 'プロジェクト名に改行を含めることはできません。';
		}elseif( strlen( $this->px->req()->get_param('project_name') ) > 256 ){
			$RTN['project_name'] = 'プロジェクト名が長すぎます。';
		}
		return	$RTN;
	}
	/**
	 * アイテムのアップデート：実行
	 */
	private function execute_update_execute(){

		return $this->px->redirect( $this->href().'&mode=thanks' );
	}
	/**
	 * アイテムのアップデート：完了
	 */
	private function page_update_thanks(){
		$command = $this->get_command();
		$RTN = ''."\n";
		$RTN .= '<p>アイテムのアップデートを完了しました。</p>'."\n";
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