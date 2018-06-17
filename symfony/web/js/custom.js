!function (e) {
    "use strict";
    e(".menu-toggle").click(function (a) {
        a.preventDefault(), e("#sidebar-wrapper").toggleClass("active"), e(".menu-toggle > .fa-bars, .menu-toggle > .fa-times").toggleClass("fa-bars fa-times"), e(this).toggleClass("active")
    }), e('a.js-scroll-trigger[href*="#"]:not([href="#"])').click(function () {
        if (location.pathname.replace(/^\//, "") == this.pathname.replace(/^\//, "") && location.hostname == this.hostname) {
            var a = e(this.hash);
            if ((a = a.length ? a : e("[name=" + this.hash.slice(1) + "]")).length) return e("html, body").animate({scrollTop: a.offset().top}, 1e3, "easeInOutExpo"), !1
        }
    }), e("#sidebar-wrapper .js-scroll-trigger").click(function () {
        e("#sidebar-wrapper").removeClass("active"), e(".menu-toggle").removeClass("active"), e(".menu-toggle > .fa-bars, .menu-toggle > .fa-times").toggleClass("fa-bars fa-times")
    }), e(document).scroll(function () {
        e(this).scrollTop() > 100 ? e(".scroll-to-top").fadeIn() : e(".scroll-to-top").fadeOut()
    })
}(jQuery);
var onMapMouseleaveHandler = function (e) {
    var a = $(this);
    a.on("click", onMapClickHandler), a.off("mouseleave", onMapMouseleaveHandler), a.find("iframe").css("pointer-events", "none")
}, onMapClickHandler = function (e) {
    var a = $(this);
    a.off("click", onMapClickHandler), a.find("iframe").css("pointer-events", "auto"), a.on("mouseleave", onMapMouseleaveHandler)
};
$(".map").on("click", onMapClickHandler);



/*----------Loader---------------*/

function loaderOff() {
    document.getElementById("overlay").style.display = "none";
}
function loaderOn() {
    document.getElementById("overlay").style.display = "block";
}

/*------------------------------*/



/*----------Velocity.js---------------*/
$( ".modal" ).each(function(index) {
    $(this).on('show.bs.modal', function (e) {
        var open = $(this).attr('data-easein');
        if(open == 'shake') {
            $('.modal-dialog').velocity('callout.' + open);
        } else if(open == 'pulse') {
            $('.modal-dialog').velocity('callout.' + open);
        } else if(open == 'tada') {
            $('.modal-dialog').velocity('callout.' + open);
        } else if(open == 'flash') {
            $('.modal-dialog').velocity('callout.' + open);
        }  else if(open == 'bounce') {
            $('.modal-dialog').velocity('callout.' + open);
        } else if(open == 'swing') {
            $('.modal-dialog').velocity('callout.' + open);
        }else {
            $('.modal-dialog').velocity('transition.' + open);
        }

    });
});
/*------------------------------*/


/*----------Enable bootstrapt tooltips---------------*/
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
})
/*------------------------------*/