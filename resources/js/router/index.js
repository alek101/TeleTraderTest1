import Vue from 'vue'
import VueRouter from 'vue-router'
import HomePage from '../components/HomePage.vue'
import Favorites from '../components/Favorites.vue'
import Details from '../components/Details.vue'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'HomePage',
    component: HomePage
  },
  {
    path: '/favorites',
    name: 'Favorites',
    component: Favorites,
    beforeEnter: async (to, from, next) => {
      const res = await fetch('/api/status');
      const resr = await res.json();
      if(resr.isLoged) next();
      else router.go('/');
    }
  },
  {
    path: '/details/:pair',
    name: 'Details',
    component: Details,
    props: true,
  },
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
