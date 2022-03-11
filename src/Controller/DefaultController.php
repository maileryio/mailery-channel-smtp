<?php

namespace Mailery\Channel\Email\Controller;

use Mailery\Channel\Repository\ChannelRepository;
use Mailery\Channel\Email\Service\ChannelCrudService;
use Mailery\Channel\Email\Form\ChannelForm;
use Mailery\Channel\Email\ValueObject\ChannelValueObject;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Yiisoft\Yii\View\ViewRenderer;
use Psr\Http\Message\ResponseFactoryInterface as ResponseFactory;
use Yiisoft\Router\UrlGeneratorInterface as UrlGenerator;
use Yiisoft\Http\Method;
use Yiisoft\Http\Status;
use Yiisoft\Http\Header;
use Yiisoft\Validator\ValidatorInterface;
use Yiisoft\Session\Flash\FlashInterface;
use Yiisoft\Router\CurrentRoute;

class DefaultController
{
    private const PAGINATION_INDEX = 10;

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
     * @var ChannelRepository
     */
    private ChannelRepository $channelRepo;

    /**
     * @var ChannelCrudService
     */
    private ChannelCrudService $channelCrudService;

    /**
     * @param ViewRenderer $viewRenderer
     * @param ResponseFactory $responseFactory
     * @param UrlGenerator $urlGenerator
     * @param ChannelRepository $channelRepo
     * @param ChannelCrudService $channelCrudService
     */
    public function __construct(
        ViewRenderer $viewRenderer,
        ResponseFactory $responseFactory,
        UrlGenerator $urlGenerator,
        ChannelRepository $channelRepo,
        ChannelCrudService $channelCrudService
    ) {
        $this->viewRenderer = $viewRenderer
            ->withController($this)
            ->withViewPath(dirname(dirname(__DIR__)) . '/views');

        $this->responseFactory = $responseFactory;
        $this->urlGenerator = $urlGenerator;
        $this->channelRepo = $channelRepo;
        $this->channelCrudService = $channelCrudService;
    }

    /**
     * @param CurrentRoute $currentRoute
     * @return Response
     */
    public function view(CurrentRoute $currentRoute): Response
    {
        $channelId = $currentRoute->getArgument('id');
        if (empty($channelId) || ($channel = $this->channelRepo->findByPK($channelId)) === null) {
            return $this->responseFactory->createResponse(Status::NOT_FOUND);
        }

        return $this->viewRenderer->render('view', compact('channel'));
    }

    /**
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param ChannelForm $form
     * @return Response
     */
    public function create(Request $request, ValidatorInterface $validator, ChannelForm $form): Response
    {
        $body = $request->getParsedBody();

        if (($request->getMethod() === Method::POST) && $form->load($body) && $validator->validate($form)->isValid()) {
            $valueObject = ChannelValueObject::fromForm($form);
            $channel = $this->channelCrudService->create($valueObject);

            return $this->responseFactory
                ->createResponse(Status::FOUND)
                ->withHeader(Header::LOCATION, $this->urlGenerator->generate($channel->getViewRouteName(), $channel->getViewRouteParams()));
        }

        return $this->viewRenderer->render('create', compact('form'));
    }

    /**
     * @param Request $request
     * @param CurrentRoute $currentRoute
     * @param ValidatorInterface $validator
     * @param FlashInterface $flash
     * @param ChannelForm $form
     * @return Response
     */
    public function edit(Request $request, CurrentRoute $currentRoute, ValidatorInterface $validator, FlashInterface $flash, ChannelForm $form): Response
    {
        $body = $request->getParsedBody();
        $channelId = $currentRoute->getArgument('id');
        if (empty($channelId) || ($channel = $this->channelRepo->findByPK($channelId)) === null) {
            return $this->responseFactory->createResponse(Status::NOT_FOUND);
        }

        $form = $form->withEntity($channel);

        if (($request->getMethod() === Method::POST) && $form->load($body) && $validator->validate($form)->isValid()) {
            $valueObject = ChannelValueObject::fromForm($form);
            $this->channelCrudService->update($channel, $valueObject);

            $flash->add(
                'success',
                [
                    'body' => 'Data have been saved!',
                ],
                true
            );
        }

        return $this->viewRenderer->render('edit', compact('form', 'channel'));
    }

    /**
     * @param CurrentRoute $currentRoute
     * @param ChannelCrudService $channelCrudService
     * @param UrlGenerator $urlGenerator
     * @return Response
     */
    public function delete(CurrentRoute $currentRoute, ChannelCrudService $channelCrudService, UrlGenerator $urlGenerator): Response
    {
        $channelId = $currentRoute->getArgument('id');
        if (empty($channelId) || ($channel = $this->channelRepo->findByPK($channelId)) === null) {
            return $this->responseFactory->createResponse(Status::NOT_FOUND);
        }

        $channelCrudService->delete($channel);

        return $this->responseFactory
            ->createResponse(Status::SEE_OTHER)
            ->withHeader(Header::LOCATION, $urlGenerator->generate('/channel/default/index'));
    }
}
