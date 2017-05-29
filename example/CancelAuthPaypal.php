<?php
require_once( './config.php');

if( isset($_POST['submit'])) {
	require_once( 'com/gmo_pg/client/input/CancelAuthPaypalInput.php');
	require_once( 'com/gmo_pg/client/tran/CancelAuthPaypal.php');

	//入力パラメータクラスをインスタンス化します
	$input = new CancelAuthPaypalInput();

	//このサンプルでは、ショップID・パスワードはコンフィグファイルで
	//定数defineしています。
	$input->setShopId(PGCARD_SHOP_ID);
	$input->setShopPass(PGCARD_SHOP_PASS);

	//各種パラメータを設定しています。
	//実際には、利用金額、オーダーIDといったパラメータをお客様が直接入力することは無く、
	//購買内容を元に加盟店様システムで生成した値が設定されるものと思います。
	$input->setAccessId( $_POST['AccessID'] );
	$input->setAccessPass( $_POST['AccessPass'] );
	$input->setOrderId( $_POST['OrderID'] );

	//API通信クラスをインスタンス化します
	$exe = new CancelAuthPaypal();

	$output = $exe->exec($input);

	if($exe->isExceptionOccured()) {//取引の処理そのものがうまくいかない（通信エラー等）場合、例外が発生します。

		//サンプルでは、例外メッセージを表示して終了します。
		require_once( PGCARD_SAMPLE_BASE . '/display/Exception.php');
		exit();
	} else {
		if($output->isErrorOccurred()) {//出力パラメータにエラーコードが含まれていないか、チェックしています。

			//サンプルでは、エラーが発生していた場合、エラー画面を表示して終了します。
			require_once( PGCARD_SAMPLE_BASE . '/display/Error.php');
			exit();
		}
		//例外発生せず、エラーの戻りもないので、正常とみなします。
		//このif文を抜けて、結果を表示します。
	}
}

require_once( PGCARD_SAMPLE_BASE . '/display/CancelAuthPaypal.php' );
