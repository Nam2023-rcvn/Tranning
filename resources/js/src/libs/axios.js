import Vue from 'vue'

// axios
import axios from 'axios'

const axiosIns = axios.create({
  // You can add your headers here
  // ================================
  // baseURL: 'https://some-domain.com/api/',
  // timeout: 1000,
  // headers: {'X-Custom-Header': 'foobar'}
  // baseURL: 'http://rcvn.local/api'
  baseURL: 'http://192.168.88.92/api'
})

Vue.prototype.$http = axiosIns

export default axiosIns
