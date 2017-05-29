<?php
require_once( './config.php');

if( isset( $_POST['submit'] ) ){
	require_once( 'com/gmo_pg/client/input/EntryTranCvsInput.php');
	require_once( 'com/gmo_pg/client/input/ExecTranCvsInput.php');
	require_once( 'com/gmo_pg/client/input/EntryExecTranCvsInput.php');
	require_once( 'com/gmo_pg/client/tran/EntryExecTranCvs.php');
	
	//入力パラメータクラスをインスタンス化します
	
	//取引登録時に必要なパラメータ
	$entryInput = new EntryTranCvsInput();
	$entryInput->setShopId( PGCARD_SHOP_ID );
	$entryInput->setShopPass( PGCARD_SHOP_PASS );
	$entryInput->setOrderId( $_POST['OrderID'] );
	$entryInput->setAmount( $_POST['Amount']);
	$entryInput->setTax( $_POST['Tax']);
	
	
	//決済実行のパラメータ
	$execInput = new ExecTranCvsInput();
	$execInput->setOrderId( $_POST['OrderID'] );
	
	//メールアドレス
	$execInput->setConvenience( $_POST['Convenience'] );
	$execInput->setCustomerName( mb_convert_encoding( $_POST['CustomerName'] , 'SJIS' , PGCARD_SAMPLE_ENCODING ) );
	$execInput->setCustomerKana( mb_convert_encoding( $_POST['CustomerKana'] , 'SJIS' , PGCARD_SAMPLE_ENCODING ) );
	$execInput->setTelNo( $_POST['TelNo'] );
	
	//
	$execInput->setMailAddress( $_POST['MailAddress'] );
	
	
	//実際には、以降のパラメータをお客様が直接入力することは無く、
	//購買内容を元に加盟店様システムで生成した値が設定されるものと思います。
	$execInput->setShopMailAdress( $_POST['ShopMailAdress'] );
	$execInput->setReserveNo( $_POST['ReserveNo'] );
	$execInput->setMemberNo( $_POST['MemberNo'] );
	
	$execInput->setPaymentTermDay( $_POST['PaymentTermDay'] );
	
	$execInput->setRegisterDisp1( mb_convert_encoding( $_POST['RegisterDisp1'] , 'SJIS' , PGCARD_SAMPLE_ENCODING ) );
	$execInput->setRegisterDisp2( mb_convert_encoding( $_POST['RegisterDisp2'] , 'SJIS' , PGCARD_SAMPLE_ENCODING ) );
	$execInput->setRegisterDisp3( mb_convert_encoding( $_POST['RegisterDisp3'] , 'SJIS' , PGCARD_SAMPLE_ENCODING ) );
	$execInput->setRegisterDisp4( mb_convert_encoding( $_POST['RegisterDisp4'] , 'SJIS' , PGCARD_SAMPLE_ENCODING ) );
	$execInput->setRegisterDisp5( mb_convert_encoding( $_POST['RegisterDisp5'] , 'SJIS' , PGCARD_SAMPLE_ENCODING ) );
	$execInput->setRegisterDisp6( mb_convert_encoding( $_POST['RegisterDisp6'] , 'SJIS' , PGCARD_SAMPLE_ENCODING ) );
	$execInput->setRegisterDisp7( mb_convert_encoding( $_POST['RegisterDisp7'] , 'SJIS' , PGCARD_SAMPLE_ENCODING ) );
	$execInput->setRegisterDisp8( mb_convert_encoding( $_POST['RegisterDisp8'] , 'SJIS' , PGCARD_SAMPLE_ENCODING ) );
	
	$execInput->setReceiptsDisp1( mb_convert_encoding( $_POST['ReceiptsDisp1'] , 'SJIS' , PGCARD_SAMPLE_ENCODING ) );
	$execInput->setReceiptsDisp2( mb_convert_encoding( $_POST['ReceiptsDisp2'] , 'SJIS' , PGCARD_SAMPLE_ENCODING ) );
	$execInput->setReceiptsDisp3( mb_convert_encoding( $_POST['ReceiptsDisp3'] , 'SJIS' , PGCARD_SAMPLE_ENCODING ) );
	$execInput->setReceiptsDisp4( mb_convert_encoding( $_POST['ReceiptsDisp4'] , 'SJIS' , PGCARD_SAMPLE_ENCODING ) );
	$execInput->setReceiptsDisp5( mb_convert_encoding( $_POST['ReceiptsDisp5'] , 'SJIS' , PGCARD_SAMPLE_ENCODING ) );
	$execInput->setReceiptsDisp6( mb_convert_encoding( $_POST['ReceiptsDisp6'] , 'SJIS' , PGCARD_SAMPLE_ENCODING ) );
	$execInput->setReceiptsDisp7( mb_convert_encoding( $_POST['ReceiptsDisp7'] , 'SJIS' , PGCARD_SAMPLE_ENCODING ) );
	$execInput->setReceiptsDisp8( mb_convert_encoding( $_POST['ReceiptsDisp8'] , 'SJIS' , PGCARD_SAMPLE_ENCODING ) );
	$execInput->setReceiptsDisp9( mb_convert_encoding( $_POST['ReceiptsDisp9'] , 'SJIS' , PGCARD_SAMPLE_ENCODING ) );
	$execInput->setReceiptsDisp10( mb_convert_encoding( $_POST['ReceiptsDisp10'] , 'SJIS' , PGCARD_SAMPLE_ENCODING ) );
	$execInput->setReceiptsDisp11( mb_convert_encoding( $_POST['ReceiptsDisp11'] , 'SJIS' , PGCARD_SAMPLE_ENCODING ) );
	
	$execInput->setReceiptsDisp12( $_POST['ReceiptsDisp12'] );
	$execInput->setReceiptsDisp13( $_POST['ReceiptsDisp13'] );
	
	
	//このサンプルでは、加盟店自由項目１～３を全て利用していますが、これらの項目は任意項目です。
	//利用しない場合、設定する必要はありません。
	//また、加盟店自由項目に２バイトコードを設定する場合、SJISに変換して設定してください。
	$execInput->setClientField1( mb_convert_encoding( $_POST['ClientField1'] , 'SJIS' , PGCARD_SAMPLE_ENCODING ) );
	$execInput->setClientField2( mb_convert_encoding( $_POST['ClientField2'] , 'SJIS' , PGCARD_SAMPLE_ENCODING ) );
	$execInput->setClientField3( mb_convert_encoding( $_POST['ClientField3'] , 'SJIS' , PGCARD_SAMPLE_ENCODING ) );
	
	
		
	
	
	
	//取引登録＋決済実行の入力パラメータクラスをインスタンス化します
	$input = new EntryExecTranCvsInput();/* @var $input EntryExecTranCvsInput */
	$input->setEntryTranCvsInput( $entryInput );
	$input->setExecTranCvsInput( $execInput );
	
	//API通信クラスをインスタンス化します
	$exe = new EntryExecTranCvs();/* @var $exec EntryExecTranCvs */
	
	//パラメータオブジェクトを引数に、実行メソッドを呼びます。
	//正常に終了した場合、結果オブジェクトが返るはずです。
	$output = $exe->exec( $input );/* @var $output EntryExecTranCvsOutput */

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

//EntryExecTranCvs入力・結果画面
require_once( PGCARD_SAMPLE_BASE . '/display/EntryExecTranCvs.php' );