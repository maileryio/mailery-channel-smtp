<?php declare(strict_types=1);

use Mailery\Web\Widget\FlashMessage;
use Yiisoft\Form\Widget\Form;
use Yiisoft\Html\Html;
use Yiisoft\Yii\Widgets\ContentDecorator;

/** @var Yiisoft\Yii\WebView $this */
/** @var Psr\Http\Message\ServerRequestInterface $request */
/** @var Mailery\Brand\Entity\Brand $brand */
/** @var Mailery\Channel\Email\Form\SettingsForm $form */
/** @var string $csrf */
?>

<?= ContentDecorator::widget()
    ->viewFile('@vendor/maileryio/mailery-brand/views/settings/_layout.php')
    ->begin(); ?>

<div class="mb-5"></div>
<div class="row">
    <div class="col-6">
        <?= FlashMessage::widget(); ?>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <?= Form::widget()
            ->action($urlGenerator->generate('/brand/settings/domain'))
            ->options(
                [
                    'id' => 'form-brand',
                    'csrf' => $csrf,
                    'enctype' => 'multipart/form-data',
                ]
            )
            ->begin(); ?>

        <h3 class="h6">Sending domain</h3>
        <div class="mb-4"></div>

        <?= $field->config($form, 'domain'); ?>

        <?= Html::submitButton(
            'Save',
            [
                'class' => 'btn btn-primary float-right mt-2'
            ]
        ); ?>

        <?= Form::end(); ?>
    </div>
</div>

<div class="row">
    <div class="col-6">
        <h3 class="h6">Domain verification</h3>
        <div class="form-text text-muted">To complete the verification process, you have to add all of the following records to the DNS configuration for your domain.</div>
        <div class="mb-4"></div>


    </div>
</div>

<?= ContentDecorator::end() ?>
