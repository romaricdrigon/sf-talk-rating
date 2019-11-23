/*
    Frontend app (comment form)
 */

// CSS
require('../sass/app.scss');

// Dependencies
const $ = require('jquery');
require('jquery-bar-rating');
// We don't use any JS from Bootstrap

// Init star rating
$('.star-rating').barrating({
    theme: 'css-stars'
});
