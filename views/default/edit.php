<?php declare(strict_types=1);

use Mailery\Icon\Icon;
use Mailery\Web\Widget\FlashMessage;

/** @var Yiisoft\Form\Widget\Field $field */
/** @var Yiisoft\Router\UrlGeneratorInterface $urlGenerator */
/** @var Yiisoft\Yii\WebView $this */
/** @var Psr\Http\Message\ServerRequestInterface $request */
/** @var Mailery\Channel\Email\Form\ChannelForm $form */
/** @var Yiisoft\Yii\View\Csrf $csrf */

$this->setTitle('Edit channel #' . $channel->getId());

?><div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pb-2 mb-3">
            <h1 class="h3">Edit channel #<?= $channel->getId(); ?></h1>
            <div class="btn-toolbar float-right">
                <a class="btn btn-sm btn-info mx-sm-1 mb-2" href="<?= $urlGenerator->generate($channel->getViewRouteName(), $channel->getViewRouteParams()); ?>">
                    <?= Icon::widget()->name('eye')->options(['class' => 'mr-1']); ?>
                    View
                </a>
                <a class="btn btn-sm btn-outline-secondary mx-sm-1 mb-2" href="<?= $urlGenerator->generate('/channel/default/index'); ?>">
                    Back
                </a>
            </div>
        </div>
    </div>
</div>
<div class="mb-2"></div>
<div class="row">
    <div class="col-12 col-xl-4">
        <?= FlashMessage::widget(); ?>
    </div>
</div>
<div class="mb-2"></div>

<?= $this->render('_form', compact('csrf', 'field', 'form')) ?>
