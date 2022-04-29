<?php declare(strict_types=1);

use Mailery\Channel\Smtp\Entity\SmtpChannel;
use Yiisoft\Yii\DataView\DetailView;
use Yiisoft\Yii\Widgets\ContentDecorator;

/** @var Yiisoft\Yii\WebView $this */
/** @var Mailery\Channel\Smtp\Entity\SmtpChannel $channel */

$this->setTitle($channel->getName());

?>

<?= ContentDecorator::widget()
    ->viewFile('@vendor/maileryio/mailery-channel-smtp/views/default/_layout.php')
    ->parameters(compact('channel', 'csrf'))
    ->begin(); ?>

<div class="mb-2"></div>
<div class="row">
    <div class="col-12">
        <h6 class="font-weight-bold">General details</h6>
    </div>
</div>

<div class="mb-2"></div>
<div class="row">
    <div class="col-12">
        <?= DetailView::widget()
            ->model($channel)
            ->options([
                'class' => 'table table-top-borderless detail-view',
            ])
            ->emptyValue('<span class="text-muted">(not set)</span>')
            ->attributes([
                [
                    'label' => 'Name',
                    'value' => function (SmtpChannel $data, $index) {
                        return $data->getName();
                    },
                ],
                [
                    'label' => 'Description',
                    'value' => function (SmtpChannel $data, $index) {
                        return $data->getDescription();
                    },
                ],
            ]);
        ?>
    </div>
</div>

<?= ContentDecorator::end() ?>
