<?php

/**
 * Created by PhpStorm.
 * User: a.itsekson
 * Date: 20.06.14
 * Time: 14:44
 */
namespace Icekson\Utils\Mail;

/**
 * Class Template
 * @package Iz\Mail
 */
class Template {

    public function __construct($body, $subject, $params = array(), $isHtml = true){
        if($isHtml){
            $this->setHtml($body);
        }
        $this->setSubject($subject);
        $this->setParams($params);
    }

    /**
     * @var null
     */
    private $subject = null;
    /**
     * @var null
     */
    private $html = null;
    /**
     * @var null
     */
    private $text = null;
    /**
     * @var array
     */
    private $params = array();

    /**
     * @param null $html
     */
    public function setHtml($html)
    {
        $this->html = $html;
    }

    /**
     * @return null
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * @param array $params
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @param null $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return null
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param null $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @return null
     */
    public function getText()
    {
        return $this->text;
    }

}