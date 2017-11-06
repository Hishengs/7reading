import config from './config.js';

module.exports = {
	// 获取文章列表
	getList (currentPage, numPerPage){
		return new Promise((resolve, reject) => {
			config.http.post(config.urlPrefix + '/essay/list', {
				currentPage,
				numPerPage,
			}).then(res => {
				resolve(res);
			}).catch(reject);
		});
	},
	// 新增文章
	add (essay){
		return new Promise((resolve, reject) => {
			config.http.post(config.urlPrefix + '/essay/add', essay).then(res => {
				resolve(res);
			}).catch(reject);
		});
	},
	// 保存文章
	save (essay){
		return new Promise((resolve, reject) => {
			config.http.post(config.urlPrefix + '/essay/save', essay).then(res => {
				resolve(res);
			}).catch(reject);
		});
	},
	// 删除文章
	del (id){
		return new Promise((resolve, reject) => {
			config.http.post(config.urlPrefix + '/essay/delete', {
				id,
			}).then(res => {
				resolve(res);
			}).catch(reject);
		});
	},
	// 查询文章
	query (keyword){
		console.log(keyword);
		return new Promise((resolve, reject) => {
			config.http.post(config.urlPrefix + '/essay/query', {
				keyword,
			}).then(res => {
				resolve(res);
			}).catch(reject);
		});
	},
	// 爬取指定文章
	fetch (opts){
		return new Promise((resolve, reject) => {
			config.http.post(config.urlPrefix + '/essay/fetch', opts).then(res => {
				resolve(res);
			}).catch(reject);
		});
	},
};