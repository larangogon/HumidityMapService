window.Vue = require('vue').default;
Vue.component('form-component', require('./components/FormComponent').default);

new Vue({
    el: '#app'
})
