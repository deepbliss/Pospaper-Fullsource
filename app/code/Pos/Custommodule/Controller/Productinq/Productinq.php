<?php

session_start();
 
namespace Pos\Custommodule\Controller\Productinq;
 
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
 
class Productinq extends \Magento\Framework\App\Action\Action
{
    protected $_resultPageFactory;
 
    public function __construct(Context $context, \Magento\Framework\View\Result\PageFactory $resultPageFactory)
    {
        $this->_resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }
 
    public function execute()
    {
   
 $post = $this->getRequest()->getPost();

            $name = $_POST["name"];
            $email = $_POST["email"];
            $phone = $_POST["phone"];
            $company = $_POST["company"];
            $comments = $_POST["comments"];

if(($_SESSION['security_code'] == $_POST['security_code']) && (!empty($_SESSION['security_code'])) )
{
        

$to      = 'sales@pospaper.com';
$subject = 'Inquiry Form';

$message = "
<html><body>
<table>
    <tbody>
        <tr>
            <td>Name</td>
            <td>:</td>
            <td>".$name."</td>
        </tr>
        <tr>
            <td>Email</td>
            <td>:</td>
            <td>".$email."</td>
        </tr>
        <tr>
            <td>Phone</td>
            <td>:</td>
            <td>".$phone."</td>
        </tr>
        <tr>
            <td>Company</td>
            <td>:</td>
            <td>".$company."</td>
        </tr>
        <tr>
            <td>Message</td>
            <td>:</td>
            <td>".$comments."</td>
        </tr>
    </tbody>
</table>
</body></html>";

$headers[] = 'MIME-Version: 1.0';
$headers[] = 'Content-type: text/html; charset=iso-8859-1';
$headers[] = 'From: '.$email;
$headers[] = 'X-Mailer: PHP/' . phpversion();
/*$headers = 'From: $email' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();*/
    $headers = implode("\r\n", $headers);

mail($to, $subject, $message, $headers);

    //$this->_redirect->getRefererUrl();

    $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
    $resultRedirect->setUrl($this->_redirect->getRefererUrl());
    return $resultRedirect;
}
else
{
    ?>

<script type="text/javascript">
alert("Security code is invalid, please try again");
//window.location.href = document.referrer;
    history.back();
    //document.querySelector('#login-popup').click();
    //document.getElementById('captcha').src='captcha.php?'+Math.random();
    
</script>
<?php
}

    }
}