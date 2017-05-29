<?php
require_once( './config.php');

if( isset( $_POST['submit'] ) ){
	require_once( 'com/gmo_pg/client/input/AlterTranInput.php');
	require_once( 'com/gmo_pg/client/tran/AlterTran.php');

	//入力パラメータクラスをインスタンス化します
	$input = new AlterTranInput();/* @var $input AlterTranInput */

	//各種パラメータを設定します。

	$input->setShopId( PGCARD_SHOP_ID );
	$input->setShopPass( PGCARD_SHOP_PASS );

	$input->setAccessId( $_POST['AccessID'] );
	$input->setAccessPass( $_POST['AccessPass'] );

	$input->setJobCd( $_POST['JobCd'] );

	$input->setAmount( $_POST['Amount']);
	$input->setTax($_POST['Tax']);
	$input->setDisplayDate($_POST['DisplayDate']);

	//支払方法に応じて、支払回数のセット要否が異なります。
	$method = $_POST['PayMethod'];
	$input->setMethod( $method );
	if( $method == '2' || $method == '4'){//支払方法が、分割またはボーナス分割の場合、支払回数を設定します。
		$input->setPayTimes( $_POST['PayTimes'] );
	}

	//API通信クラスをインスタンス化します
	$exe = new AlterTran();/* @var $exec AlterTran */

	//パラメータオブジェクトを引数に、実行メソッドを呼びます。
	//正常に終了した場合、結果オブジェクトが返るはずです。
	$output = $exe->exec( $input );/* @var $output AlterTranOutput */

	//実行後、その結果を確認します。

	if( $exe->isExceptionOccured() ){//取引の処理そのものがうまくいかない（通信エラー等）場合、例外が発生します。

		//サンプルでは、例外メッセージを表示して終了します。
		require_once( PGCARD_SAMPLE_BASE . '/display/Exception.php');
		exit();

	}else{

		//例外が発生していない場合、出力パラメータオブジェクトが戻ります。

		if( $output->isErrorOccurred() ){//出力パラメータにエラーコードが含まれていないか、チェックしています。

			//サンプルでは、エラーが発生していた場合、エラー画面を表示して終了します。
			require_once( PGCARD_SAMPLE_BASE . '/display/Error.php');
			exit();

		}

		//例外発生せず、エラーの戻りもなく、3Dセキュアフラグもオフであるので、実行結果を表示します。
	}

}

//AlterTran入力・結果画面
require_once( PGCARD_SAMPLE_BASE . '/display/AlterTran.php' );