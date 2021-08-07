<?php defined('ALTUMCODE') || die() ?>

<header class="header pb-0">
    <div class="container">

        <nav aria-label="breadcrumb">
            <small>
                <ol class="custom-breadcrumbs">
                    <li>
                        <a href="<?= url('dashboard') ?>"><?= language()->dashboard->breadcrumb ?></a><i class="fa fa-fw fa-angle-right"></i>
                    </li>
                    <li>
                        <a href="<?= url('campaign/' . $data->notification->campaign_id) ?>"><?= language()->campaign->breadcrumb ?></a><i class="fa fa-fw fa-angle-right"></i>
                    </li>
                    <li class="active" aria-current="page"><?= language()->notification->breadcrumb ?></li>
                </ol>
            </small>
        </nav>

        <div class="d-flex align-items-center">
            <h1 class="h2 mr-3"><?= $data->notification->name ?></h1>

            <div class="custom-control custom-switch mr-3" data-toggle="tooltip" title="<?= language()->campaign->notifications->is_enabled_tooltip ?>">
                <input
                        type="checkbox"
                        class="custom-control-input"
                        id="campaign_is_enabled_<?= $data->notification->notification_id ?>"
                        data-row-id="<?= $data->notification->notification_id ?>"
                        onchange="ajax_call_helper(event, 'notifications-ajax', 'is_enabled_toggle')"
                    <?= $data->notification->is_enabled ? 'checked="checked"' : null ?>
                >
                <label class="custom-control-label clickable" for="campaign_is_enabled_<?= $data->notification->notification_id ?>"></label>
            </div>

            <div class="dropdown">
                <button type="button" class="btn btn-link text-secondary dropdown-toggle dropdown-toggle-simple" data-toggle="dropdown">
                    <i class="fa fa-fw fa-ellipsis-v"></i>
                </button>

                <div class="dropdown-menu dropdown-menu-right">
                    <a href="<?= url('notification/' . $data->notification->notification_id) ?>" class="dropdown-item"><i class="fa fa-fw fa-sm fa-pencil-alt mr-1"></i> <?= language()->global->edit ?></a>
                    <a href="<?= url('notification/' . $data->notification->notification_id . '/statistics') ?>" class="dropdown-item"><i class="fa fa-fw fa-sm fa-chart-bar mr-1"></i> <?= language()->notification->statistics->link ?></a>
                    <a href="#" data-toggle="modal" data-target="#notification_delete_modal" data-notification-id="<?= $data->notification->notification_id ?>" class="dropdown-item"><i class="fa fa-fw fa-sm fa-times mr-1"></i> <?= language()->global->delete ?></a>
                </div>
            </div>
        </div>

        <div class="d-flex">
            <div class="d-flex align-items-center text-muted mr-3">
                <img src="https://external-content.duckduckgo.com/ip3/<?= $data->notification->domain ?>.ico" class="img-fluid icon-favicon mr-1" />
                <?= $data->notification->domain ?>
            </div>

            <span class="text-muted">
                <i class="<?= language()->notification->{mb_strtolower($data->notification->type)}->icon ?> fa-sm mr-1"></i> <?= language()->notification->{mb_strtolower($data->notification->type)}->name ?>
            </span>
        </div>

        <?= $this->views['method_menu'] ?>
    </div>
</header>

<?php require THEME_PATH . 'views/partials/ads_header.php' ?>

<section class="container">

    <?= \Altum\Alerts::output_alerts() ?>

    <?= $this->views['method'] ?>

</section>

<?php ob_start() ?>
<link href="<?= ASSETS_FULL_URL . 'css/pickr.min.css' ?>" rel="stylesheet" media="screen">
<link href="<?= ASSETS_FULL_URL . 'css/daterangepicker.min.css' ?>" rel="stylesheet" media="screen,print">
<link href="<?= ASSETS_FULL_URL . 'css/pixel.css' ?>" rel="stylesheet" media="screen,print">
<?php \Altum\Event::add_content(ob_get_clean(), 'head') ?>

<?php ob_start() ?>
<script>
    /* Delete handler for the notification */
    $('[data-delete]').on('click', event => {
        let message = $(event.currentTarget).attr('data-delete');

        if(!confirm(message)) return false;

        /* Continue with the deletion */
        ajax_call_helper(event, 'notifications-ajax', 'delete', (data) => {
            redirect(`campaign/${data.details.campaign_id}`);
        });

    });
</script>
<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
