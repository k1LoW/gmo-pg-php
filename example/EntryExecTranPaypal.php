<?php
require_once( './config.php');

if( isset( $_POST['submit'] ) ){
	require_once( 'com/gmo_pg/client/input/EntryTranPaypalInput.php');
	require_once( 'com/gmo_pg/client/input/ExecTranPaypalInput.php');
	require_once( 'com/gmo_pg/client/input/EntryExecTranPaypalInput.php');
	require_once( 'com/gmo_pg/client/tran/EntryExecTranPaypal.php');

	//入力パラメータクラスをインスタンス化します

	//取引登録時に必要なパラメータ
	$entryInput = new EntryTranPaypalInput();
	$entryInput->setShopId( PGCARD_SHOP_ID );
	$entryInput->setShopPass( PGCARD_SHOP_PASS );
	$entryInput->setOrderId( $_POST['OrderID'] );
	$entryInput->setJobCd( $_POST['JobCd'] );
	$entryInput->setCurrency( $_POST['Currency'] );
	$entryInput->setAmount( $_POST['Amount']);
	$entryInput->setTax( $_POST['Tax']);

	//決済実行のパラメータ
	$execInput = new ExecTranPaypalInput();
	$execInput->setShopId(PGCARD_SHOP_ID);
	$execInput->setShopPass(PGCARD_SHOP_PASS);
	$execInput->setOrderId( $_POST['OrderID'] );
	$execInput->setItemName( mb_convert_encoding( $_POST['ItemName'] , 'SJIS' , PGCARD_SAMPLE_ENCODING ) );
	$execInput->setRedirectURL( $_POST['RedirectURL'] );

	//このサンプルでは、加盟店自由項目１～３を全て利用していますが、これらの項目は任意項目です。
	//利用しない場合、設定する必要はありません。
	//また、加盟店自由項目に２バイトコードを設定する場合、SJISに変換して設定してください。
	$execInput->setClientField1( mb_convert_encoding( $_POST['ClientField1'] , 'SJIS' , PGCARD_SAMPLE_ENCODING ) );
	$execInput->setClientField2( mb_convert_encoding( $_POST['ClientField2'] , 'SJIS' , PGCARD_SAMPLE_ENCODING ) );
	$execInput->setClientField3( mb_convert_encoding( $_POST['ClientField3'] , 'SJIS' , PGCARD_SAMPLE_ENCODING ) );


	//取引登録＋決済実行の入力パラメータクラスをインスタンス化します
	$input = new EntryExecTranPaypalInput();/* @var $input EntryExecTranPaypalInput */
	$input->setEntryTranPaypalInput( $entryInput );
	$input->setExecTranPaypalInput( $execInput );

	//API通信クラスをインスタンス化します
	$exe = new EntryExecTranPaypal();/* @var $exec EntryExecTranPaypal */

	//パラメータオブジェクトを引数に、実行メソッドを呼びます。
	//正常に終了した場合、結果オブジェクトが返るはずです。
	$output = $exe->exec( $input );/* @var $output EntryExecTranPaypalOutput */

	//実行後、その結果を確認します。

	if( $exe->isExceptionOccured() ){//取引の処理そのものがうまくいかない（通信エラー等）場合、例外が発生します。

		//サンプルでは、例外メッセージを表示して終了します。
		require_once( PGCARD_SAMPLE_BASE . '/display/Exception.php');
		exit();

	}else{

		//例外が発生していない場合、出力パラメータオブジェクトが戻ります。

		if( $output->isErrorOccurred() ){//出力パラメータにエラーコードが含まれていないか、チェックしています。

			//サンプルでは、エラーが発生していた場合、エラー画面を表示して終了します。
			require_once( PGCARD_SAMPLE_BASE . '/display/EntryExecError.php');
			exit();
		}
		//例外発生せず、エラーの戻りもなく、3Dセキュアフラグもオフであるので、実行結果を表示します。
	}
}

//EntryExecTranEdy入力・結果画面
require_once( PGCARD_SAMPLE_BASE . '/display/EntryExecTranPaypal.php' );