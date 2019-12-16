<?php 
namespace frontend\models;
use Yii;
use yii\base\Model;
class FileUploadForm extends Model
{
	public $img_path;
	public $server_name;
	public $invoice_number;
	public $invoice_date;
	public $date_of_upload;
	public $img;
	
	public function rules()	
	{
		return 
		[
		[['img_path','invoice_number','invoice_date','date_of_upload','server_name'],'required'],
		['img','file','skipOnEmpty'=>false,'maxFiles'=>10,
		'extensions'=>'jpg,jpeg,png,gif',
		'wrongExtension' => 'The avatar must be a JPG or PNG.'],
		['invoice_number','unique','message'=>'Please, enter unique invoice number'],
		['invoice_number','number'],
		[['invoice_date','date_of_upload'],'date'],
		[['server_name','img_path'],'string'],
		['server_name','in','range'=>
			['btanish','combparlour','devparlour','goldfinch','petaltouch'],
			'message'=>'Please,give valid server name']
		];
	}
}
?>