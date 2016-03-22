<?php
/**
 * Created by PhpStorm.
 * User: a.itsekson
 * Date: 20.06.14
 * Time: 14:44
 */

namespace Icekson\Utils\Mail;

use Doctrine\Common\Persistence\ObjectManager;

class QueuedTemplateMailer extends TemplateMailer implements Mailer{

    /**
     * @var ObjectManager
     */
    private $manager = null;

    private $entityClass = '\Entity\MailQueue';
    private $entityCopyClass = '\Entity\MailQueueCc';


    public function __construct($template, $params, ObjectManager $manager){
        parent::__construct($template, $params);
        $this->setObjectManager($manager);
    }

    /**
     * @param string $entityClass
     */
    public function setEntityClass($entityClass)
    {
        $this->entityClass = $entityClass;
    }

    /**
     * @return string
     */
    public function getEntityClass()
    {
        return $this->entityClass;
    }

    /**
     * @param string $entityCopyClass
     */
    public function setEntityCopyClass($entityCopyClass)
    {
        $this->entityCopyClass = $entityCopyClass;
    }

    /**
     * @return string
     */
    public function getEntityCopyClass()
    {
        return $this->entityCopyClass;
    }

    /**
     * @param ObjectManager $repo
     * @return $this
     */
    public function setObjectManager(ObjectManager $manager){
        $this->manager = $manager;
        return $this;
    }

    public function send(){
        $message  =  $this->prepareMessage();
        foreach($message->getTo() as $to){
            $queue = new $this->entityClass();
            $queue->setSubject($message->getSubject());
            $queue->setBody($message->getBodyText());
            $queue->setAddedAt(new \DateTime());
            $queue->setAttempts(0);
            $queue->setFromEmail($message->getFrom()->current()->getEmail());
            $queue->setFromName($message->getFrom()->current()->getName());
            $queue->setIsHtml($this->IsHtml());
            $queue->setIsSent(false);
            $queue->setToEmail($to->getEmail());
            $queue->setToName($to->getName());
            $this->manager->persist($queue);
            $this->manager->flush($queue);
            $entityCopyClass = $this->getEntityCopyClass();
            foreach($message->getBcc() as $bcc){
                $queueBcc = new $entityCopyClass();
                $queueBcc->setEmail($bcc->getEmail());
                $queueBcc->setName($bcc->getName());
                $queueBcc->setMailQueue($queue);
                $queueBcc->setType('bcc');
                $this->manager->persist($queueBcc);
                $this->manager->flush($queueBcc);
            }
        }
        $this->manager->flush();
    }


} 