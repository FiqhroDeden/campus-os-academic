jQuery(document).ready(function($) {

$('.topmenu-toggle').click(function(){
    $('.top-bar .nav-right .top-menu').slideToggle();
});

$('.language-toggle .language-btn').click(function(){
    $('.language-toggle .list-language').slideToggle();
});

var siteHeader = $('.site-header');

$(window).scroll(function() {
var scrollTop = $(this).scrollTop();
if (scrollTop > 120) {
    siteHeader.addClass('fixed');
    if(scrollTop > 130){
        siteHeader.addClass('sticky');
    }else if(siteHeader.hasClass('sticky')){
        siteHeader.removeClass('sticky');
    }

    if(scrollTop > 160){
        siteHeader.css('top', 0)
    }else{
        siteHeader.css('top', '-60px');
    }
}else{
    if(siteHeader.hasClass('fixed')){
        siteHeader.removeClass('fixed').css('top', 'auto');
    }
}
});

$('.main-navigation ul.primary-menu').smartmenus({
    mainMenuSubOffsetX: -1,
    mainMenuSubOffsetY: 4,
    subMenusSubOffsetX: 6,
    subMenusSubOffsetY: -6
});

$('.search-toggle').click(function(){
    $('.search-wrapper').addClass('show');
    setTimeout(function(){
    $('.search-input').focus();
    }, 1000);
});

$('.search-wrapper').click(function(){
    $('.search-wrapper').removeClass('show');
});

$('.search-wrapper .search-content').click(function(e){
    e.stopPropagation();
});

$('.primarymenu-toggle').click(function(){
  $('.main-navigation > div').addClass('show');
});

$('.main-navigation > div').click(function(){
  $('.main-navigation > div').removeClass('show');
});

$('.main-navigation > div > ul.primary-menu').click(function(e){
  e.stopPropagation();
});

$('.scroll-to-top').click(function (e) {
    e.preventDefault();
    $('html, body').animate({scrollTop: 0}, 1000);
});

});