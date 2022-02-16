<?php defined('ALTUMCODE') || die() ?>
<?php // * Agregado 2/2 en 10.0.0 - (Agregamos Intercom) INICIO. 
?>
<?php isset($this->user->user_id) ? $hash = hash_hmac('sha256', $this->user->user_id, 'a5edqk3IEE_hhIILFborw4_7wRz1N9RroK9g-a0V') : "" ?>
<?php // * Agregado 2/2 en 10.0.0 - (Agregamos Intercom) FIN. 
?>
<!DOCTYPE html>
<html lang="<?= \Altum\Language::$language_code ?>" dir="<?= l('direction') ?>">

<head>
  <meta charset="UTF-8" />
  <title><?= \Altum\Title::get() ?></title>
  <base href="<?= SITE_URL; ?>">
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
  <meta http-equiv="content-language" content="<?= \Altum\Language::$language_code  ?>" />
  <?php if (\Altum\Meta::$description) : ?>
    <meta name="description" content="<?= \Altum\Meta::$description ?>" />
  <?php endif ?>
  <?php if (\Altum\Meta::$keywords) : ?>
    <meta name="keywords" content="<?= \Altum\Meta::$keywords ?>" />
  <?php endif ?>
  <?php if (\Altum\Meta::$open_graph['url']) : ?>
    <!-- Open Graph / Facebook / Twitter -->
    <?php foreach (\Altum\Meta::$open_graph as $key => $value) : ?>
      <?php if ($value) : ?>
        <meta property="og:<?= $key ?>" content="<?= $value ?>" />
        <meta property="twitter:<?= $key ?>" content="<?= $value ?>" />
      <?php endif ?>
    <?php endforeach ?>
  <?php endif ?>

  <?php if (!settings()->main->se_indexing) : ?>
    <meta name="robots" content="noindex">
  <?php endif ?>

  <link rel="alternate" href="<?= SITE_URL . \Altum\Routing\Router::$original_request ?>" hreflang="x-default" />
  <?php if (count(\Altum\Language::$languages) > 1) : ?>
    <?php foreach (\Altum\Language::$languages as $language_code => $language_name) : ?>
      <?php if (settings()->default_language != $language_name) : ?>
        <link rel="alternate" href="<?= SITE_URL . $language_code . '/' . \Altum\Routing\Router::$original_request ?>" hreflang="<?= $language_code ?>" />
      <?php endif ?>
    <?php endforeach ?>
  <?php endif ?>
  <?php if (!empty(settings()->favicon)) : ?>
    <link href="<?= UPLOADS_FULL_URL . 'favicon/' . settings()->favicon ?>" rel="shortcut icon" />
  <?php endif ?>

  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link rel="stylesheet" href="themes/widgy/assets/font/iconsmind-s/css/iconsminds.css" />
  <link rel="stylesheet" href="themes/widgy/assets/font/simple-line-icons/css/simple-line-icons.css" />
  <link rel="stylesheet" href="themes/widgy/assets/css/vendor/bootstrap.min.css" />
  <link rel="stylesheet" href="themes/widgy/assets/css/vendor/owl.carousel.min.css" />
  <link rel="stylesheet" href="themes/widgy/assets/css/dore.light.blueyale.min.css" />
  <link rel="stylesheet" href="themes/widgy/assets/css/main.css" />
  <link rel="stylesheet" href="themes/widgy/assets/css/custom.css" />

  <?php if (!empty(settings()->custom->head_js)) : ?>
    <?= settings()->custom->head_js ?>
  <?php endif ?>

  <?php if (!empty(settings()->custom->head_css)) : ?>
    <style>
      <?= settings()->custom->head_css ?>
    </style>
  <?php endif ?>

</head>

<body class="show-spinner no-footer">
  <div class="landing-page">
    <div class="mobile-menu">
      <a href="#home" class="logo-mobile scrollTo">
        <span></span>
      </a>
      <ul class="navbar-nav">
        <li class="nav-item"><a href="#home" class="scrollTo"><?= l('index.custom.nav_item1') ?></a></li>
        <li class="nav-item"><a href="#about" class="scrollTo"><?= l('index.custom.nav_item2') ?></a></li>
        <li class="nav-item"><a href="#notification_preview" class="scrollTo"><?= l('index.custom.nav_item3') ?></a></li>
        <li class="nav-item"><a href="#pricing" class="scrollTo"><?= l('index.custom.nav_item4') ?></a></li>
        <li class="nav-item">
          <div class="separator"></div>
        </li>
        <li class="nav-item pl-5"><a href="login"><strong><?= l('global.login') ?></strong></a>
        <li class="nav-item pl-4">
          <?php if (count(\Altum\Language::$languages) > 1) : ?>

            <a class=" btn btn-outline-semi-light btn-sm pr-4 pl-4 dropdown-toggle clickable" id="language_switch" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="text-muted"></i> <?= l('global.language') ?></a>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="language_switch">
              <h6 class="dropdown-header"><?= l('global.choose_language') ?></h6>
              <?php foreach (\Altum\Language::$languages as $language_code => $language_name) : ?>
                <a class="dropdown-item" href="<?= SITE_URL . $language_code . '/' . \Altum\Routing\Router::$original_request . '?set_language=' . $language_name ?>">
                  <?php if ($language_name == \Altum\Language::$language) : ?>
                    <i class="fa fa-fw fa-sm fa-check mr-1 text-success"></i>
                  <?php else : ?>
                    <i class="fa fa-fw fa-sm fa-circle-notch mr-1 text-muted"></i>
                  <?php endif ?>

                  <?= $language_name ?>
                </a>
              <?php endforeach ?>
            </div>
          <?php endif ?>

        </li>
      </ul>
    </div>

    <div class="main-container">
      <nav class="landing-page-nav">
        <div class="container d-flex align-items-center justify-content-between">
          <a class="navbar-logo pull-left scrollTo" href="#home">
            <span class="white"></span>
            <span class="dark"></span>
          </a>
          <ul class="navbar-nav d-none d-lg-flex flex-row">
            <li class="nav-item"><a href="#home" class="scrollTo"><?= l('index.custom.nav_item1') ?></a></li>
            <li class="nav-item"><a href="#about" class="scrollTo"><?= l('index.custom.nav_item2') ?></a></li>
            <li class="nav-item"><a href="#notification_preview" class="scrollTo"><?= l('index.custom.nav_item3') ?></a></li>
            <li class="nav-item"><a href="#pricing" class="scrollTo"><?= l('index.custom.nav_item4') ?></a></li>
            <li class="nav-item">
              <div class="separator"></div>
            </li>
            <li class="nav-item pl-5"><a href="login"><strong><?= l('global.login') ?></strong></a>
            </li>
            <li class="nav-item pl-2">
              <?php if (count(\Altum\Language::$languages) > 1) : ?>

                <a class=" btn btn-outline-semi-light btn-sm pr-4 pl-4 dropdown-toggle clickable" id="language_switch" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="text-muted"></i> <?= l('global.language') ?></a>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="language_switch">
                  <h6 class="dropdown-header"><?= l('global.choose_language') ?></h6>
                  <?php foreach (\Altum\Language::$languages as $language_code => $language_name) : ?>
                    <a class="dropdown-item" href="<?= SITE_URL . $language_code . '/' . \Altum\Routing\Router::$original_request . '?set_language=' . $language_name ?>">
                      <?php if ($language_name == \Altum\Language::$language) : ?>
                        <i class="fa fa-fw fa-sm fa-check mr-1 text-success"></i>
                      <?php else : ?>
                        <i class="fa fa-fw fa-sm fa-circle-notch mr-1 text-muted"></i>
                      <?php endif ?>

                      <?= $language_name ?>
                    </a>
                  <?php endforeach ?>
                </div>
              <?php endif ?>

            </li>
          </ul>
          <a href="#" class="mobile-menu-button">
            <i class="simple-icon-menu"></i>
          </a>
        </div>
      </nav>

      <div class="content-container" id="home">
        <div class="section home">
          <div class="container">
            <div class="row home-row">
              <div class="col-12 d-block d-md-none">
                <a>
                  <img alt="mobile hero" class="mobile-hero" src="themes/widgy/assets/img/landing-page/home-hero-mobile_<?= \Altum\Language::$language_code ?>.png" />
                </a>
              </div>

              <div class="col-12 col-xl-4 col-lg-5 col-md-6">
                <div class="home-text">
                  <div class="display-1"><?= l('index.header') ?></div>
                  <p class="white mb-5">
                    <?= l('index.subheader') ?>
                  </p>
                  <a class="btn btn-secondary btn-xl mr-2 mb-2" target="_blank" href="<?= l('index.custom.register_link') ?>"><?= l('index.cta.header') ?> <i class="simple-icon-arrow-right"></i></a>

                </div>
              </div>
              <div class="col-12 col-xl-7 offset-xl-1 col-lg-7 col-md-6  d-none d-md-block">
                <a>
                  <img alt="hero" src="themes/widgy/assets/img/landing-page/home-hero_<?= \Altum\Language::$language_code ?>.png" />
                </a>
              </div>
            </div>

            <div class="row">
              <div class="col-12 p-0">
                <div class="owl-container">

                  <div class="owl-carousel home-carousel">

                    <div class="card">
                      <div class="card-body text-center">
                        <div>
                          <img src="themes/widgy/assets/img/landing-page/banner1_<?= \Altum\Language::$language_code ?>.jpg">
                        </div>
                      </div>
                    </div>

                    <div class="card">
                      <div class="card-body text-center">
                        <div>
                          <img src="themes/widgy/assets/img/landing-page/banner2_<?= \Altum\Language::$language_code ?>.jpg">
                        </div>
                      </div>
                    </div>

                    <div class="card">
                      <div class="card-body text-center">
                        <div>
                          <img src="themes/widgy/assets/img/landing-page/banner3_<?= \Altum\Language::$language_code ?>.jpg">
                        </div>
                      </div>
                    </div>

                    <div class="card">
                      <div class="card-body text-center">
                        <div>
                          <img src="themes/widgy/assets/img/landing-page/banner4_<?= \Altum\Language::$language_code ?>.jpg">
                        </div>
                      </div>
                    </div>

                    <div class="card">
                      <div class="card-body text-center">
                        <div>
                          <img src="themes/widgy/assets/img/landing-page/banner5_<?= \Altum\Language::$language_code ?>.jpg">
                        </div>
                      </div>
                    </div>

                    <div class="card">
                      <div class="card-body text-center">
                        <div>
                          <img src="themes/widgy/assets/img/landing-page/banner6_<?= \Altum\Language::$language_code ?>.jpg">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">

              <a class="btn btn-circle btn-outline-semi-light hero-circle-button scrollTo" href="#notification_preview" id="homeCircleButton"><i class="simple-icon-arrow-down"></i></a>
            </div>

          </div>

        </div>

        <div class="container">

          <div class="container" id="about">
            <div class="row mb-2">
              <div class="col-12 offset-0 col-lg-8 offset-lg-2 text-center">
                <h1><?= l('index.tools.header') ?></h1>
                <p>
                  <?= sprintf(l('index.tools.subheader'), nr($data->total_track_notifications)) ?>
                </p>
                <a class="btn btn-secondary btn-xl mr-2 mb-2" target="_blank" href="<?= l('index.custom.register_link') ?>"><?= l('index.cta.header') ?> <i class="simple-icon-arrow-right"></i></a>
              </div>
            </div>

            <div class="row">
              <div class="col-12 col-md-6 col-lg-6 order-2 order-md-1">
                <img alt="feature" class="feature-image-left feature-image-charts" src="themes/widgy/assets/img/landing-page/features/plesant-design.png" />
              </div>

              <div class="col-12 col-md-6 offset-md-0 col-lg-5 offset-lg-1 d-flex align-items-center order-1 order-md-2">
                <div class="d-flex">
                  <div class="feature-icon-container">
                    <div class="icon-background">
                      <i class="fas fa-fw fa-ban"></i>
                    </div>
                  </div>
                  <div class="feature-text-container mt-4">
                    <h2><?= l('index.custom.feature1_title') ?></h2>
                    <p>
                      <?= l('index.custom.feature1_desc') ?>
                    </p>
                  </div>
                </div>
              </div>
            </div>

            <div class="row">
              <div class="col-12 col-md-6 col-lg-5 d-flex align-items-center">
                <div class="d-flex">
                  <div class="feature-text-container">
                    <h2><?= l('index.custom.feature2_title') ?></h2>
                    <p>
                      <?= l('index.custom.feature2_desc') ?>
                    </p>
                  </div>
                </div>
              </div>
              <div class="col-12 col-md-6 col-lg-6 offset-lg-1 offset-md-0 position-relative">
                <div class="background-item-1"></div>
                <img alt="feature" class="feature-image-right feature-image-charts position-relative" src="themes/widgy/assets/img/landing-page/features/settings-panel_<?= \Altum\Language::$language_code ?>.png" />
              </div>
            </div>
          </div>


        </div>

        <div class="container mt-8">

          <div class="mb-3 d-flex justify-content-between align-items-center flex-column flex-md-row">
            <div>
              <h3><span class=""><?= l('index.tools.preview') ?></span></h3>
              <p class="text-muted"><?= l('index.tools.preview_description') ?></p>
            </div>

            <div id="notification_preview" class="container-disabled-simple"></div>
          </div>

          <div class="mt-8 row d-flex align-items-center">

            <?php foreach ($data->notifications as $notification_type => $notification_config) : ?>

              <?php $notification = \Altum\Notification::get($notification_type) ?>

              <label class="col-12 col-md-6 col-lg-4 mb-3 mb-md-4 custom-radio-box mb-3">

                <input type="radio" name="type" value="<?= $notification_type ?>" class="custom-control-input" required="required">

                <div class="card shadow-lg zoomer h-100 custom-radio-box">
                  <div class="card-body">

                    <div class="mb-3 text-center">
                      <span class="custom-radio-box-main-icon"><i class="<?= l('notification.' . mb_strtolower($notification_type) . '.icon') ?>"></i></span>
                    </div>

                    <div class="card-title font-weight-bold text-center"><?= l('notification.' . mb_strtolower($notification_type) . '.name') ?>
                    </div>

                    <p class="text-muted text-center"><?= l('notification.' . mb_strtolower($notification_type) . '.description') ?></p>

                  </div>
                </div>

                <div class="preview" style="display: none">
                  <?= preg_replace(['/<form/', '/<\/form>/', '/required=\"required\"/'], ['<div', '</div>', ''], $notification->html) ?>
                </div>

              </label>

              <?php if ($notification_type == 'ENGAGEMENT_LINKS') : ?>
                <?php ob_start() ?>
                <script>
                  $('.altumcode-engagement-links-wrapper .altumcode-engagement-links-hidden').removeClass('altumcode-engagement-links-hidden').addClass('altumcode-engagement-links-shown');
                </script>
                <?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>
              <?php endif ?>

            <?php endforeach ?>

          </div>
        </div>

        <div class="section mb-0">
          <div class="container" id="layouts">
            <div class="row">
              <div class="col-12 offset-0 col-lg-8 offset-lg-2 text-center" id="pricing">
                <h1><?= l('index.pricing.header') ?></h1>
                <p>
                  <?= l('index.pricing.subheader') ?>
                </p>
              </div>
            </div>
            <div class="row equal-height-container">

              <?php
              $plans = [];
              $available_payment_frequencies = [];

              $plans_result = database()->query("SELECT * FROM `plans` WHERE `status` = 1 ORDER BY `order`");

              while ($plan = $plans_result->fetch_object()) {
                $plans[] = $plan;

                foreach (['monthly', 'annual', 'lifetime'] as $value) {
                  if ($plan->{$value . '_price'}) {
                    $available_payment_frequencies[$value] = true;
                  }
                }
              }

              ?>


              <?php foreach ($plans as $plan) : ?>

                <?php $plan->settings = json_decode($plan->settings) ?>


                <div class="col-md-12 col-lg-4 mb-4 col-item zoomer">
                  <div class="card">
                    <div class="card-body pt-5 pb-5 d-flex flex-lg-column flex-md-row flex-sm-row flex-column">
                      <div class="price-top-part">
                        <i class="iconsminds-male large-icon"></i>
                        <h5 class="mb-0 font-weight-semibold color-theme-1 mb-4"><?= $plan->name ?></h5>
                        <p class="text-large mb-2 text-default">
                          <?php if (\Altum\Language::$language_code == "tiendanube") : ?>
                            <?= $plan->monthly_price * 191 ?>
                          <?php else : ?>
                            <?= $plan->monthly_price ?>
                          <?php endif ?>
                        <h6><?php if (\Altum\Language::$language_code == "tiendanube") : ?>
                            ARS
                          <?php else : ?>
                            USD
                          <?php endif ?>
                        </h6>
                        </p>
                        <p class="text-muted text-small "><?= l('plan.custom_plan.monthly') ?></p>
                      </div>
                      <div class="pl-3 pr-3 pt-3 pb-0 d-flex price-feature-list flex-column flex-grow-1">
                        <?= include_view(THEME_PATH . 'views/partials/plans_plan_content.php', ['plan_settings' => $plan->settings]) ?>
                        <div class="text-center">
                          <?php if (\Altum\Language::$language_code == "tiendanube") : ?>
                            <a href="<?= l('index.custom.register_link') ?>" class="btn btn-primary btn-lg">
                            <?php else : ?>
                              <a href="<?= url('register?redirect=pay/' . $plan->plan_id) ?>" class="btn btn-primary btn-lg">
                              <?php endif ?>

                              <?php if (\Altum\Middlewares\Authentication::check()) : ?>
                                <?php if (!$this->user->plan_trial_done && $plan->trial_days) : ?>
                                  <?= sprintf(l('plan.button.trial'), $plan->trial_days) ?>
                                <?php elseif ($this->user->plan_id == $plan->plan_id) : ?>
                                  <?= l('plan.button.renew') ?>
                                <?php else : ?>
                                  <?= l('plan.button.choose') ?>
                                <?php endif ?>
                              <?php else : ?>
                                <?php if ($plan->trial_days) : ?>
                                  <?= sprintf(l('plan.button.trial'), $plan->trial_days) ?>
                                <?php else : ?>
                                  <?= l('plan.button.choose') ?>
                                <?php endif ?>
                              <?php endif ?>
                              <i class="simple-icon-arrow-right"></i>
                              </a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <?php if ($plan->plan_id == 3) break; ?>
              <?php endforeach ?>
            </div>
          </div>
        </div>

        <div class="section background background-no-bottom mb-0 pb-0">
          <div class="container">
            <div class="row">
              <div class="col-12 offset-0 col-lg-8 offset-lg-2 text-center">
                <h1><?= l('index.cta.header') ?> </h1>
                <p>
                  <?= l('index.cta.subheader') ?>
                </p>
              </div>

              <div class="col-12 offset-0 col-lg-6 offset-lg-3 newsletter-input-container">
                <div class="text-center mb-3">
                  <a class="btn btn-secondary btn-xl" target="_top" href="<?= l('index.custom.register_link') ?>"><?= l('index.sign_up') ?></a>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="section footer mb-0">

          <div class="container">
            <div class="row footer-row">
              <div class="col-12 text-right">
                <a class="btn btn-circle btn-outline-semi-light footer-circle-button scrollTo" href="#home" id="footerCircleButton"><i class="simple-icon-arrow-up"></i></a>
              </div>
              <div class="col-12 text-center footer-content">
                <a href="#home" class="scrollTo">
                  <img class="footer-logo" alt="footer logo" src="themes/widgy/assets/logos/white-full.svg" />
                </a>
              </div>
            </div>
          </div>

          <div class="separator mt-5"></div>

          <div class="container copyright pt-5 pb-5">
            <div class="row">
              <div class="col-12"></div>
              <div class="col-12 text-center">
                <p class="mb-0">Copyright &copy Widgy.app 2019 - 2022</p>
              </div>

            </div>
          </div>
        </div>

      </div>
    </div>
  </div>

  <script src="themes/widgy/assets/js/vendor/jquery-3.3.1.min.js"></script>
  <script src="themes/widgy/assets/js/vendor/bootstrap.bundle.min.js"></script>
  <script src="themes/widgy/assets/js/vendor/owl.carousel.min.js"></script>
  <script src="themes/widgy/assets/js/vendor/jquery.barrating.min.js"></script>
  <script src="themes/widgy/assets/js/vendor/landing-page/headroom.min.js"></script>
  <script src="themes/widgy/assets/js/vendor/landing-page/jQuery.headroom.js"></script>
  <script src="themes/widgy/assets/js/vendor/landing-page/jquery.scrollTo.min.js"></script>
  <script src="themes/widgy/assets/js/vendor/landing-page/jquery.autoellipsis.js"></script>
  <script src="themes/widgy/assets/js/dore.scripts.landingpage.js"></script>
  <script src="themes/widgy/assets/js/libraries/fontawesome.min.js"></script>
  <script src="themes/widgy/assets/js/libraries/fontawesome-solid.min.js"></script>
  <script src="themes/widgy/assets/js/libraries/fontawesome-brands.modified.js"></script>
  <script src="themes/widgy/assets/js/scripts.single.theme.js"></script>
</body>

</html>

<link rel="stylesheet" href="<?= ASSETS_FULL_URL . 'css/aos.min.css' ?>">
<link href="<?= ASSETS_FULL_URL . 'css/pixel.css' ?>" rel="stylesheet" media="screen,print">
<script src="<?= ASSETS_FULL_URL . 'js/libraries/aos.min.js' ?>"></script>

<script>
  AOS.init({
    delay: 100,
    duration: 600
  });

  /* Preview handler */
  $('input[name="type"]').on('change', (event, first_trigger = false) => {

    let preview_html = $(event.currentTarget).closest('label').find('.preview').html();

    $('#notification_preview').hide().html(preview_html).fadeIn();

    /* Make sure its not the first check */
    if (!first_trigger) {
      document.querySelector('#notification_preview').scrollIntoView();
    }

  });

  /* Select a default option */
  $('input[name="type"]:first').attr('checked', true).trigger('change', true);
</script>

<?php // * Agregado 2/2/22 en 10.0.0 - (Agregamos Intercom) INICIO. 
?>
<script>
  window.intercomSettings = {
    app_id: "nds4bz0l"
  };
</script>

<script>
  // We pre-filled your app ID in the widget URL: 'https://widget.intercom.io/widget/nds4bz0l'
  (function() {
    var w = window;
    var ic = w.Intercom;
    if (typeof ic === "function") {
      ic('reattach_activator');
      ic('update', w.intercomSettings);
    } else {
      var d = document;
      var i = function() {
        i.c(arguments);
      };
      i.q = [];
      i.c = function(args) {
        i.q.push(args);
      };
      w.Intercom = i;
      var l = function() {
        var s = d.createElement('script');
        s.type = 'text/javascript';
        s.async = true;
        s.src = 'https://widget.intercom.io/widget/nds4bz0l';
        var x = d.getElementsByTagName('script')[0];
        x.parentNode.insertBefore(s, x);
      };
      if (document.readyState === 'complete') {
        l();
      } else if (w.attachEvent) {
        w.attachEvent('onload', l);
      } else {
        w.addEventListener('load', l, false);
      }
    }
  })();
</script>
<?php // * Agregado 2/2/22 en 10.0.0 - (Agregamos Intercom) FIN. 
?>

<?php \Altum\Event::add_content(ob_get_clean(), 'javascript') ?>