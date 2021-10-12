<?php defined('ALTUMCODE') || die() ?>

<div class="d-flex justify-content-between mb-4">
    <h1 class="h3 mb-0 mr-1"><i class="fa fa-fw fa-xs fa-box-open text-primary-900 mr-2"></i> <?= language()->admin_plan_create->header ?></h1>
</div>

<?= \Altum\Alerts::output_alerts() ?>

<div class="card">
    <div class="card-body">

        <form action="" method="post" role="form">
            <input type="hidden" name="token" value="<?= \Altum\Middlewares\Csrf::get() ?>" />

            <div class="form-group">
                <label for="name"><?= language()->admin_plans->main->name ?></label>
                <input type="text" id="name" name="name" class="form-control form-control-lg <?= \Altum\Alerts::has_field_errors('name') ? 'is-invalid' : null ?>" required="required" />
                <?= \Altum\Alerts::output_field_error('name') ?>
            </div>

            <div class="form-group">
                <label for="description"><?= language()->admin_plans->main->description ?></label>
                <input type="text" id="description" name="description" class="form-control form-control-lg <?= \Altum\Alerts::has_field_errors('description') ? 'is-invalid' : null ?>" value="" />
                <?= \Altum\Alerts::output_field_error('description') ?>
            </div>

            <div class="form-group">
                <label for="order"><?= language()->admin_plans->main->order ?></label>
                <input type="number" min="0" id="order" name="order" class="form-control form-control-lg" value="" />
            </div>

            <div class="form-group">
                <label for="trial_days"><?= language()->admin_plans->main->trial_days ?></label>
                <input id="trial_days" type="number" min="0" name="trial_days" class="form-control form-control-lg" value="0" />
                <div><small class="form-text text-muted"><?= language()->admin_plans->main->trial_days_help ?></small></div>
            </div>

            <div class="row">
                <div class="col-sm-12 col-xl-4">
                    <div class="form-group">
                        <div class="form-group">
                            <label for="monthly_price"><?= language()->admin_plans->main->monthly_price ?> <small class="form-text text-muted"><?= settings()->payment->currency ?></small></label>
                            <input type="text" id="monthly_price" name="monthly_price" class="form-control form-control-lg <?= \Altum\Alerts::has_field_errors('monthly_price') ? 'is-invalid' : null ?>" required="required" />
                            <?= \Altum\Alerts::output_field_error('monthly_price') ?>
                            <small class="form-text text-muted"><?= sprintf(language()->admin_plans->main->price_help, language()->admin_plans->main->monthly_price) ?></small>
                        </div>
                    </div>
                </div>

                <div class="col-sm-12 col-xl-4">
                    <div class="form-group">
                        <label for="annual_price"><?= language()->admin_plans->main->annual_price ?> <small class="form-text text-muted"><?= settings()->payment->currency ?></small></label>
                        <input type="text" id="annual_price" name="annual_price" class="form-control form-control-lg <?= \Altum\Alerts::has_field_errors('annual_price') ? 'is-invalid' : null ?>" required="required" />
                        <?= \Altum\Alerts::output_field_error('annual_price') ?>
                        <small class="form-text text-muted"><?= sprintf(language()->admin_plans->main->price_help, language()->admin_plans->main->annual_price) ?></small>
                    </div>
                </div>

                <div class="col-sm-12 col-xl-4">
                    <div class="form-group">
                        <label for="lifetime_price"><?= language()->admin_plans->main->lifetime_price ?> <small class="form-text text-muted"><?= settings()->payment->currency ?></small></label>
                        <input type="text" id="lifetime_price" name="lifetime_price" class="form-control form-control-lg <?= \Altum\Alerts::has_field_errors('lifetime_price') ? 'is-invalid' : null ?>" required="required" />
                        <?= \Altum\Alerts::output_field_error('lifetime_price') ?>
                        <small class="form-text text-muted"><?= sprintf(language()->admin_plans->main->price_help, language()->admin_plans->main->lifetime_price) ?></small>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <span><?= language()->admin_plans->main->taxes_ids ?></span>
                <div><small class="form-text text-muted"><?= sprintf(language()->admin_plans->main->taxes_ids_help, '<a href="' . url('admin/taxes') .'">', '</a>') ?></small></div>
            </div>

            <?php if($data->taxes): ?>
                <div class="row">
                    <?php foreach($data->taxes as $row): ?>
                        <div class="col-12 col-xl-6">
                            <div class="custom-control custom-switch my-3">
                                <input id="<?= 'tax_id_' . $row->tax_id ?>" name="taxes_ids[<?= $row->tax_id ?>]" type="checkbox" class="custom-control-input">
                                <label class="custom-control-label" for="<?= 'tax_id_' . $row->tax_id ?>"><?= $row->internal_name ?></label>
                                <div><span><small><?= $row->name ?></small> - <small class="text-muted"><?= $row->description ?></small></span></div>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            <?php endif ?>

            <div class="form-group">
                <label for="color"><?= language()->admin_plans->main->color ?></label>
                <input type="text" id="color" name="color" class="form-control form-control-lg <?= \Altum\Alerts::has_field_errors('color') ? 'is-invalid' : null ?>" value="" />
                <?= \Altum\Alerts::output_field_error('color') ?>
                <small class="form-text text-muted"><?= language()->admin_plans->main->color_help ?></small>
            </div>

            <div class="form-group">
                <label for="status"><?= language()->admin_plans->main->status ?></label>
                <select id="status" name="status" class="form-control form-control-lg">
                    <option value="1"><?= language()->global->active ?></option>
                    <option value="0"><?= language()->global->disabled ?></option>
                    <option value="2"><?= language()->global->hidden ?></option>
                </select>
            </div>

            <div class="mt-5"></div>

            <h2 class="h4"><?= language()->admin_plans->plan->header ?></h2>

            <div>
                <div class="form-group">
                    <label for="campaigns_limit"><?= language()->admin_plans->plan->campaigns_limit ?></label>
                    <input type="number" id="campaigns_limit" name="campaigns_limit" min="-1" class="form-control form-control-lg" value="0" required="required" />
                    <small class="form-text text-muted"><?= language()->admin_plans->plan->campaigns_limit_help ?></small>
                </div>

                <div class="form-group">
                    <label for="notifications_limit"><?= language()->admin_plans->plan->notifications_limit ?></label>
                    <input type="number" id="notifications_limit" name="notifications_limit" min="-1" class="form-control form-control-lg" value="0" required="required" />
                    <small class="form-text text-muted"><?= language()->admin_plans->plan->notifications_limit_help ?></small>
                </div>

                <div class="form-group">
                    <label for="notifications_impressions_limit"><?= language()->admin_plans->plan->notifications_impressions_limit ?> <small class="form-text text-muted"><?= language()->admin_plans->plan->per_month ?></small></label>
                    <input type="number" id="notifications_impressions_limit" name="notifications_impressions_limit" min="-1" class="form-control form-control-lg" value="0" required="required" />
                    <small class="form-text text-muted"><?= language()->admin_plans->plan->notifications_impressions_limit_help ?></small>
                </div>

                <div class="form-group">
                    <label for="track_notifications_retention"><?= language()->admin_plans->plan->track_notifications_retention ?> <small class="form-text text-muted"><?= language()->admin_plans->plan->per_month ?></small></label>
                    <input type="number" id="track_notifications_retention" name="track_notifications_retention" min="-1" class="form-control form-control-lg" value="0" required="required" />
                    <small class="form-text text-muted"><?= language()->admin_plans->plan->track_notifications_retention_help ?></small>
                </div>


                <div class="custom-control custom-switch mb-3">
                    <input id="no_ads" name="no_ads" type="checkbox" class="custom-control-input">
                    <label class="custom-control-label" for="no_ads"><?= language()->admin_plans->plan->no_ads ?></label>
                    <div><small class="form-text text-muted"><?= language()->admin_plans->plan->no_ads_help ?></small></div>
                </div>

                <div class="custom-control custom-switch mb-3">
                    <input id="removable_branding" name="removable_branding" type="checkbox" class="custom-control-input">
                    <label class="custom-control-label" for="removable_branding"><?= language()->admin_plans->plan->removable_branding ?></label>
                    <div><small class="form-text text-muted"><?= language()->admin_plans->plan->removable_branding_help ?></small></div>
                </div>

                <div class="custom-control custom-switch mb-3">
                    <input id="custom_branding" name="custom_branding" type="checkbox" class="custom-control-input">
                    <label class="custom-control-label" for="custom_branding"><?= language()->admin_plans->plan->custom_branding ?></label>
                    <div><small class="form-text text-muted"><?= language()->admin_plans->plan->custom_branding_help ?></small></div>
                </div>

                <div class="custom-control custom-switch mb-3">
                    <input id="api_is_enabled" name="api_is_enabled" type="checkbox" class="custom-control-input">
                    <label class="custom-control-label" for="api_is_enabled"><?= language()->admin_plans->plan->api_is_enabled ?></label>
                    <div><small class="form-text text-muted"><?= language()->admin_plans->plan->api_is_enabled_help ?></small></div>
                </div>

                <?php if(\Altum\Plugin::is_active('affiliate') && settings()->affiliate->is_enabled): ?>
                    <div class="custom-control custom-switch my-3">
                        <input id="affiliate_is_enabled" name="affiliate_is_enabled" type="checkbox" class="custom-control-input">
                        <label class="custom-control-label" for="affiliate_is_enabled"><?= language()->admin_plans->plan->affiliate_is_enabled ?></label>
                        <div><small class="form-text text-muted"><?= language()->admin_plans->plan->affiliate_is_enabled_help ?></small></div>
                    </div>
                <?php endif ?>

                <h3 class="h5 mt-4"><?= language()->admin_plans->plan->enabled_notifications ?></h3>
                <p class="text-muted"><?= language()->admin_plans->plan->enabled_notifications_help ?></p>

                <div class="row">
                    <?php foreach(\Altum\Notification::get_config() as $notification_type => $notification_config): ?>
                        <div class="col-6 mb-3">
                            <div class="custom-control custom-switch">
                                <input id="enabled_notifications_<?= $notification_type ?>" name="enabled_notifications[]" value="<?= $notification_type ?>" type="checkbox" class="custom-control-input">
                                <label class="custom-control-label" for="enabled_notifications_<?= $notification_type ?>"><?= language()->notification->{mb_strtolower($notification_type)}->name ?></label>
                            </div>
                        </div>
                    <?php endforeach ?>
                </div>
            </div>

            <button type="submit" name="submit" class="btn btn-lg btn-block btn-primary mt-4"><?= language()->global->create ?></button>

        </form>

    </div>
</div>
