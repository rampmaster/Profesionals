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

            $target = $notification->getClient()->getUser()->getEmail();
            $output->writeln("Sent to: ".$target);
            $content =$this->getContainer()->get('templating')
                                    ->render('UserProfesionalBundle:Email:notifyevent.html.twig',
                                    array('event'=>$notification,'host'=>$this->getContainer()->get('session')->get('host')));

            $message = \Swift_Message::newInstance()
                            ->setSubject('Recordatorio de Cita')
                            ->setFrom('noreply@varavan.com')
                            ->setTo($target)
                            ->setBody($content,'text/html');
                 
            $response = $this->getContainer()->get('mailer')->send($message);
            $output->writeln("Status: ".$response);
        }
        $entityManager->flush();
        $output->writeln(count($notifications));
    }
}