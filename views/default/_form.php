<?php declare(strict_types=1);

use Yiisoft\Html\Tag\Form;
use Yiisoft\Form\Field;
use Mailery\Web\Vue\Directive;

/** @var Mailery\Channel\Smtp\Form\ChannelForm $form */
/** @var Yiisoft\Yii\WebView $this */
/** @var Yiisoft\Yii\View\Csrf $csrf */

?>
<?= Form::tag()
    ->csrf($csrf)
    ->id('channel-smtp-form')
    ->post()
    ->open(); ?>

<?= Field::text($form, 'name')->autofocus(); ?>

<?= Directive::pre(Field::textarea($form, 'description', ['rows()' => [5]])); ?>

<?= Field::submitButton()
    ->content($form->hasEntity() ? 'Save changes' : 'Add channel'); ?>

<?= Form::tag()->close(); ?>
