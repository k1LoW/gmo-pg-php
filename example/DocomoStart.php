<?php
require_once( './config.php');

if( isset($_POST['submit'])) {
	//リダイレクト用パラメータをインスタンス化して、パラメータを設定します
	require_once( 'com/gmo_pg/client/input/DocomoStartInput.php');
	require_once( 'com/gmo_pg/client/common/RedirectUtil.php');
	$redirectInput = new DocomoStartInput();
	$redirectInput->setAccessID( $_POST['AccessID']);
	$redirectInput->setToken( $_POST['Token']);

	//リダイレクトページ表示クラスをインスタンス化して実行します。
	$redirectShow = new RedirectUtil();
	print ($redirectShow->docomoStart( DOCOMO_START_RIDIRECT_HTML , $redirectInput ) );
	exit();
}

//DocomoStart入力・結果画面
require_once( PGCARD_SAMPLE_BASE . '/display/DocomoStart.php' );
