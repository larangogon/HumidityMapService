import Vue from 'vue';
import FormComponent from './components/FormComponent';

require('./bootstrap');

Vue.component('form-component', FormComponent);

new Vue({
    el: '#app'
});
