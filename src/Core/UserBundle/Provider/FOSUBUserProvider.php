<?php
namespace Core\UserBundle\Provider;
 
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use HWI\Bundle\OAuthBundle\Security\Core\User\FOSUBUserProvider as BaseClass;
use Doctrine\ORM\EntityManager;
use FOS\UserBundle\Model\UserManagerInterface;

use User\ProfesionalBundle\Entity\Professional;

class FOSUBUserProvider extends BaseClass
{
    private $entityManager;
 /**
     * Constructor.
     *
     * @param UserManagerInterface $userManager FOSUB user provider.
     * @param array                $properties  Property mapping.
     */
    public function __construct(UserManagerInterface $userManager, EntityManager $entityManager, array $properties)
    {
        $this->userManager = $userManager;
        $this->entityManager = $entityManager;
        $this->properties  = $properties;
    }

    /**
     * {@inheritDoc}
     */
    public function connect($user, UserResponseInterface $response)
    {
        $property = $this->getProperty($response);
        $username = $response->getUsername();
 
        //on connect - get the access token and the user ID
        $service = $response->getResourceOwner()->getName();
 
        $setter = 'set'.ucfirst($service);
        $setter_id = $setter.'Id';
        $setter_token = $setter.'AccessToken';
 
        //we "disconnect" previously connected users
        if (null !== $previousUser = $this->userManager->findUserBy(array($property => $username))) {
            $previousUser->$setter_id(null);
            $previousUser->$setter_token(null);
            $this->userManager->updateUser($previousUser);
        }
 
        //we connect current user
        $user->$setter_id($username);
        $user->$setter_token($response->getAccessToken());
 
        $this->userManager->updateUser($user);
    }
 
    /**
     * {@inheritdoc}
     */
    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {   $rawResponse = $response->getResponse();
        $username = $response->getUsername();
        $user = $this->userManager->findUserBy(array($this->getProperty($response) => $username));
        if (null === $user) {
            $user = $this->userManager->findUserBy(array('email' => $rawResponse['emailAddress']));
        }
        //when the user is registrating

        if (null === $user) {
            $service = $response->getResourceOwner()->getName();
            $setter = 'set'.ucfirst($service);
            $setter_id = $setter.'Id';
            $setter_token = $setter.'AccessToken';
            // create new user here
            $user = $this->userManager->createUser();
            $user->$setter_id($username);
            $user->$setter_token($response->getAccessToken());
            //I have set all requested data with the user's username
            //modify here with relevant data
            $user->setUsername($username);
            $user->setEmail($username);
            $user->setPassword($username);
            $user->setEnabled(true);

            $professional = new Professional();
            $professional->setUpdatedAt(new \DateTime());
            $professional->setCreatedAt(new \DateTime());
            $professional->setUser($user);
            $user->setProfessional($professional);
            $user->addRole('ROLE_USER');
            $user->addRole('ROLE_PROFESIONAL');
            /* CUSTOM PROVIDER INFO */;
                
                $user->setName($rawResponse['firstName']);
                $user->setSurname($rawResponse['lastName']);
                $user->setEmail($rawResponse['emailAddress']);
                $user->setExternalPath($rawResponse['pictureUrl']);
                $professional->setHeadline($rawResponse['headline']);
                $skills = Array();
                $rawskills = $rawResponse['skills'];
                foreach($rawskills['values'] as $skill){
                    $skills[] = $skill['skill']['name'];
                }
                $professional->setSkills($skills);

            $this->entityManager->persist($professional);
            $this->userManager->updateUser($user);
            $this->entityManager->flush();
            
            return $user;
        }
 
        //if user exists - go with the HWIOAuth way
        $user = parent::loadUserByOAuthUserResponse($response);
 
        $serviceName = $response->getResourceOwner()->getName();
        $setter = 'set' . ucfirst($serviceName) . 'AccessToken';
 
        //update access token
        $user->$setter($response->getAccessToken());
 
        return $user;
    }
 
}