<?php
require_once( './config.php');

if( isset( $_POST['submit'] ) ){
	require_once( 'com/gmo_pg/client/input/EntryTranMcpInput.php');
	require_once( 'com/gmo_pg/client/input/ExecTranMcpInput.php');
	require_once( 'com/gmo_pg/client/input/EntryExecTranMcpInput.php');
	require_once( 'com/gmo_pg/client/tran/EntryExecTranMcp.php');
	
	//入力パラメータクラスをインスタンス化します
	$input = new EntryExecTranMcpInput();/* @var $input EntryExecTranMcpInput */
		$input->setShopID($_POST['ShopID']);
		$input->setShopPass($_POST['ShopPass']);
		$input->setOrderID($_POST['OrderID']);
		$input->setJobCd($_POST['JobCd']);
		$input->setItemCode($_POST['ItemCode']);
		$input->setCurrency($_POST['Currency']);
		$input->setAmount($_POST['Amount']);
		$input->setTax($_POST['Tax']);
		$input->setTdTenantName($_POST['TdTenantName']);
		$input->setAccessID($_POST['AccessID']);
		$input->setAccessPass($_POST['AccessPass']);
		$input->setCardNo($_POST['CardNo']);
		$input->setExpire($_POST['Expire']);
		$input->setSecurityCode($_POST['SecurityCode']);
		$input->setRetURL($_POST['RetURL']);
		$input->setErrorRcvURL($_POST['ErrorRcvURL']);
		$input->setClientField1( mb_convert_encoding( $_POST['ClientField1'] , 'SJIS' , PGCARD_SAMPLE_ENCODING ) );
		$input->setClientField2( mb_convert_encoding( $_POST['ClientField2'] , 'SJIS' , PGCARD_SAMPLE_ENCODING ) );
		$input->setClientField3( mb_convert_encoding( $_POST['ClientField3'] , 'SJIS' , PGCARD_SAMPLE_ENCODING ) );
		$input->setClientFieldFlag($_POST['ClientFieldFlag']);
		$input->setPaymentTermSec($_POST['PaymentTermSec']);
		$input->setToken($_POST['Token']);

	
	//API通信クラスをインスタンス化します
	$exe = new EntryExecTranMcp();/* @var $exec EntryExecTranMcp */
	
	//パラメータオブジェクトを引数に、実行メソッドを呼びます。
	//正常に終了した場合、結果オブジェクトが返るはずです。
	$output = $exe->exec( $input );/* @var $output EntryExecTranMcpOutput */

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

//EntryExecTranMcp入力・結果画面
require_once( PGCARD_SAMPLE_BASE . '/display/EntryExecTranMcp.php' );