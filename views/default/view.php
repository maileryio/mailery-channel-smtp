<?php declare(strict_types=1);

use Mailery\Channel\Smtp\Entity\SmtpChannel;
use Mailery\Widget\Dataview\DetailView;

/** @var Yiisoft\Yii\WebView $this */
/** @var Mailery\Channel\Smtp\Entity\SmtpChannel $channel */

$this->setTitle($channel->getName());

?>

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
            ->data($channel)
            ->options([
                'class' => 'table table-top-borderless detail-view',
            ])
            ->emptyText('(not set)')
            ->emptyTextOptions([
                'class' => 'text-muted',
            ])
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
