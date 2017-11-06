import Vue from "vue";
import Router from "vue-router";
// pages
import manage from './page/manage.vue';
import login from './page/login.vue';
import register from './page/register.vue';
import essayManage from './page/essay/manage.vue';
import corpusManage from './page/corpus/index.vue';
import fetcher from './page/fetcher/index.vue';


Vue.use(Router);
const router = new Router({
  mode: 'hash',
  routes: [
    { path:'/', name: 'home', redirect: '/manage' }, // home
    { path:'/login', name: 'login', component: login }, // login
    { path:'/register', name: 'register', component: register }, // register
    {
    	path:'/manage', 
    	component: manage,
    	children: [
    		{ path:'', name: 'manageIndex', redirect: 'essay' },
    		{ path:'essay', name: 'essayManage', component: essayManage }, // 文章管理
    		{ path:'corpus', name: 'corpusManage', component: corpusManage }, // 文集管理
    		{ path:'fetcher', name: 'fetcher', component: fetcher }, // 文章爬取
    	]
    },
  ]
});

export default router;
