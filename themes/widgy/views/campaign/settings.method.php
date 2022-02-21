<?php defined('ALTUMCODE') || die() ?>

<div class="row mt-5">
    <div class="col-12 col-lg mb-3 mb-lg-0">
        <h2 class="h3 m-0"><?= l('campaign.notifications.header') ?></h2>
    </div>

    <div class="col-12 col-lg-auto d-flex">
        <div>
            <?php if($this->user->plan_settings->notifications_limit != -1 && $data->notifications_total >= $this->user->plan_settings->notifications_limit): ?>
                <button type="button" data-toggle="tooltip" title="<?= l('notification.error_message.notifications_limit') ?>" class="btn btn-primary disabled">
                    <i class="fa fa-fw fa-sm fa-plus"></i> <?= l('campaign.notifications.create') ?>
                </button>
            <?php else: ?>
                <a href="<?= url('notification-create/' . $data->campaign->campaign_id) ?>" class="btn btn-primary"><i class="fa fa-fw fa-sm fa-plus"></i> <?= l('campaign.notifications.create') ?></a>
            <?php endif ?>
        </div>

        <div class="ml-3">
            <div class="dropdown">
                <button type="button" class="btn <?= count($data->filters->get) ? 'btn-outline-primary' : 'btn-outline-secondary' ?> filters-button dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport"><i class="fa fa-fw fa-sm fa-filter"></i></button>

                <div class="dropdown-menu dropdown-menu-right filters-dropdown">
                    <div class="dropdown-header d-flex justify-content-between">
                        <span class="h6 m-0"><?= l('global.filters.header') ?></span>

                        <?php if(count($data->filters->get)): ?>
                            <a href="<?= url('campaign/' . $data->campaign->campaign_id) ?>" class="text-muted"><?= l('global.filters.reset') ?></a>
                        <?php endif ?>
                    </div>

                    <div class="dropdown-divider"></div>

                    <form action="" method="get" role="form">
                        <div class="form-group px-4">
                            <label for="filters_search" class="small"><?= l('global.filters.search') ?></label>
                            <input type="search" name="search" id="filters_search" class="form-control form-control-sm" value="<?= $data->filters->search ?>" />
                        </div>

                        <div class="form-group px-4">
                            <label for="filters_search_by" class="small"><?= l('global.filters.search_by') ?></label>
                            <select name="search_by" id="filters_search_by" class="form-control form-control-sm">
                                <option value="name" <?= $data->filters->search_by == 'name' ? 'selected="selected"' : null ?>><?= l('campaign.filters.search_by_name') ?></option>
                            </select>
                        </div>

                        <div class="form-group px-4">
                            <label for="filters_is_enabled" class="small"><?= l('global.filters.status') ?></label>
                            <select name="is_enabled" id="filters_is_enabled" class="form-control form-control-sm">
                                <option value=""><?= l('global.filters.all') ?></option>
                                <option value="1" <?= isset($data->filters->filters['is_enabled']) && $data->filters->filters['is_enabled'] == '1' ? 'selected="selected"' : null ?>><?= l('global.active') ?></option>
                                <option value="0" <?= isset($data->filters->filters['is_enabled']) && $data->filters->filters['is_enabled'] == '0' ? 'selected="selected"' : null ?>><?= l('global.disabled') ?></option>
                            </select>
                        </div>

                        <div class="form-group px-4">
                            <label for="filters_type" class="small"><?= l('campaign.filters.type') ?></label>
                            <select name="type" id="filters_type" class="form-control form-control-sm">
                                <option value=""><?= l('global.filters.all') ?></option>
                                <?php foreach(\Altum\Notification::get_config() as $notification_type => $notification_config): ?>

                                    <?php

                                    /* Check for permission of usage of the notification */
                                    if(!$this->user->plan_settings->enabled_notifications->{$notification_type}) {
                                        continue;
                                    }

                                    ?>

                                    <option value="<?= $notification_type ?>" <?= isset($data->filters->filters['type']) && $data->filters->filters['type'] == '1' ? 'selected="selected"' : null ?>>
                                        <?= l('notification.' . mb_strtolower($notification_type) . '.name') ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>

                        <div class="form-group px-4">
                            <label for="filters_order_by" class="small"><?= l('global.filters.order_by') ?></label>
                            <select name="order_by" id="filters_order_by" class="form-control form-control-sm">
                                <option value="datetime" <?= $data->filters->order_by == 'datetime' ? 'selected="selected"' : null ?>><?= l('global.filters.order_by_datetime') ?></option>
                                <option value="name" <?= $data->filters->order_by == 'name' ? 'selected="selected"' : null ?>><?= l('campaign.filters.order_by_name') ?></option>
                            </select>
                        </div>

                        <div class="form-group px-4">
                            <label for="filters_order_type" class="small"><?= l('global.filters.order_type') ?></label>
                            <select name="order_type" id="filters_order_type" class="form-control form-control-sm">
                                <option value="ASC" <?= $data->filters->order_type == 'ASC' ? 'selected="selected"' : null ?>><?= l('global.filters.order_type_asc') ?></option>
                                <option value="DESC" <?= $data->filters->order_type == 'DESC' ? 'selected="selected"' : null ?>><?= l('global.filters.order_type_desc') ?></option>
                            </select>
                        </div>

                        <div class="form-group px-4">
                            <label for="filters_results_per_page" class="small"><?= l('global.filters.results_per_page') ?></label>
                            <select name="results_per_page" id="filters_results_per_page" class="form-control form-control-sm">
                                <?php foreach($data->filters->allowed_results_per_page as $key): ?>
                                    <option value="<?= $key ?>" <?= $data->filters->results_per_page == $key ? 'selected="selected"' : null ?>><?= $key ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>

                        <div class="form-group px-4 mt-4">
                            <button type="submit" name="submit" class="btn btn-sm btn-primary btn-block"><?= l('global.submit') ?></button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<?php if(count($data->notifications)): ?>
    <div class="table-responsive table-custom-container mt-3">
        <table class="table table-custom">
            <thead>
            <tr>
                <th><?= l('campaign.notifications.name') ?></th>
                <th class="d-none d-md-table-cell"><?= l('campaign.notifications.display_trigger') ?></th>
                <th class="d-none d-md-table-cell"><?= l('campaign.notifications.display_duration') ?></th>
                <th><?= l('campaign.notifications.is_enabled') ?></th>
                <th></th>
            </tr>
            </thead>
            <tbody>

            <?php foreach($data->notifications as $row): ?>
                <?php $row->settings = json_decode($row->settings) ?>

                <tr>
                    <td class="text-nowrap">
                        <div class="d-flex flex-column">
                            <a href="<?= url('notification/' . $row->notification_id) ?>"><?= $row->name ?></a>

                            <div class="text-muted">
                                <i class="<?= l('notification.' . mb_strtolower($row->type) . '.icon') ?> fa-sm mr-1"></i> <?= l('notification.' . mb_strtolower($row->type) . '.name') ?>
                            </div>
                        </div>
                    </td>
                    <td class="text-nowrap d-none d-md-table-cell">
                        <div class="text-muted d-flex flex-column">

                            <?php
                            switch($row->settings->display_trigger) {
                                case 'delay':

                                    echo '<span>' . $row->settings->display_trigger_value . ' <small>' . l('global.date.seconds') . '</small></span>';
                                    echo '<small>' . l('notification.settings.display_trigger_' . $row->settings->display_trigger) . '</small>';

                                    break;

                                case 'scroll':

                                    echo $row->settings->display_trigger_value . '%';
                                    echo '<small>' . l('notification.settings.display_trigger_' . $row->settings->display_trigger)  . '</small>';

                                    break;

                                case 'exit_intent':

                                    echo l('notification.settings.display_trigger_' . $row->settings->display_trigger);

                                    break;
                            }
                            ?>

                        </div>
                    </td>
                    <td class="text-nowrap d-none d-md-table-cell">
                        <span><?= $row->settings->display_duration == -1 ? l('campaign.notifications.display_duration_unlimited') : $row->settings->display_duration . ' <small>' . l('global.date.seconds') . '</small>' ?></span>
                    </td>
                    <td class="text-nowrap">
                        <div class="d-flex">
                            <div class="custom-control custom-switch" data-toggle="tooltip" title="<?= l('campaign.notifications.is_enabled_tooltip') ?>">
                                <input
                                        type="checkbox"
                                        class="custom-control-input"
                                        id="notification_is_enabled_<?= $row->notification_id ?>"
                                        data-row-id="<?= $row->notification_id ?>"
                                        onchange="ajax_call_helper(event, 'notifications-ajax', 'is_enabled_toggle')"
                                    <?= $row->is_enabled ? 'checked="checked"' : null ?>
                                >
                                <label class="custom-control-label clickable" for="notification_is_enabled_<?= $row->notification_id ?>"></label>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex justify-content-end">
                            <div class="dropdown">
                                <button type="button" class="btn btn-link text-secondary dropdown-toggle dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport">
                                    <i class="fa fa-fw fa-ellipsis-v"></i>
                                </button>

                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="<?= url('notification/' . $row->notification_id) ?>" class="dropdown-item"><i class="fa fa-fw fa-sm fa-pencil-alt mr-1"></i> <?= l('global.edit') ?></a>
                                    <a href="<?= url('notification/' . $row->notification_id . '/statistics') ?>" class="dropdown-item"><i class="fa fa-fw fa-sm fa-chart-bar mr-1"></i> <?= l('notification.statistics.link') ?></a>
                                    <a href="#" data-toggle="modal" data-target="#notification_duplicate_modal" data-notification-id="<?= $row->notification_id ?>" class="dropdown-item"><i class="fa fa-fw fa-sm fa-copy mr-1"></i> <?= l('notification.duplicate') ?></a>
                                    <a href="#" data-toggle="modal" data-target="#notification_delete_modal" data-notification-id="<?= $row->notification_id ?>" class="dropdown-item"><i class="fa fa-fw fa-sm fa-times mr-1"></i> <?= l('global.delete') ?></a>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
            <?php endforeach ?>

            </tbody>
        </table>
    </div>

    <div class="mt-3"><?= $data->pagination ?></div>

<?php else: ?>

    <div class="d-flex flex-column align-items-center justify-content-center">
        <img src="<?= ASSETS_FULL_URL . 'images/no_rows.svg' ?>" class="col-10 col-md-6 col-lg-4 mb-3" alt="<?= l('global.no_data') ?>" />
        <h2 class="h4 text-muted"><?= l('global.no_data') ?></h2>
        <p><?= l('campaign.notifications.no_data') ?></a></p>
    </div>

<?php endif ?>

<?php ob_start() ?>
<script>
    $(document).ready(() => {
        $('[data-delete]').on('click', event => {
            let message = $(event.currentTarget).attr('data-delete');

            if(!confirm(message)) return false;

            /* Continue with the deletion */
            ajax_call_helper(event, 'notifications-ajax', 'delete', () => {

                /* On success delete the actual row from the DOM */
                $(event.currentTarget).closest('tr').remove();

            });

        });
    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
