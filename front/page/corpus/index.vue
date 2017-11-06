<template>
	<div id="corpus-manage">
		<div class="action-bar">
			<!-- 文集搜索 -->
			<Select
				class="search-input"
        filterable
        remote
        clearable
        :remote-method="onSearch"
        :loading="search.searching"
        @on-change="onCorpusSelect"
        ref="search"
        style="width: 400px;"
        placeholder="搜索文集"
        >
        <Option v-for="(option, index) in search.results" :value="index" :key="index">{{ option.title }}</Option>
      </Select>
      <span class="right">
      	<Button type="primary" @click="showAddModal=true">添加文集</Button>
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
		<!-- 文集新增 -->
		<Modal v-model="showAddModal" title="添加文集">
			<Form>
				<FormItem label="文集名称">
					<Input v-model="newCorpus.title" placeholder="文集名称"></Input>
				</FormItem>
				<FormItem label="文集作者">
					<Input v-model="newCorpus.author" placeholder="文集作者"></Input>
				</FormItem>
				<FormItem label="出版时间（首次）">
					<Input v-model="newCorpus.publish_date" placeholder="出版时间（首次）"></Input>
					<!-- <DatePicker type="date" placeholder="出版时间" v-model="newCorpus.publish_date" style="display: block;"></DatePicker> -->
				</FormItem>
				<FormItem label="文集简介">
					<Input v-model="newCorpus.intro" placeholder="文集简介" type="textarea" :rows="5"></Input>
				</FormItem>
			</Form>
			<div slot="footer">
				<Button type="text" @click="showAddModal=false;newCorpusName='';">取消</Button>
				<Button type="primary" @click="addCorpus">保存</Button>
			</div>
		</Modal>
		<!-- 文集编辑 -->
		<Modal v-model="showEditModal" title="文集编辑">
			<Form>
				<FormItem label="文集名称">
					<Input v-model="editingCorpus.title" placeholder="文集名称"></Input>
				</FormItem>
				<FormItem label="文集作者">
					<Input v-model="editingCorpus.author" placeholder="文集作者"></Input>
				</FormItem>
				<FormItem label="出版时间（首次）">
					<Input v-model="editingCorpus.publish_date" placeholder="出版时间（首次）"></Input>
					<!-- <DatePicker type="date" placeholder="出版时间" v-model="editingCorpus.publish_date" style="display: block;"></DatePicker> -->
				</FormItem>
				<FormItem label="文集简介">
					<Input v-model="editingCorpus.intro" placeholder="文集简介" type="textarea" :rows="5"></Input>
				</FormItem>
			</Form>
			<div slot="footer">
				<Button type="text" @click="showEditModal=false;">取消</Button>
				<Button type="primary" @click="saveCorpus">保存</Button>
			</div>
		</Modal>
	</div>
</template>

<script>
	import _cloneDeep from 'lodash/cloneDeep';
	export default {
		name: 'corpus-manage',
		data (){
			return {
				originalList: [],
				table: {
					columns: [
						{ title: 'ID', key: 'id' },
						{ title: '文集名称', key: 'title' },
						{ title: '作者', key: 'author' },
						{ title: '出版时间', key: 'publish_date' },
						{ title: '创建时间', key: 'created_at' },
						{ title: '更新时间', key: 'updated_at' },
						{
							title: '操作', 
							width: 250,
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
												this.showEditModal = true;
												this.editingCorpus = Object.assign({}, this.table.data[params.index]);
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
									}, '添加文章'),
									h('Button', {
										props: {
											type: 'ghost',
											size: 'small'
										},
										on: {
											click: () => {
												this.$Modal.confirm({
													title: '删除文集',
													content: '你确定要删除文集：《' + params.row.title + '》？',
													onOk: () => {
														this.api.corpus.del(params.row.id).then(res => {
															if(res.data.err.level < 3){
																this.$Message.success('文集删除成功');
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
					data: [],
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
				showEditModal: false, // 编辑弹窗
				editingCorpus: {},
				newCorpus: {
					title: '',
					author: '',
					publish_date: '', // 出版时间
					intro: ''
				},
				showAddModal: false, // 显示新增弹窗
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
				this.api.corpus.query(keyword).then(res => {
					this.search.searching = false;
					console.log('>>> 查询文集', res);
					if(res.data.err.level < 3){
						this.search.results = res.data.data || [];
					}
				}).catch(err => {
					this.search.searching = false;
				});
			},
			onCorpusSelect (index){
				console.log('>>> onEssaySelect', index);
				this.table.data = [this.search.results[index]];
			},
			// 获取文集列表
			getList (){
				this.api.corpus.getList(this.paginator.currentPage, this.paginator.numPerPage).then(res => {
					console.log('>>> 获取文集列表', res);
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
			// 添加文集
			addCorpus (){
				if(this.newCorpus.title.trim() && this.newCorpus.author.trim()){
					this.api.corpus.add(this.newCorpus).then(res => {
						console.log('>>> 添加文集', res);
						if(res.data.err.level < 3){
							this.$Message.success('添加成功');
							this.showAddModal = false;
							this.newCorpus = {
								title: '',
								author: '',
								publish_date: '',
								intro: ''
							};
							this.getList();
						}else this.$Message.error('文集添加失败: ' + res.data.err.msg);
					}).catch(err => {});
				}
			},
			// 保存文集
			saveCorpus (){
				if(this.editingCorpus.title.trim() && this.editingCorpus.author.trim()){
					this.api.corpus.save(this.editingCorpus).then(res => {
						console.log('>>> 保存文集', res);
						if(res.data.err.level < 3){
							this.$Message.success('保存成功');
							this.showEditModal = false;
							this.getList();
						}else this.$Message.error('文集保存失败: ' + res.data.err.msg);
					}).catch(err => {});
				}
			},
		}
	}
</script>

<style lang="less">
	#corpus-manage {
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