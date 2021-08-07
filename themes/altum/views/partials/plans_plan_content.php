<?php defined('ALTUMCODE') || die() ?>

<ul class="pricing-feature-list">
    <li class="pricing-feature">
        <?= sprintf(language()->global->plan_settings->campaigns_limit, '<strong>' . ($data->plan_settings->campaigns_limit == -1 ? language()->global->unlimited : nr($data->plan_settings->campaigns_limit)) . '</strong>') ?>
    </li>

    <li class="pricing-feature">
        <?= sprintf(language()->global->plan_settings->notifications_limit, '<strong>' . ($data->plan_settings->notifications_limit == -1 ? language()->global->unlimited : nr($data->plan_settings->notifications_limit)) . '</strong>') ?>
    </li>

    <li class="pricing-feature">
        <?= sprintf(language()->global->plan_settings->notifications_impressions_limit, '<strong>' . ($data->plan_settings->notifications_impressions_limit == -1 ? language()->global->unlimited : nr($data->plan_settings->notifications_impressions_limit)) . '</strong>') ?>
    </li>

    <li class="pricing-feature">
        <?= sprintf(language()->global->plan_settings->track_notifications_retention, '<strong>' . ($data->plan_settings->track_notifications_retention == -1 ? language()->global->unlimited : nr($data->plan_settings->track_notifications_retention)) . '</strong>') ?>
    </li>

    <?php $enabled_notifications = array_filter((array) $data->plan_settings->enabled_notifications) ?>
    <?php $enabled_notifications_count = count($enabled_notifications) ?>
    <?php
    $enabled_notifications_string = implode(', ', array_map(function($key) {
        return language()->notification->{mb_strtolower($key)}->name;
    }, array_keys($enabled_notifications)));
    ?>
    <?php if($enabled_notifications_count == count(\Altum\Notification::get_config())): ?>
        <li class="pricing-feature"><?= language()->global->plan_settings->enabled_notifications_all ?></li>
    <?php else: ?>
        <li class="pricing-feature">
            <span data-toggle="tooltip" title="<?= $enabled_notifications_string ?>">
                <?= sprintf(language()->global->plan_settings->enabled_notifications_x, nr($enabled_notifications_count)) ?>
            </span>
        </li>
    <?php endif ?>

    <?php foreach(require APP_PATH . 'includes/simple_user_plan_settings.php' as $plan_setting): ?>
        <?php if($data->plan_settings->{$plan_setting}): ?>
            <li class="pricing-feature"><?= language()->global->plan_settings->{$plan_setting} ?></li>
        <?php else: ?>
            <li class="pricing-feature"><s><?= language()->global->plan_settings->{$plan_setting} ?></s></li>
        <?php endif ?>
    <?php endforeach ?>
</ul>

