
    var app = new Vue({
        el: '#app',
        data: {
            "color": Cookies.get("color")||"grey",
            "tone": Cookies.get("tone")||"darken-2",
            "font": Cookies.get("font")||"bg-text lighten-1",
            "headfont": Cookies.get("headfont")||"bg-text text-lighten-2",
        },
        watch: {
            'color': function(val, oldVal) {
              Cookies.set("color",val);
            },
            'tone': function(val, oldVal) {
              Cookies.set("tone",val);
            },
            'font': function(val, oldVal) {
              Cookies.set("font",val);
            },
            'headfont': function(val, oldVal) {
              Cookies.set("headfont",val);
            }
        }
    });
$(document).ready(function() {

    $('.parallax').parallax();

    $(".button-collapse").sideNav();


})
