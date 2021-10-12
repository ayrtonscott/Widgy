<?php defined('ALTUMCODE') || die() ?>


<?php ob_start() ?>
<div class="altumcode-wrapper altumcode-wrapper-<?= $notification->settings->border_radius ?> <?= $notification->settings->shadow ? 'altumcode-wrapper-shadow' : null ?> altumcode-request-collector-wrapper" style='background-color: <?= $notification->settings->background_color ?>;border-width: <?= $notification->settings->border_width ?>px;border-color: <?= $notification->settings->border_color ?>;<?= $notification->settings->background_pattern_svg ? 'background-image: url("' . $notification->settings->background_pattern_svg . '")' : null ?>;'>
    <div class="altumcode-request-collector-content">
        <div class="altumcode-request-collector-header">
            <img src="<?= $notification->settings->image ?>" class="altumcode-request-collector-image" loading="lazy" />

            <div class="altumcode-request-collector-header-text">
                <p class="altumcode-request-collector-title" style="color: <?= $notification->settings->title_color ?>"><?= $notification->settings->title ?></p>
                <p class="altumcode-request-collector-description" style="color: <?= $notification->settings->description_color ?>"><?= $notification->settings->description ?></p>
            </div>

            <div class="altumcode-request-collector-close">
                <span class="altumcode-close"></span>
            </div>
        </div>

        <p class="altumcode-request-collector-content-title" style="color: <?= $notification->settings->content_title_color ?>"><?= $notification->settings->content_title ?></p>
        <p class="altumcode-request-collector-content-description" style="color: <?= $notification->settings->content_description_color ?>"><?= $notification->settings->content_description ?></p>

        <div>
            <form class="altumcode-request-collector-form" id="altumcode-request-collector-form" name="" action="" method="GET" role="form">
                <div class="altumcode-request-collector-row">
                    <input type="text" class="" name="input" placeholder="<?= $notification->settings->input_placeholder ?>" />

                    <button type="submit" name="button" style="color: <?= $notification->settings->button_color ?>; background: <?= $notification->settings->button_background_color ?>"><?= $notification->settings->button_text ?></button>
                </div>

                <?php if($notification->settings->show_agreement): ?>
                    <div class="altumcode-agreement-checkbox">
                        <input type="checkbox" id="agreement" class="altumcode-agreement-checkbox-input" name="agreement" required="required" />
                        <label for="agreement" class="altumcode-agreement-checkbox-text" style="color: <?= $notification->settings->description_color ?>">
                            <a href="<?= $notification->settings->agreement_url ?>">
                                <?= $notification->settings->agreement_text ?>
                            </a>
                        </label>
                    </div>
                <?php endif ?>
            </form>

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
<?php $html = ob_get_clean() ?>


<?php ob_start() ?>
new AltumCodeManager({
    should_show: !localStorage.getItem('notification_<?= $notification->notification_id ?>_converted'),
    content: <?= json_encode($html) ?>,
    display_mobile: <?= json_encode($notification->settings->display_mobile) ?>,
    display_desktop: <?= json_encode($notification->settings->display_desktop) ?>,
    display_trigger: <?= json_encode($notification->settings->display_trigger) ?>,
    display_trigger_value: <?= json_encode($notification->settings->display_trigger_value) ?>,
    duration: <?= $notification->settings->display_duration === -1 ? -1 : $notification->settings->display_duration * 1000 ?>,
    close: <?= json_encode($notification->settings->display_close_button) ?>,
    display_frequency: <?= json_encode($notification->settings->display_frequency) ?>,
    position: <?= json_encode($notification->settings->display_position) ?>,
    trigger_all_pages: <?= json_encode($notification->settings->trigger_all_pages) ?>,
    triggers: <?= json_encode($notification->settings->triggers) ?>,
    on_animation: <?= json_encode($notification->settings->on_animation) ?>,
    off_animation: <?= json_encode($notification->settings->off_animation) ?>,

    notification_id: <?= $notification->notification_id ?>
}).initiate({
    displayed: main_element => {

        /* Form submission */
        main_element.querySelector('#altumcode-request-collector-form').addEventListener('submit', event => {

            let input = event.currentTarget.querySelector('[name="input"]').value;
            let notification_id = main_element.getAttribute('data-notification-id');


            if(input.trim() != '') {

                /* Data collection from the form */
                send_tracking_data({
                    ...user,
                    notification_id: notification_id,
                    type: 'collector',
                    input
                });

                AltumCodeManager.remove_notification(main_element);

                /* Make sure to let the browser know of the conversion so that it is not shown again */
                localStorage.setItem(`notification_${notification_id}_converted`, true);

                /* Redirect the user to thank you url if needed */
                let thank_you_url = <?= json_encode($notification->settings->thank_you_url) ?>;

                if(thank_you_url.trim() != '') {
                    setTimeout(() => {
                        window.location.href = thank_you_url;
                    }, 350);
                }

            }

            event.preventDefault();
        });

    }
});
<?php $javascript = ob_get_clean(); ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
