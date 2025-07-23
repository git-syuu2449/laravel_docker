// import './bootstrap';
import axios from 'axios'
import Cookies from 'js-cookie'

// window.Alpine = Alpine;

// Alpine.start();

axios.defaults.withCredentials = true
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

const xsrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
if (xsrfToken) {
  axios.defaults.headers.common['X-XSRF-TOKEN'] = xsrfToken
  axios.defaults.headers.common['X-CSRF-TOKEN'] = xsrfToken
}