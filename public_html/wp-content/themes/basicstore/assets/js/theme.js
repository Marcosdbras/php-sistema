/**
 * Main Theme JavaScript File
 * Theme.al
 */

jQuery(document).ready(function( $ ) {

  // - initialize Bootstrap tooltips and popovers
  $('[data-toggle="tooltip"]').tooltip();
  $('[data-toggle="popover"]').popover();

});


// Make Select2 script (required by WooCommerce) load with bootstrap styling
if (jQuery.fn.select2) {
  jQuery.fn.select2.defaults.set( "theme", "bootstrap" );
}


// My account template make bootstrap responsive tabs
jQuery.fn.responsiveTabs = function() {
  this.addClass('responsive-tabs');
  this.append(jQuery('<span class="glyphicon glyphicon-triangle-bottom"></span>'));
  this.append(jQuery('<span class="glyphicon glyphicon-triangle-top"></span>'));

  this.on('click', 'li.active > a, span.glyphicon', function() {
    this.toggleClass('open');
  }.bind(this));

  this.on('click', 'li:not(.active) > a', function() {
    this.removeClass('open');
  }.bind(this));
};

jQuery('.nav.nav-tabs').responsiveTabs();


// Responsive tabs with tabcollapse jQuery plugin
// used on product-single tabs template
jQuery('#wc-product-tabs').tabCollapse({
    tabsClass: 'hidden-xs hidden-sm',
    accordionClass: 'visible-xs visible-sm'
});
