var nodeGeocoder=require('node-geocoder');
var options = {
    provider: 'google',
    /*httpAdapter: 'https',
    apiKey: 'AIzaSyDGzdhmURT5FridnEyq8torLTTsFRHcgAE',
    formatter: null*/
};

module.exports = nodeGeocoder(options);