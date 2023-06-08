window.Vue = require('vue').default;
Vue.component('form-component', require('./components/FormComponent.vue').default);

new Vue({
    el: '#app'
})
