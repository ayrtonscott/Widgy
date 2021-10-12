<?php defined('ALTUMCODE') || die() ?>


<?php ob_start() ?>
<div class="altumcode-wrapper altumcode-wrapper-<?= $notification->settings->border_radius ?> <?= $notification->settings->shadow ? 'altumcode-wrapper-shadow' : null ?> altumcode-latest-conversion-wrapper" style='background-color: <?= $notification->settings->background_color ?>;border-width: <?= $notification->settings->border_width ?>px;border-color: <?= $notification->settings->border_color ?>;<?= $notification->settings->background_pattern_svg ? 'background-image: url("' . $notification->settings->background_pattern_svg . '")' : null ?>;'>
    <div class="altumcode-latest-conversion-content">
        <?php $notification->image = isset($notification->image) && $notification->image ? $notification->image : $notification->settings->image; ?>
        <?php if(!empty($notification->image)): ?>
        <img src="<?= $notification->image ?>" class="altumcode-latest-conversion-image" loading="lazy" />
        <?php endif ?>

        <div>
            <div class="altumcode-latest-conversion-header">
                <p class="altumcode-latest-conversion-title" style="color: <?= $notification->settings->title_color ?>"><?= isset($notification->title) && $notification->title ? html_entity_decode($notification->title) : $notification->settings->title ?></p>

                <span class="altumcode-close"></span>
            </div>
            <p class="altumcode-latest-conversion-description" style="color: <?= $notification->settings->description_color ?>"><?= isset($notification->description) && $notification->description ? $notification->description : $notification->settings->description ?></p>

            <div class="altumcode-latest-conversion-footer">
                <div class="altumcode-latest-conversion-time">
                    <?php if(isset($notification->last_action_date) && $notification->last_action_date): ?>
                        <?= \Altum\Date::get_timeago($notification->last_action_date) ?>
                    <?php else: ?>
                        <?= language()->notification->latest_conversion->time_ago_default ?>
                    <?php endif ?>
                </div>

                <?php if($notification->settings->display_branding): ?>
                    <?php if(isset($notification->branding, $notification->branding->name, $notification->branding->url) && !empty($notification->branding->name) && !empty($notification->branding->url)): ?>
                        <a href="<?= $notification->branding->url ?>" class="altumcode-site"><?= $notification->branding->name ?></a>
                    <?php else: ?>
                        <a href="<?= url() ?>" class="altumcode-site"><?= settings()->socialproofo->branding ?></a>
                    <?php endif ?>
                <?php endif ?>
            </div>

        </div>
    </div>
</div>
<?php $html = ob_get_clean() ?>


<?php ob_start() ?>
new AltumCodeManager({
    should_show: true,
    content: <?= json_encode($html) ?>,
    display_mobile: <?= json_encode($notification->settings->display_mobile) ?>,
    display_desktop: <?= json_encode($notification->settings->display_desktop) ?>,
    display_trigger: <?= json_encode($notification->settings->display_trigger) ?>,
    display_trigger_value: <?= json_encode($notification->settings->display_trigger_value) ?>,
    duration: <?= $notification->settings->display_duration === -1 ? -1 : $notification->settings->display_duration * 1000 ?>,
    url: <?= json_encode(isset($notification->url) && $notification->url ? $notification->url : $notification->settings->url) ?>,
    url_new_tab: <?= json_encode($notification->settings->url_new_tab) ?>,
    close: <?= json_encode($notification->settings->display_close_button) ?>,
    display_frequency: <?= json_encode($notification->settings->display_frequency) ?>,
    position: <?= json_encode($notification->settings->display_position) ?>,
    trigger_all_pages: <?= json_encode($notification->settings->trigger_all_pages) ?>,
    triggers: <?= json_encode($notification->settings->triggers) ?>,
    data_trigger_auto: <?= json_encode($notification->settings->data_trigger_auto) ?>,
    data_triggers_auto: <?= json_encode($notification->settings->data_triggers_auto) ?>,
    on_animation: <?= json_encode($notification->settings->on_animation) ?>,
    off_animation: <?= json_encode($notification->settings->off_animation) ?>,

    notification_id: <?= $notification->notification_id ?>
}).initiate();
<?php $javascript = ob_get_clean(); ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
