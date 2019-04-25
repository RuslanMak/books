
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i);
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default));

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('main-books-component', require('./components/MainBooksComponent.vue').default);
Vue.component('main-books-add-component', require('./components/MainBooksAddComponent.vue').default);
Vue.component('main-book-edit-delete-component', require('./components/MainBookEditDeleteComponent.vue').default);
Vue.component('main-writers-component', require('./components/MainWritersComponent.vue').default);
Vue.component('main-writer-add-component', require('./components/MainWriterAddComponent.vue').default);
Vue.component('main-writer-edit-delete-component', require('./components/MainWriterEditDeleteComponent.vue').default);
Vue.component('main-genres-component', require('./components/MainGenresComponent.vue').default);
Vue.component('main-genre-add-component', require('./components/MainGenreAddComponent.vue').default);
Vue.component('main-genre-edit-delete-component', require('./components/MainGengeEditDeleteComponent.vue').default);


const app = new Vue({
    el: '#app'
});
