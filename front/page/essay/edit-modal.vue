<!-- 文章编辑弹窗 -->
<template>
	<div>
		<!-- 文章编辑 -->
		<Modal
		 class="essay-edit-modal"
		v-model="show"
		:title="'文章编辑《' + essay.title + '》' + ' - ' + essay.author"
		width="800"
		scrollable
		:closable="false"
		:mask-closable="false"
		>
			<Input v-model="editedEssay.title" name="title" placeholder="文章标题" long></Input>
			<Input v-model="editedEssay.author" name="author" placeholder="文章作者" long></Input>
			<Select v-model="editedEssay.type" long>
        <Option v-for="item,index in types" :value="index" :key="index">{{ item }}</Option>
	    </Select>
			<div class="content" id="edit-essay-editor">
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
	import _cloneDeep from 'lodash/cloneDeep';
	export default {
		name: 'essay-edit-modal',
		props: {
			show: {
				type: Boolean,
				default: false,
			},
			essay: {
				type: Object,
				default: () => {
					return {};
				}
			},
		},
		data (){
			return {
				editedEssay: {},
				editor: null,
				types: ['未定义', '散文', '短篇小说', '古代诗词', '现代诗歌'],
			};
		},
		watch: {
			show (val){
				console.log('>>> show editor: ', val);
				if(val){
					this.editedEssay = _cloneDeep(this.essay);
					if(!this.editor){
						this.initEditor();
						setTimeout(() => {
							this.editor.setMarkdown(this.editedEssay.content);
						}, 100);
					}else this.$nextTick(() => {
						this.editor.setMarkdown(this.editedEssay.content);
					});
				}
			}
		},
		methods: {
			// 初始化编辑器
			initEditor (){
				window.editor = this.editor = editormd('edit-essay-editor', {
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
				this.editedEssay.content = this.editor.getMarkdown();
				this.api.essay.save(this.editedEssay).then(res => {
					if(res.data.err.level < 3){
						this.$Message.success('文章保存成功');
						this.$emit('on-save');
						this.$emit('update:show', false);
					}else this.$Message.error('文章添加失败: ' + res.data.err.msg);
				}).catch(err => {});
				// this.$emit('update:show', false);
			},
		}
	}
</script>

<style lang="less">
	.essay-edit-modal {
		.ivu-modal {
			.ivu-input, .ivu-select {
				margin-bottom: 15px;
			}
		}
	}
</style>