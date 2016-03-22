<?php
/**
 * Created by PhpStorm.
 * User: a.itsekson
 * Date: 20.06.14
 * Time: 14:44
 */
namespace Icekson\Utils\Mail;


use Zend\Mail\Transport\Sendmail as Mailer;
use Zend\Mail\Transport\Smtp as SmtpMailer;
use Zend\Mail\Message as MailMessage;
use Zend\Mail\Address\AddressInterface;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Message as MimeMessage;
use Zend\Mail\Transport\SmtpOptions;
use Icekson\Utils\Mail\Mailer as IzMailer;

class TemplateMailer implements IzMailer
{    
    /**
     * 
     * Mailer
     * @var Mailer
     */
    private $mailer = null;

    /**
     * @var MailMessage
     */
    private $message = null;


    /**
     * @var bool
     */
    private $isHtml = true;


    /**
     * @var string
     */
    private $encoding = 'base64';


    /**
     * @var string
     */
    private $charset = 'UTF-8';


    /**
     * @var null|string
     */
    private $content = null;


    /**
     * @var null|string
     */
    private $subject = null;

    /**
     * @var array
     */
    private $params = array();

    /**
     * @var array
     */
    private $attachments = array();

    /**
     * 
     * 
     * @var Template
     */
    private $template = null;
    
    /**
     * 
     * 
     * @param string|Template $template
     * @param array $params
     */
    public function __construct($template, $params){
        $this->mailer = new Mailer();
        $this->message = new MailMessage();
        $this->setTemplate($template);
        $this->setParams($params);

    }

    /**
     * @param $encoding
     * @return $this
     */
    public function setEncoding($encoding){
        $this->encoding = $encoding;
        return $this;
    }

    /**
     * @param bool $state
     * @return $this
     */
    public function setHtml($state){
        $this->isHtml = (bool)$state;
        return $this;
    }

    /**
     * @param string $charset
     * @return $this
     */
    public function setCharset($charset){
        $this->charset = $charset;
        return $this;
    }

    /**
     * @param string $body
     * @return $this
     */
    public function setBody($body){
        $this->content = $body;
        return $this;
    }

    /**
     * @param string $subject
     * @return $this
     */
    public function setSubject($subject){
        $this->subject = $subject;
        return $this;
    }

    /**
     * @param string|AddressInterface $address
     * @param string|null $name
     * @return $this
     */
    public function addAddress($address, $name = null){
        $this->message->addTo($address, $name);
        return $this;
    }

    /**
     * @param string $address|AddressInterface
     * @param string|null $name
     * @return $this
     */
    public function addBcc($address, $name = null){
        $this->message->addBcc($address, $name);
        return $this;
    }

    /**
     * @return Mailer
     */
    protected function getMailer(){
        return $this->mailer;
    }

    /**
     * @return bool
     */
    protected function IsHtml(){
        return $this->isHtml;
    }

    /**
     * @param string $address
     * @param string|null $name
     * @return $this
     */
    public function addCc($address, $name = null){
        $this->message->addCc($address, $name);
        return $this;
    }

    /**
     * @param string|AddressInterface $address
     * @param string|null $name
     * @return $this
     */
    public function setFrom($address, $name = null){
        $this->message->setFrom($address, $name);
        return $this;
    }

    /**
     * @param $path
     * @param string $name
     * @param string $encoding
     * @param string $type
     * @return $this
     */
    public function addAttachment($path, $name = '', $encoding = 'base64', $type = 'application/octet-stream'){
        $attachment = new MimePart(@fopen($path, 'r'));
        $attachment->type = $type;
        $attachment->filename = $name;
        $attachment->encoding = $encoding;
        $this->attachments[] = $attachment;

        return $this;
    }

    /**
     * @param $params
     * @return $this
     */
    public function setParams($params){
        if(is_string($params)){
            $params = explode(",",$params);
        }
        $this->params = $params;
        return $this;
    }

    /**
     * @param Template $template
     * @return $this
     */
    public function setTemplate(Template $template){
        $this->template = $template;
        return $this;
    }
    
    /**
     * 
     * Send email
     */
    public function send(){
        $message = $this->prepareMessage();
        $this->mailer->send($message);
    }

    /**
     * @return MailMessage
     */
    protected function prepareMessage(){
        $res = $this->applyTemplate($this->params);
//        $this->message->setEncoding($this->encoding);
        $this->message->setSubject($res['subject']);
        $this->message->setBody($res['body']);


        $message = new MimeMessage();
//        var_dump($this->message->getBodyText());exit;
        $part = new MimePart($this->message->getBodyText());
        $part->charset = $this->charset;
        $part->type = $this->isHtml ? "text/html" : "text/plain";
        $content = array($part);
        if(count($this->attachments) > 0){
            foreach($this->attachments as $att){
                $content[] = $att;
            }
        }
        $message->setParts($content);
        $this->setBody($message);
        $this->message->setBody($message);
        return $this->message;
    }

    /**
     * @return mixed
     */
    public function getSubject(){
         $res = $this->applyTemplate($this->params);
         return $res['subject'];
    }


    /**
     * @return mixed
     */
    public function getBody(){
         $res = $this->applyTemplate($this->params);
         return $res['body'];
    }


    /**
     * @param $user
     * @param $password
     * @param string $host
     * @param int $port
     * @return $this
     */
    public function setSmtp($user, $password, $host = 'localhost', $port = 25){
        $this->mailer = new SmtpMailer();
        $options = array(
            'name'              => $user,
            'host'              => $host,
            'port'              => $port,
            'connection_class'  => 'login',
            'connection_config' => array(
                'username' => $user,
                'password' => $password,
            ),
        );

        if(preg_match("/^ssl:\/\//", $host)){
            $options['host'] = preg_replace("/^ssl:\/\//", "", $host);
            $options['connection_config']['ssl'] = 'ssl';
        }
        $options   = new SmtpOptions($options);
        $this->mailer->setOptions($options);
        return $this;
    }
    
    /**
     * 
     * Apply params to template
     * @param array $values
     * @return array (subject,body)
     */
    private function applyTemplate($values){
        $content = '';
        if($this->isHtml){
            $content = $this->template->getHtml();
        }else{
            $content = $this->template->getText();
        }
        $subject = $this->template->getSubject();
        $params = is_array($this->template->getParams()) ? $this->template->getParams() : explode(',',$this->template->getParams());
        foreach ($params as &$value){
            $value = trim($value);
        }
        foreach ($values as $key=>$val){
            if(array_search($key, $params) !== false){
                $content = preg_replace("/%%$key%%/", $val, $content);
                $subject = preg_replace("/%%$key%%/", $val, $subject);
            }
        }
        $content = preg_replace("/%%\w+%%/", "", $content);
        $subject = preg_replace("/%%\w+%%/", "", $subject);
        
        return array('body'=>$content, 'subject'=>$subject);
    }
    
    
    
}