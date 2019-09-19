<?php

namespace App\Security;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationCredentialsNotFoundException;
use Symfony\Component\Security\Core\Security;

class BanSubscriber implements EventSubscriberInterface
{
    /** @var Security */
    private $securityContext;

    /** @var UserProvider */
    private $userProvider;

    /** @var TokenStorage */
    private $tokenStorage;

    /** @var Session */
    private $session;

    public function __construct(Security $context, UserProvider $userProvider, TokenStorageInterface $tokenStorage, SessionInterface $session)
    {
        $this->securityContext = $context;
        $this->userProvider = $userProvider;
        $this->tokenStorage = $tokenStorage;
        $this->session = $session;
    }

    public function onKernelRequest()
    {
        try {
            if ($this->securityContext->isGranted('IS_AUTHENTICATED_FULLY')) {
                $user = $this->userProvider->loadUserByUsername($this->securityContext->getUser()->getUsername());

                if ($user != null && $user->isUserBanned()) {
                    $this->tokenStorage->setToken(null);
                    $this->session->invalidate();
                }
            }
        } catch (AuthenticationCredentialsNotFoundException $e) {
        }
    }

    public static function getSubscribedEvents()
    {
        return array(
            'kernel.request' => 'onKernelRequest'
        );
    }
}