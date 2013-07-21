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
				return $this->start_install( $command[2], $command[3] );
			}elseif( $command[4] == 'uninstall' ){
				// アンインストール
				return $this->start_uninstall( $command[2], $command[3] );
			}elseif( $command[4] == 'update' ){
				// アップデート
				return $this->start_update( $command[2], $command[3] );
			}

			// 詳細ページ
			return $this->page_detail( $command[2], $command[3] );

		}elseif( $command[2] == 'themes' ){
			// テーマの一覧ページ
			return $this->page_list_themes();

		}elseif( $command[2] == 'plugins' ){
			// プラグインの一覧ページ
			return $this->page_list_plugins();

		}elseif( $command[2] == 'contents' ){
			// コンテンツの一覧ページ
			return $this->page_list_contents();

		}

		$this->homepage();
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


		print $this->html_template($src);
		exit;
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


	/**
	 * アイテムをインストールする
	 */
	private function start_install( $category, $item_name ){
		$src = '';
		$src .= '<p>この機能 &quot;'.implode('.',$this->get_command()).'&quot; は、開発中です。</p>'."\n";
		print $this->html_template($src);
		exit;
	}


	/**
	 * アイテムをアンインストールする
	 */
	private function start_uninstall( $category, $item_name ){
		$src = '';
		$src .= '<p>この機能 &quot;'.implode('.',$this->get_command()).'&quot; は、開発中です。</p>'."\n";
		print $this->html_template($src);
		exit;
	}


	/**
	 * アイテムをアップデートする
	 */
	private function start_update( $category, $item_name ){
		$src = '';
		$src .= '<p>この機能 &quot;'.implode('.',$this->get_command()).'&quot; は、開発中です。</p>'."\n";
		print $this->html_template($src);
		exit;
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