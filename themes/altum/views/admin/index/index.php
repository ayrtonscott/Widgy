<?php defined('ALTUMCODE') || die() ?>

<div class="mb-5 row justify-content-between">
    <div class="col-12 col-sm-6 col-xl-3 mb-4 position-relative">
        <div class="card d-flex flex-row h-100 overflow-hidden">
            <div class="card-body">
                <small class="text-muted"><i class="fa fa-fw fa-sm fa-server mr-1"></i> <?= language()->admin_index->display->campaigns ?></small>

                <div class="mt-3"><span class="h4"><?= nr($data->campaigns) ?></span></div>
            </div>

            <div class="bg-gray-200 px-2 d-flex flex-column justify-content-center">
                <a href="<?= url('admin/campaigns') ?>" class="stretched-link">
                    <i class="fa fa-fw fa-angle-right text-gray-500"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3 mb-4 position-relative">
        <div class="card d-flex flex-row h-100 overflow-hidden">
            <div class="card-body">
                <small class="text-muted"><i class="fa fa-fw fa-sm fa-bell mr-1"></i> <?= language()->admin_index->display->notifications ?></small>

                <div class="mt-3"><span class="h4"><?= nr($data->notifications) ?></span></div>
            </div>

            <div class="bg-gray-200 px-2 d-flex flex-column justify-content-center">
                <a href="<?= url('admin/notifications') ?>" class="stretched-link">
                    <i class="fa fa-fw fa-angle-right text-gray-500"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3 mb-4 position-relative">
        <div class="card d-flex flex-row h-100 overflow-hidden">
            <div class="card-body">
                <small class="text-muted"><i class="fa fa-fw fa-sm fa-wifi mr-1"></i> <?= language()->admin_index->display->track_notifications ?></small>

                <div class="mt-3"><span class="h4"><?= nr($data->track_notifications) ?></span></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3 mb-4 position-relative">
        <div class="card d-flex flex-row h-100 overflow-hidden">
            <div class="card-body">
                <small class="text-muted"><i class="fa fa-fw fa-sm fa-project-diagram mr-1"></i> <?= language()->admin_index->display->track_logs ?></small>

                <div class="mt-3"><span class="h4"><?= nr($data->track_logs) ?></span></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3 mb-4 position-relative">
        <div class="card d-flex flex-row h-100 overflow-hidden">
            <div class="card-body">
                <small class="text-muted"><i class="fa fa-fw fa-sm fa-globe mr-1"></i> <?= language()->admin_index->display->track_conversions ?></small>

                <div class="mt-3"><span class="h4"><?= nr($data->track_conversions) ?></span></div>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3 mb-4 position-relative">
        <div class="card d-flex flex-row h-100 overflow-hidden">
            <div class="card-body">
                <small class="text-muted"><i class="fa fa-fw fa-sm fa-users mr-1"></i> <?= language()->admin_index->display->users ?></small>

                <div class="mt-3"><span class="h4"><?= nr($data->users) ?></span></div>
            </div>

            <div class="bg-gray-200 px-2 d-flex flex-column justify-content-center">
                <a href="<?= url('admin/users') ?>" class="stretched-link">
                    <i class="fa fa-fw fa-angle-right text-gray-500"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3 mb-4 position-relative">
        <div class="card d-flex flex-row h-100 overflow-hidden">
            <div class="card-body">
                <small class="text-muted"><i class="fa fa-fw fa-sm fa-funnel-dollar mr-1"></i> <?= language()->admin_index->display->payments ?></small>

                <div class="mt-3"><span class="h4"><?= nr($data->payments) ?></span></div>
            </div>

            <div class="bg-gray-200 px-2 d-flex flex-column justify-content-center">
                <a href="<?= url('admin/payments') ?>" class="stretched-link">
                    <i class="fa fa-fw fa-angle-right text-gray-500"></i>
                </a>
            </div>
        </div>
    </div>

    <div class="col-12 col-sm-6 col-xl-3 mb-4 position-relative">
        <div class="card d-flex flex-row h-100 overflow-hidden">
            <div class="card-body">
                <small class="text-muted"><i class="fa fa-fw fa-sm fa-dollar-sign mr-1"></i> <?= language()->admin_index->display->payments_total_amount ?></small>

                <div class="mt-3"><span class="h4"><?= nr($data->payments_total_amount, 2) ?></span> <small><?= settings()->payment->currency ?></small></div>
            </div>

            <div class="bg-gray-200 px-2 d-flex flex-column justify-content-center">
                <a href="<?= url('admin/payments') ?>" class="stretched-link">
                    <i class="fa fa-fw fa-angle-right text-gray-500"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<div class="mb-5">
    <h1 class="h3 mb-4"><?= language()->admin_index->users ?></h1>

    <?php $result = database()->query("SELECT * FROM `users` ORDER BY `user_id` DESC LIMIT 5"); ?>
    <div class="table-responsive table-custom-container">
        <table class="table table-custom">
            <thead>
            <tr>
                <th><?= language()->admin_users->table->user ?></th>
                <th><?= language()->admin_users->main->status ?></th>
                <th><?= language()->admin_users->table->plan_id ?></th>
                <th><?= language()->admin_users->table->details ?></th>
            </tr>
            </thead>
            <tbody>
            <?php while($row = $result->fetch_object()): ?>
                <?php //ALTUMCODE:DEMO if(DEMO) {$row->email = 'hidden@demo.com'; $row->name = 'hidden on demo';} ?>
                <?php if(!isset($data->plans[$row->plan_id])) $data->plans[$row->plan_id] = (new \Altum\Models\Plan())->get_plan_by_id($row->plan_id) ?>
                <tr>
                    <td>
                        <div class="d-flex">
                            <img src="<?= get_gravatar($row->email) ?>" class="user-avatar rounded-circle mr-3" alt="" />

                            <div class="d-flex flex-column">
                                <div>
                                    <a href="<?= url('admin/user-view/' . $row->user_id) ?>"><?= $row->name ?></a>
                                </div>

                                <span class="text-muted"><?= $row->email ?></span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <?php if($row->status == 0): ?>
                        <span class="badge badge-pill badge-warning"><i class="fa fa-fw fa-eye-slash"></i> <?= language()->admin_users->main->status_unconfirmed ?>
                            <?php elseif($row->status == 1): ?>
                        <span class="badge badge-pill badge-success"><i class="fa fa-fw fa-check"></i> <?= language()->admin_users->main->status_active ?>
                            <?php elseif($row->status == 2): ?>
                        <span class="badge badge-pill badge-light"><i class="fa fa-fw fa-times"></i> <?= language()->admin_users->main->status_disabled ?>
                        <?php endif ?>
                    </td>
                    <td>
                        <div class="d-flex flex-column">
                            <span><?= $data->plans[$row->plan_id]->name ?></span>

                            <?php if($row->plan_id != 'free'): ?>
                                <div>
                                    <small class="text-muted" data-toggle="tooltip" title="<?= language()->admin_users->table->plan_expiration_date ?>"><?= \Altum\Date::get($row->plan_expiration_date) ?></small>
                                </div>
                            <?php endif ?>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center">
                            <span class="mr-2" data-toggle="tooltip" title="<?= sprintf(language()->admin_users->table->datetime, \Altum\Date::get($row->datetime)) ?>">
                                <i class="fa fa-fw fa-clock text-muted"></i>
                            </span>

                            <span class="mr-2" data-toggle="tooltip" title="<?= sprintf(language()->admin_users->table->last_activity, ($row->last_activity ? \Altum\Date::get($row->last_activity) : '-')) ?>">
                                <i class="fa fa-fw fa-history text-muted"></i>
                            </span>

                            <span class="mr-2" data-toggle="tooltip" title="<?= sprintf(language()->admin_users->table->total_logins, nr($row->total_logins)) ?>">
                                <i class="fa fa-fw fa-user-clock text-muted"></i>
                            </span>

                            <?php if($row->country): ?>
                                <img src="<?= ASSETS_FULL_URL . 'images/countries/' . mb_strtolower($row->country) . '.svg' ?>" class="img-fluid icon-favicon mr-2" data-toggle="tooltip" title="<?= get_country_from_country_code($row->country) ?>" />
                            <?php else: ?>
                                <span class="mr-2" data-toggle="tooltip" title="<?= language()->admin_users->table->country_unknown ?>">
                                    <i class="fa fa-fw fa-globe text-muted"></i>
                                </span>
                            <?php endif ?>
                        </div>
                    </td>
                </tr>
            <?php endwhile ?>
            </tbody>
        </table>
    </div>
</div>

<?php if(in_array(settings()->license->type, ['Extended License', 'extended'])): ?>
    <?php $result = database()->query("SELECT `payments`.*, `users`.`name` AS `user_name`, `users`.`email` AS `user_email` FROM `payments` LEFT JOIN `users` ON `payments`.`user_id` = `users`.`user_id` ORDER BY `id` DESC LIMIT 5"); ?>

    <?php if($result->num_rows): ?>
        <div class="mb-5">
            <h1 class="h3 mb-4"><?= language()->admin_index->payments ?></h1>

            <div class="table-responsive table-custom-container">
                <table class="table table-custom">
                    <thead>
                    <tr>
                        <th><?= language()->admin_payments->table->user ?></th>
                        <th><?= language()->admin_payments->table->type ?></th>
                        <th><?= language()->admin_payments->table->plan ?></th>
                        <th><?= language()->admin_payments->table->total_amount ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php while($row = $result->fetch_object()): ?>
                        <?php //ALTUMCODE:DEMO if(DEMO) {$row->email = $row->user_email = 'hidden@demo.com'; $row->user_name = $row->name = 'hidden on demo';} ?>

                        <tr>
                            <td>
                                <div class="d-flex flex-column">
                                    <div>
                                        <a href="<?= url('admin/user-view/' . $row->user_id) ?>"><?= $row->user_name ?></a>
                                    </div>

                                    <span class="text-muted"><?= $row->user_email ?></span>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span><?= language()->pay->custom_plan->{$row->type . '_type'} ?></span>
                                    <div>
                                        <span class="text-muted"><?= language()->pay->custom_plan->{$row->frequency} ?></span> - <span class="text-muted"><?= language()->pay->custom_plan->{$row->processor} ?></span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <a href="<?= url('admin/plans/plan-update/' . $row->plan_id) ?>"><?= $data->plans[$row->plan_id]->name ?></a>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span class=""><?= nr($row->total_amount, 2) . ' ' . $row->currency ?></span>
                                    <div>
                                        <span class="text-muted" data-toggle="tooltip" title="<?= \Altum\Date::get($row->datetime) ?>">
                                            <?= \Altum\Date::get($row->datetime, 2) ?>
                                        </span>
                                    </div>
                                </div>
                            </td>
                        </tr>

                    <?php endwhile ?>
                    </tbody>
                </table>
            </div>
        </div>
    <?php endif ?>
<?php endif ?>

<div class="card">
    <div class="card-body">

        <div class="row my-3">
            <div class="col-12 col-md-6">
                <span class="font-weight-bold">
                    <i class="fa fa-fw fa-code fa-sm mr-1"></i> Version
                </span>
            </div>
            <div class="col-12 col-md-6">
                <?= PRODUCT_VERSION ?>
            </div>
        </div>

        <div class="row my-3">
            <div class="col-12 col-md-6">
                <span class="font-weight-bold">
                    <i class="fa fa-fw fa-book fa-sm mr-1"></i> Documentation
                </span>
            </div>
            <div class="col-12 col-md-6">
                <a href="<?= PRODUCT_DOCUMENTATION_URL ?>" target="_blank"><?= PRODUCT_NAME ?> Documentation</a>
            </div>
        </div>

        <div class="row my-3">
            <div class="col-12 col-md-6">
                <span class="font-weight-bold">
                    <i class="fa fa-fw fa-question-circle fa-sm mr-1"></i> Support
                </span>
            </div>
            <div class="col-12 col-md-6">
                <a href="https://altumcode.com/contact" target="_blank">support@altumcode.com</a><br />
                <span class="text-muted">Provide proof of purchase when requesting support, otherwise your email can be discarded.</span>
            </div>
        </div>

        <div class="row my-3">
            <div class="col-12 col-md-6">
                <span class="font-weight-bold">
                    <i class="fa fa-fw fa-cloud-upload-alt fa-sm mr-1"></i> Check for updates
                </span>
            </div>
            <div class="col-12 col-md-6">
                <a href="<?= PRODUCT_URL ?>" target="_blank">Codecanyon</a>
            </div>
        </div>

        <div class="row my-3">
            <div class="col-12 col-md-6">
                <span class="font-weight-bold">
                    <i class="fa fa-fw fa-project-diagram fa-sm mr-1"></i> More work of mine
                </span>
            </div>
            <div class="col-12 col-md-6">
                <a href="https://codecanyon.net/user/altumcode/portfolio" target="_blank">Envato // Codecanyon</a>
            </div>
        </div>

        <div class="row my-3">
            <div class="col-12 col-md-6">
                <span class="font-weight-bold">
                    <i class="fa fa-fw fa-globe fa-sm mr-1"></i> Official website
                </span>
            </div>
            <div class="col-12 col-md-6">
                <a href="https://altumcode.com/" target="_blank">AltumCode</a>
            </div>
        </div>

        <div class="row my-3">
            <div class="col-12 col-md-6">
                <span class="font-weight-bold">
                    <i class="fab fa-fw fa-twitter fa-sm mr-1"></i> Twitter Updates
                </span>
            </div>
            <div class="col-12 col-md-6">
                <a href="https://altumco.de/twitter" target="_blank">@altumcode</a>
            </div>
        </div>

    </div>
</div>
