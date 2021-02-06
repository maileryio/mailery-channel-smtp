<?php

namespace Mailery\Channel\Controller;

use Mailery\Brand\Form\BrandForm;
use Mailery\Brand\Service\BrandCrudService;
use Mailery\Brand\BrandLocatorInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Yiisoft\Http\Method;
use Yiisoft\Router\UrlGeneratorInterface as UrlGenerator;
use Yiisoft\Yii\View\ViewRenderer;
use Psr\Http\Message\ResponseFactoryInterface as ResponseFactory;
use Yiisoft\Validator\ValidatorInterface;
use Yiisoft\Session\Flash\FlashInterface;

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
     * @var BrandCrudService
     */
    private BrandCrudService $BrandCrudService;

    /**
     * @var BrandLocatorInterface
     */
    private BrandLocatorInterface $brandLocator;

    /**
     * @param ViewRenderer $viewRenderer
     * @param ResponseFactory $responseFactory
     * @param UrlGenerator $urlGenerator
     * @param BrandCrudService $BrandCrudService
     * @param BrandLocatorInterface $brandLocator
     */
    public function __construct(
        ViewRenderer $viewRenderer,
        ResponseFactory $responseFactory,
        UrlGenerator $urlGenerator,
        BrandCrudService $BrandCrudService,
        BrandLocatorInterface $brandLocator
    ) {
        $this->viewRenderer = $viewRenderer
            ->withController($this)
            ->withViewBasePath(dirname(dirname(__DIR__)) . '/views');

        $this->responseFactory = $responseFactory;
        $this->urlGenerator = $urlGenerator;
        $this->BrandCrudService = $BrandCrudService;
        $this->brandLocator = $brandLocator;
    }

    /**
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param FlashInterface $flash
     * @param BrandForm $form
     * @return Response
     */
    public function basic(Request $request, ValidatorInterface $validator, FlashInterface $flash, BrandForm $form): Response
    {
        $body = $request->getParsedBody();
        $brand = $this->brandLocator->getBrand();

        $form = $form->withBrand($brand);

        if (($request->getMethod() === Method::POST) && $form->load($body) && $form->validate($validator)) {
            $this->BrandCrudService->update($brand, $form);

            $flash->add(
                'success',
                [
                    'body' => 'Settings have been saved!',
                ],
                true
            );
        }

        return $this->viewRenderer->render('basic', compact('brand', 'form'));
    }
}
