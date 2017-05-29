<?php
require_once( './config.php');

if( isset($_POST['submit'])) {
	//リダイレクト用パラメータをインスタンス化して、パラメータを設定します
	require_once( 'com/gmo_pg/client/common/RedirectUtil.php');

	$startURL = $_POST['DocomoContinuanceStartURL'];
	$accessID = $_POST['AccessID'];
	$token = $_POST['Token'];

	//リダイレクトページ表示クラスをインスタンス化して実行します。
	$redirectShow = new RedirectUtil();
	print ($redirectShow->docomoContinuanceStart( DOCOMO_CONTINUANCE_START_RIDIRECT_HTML , $startURL, $accessID, $token ) );
	exit();
}

//DocomoContinuanceStart入力・結果画面
require_once( PGCARD_SAMPLE_BASE . '/display/DocomoContinuanceStart.php' );
