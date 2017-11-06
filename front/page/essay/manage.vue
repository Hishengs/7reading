<template>
	<div id="essay-manage">
		<div class="action-bar">
			<!-- 文章搜索 -->
			<Select
				class="search-input"
        filterable
        remote
        clearable
        :remote-method="onSearch"
        :loading="search.searching"
        @on-change="onEssaySelect"
        ref="search"
        style="width: 400px;"
        placeholder="搜索文章"
        >
        <Option v-for="(option, index) in search.results" :value="index" :key="index">{{ option.title }}</Option>
      </Select>
      <span class="right">
      	<Button type="primary" @click="showEssayAddModal=true">添加文章</Button>
      	<!-- <Button type="error">删除已选</Button> -->
      </span>
		</div>
		<Table :columns="table.columns" :data="table.data"></Table>
		<div class="table-action-bar">
			<Page 
			show-total
			:current="paginator.currentPage" 
			:total="paginator.total" 
			:page-size="paginator.numPerPage" 
			@on-change="onPageSelect"></Page>
		</div>
		<!-- 文章查看 -->
		<Modal
		class="essay-view-modal"
		v-model="showEssayViewer"
		:title="'《' + currentEssay.title + '》' + ' - ' + currentEssay.author"
		width="800"
		scrollable
		>
			<div class="content" :style="{maxHeight: '600px', overflowY: 'auto', fontSize: '14px', lineHeight: '1.6'}" v-html="currentEssay.html">
			</div>
		</Modal>
		<!-- 文章编辑 -->
		<essay-edit-modal :show.sync="showEssayEditModal" :essay="currentEssay" @on-save="getList"></essay-edit-modal>
		<!-- 文章新增 -->
		<essay-add-modal :show.sync="showEssayAddModal"></essay-add-modal>
	</div>
</template>

<script>
	import essayEditModal from './edit-modal.vue';
	import essayAddModal from './add-modal.vue';
	import _cloneDeep from 'lodash/cloneDeep';
	export default {
		name: 'essay-manage',
		components: {
			essayEditModal,
			essayAddModal,
		},
		data (){
			return {
				originalList: [],
				table: {
					columns: [
						{ title: 'ID', key: 'id' },
						{
							title: '标题', key: 'title',
							render: (h, params) => {
								return h('Button', {
									props: {
										type: 'text',
										size: 'small'
									},
									style: {
										marginLeft: '-8px'
									},
									on: {
										click: () => {
											this.showEssayViewer = true;
											this.currentEssay = this.table.data[params.index];
											this.currentEssay.html = marked(this.currentEssay.content);
										}
									}
								}, params.row.title);
							}
						},
						{ title: '作者', key: 'author' },
						{ title: '创建时间', key: 'created_at' },
						{ title: '更新时间', key: 'updated_at' },
						{
							title: '操作', 
							width: 300,
							key: 'action',
							render: (h, params) => {
								return h('div', {}, [
									h('Button', {
										props: {
											type: 'ghost',
											size: 'small'
										},
										style: {
											marginRight: '5px'
										},
										on: {
											click: () => {
												this.showEssayViewer = true;
												this.currentEssay = this.table.data[params.index];
												this.currentEssay.html = marked(this.currentEssay.content);
											}
										}
									}, '查看'),
									h('Button', {
										props: {
											type: 'ghost',
											size: 'small'
										},
										style: {
											marginRight: '5px'
										},
										on: {
											click: () => {
												this.showEssayEditModal = true;
												this.currentEssay = this.table.data[params.index];
												console.log('>>> 文章编辑', this.currentEssay);
											}
										}
									}, '编辑'),
									h('Button', {
										props: {
											type: 'ghost',
											size: 'small'
										},
										style: {
											marginRight: '5px'
										},
										on: {
											click: () => {
												//
											}
										}
									}, '加入到文集'),
									h('Button', {
										props: {
											type: 'ghost',
											size: 'small'
										},
										on: {
											click: () => {
												this.$Modal.confirm({
													title: '删除文章',
													content: '你确定要删除文章：《' + params.row.title + '》？',
													onOk: () => {
														this.api.essay.del(params.row.id).then(res => {
															if(res.data.err.level < 3){
																this.$Message.success('文章删除成功');
																this.getList();
															}
														}).catch(err => {});
													}
												});
											}
										}
									}, '删除'),
								]);
							}
						},
					],
					data: [
						{ title: '跑警报', author: '汪曾祺', content: '跑警报,跑警报,跑警报' },
					],
				},
				search: {
					// key: '',
					searching: false,
					lastTime: new Date().getTime(),
					queryInterval: 1500, // 查询间隔 1.5s
					results: []
				},
				// 分页
				paginator: {
					currentPage: 1,
					numPerPage: 10,
					total: 0,
				},
				showEssayViewer: false,
				currentEssay: {},
				showEssayEditModal: false,
				showEssayAddModal: false,
			}
		},
		activated (){
			this.getList();
		},
		methods: {
			onSearch (keyword){
				if(!keyword.trim()){
					this.table.data = _cloneDeep(this.originalList);
					return;
				}
				if(new Date().getTime() - this.search.lastTime < this.search.queryInterval)return;
				this.search.lastTime = new Date().getTime();
				this.search.searching = true;
				this.api.essay.query(keyword).then(res => {
					this.search.searching = false;
					console.log('>>> 查询文章', res);
					if(res.data.err.level < 3){
						this.search.results = res.data.data || [];
					}
				}).catch(err => {
					this.search.searching = false;
				});
			},
			onEssaySelect (index){
				console.log('>>> onEssaySelect', index);
				this.table.data = [this.search.results[index]];
			},
			// 获取文章列表
			getList (){
				// return;
				this.api.essay.getList(this.paginator.currentPage, this.paginator.numPerPage).then(res => {
					console.log('>>> 获取文章列表', res);
					if(res.data.err.level < 3){
						this.table.data = res.data.data.list || [];
						this.originalList = _cloneDeep(this.table.data);
						this.paginator.total = res.data.data.total;
						this.$refs.search.setQuery('');
					}
				}).catch(err => {});
			},
			onPageSelect (page){
				this.paginator.currentPage = page;
				this.getList();
			},
		}
	}
</script>

<style lang="less">
	#essay-manage {
		.action-bar {
			padding: 5px 0;
			border-bottom: 1px solid #efefef;
			margin-bottom: 20px;
			.search-input {
				display: inline-block; 
				width: 200px;
				margin-right: 5px;
			}
			.right {
				float: right;
			}
		}
		.table-action-bar {
			margin-top: 10px;
		}
	}
</style>