<?php

namespace Mailery\Channel\Email\Controller;

use Mailery\Channel\Email\Form\DomainForm;
use Mailery\Channel\Email\Service\DomainCrudService;
use Mailery\Brand\BrandLocatorInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Yiisoft\Http\Method;
use Yiisoft\Router\UrlGeneratorInterface as UrlGenerator;
use Yiisoft\Yii\View\ViewRenderer;
use Psr\Http\Message\ResponseFactoryInterface as ResponseFactory;
use Yiisoft\Validator\ValidatorInterface;
use Yiisoft\Session\Flash\FlashInterface;
use Mailery\Channel\Email\Repository\DomainRepository;
use Mailery\Channel\Email\ValueObject\DomainValueObject;

class SettingsController
{
    /**
     * @var ViewRenderer
     */
    private ViewRenderer $viewRenderer;

    /**
     * @var ResponseFactory
     */
    private ResponseFactory $responseFactory;

    /**
     * @var UrlGenerator
     */
    private UrlGenerator $urlGenerator;

    /**
     * @var DomainRepository
     */
    private DomainRepository $domainRepo;

    /**
     * @var DomainCrudService
     */
    private DomainCrudService $domainCrudService;

    /**
     * @var BrandLocatorInterface
     */
    private BrandLocatorInterface $brandLocator;

    /**
     * @param ViewRenderer $viewRenderer
     * @param ResponseFactory $responseFactory
     * @param UrlGenerator $urlGenerator
     * @param DomainCrudService $domainCrudService
     * @param BrandLocatorInterface $brandLocator
     */
    public function __construct(
        ViewRenderer $viewRenderer,
        ResponseFactory $responseFactory,
        UrlGenerator $urlGenerator,
        DomainRepository $domainRepo,
        DomainCrudService $domainCrudService,
        BrandLocatorInterface $brandLocator
    ) {
        $this->viewRenderer = $viewRenderer
            ->withController($this)
            ->withViewBasePath(dirname(dirname(__DIR__)) . '/views');

        $this->responseFactory = $responseFactory;
        $this->urlGenerator = $urlGenerator;
        $this->domainRepo = $domainRepo->withBrand($brandLocator->getBrand());
        $this->domainCrudService = $domainCrudService;
        $this->brandLocator = $brandLocator;
    }

    /**
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param FlashInterface $flash
     * @param DomainForm $form
     * @return Response
     */
    public function domain(Request $request, ValidatorInterface $validator, FlashInterface $flash, DomainForm $form): Response
    {
        $body = $request->getParsedBody();
        $domain = $this->domainRepo->findOne();

        if ($domain !== null) {
            $form = $form->withDomain($domain);
        }

        if (($request->getMethod() === Method::POST) && $form->load($body) && $form->validate($validator)) {
            $valueObject = DomainValueObject::fromForm($form)
                ->withBrand($this->brandLocator->getBrand());

            if ($domain !== null) {
                if (empty($valueObject->getDomain())) {
                    $this->domainCrudService->delete($domain);
                } else {
                    $this->domainCrudService->update($domain, $valueObject);
                }
            } else {
                $domain = $this->domainCrudService->create($valueObject);
            }

            $flash->add(
                'success',
                [
                    'body' => 'Settings have been saved!',
                ],
                true
            );
        }

        $provider = new \Mesour\DnsChecker\Providers\DnsRecordProvider();
        $checker = new \Mesour\DnsChecker\DnsChecker($provider);

        $dnsRecordSet = $checker->getDnsRecordSet('mail.automotolife.com', DNS_MX + DNS_TXT);
var_dump($dnsRecordSet->getRecords());exit;
        return $this->viewRenderer->render('domain', compact('form'));
    }
}
