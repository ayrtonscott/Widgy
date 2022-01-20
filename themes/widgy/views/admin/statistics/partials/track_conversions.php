<?php defined('ALTUMCODE') || die() ?>

<?php ob_start() ?>
<div class="card mb-5">
    <div class="card-body">
        <h2 class="h4"><i class="fa fa-fw fa-chart-bar fa-xs text-muted"></i> <?= language()->admin_statistics->track_conversions->header ?></h2>
        <p class="text-muted"><?= language()->admin_statistics->track_conversions->subheader ?></p>
        <div class="d-flex flex-column flex-xl-row">
            <div class="mb-2 mb-xl-0 mr-4">
                <span class="font-weight-bold"><?= nr($data->total['webhook']) ?></span> <?= language()->admin_statistics->track_conversions->chart_webhook ?>
            </div>
            <div class="mb-2 mb-xl-0 mr-4">
                <span class="font-weight-bold"><?= nr($data->total['auto_capture']) ?></span> <?= language()->admin_statistics->track_conversions->chart_auto_capture ?>
            </div>
            <div class="mb-2 mb-xl-0 mr-4">
                <span class="font-weight-bold"><?= nr($data->total['collector']) ?></span> <?= language()->admin_statistics->track_conversions->chart_collector ?>
            </div>
            <div class="mb-2 mb-xl-0 mr-4">
                <span class="font-weight-bold"><?= nr($data->total['imported']) ?></span> <?= language()->admin_statistics->track_conversions->chart_imported ?>
            </div>
        </div>

        <div class="chart-container">
            <canvas id="track_conversions"></canvas>
        </div>
    </div>
</div>
<?php $html = ob_get_clean() ?>

<?php ob_start() ?>
<script>
    let webhook_color = css.getPropertyValue('--teal');
    let auto_capture_color = css.getPropertyValue('--indigo');
    let collector_color = css.getPropertyValue('--cyan');
    let imported_color = css.getPropertyValue('--blue');

    /* Display chart */
    new Chart(document.getElementById('track_conversions').getContext('2d'), {
        type: 'line',
        data: {
            labels: <?= $data->track_conversions_chart['labels'] ?>,
            datasets: [
                {
                    label: <?= json_encode(language()->admin_statistics->track_conversions->chart_webhook) ?>,
                    data: <?= $data->track_conversions_chart['webhook'] ?? '[]' ?>,
                    backgroundColor: webhook_color,
                    borderColor: webhook_color,
                    fill: false
                },
                {
                    label: <?= json_encode(language()->admin_statistics->track_conversions->chart_auto_capture) ?>,
                    data: <?= $data->track_conversions_chart['auto_capture'] ?? '[]' ?>,
                    backgroundColor: auto_capture_color,
                    borderColor: auto_capture_color,
                    fill: false
                },
                {
                    label: <?= json_encode(language()->admin_statistics->track_conversions->chart_collector) ?>,
                    data: <?= $data->track_conversions_chart['collector'] ?? '[]' ?>,
                    backgroundColor: collector_color,
                    borderColor: collector_color,
                    fill: false
                },
                {
                    label: <?= json_encode(language()->admin_statistics->track_conversions->chart_imported) ?>,
                    data: <?= $data->track_conversions_chart['imported'] ?? '[]' ?>,
                    backgroundColor: imported_color,
                    borderColor: imported_color,
                    fill: false
                }
            ]
        },
        options: chart_options
    });
</script>
<?php $javascript = ob_get_clean() ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
