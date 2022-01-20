<?php defined('ALTUMCODE') || die() ?>

<?php ob_start() ?>
<div class="card mb-5">
    <div class="card-body">
        <h2 class="h4"><i class="fa fa-fw fa-chart-bar fa-xs text-muted"></i> <?= language()->admin_statistics->track_notifications->header ?></h2>
        <p class="text-muted"><?= language()->admin_statistics->track_notifications->subheader ?></p>
        <div class="d-flex flex-column flex-xl-row">
            <div class="mb-2 mb-xl-0 mr-4">
                <span class="font-weight-bold"><?= nr($data->total['impression']) ?></span> <?= language()->admin_statistics->track_notifications->chart_impression ?>
            </div>
            <div class="mb-2 mb-xl-0 mr-4">
                <span class="font-weight-bold"><?= nr($data->total['hover']) ?></span> <?= language()->admin_statistics->track_notifications->chart_hover ?>
            </div>
            <div class="mb-2 mb-xl-0 mr-4">
                <span class="font-weight-bold"><?= nr($data->total['click']) ?></span> <?= language()->admin_statistics->track_notifications->chart_click ?>
            </div>
            <div class="mb-2 mb-xl-0 mr-4">
                <span class="font-weight-bold"><?= nr($data->total['form_submission']) ?></span> <?= language()->admin_statistics->track_notifications->chart_form_submission ?>
            </div>
        </div>

        <div class="chart-container">
            <canvas id="track_notifications"></canvas>
        </div>
    </div>
</div>
<?php $html = ob_get_clean() ?>

<?php ob_start() ?>
<script>
    let impression_color = css.getPropertyValue('--teal');
    let hover_color = css.getPropertyValue('--indigo');
    let click_color = css.getPropertyValue('--cyan');
    let form_submission_color = css.getPropertyValue('--blue');

    /* Display chart */
    new Chart(document.getElementById('track_notifications').getContext('2d'), {
        type: 'line',
        data: {
            labels: <?= $data->track_notifications_chart['labels'] ?>,
            datasets: [
                {
                    label: <?= json_encode(language()->admin_statistics->track_notifications->chart_impression) ?>,
                    data: <?= $data->track_notifications_chart['impression'] ?? '[]' ?>,
                    backgroundColor: impression_color,
                    borderColor: impression_color,
                    fill: false
                },
                {
                    label: <?= json_encode(language()->admin_statistics->track_notifications->chart_hover) ?>,
                    data: <?= $data->track_notifications_chart['hover'] ?? '[]' ?>,
                    backgroundColor: hover_color,
                    borderColor: hover_color,
                    fill: false
                },
                {
                    label: <?= json_encode(language()->admin_statistics->track_notifications->chart_click) ?>,
                    data: <?= $data->track_notifications_chart['click'] ?? '[]' ?>,
                    backgroundColor: click_color,
                    borderColor: click_color,
                    fill: false
                },
                {
                    label: <?= json_encode(language()->admin_statistics->track_notifications->chart_form_submission) ?>,
                    data: <?= $data->track_notifications_chart['form_submission'] ?? '[]' ?>,
                    backgroundColor: form_submission_color,
                    borderColor: form_submission_color,
                    fill: false
                }
            ]
        },
        options: chart_options
    });
</script>
<?php $javascript = ob_get_clean() ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
