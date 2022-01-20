<?php defined('ALTUMCODE') || die() ?>

<nav aria-label="breadcrumb">
    <ol class="custom-breadcrumbs small">
        <li>
            <a href="<?= url('admin/users') ?>"><?= language()->admin_users->breadcrumb ?></a><i class="fa fa-fw fa-angle-right"></i>
        </li>
        <li class="active" aria-current="page"><?= language()->admin_user_update->breadcrumb ?></li>
    </ol>
</nav>

<div class="d-flex justify-content-between mb-4">
    <div class="d-flex align-items-center">
        <h1 class="h3 mb-0 mr-1"><i class="fa fa-fw fa-xs fa-user text-primary-900 mr-2"></i> <?= language()->admin_user_update->header ?></h1>

        <?= include_view(THEME_PATH . 'views/admin/users/admin_user_dropdown_button.php', ['id' => $data->user->user_id]) ?>
    </div>
</div>

<?= \Altum\Alerts::output_alerts() ?>

<?php //ALTUMCODE:DEMO if(DEMO) {$data->user->email = 'hidden@demo.com'; $data->user->name = $data->user->ip = 'hidden on demo';} ?>

<div class="card <?= \Altum\Alerts::has_field_errors() ? 'border-danger' : null ?>">
    <div class="card-body">

        <form action="" method="post" role="form" enctype="multipart/form-data">
            <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />

            <div class="form-group">
                <label for="name"><?= language()->admin_users->main->name ?></label>
                <input id="name" type="text" name="name" class="form-control form-control-lg <?= \Altum\Alerts::has_field_errors('name') ? 'is-invalid' : null ?>" value="<?= $data->user->name ?>" required="required" />
                <?= \Altum\Alerts::output_field_error('name') ?>
            </div>

            <div class="form-group">
                <label for="email"><?= language()->admin_users->main->email ?></label>
                <input id="email" type="email" name="email" class="form-control form-control-lg <?= \Altum\Alerts::has_field_errors('email') ? 'is-invalid' : null ?>" value="<?= $data->user->email ?>" required="required" />
                <?= \Altum\Alerts::output_field_error('email') ?>
            </div>

            <div class="form-group">
                <label for="status"><?= language()->admin_users->main->status ?></label>
                <select id="status" name="status" class="form-control form-control-lg">
                    <option value="2" <?= $data->user->status == 2 ? 'selected="selected"' : null ?>><?= language()->admin_users->main->status_disabled ?></option>
                    <option value="1" <?= $data->user->status == 1 ? 'selected="selected"' : null ?>><?= language()->admin_users->main->status_active ?></option>
                    <option value="0" <?= $data->user->status == 0 ? 'selected="selected"' : null ?>><?= language()->admin_users->main->status_unconfirmed ?></option>
                </select>
            </div>

            <div class="form-group">
                <label for="type"><?= language()->admin_users->main->type ?></label>
                <select id="type" name="type" class="form-control form-control-lg">
                    <option value="1" <?= $data->user->type == 1 ? 'selected="selected"' : null ?>><?= language()->admin_users->main->type_admin ?></option>
                    <option value="0" <?= $data->user->type == 0 ? 'selected="selected"' : null ?>><?= language()->admin_users->main->type_user ?></option>
                </select>
                <small class="form-text text-muted"><?= language()->admin_users->main->type_help ?></small>
            </div>

            <div class="mt-5"></div>

            <h2 class="h4"><?= language()->admin_user_update->plan->header ?></h2>

            <div class="form-group">
                <label for="plan_id"><?= language()->admin_user_update->plan->plan_id ?></label>
                <select id="plan_id" name="plan_id" class="form-control form-control-lg">
                    <option value="free" <?= $data->user->plan->plan_id == 'free' ? 'selected="selected"' : null ?>><?= settings()->plan_free->name ?></option>
                    <option value="custom" <?= $data->user->plan->plan_id == 'custom' ? 'selected="selected"' : null ?>><?= settings()->plan_custom->name ?></option>

                    <?php foreach($data->plans as $plan): ?>
                        <option value="<?= $plan->plan_id ?>" <?= $data->user->plan->plan_id == $plan->plan_id ? 'selected="selected"' : null ?>><?= $plan->name ?></option>
                    <?php endforeach ?>
                </select>
            </div>

            <div class="form-group">
                <label for="plan_trial_done"><?= language()->admin_user_update->plan->plan_trial_done ?></label>
                <select id="plan_trial_done" name="plan_trial_done" class="form-control form-control-lg">
                    <option value="1" <?= $data->user->plan_trial_done ? 'selected="selected"' : null ?>><?= language()->global->yes ?></option>
                    <option value="0" <?= !$data->user->plan_trial_done ? 'selected="selected"' : null ?>><?= language()->global->no ?></option>
                </select>
            </div>

            <div id="plan_expiration_date_container" class="form-group">
                <label for="plan_expiration_date"><?= language()->admin_user_update->plan->plan_expiration_date ?></label>
                <input id="plan_expiration_date" type="text" name="plan_expiration_date" class="form-control form-control-lg" autocomplete="off" value="<?= $data->user->plan_expiration_date ?>">
                <div class="invalid-feedback">
                    <?= language()->admin_user_update->plan->plan_expiration_date_invalid ?>
                </div>
            </div>

            <div id="plan_settings" style="display: none">
                <div class="form-group">
                    <label for="campaigns_limit"><?= language()->admin_plans->plan->campaigns_limit ?></label>
                    <input type="number" id="campaigns_limit" name="campaigns_limit" min="-1" class="form-control form-control-lg" value="<?= $data->user->plan->settings->campaigns_limit ?>" />
                    <small class="form-text text-muted"><?= language()->admin_plans->plan->campaigns_limit_help ?></small>
                </div>

                <div class="form-group">
                    <label for="notifications_limit"><?= language()->admin_plans->plan->notifications_limit ?></label>
                    <input type="number" id="notifications_limit" name="notifications_limit" min="-1" class="form-control form-control-lg" value="<?= $data->user->plan->settings->notifications_limit ?>" />
                    <small class="form-text text-muted"><?= language()->admin_plans->plan->notifications_limit_help ?></small>
                </div>

                <div class="form-group">
                    <label for="notifications_impressions_limit"><?= language()->admin_plans->plan->notifications_impressions_limit ?></label>
                    <input type="number" id="notifications_impressions_limit" name="notifications_impressions_limit" min="-1" class="form-control form-control-lg" value="<?= $data->user->plan->settings->notifications_impressions_limit ?>" />
                    <small class="form-text text-muted"><?= language()->admin_plans->plan->notifications_impressions_limit_help ?></small>
                </div>

                <div class="form-group">
                    <label for="track_notifications_retention"><?= language()->admin_plans->plan->track_notifications_retention ?></label>
                    <input type="number" id="track_notifications_retention" name="track_notifications_retention" min="-1" class="form-control form-control-lg" value="<?= $data->user->plan->settings->track_notifications_retention ?>" />
                    <small class="form-text text-muted"><?= language()->admin_plans->plan->track_notifications_retention_help ?></small>
                </div>

                <div class="custom-control custom-switch mb-3">
                    <input id="no_ads" name="no_ads" type="checkbox" class="custom-control-input" <?= $data->user->plan->settings->no_ads ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="no_ads"><?= language()->admin_plans->plan->no_ads ?></label>
                    <div><small class="form-text text-muted"><?= language()->admin_plans->plan->no_ads_help ?></small></div>
                </div>

                <div class="custom-control custom-switch mb-3">
                    <input id="removable_branding" name="removable_branding" type="checkbox" class="custom-control-input" <?= $data->user->plan->settings->removable_branding ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="removable_branding"><?= language()->admin_plans->plan->removable_branding ?></label>
                    <div><small class="form-text text-muted"><?= language()->admin_plans->plan->removable_branding_help ?></small></div>
                </div>

                <div class="custom-control custom-switch mb-3">
                    <input id="custom_branding" name="custom_branding" type="checkbox" class="custom-control-input" <?= $data->user->plan->settings->custom_branding ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="custom_branding"><?= language()->admin_plans->plan->custom_branding ?></label>
                    <div><small class="form-text text-muted"><?= language()->admin_plans->plan->custom_branding_help ?></small></div>
                </div>

                <div class="custom-control custom-switch mb-3">
                    <input id="api_is_enabled" name="api_is_enabled" type="checkbox" class="custom-control-input" <?= $data->user->plan->settings->api_is_enabled ? 'checked="checked"' : null ?>>
                    <label class="custom-control-label" for="api_is_enabled"><?= language()->admin_plans->plan->api_is_enabled ?></label>
                    <div><small class="form-text text-muted"><?= language()->admin_plans->plan->api_is_enabled_help ?></small></div>
                </div>

                <?php if(\Altum\Plugin::is_active('affiliate') && settings()->affiliate->is_enabled): ?>
                    <div class="custom-control custom-switch my-3">
                        <input id="affiliate_is_enabled" name="affiliate_is_enabled" type="checkbox" class="custom-control-input" <?= $data->user->plan->settings->affiliate_is_enabled ? 'checked="checked"' : null ?>>
                        <label class="custom-control-label" for="affiliate_is_enabled"><?= language()->admin_plans->plan->affiliate_is_enabled ?></label>
                        <div><small class="form-text text-muted"><?= language()->admin_plans->plan->affiliate_is_enabled_help ?></small></div>
                    </div>
                <?php endif ?>

                <h3 class="h5 mt-3"><?= language()->admin_plans->plan->enabled_notifications ?></h3>
                <p class="text-muted"><?= language()->admin_plans->plan->enabled_notifications_help ?></p>

                <div class="row">
                    <?php foreach(\Altum\Notification::get_config() as $notification_type => $notification_config): ?>
                        <div class="col-6 mb-3">
                            <div class="custom-control custom-switch">
                                <input id="enabled_notifications_<?= $notification_type ?>" name="enabled_notifications[]" value="<?= $notification_type ?>" type="checkbox" class="custom-control-input" <?= $data->user->plan->settings->enabled_notifications->{$notification_type} ? 'checked="checked"' : null ?>>
                                <label class="custom-control-label" for="enabled_notifications_<?= $notification_type ?>"><?= language()->notification->{mb_strtolower($notification_type)}->name ?></label>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>

            <div class="mt-5"></div>

            <h2 class="h4"><?= language()->admin_user_update->change_password->header ?></h2>
            <p class="text-muted"><?= language()->admin_user_update->change_password->subheader ?></p>

            <div class="form-group">
                <label for="new_password"><?= language()->admin_user_update->change_password->new_password ?></label>
                <input id="new_password" type="password" name="new_password" class="form-control form-control-lg <?= \Altum\Alerts::has_field_errors('new_password') ? 'is-invalid' : null ?>" />
                <?= \Altum\Alerts::output_field_error('new_password') ?>
            </div>

            <div class="form-group">
                <label for="repeat_password"><?= language()->admin_user_update->change_password->repeat_password ?></label>
                <input id="repeat_password" type="password" name="repeat_password" class="form-control form-control-lg <?= \Altum\Alerts::has_field_errors('new_password') ? 'is-invalid' : null ?>" />
                <?= \Altum\Alerts::output_field_error('new_password') ?>
            </div>

            <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= language()->global->update ?></button>
        </form>
    </div>
</div>

<?php ob_start() ?>
<link href="<?= ASSETS_FULL_URL . 'css/daterangepicker.min.css' ?>" rel="stylesheet" media="screen,print">
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

<?php ob_start() ?>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/moment.min.js' ?>"></script>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/daterangepicker.min.js' ?>"></script>
<script src="<?= ASSETS_FULL_URL . 'js/libraries/moment-timezone-with-data-10-year-range.min.js' ?>"></script>

<script>
    'use strict';

    moment.tz.setDefault(<?= json_encode($this->user->timezone) ?>);

    let check_plan_id = () => {
        let selected_plan_id = document.querySelector('[name="plan_id"]').value;

        if(selected_plan_id == 'free') {
            document.querySelector('#plan_expiration_date_container').style.display = 'none';
        } else {
            document.querySelector('#plan_expiration_date_container').style.display = 'block';
        }

        if(selected_plan_id == 'custom') {
            document.querySelector('#plan_settings').style.display = 'block';
        } else {
            document.querySelector('#plan_settings').style.display = 'none';
        }
    };

    check_plan_id();

    /* Dont show expiration date when the chosen plan is the free one */
    document.querySelector('[name="plan_id"]').addEventListener('change', check_plan_id);

    /* Check for expiration date to show a warning if expired */
    let check_plan_expiration_date = () => {
        let plan_expiration_date = document.querySelector('[name="plan_expiration_date"]');

        let plan_expiration_date_object = new Date(plan_expiration_date.value);
        let today_date_object = new Date();

        if(plan_expiration_date_object < today_date_object) {
            plan_expiration_date.classList.add('is-invalid');
        } else {
            plan_expiration_date.classList.remove('is-invalid');
        }
    };

    check_plan_expiration_date();
    document.querySelector('[name="plan_expiration_date"]').addEventListener('change', check_plan_expiration_date);

    /* Daterangepicker */
    $('[name="plan_expiration_date"]').daterangepicker({
        startDate: <?= json_encode($data->user->plan_expiration_date) ?>,
        minDate: new Date(),
        alwaysShowCalendars: true,
        singleCalendar: true,
        singleDatePicker: true,
        locale: <?= json_encode(require APP_PATH . 'includes/daterangepicker_translations.php') ?>,
    }, (start, end, label) => {
        check_plan_expiration_date()
    });

</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/admin/users/user_delete_modal.php'), 'modals'); ?>
<?php \Altum\Event::add_content(include_view(THEME_PATH . 'views/admin/users/user_login_modal.php'), 'modals'); ?>
