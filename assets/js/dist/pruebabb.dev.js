"use strict";

fetch('./data.json').then(function (response) {
  return response.json();
}).then(function (json) {
  return console.log(json);
});