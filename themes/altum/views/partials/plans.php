<?php defined('ALTUMCODE') || die() ?>

<?php

use Altum\Middlewares\Authentication;

?>

<?php if(settings()->payment->is_enabled): ?>

    <?php
    $plans = [];
    $available_payment_frequencies = [];

    $plans_result = database()->query("SELECT * FROM `plans` WHERE `status` = 1 ORDER BY `order`");

    while($plan = $plans_result->fetch_object()) {
        $plans[] = $plan;

        foreach(['monthly', 'annual', 'lifetime'] as $value) {
            if($plan->{$value . '_price'}) {
                $available_payment_frequencies[$value] = true;
            }
        }
    }

    ?>

    <?php if(count($plans)): ?>
        <div class="mb-4 d-flex justify-content-center">
            <div class="btn-group btn-group-toggle" data-toggle="buttons">

                <?php if(isset($available_payment_frequencies['monthly'])): ?>
                    <label class="btn btn-outline-secondary active" data-payment-frequency="monthly">
                        <input type="radio" name="payment_frequency" checked="checked"> <?= language()->plan->custom_plan->monthly ?>
                    </label>
                <?php endif ?>

                <?php if(isset($available_payment_frequencies['annual'])): ?>
                    <label class="btn btn-outline-secondary <?= !isset($available_payment_frequencies['monthly']) ? 'active' : null ?>" data-payment-frequency="annual">
                        <input type="radio" name="payment_frequency" <?= !isset($available_payment_frequencies['monthly']) ? 'checked="checked"' : null ?>> <?= language()->plan->custom_plan->annual ?>
                    </label>
                <?php endif ?>

                <?php if(isset($available_payment_frequencies['lifetime'])): ?>
                    <label class="btn btn-outline-secondary <?= !isset($available_payment_frequencies['monthly']) && !isset($available_payment_frequencies['annual']) ? 'active' : null ?>" data-payment-frequency="lifetime">
                        <input type="radio" name="payment_frequency" <?= !isset($available_payment_frequencies['monthly']) && !isset($available_payment_frequencies['annual']) ? 'checked="checked"' : null ?>> <?= language()->plan->custom_plan->lifetime ?>
                    </label>
                <?php endif ?>

            </div>
        </div>
    <?php endif ?>
<?php endif ?>


<div class="pricing pricing-palden">

    <?php if(settings()->plan_free->status == 1): ?>

        <div class="pricing-item zoomer">
            <div class="pricing-deco">
                <h3 class="pricing-title"><?= settings()->plan_free->name ?></h3>

                <svg class="pricing-deco-img" enable-background="new 0 0 300 100" height="100px" id="Layer_1" preserveAspectRatio="none" version="1.1" viewBox="0 0 300 100" width="300px" x="0px" xml:space="preserve" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" y="0px">
                    <path class="deco-layer deco-layer--1" d="M30.913,43.944c0,0,42.911-34.464,87.51-14.191c77.31,35.14,113.304-1.952,146.638-4.729&#x000A;	c48.654-4.056,69.94,16.218,69.94,16.218v54.396H30.913V43.944z" fill="#FFFFFF" opacity="0.6"></path>
                    <path class="deco-layer deco-layer--2" d="M-35.667,44.628c0,0,42.91-34.463,87.51-14.191c77.31,35.141,113.304-1.952,146.639-4.729&#x000A;	c48.653-4.055,69.939,16.218,69.939,16.218v54.396H-35.667V44.628z" fill="#FFFFFF" opacity="0.6"></path>
                    <path class="deco-layer deco-layer--3" d="M43.415,98.342c0,0,48.283-68.927,109.133-68.927c65.886,0,97.983,67.914,97.983,67.914v3.716&#x000A;	H42.401L43.415,98.342z" fill="#FFFFFF" opacity="0.7"></path>
                    <path class="deco-layer deco-layer--4" d="M-34.667,62.998c0,0,56-45.667,120.316-27.839C167.484,57.842,197,41.332,232.286,30.428&#x000A;	c53.07-16.399,104.047,36.903,104.047,36.903l1.333,36.667l-372-2.954L-34.667,62.998z" fill="#FFFFFF"></path>
                </svg>

                <div class="pricing-price">
                    <?= language()->plan->free->price ?>
                </div>

                <div class="pricing-sub">&nbsp;</div>
            </div>

            <?= include_view(THEME_PATH . 'views/partials/plans_plan_content.php', ['plan_settings' => settings()->plan_free->settings]) ?>

            <?php if(Authentication::check() && $this->user->plan_id == 'free'): ?>
                <button class="pricing-action-disabled"><?= language()->plan->button->already_free ?></button>
            <?php else: ?>
                <a href="<?= Authentication::check() ? url('pay/free') : url('register') ?>" class="pricing-action"><?= language()->plan->button->choose ?></a>
            <?php endif ?>
        </div>

    <?php endif ?>

    <?php if(settings()->payment->is_enabled): ?>

        <?php if(settings()->plan_trial->status == 1): ?>

        <div class="pricing-item zoomer">
            <div class="pricing-deco">
                <h3 class="pricing-title"><?= settings()->plan_trial->name ?></h3>

                <svg class="pricing-deco-img" enable-background="new 0 0 300 100" height="100px" id="Layer_1" preserveAspectRatio="none" version="1.1" viewBox="0 0 300 100" width="300px" x="0px" xml:space="preserve" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" y="0px">
                    <path class="deco-layer deco-layer--1" d="M30.913,43.944c0,0,42.911-34.464,87.51-14.191c77.31,35.14,113.304-1.952,146.638-4.729&#x000A;	c48.654-4.056,69.94,16.218,69.94,16.218v54.396H30.913V43.944z" fill="#FFFFFF" opacity="0.6"></path>
                    <path class="deco-layer deco-layer--2" d="M-35.667,44.628c0,0,42.91-34.463,87.51-14.191c77.31,35.141,113.304-1.952,146.639-4.729&#x000A;	c48.653-4.055,69.939,16.218,69.939,16.218v54.396H-35.667V44.628z" fill="#FFFFFF" opacity="0.6"></path>
                    <path class="deco-layer deco-layer--3" d="M43.415,98.342c0,0,48.283-68.927,109.133-68.927c65.886,0,97.983,67.914,97.983,67.914v3.716&#x000A;	H42.401L43.415,98.342z" fill="#FFFFFF" opacity="0.7"></path>
                    <path class="deco-layer deco-layer--4" d="M-34.667,62.998c0,0,56-45.667,120.316-27.839C167.484,57.842,197,41.332,232.286,30.428&#x000A;	c53.07-16.399,104.047,36.903,104.047,36.903l1.333,36.667l-372-2.954L-34.667,62.998z" fill="#FFFFFF"></path>
                </svg>

                <div class="pricing-price">
                    <?= language()->plan->trial->price ?>
                </div>

                <div class="pricing-sub">&nbsp;</div>
            </div>

            <?= include_view(THEME_PATH . 'views/partials/plans_plan_content.php', ['plan_settings' => settings()->plan_trial->settings]) ?>

            <?php if(Authentication::check() && $this->user->plan_trial_done): ?>
                <button class="pricing-action-disabled"><?= language()->plan->button->disabled ?></button>
            <?php else: ?>
                <a href="<?= Authentication::check() ? url('pay/trial') : url('register?redirect=pay/trial') ?>" class="pricing-action"><?= language()->plan->button->choose ?></a>
            <?php endif ?>
        </div>

    <?php endif ?>

        <?php foreach($plans as $plan): ?>

            <?php $plan->settings = json_decode($plan->settings) ?>

            <div
                class="pricing-item zoomer"
                data-plan-monthly="<?= json_encode((bool) $plan->monthly_price) ?>"
                data-plan-annual="<?= json_encode((bool) $plan->annual_price) ?>"
                data-plan-lifetime="<?= json_encode((bool) $plan->lifetime_price) ?>"
            >
                <div class="pricing-deco">
                    <h3 class="pricing-title"><?= $plan->name ?></h3>

                    <svg class="pricing-deco-img" enable-background="new 0 0 300 100" height="100px" id="Layer_1" preserveAspectRatio="none" version="1.1" viewBox="0 0 300 100" width="300px" x="0px" xml:space="preserve" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" y="0px">
                        <path class="deco-layer deco-layer--1" d="M30.913,43.944c0,0,42.911-34.464,87.51-14.191c77.31,35.14,113.304-1.952,146.638-4.729&#x000A;	c48.654-4.056,69.94,16.218,69.94,16.218v54.396H30.913V43.944z" fill="#FFFFFF" opacity="0.6"></path>
                        <path class="deco-layer deco-layer--2" d="M-35.667,44.628c0,0,42.91-34.463,87.51-14.191c77.31,35.141,113.304-1.952,146.639-4.729&#x000A;	c48.653-4.055,69.939,16.218,69.939,16.218v54.396H-35.667V44.628z" fill="#FFFFFF" opacity="0.6"></path>
                        <path class="deco-layer deco-layer--3" d="M43.415,98.342c0,0,48.283-68.927,109.133-68.927c65.886,0,97.983,67.914,97.983,67.914v3.716&#x000A;	H42.401L43.415,98.342z" fill="#FFFFFF" opacity="0.7"></path>
                        <path class="deco-layer deco-layer--4" d="M-34.667,62.998c0,0,56-45.667,120.316-27.839C167.484,57.842,197,41.332,232.286,30.428&#x000A;	c53.07-16.399,104.047,36.903,104.047,36.903l1.333,36.667l-372-2.954L-34.667,62.998z" fill="#FFFFFF"></path>
                    </svg>

                    <div class="pricing-price">
                        <span class="d-none" data-plan-payment-frequency="monthly"><?= $plan->monthly_price ?></span>
                        <span class="d-none" data-plan-payment-frequency="annual"><?= $plan->annual_price ?></span>
                        <span class="d-none" data-plan-payment-frequency="lifetime"><?= $plan->lifetime_price ?></span>
                        <span class="pricing-currency"><?= settings()->payment->currency ?></span>
                    </div>

                    <div class="pricing-sub">
                        <span class="d-none" data-plan-payment-frequency="monthly"><?= language()->plan->custom_plan->monthly_payments ?></span>
                        <span class="d-none" data-plan-payment-frequency="annual"><?= language()->plan->custom_plan->annual_payments ?></span>
                        <span class="d-none" data-plan-payment-frequency="lifetime"><?= language()->plan->custom_plan->lifetime_payments ?></span>
                    </div>
                </div>

                <?= include_view(THEME_PATH . 'views/partials/plans_plan_content.php', ['plan_settings' => $plan->settings]) ?>

                <a href="<?= Authentication::check() ? url('pay/' . $plan->plan_id) : url('register?redirect=pay/' . $plan->plan_id) ?>" class="pricing-action"><?= language()->plan->button->choose ?></a>
            </div>

        <?php endforeach ?>

        <?php ob_start() ?>
        <script>
            'use strict';

            let payment_frequency_handler = (event = null) => {

                let payment_frequency = null;

                if(event) {
                    payment_frequency = $(event.currentTarget).data('payment-frequency');
                } else {
                    payment_frequency = $('[name="payment_frequency"]:checked').closest('label').data('payment-frequency');
                }

                switch(payment_frequency) {
                    case 'monthly':
                        $(`[data-plan-payment-frequency="annual"]`).removeClass('d-inline-block').addClass('d-none');
                        $(`[data-plan-payment-frequency="lifetime"]`).removeClass('d-inline-block').addClass('d-none');

                        break;

                    case 'annual':
                        $(`[data-plan-payment-frequency="monthly"]`).removeClass('d-inline-block').addClass('d-none');
                        $(`[data-plan-payment-frequency="lifetime"]`).removeClass('d-inline-block').addClass('d-none');

                        break

                    case 'lifetime':
                        $(`[data-plan-payment-frequency="monthly"]`).removeClass('d-inline-block').addClass('d-none');
                        $(`[data-plan-payment-frequency="annual"]`).removeClass('d-inline-block').addClass('d-none');

                        break
                }

                $(`[data-plan-payment-frequency="${payment_frequency}"]`).addClass('d-inline-block');

                $(`[data-plan-${payment_frequency}="true"]`).removeClass('d-none').addClass('');
                $(`[data-plan-${payment_frequency}="false"]`).addClass('d-none').removeClass('');

            };

            $('[data-payment-frequency]').on('click', payment_frequency_handler);

            payment_frequency_handler();
        </script>
        <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>

    <?php endif ?>

</div>
