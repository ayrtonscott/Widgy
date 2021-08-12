<?php defined('ALTUMCODE') || die() ?>

<?php ob_start() ?>
<div class="altumcode-wrapper altumcode-wrapper-<?= $notification->settings->border_radius ?> <?= $notification->settings->shadow ? 'altumcode-wrapper-shadow' : null ?> altumcode-engagement-links-wrapper" style='background-color: <?= $notification->settings->background_color ?>;border-width: <?= $notification->settings->border_width ?>px;border-color: <?= $notification->settings->border_color ?>;<?= $notification->settings->background_pattern_svg ? 'background-image: url("' . $notification->settings->background_pattern_svg . '")' : null ?>;'>
    <div class="altumcode-engagement-links-content">
        <div class="altumcode-engagement-links-header">
            <div class="altumcode-engagement-links-title" style="color: <?= $notification->settings->title_color ?>"><?= $notification->settings->title ?></div>

            <span class="altumcode-close"></span>
        </div>

        <div class="altumcode-engagement-links-hidden">
            <div class="altumcode-engagement-links-categories">
            <?php if($notification->settings->categories): ?>
                <?php foreach($notification->settings->categories as $category): ?>
                    <div class="altumcode-engagement-links-category">
                        <p class="altumcode-engagement-links-category-title" style="color: <?= $notification->settings->categories_title_color ?>;"><?= $category->title ?></p>
                        <p class="altumcode-engagement-links-category-description" style="color: <?= $notification->settings->categories_description_color ?>;"><?= $category->description ?></p>

                        <div class="altumcode-engagement-links-category-links">
                            <?php foreach($category->links as $link): ?>
                            <a href="<?= $link->url ?>" class="altumcode-engagement-links-category-link" style="background: <?= $notification->settings->categories_links_background_color ?>;border-color: <?= $notification->settings->categories_links_border_color ?>;">
                                <?php if(!empty($link->image)): ?>
                                    <img src="<?= $link->image ?>" class="altumcode-engagement-links-category-link-image" alt="<?= $link->title ?>" loading="lazy" />
                                <?php endif ?>

                                <div class="altumcode-engagement-links-category-link-content">
                                    <p class="altumcode-engagement-links-category-link-title" style="color: <?= $notification->settings->categories_links_title_color ?>;"><?= $link->title ?></p>
                                    <p class="altumcode-engagement-links-category-link-description" style="color: <?= $notification->settings->categories_links_description_color ?>;"><?= $link->description ?></p>
                                </div>
                            </a>
                            <?php endforeach ?>
                        </div>
                    </div>
                <?php endforeach ?>
            <?php endif ?>
            </div>
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

        /* On click */
        main_element.querySelector('.altumcode-engagement-links-title').addEventListener('click', event => {

            let clickable = main_element.querySelector('.altumcode-engagement-links-hidden, .altumcode-engagement-links-shown');
            clickable.classList.toggle('altumcode-engagement-links-hidden');
            clickable.classList.toggle('altumcode-engagement-links-shown');

        });

    }
});
<?php $javascript = ob_get_clean(); ?>

<?php return (object) ['html' => $html, 'javascript' => $javascript] ?>
