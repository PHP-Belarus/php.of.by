<?php

namespace CommonBundle\Controller;

use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Templating\EngineInterface;

/**
 * Here we suppress phpmd warning because we have to use all this objects.
 *
 * SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
abstract class AbstractServiceController
{
    /** @var RouterInterface */
    private $router;

    /** @var SerializerInterface */
    private $serializer;

    /** @var Session */
    private $session;

    /** @var AuthorizationCheckerInterface */
    private $authorizationChecker;

    /** @var EngineInterface */
    private $templating;

    /** @var FormFactoryInterface */
    private $formFactory;

    /** @var TokenStorageInterface */
    private $tokenStorage;

    /** @var CsrfTokenManagerInterface */
    private $csrfTokenManager;

    /**
     * @param RouterInterface $router
     *
     * @return AbstractServiceController
     */
    public function setRouter(RouterInterface $router): AbstractServiceController
    {
        $this->router = $router;

        return $this;
    }

    /**
     * @param SerializerInterface $serializer
     *
     * @return AbstractServiceController
     */
    public function setSerializer(SerializerInterface $serializer): AbstractServiceController
    {
        $this->serializer = $serializer;

        return $this;
    }

    /**
     * @param Session $session
     *
     * @return AbstractServiceController
     */
    public function setSession(Session $session): AbstractServiceController
    {
        $this->session = $session;

        return $this;
    }

    /**
     * @param AuthorizationCheckerInterface $authorizationChecker
     *
     * @return AbstractServiceController
     */
    public function setAuthorizationChecker(
        AuthorizationCheckerInterface $authorizationChecker
    ): AbstractServiceController {
        $this->authorizationChecker = $authorizationChecker;

        return $this;
    }

    /**
     * @param EngineInterface $templating
     *
     * @return AbstractServiceController
     */
    public function setTemplating(EngineInterface $templating): AbstractServiceController
    {
        $this->templating = $templating;

        return $this;
    }

    /**
     * @param FormFactoryInterface $formFactory
     *
     * @return AbstractServiceController
     */
    public function setFormFactory(FormFactoryInterface $formFactory): AbstractServiceController
    {
        $this->formFactory = $formFactory;

        return $this;
    }

    /**
     * @param TokenStorageInterface $tokenStorage
     *
     * @return AbstractServiceController
     */
    public function setTokenStorage(TokenStorageInterface $tokenStorage): AbstractServiceController
    {
        $this->tokenStorage = $tokenStorage;

        return $this;
    }

    /**
     * @param CsrfTokenManagerInterface $csrfTokenManager
     *
     * @return AbstractServiceController
     */
    public function setCsrfTokenManager(CsrfTokenManagerInterface $csrfTokenManager): AbstractServiceController
    {
        $this->csrfTokenManager = $csrfTokenManager;

        return $this;
    }

    /**
     * @return RouterInterface
     */
    protected function getRouter(): RouterInterface
    {
        return $this->router;
    }

    /**
     * @return SerializerInterface
     */
    protected function getSerializer(): SerializerInterface
    {
        return $this->serializer;
    }

    /**
     * @return Session
     */
    protected function getSession(): Session
    {
        return $this->session;
    }

    /**
     * @return AuthorizationCheckerInterface
     */
    protected function getAuthorizationChecker(): AuthorizationCheckerInterface
    {
        return $this->authorizationChecker;
    }

    /**
     * @return EngineInterface
     */
    protected function getTemplating(): EngineInterface
    {
        return $this->templating;
    }

    /**
     * @return FormFactoryInterface
     */
    protected function getFormFactory(): FormFactoryInterface
    {
        return $this->formFactory;
    }

    /**
     * @return TokenStorageInterface
     */
    protected function getTokenStorage(): TokenStorageInterface
    {
        return $this->tokenStorage;
    }

    /**
     * @return CsrfTokenManagerInterface
     */
    protected function getCsrfTokenManager(): CsrfTokenManagerInterface
    {
        return $this->csrfTokenManager;
    }

    /**
     * Generates a URL from the given parameters.
     *
     * @see UrlGeneratorInterface
     *
     * @final
     *
     * @param string $route
     * @param array  $parameters
     * @param int    $referenceType
     *
     * @return string
     */
    protected function generateUrl(
        string $route,
        array $parameters = [],
        int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH
    ): string {
        return $this->getRouter()->generate($route, $parameters, $referenceType);
    }

    /**
     * Returns a RedirectResponse to the given URL.
     *
     * @final
     *
     * @param string $url
     * @param int    $status
     *
     * @return RedirectResponse
     */
    protected function redirect(string $url, int $status = 302): RedirectResponse
    {
        return new RedirectResponse($url, $status);
    }

    /**
     * Returns a RedirectResponse to the given route with the given parameters.
     *
     * @final
     *
     * @param string $route
     * @param array  $parameters
     * @param int    $status
     *
     * @return RedirectResponse
     */
    protected function redirectToRoute(string $route, array $parameters = [], int $status = 302): RedirectResponse
    {
        return $this->redirect($this->generateUrl($route, $parameters), $status);
    }

    /**
     * Returns a JsonResponse that uses the serializer component if enabled, or json_encode.
     *
     * @final
     *
     * @param mixed $data
     * @param int   $status
     * @param array $headers
     * @param array $context
     *
     * @return JsonResponse
     */
    protected function json($data, int $status = 200, array $headers = [], array $context = []): JsonResponse
    {
        $json = $this->getSerializer()->serialize($data, 'json', array_merge([
            'json_encode_options' => JsonResponse::DEFAULT_ENCODING_OPTIONS,
        ], $context));

        return new JsonResponse($json, $status, $headers, true);
    }

    /**
     * Returns a BinaryFileResponse object with original or customized file name and disposition header.
     *
     * @final
     *
     * @param \SplFileInfo|string $file        File object or path to file to be sent as response
     * @param string              $fileName,
     * @param string              $disposition
     *
     * @return BinaryFileResponse
     */
    protected function file(
        $file,
        string $fileName = null,
        string $disposition = ResponseHeaderBag::DISPOSITION_ATTACHMENT
    ): BinaryFileResponse {
        $response = new BinaryFileResponse($file);
        $response->setContentDisposition(
            $disposition,
            null === $fileName ? $response->getFile()->getFilename() : $fileName
        );

        return $response;
    }

    /**
     * Adds a flash message to the current session for type.
     *
     * @final
     *
     * @param string $type
     * @param string $message
     *
     * @throws \LogicException
     */
    protected function addFlash(string $type, string $message): void
    {
        $this->getSession()->getFlashBag()->add($type, $message);
    }

    /**
     * Checks if the attributes are granted against the current authentication token and optionally supplied subject.
     *
     * @final
     *
     * @param mixed $attributes
     * @param mixed $subject
     *
     * @throws \LogicException
     *
     * @return bool
     */
    protected function isGranted($attributes, $subject = null): bool
    {
        return $this->getAuthorizationChecker()->isGranted($attributes, $subject);
    }

    /**
     * Throws an exception unless the attributes are granted against the current authentication token and optionally
     * supplied subject.
     *
     * @final
     *
     * @param mixed  $attributes
     * @param mixed  $subject
     * @param string $message
     *
     * @throws AccessDeniedException
     */
    protected function denyAccessUnlessGranted($attributes, $subject = null, string $message = 'Access Denied.'): void
    {
        if (!$this->isGranted($attributes, $subject)) {
            $exception = $this->createAccessDeniedException($message);
            $exception->setAttributes($attributes);
            $exception->setSubject($subject);

            throw $exception;
        }
    }

    /**
     * Returns a rendered view.
     *
     * @final
     *
     * @param string $view
     * @param array  $parameters
     *
     * @return string
     */
    protected function renderView(string $view, array $parameters = []): string
    {
        return $this->getTemplating()->render($view, $parameters);
    }

    /**
     * Renders a view.
     *
     * @final
     *
     * @param string   $view
     * @param array    $parameters
     * @param Response $response
     *
     * @return Response
     */
    protected function render(string $view, array $parameters = [], Response $response = null): Response
    {
        $content = $this->getTemplating()->render($view, $parameters);

        if (null === $response) {
            $response = new Response();
        }

        $response->setContent($content);

        return $response;
    }

    /**
     * Returns a NotFoundHttpException.
     *
     * This will result in a 404 response code. Usage example:
     *
     *     throw $this->createNotFoundException('Page not found!');
     *
     * @final
     *
     * @param string     $message
     * @param \Exception $previous
     *
     * @return NotFoundHttpException
     */
    protected function createNotFoundException(
        string $message = 'Not Found',
        \Exception $previous = null
    ): NotFoundHttpException {
        return new NotFoundHttpException($message, $previous);
    }

    /**
     * Returns an AccessDeniedException.
     *
     * This will result in a 403 response code. Usage example:
     *
     *     throw $this->createAccessDeniedException('Unable to access this page!');
     *
     * @final
     *
     * @param string     $message
     * @param \Exception $previous
     *
     * @throws \LogicException If the Security component is not available
     *
     * @return AccessDeniedException
     */
    protected function createAccessDeniedException(
        string $message = 'Access Denied.',
        \Exception $previous = null
    ): AccessDeniedException {
        return new AccessDeniedException($message, $previous);
    }

    /**
     * Creates and returns a Form instance from the type of the form.
     *
     * @final
     *
     * @param string $type
     * @param mixed  $data
     * @param array  $options
     *
     * @return FormInterface
     */
    protected function createForm(string $type, $data = null, array $options = []): FormInterface
    {
        return $this->getFormFactory()->create($type, $data, $options);
    }

    /**
     * Creates and returns a form builder instance.
     *
     * @final
     *
     * @param mixed $data
     * @param array $options
     *
     * @return FormBuilderInterface
     */
    protected function createFormBuilder($data = null, array $options = []): FormBuilderInterface
    {
        return $this->getFormFactory()->createBuilder(FormType::class, $data, $options);
    }

    /**
     * Get a user from the Security Token Storage.
     *
     * @throws \LogicException If SecurityBundle is not available
     *
     * @return mixed
     *
     * @see TokenInterface::getUser()
     *
     * @final
     */
    protected function getUser()
    {
        if (null === $token = $this->getTokenStorage()->getToken()) {
            return;
        }

        if (!is_object($user = $token->getUser())) {
            // e.g. anonymous authentication
            return;
        }

        return $user;
    }

    /**
     * Checks the validity of a CSRF token.
     *
     * @param string      $tokenId The id used when generating the token
     * @param string|null $token   The actual token sent with the request that should be validated
     *
     * @return bool
     * @final
     */
    protected function isCsrfTokenValid(string $tokenId, ?string $token): bool
    {
        return $this->getCsrfTokenManager()->isTokenValid(new CsrfToken($tokenId, $token));
    }
}
