<?php
namespace AppBundle\EventListener;

use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\FormEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Listener responsible to change the redirection at the end of the password resetting
 */
class PasswordListener implements EventSubscriberInterface {
	private $router;
	private $session;

	public function __construct(UrlGeneratorInterface $router, Session $session) {
		$this->router = $router;
		$this->session = $session;
	}

	public static function getSubscribedEvents() {
		return [
				FOSUserEvents::CHANGE_PASSWORD_SUCCESS => 'onChangePasswordCompleted',
		];
	}

	public function onChangePasswordCompleted(FormEvent $event) {
		$this->session->getFlashBag()->add('notice', 'Your password has been updated');
		$url = $this->router->generate('fos_user_change_password');
		$event->setResponse(new RedirectResponse($url));
	}
}