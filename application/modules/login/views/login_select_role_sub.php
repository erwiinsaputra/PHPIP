<!doctype html>
<!--[if lte IE 9]>     <html lang="fr" class="template--home ie9"> <![endif]-->
<!--[if gt IE 9]><!--> <html lang="fr" class="template--home" data-template="home"> <!--<![endif]-->
  
<!-- Mirrored from dansmonsac.ca/ by HTTrack Website Copier/3.x [XR&CO'2014], Thu, 29 Nov 2018 01:12:15 GMT -->
<!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=UTF-8" /><!-- /Added by HTTrack -->
<head>
    <meta charset="utf-8">
    <base >
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#f44044">
    <title>Login Select Role</title>

    <link rel="icon" type="image/png" href="<?php echo base_url('public/assets/login/img/logo_gmf.png') ?>" sizes="16x16">
    <link rel="icon" type="image/png" href="<?php echo base_url('public/assets/login/img/logo_gmf.png') ?>" sizes="32x32">
    <link rel="apple-touch-icon-precomposed" href="<?php echo base_url('public/assets/login/img/logo_gmf.png') ?>">
    <!--[if IE]><link rel="shortcut icon" href="assets/images/favicon-32.ico"><![endif]-->

    <link rel="stylesheet" href="<?php echo base_url('public/assets/login/modules/cocqsac/assets/styles/dist/main5e1f.css?v=2') ?>">
    <link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600' rel='stylesheet' type='text/css'>

    <style type="text/css">
      .btn_link{
        cursor:pointer;
      }
    </style>
    <meta name="description" content="Santé sexuelle et prévention à l'intention des femmes du Québec. Découvre les stratégies santé de Julie, Audrey, Lyne et Rosa, et trouve la tienne.">

      <meta property="og:title" content="Dans Mon Sac" />
      <meta property="og:image" content="uploads/pub-1200x628-audrey.jpg" />
      <meta property="og:description" content="Santé sexuelle et prévention à l'intention des femmes du Québec. Découvre les stratégies santé de Julie, Audrey, Lyne et Rosa, et trouve la tienne." />
      <meta property="og:url" content="fr/1/accueil.html">
    <script>
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
    (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','../www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-49592669-2', 'auto');
    ga('send', 'pageview');


    </script>

    <!-- Google Tag Manager -->
      <!-- <noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-MHKQFK" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
      <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start': new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
      j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
      })(window,document,'script','dataLayer','GTM-MHKQFK');</script> -->
    <!-- End Google Tag Manager -->

    

  </head>
  <body class="">

    <div style="display: none;">
      <img src="<?php echo base_url('public/assets/login/uploads/pub-1200x628-audrey.jpg') ?>"/>
    </div>

    <aside class="strip ">
      <!-- <a href="fr/1/accueil.html" class="strip__sac">
        <svg class="strip__svg" role="img" title="Dans mon sac"><use xlink:href="modules/cocqsac/assets/images/dist/svgs.svg#logo-sac-fr"></use></svg>
      </a> -->
      <div class="strip__inner js-categories-open">
        <span class="strip__text">
          <p class="strip__label">
            <svg class="strip__icon" role="img"><use xlink:href="<?php echo base_url('public/assets/login/modules/cocqsac/assets/images/dist/svgs.svg#icon-plus') ?>"></use></svg>
            Sub Menu          </p>
        </span>

        <span class="x">
          <svg class="x__svg" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="50px" height="50px" viewBox="0 0 50 50" enable-background="new 0 0 50 50" xml:space="preserve">
            <line id="path1" fill="none" stroke-width="6" stroke-miterlimit="10" x1="8.5" y1="41.5" x2="41.5" y2="8.5"></line>
            <line id="path2" fill="none" stroke-width="6" stroke-miterlimit="10" x1="41.5" y1="41.5" x2="8.5" y2="8.5"></line>
          </svg>
        </span>
      </div>
    </aside>
    <span class="navigation-bar">
      <span class="hamburger__text js-menu-open">Menu</span>
      <button class="hamburger js-menu-open">
        <span class="hamburger__line"></span>
        <span class="hamburger__line"></span>
        <span class="hamburger__line"></span>
      </button>
    </span>
    <div class="navigation__categories">
      <ul class="navigation__catlist">
        <?php $i=0; foreach ($arr_role_sub as $row) {?>
          <li class="navigation__catitem btn_link" linknya="<?=site_url('login/change_session_role_sub_menu');?>/<?=$row->ROLE_SUB_ID;?>">
            <a class="navigation__catlink">
              <span class="navigation__catbackground"  style="background-image:
              url(<?php echo base_url('public/assets/login/uploads/subjects/images/'.$row->IMG_MENU); ?>);"></span>
              <span class="navigation__catcontent">
                <span class="navigation__cattitle">
                  <span class="navigation__cattitlewrap">
                    <h1 class="title-slanted"><span class="navigation__pretitle"><?=$row->ROLE_SUB_NAME1;?></span> <span class="navigation__aftertitle"><?=$row->ROLE_SUB_NAME2;?></span></h1>
                  </span>
                </span>
                <span class="navigation__cattext">
                  <p class="navigation__catdesc">
                    <?=$row->ROLE_SUB_NAME1;?>
                  </p>
                  <span class="btn">Go To Start</span>
                </span>
              </span>
            </a>
          </li>
        <?php  $i++;}  ?> 
      </ul>
    </div>
    <main>
<div class="slider -loading">
  <div class="loader">
    <div class="loader__square" ></div>
    <div class="loader__square"></div>
    <div class="loader__square -last"></div>
    <div class="loader__square -clear"></div>
    <div class="loader__square"></div>
    <div class="loader__square -last"></div>
    <div class="loader__square -clear"></div>
    <div class="loader__square "></div>
    <div class="loader__square -last"></div>
  </div>

  <div class="slider__wrap  js-slider">
    <?php $i=0; foreach ($arr_role_sub as $row) {?>
      <div class="slider__item -julie btn_link" linknya="<?=site_url('login/change_session_role_sub_menu');?>/<?=$row->ROLE_SUB_ID;?>">
        <a href="<?=site_url('login/change_session_role_sub_menu');?>/<?=$row->ROLE_SUB_ID;?>" class="slider__link -julie">
          <button class="btn -plus -pulse -long">
            <span class="btn__inner">
              <span class="btn__text">Go To Start</span>
              <span class="btn__plus">+</span>
            </span>
          </button>
        </a>

        <div class="slider__background">
          <div class="slider__background__inner" style="background-image:
          url(<?php echo base_url('public/assets/login/uploads/subjects/slider_images/'.$row->IMG_MENU); ?>)"></div>
        </div>
        <div class="slider__content">
          <div class="slider__inner container">
            <div class="slider__title">
              <h1 class="title-slanted"><span class="slider__sac"><?=$row->ROLE_SUB_NAME1;?></span><span class="slider__name"><?=$row->ROLE_SUB_NAME2;?></span></h1>
            </div>

            <div class="slider__description">
              <p><?=$row->ROLE_SUB_NAME1;?></p>
            </div>
            <div class="btn-wrap">
              <a class="btn" href="<?=site_url('login/change_session_role_sub_menu');?>/<?=$row->ROLE_SUB_ID;?>">GO TO START</a>
            </div>
          </div>
        </div>
      </div>
    <?php  $i++;}  ?>
  </div>
</div>
</main>


  <script src="<?php echo base_url('public/assets/login/modules/cocqsac/assets/scripts/dist/jquery.min.js')?>"></script>
  <script>window.jQuery || document.write('<script src="<?php echo site_url('public/assets/login/modules/cocqsac/assets/scripts/src/vendors/jquery-1.11.2.min.js') ?>"><\/script>')</script>

  <script src="<?php echo base_url('public/assets/login/modules/cocqsac/assets/scripts/dist/vendors.js')?>"></script>
  <script src="<?php echo base_url('public/assets/login/modules/cocqsac/assets/scripts/dist/app.js')?>"></script>

  <script>
    $(document).ready(function() {
      
      var slider = new app.slider();
      var slider2 = new app.slider();

      slider.init();

      //event listeners
      slider.previous().on('click', function(event) {
        slider.container().slick('slickPrev');
      });

      slider.next().on('click', function(event) {
        slider.container().slick('slickNext');
      });

      $('.btn_link').on('click',function(){
        var linknya = $(this).attr('linknya');
        window.location.href = linknya;
      });
    });
  </script>

</body>

</html>

