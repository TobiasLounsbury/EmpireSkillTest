import Routes from '@/js/frontend/routes.js';
import App from '@/js/frontend/views/App';
import Select2 from 'v-select2-component';

Vue.component('tic-tac-toe', require('./components/TicTacToe.vue').default);
Vue.component('Select2', Select2);

const spa = new Vue({
    el: '#spa',
    router: Routes,
    render: h => h(App)
});

export default spa;
