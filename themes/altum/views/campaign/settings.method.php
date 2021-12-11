<?php defined('ALTUMCODE') || die() ?>

<div class="mt-5 d-flex justify-content-between">
        <h2 class="h3"><?= language()->campaign->notifications->header ?></h2>

        <div class="col-auto p-0 d-flex">
            <div>
                <?php if($this->user->plan_settings->notifications_limit != -1 && $data->notifications_total >= $this->user->plan_settings->notifications_limit): ?>
                    <button type="button" data-toggle="tooltip" title="<?= language()->notification->error_message->notifications_limit ?>" class="btn btn-primary rounded-pill disabled">
                        <i class="fa fa-plus-circle"></i> <?= language()->campaign->notifications->create ?>
                    </button>
                <?php else: ?>
                    <a href="<?= url('notification-create/' . $data->campaign->campaign_id) ?>" class="btn btn-primary rounded-pill"><i class="fa fa-plus-circle"></i> <?= language()->campaign->notifications->create ?></a>
                <?php endif ?>
            </div>

            <div class="ml-3">
                <div class="dropdown">
                    <button type="button" class="btn <?= count($data->filters->get) ? 'btn-outline-primary' : 'btn-outline-secondary' ?> rounded-pill filters-button dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport"><i class="fa fa-fw fa-sm fa-filter"></i></button>

                    <div class="dropdown-menu dropdown-menu-right filters-dropdown">
                        <div class="dropdown-header d-flex justify-content-between">
                            <span class="h6 m-0"><?= language()->global->filters->header ?></span>

                            <?php if(count($data->filters->get)): ?>
                                <a href="<?= url('campaign/' . $data->campaign->campaign_id) ?>" class="text-muted"><?= language()->global->filters->reset ?></a>
                            <?php endif ?>
                        </div>

                        <div class="dropdown-divider"></div>

                        <form action="" method="get" role="form">
                            <div class="form-group px-4">
                                <label for="filters_search" class="small"><?= language()->global->filters->search ?></label>
                                <input type="search" name="search" id="filters_search" class="form-control form-control-sm" value="<?= $data->filters->search ?>" />
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_search_by" class="small"><?= language()->global->filters->search_by ?></label>
                                <select name="search_by" id="filters_search_by" class="form-control form-control-sm">
                                    <option value="name" <?= $data->filters->search_by == 'name' ? 'selected="selected"' : null ?>><?= language()->campaign->filters->search_by_name ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_is_enabled" class="small"><?= language()->global->filters->status ?></label>
                                <select name="is_enabled" id="filters_is_enabled" class="form-control form-control-sm">
                                    <option value=""><?= language()->global->filters->all ?></option>
                                    <option value="1" <?= isset($data->filters->filters['is_enabled']) && $data->filters->filters['is_enabled'] == '1' ? 'selected="selected"' : null ?>><?= language()->global->active ?></option>
                                    <option value="0" <?= isset($data->filters->filters['is_enabled']) && $data->filters->filters['is_enabled'] == '0' ? 'selected="selected"' : null ?>><?= language()->global->disabled ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_type" class="small"><?= language()->campaign->filters->type ?></label>
                                <select name="type" id="filters_type" class="form-control form-control-sm">
                                    <option value=""><?= language()->global->filters->all ?></option>
                                    <?php foreach(\Altum\Notification::get_config() as $notification_type => $notification_config): ?>

                                    <?php

                                    /* Check for permission of usage of the notification */
                                    if(!$this->user->plan_settings->enabled_notifications->{$notification_type}) {
                                        continue;
                                    }

                                    ?>

                                    <option value="<?= $notification_type ?>" <?= isset($data->filters->filters['type']) && $data->filters->filters['type'] == '1' ? 'selected="selected"' : null ?>>
                                        <?= language()->notification->{mb_strtolower($notification_type)}->name ?>
                                    </option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_order_by" class="small"><?= language()->global->filters->order_by ?></label>
                                <select name="order_by" id="filters_order_by" class="form-control form-control-sm">
                                    <option value="datetime" <?= $data->filters->order_by == 'datetime' ? 'selected="selected"' : null ?>><?= language()->global->filters->order_by_datetime ?></option>
                                    <option value="name" <?= $data->filters->order_by == 'name' ? 'selected="selected"' : null ?>><?= language()->campaign->filters->order_by_name ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_order_type" class="small"><?= language()->global->filters->order_type ?></label>
                                <select name="order_type" id="filters_order_type" class="form-control form-control-sm">
                                    <option value="ASC" <?= $data->filters->order_type == 'ASC' ? 'selected="selected"' : null ?>><?= language()->global->filters->order_type_asc ?></option>
                                    <option value="DESC" <?= $data->filters->order_type == 'DESC' ? 'selected="selected"' : null ?>><?= language()->global->filters->order_type_desc ?></option>
                                </select>
                            </div>

                            <div class="form-group px-4">
                                <label for="filters_results_per_page" class="small"><?= language()->global->filters->results_per_page ?></label>
                                <select name="results_per_page" id="filters_results_per_page" class="form-control form-control-sm">
                                    <?php foreach($data->filters->allowed_results_per_page as $key): ?>
                                        <option value="<?= $key ?>" <?= $data->filters->results_per_page == $key ? 'selected="selected"' : null ?>><?= $key ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>

                            <div class="form-group px-4 mt-4">
                                <button type="submit" name="submit" class="btn btn-sm btn-primary btn-block"><?= language()->global->submit ?></button>
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
                <th><?= language()->campaign->notifications->name ?></th>
                <th class="d-none d-md-table-cell"><?= language()->campaign->notifications->display_trigger ?></th>
                <th class="d-none d-md-table-cell"><?= language()->campaign->notifications->display_duration ?></th>
                <th><?= language()->campaign->notifications->is_enabled ?></th>
                <th></th>
            </tr>
            </thead>
            <tbody id="tbody_campaign">

            <?php foreach($data->notifications as $row): ?>
                <?php $row->settings = json_decode($row->settings) ?>

                <tr>
                    <td>
                        <div class="d-flex flex-column">
                            <a href="<?= url('notification/' . $row->notification_id) ?>"><?= $row->name ?></a>

                            <div class="text-muted">
                                <i class="<?= language()->notification->{mb_strtolower($row->type)}->icon ?> fa-sm mr-1"></i> <?= language()->notification->{mb_strtolower($row->type)}->name ?>
                            </div>
                        </div>
                    </td>
                    <td class="d-none d-md-table-cell">
                        <div class="text-muted d-flex flex-column">

                            <?php
                            switch($row->settings->display_trigger) {
                                case 'delay':

                                    echo '<span>' . $row->settings->display_trigger_value . ' <small>' . language()->global->date->seconds . '</small></span>';
                                    echo '<small>' . language()->notification->settings->{'display_trigger_' . $row->settings->display_trigger} . '</small>';

                                    break;

                                case 'scroll':

                                    echo $row->settings->display_trigger_value . '%';
                                    echo '<small>' . language()->notification->settings->{'display_trigger_' . $row->settings->display_trigger}  . '</small>';

                                    break;

                                case 'exit_intent':

                                    echo language()->notification->settings->{'display_trigger_' . $row->settings->display_trigger};

                                    break;
                            }
                            ?>

                        </div>
                    </td>
                    <td class="d-none d-md-table-cell">
                        <span><?= $row->settings->display_duration == -1 ? language()->campaign->notifications->display_duration_unlimited : $row->settings->display_duration . ' <small>' . language()->global->date->seconds . '</small>' ?></span>
                    </td>
                    <td>
                        <div class="d-flex">
                            <div class="custom-control custom-switch" data-toggle="tooltip" title="<?= language()->campaign->notifications->is_enabled_tooltip ?>">
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
                        <div class="dropdown">
                            <button type="button" class="btn btn-link text-secondary dropdown-toggle dropdown-toggle-simple" data-toggle="dropdown" data-boundary="viewport">
                                <i class="fa fa-fw fa-ellipsis-v"></i>
                            </button>

                            <div class="dropdown-menu dropdown-menu-right">
                                <a href="<?= url('notification/' . $row->notification_id) ?>" class="dropdown-item"><i class="fa fa-fw fa-sm fa-pencil-alt mr-1"></i> <?= language()->global->edit ?></a>
                                <a href="<?= url('notification/' . $row->notification_id . '/statistics') ?>" class="dropdown-item"><i class="fa fa-fw fa-sm fa-chart-bar mr-1"></i> <?= language()->notification->statistics->link ?></a>
                                <a href="#" data-toggle="modal" data-target="#notification_duplicate_modal" data-notification-id="<?= $row->notification_id ?>" class="dropdown-item"><i class="fa fa-fw fa-sm fa-copy mr-1"></i> <?= language()->notification->duplicate ?></a>
                                <a href="#" data-toggle="modal" data-target="#notification_delete_modal" data-notification-id="<?= $row->notification_id ?>" class="dropdown-item"><i class="fa fa-fw fa-sm fa-times mr-1"></i> <?= language()->global->delete ?></a>
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
        <img src="<?= ASSETS_FULL_URL . 'images/no_rows.svg' ?>" class="col-10 col-md-6 col-lg-4 mb-3" alt="<?= language()->global->no_data ?>" />
        <h2 class="h4 text-muted"><?= language()->global->no_data ?></h2>
        <p><?= language()->campaign->notifications->no_data ?></a></p>
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
