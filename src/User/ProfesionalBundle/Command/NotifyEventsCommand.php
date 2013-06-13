<?php
namespace User\ProfesionalBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class NotifyEventsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:notify:events')
            ->setDescription('Notificar Eventos')
            ->addOption('size', null, InputOption::VALUE_OPTIONAL, 'Numero max de envíos')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {


        //$context = $this->getContainer()->get('router')->getContext();
        //$context->setHost('varavan.com');
        //$context->setScheme('http');
        //$context->setBaseUrl('/');
        

        $entityManager = $this->getContainer()->get('doctrine')->getManager();

        if ($input->getOption('size')) {
            $limite = $input->getOption('size');
        }

        $notifications = $entityManager->createQuery("SELECT e FROM UserProfesionalBundle:ProfessionalEvent e WHERE e.notified_at IS NULL AND e.notify = TRUE AND e.notify_at < :now")
                                        ->setParameter('now',new \DateTime())->getResult();
        foreach($notifications as $notification){
            $token = md5(time()."_token");
            $notification->setClientAccessToken($token);
            //$notification->setNotifiedAt(new \DateTime());
            $entityManager->persist($notification);

            $host = $professional->getUsername();

            $professional = $notification->getProfessional();
            //$professional->getStyle()->get

            $target = $notification->getClient()->getUser()->getEmail();
            $content =$this->getContainer()->get('templating')
                                    ->render('UserProfesionalBundle:Email:notifyevent.html.twig',
                                    array('event'=>$notification,'host'=>$host));

            $message = \Swift_Message::newInstance()
                            ->setSubject('Recordatorio de Cita')
                            ->setFrom('noreply@varavan.com')
                            ->setTo($target)
                            ->setBody($content,'text/html');
                 
            $this->getContainer()->get('mailer')->send($message);
            $output->writeln(" - Notificaion para '".$target."' cargada");
        }
        $entityManager->flush();
        $output->writeln(count($notifications));

        $transport = $this->getContainer()->get('mailer')->getTransport();
        if (!$transport instanceof \Swift_Transport_SpoolTransport) {
            return;
        }

        $spool = $transport->getSpool();
        if (!$spool instanceof \Swift_MemorySpool) {
            return;
        }
        $output->writeln("Enviando emails...");
        $spool->flushQueue($this->getContainer()->get('swiftmailer.transport.real'));

    }
}