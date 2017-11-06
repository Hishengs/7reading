import config from './config.js';

module.exports = {
	// 获取文集列表
	getList (currentPage, numPerPage){
		return new Promise((resolve, reject) => {
			config.http.post(config.urlPrefix + '/corpus/list', {
				currentPage,
				numPerPage,
			}).then(res => {
				resolve(res);
			}).catch(reject);
		});
	},
	// 新增文集
	add (corpus){
		return new Promise((resolve, reject) => {
			config.http.post(config.urlPrefix + '/corpus/add', corpus).then(res => {
				resolve(res);
			}).catch(reject);
		});
	},
	// 保存文集
	save (corpus){
		return new Promise((resolve, reject) => {
			config.http.post(config.urlPrefix + '/corpus/save', corpus).then(res => {
				resolve(res);
			}).catch(reject);
		});
	},
	// 删除文集
	del (id){
		return new Promise((resolve, reject) => {
			config.http.post(config.urlPrefix + '/corpus/delete', {
				id,
			}).then(res => {
				resolve(res);
			}).catch(reject);
		});
	},
	// 爬取指定文集
	fetch (opts){
		return new Promise((resolve, reject) => {
			config.http.post(config.urlPrefix + '/corpus/fetch', opts).then(res => {
				resolve(res);
			}).catch(reject);
		});
	},
	// 查询文集
	query (keyword){
		console.log(keyword);
		return new Promise((resolve, reject) => {
			config.http.post(config.urlPrefix + '/corpus/query', {
				keyword,
			}).then(res => {
				resolve(res);
			}).catch(reject);
		});
	},
};