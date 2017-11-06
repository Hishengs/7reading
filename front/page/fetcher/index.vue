<template>
	<div id="fetcher">
		<Tabs v-model="tab">
      <TabPane label="文章" name="essay">
      	<Form>
					<!-- <FormItem label="目标网址">
						<Input v-model="essay.info.url" placeholder="请输入目标网址"></Input>
					</FormItem> -->
					<FormItem label="作者">
						<Input v-model="essay.info.author" placeholder="请输入作者名称"></Input>
					</FormItem>
					<FormItem label="书集版本">
						<Select v-model="essay.info.bookSerie" long>
			        <Option v-for="item,index in ['book', 'book1', 'book2', 'book3', 'book4']" :value="item" :key="index">{{ item }}</Option>
				    </Select>
						<!-- <Input v-model="essay.info.bookSerie" placeholder="请输入书集版本"></Input> -->
					</FormItem>
					<FormItem label="书集序号">
						<Input v-model="essay.info.bookId" placeholder="请输入书集序号"></Input>
					</FormItem>
					<FormItem label="开始文章ID">
						<Input v-model="essay.info.startId" placeholder="请输入开始文章ID"></Input>
					</FormItem>
					<FormItem label="结束文章ID">
						<Input v-model="essay.info.endId" placeholder="请输入结束文章ID"></Input>
					</FormItem>
				</Form>
				<!-- 爬取结果 -->
				<p>爬取结果</p>
				<div class="result" v-html="essay.fetchResult"></div>
				<Button type="primary" @click="fetch" :disabled="essay.fetchBtn.disabled">{{ essay.fetchBtn.text }}</Button>
      </TabPane>
      <TabPane label="文集" name="corpus">
      	<Form>
					<!-- <FormItem label="目标网址">
						<Input v-model="corpus.info.url" placeholder="请输入目标网址"></Input>
					</FormItem> -->
					<FormItem label="作者">
						<Input v-model="corpus.info.author" placeholder="请输入作者名称"></Input>
					</FormItem>
					<FormItem label="书集版本">
						<Select v-model="corpus.info.bookSerie" long>
			        <Option v-for="item,index in ['book', 'book1', 'book2', 'book3']" :value="item" :key="index">{{ item }}</Option>
				    </Select>
					</FormItem>
					<FormItem label="书集序号">
						<Input v-model="corpus.info.bookId" placeholder="请输入书集序号"></Input>
					</FormItem>
					<FormItem label="开始文章ID">
						<Input v-model="corpus.info.startId" placeholder="请输入开始文章ID"></Input>
					</FormItem>
					<FormItem label="结束文章ID">
						<Input v-model="corpus.info.endId" placeholder="请输入结束文章ID"></Input>
					</FormItem>
				</Form>
				<!-- 爬取结果 -->
				<p>爬取结果</p>
				<div class="result" v-html="corpus.fetchResult"></div>
				<Button type="primary" @click="fetch" :disabled="corpus.fetchBtn.disabled">{{ corpus.fetchBtn.text }}</Button>
      </TabPane>
    </Tabs>
	</div>
</template>

<script>
	export default {
		name: 'fetcher',
		data (){
			return {
				tab: 'essay',
				essay: {
					info: {
						url: '',
						author: '',
						bookSerie: 'book',
						bookId: null,
						startId: null,
						endId: null,
					},
					fetchBtn: {
						text: '开始爬取',
						disabled: false,
					},
					fetchResult: '',
				},
				corpus: {
					info: {
						url: '',
						author: '',
						bookSerie: 'book',
						bookId: null,
						startId: null,
						endId: null,
					},
					fetchBtn: {
						text: '开始爬取',
						disabled: false,
					},
					fetchResult: '',
				},
			};
		},
		methods: {
			fetch (){
				console.log(this.essay.info);
				this.essay.fetchResult = '';
				this.essay.fetchBtn = {
					text: '爬取中...',
					disabled: true,
				};
				this.api.essay.fetch(this.essay.info).then(res => {
					this.essay.fetchBtn = {
						text: '开始爬取',
						disabled: false,
					};
					console.log('>>> 文章爬取', res);
					if(res.data.err.level < 3){
						this.essay.fetchResult = res.data.data || '';
					}
				}).catch(err => {
					this.essay.fetchBtn = {
						text: '开始爬取',
						disabled: false,
					};
				});
			},
		}
	}
</script>

<style lang="less" scoped>
	.result {
		height: 300px;
		overflow-y: auto;
		border: 1px solid #efefef;
		border-radius: 3px;
		background-color: #343434;
		color: #f2f2f2;
		padding: 10px;
	}
	p, .result {
		margin-bottom: 15px;
	}
</style>