import Vue from 'vue'
import useJwt from '@/auth/jwt/useJwt'
import router from '@/router'

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

axiosIns.interceptors.response.use(
  response => {
    console.log('reponse')
    return response
  },
  error => {
    console.log('error')
    if (error.response && error.response.status === 401) {

      useJwt.deleteToken()

      router.push('/user-login').catch(() => { })
    }
    return Promise.reject(error)
  })

Vue.prototype.$http = axiosIns

export default axiosIns
