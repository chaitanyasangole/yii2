<?php
namespace frontend\controllers;

use frontend\models\ResendVerificationEmailForm;
use frontend\models\VerifyEmailForm;
use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use frontend\models\PaymentForm;
use frontend\models\Checkout2Form;
use frontend\models\FileUploadForm;	
use yii\web\UploadedFile;
use frontend\models\EmailForm;
use frontend\models\SmsForm;
use frontend\models\HiForm;
use frontend\models\SmsTemplateForm;
/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
	//public $defaultAction="smsform";
	
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout', 'signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        } else {
            $model->password = '';

            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending your message.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post()) && $model->signup()) {
            Yii::$app->session->setFlash('success', 'Thank you for registration. Please check your inbox for verification email.');
            return $this->goHome();
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for the provided email address.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }

    /**
     * Verify email address
     *
     * @param string $token
     * @throws BadRequestHttpException
     * @return yii\web\Response
     */
    public function actionVerifyEmail($token)
    {
        try {
            $model = new VerifyEmailForm($token);
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
        if ($user = $model->verifyEmail()) {
            if (Yii::$app->user->login($user)) {
                Yii::$app->session->setFlash('success', 'Your email has been confirmed!');
                return $this->goHome();
            }
        }

        Yii::$app->session->setFlash('error', 'Sorry, we are unable to verify your account with provided token.');
        return $this->goHome();
    }

    /**
     * Resend verification email
     *
     * @return mixed
     */
    public function actionResendVerificationEmail()
    {
        $model = new ResendVerificationEmailForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');
                return $this->goHome();
            }
            Yii::$app->session->setFlash('error', 'Sorry, we are unable to resend verification email for the provided email address.');
        }

        return $this->render('resendVerificationEmail', [
            'model' => $model
        ]);
    }
	public function actionPayment()
	{
		$model=new PaymentForm();
		if($model->load(Yii::$app->request->post()))
		{//print_r($model);
			return $this->render('display',['model'=>$model]);
		}
		else
		{
			return $this->render('payment',['model'=>$model]);
		}
	}
	public function actionDisplay()
	{
		return $this->render('display');
	}
	public function actionStudent()
	{
		$student=new Student();
		return $this->render('student',['student'=>$student]);
	}
	public function actionGateway()
	{
		if(Yii::$app->request->isPost)
		{
		$model=new Checkout2Form();
		$model->merchant_id=234548;
		$model->order_id=1234;
		$model->language='EN';
		$model->amount=6000;
		$model->currency='INR';
		$model->cancel_url='http://youdomain.com/payment-response.php';
		$model->redirect_url='http://youdomain.com/payment-cancel.php';
		
		return $this->render('checkout2',['model'=>$model]);
		}
		else
		{
		return $this->render('gateway');
		}
	}
	public function actionRequesthandler()
	{
		return $this->render('ccavRequestHandler');
	}
	// functions for encrypt & decrypt
	public function encrypt($plainText,$key)
	{
		$secretKey = hextobin(md5($key));
		$initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
	  	$openMode = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '','cbc', '');
	  	$blockSize = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, 'cbc');
		$plainPad = pkcs5_pad($plainText, $blockSize);
	  	if (mcrypt_generic_init($openMode, $secretKey, $initVector) != -1) 
		{
		      $encryptedText = mcrypt_generic($openMode, $plainPad);
	      	      mcrypt_generic_deinit($openMode);
		      			
		} 
		return bin2hex($encryptedText);
	}

	public function decrypt($encryptedText,$key)
	{
		$secretKey = hextobin(md5($key));
		$initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
		$encryptedText=hextobin($encryptedText);
	  	$openMode = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '','cbc', '');
		mcrypt_generic_init($openMode, $secretKey, $initVector);
		$decryptedText = mdecrypt_generic($openMode, $encryptedText);
		$decryptedText = rtrim($decryptedText, "\0");
	 	mcrypt_generic_deinit($openMode);
		return $decryptedText;
		
	}
	//*********** Padding Function *********************

	 public function pkcs5_pad ($plainText, $blockSize)
	{
	    $pad = $blockSize - (strlen($plainText) % $blockSize);
	    return $plainText . str_repeat(chr($pad), $pad);
	}

	//********** Hexadecimal to Binary function for php 4.0 version ********

	public function hextobin($hexString) 
   	 { 
        	$length = strlen($hexString); 
        	$binString="";   
        	$count=0; 
        	while($count<$length) 
        	{       
        	    $subString =substr($hexString,$count,2);           
        	    $packedString = pack("H*",$subString); 
        	    if ($count==0)
		    {
				$binString=$packedString;
		    } 
        	    
		    else 
		    {
				$binString.=$packedString;
		    } 
        	    
		    $count+=2; 
        	} 
  	        return $binString; 
    	  }
	public function actionFileupload()
	{
		$con=Yii::$app->getDb();
		$model=new FileUploadForm();
		if($model->load(Yii::$app->request->post()))
		{
			$name="file";
			/*
			$model->img=UploadedFile::getInstance($model,'img');
			$model->img->saveAs('upload/'.$name.'.'.$model->img->extension);
			*/
			$files=UploadedFile::getInstances($model,'img');
			$i=0;
			$model->date_of_upload=date("Y-m-d");// date of upload
			
			$con->createCommand('insert into inward 
			(invoice_number,invoice_date,date_of_upload,server_name)
			values('.$model->invoice_number.',"'.$model->invoice_date.'",
			"'.$model->date_of_upload.'","'.$model->server_name.'")')->execute();
			
			$sql='SELECT id from inward where invoice_number='.$model->invoice_number;
			echo 'query='.$sql.'<br>';
			$db=$con->createCommand($sql)->queryOne();
			var_dump($db);
			$server='';
			switch($model->server_name)
			{
				case 'goldfinch':
				$server='goldfinch';
				break;
						
				case 'petaltouch':
				$server='petaltouch';
				break;
						
				case 'devparlour':
				$server='devparlour';
				break;
						
				case 'combparlour':
				$server='combparlour';
				break;
						
				case 'btanish':
				$server='btanish';
				break;
			}
			$path1='upload/'.$server.'/'.$db['id'].'/';
			
			if(file_exists($path))
			{
				
			}
			else
			{
			mkdir($path1);
			}
			foreach($files as $file)
			{
				if($file!==null)
				{
					echo $db['id'];
					$i++;//file numbering.
					//missing inward.
					$path=$path1.$i.'_'.$file->name;//.'.'.$file->extension;
					echo $path.'<br>';
					
					$con->createCommand('insert into inward_details
					(inward_id,img_path)
					values('.$db['id'].',"'.$path.'")')
					->execute();
					
					$file->saveAs($path);
				}
			}
			Yii::$app->session->setFlash('success',
			"File has been successfully uploaded");
		}
		return $this->render('file_upload',['model'=>$model]);
	}
	public function actionSendemail()
	{
		$model=new EmailForm();
		if($model->load(Yii::$app->request->post()))
		{
			$model->save();
			return $this->render('view_email',['model'=>$model]);
		}
		else
		{
			return $this->render('sendemail',['model'=>$model]);
		}
	}
	public function actionSmsform()
	{
		$sms=new SmsTemplateForm();
		if($sms->load(Yii::$app->request->post()))
		{
			$db=Yii::$app->getDb();
			$db->createCommand('insert into ');
		}
		else
		{
			return $this->render('smsform',['sms'=>$sms]);
		}
	}
	public function actionHi()
	{
		$hi=new HiForm();
		if($hi->load(Yii::$app->request->post()))
		{
			var_dump($hi);
			exit;
		}
		else
		{
			return $this->render('hi',['hi'=>$hi]);
		}
	}
	public function actionDynamic()
	{
		return $this->render('dynamic_form');
	}
}
