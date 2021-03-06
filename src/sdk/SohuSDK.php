<?php
namespace OauthSDK\sdk;

use OauthSDK\Oauth;

/**
 * Class SohuSDK
 * @package OauthSDK\sdk
 */
class SohuSDK extends Oauth{
	/**
	 * 获取requestCode的api接口
	 * @var string
	 */
	protected $getRequestCodeURL = 'https://api.sohu.com/oauth2/authorize';

	/**
	 * 获取access_token的api接口
	 * @var string
	 */
	protected $getAccessTokenURL = 'https://api.sohu.com/oauth2/token';

	/**
	 * API根路径
	 * @var string
	 */
	protected $apiBase = 'https://api.sohu.com/rest/';
	
	/**
	 * 组装接口调用参数 并调用接口
	 * @param  string $api    搜狐API
	 * @param  string $param  调用API的额外参数
	 * @param  string $method HTTP请求方法 默认为GET
	 * @param bool $multi
	 * @return json
	 * @throws \Exception
	 */
	public function call($api, $param = '', $method = 'GET', $multi = false){		
		/* 搜狐调用公共参数 */
		$params = array(
			'access_token' => $this->token['access_token'],
		);

		$data = $this->http($this->url($api), $this->param($params, $param), $method);
		return json_decode($data, true);
	}
	
	/**
	 * 解析access_token方法请求后的返回值
	 * @param $result 获取access_token的方法的返回值
	 * @param $extend
	 * @return mixed
	 * @throws \Exception
	 */
	protected function parseToken($result, $extend){
		$data = json_decode($result, true);
		if($data['access_token'] && $data['expires_in'] && $data['refresh_token'] && $data['open_id']){
			$data['openid'] = $data['open_id'];
			unset($data['open_id']);
			return $data;
		} else
			throw new \Exception("获取搜狐ACCESS_TOKEN出错：{$data['error']}");
	}

	/**
	 * 获取当前授权应用的openid
	 * @return string
	 * @throws \Exception
	 */
	public function openid(){
		$data = $this->token;
		if(isset($data['openid']))
			return $data['openid'];
		else
			throw new \Exception('没有获取到搜狐用户ID！');
	}
	
}