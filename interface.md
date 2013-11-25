#接口说明

##certify.php

###参数
* userName : 用户名
* passwords : 密码

###返回
	{
		certified : true,
		userNickName : "用户昵称",
		userID : "用户ID"
	}

===

##querySource.php
###参数
userName : 用户名
passwords : 密码
lonFrom : 经度左
lonTo : 经度右
latFrom : 纬度下
latTo : 纬度上
dateFrom : 开始时间，格式"2001-1-1"
dateTo : 结束时间
###返回
{
	certified : true,
	sourceList : [
		{
			type : "source",
			id : 0,
			lat0 : -5,
			lat1 : 5,
			lon0 : 30,
			lon1 : 40,
			time : "2001-01-01 23:50"
			fileName : "x.tif",
			url : "/data/....",
			thumbUrl : "/data/...",
		},
		...
	]

}


##queryProduct.php
###参数
userName : 用户名
passwords : 密码
lonFrom : 经度左
lonTo : 经度右
latFrom : 纬度下
latTo : 纬度上
dateFrom : 开始时间，格式"2001-1-1"
dateTo : 结束时间
productType : 数组(0水体1土地利用2植被指数3冰雪4烟雾5水华6雾霾7赤潮，如果没有该参数则检测所有的)
###返回
{
	certified : true,
	productList : [
		{
			type : "product",
			productType : [0, 1, 3, 7],
			id : 0,
			lat0 : -5,
			lat1 : 5,
			lon0 : 30,
			lon1 : 40,
			fileName : "x.tif",
			url : "/data/....",
			thumbUrl : "/data/...",
		},
		...
	]
}


##querySourceProduct.php
###参数
userName : 用户名
passwords : 密码
lonFrom : 经度左
lonTo : 经度右
latFrom : 纬度下
latTo : 纬度上
dateFrom : 开始时间，格式"2001-1-1"
dateTo : 结束时间
productType : 数组(如果没有该参数则检测所有的)
###返回
{
	certified : true,
	sourceList : [...],
	productList : [...]
}


##submitOrder.php
###参数
userName : 用户名
passwords : 密码
order : JSON字符串（全部使用单引号）
[
	{
		sourceId : 0,
		type : [0, 1, 3]
	}
]
###返回
{
	certified : true,
	productList : [...]
}


##queryOrder.php
###参数
userName : 用户名
passwords : 密码
###
{
	certification : true,
	orderList : [
		[
			productList : [...]
		],
		...
	]
}