<?php defined('ALTUMCODE') || die() ?>

<ul class="list-style-none m-0">

    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->campaigns_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->campaigns_limit ? null : 'text-muted' ?>">
            <?= sprintf(language()->global->plan_settings->campaigns_limit, '<strong>' . ($data->plan_settings->campaigns_limit == -1 ? language()->global->unlimited : nr($data->plan_settings->campaigns_limit)) . '</strong>') ?>
        </div>
    </li>

    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->notifications_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->notifications_limit ? null : 'text-muted' ?>">
            <?= sprintf(language()->global->plan_settings->notifications_limit, '<strong>' . ($data->plan_settings->notifications_limit == -1 ? language()->global->unlimited : nr($data->plan_settings->notifications_limit)) . '</strong>') ?>
        </div>
    </li>

    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->notifications_impressions_limit ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->notifications_impressions_limit ? null : 'text-muted' ?>">
            <?= sprintf(language()->global->plan_settings->notifications_impressions_limit, '<strong>' . ($data->plan_settings->notifications_impressions_limit == -1 ? language()->global->unlimited : nr($data->plan_settings->notifications_impressions_limit)) . '</strong>') ?>
        </div>
    </li>

    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->track_notifications_retention ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->track_notifications_retention ? null : 'text-muted' ?>">
            <?= sprintf(language()->global->plan_settings->track_notifications_retention, '<strong>' . ($data->plan_settings->track_notifications_retention == -1 ? language()->global->unlimited : nr($data->plan_settings->track_notifications_retention)) . '</strong>') ?>
        </div>
    </li>

    <?php $enabled_notifications = array_filter((array) $data->plan_settings->enabled_notifications) ?>
    <?php $enabled_notifications_count = count($enabled_notifications) ?>
    <?php
        $enabled_notifications_string = implode(', ', array_map(function($key) {
            return language()->notification->{mb_strtolower($key)}->name;
        }, array_keys($enabled_notifications)));
    ?>
    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $enabled_notifications_count ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        <div class="<?= $enabled_notifications_count ? null : 'text-muted' ?>">
            <?php if($enabled_notifications_count == count(\Altum\Notification::get_config())): ?>
                <?= language()->global->plan_settings->enabled_notifications_all ?>
            <?php else: ?>
                <span data-toggle="tooltip" title="<?= $enabled_notifications_string ?>">
                    <?= sprintf(language()->global->plan_settings->enabled_notifications_x, '<strong>' . nr($enabled_notifications_count) . '</strong>') ?>
                </span>
            <?php endif ?>
        </div>
    </li>

    <?php foreach(require APP_PATH . 'includes/simple_user_plan_settings.php' as $plan_setting): ?>
    <li class="d-flex align-items-baseline mb-2">
        <i class="fa fa-fw fa-sm mr-3 <?= $data->plan_settings->{$plan_setting} ? 'fa-check-circle text-success' : 'fa-times-circle text-muted' ?>"></i>
        <div class="<?= $data->plan_settings->{$plan_setting} ? null : 'text-muted' ?>">
            <?= language()->global->plan_settings->{$plan_setting} ?>
        </div>
    </li>
    <?php endforeach ?>
</ul>
