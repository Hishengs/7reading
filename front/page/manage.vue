<template>
	<div id="manage">
		<top-menu></top-menu>
		<Menu id="left-menu" theme="light" :active-name="$route.path" width="150px" @on-select="onMenuSelect">
      <MenuItem name="/manage/essay">文章管理</MenuItem>
      <MenuItem name="/manage/corpus">文集管理</MenuItem>
      <MenuItem name="/manage/fetcher">自动爬取</MenuItem>
    </Menu>
    <div id="content">
    	<keep-alive>
	      <router-view></router-view>
	    </keep-alive>
    </div>
	</div>
</template>

<script>
	import topMenu from './top-menu.vue';
	export default {
		name: 'manage',
		components: {
			topMenu,
		},
		data (){
			return {
				menu: '/manage/essay'
			}
		},
		methods: {
			onMenuSelect (name){
				this.$router.push(name);
			}
		}
	}
</script>

<style lang="less">
	#manage {
		height: 100%;
		position: relative;
		#left-menu {
			position: absolute;
			top: 60px;
			left: 0;
			bottom: 0;
		}
		#content {
			height: 100%;
			width: 100%;
			margin-top: -60px;
			padding: 80px 20px 20px 170px;
		}
	}
</style>