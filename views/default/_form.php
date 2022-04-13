<?php declare(strict_types=1);

use Yiisoft\Form\Widget\Form;

/** @var Mailery\Channel\Smtp\Form\ChannelForm $form */
/** @var Yiisoft\Form\Widget\Field $field */
/** @var Yiisoft\Yii\WebView $this */
/** @var Yiisoft\Yii\View\Csrf $csrf */

?>
<?= Form::widget()
        ->csrf($csrf)
        ->id('channel-smtp-form')
        ->begin(); ?>

<?= $field->text($form, 'name')->autofocus(); ?>

<?= $field->textArea($form, 'description', ['rows()' => [5]]); ?>

<?= $field->submitButton()
        ->class('btn btn-primary float-right mt-2')
        ->value($form->hasEntity() ? 'Save changes' : 'Add channel'); ?>

<?= Form::end(); ?>
