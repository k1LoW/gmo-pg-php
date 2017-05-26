<?php
require_once( './config.php');

if( isset( $_POST['submit'] ) ){
	require_once( 'com/gmo_pg/client/input/DeleteCardInput.php');
	require_once( 'com/gmo_pg/client/tran/DeleteCard.php');
	
	//入力パラメータクラスをインスタンス化します
	$input = new DeleteCardInput();/* @var $input DeleteCardInput */
	
	//このサンプルでは、サイトID・パスワードはコンフィグファイルで
	//定数defineしています。
	$input->setSiteId( PGCARD_SITE_ID );
	$input->setSitePass( PGCARD_SITE_PASS );
	
	//会員IDは必須です
	$input->setMemberId( $_POST['MemberID'] );
	
	//カード連番
	$input->setCardSeq( $_POST['CardSeq']);
	
	//カード連番モード
	$input->setSeqMode( $_POST['SeqMode']);
	
	//API通信クラスをインスタンス化します
	$exe = new DeleteCard();/* @var $exec DeleteCard */
	
	//パラメータオブジェクトを引数に、実行メソッドを呼び、結果を受け取ります。
	//正常に終了した場合、結果オブジェクトが返るはずです。
	$output = $exe->exec( $input );/* @var $output DeleteCardOutput */

	if( $exe->isExceptionOccured() ){//取引の処理そのものがうまくいかない（通信エラー等）場合、例外が発生します。

		//ここでは、例外メッセージを表示して終了します。
		require_once( 'display/Exception.php');
		exit();
		
	}else{
		
		//例外が発生していない場合、出力パラメータオブジェクトが戻ります。
		
		if( $output->isErrorOccurred() ){//出力パラメータにエラーコードが含まれていないか、チェックしています。
			
			//エラーが発生した場合、エラー画面を表示して終了します。
			require_once( 'display/Error.php');
			exit();
			
		}

		//例外発生せず、エラーの戻りもないので、正常とみなします。
		//このif文を抜けて、結果を表示します。
	}
}

//DeleteCard入力・結果画面
require_once( 'display/DeleteCard.php' );