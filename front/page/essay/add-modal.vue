<!-- 文章新增弹窗 -->
<template>
	<div>
		<!-- 文章新增 -->
		<Modal
		class="essay-add-modal"
		v-model="show"
		title="添加文章"
		width="800"
		scrollable
		:closable="false"
		:mask-closable="false"
		>
			<Input v-model="essay.title" name="title" placeholder="文章标题" long></Input>
			<Input v-model="essay.author" name="author" placeholder="文章作者" long></Input>
			<Select v-model="essay.type" long>
        <Option v-for="item,index in types" :value="index" :key="index">{{ item }}</Option>
	    </Select>
			<div class="content" id="add-essay-editor">
				<textarea style="display:none;"></textarea>
			</div>
			<div slot="footer">
				<Button type="text" @click="cancel">取消</Button>
				<Button type="primary" @click="save">保存</Button>
			</div>
		</Modal>
	</div>
</template>

<script>
	export default {
		name: 'essay-add-modal',
		props: {
			show: {
				type: Boolean,
				default: false,
			},
		},
		data (){
			return {
				essay: {
					title: '',
					author: '',
					content: '',
					type: 0, // 文章类型
				},
				editor: null,
				types: ['未定义', '散文', '短篇小说', '古代诗词', '现代诗歌'],
			};
		},
		watch: {
			show (val){
				console.log('>>> show editor: ', val);
				if(val){
					if(!this.editor){
						this.initEditor();
						setTimeout(() => {
							this.editor.setMarkdown(this.essay.content);
						}, 100);
					}
				}
			}
		},
		methods: {
			// 初始化编辑器
			initEditor (){
				window.editor = this.editor = editormd('add-essay-editor', {
					path: 'http://localhost:85/js/editor.md-master/lib/',
					height: 500,
					placeholder: '输入文章内容',
					watch: false
				});
			},
			cancel (){
				this.$emit('update:show', false);
			},
			save (){
				// 检查文章
				this.essay.content = this.editor.getMarkdown();
				this.api.essay.add(this.essay).then(res => {
					if(res.data.err.level < 3){
						this.$Message.success('文章添加成功');
						this.essay = {
							title: '',
							author: '',
							content: '',
						};
						this.editor.setValue('');
					}else this.$Message.error('文章添加失败: ' + res.data.err.msg);
				}).catch(err => {});
				// this.$emit('update:show', false);
			},
		}
	}
</script>

<style lang="less">
	.essay-add-modal {
		.ivu-modal {
			.ivu-input, .ivu-select {
				margin-bottom: 15px;
			}
		}
	}
</style>