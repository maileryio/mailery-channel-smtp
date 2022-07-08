<?php declare(strict_types=1);

use Mailery\Web\Widget\FlashMessage;
use Yiisoft\Yii\Widgets\ContentDecorator;

/** @var Mailery\Channel\Smtp\Form\ChannelForm $form */
/** @var Yiisoft\Yii\WebView $this */
/** @var Yiisoft\Yii\View\Csrf $csrf */

?>

<?= ContentDecorator::widget()
    ->viewFile('@vendor/maileryio/mailery-channel-smtp/views/default/_layout.php')
    ->parameters(compact('channel', 'csrf'))
    ->begin(); ?>

<div class="mb-2"></div>
<div class="row">
    <div class="col-12">
        <?= FlashMessage::widget(); ?>
    </div>
</div>
<div class="mb-2"></div>

<div class="row">
    <div class="col-12">
        <?= $this->render('_form', compact('csrf', 'form')) ?>
    </div>
</div>

<?= ContentDecorator::end() ?>