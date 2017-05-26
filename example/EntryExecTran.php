<?php
require_once( './config.php');

if( isset( $_POST['submit'] ) ){
	require_once( 'com/gmo_pg/client/input/EntryTranInput.php');
	require_once( 'com/gmo_pg/client/input/ExecTranInput.php');
	require_once( 'com/gmo_pg/client/input/EntryExecTranInput.php');
	require_once( 'com/gmo_pg/client/tran/EntryExecTran.php');
	
	//入力パラメータクラスをインスタンス化します
	
	//取引登録時に必要なパラメータ
	$entryInput = new EntryTranInput();
	$entryInput->setShopId( PGCARD_SHOP_ID );
	$entryInput->setShopPass( PGCARD_SHOP_PASS );
	$entryInput->setJobCd( $_POST['JobCd']);
	$entryInput->setOrderId( $_POST['OrderID'] );
	$entryInput->setItemCode( $_POST['ItemCode'] );
	$entryInput->setAmount( $_POST['Amount']);
	$entryInput->setTax( $_POST['Tax']);
	$entryInput->setTdFlag( $_POST['TdFlag']);
	$entryInput->setTdTenantName( $_POST['TdTenantName']);
	
	//決済実行のパラメータ
	$execInput = new ExecTranInput();

	//カード番号入力型・会員ID決済型に共通する値です。
	$execInput->setOrderId( $_POST['OrderID'] );
	
	//支払方法に応じて、支払回数のセット要否が異なります。
	$method = $_POST['PayMethod'];
	$execInput->setMethod( $method );
	if( $method == '2' || $method == '4'){//支払方法が、分割またはボーナス分割の場合、支払回数を設定します。
		$execInput->setPayTimes( $_POST['PayTimes'] );
	}
	
	//このサンプルでは、加盟店自由項目１～３を全て利用していますが、これらの項目は任意項目です。
	//利用しない場合、設定する必要はありません。
	//また、加盟店自由項目に２バイトコードを設定する場合、SJISに変換して設定してください。
	$execInput->setClientField1( mb_convert_encoding( $_POST['ClientField1'] , 'SJIS' , PGCARD_SAMPLE_ENCODING ) );
	$execInput->setClientField2( mb_convert_encoding( $_POST['ClientField2'] , 'SJIS' , PGCARD_SAMPLE_ENCODING ) );
	$execInput->setClientField3( mb_convert_encoding( $_POST['ClientField3'] , 'SJIS' , PGCARD_SAMPLE_ENCODING ) );
	$execInput->setDisplayInfo( mb_convert_encoding( $_POST['DisplayInfo'] , 'SJIS' , PGCARD_SAMPLE_ENCODING ) );
	
	//HTTP_ACCEPT,HTTP_USER_AGENTは、3Dセキュアサービスをご利用の場合のみ必要な項目です。
	//Entryで3D利用フラグをオンに設定した場合のみ、設定してください。	
	//設定する場合、カード所有者のブラウザから送信されたリクエストヘッダの値を、無加工で
	//設定してください。
	$execInput->setHttpUserAgent( $_SERVER['HTTP_USER_AGENT']);
	$execInput->setHttpAccept( $_SERVER['HTTP_ACCEPT' ]);
	
	//ここから、カード番号入力型決済と会員ID型決済それぞれの場合で
	//異なるパラメータを設定します。
	
	//ここでは、「画面で会員IDが入力されたか」を判断基準にして、
	//決済のタイプを判別しています。
	$memberId = $_POST['MemberID'];
	
	if( 0 < strlen( $memberId )  ){//会員ID決済
		//サンプルでは、サイトID・サイトパスワードはコンスタント定義しています。
		$execInput->setSiteId( PGCARD_SITE_ID );
		$execInput->setSitePass( PGCARD_SITE_PASS );
		
		//会員IDは必須です。
		$execInput->setMemberId( $memberId );
		
		//登録カード連番は任意です。
		$cardSeq = $_POST['CardSeq'];
		if( 0< strlen( $cardSeq ) ){
			$execInput->setCardSeq( $cardSeq );
		}
		
	}else{//カード番号決済
		
		//カード番号・有効期限は必須です。
		$execInput->setCardNo( $_POST['CardNo'] );
		$execInput->setExpire( $_POST['Expire'] );
		
		//セキュリティコードは任意です。
		$execInput->setSecurityCode( $_POST['SecurityCode'] );
	}
	
	
	//取引登録＋決済実行の入力パラメータクラスをインスタンス化します
	$input = new EntryExecTranInput();/* @var $input EntryExecTranInput */
	$input->setEntryTranInput( $entryInput );
	$input->setExecTranInput( $execInput );
	
	//API通信クラスをインスタンス化します
	$exe = new EntryExecTran();/* @var $exec EntryExecTran */
	
	//パラメータオブジェクトを引数に、実行メソッドを呼びます。
	//正常に終了した場合、結果オブジェクトが返るはずです。
	$output = $exe->exec( $input );/* @var $output EntryExecTranOutput */

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
			
		}else if( $output->isTdSecure() ){//決済実行の場合、3Dセキュアフラグをチェックします。
			
			//3Dセキュアフラグがオンである場合、リダイレクトページを表示する必要があります。
			//サンプルでは、モジュールタイプに標準添付されるリダイレクトユーティリティを利用しています。
			
			//リダイレクト用パラメータをインスタンス化して、パラメータを設定します
			require_once( 'com/gmo_pg/client/input/AcsParam.php');
			require_once( 'com/gmo_pg/client/common/RedirectUtil.php');
			$redirectInput = new AcsParam();
			$redirectInput->setAcsUrl( $output->getAcsUrl() );
			$redirectInput->setMd( $output->getAccessId() );
			$redirectInput->setPaReq( $output->getPaReq() );
			$redirectInput->setTermUrl( PGCARD_SAMPLE_URL . '/SecureTran.php');
			
			//リダイレクトページ表示クラスをインスタンス化して実行します。
			$redirectShow = new RedirectUtil();
			print ($redirectShow->createRedirectPage( PGCARD_SECURE_RIDIRECT_HTML , $redirectInput ) );
			exit();
			
		}

		//例外発生せず、エラーの戻りもなく、3Dセキュアフラグもオフであるので、実行結果を表示します。
	}
	
}

//EntryExecTran入力・結果画面
require_once( PGCARD_SAMPLE_BASE . '/display/EntryExecTran.php' );