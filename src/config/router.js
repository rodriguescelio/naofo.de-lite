import Vue from 'vue'
import Router from 'vue-router'
import Home from '@/components/Home'
import Confirm from '@/components/Confirm'
import Viewer from '@/components/Viewer'

Vue.use(Router)

export default new Router({
	routes: [
		{
			path: '/',
			name: 'Home',
			component: Home
		}, {
			path: '/confirm/:url',
			name: 'Confirm',
			component: Confirm,
			props: true
		}, {
			path: '/:hash',
			name: 'Viewer',
			component: Viewer,
			props: true
		}
	]
})
