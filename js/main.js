function main(){

    (function () {
        'use strict';
        $('a.page-scroll').click(function() {
            if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
              var target = $(this.hash);
              target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
              if (target.length) {
                $('html,body').animate({
                  scrollTop: target.offset().top - 50
                }, 900);
                return false;
              }
            }
          });
    
        

          $(".navbar-nav li:not(.dropdown) a").click(function (event) {
          var toggle = $(".navbar-toggle").is(":visible");
          if (toggle) {
              $(".navbar-collapse").collapse('hide');
          }
        });
      });
}

main();