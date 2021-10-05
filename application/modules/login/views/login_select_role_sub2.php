<!DOCTYPE html>
<html>
<head>
  <title>Di Coba</title>
</head>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('public/assets/login/index/css/style.css') ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('public/assets/login/index/css/style.scss') ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url('public/assets/login/index/css/main.css') ?>">
<link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

<style type="text/css">
  span.drop-cap {
    font-size:150%;
    font-weight:bold;
  }
  #font{
    font-family: cursive;
  }
</style>
<body>
  <section class="strips">
    <?php $i=0; foreach ($arr_role_sub as $row) {?>
    <article class="strips__strip btn_select_role" sub_id="<?=$row->ROLE_SUB_ID;?>">
      <div class="strip__content">
        <h1 class="strip__title feature-item wow animated bounceIn1" id="font" data-name="Lorem"><?=$row->ROLE_SUB;?></h1>
        <div class="strip__inner-text" style="margin-top: 250px;">
          <h2 id="font"><span class="drop-cap"><?=$row->ROLE_SUB_NAME;?></span></h2>
          <p id="font"><?=$row->ROLE_SUB_DESC;?></p>
          <div class="banner-area">
            <div class="container">
              <div class="row justify-content-center height align-items-center">
                <div class="col-lg-8">
                  <div class="banner-content text-center">
                    <a href="<?=site_url('login/change_session_role_sub_menu');?>/<?=$row->ROLE_SUB_ID;?>" class="primary-btn d-inline-flex align-items-center banner-area1"><span class="mr-10">Get Started</span><span class="lnr lnr-arrow-right"></span></i></a>
                  </div>
                </div>
              </div>
            </div>
          </div>  
        </div> 
      </div>
    </article>
    <?php  $i++;}  ?>
      <i class="fa fa-sign-out fa-6 strip__close"></i>
  </section>
</body>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
<script type="text/javascript">
function myFunction() {
    var x = document.getElementById('myDIV');
    if (x.style.display === 'none') {
        x.style.display = 'block';
    } else {
        x.style.display = 'none';
    }
}

var Expand = (function() {
  var tile = $('.strips__strip');
  var tileLink = $('.strips__strip > .strip__content');
  var tileText = tileLink.find('.strip__inner-text');
  var stripClose = $('.strip__close');
  
  var expanded  = false;

  var open = function() {
      
    var tile = $(this).parent();

      if (!expanded) {
        tile.addClass('strips__strip--expanded');
        // add delay to inner text
        tileText.css('transition', 'all .5s .3s cubic-bezier(0.23, 1, 0.32, 1)');
        stripClose.addClass('strip__close--show');
        stripClose.css('transition', 'all .6s 1s cubic-bezier(0.23, 1, 0.32, 1)');
        expanded = true;
      } 
    };
  
  var close = function() {
    if (expanded) {
      tile.removeClass('strips__strip--expanded');
      // remove delay from inner text
      tileText.css('transition', 'all 0.15s 0 cubic-bezier(0.23, 1, 0.32, 1)');
      stripClose.removeClass('strip__close--show');
      stripClose.css('transition', 'all 0.2s 0s cubic-bezier(0.23, 1, 0.32, 1)')
      expanded = false;
    }
  }

    var bindActions = function() {
      tileLink.on('click', open);
      stripClose.on('click', close);
    };

    var init = function() {
      bindActions();
    };

    return {
      init: init
    };

  }());

Expand.init();


// $(document).ready(function () {
    
//     $('.btn_select_role').on('click', function(){
//        var sub_id = $(this).attr('sub_id');
//        window.location.href = "<?=site_url('login/change_session_role_sub_menu');?>/"+sub_id;
//     });
// });


</script>
</html>