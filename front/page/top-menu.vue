<template>
	<div id="top-menu">
		<Menu theme="light" :active-name="menu" mode="horizontal">
      <MenuItem name="7reading" class="site-name" @click.native="$router.push('/')"><Icon type="leaf" size="22"></Icon>七读</MenuItem>
    </Menu> 
	</div>
</template>

<script>
	export default {
		name: 'top-menu',
		data (){
			return {
				menu: '7reading'
			}
		}
	}
</script>

<style lang="less" scoped>
	.site-name {
		font-size: 18px;
		&.ivu-menu-item-selected {
			border-bottom: none;
		}
	}
</style>