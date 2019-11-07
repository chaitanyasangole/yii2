<?php 
namespace frontend\models;
use Yii;
use yii\base\Model;
class Checkout2Form extends Model
{
	public $merchant_id;
	public $order_id;
	public $language;
	public $amount;
	public $currency;
	public $cancel_url;
	public $redirect_url;
	
}
?>