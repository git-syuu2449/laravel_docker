// import './bootstrap';
import axios from 'axios'
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

axios.defaults.withCredentials = true
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'