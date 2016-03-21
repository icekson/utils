<?php
/**
 * Created by PhpStorm.
 * User: a.itsekson
 * Date: 20.06.14
 * Time: 14:39
 */

namespace Icekson\Utils\Mail;


interface Mailer {

    public function setEncoding($encoding);
    public function setHtml($state);
    public function setCharset($charset);
    public function setBody($body);
    public function setSubject($subject);
    public function addAddress($address, $name = null);
    public function addBcc($address, $name = null);
    public function addCc($address, $name = null);
    public function setFrom($address, $name = null);
    public function addAttachment($path, $name = '', $encoding = 'base64', $type = 'application/octet-stream');
    public function setParams($params);
    public function setTemplate(Template $template);
    public function send();
    public function getSubject();
    public function getBody();
    public function setSmtp($user, $password, $host = 'localhost', $port = 25);
} 